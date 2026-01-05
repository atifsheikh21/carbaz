<?php

namespace Modules\BannerSlider\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Car\Entities\Car;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Intervention\Image\Facades\Image;
use Modules\BannerSlider\Entities\BannerSlider;

class BannerSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $banners = BannerSlider::latest()->get();

        return view('bannerslider::index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $cars = Car::get();
        return view('bannerslider::create',['cars' => $cars]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $banner = new BannerSlider();

         if ($request->hasFile('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/custom-images');
            $banner->image = $image_path;
        }

        $banner->link = $request->link;
        $banner->status = $request->status ? 'enable' : 'disable';
        $banner->save();

        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.bannerslider.edit', $banner->id)->with($notification);


    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request ,$id)
    {

        $banner = BannerSlider::findOrFail($id);
        $cars = Car::get();

        return view('bannerslider::edit', ['banner' => $banner, 'cars' => $cars]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $banner = BannerSlider::findOrFail($id);

         if ($request->hasFile('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/custom-images', $banner->image);
            $banner->image = $image_path;
            $banner->save();
        }

        $banner->link = $request->link;
        $banner->status = $request->status ? 'enable' : 'disable';
        $banner->save();



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
        $banner = BannerSlider::find($id);
        $old_image = $banner->image;
        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        $banner->delete();

        $notification= trans('translate.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.bannerslider.index')->with($notification);
    }
}
