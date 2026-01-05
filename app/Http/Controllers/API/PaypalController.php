<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Booking;

use Auth, Session, Mail;
use Illuminate\Http\Request;
use App\Models\PaypalPayment;
use Modules\Car\Entities\Car;
use Modules\City\Entities\City;
use App\Http\Controllers\Controller;
use Modules\GeneralSetting\Entities\Setting;
use Modules\Currency\app\Models\MultiCurrency;
use Modules\GeneralSetting\Entities\EmailTemplate;
use Modules\Subscription\Entities\SubscriptionPlan;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Modules\Subscription\Entities\SubscriptionHistory;

class PaypalController extends Controller
{


    public function paypal_webview(Request $request, $id){


        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('translate.This Is Demo Version. You Can Not Change Anything');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $user = Auth::guard('api')->user();

        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();


        $paypal_setting = PaypalPayment::first();

        $payable_amount = round($subscription_plan->plan_price * $paypal_setting->currency->currency_rate,2);

        config(['paypal.mode' => $paypal_setting->account_mode]);

        if($paypal_setting->account_mode == 'sandbox'){
            config(['paypal.sandbox.client_id' => $paypal_setting->client_id]);
            config(['paypal.sandbox.client_secret' => $paypal_setting->secret_id]);
        }else{
            config(['paypal.live.client_id' => $paypal_setting->client_id]);
            config(['paypal.live.client_secret' => $paypal_setting->secret_id]);
            config(['paypal.live.app_id' => 'APP-80W284485P519543T']);
        }


        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal-payment-success-webview'),
                "cancel_url" => route('paypal-payment-cancled-webview'),
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

        if (isset($response['id']) && $response['id'] != null) {
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }

            return redirect()->route('webview-payment-faild');

        } else {
            $notification = trans('translate.Something went wrong, please try again');

            return redirect()->route('webview-payment-faild');
        }
    }

    public function paypal_success_payment(Request $request){

        $user = Session::get('auth_user');

        $paypal_setting = PaypalPayment::first();

        config(['paypal.mode' => $paypal_setting->account_mode]);

        if($paypal_setting->account_mode == 'sandbox'){
            config(['paypal.sandbox.client_id' => $paypal_setting->client_id]);
            config(['paypal.sandbox.client_secret' => $paypal_setting->secret_id]);
        }else{
            config(['paypal.live.client_id' => $paypal_setting->client_id]);
            config(['paypal.live.client_secret' => $paypal_setting->secret_id]);
            config(['paypal.live.app_id' => 'APP-80W284485P519543T']);
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {

            $car_id =  Session::get('car_id');
            $carInfo = Car::where('id',$car_id)->first();



            $order = $this->create_order($user, $carInfo,  'Paypal', 'success', $request->PayerID);

            return redirect()->route('webview-payment-success');

        } else {
            return redirect()->route('webview-payment-faild');
        }

    }

    public function paypal_faild_payment(Request $request){
        return redirect()->route('webview-payment-faild');
    }

    public function create_order($user, $carInfo, $payment_method, $payment_status, $tnx_info){


        $pickupLocation = Session::get('pickupLocation');
        $ReturnLocation = Session::get('ReturnLocation');

        $pickup_date = Session::get('pickup_date');
        $return_date = Session::get('return_date');
        $booking_note = Session::get('booking_note');
        $number_of_day = Session::get('number_of_day');
        $carInfo = Session::get('carInfo');

        $duration = $number_of_day;

        $totalPrice = Session::get('totalPrice');
        $vatAmount = Session::get('vatAmount');
        $platformFeeAmount = Session::get('platformFeeAmount');

        $booking = new Booking();
        $booking->order_id = substr(rand(0,time()),0,10);
        $booking->user_id = $user->id;
        $booking->supplier_id = $carInfo->agent_id;
        $booking->car_id = $carInfo->id;
        $booking->price = $carInfo->regular_price;
        $booking->total_price = $totalPrice;
        $booking->vat_amount = $vatAmount;
        $booking->platform_amount = $platformFeeAmount;
        $booking->pickup_location = $pickupLocation?->name;
        $booking->return_location = $ReturnLocation?->name;
        $booking->pickup_date = $pickup_date;
        $booking->pickup_time = 'pickup_time';
        $booking->return_date = $return_date;
        $booking->return_time = 'return_time';
        $booking->duration = $duration;
        $booking->payment_method = $payment_method;
        $booking->payment_status = $payment_status;
        $booking->transaction = $tnx_info;
        $booking->booking_note = $booking_note;
        $booking->status = 0;
        $booking->save();

        $ride = Car::where('id',$carInfo->id)->first();
        $ride->total_rides = $ride->total_rides + 1;
        $ride->save();


        Session::forget('pickupLocation');
        Session::forget('ReturnLocation');
        Session::forget('pickup_date');
        Session::forget('pickup_time');
        Session::forget('return_date');
        Session::forget('return_time', );
        Session::forget('totalPrice');
        Session::forget('duration');
        Session::forget('vatAmount');
        Session::forget('platformFeeAmount');
        Session::forget('booking_note');
        Session::forget('number_of_day');

        return $booking;

    }

}
