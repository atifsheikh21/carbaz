<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Review;
use App\Rules\Captcha;
use App\Models\Booking;
use App\Models\Wishlist;
use Illuminate\View\View;
use Hash, Image, File, Str;
use Illuminate\Http\Request;
use Modules\Car\Entities\Car;
use App\Models\WithdrawMethod;
use App\Models\SupplierWithdraw;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use Modules\Subscription\Entities\SubscriptionPlan;
use Modules\Subscription\Entities\SubscriptionHistory;

class ProfileController extends Controller
{

    public function dashboard(Request $request)
    {

        $user = Auth::guard('api')->user();

        $cars = Car::with('brand')->where('agent_id', $user->id)->get()->take(10);

        $total_car = Car::where('agent_id', $user->id)->count();

        $total_featured_car = Car::where('agent_id', $user->id)->where('is_featured', 'enable')->count();

        $total_wishlist = Wishlist::where('user_id', $user->id)->count();


        return response()->json([
            'user' => $user,
            'cars' => $cars,
            'total_car' => $total_car,
            'total_featured_car' => $total_featured_car,
            'total_wishlist' => $total_wishlist
        ]);
    }

    public function edit(Request $request)
    {
        $user = Auth::guard('api')->user();

        return response()->json([
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'address'=>'required|max:220',
        ];
        $customMessages = [
            'name.required' => trans('translate.Name is required'),
            'email.required' => trans('translate.Email is required'),
            'phone.required' => trans('translate.Phone is required'),
            'address.required' => trans('translate.Address is required')
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('api')->user();

        if($request->file('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/custom-images', $user->image);
            $user->image = $image_path;
            $user->save();
        }

        if($request->file('banner_image')) {
            $image_path = uploadFile($request->file('banner_image'), 'uploads/custom-images', $user->banner_image);
            $user->banner_image = $image_path;
            $user->save();
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->designation = $request->designation;
        $user->save();

        $notification= trans('translate.Your profile updated successfully');
        return response()->json([
            'message' => $notification,
        ]);
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

        $user = Auth::guard('api')->user();
        if(Hash::check($request->current_password, $user->password)){
            $user->password = Hash::make($request->password);
            $user->save();

            $notification = trans('translate.Password change successfully');
            return response()->json([
                'message' => $notification,
            ]);

        }else{
            $notification = trans('translate.Current password does not match');
            return response()->json([
                'message' => $notification,
            ], 403);
        }
    }


    public function transactions(){
        $user = Auth::guard('api')->user();

        $histories = SubscriptionHistory::where('user_id', $user->id)->latest()->get();

        return response()->json(['histories' => $histories]);
    }

    public function bookingHistory(){
        $user = Auth::guard('api')->user();
        $histories = Booking::with('car', 'seller','review_by_user', 'review_by_dealer')->where('user_id', $user->id)->orderBy('id','desc')->paginate(10);


        return response()->json([
            'histories' => $histories
        ]);
    }

    public function bookingDetails($id){

        $user = Auth::guard('api')->user();

        $history = Booking::with('car', 'seller')->where('id', $id)->where('user_id', $user->id)->first();

        if(!$history){
            $message = trans('Not found');
            return response()->json([
                'message' => $message
            ], 403);
        }

        return response()->json([
            'history' => $history
        ]);

    }


    public function bookingCancelByUser(Request $request, $id){

        $user = Auth::guard('api')->user();

        $history = Booking::where('id', $id)->where('user_id', $user->id)->first();

        if(!$history){
            $message = trans('Not found');
            return response()->json([
                'message' => $message
            ], 403);
        }


        if($history->status == 0 || $history->status == 1){
            $history->status = 3;
            $history->save();


            $message = trans('translate.Booking cancel succesful');
            return response()->json([
                'message' => $message,
            ]);


        }else{
            $message = trans('translate.Unable to cancel the booking');
            return response()->json([
                'message' => $message
            ], 403);
        }

    }

    public function rideStartByUser(Request $request, $id){

        $user = Auth::guard('api')->user();

        $history = Booking::where('id', $id)->where('user_id', $user->id)->first();


        if(!$history){
            $message = trans('Not found');
            return response()->json([
                'message' => $message
            ], 403);
        }

        if($history->status == 1){

            $history->status = 6;
            $history->save();


            $message = trans('translate.Ride started succesful');
            return response()->json([
                'message' => $message,
            ]);


        }else{
            $message = trans('translate.Unable to start the ride');
            return response()->json([
                'message' => $message
            ], 403);
        }

    }



    public function bookingRequest(){
        $user = Auth::guard('api')->user();

        if($user->is_dealer == 0){
            $message = trans('Need dealer account to access this route');
            return response()->json([
                'message' => $message
            ], 403);
        }

        $histories = Booking::with('car', 'seller','review_by_user', 'review_by_dealer')->where('supplier_id', $user->id)->orderBy('id','desc')->paginate(10);


         return response()->json(['histories' => $histories]);
    }


    public function bookingRequestDetails($id){

        $user = Auth::guard('api')->user();

        $history = Booking::with('car', 'seller','review_by_user', 'review_by_dealer')->where('id', $id)->where('supplier_id', $user->id)->first();


        if(!$history){
            $message = trans('Not found');
            return response()->json([
                'message' => $message
            ], 403);
        }

        return response()->json(['history' => $history]);
    }


    public function bookingRequestAccept(Request $request, $id){


        $user = Auth::guard('api')->user();

        $history = Booking::where('id', $id)->where('supplier_id', $user->id)->first();

        if(!$history){
            $message = trans('Not found');
            return response()->json([
                'message' => $message
            ], 403);
        }

        if($history->status == 0){
            $history->status = 1;
            $history->save();

            $notification = trans('translate.Booking approved succesful');
            return response()->json([
                'message' => $notification
            ]);


        }else{
            $notification = trans('translate.Unable to approved the booking');
            return response()->json([
                'message' => $notification
            ], 403);
        }

    }


    public function bookingCancelByDealer(Request $request, $id){


        $user = Auth::guard('api')->user();

        $history = Booking::where('id', $id)->where('supplier_id', $user->id)->first();

        if(!$history){
            $message = trans('Not found');
            return response()->json([
                'message' => $message
            ], 403);
        }


        if($history->status == 0 || $history->status == 1){
            $history->status = 4;
            $history->save();

            $notification = trans('translate.Booking cancel succesful');
            return response()->json([
                'message' => $notification
            ]);


        }else{
            $notification = trans('translate.Unable to cancel the booking');
            return response()->json([
                'message' => $notification
            ], 403);
        }

    }


    public function bookingCompleteByDealer(Request $request, $id){


        $user = Auth::guard('api')->user();

        $history = Booking::where('id', $id)->where('supplier_id', $user->id)->first();

        if(!$history){
            $message = trans('Not found');
            return response()->json([
                'message' => $message
            ], 403);
        }

        if($history->status == 6){
            $history->status = 2;
            $history->save();

            $notification = trans('translate.Booking completed succesful');
            return response()->json([
                'message' => $notification
            ]);


        }else{
            $notification = trans('translate.Unable to complete the booking');
            return response()->json([
                'message' => $notification
            ], 403);
        }

    }



    public function reviews(){

        $user = Auth::guard('api')->user();

        $reviews = Review::with('car')->latest()->where('user_id', $user->id)->get();

        return response()->json(['reviews' => $reviews]);
    }

    public function store_review(Request $request){

        $rules = [
            'rating'=>'required',
            'comment'=>'required',
            'car_id'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];

        $customMessages = [
            'rating.required' => trans('translate.Rating is required'),
            'comment.required' => trans('translate.Review is required'),
            'car_id.required' => trans('Car is required'),
        ];

        $this->validate($request, $rules,$customMessages);


        $user = Auth::guard('api')->user();

        $car = Car::where('id', $request->car_id)->first();

        $is_exist = Review::where(['user_id' => $user->id, 'car_id' => $car->id])->first();

        if(!$is_exist){

            $review = new Review();
            $review->user_id = $user->id;
            $review->rating = $request->rating;
            $review->comment = $request->comment;
            $review->agent_id = $car->agent_id;
            $review->car_id = $request->car_id;
            $review->save();


            $message = trans('translate.Review submited successfully');
            return response()->json([
                'message' => $message
            ]);

        }else{

            $notification = trans('translate.Review already submited');
            return response()->json([
                'message' => $notification
            ], 403);
        }


    }


    public function add_to_wishlist($id){

        $car = Car::where('id', $id)->first();

        if(!$car){
            $message = trans('translate.Listing Not Found!');
            return response()->json([
                'message' => $message
            ], 403);
        }

        $user = Auth::guard('api')->user();

        $is_exist = Wishlist::where(['user_id' => $user->id, 'car_id' => $id])->count();

        if($is_exist == 0){

            $wishlist = new Wishlist();
            $wishlist->car_id = $id;
            $wishlist->user_id = $user->id;
            $wishlist->save();

            $notification = trans('translate.Item added to favourite list');
            return response()->json([
                'message' => $notification,
            ]);

        }else{
            $notification = trans('translate.Already added to favourite list');
            return response()->json([
                'message' => $notification,
            ], 403);
        }

    }

    public function wishlists(){

        $user = Auth::guard('api')->user();
        $wishlists = Wishlist::where(['user_id' => $user->id])->get();
        $wishlist_arr = array();
        foreach($wishlists as $wishlist){
            $wishlist_arr [] = $wishlist->car_id;
        }

        $cars = Car::with('brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->whereIn('id', $wishlist_arr)->select('id', 'slug', 'brand_id', 'expired_date', 'regular_price', 'offer_price', 'thumb_image', 'purpose', 'condition', 'is_featured', 'status', 'approved_by_admin')->get();


        return response()->json(['cars' => $cars]);

    }

    public function remove_wishlist($id){

        $user = Auth::guard('api')->user();
        Wishlist::where(['user_id' => $user->id, 'car_id' => $id])->delete();

        $notification = trans('translate.Item remove to favourite list');
        return response()->json([
            'message' => $notification,
        ]);
    }

}
