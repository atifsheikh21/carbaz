@extends('admin.master_layout')
@section('title')
<title>{{ __('translate.Basic Gateway') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Basic Gateway') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Payment Gateway') }} >> {{ __('translate.Basic Gateway') }}</p>
@endsection

@section('body-content')

<section class="crancy-adashboard crancy-show">
    <div class="container container__bscreen">
        <div class="row row__bscreen">
            <div class="col-12">
                <div class="crancy-body">
                    <!-- Dashboard Inner -->
                    <div class="crancy-dsinner">
                        <div class="crancy-personals mg-top-30">
                            <div class="row">
                                <div class="col-lg-3 col-md-2 col-12 crancy-personals__list">
                                    <div class="crancy-psidebar">
                                        <!-- Features Tab List -->
                                        <div class="list-group crancy-psidebar__list" id="list-tab" role="tablist">

                                            <a class="list-group-item active" data-bs-toggle="list" href="#id9" role="tab" aria-selected="true"><span class="crancy-psidebar__icon">
                                                <i class="fas fa-list    "></i>
                                                </span>
                                                <h4 class="crancy-psidebar__title">Worldpay</h4>
                                            </a>

                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-9 col-md-10 col-12  crancy-personals__content">
                                    <div class="crancy-ptabs">

                                        <div class="crancy-ptabs__inner">
                                            <div class="tab-content" id="nav-tabContent">
                                                <!--  Features Single Tab -->
                                                <div class="tab-pane fade active show" id="id9" role="tabpanel">
                                                    <form action="{{  route('admin.update-worldpay') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="crancy-ptabs__separate">
                                                                    <div class="crancy-ptabs__form-main">
                                                                        <div class="crancy__item-group">
                                                                            <h3 class="crancy__item-group__title">Worldpay Configuration</h3>
                                                                            <div class="crancy__item-form--group">
                                                                                <div class="row">

                                                                                    <div class="col-12">
                                                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                                                            <label class="crancy__item-label">{{__('Visibility Status')}} </label>
                                                                                            <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                                                                <label class="crancy__item-switch">
                                                                                                <input {{ $worldpay->status == 1 ? 'checked' : '' }} name="status" type="checkbox" >
                                                                                                <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-12">
                                                                                        <div class="row">
                                                                                            <div class="col-md-4">
                                                                                                <div class="crancy__item-form--group w-100 h-100">
                                                                                                    <label class="crancy__item-label">{{ __('translate.Image') }} 
                                                                                                        <i 
                                                                                                            class="fas fa-info-circle text-info"
                                                                                                            data-toggle="tooltip"
                                                                                                            data-placement="right"
                                                                                                            title="Recommended size: 120x30"
                                                                                                            style="cursor: pointer;"
                                                                                                        ></i>
                                                                                                    </label>
                                                                                                    <div class="crancy-product-card__upload crancy-product-card__upload--border">
                                                                                                        <input type="file" class="btn-check" name="image" id="input-img-worldpay" autocomplete="off" onchange="worldpayPreviewImage(event)">
                                                                                                        <label class="crancy-image-video-upload__label" for="input-img-worldpay">
                                                                                                            <img id="view_worldpay_img" src="{{ getImageOrPlaceholder($worldpay->image, '120x30') }}">
                                                                                                            <h4 class="crancy-image-video-upload__title">{{ __('translate.Click here to') }} <span class="crancy-primary-color">{{ __('translate.Choose File') }}</span> {{ __('translate.and upload') }} </h4>
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>

                                                                                    <div class="col-12">
                                                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                                                            <label class="crancy__item-label">{{ __('translate.Account Mode') }} * </label>
                                                                                            <select class="form-select crancy__item-input" name="account_mode">
                                                                                                <option {{ $paypal->account_mode == 'live' ? 'selected' : '' }} value="live">{{ __('translate.Live') }}</option>
                                                                                                <option {{ $paypal->account_mode == 'sandbox' ? 'selected' : '' }} value="sandbox">{{ __('translate.Sandbox') }}</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-12">
                                                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                                                            <label class="crancy__item-label">{{ __('translate.Currency') }} * </label>
                                                                                            <select class="form-select crancy__item-input" name="currency_id">
                                                                                                <option value="">{{ __('translate.Select') }}</option>
                                                                                                @foreach ($currency_list as $currency)
                                                                                                    <option {{ $paypal->currency_id == $currency->id ? 'selected' : '' }} value="{{ $currency->id }}">{{ $currency->currency_name }}
                                                                                                </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-12">
                                                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                                                            <label class="crancy__item-label">{{ __('translate.Client Id') }} * </label>
                                                                                            <input class="crancy__item-input" type="text" name="paypal_client_id" value="{{ $paypal->client_id }}">
                                                                                        </div>
                                                                                    </div>


                                                                                    <div class="col-12">
                                                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                                                            <label class="crancy__item-label">{{ __('translate.Secret Id') }} *</label>
                                                                                            <input class="crancy__item-input" type="text" name="paypal_secret_key" value="{{ $paypal->secret_id }}">
                                                                                        </div>
                                                                                    </div>


                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="mg-top-40">
                                                                            <button class="crancy-btn" type="submit">{{ __('translate.Update') }}</button>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Dashboard Inner -->
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


@push('js_section')

    <script>
        "use strict"

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

        function paypalPreviewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_paypal_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

        function worldpayPreviewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_worldpay_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

        function razorpayPreviewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_razorpay_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

        function flutterwavePreviewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_flutterwave_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

        function molliePreviewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_mollie_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

        function paystackPreviewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_paystack_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

        function instamojoPreviewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_instamojo_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

        function bankPreviewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_bank_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };

    </script>
@endpush
