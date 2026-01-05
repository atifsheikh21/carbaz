<?php

namespace Modules\GeneralSetting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\PaypalPayment;
use App\Models\StripePayment;
use App\Models\RazorpayPayment;
use App\Models\Flutterwave;
use App\Models\BankPayment;
use App\Models\PaystackAndMollie;
use App\Models\InstamojoPayment;
use App\Models\Currency;
use Modules\GeneralSetting\Entities\Setting;
use Modules\Currency\app\Models\MultiCurrency;
use Image;
use File;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $paypal = PaypalPayment::first();
        $stripe = StripePayment::first();
        $razorpay = RazorpayPayment::first();
        $flutterwave = Flutterwave::first();
        $bank = BankPayment::first();
        $paystack = PaystackAndMollie::first();
        $mollie = $paystack;
        $instamojo = InstamojoPayment::first();

        $currency_list = MultiCurrency::get();

        return view('generalsetting::gateway.payment_method', compact('paypal','stripe','razorpay','bank','paystack','flutterwave','instamojo','currency_list', 'mollie'));
    }

    public function updateStripe(Request $request){

        $rules = [
            'stripe_key' => $request->status ? 'required' : '',
            'stripe_secret' => $request->status ? 'required' : '',
            'currency_id' => $request->status ? 'required' : '',
        ];
        $customMessages = [
            'stripe_key.required' => trans('translate.Stripe key is required'),
            'stripe_secret.required' => trans('translate.Stripe secret is required'),
            'currency_id.required' => trans('translate.Currency name is required'),
        ];

        $request->validate($rules,$customMessages);

        $stripe = StripePayment::first();
        $stripe->stripe_key = $request->stripe_key;
        $stripe->stripe_secret = $request->stripe_secret;
        $stripe->currency_id = $request->currency_id;
        $stripe->status = $request->status ? 1 : 0;
        $stripe->save();

        if($request->file('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/website-images', $stripe->image);
            $stripe->image = $image_path;
            $stripe->save();
        }


        $notification=trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updatePaypal(Request $request){

        $rules = [
            'paypal_client_id' => $request->status ? 'required' : '',
            'paypal_secret_key' => $request->status ? 'required' : '',
            'account_mode' => $request->status ? 'required' : '',
            'currency_id' => $request->status ? 'required' : '',
        ];
        $customMessages = [
            'paypal_client_id.required' => trans('translate.Paypal client id is required'),
            'paypal_secret_key.required' => trans('translate.Paypal secret key is required'),
            'account_mode.required' => trans('translate.Account mode is required'),
            'currency_id.required' => trans('translate.Currency name is required'),
        ];
        $request->validate($rules,$customMessages);

        $paypal = PaypalPayment::first();
        $paypal->client_id = $request->paypal_client_id;
        $paypal->secret_id = $request->paypal_secret_key;
        $paypal->account_mode = $request->account_mode;
        $paypal->currency_id = $request->currency_id;
        $paypal->status = $request->status ? 1 : 0;
        $paypal->save();

        if($request->file('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/website-images', $paypal->image);
            $paypal->image = $image_path;
            $paypal->save();
        }

        $notification=trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateRazorpay(Request $request){
        $rules = [
            'razorpay_key' => $request->status ? 'required' : '',
            'razorpay_secret' => $request->status ? 'required' : '',
            'name' => $request->status ? 'required' : '',
            'description' => $request->status ? 'required' : '',
            'theme_color' => $request->status ? 'required' : '',
            'currency_id' => $request->status ? 'required' : '',
        ];
        $customMessages = [
            'razorpay_key.required' => trans('translate.Razorpay key is required'),
            'razorpay_secret.required' => trans('translate.Razorpay secret is required'),
            'name.required' => trans('translate.Name is required'),
            'description.required' => trans('translate.Description is required'),
            'currency_id.required' => trans('translate.Currency name is required'),
            'theme_color.required' => trans('translate.Theme Color is required'),
        ];
        $request->validate($rules,$customMessages);

        $razorpay = RazorpayPayment::first();
        $razorpay->key = $request->razorpay_key;
        $razorpay->secret_key = $request->razorpay_secret;
        $razorpay->name = $request->name;
        $razorpay->currency_id = $request->currency_id;
        $razorpay->description = $request->description;
        $razorpay->color = $request->theme_color;
        $razorpay->status = $request->status ? 1 : 0;
        $razorpay->save();

        if($request->image) {
            $image_path = uploadFile($request->image, 'uploads/website-images', $razorpay->image);
            $razorpay->image = $image_path;
            $razorpay->save();
        }

        $notification=trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateBank(Request $request){
        $rules = [
            'account_info' => $request->status ? 'required' : ''
        ];
        $customMessages = [
            'account_info.required' => trans('translate.Account information is required'),
        ];
        $request->validate($rules,$customMessages);

        $bank = BankPayment::first();
        $bank->account_info = $request->account_info;
        $bank->status = $request->status ? 1 : 0;
        $bank->save();

        if($request->file('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/website-images', $bank->image);
            $bank->image = $image_path;
            $bank->save();
        }


        $notification=trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function updateMollie(Request $request){
        $rules = [
            'mollie_key' => $request->status ? 'required' : '',
            'mollie_currency_id' => $request->status ? 'required' : ''
        ];

        $customMessages = [
            'mollie_key.required' => trans('translate.Mollie key is required'),
            'mollie_currency_id.required' => trans('translate.Currency name is required'),
        ];
        $request->validate($rules,$customMessages);

        $mollie = PaystackAndMollie::first();
        $mollie->mollie_key = $request->mollie_key;
        $mollie->mollie_currency_id = $request->mollie_currency_id;
        $mollie->mollie_status = $request->status ? 1 : 0;
        $mollie->save();


        if($request->file('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/website-images', $mollie->image);
            $mollie->image = $image_path;
            $mollie->save();
        }

        $notification=trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updatePayStack(Request $request){
        $rules = [
            'paystack_public_key' => $request->status ? 'required' : '',
            'paystack_secret_key' => $request->status ? 'required' : '',
            'paystack_currency_id' => $request->status ? 'required' : '',
        ];

        $customMessages = [
            'paystack_public_key.required' => trans('translate.Paystack public key is required'),
            'paystack_secret_key.required' => trans('translate.Paystack secret key is required'),
            'paystack_currency_id.required' => trans('translate.Currency name is required'),
        ];
        $request->validate($rules,$customMessages);

        $paystact = PaystackAndMollie::first();
        $paystact->paystack_public_key = $request->paystack_public_key;
        $paystact->paystack_secret_key = $request->paystack_secret_key;
        $paystact->paystack_currency_id = $request->paystack_currency_id;
        $paystact->paystack_status = $request->status ? 1 : 0;
        $paystact->save();

        if($request->file('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/website-images', $paystact->image);
            $paystact->image = $image_path;
            $paystact->save();
        }

        $notification=trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateflutterwave(Request $request){
        $rules = [
            'public_key' => $request->status ? 'required' : '',
            'secret_key' => $request->status ? 'required' : '',
            'title' => $request->status ? 'required' : '',
            'currency_id' => $request->status ? 'required' : '',
        ];
        $customMessages = [
            'title.required' => trans('translate.Title is required'),
            'public_key.required' => trans('translate.Public key is required'),
            'secret_key.required' => trans('translate.Secret key is required'),
            'currency_id.required' => trans('translate.Currency name is required'),
        ];
        $request->validate($rules,$customMessages);

        $flutterwave = Flutterwave::first();
        $flutterwave->public_key = $request->public_key;
        $flutterwave->secret_key = $request->secret_key;
        $flutterwave->title = $request->title;
        $flutterwave->currency_id = $request->currency_id;
        $flutterwave->status = $request->status ? 1 : 0;
        $flutterwave->save();

        if($request->image) {
            $image_path = uploadFile($request->image, 'uploads/website-images', $flutterwave->image);
            $flutterwave->image = $image_path;
            $flutterwave->save();
        }

        $notification=trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function updateInstamojo(Request $request){
        $rules = [
            'account_mode' => 'required',
            'api_key' => 'required',
            'auth_token' => 'required',
            'currency_id' => 'required',
        ];
        $customMessages = [
            'account_mode.required' => trans('translate.Account mode is required'),
            'api_key.required' => trans('translate.Api key is required'),
            'currency_id.required' => trans('translate.Currency name is required'),
            'auth_token.required' => trans('translate.Auth token is required'),
        ];
        $request->validate($rules,$customMessages);

        $instamojo = InstamojoPayment::first();
        $instamojo->account_mode = $request->account_mode;
        $instamojo->api_key = $request->api_key;
        $instamojo->auth_token = $request->auth_token;
        $instamojo->currency_id = $request->currency_id;
        $instamojo->status = $request->status ? 1 : 0;
        $instamojo->save();

        if($request->file('image')) {
            $image_path = uploadFile($request->file('image'), 'uploads/website-images', $instamojo->image);
            $instamojo->image = $image_path;
            $instamojo->save();
        }

        $notification=trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

}
