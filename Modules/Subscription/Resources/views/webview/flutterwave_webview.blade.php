<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('Flutterwave Payment')}}</title>
    <link rel="icon" type="image/png" href="{{ asset($setting->favicon) }}">

    <script src="{{ asset('global/jquery-3.7.1.min.js') }}"></script>
    <script src="https://checkout.flutterwave.com/v3.js"></script>

</head>
<body>
    <p style="text-align: center">Please wait. Your payment is processing....</p>
    <p style="text-align: center">Do not press browser back or forward button while you are in payment page</p>

    {{-- start flutterwave payment --}}
    @php
        $payable_amount = $subscription_plan->plan_price * $flutterwave->currency->currency_rate;
        $payable_amount = round($payable_amount, 2);

    @endphp

    <script>
        flutterwavePayment();
        function flutterwavePayment() {
            FlutterwaveCheckout({
                public_key: "{{ $flutterwave->public_key }}",
                tx_ref: "{{ substr(rand(0,time()),0,10) }}",
                amount: {{ $payable_amount }},
                currency: "{{ $flutterwave->currency_code }}",
                country: "{{ $flutterwave->country_code }}",
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
                        url: "{{ url('user/flutterwave-webview-payment') }}" + "/" + "{{ $subscription_plan->id }}",
                        success: function (response) {
                            if(response.status == 'success'){
                                window.location.href = "{{ route('webview-success-payment') }}";
                            }else{
                                window.location.href = "{{ route('webview-faild-payment') }}";
                            }
                        },
                        error: function(err) {
                            window.location.href = "{{ route('webview-faild-payment') }}";
                        }
                    });
                },
                customizations: {
                title: "{{ $flutterwave->title }}",
                logo: "{{ asset($flutterwave->logo) }}",
                },
            });
        }
    </script>
    {{-- end flutterwave payment --}}

</body>
</html>
