<?php

namespace Modules\Testimonial\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Testimonial\Entities\Testimonial;
use Modules\Testimonial\Entities\TestimonialTranslation;
use Modules\Language\Entities\Language;
use Modules\Testimonial\Http\Requests\TestimonialRequest;
use File, Image, Str;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('translate')->orderBy('id','desc')->latest()->get();

        return view('testimonial::index', compact('testimonials'));
    }

    public function create()
    {
        return view('testimonial::create');
    }

    public function store(TestimonialRequest $request)
    {
        $testimonial = new Testimonial();
        if($request->image){
            $image_path = uploadFile($request->image, 'uploads/custom-images');
            $testimonial->image = $image_path;
        }

        $testimonial->status = $request->status ? 'active' : 'inactive';
        $testimonial->save();

        $languages = Language::all();
        foreach($languages as $language){
            $translate = new TestimonialTranslation();
            $translate->lang_code = $language->lang_code;
            $translate->testimonial_id = $testimonial->id;
            $translate->name = $request->name;
            $translate->designation = $request->designation;
            $translate->comment = $request->comment;
            $translate->save();
        }

        $notification = trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.testimonial.edit', ['testimonial' => $testimonial,'lang_code' => admin_lang()] )->with($notification);
    }


    public function edit(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $translate = TestimonialTranslation::where(['testimonial_id' => $id, 'lang_code' => $request->lang_code])->first();

        return view('testimonial::edit', compact('testimonial','translate'));
    }


    public function update(TestimonialRequest $request, $id)
    {

        $testimonial = Testimonial::findOrFail($id);

        if($request->lang_code == admin_lang()){
            $testimonial->status = $request->status ? 'active' : 'inactive';
            $testimonial->save();
        }

        if($request->image){
            $image_path = uploadFile($request->image, 'uploads/custom-images', $testimonial->image);
            $testimonial->image = $image_path;
            $testimonial->save();
        }


        $translate = TestimonialTranslation::where(['testimonial_id' => $id, 'lang_code' => $request->lang_code])->first();
        $translate->name = $request->name;
        $translate->designation = $request->designation;
        $translate->comment = $request->comment;
        $translate->save();

        $notification = trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function destroy($id)
    {
        $testimonial = Testimonial::find($id);
        $existing_image = $testimonial->image;

        TestimonialTranslation::where(['testimonial_id' => $id])->delete();

        if($existing_image){
            if(File::exists(public_path().'/'.$existing_image))unlink(public_path().'/'.$existing_image);
        }

        $testimonial->delete();

        $notification = trans('translate.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.testimonial.index')->with($notification);
    }

    public function assign_language($lang_code){
        $testi_translates = TestimonialTranslation::where('lang_code', admin_lang())->get();
        foreach($testi_translates as $testi_translate){
            $translate = new TestimonialTranslation();
            $translate->lang_code = $lang_code;
            $translate->testimonial_id = $testi_translate->testimonial_id;
            $translate->name = $testi_translate->name;
            $translate->designation = $testi_translate->designation;
            $translate->comment = $testi_translate->comment;
            $translate->save();
        }
    }
}
