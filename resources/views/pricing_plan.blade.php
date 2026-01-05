@extends('layout')
@section('title')
    <title>{{ __('translate.Pricing Plan') }}</title>
@endsection

@section('body-content')
<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }})"></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Pricing Plan') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Pricing Plan') }}</li>
                    </ol>
                </nav>
            </div>
            </div>
        </div>
    </section>
    <!-- banner-part-end -->

    <!-- dashboard-part-start -->

    <section class="pricing two">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="taitel two">
                        <div class="taitel-img">
                           <span>
                            <svg width="248" height="6" viewBox="0 0 248 6" fill="none"  xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 5C34.6259 1.98151 130.902 -2.24439 247 5" stroke="#405FF2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                           </span>
                        </div>
                        <span>{{ __('translate.Looking for Subscription') }} </span>
                    </div>
                    <h2 class="pricing-titel">{{ __('translate.Choose Our Pricing Plans') }}</h2>
                </div>
            </div>
            <div class="row mt-56px justify-content-center">
                <div class=" col-xl-12 col-lg-12">
                    <div class="row pricing-mt  ">
                        @foreach ($subscription_plans as $index => $subscription_plan)
                            <div class="col-lg-4">
                                <div class="pricing-item  {{ $index == 1 ? 'pricing-item-two' : '' }}">

                                    <h4 class="pricing-text">{{ $subscription_plan->plan_name }}</h4>
                                    <h2 class="pricing-text-box">

                                        @if (Session::get('currency_position') == 'before_price')
                                        <sup>{{ Session::get('currency_icon') }}</sup>{{ $subscription_plan->plan_price }}
                                        @elseif (Session::get('currency_position') == 'before_price_with_space')
                                        <sup>{{ Session::get('currency_icon') }}</sup> {{ $subscription_plan->plan_price }}
                                        @elseif (Session::get('currency_position') == 'after_price')
                                        {{ $subscription_plan->plan_price }}<sup>{{ Session::get('currency_icon') }}</sup>
                                        @elseif (Session::get('currency_position') == 'after_price_with_space')
                                        {{ $subscription_plan->plan_price }}<sup> {{ Session::get('currency_icon') }}</sup>
                                        @endif

                                         <span>
                                        @if ($subscription_plan->expiration_date == 'monthly')
                                        /{{ __('translate.Monthly') }}
                                        @elseif ($subscription_plan->expiration_date == 'yearly')
                                        /{{ __('translate.Yearly') }}
                                        @elseif ($subscription_plan->expiration_date == 'lifetime')
                                        /{{ __('translate.Lifetime') }}
                                        @endif


                                    </span> </h2>

                                    <div class="pricing-item-box">
                                        <ul>
                                            <li>
                                                <span>
                                                    <svg width="14" height="10" viewBox="0 0 14 10" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.36086 9.80735C5.22722 9.93101 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                    </svg>
                                                </span>
                                                {{ __('translate.Duration') }} : @if ($subscription_plan->expiration_date == 'monthly')
                                                {{ __('translate.Monthly') }}
                                                @elseif ($subscription_plan->expiration_date == 'yearly')
                                                {{ __('translate.Yearly') }}
                                                @elseif ($subscription_plan->expiration_date == 'lifetime')
                                                {{ __('translate.Lifetime') }}
                                                @endif
                                            </li>
                                            <li>
                                                <span>
                                                    <svg width="14" height="10" viewBox="0 0 14 10" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.36086 9.80735C5.22722 9.93101 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                    </svg>
                                                </span>
                                                {{ __('translate.Maximum Listings') }} : {{ $subscription_plan->max_car }}
                                            </li>
                                            
                                            @if($subscription_plan->featured_car == 0)
                                                <li class="pricing-item-box-two">
                                                    <span>
                                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M0.365448 11.6491C0.589929 11.8738 0.894351 12 1.21177 12C1.52918 12 1.83361 11.8738 2.05809 11.6491L6 7.70224L9.94191 11.6491C10.1677 11.8674 10.4701 11.9882 10.7839 11.9855C11.0978 11.9828 11.398 11.8567 11.62 11.6345C11.8419 11.4123 11.9678 11.1116 11.9705 10.7974C11.9733 10.4831 11.8526 10.1804 11.6346 9.95433L7.69264 6.00749L11.6346 2.06066C11.7489 1.95009 11.8401 1.81784 11.9028 1.67161C11.9656 1.52538 11.9986 1.36811 12 1.20897C12.0013 1.04982 11.971 0.892 11.9109 0.744702C11.8507 0.597404 11.7618 0.463583 11.6494 0.351048C11.537 0.238512 11.4033 0.149516 11.2562 0.0892518C11.1091 0.0289875 10.9515 -0.00133766 10.7925 4.52538e-05C10.6336 0.00142816 10.4765 0.0344918 10.3305 0.0973067C10.1844 0.160122 10.0523 0.25143 9.94191 0.365904L6 4.31274L2.05809 0.365904C1.94766 0.25143 1.81557 0.160122 1.66953 0.0973067C1.52348 0.0344918 1.3664 0.00142816 1.20746 4.52538e-05C1.04852 -0.00133766 0.890887 0.0289875 0.743773 0.0892518C0.596659 0.149516 0.463005 0.238512 0.35061 0.351048C0.238215 0.463583 0.14933 0.597404 0.0891405 0.744702C0.0289514 0.892 -0.00133599 1.04982 4.51974e-05 1.20897C0.00142638 1.36811 0.0344488 1.52538 0.0971854 1.67161C0.159922 1.81784 0.251117 1.95009 0.365448 2.06066L4.30736 6.00749L0.365448 9.95433C0.141034 10.1791 0.0149654 10.4839 0.0149654 10.8017C0.0149654 11.1195 0.141034 11.4243 0.365448 11.6491Z">
                                                        </path>
                                                    </svg>
                                                    </span>
                                                    {{ __('translate.Featured Listing') }}
                                                </li>
                                            @else
                                                <li>
                                                    <span>
                                                        <svg width="14" height="10" viewBox="0 0 14 10" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M5.36086 9.80735C5.22722 9.93101 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                        </svg>
                                                    </span>
                                                    {{ __('translate.Featured Listing') }}
                                                </li>
                                            @endif


                                            <li>
                                                <span>
                                                    <svg width="14" height="10" viewBox="0 0 14 10" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.36086 9.80735C5.22722 9.93101 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                    </svg>
                                                </span>
                                                {{ __('translate.Number of Featured') }} : {{ $subscription_plan->featured_car }}
                                            </li>


                                            <li>
                                                <span>
                                                    <svg width="14" height="10" viewBox="0 0 14 10" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.36086 9.80735C5.22722 9.93101 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                    </svg>
                                                </span>
                                                {{ __('translate.Listing Image') }} : {{ __('translate.Unlimited') }}
                                            </li>
                                            
                                            <li>
                                                <span>
                                                    <svg width="14" height="10" viewBox="0 0 14 10" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.36086 9.80735C5.22722 9.93101 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                    </svg>
                                                </span>
                                                {{ __('translate.Features & Aminities') }}
                                            </li>
                                            
                                            <li>
                                                <span>
                                                    <svg width="14" height="10" viewBox="0 0 14 10" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.36086 9.80735C5.22722 9.93101 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                    </svg>
                                                </span>
                                                {{ __('translate.24/7 Hours Help') }}
                                            </li>
                                        </ul>
                                    </div>

                                    <a href="{{ route('pricing-plan-enroll', $subscription_plan->id) }}" class="thm-btn-two">
                                        {{ __('translate.Enroll Now') }}
                                    </a>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- dashboard-part-end -->


</main>
@endsection
