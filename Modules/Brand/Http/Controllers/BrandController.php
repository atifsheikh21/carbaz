<?php

namespace Modules\Brand\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Brand\Entities\Brand;
use Modules\Brand\Entities\BrandTranslation;
use Modules\Brand\Http\Requests\BrandRequest;
use Modules\Language\Entities\Language;
use Modules\Car\Entities\Car;

use Image, File, Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $brands = Brand::with('translate')->latest()->get();

        return view('brand::index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('brand::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(BrandRequest $request)
    {
        $brand = new Brand();

        if($request->image){
            $image_name = 'brand-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/custom-images/'.$image_name;
            Image::make($request->image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $brand->image = $image_name;
        }

        if ($request->hasFile('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/custom-images');
            $brand->image = $image_path;
        }

        $brand->slug = $request->slug;
        $brand->status = $request->status ? 'enable' : 'disable';
        $brand->save();

        $languages = Language::all();
        foreach($languages as $language){
            $brand_translation = new BrandTranslation();
            $brand_translation->lang_code = $language->lang_code;
            $brand_translation->brand_id = $brand->id;
            $brand_translation->name = $request->name;
            $brand_translation->save();
        }

        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.brand.edit', ['brand' => $brand->id, 'lang_code' => admin_lang()])->with($notification);


    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request ,$id)
    {

        $brand = Brand::findOrFail($id);
        $brand_translate = BrandTranslation::where(['brand_id' => $id, 'lang_code' => $request->lang_code])->first();

        return view('brand::edit', ['brand' => $brand, 'brand_translate' => $brand_translate]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(BrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);

        if($request->lang_code == admin_lang()){

            if ($request->hasFile('image')) {
                $image_path = uploadFile($request->file('image'), 'uploads/custom-images', $brand->image);
                $brand->image = $image_path;
                $brand->save();
            }

            $brand->slug = $request->slug;
            $brand->status = $request->status ? 'enable' : 'disable';
            $brand->save();

            $brand_translation = BrandTranslation::findOrFail($request->translate_id);
            $brand_translation->name = $request->name;
            $brand_translation->save();

        }else{

            $brand_translation = BrandTranslation::findOrFail($request->translate_id);
            $brand_translation->name = $request->name;
            $brand_translation->save();
        }

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
        $car_qty = Car::where('brand_id', $id)->count();

        if($car_qty > 0){
            $notification = trans('translate.Multiple listing created under it, so you can not delete it');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $brand = Brand::find($id);
        $old_image = $brand->image;
        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        $brand->delete();

        BrandTranslation::where('brand_id', $id)->delete();

        $notification= trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.brand.index')->with($notification);
    }

    public function assign_language($lang_code){
        $brand_translates = BrandTranslation::where('lang_code', admin_lang())->get();
        foreach($brand_translates as $brand_translate){
            $new_brand_translate = new BrandTranslation();
            $new_brand_translate->lang_code = $lang_code;
            $new_brand_translate->brand_id = $brand_translate->brand_id;
            $new_brand_translate->name = $brand_translate->name;
            $new_brand_translate->save();
        }
    }
}
