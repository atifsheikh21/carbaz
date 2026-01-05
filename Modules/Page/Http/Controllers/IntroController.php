<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\HomePage;
use Modules\Page\Entities\HomePageTranslation;
use Modules\Page\Http\Requests\IntroRequest;
use Image, File, Str;

class IntroController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $home1_intro = HomePage::first();
        $translate = HomePageTranslation::where(['home_page_id' => $home1_intro->id, 'lang_code' => $request->lang_code])->first();

        return view('page::section.home1_intro', compact('home1_intro','translate'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function update(IntroRequest $request)
    {

        $translate = HomePageTranslation::where(['id' => $request->translate_id])->first();
        $translate->home1_intro_short_title = $request->short_title;
        $translate->home1_intro_title = $request->intro_title;
        $translate->save();

        $home1_intro = HomePage::first();

        if($request->home1_intro_image){
            $image_path = uploadFile($request->home1_intro_image, 'uploads/website-images', $home1_intro->home1_intro_image);
            $home1_intro->home1_intro_image = $image_path;
            $home1_intro->save();
        }

        if($request->home1_intro_bg){
            $image_path = uploadFile($request->home1_intro_bg, 'uploads/website-images', $home1_intro->home1_intro_bg);
            $home1_intro->home1_intro_bg = $image_path;
            $home1_intro->save();
        }

        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function home2_intro(Request $request)
    {
        $home2_intro = HomePage::first();
        $translate = HomePageTranslation::where(['home_page_id' => $home2_intro->id, 'lang_code' => $request->lang_code])->first();

        return view('page::section.home2_intro', compact('home2_intro','translate'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function home2_intro_update(IntroRequest $request)
    {

        $translate = HomePageTranslation::where(['id' => $request->translate_id])->first();
        $translate->home2_intro_short_title = $request->short_title;
        $translate->home2_intro_title = $request->intro_title;
        $translate->save();

        $home2_intro = HomePage::first();

        if($request->home2_intro_image){
            $image_path = uploadFile($request->home2_intro_image, 'uploads/website-images', $home2_intro->home2_intro_image);
            $home2_intro->home2_intro_image = $image_path;
            $home2_intro->save();
        }

        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function home3_intro(Request $request)
    {
        $home3_intro = HomePage::first();
        $translate = HomePageTranslation::where(['home_page_id' => $home3_intro->id, 'lang_code' => $request->lang_code])->first();

        return view('page::section.home3_intro', compact('home3_intro','translate'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function home3_intro_update(IntroRequest $request)
    {
        $translate = HomePageTranslation::where(['id' => $request->translate_id])->first();
        $translate->home3_intro_short_title = $request->short_title;
        $translate->home3_intro_title = $request->intro_title;
        $translate->home3_intro_des = $request->home3_intro_des;
        $translate->save();

        $home3_intro = HomePage::first();

        if($request->home3_intro_image){
            $image_path = uploadFile($request->home3_intro_image, 'uploads/website-images', $home3_intro->home3_intro_image);
            $home3_intro->home3_intro_image = $image_path;
            $home3_intro->save();
        }

        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function assign_language($lang_code){

        $home_translate = HomePageTranslation::where(['lang_code' => admin_lang()])->first();

        $translate = new HomePageTranslation();
        $translate->home_page_id = $home_translate->home_page_id;
        $translate->lang_code = $lang_code;

        $translate->home1_intro_short_title = $home_translate->home1_intro_short_title;
        $translate->home1_intro_title = $home_translate->home1_intro_title;
        $translate->home2_intro_short_title = $home_translate->home2_intro_short_title;
        $translate->home2_intro_title = $home_translate->home2_intro_title;
        $translate->home3_intro_short_title = $home_translate->home3_intro_short_title;
        $translate->home3_intro_title = $home_translate->home3_intro_title;
        $translate->home3_intro_des = $home_translate->home3_intro_des;

        $translate->video_short_title = $home_translate->video_short_title;
        $translate->video_title = $home_translate->video_title;

        $translate->dealer_short_title = $home_translate->dealer_short_title;
        $translate->dealer_title = $home_translate->dealer_title;

        $translate->mobile_short_title = $home_translate->mobile_short_title;
        $translate->mobile_title = $home_translate->mobile_title;
        $translate->mobile_description = $home_translate->mobile_description;

        $translate->callus_title = $home_translate->callus_title;
        $translate->callus_header1 = $home_translate->callus_header1;
        $translate->callus_header2 = $home_translate->callus_header2;

        $translate->working_step_title1 = $home_translate->working_step_title1;
        $translate->working_step_des1 = $home_translate->working_step_des1;
        $translate->working_step_title2 = $home_translate->working_step_title2;
        $translate->working_step_des2 = $home_translate->working_step_des2;
        $translate->working_step_title3 = $home_translate->working_step_title3;
        $translate->working_step_des3 = $home_translate->working_step_des3;
        $translate->working_step_title4 = $home_translate->working_step_title4;
        $translate->working_step_des4 = $home_translate->working_step_des4;

        $translate->counter_title1 = $home_translate->counter_title1;
        $translate->counter_title2 = $home_translate->counter_title2;
        $translate->counter_title3 = $home_translate->counter_title3;

        $translate->save();

    }

}
