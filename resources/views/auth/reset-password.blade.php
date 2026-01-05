@extends('layout')
@section('title')
    <title>{{ __('translate.Reset Password') }}</title>
@endsection

@section('body-content')

<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Reset Password') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Reset Password') }}</li>
                    </ol>
                </nav>
            </div>
            </div>
        </div>
    </section>
    <!-- banner-part-end -->



    <!-- contact-us-part-start -->
    <section class="login">
        <div class="container">
            <div class="row login-bg">
                <div class="col-lg-6">
                    <div class="login-head">
                        <h3>{{ __('translate.Reset Password') }}</h3>

                        <span>{{ __('translate.To reset your password, please fill out the form below') }}</span>
                    </div>


                    <form method="POST" action="{{ route('reset-password-store', $token) }}">
                        @csrf

                        <div class="login-form-item three">
                            <div class="login-form-inner">
                                <label for="exampleFormControlInput1" class="form-label">{{ __('translate.Email address') }}
                                    <span>*</span> </label>
                                <input type="email" class="form-control" id="exampleFormControlInput1"
                                    placeholder="{{ __('translate.Email address') }}" name="email" value="{{ $user->email }}">
                            </div>
                        </div>


                        <div class="login-form-item two">
                            <div class="login-form-inner">
                                <label class="form-label">{{ __('translate.Password') }}
                                    <span>*</span> </label>
                                <input type="password" class="form-control"
                                    placeholder="......" name="password" id="input_password">

                                <div class="icon" id="password-field">
                                    <span>
                                        <i class="fa fa-eye-slash" aria-hidden="true" ></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="login-form-item two">
                            <div class="login-form-inner">
                                <label class="form-label">{{ __('translate.Confirm Password') }}
                                    <span>*</span> </label>
                                <input type="password" class="form-control"
                                    placeholder="......" name="password_confirmation" id="input_password_confirm">

                                <div class="icon" id="password-field-confirm">
                                    <span>
                                        <i class="fa fa-eye-slash" aria-hidden="true" ></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($google_recaptcha->status==1)
                            <div class="login-form-item three">
                                <div class="login-form-inner">
                                    <div class="g-recaptcha" data-sitekey="{{ $google_recaptcha->site_key }}"></div>
                                </div>
                            </div>
                        @endif

                        <div class="login-form-item two">
                            <button type="submit" class="thm-btn-two">{{ __('translate.Reset Password') }}</button>
                        </div>

                    </form>


                    <div class="create-accoun-text">
                        <p>{{ __('translate.Back to sign-in page') }}<span><a href="{{ route('login') }}"> {{ __('translate.Click here') }}</a></span></p>
                    </div>


                </div>

                <div class="col-lg-6">
                    <div class="login-img">
                        <img src="{{ getImageOrPlaceholder($setting->login_page_bg, '571x708') }}" alt="img">
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- contact-us-part-end -->

</main>

@endsection


@push('js_section')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
    let is_password = true;
    let is_password_confirm = true;
    (function($) {
        "use strict"
        $(document).ready(function () {
            $("#password-field").on("click",function(e){
                is_password = !is_password;
                if(is_password){
                    $("#input_password").attr('type', 'password');

                    $("#password-field").html(`<span>
                                        <i class='fa fa-eye-slash' aria-hidden='true' ></i>
                                    </span>`)

                }else{
                    $("#input_password").attr('type', 'text');
                    $("#password-field").html(`<span>
                                        <i class='fa fa-eye' aria-hidden='true' ></i>
                                    </span>`)
                }
            })

            $("#password-field-confirm").on("click",function(e){
                is_password_confirm = !is_password_confirm;
                if(is_password_confirm){
                    $("#input_password_confirm").attr('type', 'password');

                    $("#password-field-confirm").html(`<span>
                                        <i class='fa fa-eye-slash' aria-hidden='true' ></i>
                                    </span>`)

                }else{
                    $("#input_password_confirm").attr('type', 'text');
                    $("#password-field-confirm").html(`<span>
                                        <i class='fa fa-eye' aria-hidden='true' ></i>
                                    </span>`)
                }
            })



            $(".login_with_google").on("click", function(e){
                window.location = "{{ route('login-google') }}";
            })

            $(".login_with_facebook").on("click", function(e){
                window.location = "{{ route('login-facebook') }}";
            })


        });
    })(jQuery);
</script>
@endpush
