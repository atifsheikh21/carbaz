@extends('layout')
@section('title')
    <title>{{ html_decode($car_part?->frontTranslate?->title) }}</title>
@endsection

@section('body-content')
<main>
    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
            <div class="col-lg-12">
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ html_decode($car_part?->frontTranslate?->title) }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('car-parts') }}">{{ __('translate.Car Parts') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ html_decode($car_part?->frontTranslate?->title) }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="brand-car brand-car-two py-120px">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5">
                    <img style="width:100%; border-radius:12px;" src="{{ getImageOrPlaceholder($car_part->thumb_image, '600x600') }}" alt="thumb">
                </div>
                <div class="col-lg-7">
                    <h2 style="margin-bottom:10px;">{{ html_decode($car_part?->frontTranslate?->title) }}</h2>

                    <h4 style="margin-bottom:14px;">
                        @if ($car_part->offer_price)
                            {{ currency($car_part->offer_price) }}
                        @else
                            {{ currency($car_part->regular_price) }}
                        @endif
                    </h4>

                    <div style="margin-bottom:14px;">
                        <strong>{{ __('translate.Condition') }}</strong>
                        <span>{{ $car_part->condition }}</span>
                    </div>

                    @if ($car_part->part_number)
                        <div style="margin-bottom:14px;">
                            <strong>{{ __('translate.Part Number') }}</strong>
                            <span>{{ $car_part->part_number }}</span>
                        </div>
                    @endif

                    @if ($car_part->compatibility)
                        <div style="margin-bottom:14px;">
                            <strong>{{ __('translate.Compatibility') }}</strong>
                            <span>{{ $car_part->compatibility }}</span>
                        </div>
                    @endif

                    <div>
                        {!! clean($car_part?->frontTranslate?->description) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
