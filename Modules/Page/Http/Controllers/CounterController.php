<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\HomePage;
use Modules\Page\Entities\HomePageTranslation;
use Modules\Page\Http\Requests\CounterRequest;
use Image, File, Str;


class CounterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $counter = HomePage::first();
        $translate = HomePageTranslation::where(['home_page_id' => $counter->id, 'lang_code' => $request->lang_code])->first();

        return view('page::section.counter', compact('counter','translate'));
    }

    public function update(CounterRequest $request)
    {

        $translate = HomePageTranslation::where(['id' => $request->translate_id])->first();

        $translate->counter_title1 = $request->counter_title1;
        $translate->counter_title2 = $request->counter_title2;
        $translate->counter_title3 = $request->counter_title3;
        $translate->save();

        $counter = HomePage::first();

        if($request->lang_code == admin_lang()){
            $counter->counter_qty1 = $request->counter_qty1;
            $counter->counter_qty2 = $request->counter_qty2;
            $counter->counter_qty3 = $request->counter_qty3;
            $counter->save();
        }

        if($request->counter_icon1){
            $old_image = $counter->counter_icon1;
            $image_name = 'counter'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->counter_icon1)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $counter->counter_icon1 = $image_name;
            $counter->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        if($request->counter_icon2){
            $old_image = $counter->counter_icon2;
            $image_name = 'counter'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->counter_icon2)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $counter->counter_icon2 = $image_name;
            $counter->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        if($request->counter_icon3){
            $old_image = $counter->counter_icon3;
            $image_name = 'counter'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->counter_icon3)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $counter->counter_icon3 = $image_name;
            $counter->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

}
