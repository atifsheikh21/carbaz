<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\HomePage;
use Modules\Page\Entities\HomePageTranslation;
use Modules\Page\Http\Requests\JoinDealerRequest;
use Image, File, Str;

class JoinDealerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $join_dealer = HomePage::first();
        $translate = HomePageTranslation::where(['home_page_id' => $join_dealer->id, 'lang_code' => $request->lang_code])->first();

        return view('page::section.join_as_dealer', compact('join_dealer','translate'));
    }

    public function update(JoinDealerRequest $request)
    {

        $translate = HomePageTranslation::where(['id' => $request->translate_id])->first();
        $translate->dealer_short_title = $request->short_title;
        $translate->dealer_title = $request->dealer_title;
        $translate->save();

        $join_dealer = HomePage::first();

        if($request->dealer_bg_image){
            $image_path = uploadFile($request->dealer_bg_image, 'uploads/website-images', $join_dealer->dealer_bg_image);
            $join_dealer->dealer_bg_image = $image_path;
            $join_dealer->save();
        }

        if($request->dealer_foreground_image){
            $image_path = uploadFile($request->dealer_foreground_image, 'uploads/website-images', $join_dealer->dealer_foreground_image);
            $join_dealer->dealer_foreground_image  = $image_path;
            $join_dealer->save();
        }


        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
