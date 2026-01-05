<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\HomePage;
use Image, File, Str;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $loan_calculator = HomePage::first();

        return view('page::section.loan_calculator', ['loan_calculator' => $loan_calculator]);
    }

    public function update(Request $request)
    {

        $loan_calculator = HomePage::first();

        if($request->loan_bg_image){
            $image_path = uploadFile($request->loan_bg_image, 'uploads/website-images', $loan_calculator->loan_bg_image);
            $loan_calculator->loan_bg_image = $image_path;
            $loan_calculator->save();
        }

        if($request->loan_foreground_image){
            $image_path = uploadFile($request->loan_foreground_image, 'uploads/website-images', $loan_calculator->loan_foreground_image);
            $loan_calculator->loan_foreground_image = $image_path;
            $loan_calculator->save();
        }

        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
