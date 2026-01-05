<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use Razorpay\Api\Api;
use App\Models\BankPayment;
use App\Models\Flutterwave;
use Illuminate\Http\Request;
use App\Models\PaypalPayment;
use App\Models\StripePayment;
use Modules\Car\Entities\Car;
use App\Models\RazorpayPayment;

use Modules\City\Entities\City;
use App\Models\InstamojoPayment;
use App\Models\PaystackAndMollie;
use Mollie\Laravel\Facades\Mollie;
use App\Http\Controllers\Controller;
use Modules\GeneralSetting\Entities\Setting;
use Modules\Currency\app\Models\MultiCurrency;
use Modules\Subscription\Entities\SubscriptionPlan;
use Modules\Subscription\Entities\SubscriptionHistory;
use Session, Auth, Stripe, Mail, Str, Exception, Redirect;

class PaymentController extends Controller
{

    public function payment(Request $request){

        $rules = [
            'car_id'=>'required|numeric',
            'pickup_location'=>'required|exists:cities,id',
            'return_location'=>'required|exists:cities,id',
            'pickup_date'=>'required',
            'return_date'=>'required',
            'number_of_day'=>'required|numeric',
        ];
        $customMessages = [
            'pickup_location.required' => trans('translate.PickUp Location is required'),
            'return_location.required' => trans('translate.Return Location is required'),
            'pickup_date.required' => trans('translate.PickUp Date is required'),
            'pickup_time.required' => trans('translate.PickUp Time is required'),
            'return_date.required' => trans('translate.Return Date is required'),
            'return_time.required' => trans('translate.Return Time is required'),
        ];
        $this->validate($request, $rules, $customMessages);


        $car = Car::where('id',$request->car_id)->first();

        if(!$car){
            $message = trans('translate.Listing Not Found!');
            return response()->json([
                'message' => $message
            ], 403);
        }



        $pickup_date = Carbon::createFromFormat('Y-m-d', $request->pickup_date);
        $return_date = Carbon::createFromFormat('Y-m-d', $request->return_date);

        $number_of_day = $return_date->diffInDays($pickup_date) + 1;

        $totalPrice = $car->regular_price * $number_of_day;

        $totalDuration = $number_of_day;

        $general_setting = Setting::first();

        $vatPercentage = $general_setting->vat_percentage;
        $platformFeePercentage = $general_setting->platform_percentage;

        $vatAmount = ($totalPrice * $vatPercentage) / 100;
        $platformFeeAmount = ($totalPrice * $platformFeePercentage) / 100;

        $finalPrice = $totalPrice + $vatAmount + $platformFeeAmount;


        $paypal = PaypalPayment::first();

        return response()->json([
            'sub_total' => $totalPrice,
            'vat_percentage' => $general_setting->vat_percentage,
            'platform_percentage' => $general_setting->platform_percentage,
            'vat_mount' => $vatAmount,
            'platform_fee_amount' => $platformFeeAmount,
            'grand_total' => $finalPrice,
            'paypal' => $paypal,
        ]);




    }

    public function payment2(Request $request){


        $pickupLocation = Session::get('pickupLocation');

        if(!$pickupLocation){
            abort(404);
        }

        $ReturnLocation = Session::get('ReturnLocation');

        $pickup_date = Session::get('pickup_date');
        $return_date = Session::get('return_date');
        $booking_note = Session::get('booking_note');
        $number_of_day = Session::get('number_of_day');
        $carInfo = Session::get('carInfo');

        $user = Auth::guard('web')->user();

        $general_setting = Setting::first();

        $paypal = PaypalPayment::first();


        $totalPrice = $carInfo->regular_price * $number_of_day;

        $totalDuration = $number_of_day;

        $general_setting = Setting::first();


        $vatPercentage = $general_setting->vat_percentage;
        $platformFeePercentage = $general_setting->platform_percentage;

        $vatAmount = ($totalPrice * $vatPercentage) / 100;
        $platformFeeAmount = ($totalPrice * $platformFeePercentage) / 100;

        $finalPrice = $totalPrice + $vatAmount + $platformFeeAmount;

        return view('payment', [
            'user' => $user,
            'paypal' => $paypal,
            'carInfo' => $carInfo,
            'pickupLocation' => $pickupLocation,
            'ReturnLocation' => $ReturnLocation,
            'pickup_date' => $pickup_date,
            'return_date' => $return_date,
            'totalPrice' => $totalPrice,
            'totalDuration' => $totalDuration,
            'vatAmount' => $vatAmount,
            'platformFeeAmount' => $platformFeeAmount,
            'finalPrice' => $finalPrice,
            'general_setting' => $general_setting,
            'booking_note' => $booking_note,
        ]);

    }





    public function pay_via_bank(Request $request, $id){

        $rules = [
            'tnx_info'=>'required',
        ];
        $customMessages = [
            'tnx_info.required' => trans('translate.Transaction is required'),
        ];

        $request->validate($rules,$customMessages);

        $user = Auth::guard('web')->user();

        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

        $order = $this->create_order($user, $subscription_plan,  'Bank_Payment', 'pending', $request->tnx_info);

        $notification = trans('translate.Your payment has been made. please wait for admin payment approval');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('user.orders')->with($notification);
    }

    public function pay_via_stripe(Request $request, $id){

        $user = Auth::guard('web')->user();

        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

        $stripe = StripePayment::first();
        $payable_amount = round($subscription_plan->plan_price * $stripe->currency->currency_rate,2);
        Stripe\Stripe::setApiKey($stripe->stripe_secret);

        try{
            $result = Stripe\Charge::create ([
                "amount" => $payable_amount * 100,
                "currency" => $stripe->currency->currency_code,
                "source" => $request->stripeToken,
                "description" => env('APP_NAME')
            ]);
        }catch(Exception $ex){
            $notification = trans('translate.Something went wrong, please try again');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }


        $order = $this->create_order($user, $subscription_plan,  'Stripe', 'success', $result->balance_transaction);

        $notification = trans('translate.Your payment has been made successful. Thanks for your new purchase');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('user.orders')->with($notification);
    }

    public function pay_via_razorpay(Request $request, $id){
        $razorpay = RazorpayPayment::first();
        $input = $request->all();
        $api = new Api($razorpay->key,$razorpay->secret_key);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));
                $payId = $response->id;

                $user = Auth::guard('web')->user();

                $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

                $order = $this->create_order($user, $subscription_plan,  'Razorpay', 'success', $payId);

                $notification = trans('translate.Your payment has been made successful. Thanks for your new purchase');
                $notification = array('messege'=>$notification,'alert-type'=>'success');
                return redirect()->route('user.orders')->with($notification);

            }catch (Exception $e) {
                $notification = trans('translate.Something went wrong, please try again');
                $notification = array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->back()->with($notification);
            }
        }else{
            $notification = trans('translate.Something went wrong, please try again');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
    }

    public function pay_via_flutterwave(Request $request, $id){

        $flutterwave = Flutterwave::first();
        $curl = curl_init();
        $tnx_id = $request->tnx_id;
        $url = "https://api.flutterwave.com/v3/transactions/$tnx_id/verify";
        $token = $flutterwave->secret_key;
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        if($response->status == 'success'){

            $user = Auth::guard('web')->user();

            $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

            $order = $this->create_order($user, $subscription_plan,  'Flutterwave', 'success', $tnx_id);

            $notification = trans('translate.Your payment has been made successful. Thanks for your new purchase');
            return response()->json(['status' => 'success' , 'message' => $notification]);
        }else{
            $notification = trans('translate.Something went wrong, please try again');
            return response()->json(['status' => 'faild' , 'message' => $notification]);
        }
    }

    public function pay_via_payStack(Request $request, $id){

        $paystack = PaystackAndMollie::first();

        $reference = $request->reference;
        $transaction = $request->tnx_id;
        $secret_key = $paystack->paystack_secret_key;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST =>0,
            CURLOPT_SSL_VERIFYPEER =>0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $secret_key",
                "Cache-Control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $final_data = json_decode($response);
        if($final_data->status == true) {

            $user = Auth::guard('web')->user();

            $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

            $order = $this->create_order($user, $subscription_plan,  'Paystack', 'success', $transaction);

            $notification = trans('translate.Your payment has been made successful. Thanks for your new purchase');
            return response()->json(['status' => 'success' , 'message' => $notification]);

        }else{
            $notification = trans('translate.Something went wrong, please try again');
            return response()->json(['status' => 'faild' , 'message' => $notification]);
        }
    }

    public function pay_via_mollie(Request $request, $id){

        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('translate.This Is Demo Version. You Can Not Change Anything');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $user = Auth::guard('web')->user();

        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

        $mollie = PaystackAndMollie::first();
        $price = $subscription_plan->plan_price * $mollie->mollie_currency->currency_rate;
        $price = sprintf('%0.2f', $price);

        $mollie_api_key = $mollie->mollie_key;
        $currency = strtoupper($mollie->mollie_currency->currency_code);
        Mollie::api()->setApiKey($mollie_api_key);
        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => $currency,
                'value' => ''.$price.'',
            ],
            'description' => env('APP_NAME'),
            'redirectUrl' => route('mollie-payment-success'),
        ]);

        $payment = Mollie::api()->payments()->get($payment->id);
        session()->put('payment_id',$payment->id);
        session()->put('subscription_plan',$subscription_plan);
        return redirect($payment->getCheckoutUrl(), 303);

    }

    public function mollie_payment_success(Request $request){

        $mollie = PaystackAndMollie::first();
        $mollie_api_key = $mollie->mollie_key;
        Mollie::api()->setApiKey($mollie_api_key);
        $payment = Mollie::api()->payments->get(session()->get('payment_id'));
        if ($payment->isPaid()){

            $user = Auth::guard('web')->user();

            $subscription_plan = Session::get('subscription_plan');

            $order = $this->create_order($user, $subscription_plan,  'Mollie', 'success', session()->get('payment_id'));

            $notification = trans('translate.Your payment has been made successful. Thanks for your new purchase');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('user.orders')->with($notification);

        }else{
            $subscription_plan = Session::get('subscription_plan');

            $notification = trans('translate.Something went wrong, please try again');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('payment', $subscription_plan->id)->with($notification);
        }
    }

    public function pay_via_instamojo(Request $request, $id){

        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('translate.This Is Demo Version. You Can Not Change Anything');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $user = Auth::guard('web')->user();

        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

        $instamojoPayment = InstamojoPayment::first();
        $price = $subscription_plan->plan_price * $instamojoPayment->currency->currency_rate;
        $price = round($price,2);

        $environment = $instamojoPayment->account_mode;
        $api_key = $instamojoPayment->api_key;
        $auth_token = $instamojoPayment->auth_token;

        if($environment == 'Sandbox') {
            $url = 'https://test.instamojo.com/api/1.1/';
        } else {
            $url = 'https://www.instamojo.com/api/1.1/';
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url.'payment-requests/');
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array("X-Api-Key:$api_key",
                "X-Auth-Token:$auth_token"));
        $payload = Array(
            'purpose' => env("APP_NAME"),
            'amount' => $price,
            'phone' => '918160651749',
            'buyer_name' => Auth::user()->name,
            'redirect_url' => route('response-instamojo'),
            'send_email' => true,
            'webhook' => 'http://www.example.com/webhook/',
            'send_sms' => true,
            'email' => Auth::user()->email,
            'allow_repeated_payments' => false
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        Session::put('subscription_plan', $subscription_plan);
        return redirect($response->payment_request->longurl);
    }

    public function instamojo_response(Request $request){

        $input = $request->all();
        $instamojoPayment = InstamojoPayment::first();
        $environment = $instamojoPayment->account_mode;
        $api_key = $instamojoPayment->api_key;
        $auth_token = $instamojoPayment->auth_token;

        if($environment == 'Sandbox') {
            $url = 'https://test.instamojo.com/api/1.1/';
        } else {
            $url = 'https://www.instamojo.com/api/1.1/';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.'payments/'.$request->get('payment_id'));
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array("X-Api-Key:$api_key",
                "X-Auth-Token:$auth_token"));
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            $subscription_plan = Session::get('subscription_plan');

            $notification = trans('translate.Something went wrong, please try again');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('payment', $subscription_plan->id)->with($notification);
        } else {
            $data = json_decode($response);
        }

        if($data->success == true) {
            if($data->payment->status == 'Credit') {

                $subscription_plan = Session::get('subscription_plan');

                $user = Auth::guard('web')->user();

                $order = $this->create_order($user, $subscription_plan,  'Instamojo', 'success', $request->get('payment_id'));

                $notification = trans('translate.Your payment has been made successful. Thanks for your new purchase');
                $notification = array('messege'=>$notification,'alert-type'=>'success');
                return redirect()->route('user.orders')->with($notification);
            }
        }else{
            $subscription_plan = Session::get('subscription_plan');

            $notification = trans('translate.Something went wrong, please try again');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('payment', $subscription_plan->id)->with($notification);
        }
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
        $purchase->status = $payment_status == 'success' ? 'active' : 'pending';
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
