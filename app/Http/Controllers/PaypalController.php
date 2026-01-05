<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

use App\Models\PaypalPayment;
use Auth, Session, Mail;
use Modules\Subscription\Entities\SubscriptionPlan;
use Modules\Subscription\Entities\SubscriptionHistory;
use Modules\Car\Entities\Car;

class PaypalController extends Controller
{
    public function pay_via_paypal(Request $request, $id){

        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('translate.This Is Demo Version. You Can Not Change Anything');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $user = Auth::guard('web')->user();

        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

        $paypal_setting = PaypalPayment::first();

        $payable_amount = round($subscription_plan->plan_price * $paypal_setting->currency->currency_rate,2);

        config(['paypal.mode' => $paypal_setting->account_mode]);

        if($paypal_setting->account_mode == 'sandbox'){
            config(['paypal.sandbox.client_id' => $paypal_setting->client_id]);
            config(['paypal.sandbox.client_secret' => $paypal_setting->secret_id]);
        }else{
            config(['paypal.sandbox.client_id' => $paypal_setting->client_id]);
            config(['paypal.sandbox.client_secret' => $paypal_setting->secret_id]);
            config(['paypal.sandbox.app_id' => 'APP-80W284485P519543T']);
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal-success-payment'),
                "cancel_url" => route('paypal-faild-payment'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => $paypal_setting->currency->currency_code,
                        "value" => $payable_amount
                    ]
                ]
            ]
        ]);

        Session::put('subscription_plan', $subscription_plan);

        if (isset($response['id']) && $response['id'] != null) {
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }

            $notification = trans('translate.Something went wrong, please try again');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);

        } else {
            $notification = trans('translate.Something went wrong, please try again');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
    }

    public function paypal_success_payment(Request $request){

        $paypal_setting = PaypalPayment::first();

        config(['paypal.mode' => $paypal_setting->account_mode]);

        if($paypal_setting->account_mode == 'sandbox'){
            config(['paypal.sandbox.client_id' => $paypal_setting->client_id]);
            config(['paypal.sandbox.client_secret' => $paypal_setting->secret_id]);
        }else{
            config(['paypal.sandbox.client_id' => $paypal_setting->client_id]);
            config(['paypal.sandbox.client_secret' => $paypal_setting->secret_id]);
            config(['paypal.sandbox.app_id' => 'APP-80W284485P519543T']);
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {

            $subscription_plan = Session::get('subscription_plan');

            $user = Auth::guard('web')->user();

            $order = $this->create_order($user, $subscription_plan,  'Paypal', 'success', $request->PayerID);

            $notification = trans('translate.Your payment has been made successful. Thanks for your new purchase');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('user.orders')->with($notification);

        } else {
            $subscription_plan = Session::get('subscription_plan');

            $notification = trans('translate.Something went wrong, please try again');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('payment', $subscription_plan->id)->with($notification);
        }

    }

    public function paypal_faild_payment(Request $request){

        $subscription_plan = Session::get('subscription_plan');

        $notification = trans('translate.Something went wrong, please try again');
        $notification = array('messege'=>$notification,'alert-type'=>'error');
        return redirect()->route('payment', $subscription_plan->id)->with($notification);
    }

    public function create_order($user, $subscription_plan, $payment_method, $payment_status, $tnx_info){

        if($payment_status == 'success'){
            SubscriptionHistory::where('user_id', $user->id)->update(['status' => 'expired']);
        }

        if($subscription_plan->expiration_date == 'monthly'){
            $expiration_date = date('Y-m-d', strtotime('30 days'));
        }elseif($subscription_plan->expiration_date == 'yearly'){
            $expiration_date = date('Y-m-d', strtotime('365 days'));
        }elseif($subscription_plan->expiration_date == 'lifetime'){
            $expiration_date = 'lifetime';
        }


        $purchase = new SubscriptionHistory();
        $purchase->order_id = substr(rand(0,time()),0,10);
        $purchase->user_id = $user->id;
        $purchase->subscription_plan_id = $subscription_plan->id;
        $purchase->max_car = $subscription_plan->max_car;
        $purchase->featured_car = $subscription_plan->featured_car;
        $purchase->plan_name = $subscription_plan->plan_name;
        $purchase->plan_price = $subscription_plan->plan_price;
        $purchase->expiration = $subscription_plan->expiration_date;
        $purchase->expiration_date = $expiration_date;
        $purchase->status = 'active';
        $purchase->payment_method = $payment_method;
        $purchase->payment_status = $payment_status;
        $purchase->transaction = $tnx_info;
        $purchase->save();

        $user->is_dealer = 1;
        $user->save();

        if($payment_status == 'success'){
            if($expiration_date == 'lifetime'){
                Car::where('agent_id', $user->id)->update(['expired_date' => null]);
            }else{
                Car::where('agent_id', $user->id)->update(['expired_date' => $expiration_date]);
            }

            $cars = Car::where('agent_id', $user->id)->get();

            if($cars->count() > $purchase->max_car){

                foreach($cars as $index => $car){
                    if($index > $purchase->max_car){
                        $car->approved_by_admin = 'pending';
                        $car->save();
                    }
                }
            }
        }

        return $purchase;

    }

}
