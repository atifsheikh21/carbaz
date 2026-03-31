<?php

namespace Modules\Car\Http\Controllers;

use App\Models\CarPart;
use App\Models\CarPartGallery;
use App\Models\CarPartTranslation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Brand\Entities\Brand;
use Modules\Car\Http\Requests\CarPartRequest;
use Modules\City\Entities\City;
use Modules\Country\Entities\Country;
use Modules\Language\Entities\Language;

class CarPartController extends Controller
{
    public function index()
    {
        $carParts = CarPart::latest()->get();

        return view('car::car_parts.index', [
            'carParts' => $carParts,
        ]);
    }

    public function create()
    {
        $brands = Brand::with('translate')->where('status', 'enable')->get();
        $users = User::where('status', 'enable')->get();
        $ireland = Country::whereRaw('LOWER(name) = ?', ['ireland'])->first();
        $cities = collect();
        if ($ireland) {
            $cities = City::with('translate')->where('country_id', $ireland->id)->get();
        }

        return view('car::car_parts.create', [
            'brands' => $brands,
            'users' => $users,
            'ireland' => $ireland,
            'cities' => $cities,
        ]);
    }

    public function store(CarPartRequest $request): RedirectResponse
    {
        $carPart = new CarPart();
        $carPart->agent_id = $request->agent_id;
        $carPart->brand_id = $request->brand_id;
        $carPart->city_id = $request->city_id;
        $carPart->slug = $this->generateUniqueSlug($request->title, (int) $request->agent_id);
        $carPart->condition = $request->condition;
        $carPart->regular_price = $request->regular_price;
        $carPart->offer_price = null;
        $carPart->part_number = $request->part_number;
        $carPart->compatibility = $request->compatibility;

        $carPart->approved_by_admin = 'approved';
        $carPart->status = 'enable';
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

        $notification = trans('translate.Created Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.car-part.edit', $carPart->id)->with($notification);
    }

    public function edit(Request $request, int $id)
    {
        $carPart = CarPart::with('galleries')->findOrFail($id);

        $brands = Brand::with('translate')->where('status', 'enable')->get();
        $users = User::where('status', 'enable')->get();
        $ireland = Country::whereRaw('LOWER(name) = ?', ['ireland'])->first();
        $cities = collect();
        if ($ireland) {
            $cities = City::with('translate')->where('country_id', $ireland->id)->get();
        }

        $translation = CarPartTranslation::where('car_part_id', $carPart->id)->where('lang_code', $request->get('lang_code', admin_lang()))->first();

        return view('car::car_parts.edit', [
            'carPart' => $carPart,
            'brands' => $brands,
            'users' => $users,
            'translation' => $translation,
            'lang_code' => $request->get('lang_code', admin_lang()),
            'ireland' => $ireland,
            'cities' => $cities,
        ]);
    }

    public function update(CarPartRequest $request, int $id): RedirectResponse
    {
        $carPart = CarPart::findOrFail($id);

        $carPart->agent_id = $request->agent_id;
        $carPart->brand_id = $request->brand_id;
        $carPart->city_id = $request->city_id;
        $carPart->slug = $this->generateUniqueSlug($request->title, (int) $request->agent_id, $carPart->id);
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
            'lang_code' => $request->get('lang_code', admin_lang()),
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
        $carPart = CarPart::findOrFail($id);
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
