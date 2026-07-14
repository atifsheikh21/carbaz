<?php

namespace App\Http\Controllers;

use App\Helpers\MailHelper;
use App\Models\CarPart;
use App\Models\CarPartGallery;
use App\Models\CarPartTranslation;
use App\Models\User;
use App\Notifications\DeleteAccountVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Modules\Car\Entities\Car;
use Modules\Car\Entities\CarGallery;
use Modules\Car\Entities\CarTranslation;

class AccountDeletionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->only(['request']);
    }

    public function request(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $hash = sha1(strtolower((string) $user->email));
        $signedPath = URL::temporarySignedRoute(
            'user.account.delete.confirm',
            now()->addMinutes(60),
            ['user' => $user->id, 'hash' => $hash],
            false
        );
        $signedUrl = $request->getSchemeAndHttpHost().$signedPath;

        try {
            MailHelper::setMailConfig();
            $user->notify(new DeleteAccountVerification($signedUrl));
        } catch (\Throwable $e) {
            $notification = 'Unable to send verification email. Please contact support.';
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }

        $notification = 'We sent a verification link to your email. Click it to delete your account.';
        $notification = ['messege' => $notification, 'alert-type' => 'success'];
        return redirect()->back()->with($notification);
    }

    public function confirm(Request $request, User $user, string $hash): RedirectResponse
    {
        $expected = sha1(strtolower((string) $user->email));
        if (!hash_equals($expected, $hash)) {
            abort(403);
        }

        $filePaths = [];

        if (!empty($user->image)) {
            $filePaths[] = $user->image;
        }

        $cars = Car::query()->where('agent_id', $user->id)->get(['id', 'thumb_image', 'video_image']);
        $carIds = $cars->pluck('id')->all();

        foreach ($cars as $car) {
            if (!empty($car->thumb_image)) {
                $filePaths[] = $car->thumb_image;
            }
            if (!empty($car->video_image)) {
                $filePaths[] = $car->video_image;
            }
        }

        if (!empty($carIds)) {
            $carGalleryImages = CarGallery::query()->whereIn('car_id', $carIds)->pluck('image')->all();
            foreach ($carGalleryImages as $img) {
                if (!empty($img)) {
                    $filePaths[] = $img;
                }
            }
        }

        $carParts = CarPart::query()->where('agent_id', $user->id)->get(['id', 'thumb_image']);
        $carPartIds = $carParts->pluck('id')->all();

        foreach ($carParts as $part) {
            if (!empty($part->thumb_image)) {
                $filePaths[] = $part->thumb_image;
            }
        }

        if (!empty($carPartIds)) {
            $carPartGalleryImages = CarPartGallery::query()->whereIn('car_part_id', $carPartIds)->pluck('image')->all();
            foreach ($carPartGalleryImages as $img) {
                if (!empty($img)) {
                    $filePaths[] = $img;
                }
            }
        }

        DB::transaction(function () use ($user) {
            $carIds = Car::query()->where('agent_id', $user->id)->pluck('id')->all();
            if (!empty($carIds)) {
                CarGallery::query()->whereIn('car_id', $carIds)->delete();
                CarTranslation::query()->whereIn('car_id', $carIds)->delete();
            }

            Car::query()->where('agent_id', $user->id)->delete();

            $carPartIds = CarPart::query()->where('agent_id', $user->id)->pluck('id')->all();
            if (!empty($carPartIds)) {
                CarPartGallery::query()->whereIn('car_part_id', $carPartIds)->delete();
                CarPartTranslation::query()->whereIn('car_part_id', $carPartIds)->delete();
            }

            CarPart::query()->where('agent_id', $user->id)->delete();

            if (Schema::hasTable('wishlists')) {
                \App\Models\Wishlist::query()->where('user_id', $user->id)->delete();
            }

            $user->delete();
        });

        $filePaths = array_values(array_unique(array_filter($filePaths)));
        foreach ($filePaths as $path) {
            $path = str_replace('\\', '/', (string) $path);
            $path = ltrim($path, '/');

            if ($path === '' || str_contains($path, '..')) {
                continue;
            }

            $fullPath = public_path($path);
            if (File::exists($fullPath)) {
                @unlink($fullPath);
            }
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $notification = 'Your account has been deleted successfully.';
        $request->session()->flash('messege', $notification);
        $request->session()->flash('alert-type', 'success');
        $request->session()->save();

        return redirect()->route('home');
    }
}
