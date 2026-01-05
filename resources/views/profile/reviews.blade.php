@extends('layout')
@section('title')
    <title>{{ __('translate.Review') }}</title>
@endsection
@section('body-content')

<main>
    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Review') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Review') }}</li>
                    </ol>
                </nav>
            </div>
            </div>
        </div>
    </section>
    <!-- banner-part-end -->

    <section class="dashboard">
        <div class="container">
            <div class="row">
                @include('profile.sidebar')
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="review-main">
                                <h3 class="review-main-taitel">{{ __('translate.My Review List') }}</h3>
                                <div class="review-main-item">
                                    @foreach ($reviews as $review)
                                        <div class="review-main-inner">
                                            <div class="review-main-inner-top">
                                                <ul class="icon">
                                                    @for ($i = 1; $i <= 5 ; $i++)
                                                        @if ($i <= $review->rating)
                                                            <li><span><i class="fa-solid fa-star"></i></span></li>
                                                        @else
                                                            <li><span><i class="fa-regular fa-star"></i></span></li>
                                                        @endif
                                                    @endfor
                                                </ul>

                                                <div class="text">
                                                    <span>{{ $review->created_at->format('d F Y') }}</span>
                                                </div>
                                            </div>

                                            <p class="review-dec">
                                                {{ html_decode($review->comment) }}</p>

                                            <div class="review-main-inner-btm">
                                                <div class="review-main-inner-btm-img">
                                                    <img src="{{ getImageOrPlaceholder($review?->car?->thumb_image, '64x64') }}" alt="img">
                                                </div>


                                                <div class="review-main-inner-btm-text">
                                                    <h3><a href="{{ route('listing', $review?->car?->slug) }}">{{ html_decode($review?->car?->title) }}</a></h3>


                                                    <span>@ {{ $review?->car?->dealer?->name }}</span>
                                                </div>

                                            </div>

                                        </div>
                                    @endforeach

                                </div>
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


