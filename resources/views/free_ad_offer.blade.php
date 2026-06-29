@extends('layout')
@section('title')
    <title>{{ __('Place Your Ad for Free') }}</title>
@endsection

@section('body-content')
<main>
    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }})"></div>
        <div class="container">
            <div class="col-lg-12">
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('Place Your Ad for Free') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Free Ad Offer') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

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
                        <span>{{ __('Limited Time Offer') }}</span>
                    </div>
                    <h2 class="pricing-titel">{{ __('Start Selling with a Free Ad Today') }}</h2>
                </div>
            </div>
            <div class="row mt-56px justify-content-center">
                <div class="col-xl-10 col-lg-12">
                    <div class="row pricing-mt justify-content-center">
                        <div class="col-lg-6 col-md-6">
                            <div class="pricing-item pricing-item-two">
                                <h4 class="pricing-text">{{ __('For Private Sellers') }}</h4>
                                <h2 class="pricing-text-box">
                                    {{ __('Free') }}
                                    <span>/{{ __('Limited Time') }}</span>
                                </h2>

                                <div class="pricing-item-box">
                                    <ul>
                                        <li>
                                            <span>
                                                <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5.36086 9.80735C5.22722 9.93101 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                </svg>
                                            </span>
                                            {{ __('Post your vehicle ad without any listing fee') }}
                                        </li>
                                        <li>
                                            <span>
                                                <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5.36086 9.80735C5.22722 9.93101 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                </svg>
                                            </span>
                                            {{ __('Reach genuine buyers looking for cars like yours') }}
                                        </li>
                                        <li>
                                            <span>
                                                <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5.36086 9.80735C5.22722 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                </svg>
                                            </span>
                                            {{ __('Quick and simple ad posting for individual sellers') }}
                                        </li>
                                    </ul>
                                </div>

                                @auth('web')
                                    <a href="{{ route('user.select-car-purpose') }}" class="thm-btn-two">{{ __('Place Free Ad') }}</a>
                                @else
                                    <a href="{{ route('login') }}" class="thm-btn-two">{{ __('Place Free Ad') }}</a>
                                @endauth
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="pricing-item pricing-item-two">
                                <h4 class="pricing-text">{{ __('For Dealers & Companies') }}</h4>
                                <h2 class="pricing-text-box">
                                    {{ __('Free') }}
                                    <span>/{{ __('Limited Time') }}</span>
                                </h2>

                                <div class="pricing-item-box">
                                    <ul>
                                        <li>
                                            <span>
                                                <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5.36086 9.80735C5.22722 9.93101 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                </svg>
                                            </span>
                                            {{ __('List your business inventory with no upfront ad cost') }}
                                        </li>
                                        <li>
                                            <span>
                                                <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5.36086 9.80735C5.22722 9.93101 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                </svg>
                                            </span>
                                            {{ __('Showcase your dealership or company to active buyers') }}
                                        </li>
                                        <li>
                                            <span>
                                                <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5.36086 9.80735C5.22722 9.93101 5.0449 10 4.8555 10C4.6661 10 4.48377 9.93101 4.35013 9.80735L0.314136 6.09406C-0.104712 5.70876 -0.104712 5.08398 0.314136 4.69941L0.819503 4.2344C1.23848 3.84911 1.91688 3.84911 2.33573 4.2344L4.8555 6.55244L11.6643 0.288972C12.0832 -0.096324 12.7623 -0.096324 13.1805 0.288972L13.6859 0.753976C14.1047 1.13927 14.1047 1.76393 13.6859 2.14863L5.36086 9.80735Z" />
                                                </svg>
                                            </span>
                                            {{ __('A special introductory offer for growing your online presence') }}
                                        </li>
                                    </ul>
                                </div>

                                <a href="{{ route('join-as-dealer') }}" class="thm-btn-two">{{ __('Place Free Ad') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
