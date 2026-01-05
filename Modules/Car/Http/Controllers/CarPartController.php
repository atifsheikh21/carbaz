<?php

namespace Modules\Car\Http\Controllers;

use App\Models\CarPart;
use App\Models\CarPartTranslation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Brand\Entities\Brand;
use Modules\Car\Http\Requests\CarPartRequest;
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

        return view('car::car_parts.create', [
            'brands' => $brands,
            'users' => $users,
        ]);
    }

    public function store(CarPartRequest $request): RedirectResponse
    {
        $carPart = new CarPart();
        $carPart->agent_id = $request->agent_id;
        $carPart->brand_id = $request->brand_id;
        $carPart->slug = $request->slug;
        $carPart->condition = $request->condition;
        $carPart->regular_price = $request->regular_price;
        $carPart->offer_price = $request->offer_price;
        $carPart->part_number = $request->part_number;
        $carPart->compatibility = $request->compatibility;

        if ($request->thumb_image) {
            $carPart->thumb_image = uploadFile($request->thumb_image, 'uploads/custom-images');
        }

        $carPart->approved_by_admin = 'approved';
        $carPart->status = 'enable';
        $carPart->save();

        $languages = Language::all();
        foreach ($languages as $language) {
            $t = new CarPartTranslation();
            $t->car_part_id = $carPart->id;
            $t->lang_code = $language->lang_code;
            $t->title = $request->title;
            $t->description = $request->description;
            $t->seo_title = $request->seo_title ? $request->seo_title : $request->title;
            $t->seo_description = $request->seo_description ? $request->seo_description : $request->title;
            $t->save();
        }

        $notification = trans('translate.Created Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.car-part.edit', $carPart->id)->with($notification);
    }

    public function edit(Request $request, int $id)
    {
        $carPart = CarPart::findOrFail($id);

        $brands = Brand::with('translate')->where('status', 'enable')->get();
        $users = User::where('status', 'enable')->get();

        $translation = CarPartTranslation::where('car_part_id', $carPart->id)->where('lang_code', $request->get('lang_code', admin_lang()))->first();

        return view('car::car_parts.edit', [
            'carPart' => $carPart,
            'brands' => $brands,
            'users' => $users,
            'translation' => $translation,
            'lang_code' => $request->get('lang_code', admin_lang()),
        ]);
    }

    public function update(CarPartRequest $request, int $id): RedirectResponse
    {
        $carPart = CarPart::findOrFail($id);

        $carPart->agent_id = $request->agent_id;
        $carPart->brand_id = $request->brand_id;
        $carPart->slug = $request->slug;
        $carPart->condition = $request->condition;
        $carPart->regular_price = $request->regular_price;
        $carPart->offer_price = $request->offer_price;
        $carPart->part_number = $request->part_number;
        $carPart->compatibility = $request->compatibility;

        if ($request->thumb_image) {
            $carPart->thumb_image = uploadFile($request->thumb_image, 'uploads/custom-images', $carPart->thumb_image);
        }

        $carPart->save();

        $translation = CarPartTranslation::firstOrNew([
            'car_part_id' => $carPart->id,
            'lang_code' => $request->get('lang_code', admin_lang()),
        ]);
        $translation->title = $request->title;
        $translation->description = $request->description;
        $translation->seo_title = $request->seo_title ? $request->seo_title : $request->title;
        $translation->seo_description = $request->seo_description ? $request->seo_description : $request->title;
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
}
