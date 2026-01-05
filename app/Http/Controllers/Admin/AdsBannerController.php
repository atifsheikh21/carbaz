<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\AdsBanner;
use Image, Str, File;

class AdsBannerController extends Controller
{
    public function index(){

        $ads_banner = AdsBanner::all();

        return view('admin.ads_banner', ['ads_banner' => $ads_banner]);
    }

    public function update(Request $request, $id){

        $rules = [
            'link'=>'required',
        ];
        $customMessages = [
            'link.required' => trans('translate.Link is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $banner = AdsBanner::findOrFail($id);
        $banner->link = $request->link;
        $banner->status = $request->status ? 'enable' : 'disable';
        $banner->save();

       if ($request->hasFile('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/custom-images', $banner->image);
            $banner->image  = $image_path;
            $banner->save();
        }

        $notification= trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);


    }


}
