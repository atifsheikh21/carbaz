<?php

namespace Modules\Feature\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Feature\Entities\Feature;
use Modules\Feature\Entities\FeatureTranslation;
use Modules\Language\Entities\Language;
use Modules\Car\Entities\Car;
use Modules\Feature\Http\Requests\FeatureRequest;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $features = Feature::with('translate')->latest()->get();

        return view('feature::index', compact('features'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('feature::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(FeatureRequest $request)
    {
        $feature = new Feature();
        $feature->save();

        $languages = Language::all();
        foreach($languages as $language){
            $feature_translation = new FeatureTranslation();
            $feature_translation->lang_code = $language->lang_code;
            $feature_translation->feature_id = $feature->id;
            $feature_translation->name = $request->name;
            $feature_translation->save();
        }

        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.feature.edit', ['feature' => $feature->id, 'lang_code' => admin_lang()])->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request, $id)
    {
        $feature = Feature::findOrFail($id);
        $feature_translate = FeatureTranslation::where(['feature_id' => $id, 'lang_code' => $request->lang_code])->first();

        return view('feature::edit', compact('feature', 'feature_translate'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(FeatureRequest $request, $id)
    {
        $feature_translate = FeatureTranslation::findOrFail($request->translate_id);
        $feature_translate->name = $request->name;
        $feature_translate->save();

        $notification= trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {

        $car_qty = Car::whereJsonContains('features', $id)->count();

        if($car_qty > 0){
            $notification = trans('translate.Multiple listing created under it, so you can not delete it');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $feature = Feature::find($id);
        $feature->delete();

        FeatureTranslation::where('feature_id', $id)->delete();

        $notification= trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.feature.index')->with($notification);
    }

    public function assign_language($lang_code){
        $feature_translates = FeatureTranslation::where('lang_code', admin_lang())->get();
        foreach($feature_translates as $feature_translate){
            $translate = new FeatureTranslation();
            $translate->feature_id = $feature_translate->feature_id;
            $translate->lang_code = $lang_code;
            $translate->name = $feature_translate->name;
            $translate->save();
        }
    }


}
