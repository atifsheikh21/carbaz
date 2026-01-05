<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\HomePage;
use Modules\Page\Entities\HomePageTranslation;
use Modules\Page\Http\Requests\MobileAppRequest;
use Image, File, Str;

class MobileAppController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $mobile_app = HomePage::first();
        $translate = HomePageTranslation::where(['home_page_id' => $mobile_app->id, 'lang_code' => $request->lang_code])->first();

        return view('page::section.mobile_app', compact('mobile_app','translate'));
    }

    public function update(MobileAppRequest $request)
    {

        $translate = HomePageTranslation::where(['id' => $request->translate_id])->first();
        $translate->mobile_short_title = $request->short_title;
        $translate->mobile_title = $request->mobile_title;
        $translate->mobile_description = $request->mobile_description;
        $translate->save();

        $mobile_app = HomePage::first();

        if($request->lang_code == admin_lang()){
            $mobile_app->mobile_playstore = $request->mobile_playstore;
            $mobile_app->mobile_appstore = $request->mobile_appstore;
            $mobile_app->save();
        }

        if($request->mobile_app_image){
            $image_path = uploadFile($request->mobile_app_image, 'uploads/website-images', $mobile_app->mobile_app_image);
            $mobile_app->mobile_app_image = $image_path;
            $mobile_app->save();
        }

        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }



}
