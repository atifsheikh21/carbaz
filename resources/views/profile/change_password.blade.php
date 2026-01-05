@extends('layout')
@section('title')
    <title>{{ __('translate.Change Password') }}</title>
@endsection
@section('body-content')
<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Change Password') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Change Password') }}</li>
                    </ol>
                </nav>
            </div>
            </div>
        </div>
    </section>
    <!-- banner-part-end -->




    <!-- dashboard-part-start -->
    <section class="dashboard">
        <div class="container">
            <div class="row">
                @include('profile.sidebar')


                <div class="col-lg-9">
                    <!-- Change Password  -->

                    <div class="row login-bg">
                        <div class="col-lg-6">
                            <div class="login-head">
                                <h3>{{ __('translate.Change your Password') }}</h3>

                                <span>{{ __('translate.To change your password, please fill out the form below.') }}</span>
                            </div>


                            <form action="{{ route('user.update-password') }}" method="POST">
                                @csrf

                                <div class="login-form-item two">
                                    <div class="login-form-inner">
                                        <label for="exampleFormControlInput37" class="form-label">
                                            {{ __('translate.Current Password') }}
                                            <span>*</span> </label>
                                        <input type="password" class="form-control" id="exampleFormControlInput37"
                                            placeholder="........" name="current_password">


                                    </div>
                                </div>

                                <div class="login-form-item two">
                                    <div class="login-form-inner">
                                        <label for="exampleFormControlInput38" class="form-label">
                                            {{ __('translate.New Password') }}
                                            <span>*</span> </label>
                                        <input type="password" class="form-control" id="exampleFormControlInput38"
                                            placeholder="........" name="password">

                                    </div>
                                </div>

                                <div class="login-form-item two">
                                    <div class="login-form-inner">
                                        <label for="exampleFormControlInput39" class="form-label">
                                            {{ __('translate.Confirm Password') }}
                                            <span>*</span> </label>
                                        <input type="password" class="form-control" id="exampleFormControlInput39"
                                            placeholder="........" name="password_confirmation">

                                    </div>
                                </div>

                                <div class="login-form-item-btn">
                                    <button type="submit" class="thm-btn two">{{ __('translate.Update Password') }}</button>
                                    <a href="" class="thm-btn-two ">{{ __('translate.Cancel') }}</a>
                                </div>


                            </form>

                        </div>

                        <div class="col-lg-6">
                            <div class="login-img">
                                <img src="{{ getImageOrPlaceholder($setting->login_page_bg, '571x708') }}" alt="img">
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- dashboard-part-end -->

    @include('profile.logout')

</main>

@endsection
