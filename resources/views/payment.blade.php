@extends('layout')
@section('title')
    <title>{{ __('translate.Payment') }}</title>
@endsection

@section('body-content')
<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Payment') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Payment') }}</li>
                    </ol>
                </nav>
            </div>
            </div>
        </div>
    </section>
    <!-- banner-part-end -->



    <!-- dashboard-part-start -->

    <section class="pricing package-details">
        <div class="container">

            <div class="row">
                <div class="col-lg-8">
                <div class="alert alert-danger" role="alert">
                      {{ __('translate.When you purchase new plan, your previous package features will be destroy') }}
                    </div>
                    <div class="package-details-item">
                        <div class="package-details-table">
                            <table class=" table table-bordered ">
                                <tr>
                                    <td>{{ __('translate.Package') }}</td>
                                    <td>{{ $subscription_plan->plan_name }}</td>
                                </tr>

                                <tr>
                                    <td>{{ __('translate.Price') }}</td>
                                    <td>{{ currency($subscription_plan->plan_price) }}</td>
                                </tr>

                                <tr>
                                    <td>{{ __('translate.Expiration') }}</td>
                                    <td>
                                        @if ($subscription_plan->expiration_date == 'monthly')
                                        {{ __('translate.Monthly') }}
                                        @elseif ($subscription_plan->expiration_date == 'yearly')
                                        {{ __('translate.Yearly') }}
                                        @elseif ($subscription_plan->expiration_date == 'lifetime')
                                        {{ __('translate.Lifetime') }}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ __('translate.Number of Car') }}</td>
                                    <td>
                                        {{ $subscription_plan->max_car }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ __('translate.Number of Featured Car') }}</td>
                                    <td>
                                        {{ $subscription_plan->featured_car }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ __('translate.Listing Image') }}</td>
                                    <td>
                                        {{ __('translate.Unlimited') }}
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="payment">
                        <h2 class="payment-txt">{{ __('translate.Payment Gateway') }}</h2>
                        <div class="payment-inner">
                            @if ($stripe->status == 1)
                                <div class="payment-inner-item modal-btn" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ getImageOrPlaceholder($stripe->image, '120x30') }}" alt="img">
                                    </div>
                                    <input type="button" class="payment-inner-item-input">
                                </div>
                            @endif


                            @if ($paypal->status == 1)
                                <a href="{{ route('pay-via-paypal', $subscription_plan->id) }}" class="payment-inner-item modal-btn">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ getImageOrPlaceholder($paypal->image, '120x30') }}" alt="img">
                                    </div>

                                    <input type="button" class="payment-inner-item-input">
                                </a>
                            @endif

                            @if ($razorpay->status == 1)
                            <div class="payment-inner-item" id="razorpayBtn">
                                <div class="payment-inner-item-label">
                                    <img src="{{ getImageOrPlaceholder($razorpay->image, '120x30') }}" alt="img">
                                </div>

                                <input type="button" class="payment-inner-item-input">
                            </div>

                            <form action="{{ route('pay-via-razorpay', $subscription_plan->id) }}" method="POST" class="d-none">
                                @csrf
                                @php
                                    $payable_amount = $subscription_plan->plan_price * $razorpay->currency->currency_rate;
                                    $payable_amount = round($payable_amount, 2);
                                @endphp
                                <script src="https://checkout.razorpay.com/v1/checkout.js"
                                        data-key="{{ $razorpay->key }}"
                                        data-currency="{{ $razorpay->currency->currency_code }}"
                                        data-amount= "{{ $payable_amount * 100 }}"
                                        data-buttontext="{{ __('translate.Pay') }} {{ $payable_amount }} {{ $razorpay->currency->currency_code }}"
                                        data-name="{{ $razorpay->name }}"
                                        data-description="{{ $razorpay->description }}"
                                        data-image="{{ asset($razorpay->image) }}"
                                        data-prefill.name=""
                                        data-prefill.email=""
                                        data-theme.color="{{ $razorpay->color }}">
                                </script>
                            </form>

                            @endif

                            @if ($flutterwave->status == 1)
                                <div class="payment-inner-item" id="flutterwavePayment">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ getImageOrPlaceholder($flutterwave->logo, '120x30') }}" alt="img">
                                    </div>

                                    <input type="button" class="payment-inner-item-input">
                                </div>
                            @endif

                            @if ($paystack->paystack_status == 1)
                                <div class="payment-inner-item" id="paystackPayment">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ getImageOrPlaceholder($paystack->paystack_image, '120x30') }}" alt="img">
                                    </div>

                                    <input type="submit" class="payment-inner-item-input">
                                </div>
                            @endif


                            @if ($mollie->mollie_status == 1)
                                <a href="{{ route('pay-via-mollie',$subscription_plan->id) }}" class="payment-inner-item">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ getImageOrPlaceholder($mollie->mollie_image, '120x30') }}" alt="img">
                                    </div>

                                    <input type="submit" class="payment-inner-item-input">
                                </a>
                            @endif

                            @if ($instamojo->status == 1)
                                <a href="{{ route('pay-via-instamojo',$subscription_plan->id) }}" class="payment-inner-item">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ getImageOrPlaceholder($instamojo->image, '120x30') }}" alt="img">
                                    </div>

                                    <input type="submit" class="payment-inner-item-input">
                                </a>
                            @endif


                            @if ($bank->status == 1)
                                <div class="payment-inner-item" data-bs-toggle="modal"
                                data-bs-target="#exampleModal7">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ getImageOrPlaceholder($bank->image, '120x30') }}" alt="img">
                                    </div>

                                    <input type="submit" class="payment-inner-item-input">
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- dashboard-part-end -->


     <!-- Stripe Modal -->
     <div class="modal payment-modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('translate.Pay via Stripe') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="payment-modal-item">
                        <h4>{{ __('translate.Amount') }}<span>{{ currency($subscription_plan->plan_price) }}</span></h4>
                    </div>

                        <form role="form" action="{{ route('pay-via-stripe', $subscription_plan->id) }}" method="POST" class="require-validation payment-modal-from" data-cc-on-file="false" data-stripe-publishable-key="{{ $stripe->stripe_key }}" id="payment-form">
                            @csrf

                        <div class="payment-modal-from-item">
                            <div class="payment-modal-from-inner">
                                <input type="text" class="card-number form-control" name="card_number" placeholder="{{ __('translate.Card Number') }}">
                            </div>
                        </div>
                        <div class="payment-modal-from-item">
                            <div class="payment-modal-from-inner">
                                <input type="text" class="card-expiry-month form-control" name="month" placeholder="{{ __('translate.Expired Month') }}">
                            </div>
                            <div class="payment-modal-from-inner">
                                <input type="text" class="card-expiry-year form-control" name="year" placeholder="{{ __('translate.Expired Year') }}">
                            </div>
                        </div>
                        <div class="payment-modal-from-item">
                            <div class="payment-modal-from-inner">
                                <input type="text" class="card-cvc form-control" name="cvc" placeholder="{{ __('translate.CVC') }}">
                            </div>
                        </div>

                        <div class="payment-modal-from-item stripe_error d-none">
                            <div class="payment-modal-from-inner">
                                <div class='alert-danger alert '>{{ __('translate.Please provide your valid card information') }}</div>
                            </div>
                        </div>


                        <div class="payment-modal-from-item">
                            <div class="payment-modal-from-inner">
                                <button class="thm-btn-two" type="submit">{{ __('translate.Payment Now') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <!-- Bank Modal -->
        <div class="modal bank-payment-modal fade" id="exampleModal7" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">{{ __('translate.Bank Payment') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="payment-modal-item">
                            <h4>{{ __('translate.Amount') }}<span>{{ currency($subscription_plan->plan_price) }}</span></h4>
                        </div>

                        <div class="bank-payment-modal-txt">
                            {!! clean(nl2br($bank->account_info)) !!}
                        </div>

                        <form class="payment-modal-from" action="{{ route('pay-via-bank', $subscription_plan->id) }}" method="POST">
                            @csrf
                            <div class="payment-modal-from-item">
                                <div class="payment-modal-from-inner">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                                        placeholder="{{ __('translate.Transaction information') }}" name="tnx_info"></textarea>
                                </div>
                            </div>
                            <div class="payment-modal-from-item">
                                <div class="payment-modal-from-inner">
                                    <button class="thm-btn-two" type="submit">{{ __('translate.Payment Now') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


</main>
@endsection


@push('js_section')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script>
        "use strict";
        $(function() {
            var $form = $(".require-validation");
            $('form.require-validation').bind('submit', function(e) {
                var $form         = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                                    'input[type=text]', 'input[type=file]',
                                    'textarea'].join(', '),
                $inputs       = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.stripe_error'),
                valid         = true;
                $errorMessage.addClass('d-none');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('d-none');
                        e.preventDefault();
                    }
                });

                if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
                }

            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.stripe_error')
                        .removeClass('d-none')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    var token = response['id'];
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
            }

            $("#razorpayBtn").on("click", function(){
                $(".razorpay-payment-button").click();
            })

        });
    </script>

    {{-- start flutterwave payment --}}
    @if ($flutterwave->status == 1)
        <script src="https://checkout.flutterwave.com/v3.js"></script>

        @php
            $payable_amount = $subscription_plan->plan_price * $flutterwave->currency->currency_rate;
            $payable_amount = round($payable_amount, 2);


        @endphp

        <script>
            "use strict";
            $(function() {
                $("#flutterwavePayment").on("click", function(){

                    var isDemo = "{{ env('APP_MODE') }}"
                    if(isDemo == 'DEMO'){
                        toastr.error('This Is Demo Version. You Can Not Change Anything');
                        return;
                    }

                    FlutterwaveCheckout({
                        public_key: "{{ $flutterwave->public_key }}",
                        tx_ref: "{{ substr(rand(0,time()),0,10) }}",
                        amount: {{ $payable_amount }},
                        currency: "{{ $flutterwave->currency->currency_code }}",
                        country: "{{ $flutterwave->currency->country_code }}",
                        payment_options: " ",
                        customer: {
                        email: "{{ $user->email }}",
                        phone_number: "{{ $user->phone }}",
                        name: "{{ $user->name }}",
                        },
                        callback: function (data) {

                            var tnx_id = data.transaction_id;
                            var _token = "{{ csrf_token() }}";
                            $.ajax({
                                type: 'post',
                                data : {tnx_id,_token},
                                url: "{{ url('pay-via-flutterwave') }}" + "/" + "{{ $subscription_plan->id }}",
                                success: function (response) {

                                    if(response.status == 'success'){
                                        toastr.success(response.message);
                                        window.location.href = "{{ route('user.orders') }}";
                                    }else{
                                        toastr.error(response.message);
                                        window.location.reload();
                                    }
                                },
                                error: function(err) {
                                    toastr.error("{{ __('translate.Something went wrong, please try again') }}");
                                    window.location.reload();
                                }
                            });
                        },
                        customizations: {
                        title: "{{ $flutterwave->title }}",
                        logo: "{{ asset($flutterwave->logo) }}",
                        },
                    });
                })
            });
        </script>

    @endif

    {{-- end flutterwave payment --}}

    {{-- start paystack payment --}}

    @if ($paystack->paystack_status == 1)
        <script src="https://js.paystack.co/v1/inline.js"></script>

        @php

            $public_key = $paystack->paystack_public_key;
            $currency = $paystack->paystack_currency->currency_code;
            $currency = strtoupper($currency);

            $ngn_amount = $subscription_plan->plan_price * $paystack->paystack_currency->currency_rate;
            $ngn_amount = $ngn_amount * 100;
            $ngn_amount = round($ngn_amount);

        @endphp

        <script>
            "use strict";
            $(function() {
                $("#paystackPayment").on("click", function(){

                    var isDemo = "{{ env('APP_MODE') }}"
                    if(isDemo == 'DEMO'){
                        toastr.error('This Is Demo Version. You Can Not Change Anything');
                        return;
                    }

                    var handler = PaystackPop.setup({
                                    key: '{{ $public_key }}',
                                    email: '{{ $user->email }}',
                                    amount: '{{ $ngn_amount }}',
                                    currency: "{{ $currency }}",
                                    callback: function(response){
                                        let reference = response.reference;
                                        let tnx_id = response.transaction;
                                        let _token = "{{ csrf_token() }}";
                                        $.ajax({
                                            type: "get",
                                            data: {reference, tnx_id, _token},
                                            url: "{{ url('pay-via-paystack') }}" + "/" + "{{ $subscription_plan->id }}",
                                            success: function(response) {
                                                if(response.status == 'success'){
                                                    toastr.success(response.message);
                                                    window.location.href = "{{ route('user.orders') }}";
                                                }else{
                                                    toastr.error(response.message);
                                                    window.location.reload();
                                                }
                                            },
                                            error: function(response){
                                                    toastr.error('Server Error');
                                                    window.location.reload();
                                            }
                                        });
                                    },
                                    onClose: function(){
                                        alert('window closed');
                                    }
                                });
                        handler.openIframe();

                })
            });
        </script>

    @endif

    {{-- end paystack payment --}}
@endpush
