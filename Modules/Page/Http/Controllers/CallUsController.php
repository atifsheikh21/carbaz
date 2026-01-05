<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\HomePage;
use Modules\Page\Entities\HomePageTranslation;
use Modules\Page\Http\Requests\CallUsRequest;
use Image, File, Str;

class CallUsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $call_us = HomePage::first();
        $translate = HomePageTranslation::where(['home_page_id' => $call_us->id, 'lang_code' => $request->lang_code])->first();

        return view('page::section.call_us', compact('call_us','translate'));
    }

    public function update(CallUsRequest $request)
    {

        $translate = HomePageTranslation::where(['id' => $request->translate_id])->first();


        $translate->callus_title = $request->callus_title;
        $translate->callus_header1 = $request->callus_header1;
        $translate->callus_header2 = $request->callus_header2;
        $translate->save();

        $call_us = HomePage::first();

        if($request->lang_code == admin_lang()){
            $call_us->callus_phone = $request->callus_phone;
            $call_us->save();
        }

        if($request->callus_image){
            $image_path = uploadFile($request->callus_image, 'uploads/website-images', $call_us->callus_image);
            $call_us->callus_image = $image_path;
            $call_us->save();
        }


        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

}
