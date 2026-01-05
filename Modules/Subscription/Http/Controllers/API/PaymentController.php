<?php

namespace Modules\Subscription\Http\Controllers\API;

use Razorpay\Api\Api;
use App\Models\BankPayment;
use App\Models\Flutterwave;
use Illuminate\Http\Request;
use App\Models\PaypalPayment;
use App\Models\StripePayment;
use Modules\Car\Entities\Car;

use App\Models\RazorpayPayment;
use App\Models\InstamojoPayment;
use App\Models\PaystackAndMollie;
use Mollie\Laravel\Facades\Mollie;
use App\Http\Controllers\Controller;
use Modules\Subscription\Entities\SubscriptionPlan;
use Modules\Subscription\Entities\SubscriptionHistory;
use Session, Auth, Stripe, Mail, Str, Exception, Redirect, Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('razorpay_webview_success', 'flutterwave_webview_payment', 'mollie_webview_payment', 'paystack_webview_payment', 'instamojo_webview_payment');
    }


    public function pricing_plan(){

        $plans = SubscriptionPlan::where('status', 'active')->orderBy('serial', 'asc')->get();

        return response()->json(['plans' => $plans]);
    }


    public function payment(){

        $user = Auth::guard('api')->user();

        $paypal = PaypalPayment::first();
        $stripe = StripePayment::first();
        $razorpay = RazorpayPayment::first();
        $flutterwave = Flutterwave::first();
        $paystack = PaystackAndMollie::first();
        $mollie = $paystack;
        $instamojo = InstamojoPayment::first();
        $bank = BankPayment::first();

        return response()->json([
            'stripe' => $stripe,
            'paypal' => $paypal,
            'razorpay' => $razorpay,
            'flutterwave' => $flutterwave,
            'paystack' => $paystack,
            'mollie' => $mollie,
            'instamojo' => $instamojo,
            'bank' => $bank,
        ]);
    }


    public function free_enroll($id){

        $user = Auth::guard('api')->user();

        $subscription_plan = SubscriptionPlan::where('id', $id)->first();

        if(!$subscription_plan){
            return response()->json(['error' => 'Subscription Plan Not Found']);
        }

        if($subscription_plan->plan_price == '0.00'){

            $free_exist = SubscriptionHistory::where('user_id', $user->id)->where('transaction', 'Freemium')->first();

            if($free_exist){
                return response()->json(['error' => trans('You have already enrolled in this plan')], 403);
            }

            $order = $this->create_order($user, $subscription_plan,  'Freemium', 'success', 'Freemium');

            $notification = trans('translate.Your enrollment have successfully done');
            return response()->json(['status' => 'success' , 'message' => $notification, 'order' => $order]);
        }else{
            return response()->json(['error' => trans('This is premium plan')]);
        }

    }

    public function pay_via_bank(Request $request, $id){

        $rules = [
            'tnx_info'=>'required',
        ];
        $customMessages = [
            'tnx_info.required' => trans('translate.Transaction is required'),
        ];

        $request->validate($rules,$customMessages);

        $user = Auth::guard('api')->user();

        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

        $order = $this->create_order($user, $subscription_plan,  'Bank_Payment', 'pending', $request->tnx_info);

        $notification = trans('translate.Your payment has been made. please wait for admin payment approval');

        return response()->json(['status' => 'success' , 'message' => $notification, 'order' => $order]);
    }

    public function pay_via_stripe(Request $request, $id){

        $rules = [
            'card_number'=>'required',
            'year'=>'required',
            'month'=>'required',
            'cvc'=>'required',
        ];
        $customMessages = [
            'card_number.required' => trans('Card number is required'),
            'year.required' => trans('Year is required'),
            'month.required' => trans('Month is required'),
            'cvc.required' => trans('Cvv is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('This Is Demo Version. You Can Not Change Anything');
            return response()->json(['message' => $notification],403);
        }

        $user = Auth::guard('api')->user();

        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

        $stripe = StripePayment::first();
        $payable_amount = round($subscription_plan->plan_price * $stripe->currency->currency_rate,2);
        Stripe\Stripe::setApiKey($stripe->stripe_secret);


        try{
            $token = Stripe\Token::create([
                'card' => [
                    'number' => $request->card_number,
                    'exp_month' => $request->month,
                    'exp_year' => $request->year,
                    'cvc' => $request->cvc,
                ],
            ]);

            if (!isset($token['id'])) {
                return response()->json(['error' => trans('Payment Faild')],403);
            }

            $result = Stripe\Charge::create ([
                'card' => $token['id'],
                "amount" => $payable_amount * 100,
                "currency" => $stripe->currency_code,
                "description" => env('APP_NAME')
            ]);

        }catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['message' => trans('Please provide valid card information')],403);
        }

        $order = $this->create_order($user, $subscription_plan,  'Stripe', 'success', $result->balance_transaction);

        $notification = trans('translate.Your payment has been made successful. Thanks for your new purchase');
        return response()->json(['status' => 'success' , 'message' => $notification, 'order' => $order]);
    }


    public function razorpay_webview(Request $request, $id){

        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('user_validation.This Is Demo Version. You Can Not Change Anything');
            return response()->json(['message' => $notification],403);
        }

        $razorpay = RazorpayPayment::first();

        $user = Auth::guard('api')->user();
        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

        Session::put('auth_user', $user);

        return view('subscription::webview.razorpay_webview', compact('razorpay','user','subscription_plan'));
    }


    public function razorpay_webview_success(Request $request, $id){
        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('user_validation.This Is Demo Version. You Can Not Change Anything');
            return response()->json(['message' => $notification],403);
        }

        $user = Session::get('auth_user');

        $razorpay = RazorpayPayment::first();
        $input = $request->all();
        $api = new Api($razorpay->key,$razorpay->secret_key);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));
                $payId = $response->id;

                $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

                $order = $this->create_order($user, $subscription_plan,  'Razorpay', 'success', $payId);

                return redirect()->route('webview-success-payment');

            }catch (Exception $e) {
                return redirect()->route('webview-faild-payment');
            }
        }else{
            return redirect()->route('webview-faild-payment');
        }
    }

    public function flutterwave_webview(Request $request, $id){
        $flutterwave = Flutterwave::first();

        $user = Auth::guard('api')->user();
        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

        Session::put('auth_user', $user);

        return view('subscription::webview.flutterwave_webview', compact('flutterwave','user','subscription_plan'));
    }


    public function flutterwave_webview_payment(Request $request, $id){

        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('user_validation.This Is Demo Version. You Can Not Change Anything');
            return response()->json(['message' => $notification],403);
        }

        $user = Session::get('auth_user');

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

            $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

            $order = $this->create_order($user, $subscription_plan,  'Flutterwave', 'success', $tnx_id);

            $notification = trans('user_validation.You have successfully enrolled this package');
            return response()->json(['status' => 'success' , 'message' => $notification]);
        }else{
            $notification = trans('user_validation.Payment Faild');
            return response()->json(['status' => 'faild' , 'message' => $notification]);
        }
    }


    public function mollie_webview(Request $request, $id){

        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('user_validation.This Is Demo Version. You Can Not Change Anything');
            return response()->json(['message' => $notification],403);
        }

        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();
        $user = Auth::guard('api')->user();

        $mollie = PaystackAndMollie::first();
        $price = $subscription_plan->plan_price * $mollie->mollie_currency->currency_rate;
        $price = round($price,2);
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
            'redirectUrl' => route('user.mollie-webview-payment'),
        ]);

        $payment = Mollie::api()->payments()->get($payment->id);
        session()->put('payment_id',$payment->id);
        session()->put('subscription_plan',$subscription_plan);
        session()->put('auth_user',$user);

        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function mollie_webview_payment(Request $request){
        $subscription_plan = Session::get('subscription_plan');
        $user = Session::get('auth_user');
        $mollie = PaystackAndMollie::first();
        $mollie_api_key = $mollie->mollie_key;
        Mollie::api()->setApiKey($mollie_api_key);
        $payment = Mollie::api()->payments->get(session()->get('payment_id'));
        if ($payment->isPaid()){

            $order = $this->create_order($user, $subscription_plan,  'Mollie', 'success', session()->get('payment_id'));

            return redirect()->route('webview-success-payment');
        }else{
            return redirect()->route('webview-faild-payment');
        }
    }


    public function paystack_webview(Request $request, $id){

        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('user_validation.This Is Demo Version. You Can Not Change Anything');
            return response()->json(['message' => $notification],403);
        }

        $mollie = PaystackAndMollie::first();
        $paystack = $mollie;

        $user = Auth::guard('api')->user();
        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

        Session::put('auth_user', $user);

        return view('subscription::webview.paystack_webview', compact('paystack','user','subscription_plan'));
    }

    public function paystack_webview_payment(Request $request, $id){

        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('user_validation.This Is Demo Version. You Can Not Change Anything');
            return response()->json(['message' => $notification],403);
        }

        $user = Session::get('auth_user');
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
            $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();

            $order = $this->create_order($user, $subscription_plan,  'Paystack', 'success', $transaction);

            $notification = trans('user_validation.You have successfully enrolled this package');
            return response()->json(['status' => 'success' , 'message' => $notification]);
        }else{
            $notification = trans('user_validation.Payment Faild');
            return response()->json(['status' => 'faild' , 'message' => $notification]);
        }
    }


    public function instamojo_webview(Request $request, $id){

        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('user_validation.This Is Demo Version. You Can Not Change Anything');
            return response()->json(['message' => $notification],403);
        }

        $subscription_plan = SubscriptionPlan::where('id', $id)->where('status', 'active')->firstOrFail();
        $user = Auth::guard('api')->user();

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
            'redirect_url' => route('user.instamojo-webview-payment'),
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
        Session::put('auth_user', $user);
        return redirect($response->payment_request->longurl);
    }

    public function instamojo_webview_payment(Request $request){

        $subscription_plan = Session::get('subscription_plan');
        $user = Session::get('auth_user');

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
            return redirect()->route('webview-faild-payment');
        } else {
            $data = json_decode($response);
        }

        if($data->success == true) {
            if($data->payment->status == 'Credit') {

                $order = $this->createOrder($user, $subscription_plan, 'Instamojo', 'success', $request->get('payment_id'));

                return redirect()->route('webview-success-payment');
            }
        }else{
            return redirect()->route('webview-faild-payment');
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
