<?php

namespace App\Http\Controllers;

use App\Models\CarPartRequest;
use App\Models\CarPartRequestReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarPartRequestForumController extends Controller
{
    public function index()
    {
        $requests = CarPartRequest::with('user')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('car_part_requests.index', [
            'requests' => $requests,
        ]);
    }

    public function create()
    {
        return view('car_part_requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'part_description' => ['required', 'string'],
            'car_make' => ['nullable', 'string', 'max:255'],
            'car_model' => ['nullable', 'string', 'max:255'],
            'car_year' => ['nullable', 'string', 'max:255'],
            'additional_notes' => ['nullable', 'string'],
        ]);

        $user = Auth::guard('web')->user();

        $validated['user_id'] = $user->id;
        $validated['status'] = 'open';

        $requestModel = CarPartRequest::create($validated);

        $notification = trans('translate.Request submitted successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('car-part-requests.show', $requestModel->id)->with($notification);
    }

    public function show($id)
    {
        $requestModel = CarPartRequest::with(['user', 'replies.user'])
            ->findOrFail($id);

        return view('car_part_requests.show', [
            'request' => $requestModel,
        ]);
    }

    public function reply(Request $request, $id)
    {
        $requestModel = CarPartRequest::findOrFail($id);

        $validated = $request->validate([
            'message' => ['required', 'string'],
            'offer_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = Auth::guard('web')->user();

        $reply = new CarPartRequestReply();
        $reply->car_part_request_id = $requestModel->id;
        $reply->user_id = $user->id;
        $reply->message = $validated['message'];
        $reply->offer_price = $validated['offer_price'] ?? null;
        $reply->save();

        $notification = trans('translate.Reply submitted successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
}
