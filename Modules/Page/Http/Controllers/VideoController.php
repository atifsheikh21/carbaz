<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\HomePage;
use Modules\Page\Entities\HomePageTranslation;
use Modules\Page\Http\Requests\VideoRequest;
use Image, File, Str;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $video = HomePage::first();
        $translate = HomePageTranslation::where(['home_page_id' => $video->id, 'lang_code' => $request->lang_code])->first();

        return view('page::section.video', compact('video','translate'));
    }

    public function update(VideoRequest $request)
    {
        $translate = HomePageTranslation::where(['id' => $request->translate_id])->first();
        $translate->video_short_title = $request->short_title;
        $translate->video_title = $request->video_title;
        $translate->save();

        if($request->lang_code == admin_lang()){
            $video = HomePage::first();
            $video->video_id = $request->video_id;
            $video->save();
        }

        if($request->video_bg_image){
            $image_path = uploadFile($request->video_bg_image, 'uploads/website-images', $video->video_bg_image);
            $video->video_bg_image = $image_path;
            $video->save();
        }

        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

}
