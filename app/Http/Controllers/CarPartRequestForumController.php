<?php

namespace App\Http\Controllers;

use App\Models\CarPartRequest;
use App\Models\CarPartRequestReply;
use App\Models\CarPartRequestVote;
use App\Models\CarPartRequestReplyVote;
use App\Models\User;
use App\Jobs\SendForumHelperNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarPartRequestForumController extends Controller
{
    private array $categories = ['Engine', 'Electrical', 'Body', 'Radiator', 'Suspension', 'Transmission', 'Interior', 'Exterior', 'Wheels', 'Other'];

    public function index(Request $request)
    {
        $sort = (string) $request->query('sort', 'latest');
        $search = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));

        $requests = CarPartRequest::with('user')
            ->withCount('replies')
            ->withMax('replies', 'created_at')
            ->when($category !== '', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('part_description', 'like', "%{$search}%")
                        ->orWhere('car_make', 'like', "%{$search}%")
                        ->orWhere('car_model', 'like', "%{$search}%")
                        ->orWhere('car_year', 'like', "%{$search}%");
                });
            });

        if ($sort === 'oldest') {
            $requests = $requests->orderBy('id', 'asc');
        } elseif ($sort === 'most_replied') {
            $requests = $requests->orderBy('replies_count', 'desc')->orderBy('id', 'desc');
        } else {
            $requests = $requests->orderBy('id', 'desc');
        }

        $requests = $requests->paginate(15)->withQueryString();

        return view('car_part_requests.index', [
            'requests' => $requests,
            'sort' => $sort,
            'search' => $search,
            'category' => $category,
            'categories' => $this->categories,
        ]);
    }

    public function create()
    {
        return view('car_part_requests.create', [
            'categories' => $this->categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'part_description' => ['required', 'string'],
            'car_make' => ['nullable', 'string', 'max:255'],
            'car_model' => ['nullable', 'string', 'max:255'],
            'car_year' => ['nullable', 'string', 'max:255'],
            'additional_notes' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);

        $user = Auth::guard('web')->user();

        $validated['user_id'] = $user->id;
        $validated['status'] = 'open';

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadForumRequestImage($request->file('image'));
        }

        $requestModel = CarPartRequest::create($validated);

        SendForumHelperNotificationJob::dispatchAfterResponse($requestModel->id);

        $notification = trans('translate.Request submitted successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('car-part-requests.show', $requestModel->id)->with($notification);
    }

    public function show($id)
    {
        $requestModel = CarPartRequest::with(['user', 'replies.user', 'replies.votes', 'votes'])
            ->findOrFail($id);

        $relatedQuestions = CarPartRequest::where('id', '!=', $id)
            ->where(function ($q) use ($requestModel) {
                $q->where('car_make', $requestModel->car_make)
                  ->orWhere('car_model', $requestModel->car_model);
            })
            ->latest()
            ->limit(5)
            ->get();

        $userId = Auth::guard('web')->id();
        $myRequestVote = $userId ? CarPartRequestVote::where('car_part_request_id', $id)->where('user_id', $userId)->value('type') : null;
        $myReplyVotes = $userId ? CarPartRequestReplyVote::where('user_id', $userId)
            ->whereIn('car_part_request_reply_id', $requestModel->replies->pluck('id'))
            ->pluck('type', 'car_part_request_reply_id') : collect();

        return view('car_part_requests.show', [
            'request'        => $requestModel,
            'relatedQuestions' => $relatedQuestions,
            'myRequestVote'  => $myRequestVote,
            'myReplyVotes'   => $myReplyVotes,
        ]);
    }

    public function reply(Request $request, $id)
    {
        $requestModel = CarPartRequest::findOrFail($id);

        $validated = $request->validate([
            'message'     => ['required', 'string'],
            'offer_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = Auth::guard('web')->user();

        $reply = new CarPartRequestReply();
        $reply->car_part_request_id = $requestModel->id;
        $reply->user_id  = $user->id;
        $reply->message  = $validated['message'];
        $reply->offer_price = $validated['offer_price'] ?? null;
        $reply->save();

        $notification = ['messege' => trans('translate.Reply submitted successfully'), 'alert-type' => 'success'];
        return redirect()->back()->with($notification);
    }

    public function editRequest($id)
    {
        $requestModel = CarPartRequest::findOrFail($id);
        $this->authorizeOwner($requestModel->user_id);
        return view('car_part_requests.edit_request', ['requestModel' => $requestModel]);
    }

    public function updateRequest(Request $request, $id)
    {
        $requestModel = CarPartRequest::findOrFail($id);
        $this->authorizeOwner($requestModel->user_id);

        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'category'         => ['required', 'string', 'max:255'],
            'part_description' => ['required', 'string'],
            'car_make'         => ['nullable', 'string', 'max:255'],
            'car_model'        => ['nullable', 'string', 'max:255'],
            'car_year'         => ['nullable', 'string', 'max:255'],
            'additional_notes' => ['nullable', 'string'],
            'image'            => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadForumRequestImage($request->file('image'), $requestModel->image);
        }

        $requestModel->update($validated);

        $notification = ['messege' => 'Post updated successfully.', 'alert-type' => 'success'];
        return redirect()->route('car-part-requests.show', $requestModel->id)->with($notification);
    }

    public function deleteRequest($id)
    {
        $requestModel = CarPartRequest::findOrFail($id);
        $this->authorizeOwner($requestModel->user_id);

        if ($requestModel->image) {
            deleteFile($requestModel->image);
        }

        $requestModel->replies()->delete();
        $requestModel->votes()->delete();
        $requestModel->delete();

        $notification = ['messege' => 'Post deleted successfully.', 'alert-type' => 'success'];
        return redirect()->route('car-part-requests.index')->with($notification);
    }

    public function editReply($id)
    {
        $reply = CarPartRequestReply::findOrFail($id);
        $this->authorizeOwner($reply->user_id);
        return response()->json(['message' => $reply->message, 'offer_price' => $reply->offer_price]);
    }

    public function updateReply(Request $request, $id)
    {
        $reply = CarPartRequestReply::findOrFail($id);
        $this->authorizeOwner($reply->user_id);

        $validated = $request->validate([
            'message'     => ['required', 'string'],
            'offer_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $reply->update($validated);

        $notification = ['messege' => 'Reply updated successfully.', 'alert-type' => 'success'];
        return redirect()->back()->with($notification);
    }

    public function deleteReply($id)
    {
        $reply = CarPartRequestReply::findOrFail($id);
        $this->authorizeOwner($reply->user_id);

        $reply->votes()->delete();
        $reply->delete();

        $notification = ['messege' => 'Reply deleted successfully.', 'alert-type' => 'success'];
        return redirect()->back()->with($notification);
    }

    public function voteRequest(Request $request, $id)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }

        $requestModel = CarPartRequest::findOrFail($id);
        $user = Auth::guard('web')->user();
        $type = $request->input('type') === 'down' ? 'down' : 'up';

        $existing = CarPartRequestVote::where('car_part_request_id', $id)->where('user_id', $user->id)->first();
        if ($existing) {
            if ($existing->type === $type) {
                $existing->delete();
            } else {
                $existing->update(['type' => $type]);
            }
        } else {
            CarPartRequestVote::create(['car_part_request_id' => $id, 'user_id' => $user->id, 'type' => $type]);
        }

        return redirect()->back();
    }

    public function voteReply(Request $request, $id)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }

        $reply = CarPartRequestReply::findOrFail($id);
        $user  = Auth::guard('web')->user();
        $type  = $request->input('type') === 'down' ? 'down' : 'up';

        $existing = CarPartRequestReplyVote::where('car_part_request_reply_id', $id)->where('user_id', $user->id)->first();
        if ($existing) {
            if ($existing->type === $type) {
                $existing->delete();
            } else {
                $existing->update(['type' => $type]);
            }
        } else {
            CarPartRequestReplyVote::create(['car_part_request_reply_id' => $id, 'user_id' => $user->id, 'type' => $type]);
        }

        return redirect()->back();
    }

    public function unsubscribeHelper(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $user->is_forum_helper = false;
        $user->save();

        return view('car_part_requests.helper_unsubscribed');
    }

    private function uploadForumRequestImage($file, ?string $oldFile = null): string
    {
        $directory = 'uploads/car-part-requests';
        $extension = strtolower((string) $file->getClientOriginalExtension());
        $fileName = 'part-help-' . time() . '-' . random_int(1000, 9999) . '.' . $extension;
        $filePath = $directory . '/' . $fileName;

        if (env('FILESYSTEM_DISK') === 's3') {
            $storedPath = \Illuminate\Support\Facades\Storage::disk('s3')->put($directory, $file);
            if ($oldFile) {
                \Illuminate\Support\Facades\Storage::disk('s3')->delete($oldFile);
            }

            return $storedPath;
        }

        $destinationPath = public_path($directory);
        if (! is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        if (! is_dir($destinationPath) || ! is_writable($destinationPath)) {
            throw new \RuntimeException('Upload directory is not writable: ' . $destinationPath);
        }

        $file->move($destinationPath, $fileName);

        try {
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $absolutePath = public_path($filePath);
                $manager = new \Intervention\Image\ImageManager(['driver' => 'gd']);
                $image = $manager->make($absolutePath);
                if (method_exists($image, 'orientate')) {
                    $image->orientate();
                }
                $image->resize(1200, 900, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                if ($extension === 'webp') {
                    $jpgName = pathinfo($fileName, PATHINFO_FILENAME) . '.jpg';
                    $jpgPath = $directory . '/' . $jpgName;
                    $image->encode('jpg', 84)->save(public_path($jpgPath));
                    @unlink($absolutePath);
                    $filePath = $jpgPath;
                } else {
                    $image->save($absolutePath, 84);
                }
            }
        } catch (\Throwable $e) {
        }

        if ($oldFile) {
            deleteFile($oldFile);
        }

        return $filePath;
    }

    private function authorizeOwner($ownerId)
    {
        $user = Auth::guard('web')->user();
        if (!$user || (int) $user->id !== (int) $ownerId) {
            abort(403);
        }
    }
}
