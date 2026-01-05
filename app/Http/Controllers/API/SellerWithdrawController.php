<?php

namespace App\Http\Controllers\API;

use Auth;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\WithdrawMethod;
use App\Models\SupplierWithdraw;
use App\Http\Controllers\Controller;

class SellerWithdrawController extends Controller
{

    public function index(){
        $user = Auth::guard('api')->user();

        $withdraws = SupplierWithdraw::where('supplier_id',$user->id)->latest()->paginate(10);

        $complete_booking = Booking::where('supplier_id', $user->id)->where('status', 2)->get();

        $total_earning = $complete_booking->sum('total_price');


        $total_withdraw = SupplierWithdraw::where('supplier_id', $user->id)->sum('total_amount');

        $current_balance = $total_earning - $total_withdraw;

        return response()->json([
            'withdraws' => $withdraws,
            'total_earning' => $total_earning,
            'total_withdraw' => $total_withdraw,
            'current_balance' => $current_balance,
        ]);
    }

    public function create(){

        $methods = WithdrawMethod::whereStatus('1')->get();

        return response()->json([
            'methods' => $methods,
        ]);

    }

    public function store(Request $request){
        $rules = [
            'method_id' => 'required|exists:withdraw_methods,id',
            'withdraw_amount' => 'required|numeric',
            'account_info' => 'required',
        ];

        $customMessages = [
            'method_id.required' => trans('translate.Payment Method filed is required'),
            'withdraw_amount.required' => trans('translate.Withdraw amount filed is required'),
            'withdraw_amount.numeric' => trans('translate.Please provide valid numeric number'),
            'account_info.required' => trans('translate.Account filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $user = Auth::guard('api')->user();


        $complete_booking = Booking::where('supplier_id', $user->id)->where('status', 2)->get();

        $total_earning = $complete_booking->sum('total_price');


        $total_withdraw = SupplierWithdraw::where('supplier_id', $user->id)->sum('total_amount');

        $current_balance = $total_earning - $total_withdraw;


        if($request->withdraw_amount > $current_balance){
            $notification = trans('translate.Sorry! Your Payment request is more then your current balance');
            return response()->json([
                'message' => $notification
            ], 403);
        }

        $method = WithdrawMethod::whereId($request->method_id)->first();
        if($request->withdraw_amount >= $method->min_amount && $request->withdraw_amount <= $method->max_amount){
            $widthdraw = new SupplierWithdraw();
            $widthdraw->supplier_id = $user->id;
            $widthdraw->method = $method->name;
            $widthdraw->total_amount = $request->withdraw_amount;
            $withdraw_request = $request->withdraw_amount;
            $withdraw_amount = ($method->withdraw_charge / 100) * $withdraw_request;
            $widthdraw->withdraw_amount = $request->withdraw_amount - $withdraw_amount;
            $widthdraw->withdraw_charge = $method->withdraw_charge;
            $widthdraw->account_info = $request->account_info;
            $widthdraw->save();

            $notification = trans('translate.Withdraw request send successfully, please wait for admin approval');
            return response()->json([
                'message' => $notification
            ]);

        }else{
            $notification = trans('translate.Invalid withraw amount range');
            return response()->json([
                'message' => $notification
            ], 403);
        }

    }
}
