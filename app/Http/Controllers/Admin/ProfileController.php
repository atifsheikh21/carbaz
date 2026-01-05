<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, Str, Image, Hash, File;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function edit_profile(){

        $admin = Auth::guard('admin')->user();

        return view('admin.edit_profile', compact('admin'));
    }

    public function profile_update(Request $request){
        $admin = Auth::guard('admin')->user();
        $rules = [
            'name'=>'required',
            'designation'=>'required',
            'email'=>'required|unique:admins,email,'.$admin->id,

        ];
        $customMessages = [
            'name.required' => trans('translate.Name is required'),
            'designation.required' => trans('translate.Designation is required'),
            'email.required' => trans('translate.Email is required'),
            'email.unique' => trans('translate.Email already exist')
        ];
        $this->validate($request, $rules,$customMessages);

        $admin = Auth::guard('admin')->user();

        // inset user profile image
        if($request->hasFile('image')) {
            $image_path = uploadFile($request->hasFile('image'), 'uploads/website-images', $admin->image);
            $admin->image = $image_path;
            $admin->save();
        }

        $admin->name = $request->name;
        $admin->designation = $request->designation;
        $admin->email = $request->email;
        $admin->about_me = $request->about_me;
        $admin->facebook = $request->facebook;
        $admin->linkedin = $request->linkedin;
        $admin->twitter = $request->twitter;
        $admin->instagram = $request->instagram;
        $admin->save();

        $notification= trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }


    public function update_password(Request $request){
        $admin = Auth::guard('admin')->user();
        $rules = [
            'current_password'=>'required',
            'password'=>'required|confirmed|min:4',
        ];
        $customMessages = [
            'current_password.required' => trans('translate.Current password is required'),
            'password.required' => trans('translate.Password is required'),
            'password.confirmed' => trans('translate.Confirm password does not match'),
            'password.min' => trans('translate.Password must be at leat 4 characters'),
        ];
        $this->validate($request, $rules,$customMessages);

        if(Hash::check($request->current_password,$admin->password)){
            $admin->password = Hash::make($request->password);
            $admin->save();

            $notification= trans('translate.Password updated successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);

        }else{
            $notification= trans('translate.Current password does not match');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
    }


}
