<?php

namespace Modules\Currency\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Currency\app\Models\MultiCurrency;
use Modules\Currency\Http\Requests\CurrencyRequest;

use App\Models\PaypalPayment;
use App\Models\StripePayment;
use App\Models\RazorpayPayment;
use App\Models\Flutterwave;
use App\Models\BankPayment;
use App\Models\PaystackAndMollie;
use App\Models\InstamojoPayment;

class MultiCurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $multi_currencies = MultiCurrency::get();

        return view('currency::index', compact('multi_currencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('currency::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CurrencyRequest $request)
    {
        $currency = new MultiCurrency();

        if($request->is_default){
            MultiCurrency::where(['is_default' => 'yes'])->update(['is_default' => 'no']);
        }

        $currency->currency_name = $request->currency_name;
        $currency->country_code = $request->country_code;
        $currency->currency_code = $request->currency_code;
        $currency->currency_icon = $request->currency_icon;
        $currency->currency_rate = $request->currency_rate;
        $currency->is_default = $request->is_default ? 'yes' : 'no';
        $currency->currency_position = $request->currency_position;
        $currency->status = $request->status ? 'active' : 'inactive';
        $currency->save();

        $notification=trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $currency = MultiCurrency::findOrFail($id);

        return view('currency::edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CurrencyRequest $request, $id)
    {
        $currency = MultiCurrency::findOrFail($id);

        if($request->is_default){
            MultiCurrency::where(['is_default' => 'yes'])->update(['is_default' => 'no']);
        }

        $currency->currency_name = $request->currency_name;
        $currency->country_code = $request->country_code;
        $currency->currency_code = $request->currency_code;
        $currency->currency_icon = $request->currency_icon;
        $currency->currency_rate = $request->currency_rate;
        $currency->is_default = $request->is_default ? 'yes' : 'no';
        $currency->currency_position = $request->currency_position;
        $currency->status = $request->status ? 'active' : 'inactive';
        $currency->save();


        $notification=trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.multi-currency.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $currency = MultiCurrency::find($id);
        if($currency->id == 1){
            $notification = trans('translate.You can not delete USD currency');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $is_flutterwave = Flutterwave::where('currency_id', $id)->first();
        $is_instamojo = InstamojoPayment::where('currency_id', $id)->first();
        $is_paypal = PaypalPayment::where('currency_id', $id)->first();
        $is_paystack = PaystackAndMollie::where('paystack_currency_id', $id)->first();
        $is_mollie = PaystackAndMollie::where('mollie_currency_id', $id)->first();
        $is_razorpay = RazorpayPayment::where('currency_id', $id)->first();
        $is_stripe = StripePayment::where('currency_id', $id)->first();

        if($is_flutterwave || $is_instamojo || $is_paypal || $is_paystack || $is_mollie || $is_razorpay || $is_stripe){
            $notification = trans('translate.You can not delete this currency. Because there are one or more payment method has been created in this currency.');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }


        if($currency->is_default == 'yes'){
            MultiCurrency::where('id', 1)->update(['is_default' => 'yes']);
        }
        $currency->delete();

        $notification = trans('translate.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);


    }
}
