<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Subscription\Entities\SubscriptionPlan;
use Modules\Subscription\Entities\SubscriptionHistory;
use App\Models\Wishlist;
use App\Models\Review;
use App\Rules\Captcha;
use Hash, Image, File, Str;
use Modules\Car\Entities\Car;
use Modules\City\Entities\City;
use Modules\Country\Entities\Country;
use App\Models\CarPart;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function dashboard(Request $request)
    {
        $user = Auth::guard('web')->user();

        $cars = Car::where('agent_id', $user->id)->get()->take(10);

        $total_car = Car::where('agent_id', $user->id)->count();

        $total_car_part = CarPart::where('agent_id', $user->id)->count();

        $total_featured_car = Car::where('agent_id', $user->id)->where('is_featured', 'enable')->count();

        $total_wishlist = Wishlist::where('user_id', $user->id)->count();

        return view('profile.dashboard', ['user' => $user, 'cars' => $cars, 'total_car' => $total_car, 'total_car_part' => $total_car_part, 'total_featured_car' => $total_featured_car, 'total_wishlist' => $total_wishlist]);
    }

    public function edit(Request $request)
    {
        $user = Auth::guard('web')->user();

        $ireland = Country::where('code', 'IE')->orWhere('name', 'Ireland')->first();
        $cities = City::where('country_id', $ireland?->id ?? 0)->get();

        return view('profile.edit', [
            'user' => $user,
            'ireland' => $ireland,
            'cities' => $cities,
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'vehicle_company_name' => 'nullable|string|max:255',
            'vehicle_company_address' => 'nullable|string|max:220',
            'part_company_name' => 'nullable|string|max:255',
            'part_company_address' => 'nullable|string|max:220',
        ];
        $customMessages = [
            'name.required' => trans('translate.Name is required'),
            'email.required' => trans('translate.Email is required'),
            'phone.required' => trans('translate.Phone is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('web')->user();
        $user->name = $request->name;
        $user->phone = $request->phone;

        $user->about_me = $request->about_me;
        $user->vehicle_company_name = $request->vehicle_company_name;
        $user->vehicle_company_address = $request->vehicle_company_address;
        $user->part_company_name = $request->part_company_name;
        $user->part_company_address = $request->part_company_address;

        if ($request->has('instagram')) {
            $user->instagram = $request->instagram;
        }
        if ($request->has('facebook')) {
            $user->facebook = $request->facebook;
        }
        if ($request->has('linkedin')) {
            $user->linkedin = $request->linkedin;
        }
        if ($request->has('twitter')) {
            $user->twitter = $request->twitter;
        }

        if ($request->has('sunday')) {
            $user->sunday = $request->sunday;
        }
        if ($request->has('monday')) {
            $user->monday = $request->monday;
        }
        if ($request->has('tuesday')) {
            $user->tuesday = $request->tuesday;
        }
        if ($request->has('wednesday')) {
            $user->wednesday = $request->wednesday;
        }
        if ($request->has('thursday')) {
            $user->thursday = $request->thursday;
        }
        if ($request->has('friday')) {
            $user->friday = $request->friday;
        }
        if ($request->has('saturday')) {
            $user->saturday = $request->saturday;
        }
        $user->save();

        $notification= trans('translate.Your profile updated successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function change_password(Request $request)
    {
        return view('profile.change_password');
    }

    public function update_password(Request $request)
    {
        $rules = [
            'current_password'=>'required',
            'password'=>'required|min:4|confirmed',
        ];
        $customMessages = [
            'current_password.required' => trans('translate.Current password is required'),
            'password.required' => trans('translate.Password is required'),
            'password.min' => trans('translate.Password minimum 4 character'),
            'password.confirmed' => trans('translate.Confirm password does not match'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('web')->user();
        if(Hash::check($request->current_password, $user->password)){
            $user->password = Hash::make($request->password);
            $user->save();

            $notification = trans('translate.Password change successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);

        }else{
            $notification = trans('translate.Current password does not match');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
    }

    public function upload_user_avatar(Request $request){

        $rules = [
            'image' => 'sometimes|required|mimes:jpeg,png,jpg|max:1024'
        ];
        $customMessages = [
            'image.required' => trans('translate.Image is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('web')->user();

        if($request->file('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/custom-images', $user->image);
            $user->image = $image_path;
            $user->save();
        }

        $notification = trans('translate.Image updated successfully');
        return response()->json(['message' => $notification]);
    }


    public function pricing_plan(){

        $user = Auth::guard('web')->user();
        if($user && !$user->is_dealer){
            $notification = trans('translate.You are not allowed to access this page');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('user.dashboard')->with($notification);
        }

        $subscription_plans = SubscriptionPlan::orderBy('serial', 'asc')->where('status', 'active')->get();

        return view('profile.pricing_plan', ['subscription_plans' => $subscription_plans]);
    }

    public function orders(){

        $user = Auth::guard('web')->user();

        $histories = SubscriptionHistory::where('user_id', $user->id)->latest()->get();

        return view('profile.orders', ['histories' => $histories, 'user' => $user]);
    }


    public function reviews(){

        $user = Auth::guard('web')->user();

        $reviews = Review::with('car.dealer')->latest()->where('user_id', $user->id)->get();

        return view('profile.reviews', ['reviews' => $reviews]);
    }

    public function store_review(Request $request){

        $rules = [
            'rating'=>'required',
            'comment'=>'required',
            'agent_id'=>'required',
            'car_id'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'rating.required' => trans('translate.Rating is required'),
            'comment.required' => trans('translate.Review is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('web')->user();

        $is_exist = Review::where(['user_id' => $user->id, 'car_id' => $request->car_id])->count();

        if($is_exist == 0){
            $review = new Review();
            $review->user_id = $user->id;
            $review->rating = $request->rating;
            $review->comment = $request->comment;
            $review->agent_id = $request->agent_id;
            $review->car_id = $request->car_id;
            $review->save();

            $notification = trans('translate.Review submited successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);

        }else{
            $notification = trans('translate.Review already submited');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

    }


    public function add_to_wishlist($id){
        $user = Auth::guard('web')->user();
        $is_exist = Wishlist::where(['user_id' => $user->id, 'car_id' => $id])->count();
        if($is_exist == 0){

            $wishlist = new Wishlist();
            $wishlist->car_id = $id;
            $wishlist->user_id = $user->id;
            $wishlist->save();

            $notification = trans('translate.Item added to favourite list');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);

        }else{
            $notification = trans('translate.Already added to favourite list');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

    }

    public function wishlists(){

        $user = Auth::guard('web')->user();
        $wishlists = Wishlist::where(['user_id' => $user->id])->get();
        $wishlist_arr = array();
        foreach($wishlists as $wishlist){
            $wishlist_arr [] = $wishlist->car_id;
        }

        $cars = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->whereIn('id', $wishlist_arr)->get();


        return view('profile.wishlists', ['cars' => $cars]);

    }

    public function remove_wishlist($id){

        $user = Auth::guard('web')->user();
        Wishlist::where(['user_id' => $user->id, 'car_id' => $id])->delete();

        $notification = trans('translate.Item remove to favourite list');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

}
