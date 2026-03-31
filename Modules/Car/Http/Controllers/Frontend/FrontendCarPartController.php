<?php

namespace Modules\Car\Http\Controllers\Frontend;

use App\Models\CarPart;
use App\Models\CarPartGallery;
use App\Models\IndividualAdPayment;
use App\Models\CarPartTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\GeneralSetting\Entities\Setting;
use Modules\Brand\Entities\Brand;
use Modules\Car\Http\Requests\CarPartRequest;
use Modules\City\Entities\City;
use Modules\Country\Entities\Country;
use Modules\Language\Entities\Language;

class FrontendCarPartController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();

        if ($user?->is_dealer && !(bool) $user?->is_part_seller) {
            $notification = trans('translate.Access denied');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $status = $request->get('status', 'all');

        $baseQuery = CarPart::where('agent_id', $user->id);

        $totalCount = (clone $baseQuery)->count();
        $activeCount = (clone $baseQuery)->where('approved_by_admin', 'approved')->count();
        $inactiveCount = max(0, $totalCount - $activeCount);

        $carPartsQuery = clone $baseQuery;
        if ($status === 'active') {
            $carPartsQuery->where('approved_by_admin', 'approved');
        } elseif ($status === 'inactive') {
            $carPartsQuery->where('approved_by_admin', '!=', 'approved');
        }

        $carParts = $carPartsQuery->with('translations')->latest()->paginate(15)->appends($request->query());

        return view('car::frontend.car_parts.index', [
            'carParts' => $carParts,
            'user' => $user,
            'status' => $status,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
        ]);
    }

    public function create()
    {
        $user = Auth::guard('web')->user();

        if ($user?->is_dealer && !(bool) $user?->is_part_seller) {
            $notification = trans('translate.Access denied');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        if ($user && !$user->is_dealer) {
            $setting = Setting::first();
            $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';

            $pendingIndividualPaymentQuery = IndividualAdPayment::where('user_id', $user->id)
                ->where('status', 'success')
                ->whereNull('consumed_at');

            if (!$feeFreeModeEnabled) {
                $pendingIndividualPaymentQuery->where('payment_method', '!=', 'Free');
            }

            $pendingIndividualPayment = $pendingIndividualPaymentQuery
                ->orderBy('id', 'asc')
                ->first();

            if (!$pendingIndividualPayment) {
                if ($feeFreeModeEnabled) {
                    IndividualAdPayment::create([
                        'user_id' => $user->id,
                        'car_id' => null,
                        'amount' => 0,
                        'currency' => 'EUR',
                        'payment_method' => 'Free',
                        'status' => 'success',
                        'transaction_id' => 'free_period',
                        'consumed_at' => null,
                    ]);
                } else {
                    $notification = trans('translate.Please complete payment to post your ad');
                    $notification = ['messege' => $notification, 'alert-type' => 'error'];
                    return redirect()->route('user.select-car-purpose')->with($notification);
                }
            }
        }

        $brands = Brand::with('translate')->where('status', 'enable')->get();
        $ireland = Country::whereRaw('LOWER(name) = ?', ['ireland'])->first();
        $cities = collect();
        if ($ireland) {
            $cities = City::with('translate')->where('country_id', $ireland->id)->get();
        }

        return view('car::frontend.car_parts.create', [
            'brands' => $brands,
            'ireland' => $ireland,
            'cities' => $cities,
        ]);
    }

    public function store(CarPartRequest $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if ($user?->is_dealer && !(bool) $user?->is_part_seller) {
            $notification = trans('translate.Access denied');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $pendingIndividualPayment = null;
        if ($user && !$user->is_dealer) {
            $setting = Setting::first();
            $feeFreeModeEnabled = $setting && $setting->fee_free_mode == 'enable';

            $pendingIndividualPaymentQuery = IndividualAdPayment::where('user_id', $user->id)
                ->where('status', 'success')
                ->whereNull('consumed_at');

            if (!$feeFreeModeEnabled) {
                $pendingIndividualPaymentQuery->where('payment_method', '!=', 'Free');
            }

            $pendingIndividualPayment = $pendingIndividualPaymentQuery
                ->orderBy('id', 'asc')
                ->first();

            if (!$pendingIndividualPayment) {
                if ($feeFreeModeEnabled) {
                    $pendingIndividualPayment = IndividualAdPayment::create([
                        'user_id' => $user->id,
                        'car_id' => null,
                        'amount' => 0,
                        'currency' => 'EUR',
                        'payment_method' => 'Free',
                        'status' => 'success',
                        'transaction_id' => 'free_period',
                        'consumed_at' => null,
                    ]);
                } else {
                    $notification = trans('translate.Please complete payment to post your ad');
                    $notification = ['messege' => $notification, 'alert-type' => 'error'];
                    return redirect()->route('user.select-car-purpose')->with($notification);
                }
            }
        }

        $carPart = new CarPart();
        $carPart->agent_id = $user->id;
        $carPart->brand_id = $request->brand_id;
        $carPart->city_id = $request->city_id;
        $carPart->slug = $this->generateUniqueSlug($request->title, $user->id);
        $carPart->condition = $request->condition;
        $carPart->regular_price = $request->regular_price;
        $carPart->offer_price = null;
        $carPart->part_number = $request->part_number;
        $carPart->compatibility = $request->compatibility;

        $carPart->save();

        $uploadedImages = $request->file('images', []);
        foreach ($uploadedImages as $index => $image) {
            if (!$image) {
                continue;
            }

            $imagePath = uploadFile($image, 'uploads/custom-images');

            if ($index === 0 && empty($carPart->thumb_image)) {
                $carPart->thumb_image = $imagePath;
                $carPart->save();
            }

            $gallery = new CarPartGallery();
            $gallery->car_part_id = $carPart->id;
            $gallery->image = $imagePath;
            $gallery->save();
        }

        $languages = Language::all();
        foreach ($languages as $language) {
            $t = new CarPartTranslation();
            $t->car_part_id = $carPart->id;
            $t->lang_code = $language->lang_code;
            $t->title = $request->title;
            $t->description = $request->description;
            $t->seo_title = $request->title;
            $t->seo_description = $request->title;
            $t->save();
        }

        if ($pendingIndividualPayment) {
            $pendingIndividualPayment->consumed_at = now();
            $pendingIndividualPayment->save();
        }

        $notification = trans('translate.Created Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('user.car-part.edit', $carPart->id)->with($notification);
    }

    public function edit(int $id)
    {
        $user = Auth::guard('web')->user();

        if ($user?->is_dealer && !(bool) $user?->is_part_seller) {
            $notification = trans('translate.Access denied');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $carPart = CarPart::with('galleries')->where('agent_id', $user->id)->findOrFail($id);

        $brands = Brand::with('translate')->where('status', 'enable')->get();
        $ireland = Country::whereRaw('LOWER(name) = ?', ['ireland'])->first();
        $cities = collect();
        if ($ireland) {
            $cities = City::with('translate')->where('country_id', $ireland->id)->get();
        }
        $translation = CarPartTranslation::where('car_part_id', $carPart->id)->where('lang_code', admin_lang())->first();

        return view('car::frontend.car_parts.edit', [
            'carPart' => $carPart,
            'brands' => $brands,
            'translation' => $translation,
            'ireland' => $ireland,
            'cities' => $cities,
        ]);
    }

    public function update(CarPartRequest $request, int $id): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if ($user?->is_dealer && !(bool) $user?->is_part_seller) {
            $notification = trans('translate.Access denied');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->route('user.select-car-purpose')->with($notification);
        }

        $carPart = CarPart::where('agent_id', $user->id)->findOrFail($id);

        $carPart->brand_id = $request->brand_id;
        $carPart->city_id = $request->city_id;
        $carPart->slug = $this->generateUniqueSlug($request->title, $user->id, $carPart->id);
        $carPart->condition = $request->condition;
        $carPart->regular_price = $request->regular_price;
        $carPart->offer_price = null;
        $carPart->part_number = $request->part_number;
        $carPart->compatibility = $request->compatibility;

        $carPart->save();

        $uploadedImages = $request->file('images', []);
        foreach ($uploadedImages as $index => $image) {
            if (!$image) {
                continue;
            }

            $imagePath = uploadFile($image, 'uploads/custom-images');

            if ($index === 0 && empty($carPart->thumb_image)) {
                $carPart->thumb_image = $imagePath;
                $carPart->save();
            }

            $gallery = new CarPartGallery();
            $gallery->car_part_id = $carPart->id;
            $gallery->image = $imagePath;
            $gallery->save();
        }

        $translation = CarPartTranslation::firstOrNew([
            'car_part_id' => $carPart->id,
            'lang_code' => admin_lang(),
        ]);
        $translation->title = $request->title;
        $translation->description = $request->description;
        $translation->seo_title = $request->title;
        $translation->seo_description = $request->title;
        $translation->save();

        $notification = trans('translate.Updated Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = Auth::guard('web')->user();
        $carPart = CarPart::where('agent_id', $user->id)->findOrFail($id);
        $carPart->delete();

        $notification = trans('translate.Deleted Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    private function generateUniqueSlug(string $title, int $userId, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        if ($baseSlug === '') {
            $baseSlug = 'car-part';
        }

        $slug = $baseSlug . '-' . $userId;
        $counter = 1;

        while (CarPart::when($ignoreId, function ($query) use ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        })->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $userId . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
