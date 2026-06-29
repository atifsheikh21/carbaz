@extends('layout')
@section('title')
    <title>{{ __('translate.Messages') }}</title>
@endsection

@section('body-content')
<main>
    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb, '1920x150') }}) ;"></div>
        <div class="container">
            <div class="col-lg-12">
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('Messages') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Messages') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="dashboard">
        <div class="container">
            <div class="row">
                @include('profile.sidebar')

                <div class="col-lg-9">
                    <div class="dashboard-item">
                        <div class="dashboard-inner-text">
                            <h5>{{ __('Messages') }}</h5>
                        </div>

                        <div class="text-center" style="padding:60px 20px;">
                            <p style="font-size:18px;color:#6b7280;margin:0;">{{ __('This feature will be available soon.') }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>

@include('profile.logout')

@endsection
