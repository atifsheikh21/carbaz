<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Review;
use App\Models\Wishlist;
use Modules\Car\Entities\Car;
use Modules\Subscription\Entities\SubscriptionHistory;
use Modules\Kyc\Entities\KycInformation;

use Auth, Str, Image, File, Hash, Mail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function user_list(){

        $users = User::where('status', 'enable')->latest()->get();

        $title = trans('translate.User List');

        return view('admin.user.user_list', compact('users','title'));
    }

    public function pending_user(){

        $users = User::where('status', 'disable')->latest()->get();

        $title = trans('translate.Pending User');

        return view('admin.user.user_list', compact('users','title'));
    }

    public function user_show($id){

        $user = User::findOrFail($id);

        $total_listing = Car::where('agent_id', $user->id)->count();

        $active_listing = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved', 'agent_id' => $id])->count();

        $total_purchase = SubscriptionHistory::whereDate('user_id', $id)->sum('plan_price');

        $total_review = Review::where('agent_id',$id)->count();

        $cars = Car::with('translate','brand')->where('agent_id', $user->id)->latest()->get();

        return view('admin.user.user_show', [
            'user' => $user,
            'total_listing' => $total_listing,
            'total_purchase' => $total_purchase,
            'total_review' => $total_review,
            'active_listing' => $active_listing,
            'cars' => $cars,
        ]);
    }

    public function update(Request $request ,$id){

        $user = User::findOrFail($id);

        $rules = [
            'name'=>'required',
            'phone'=>'required',
            'address'=>'required|max:220',
        ];
        $customMessages = [
            'name.required' => trans('translate.Name is required'),
            'phone.required' => trans('translate.Phone is required'),
            'address.required' => trans('translate.Address is required')
        ];
        $this->validate($request, $rules,$customMessages);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->designation = $request->designation;
        $user->about_me = $request->about_me;
        $user->status = $request->status ? 'enable' : 'disable';
        $user->save();

        $notification= trans('translate.User updated successful');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function user_destroy($id){

        $total_car = Car::where('agent_id', $id)->count();
        if($total_car > 0){
            $notification = trans('translate.You can not delete this user, multiple listing available under this user');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.user-list')->with($notification);
        }

        $user = User::find($id);
        $user_image = $user->image;

        if($user_image){
            if(File::exists(public_path().'/'.$user_image))unlink(public_path().'/'.$user_image);
        }

        Review::where('user_id',$id)->delete();
        SubscriptionHistory::where('user_id',$id)->delete();
        Wishlist::where('user_id',$id)->delete();
        KycInformation::where('user_id',$id)->delete();

        $user->delete();

        $notification = trans('translate.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.user-list')->with($notification);

    }

    public function user_status($id){
        $user = User::findOrFail($id);
        if($user->status == 'enable'){
            $user->status = 'disable';
            $user->save();
            $message = trans('translate.Status Changed Successfully');
        }else{
            $user->status = 'enable';
            $user->save();
            $message = trans('translate.Status Changed Successfully');
        }
        return response()->json($message);
    }


}
