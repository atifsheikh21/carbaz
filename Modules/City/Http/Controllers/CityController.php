<?php

namespace Modules\City\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Car\Entities\Car;
use Modules\City\Entities\City;
use Illuminate\Routing\Controller;
use Modules\Country\Entities\Country;
use Modules\Language\Entities\Language;
use Modules\City\Entities\CityTranslation;

use Modules\City\Http\Requests\CityRequest;
use Illuminate\Contracts\Support\Renderable;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $cities = City::with('translate')->latest()->get();

        return view('city::index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $countries = Country::latest()->get();

        return view('city::create', [
            'countries' => $countries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CityRequest $request)
    {
        $city = new City();
        $city->country_id = $request->country_id;
        $city->save();

        $languages = Language::all();
        foreach($languages as $language){
            $city_translation = new CityTranslation();
            $city_translation->lang_code = $language->lang_code;
            $city_translation->city_id = $city->id;
            $city_translation->name = $request->name;
            $city_translation->save();
        }

        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.city.edit', ['city' => $city->id, 'lang_code' => admin_lang()])->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request ,$id)
    {
        $city = City::findOrFail($id);
        $city_translate = CityTranslation::where(['city_id' => $id, 'lang_code' => $request->lang_code])->first();

        $countries = Country::latest()->get();

        return view('city::edit', compact('city','city_translate', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CityRequest $request, $id)
    {


        $city = City::findOrFail($id);

        if($request->lang_code == admin_lang()){
            $city->country_id = $request->country_id;
            $city->save();
        }


        $city_translation = CityTranslation::findOrFail($request->translate_id);
        $city_translation->name = $request->name;
        $city_translation->save();

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
        $car_qty = Car::where('city_id', $id)->count();

        if($car_qty > 0){
            $notification = trans('translate.Multiple listing created under it, so you can not delete it');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $city = City::find($id);
        $city->delete();

        CityTranslation::where('city_id', $id)->delete();

        $notification= trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.city.index')->with($notification);
    }

    public function assign_language($lang_code){
        $city_translates = CityTranslation::where('lang_code', admin_lang())->get();
        foreach($city_translates as $city_translate){
            $city_translation = new CityTranslation();
            $city_translation->lang_code = $lang_code;
            $city_translation->city_id = $city_translate->city_id;
            $city_translation->name = $city_translate->name;
            $city_translation->save();
        }
    }

}
