<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\HomePage;
use Modules\Page\Entities\HomePageTranslation;
use Modules\Page\Http\Requests\WorkingStepRequest;
use Image, File, Str;

class WorkingStepController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $working_step = HomePage::first();
        $translate = HomePageTranslation::where(['home_page_id' => $working_step->id, 'lang_code' => $request->lang_code])->first();

        return view('page::section.working_step', compact('working_step','translate'));
    }

    public function update(WorkingStepRequest $request)
    {

        $translate = HomePageTranslation::where(['id' => $request->translate_id])->first();

        $translate->working_step_title1 = $request->working_step_title1;
        $translate->working_step_des1 = $request->working_step_des1;

        $translate->working_step_title2 = $request->working_step_title2;
        $translate->working_step_des2 = $request->working_step_des2;

        $translate->working_step_title3 = $request->working_step_title3;
        $translate->working_step_des3 = $request->working_step_des3;

        $translate->working_step_title4 = $request->working_step_title4;
        $translate->working_step_des4 = $request->working_step_des4;

        $translate->save();

        $working_step = HomePage::first();

        if($request->working_step_icon1){
            $old_image = $working_step->working_step_icon1;
            $image_name = 'video-bg-image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->working_step_icon1)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $working_step->working_step_icon1 = $image_name;
            $working_step->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        if($request->working_step_icon2){
            $old_image = $working_step->working_step_icon2;
            $image_name = 'video-bg-image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->working_step_icon2)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $working_step->working_step_icon2 = $image_name;
            $working_step->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        if($request->working_step_icon3){
            $old_image = $working_step->working_step_icon3;
            $image_name = 'video-bg-image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->working_step_icon3)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $working_step->working_step_icon3 = $image_name;
            $working_step->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        if($request->working_step_icon4){
            $old_image = $working_step->working_step_icon4;
            $image_name = 'video-bg-image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->working_step_icon4)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $working_step->working_step_icon4 = $image_name;
            $working_step->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }


}
