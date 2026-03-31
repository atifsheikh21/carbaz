@extends('layout')
@section('title')
    @php
        $__detailTranslationTitle = $car_part?->translations?->firstWhere('lang_code', front_lang())
            ?? $car_part?->translations?->firstWhere('lang_code', 'en');
    @endphp
    <title>{{ html_decode($__detailTranslationTitle?->title) }}</title>
@endsection

@push('style_section')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
        .listing-detail-hero{
            background: #e9e6e3;
            padding: 60px 0;
        }
        .listing-detail-hero .container{
            max-width: 1200px;
        }
        .listing-detail-hero__grid{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
        }
        .listing-detail-hero__media{
            max-width: 100%;
            display: flex;
            flex-direction: column;
            margin-top: 0 !important;
        }
        .listing-detail-hero .inventory-details-slick-for{
            margin: 0;
        }
        .listing-detail-hero .inventory-details-slick-for .inventory-details-slick-img{
            height: 430px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            border: 8px solid #2f2f2f;
            background: #f4f4f4;
            overflow: hidden;
        }
        .listing-detail-hero .inventory-details-slick-for .inventory-details-slick-img img{
            width: 100%;
            height: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 0;
            border: 0;
            background: transparent;
        }
        .listing-detail-hero .inventory-details-slick-nav{
            margin-top: 18px;
        }
        .listing-detail-hero__mobile-image{
            display: none;
        }
        .listing-detail-hero .inventory-details-slick-nav .inventory-details-slick-img img{
            border-radius: 10px;
            background: #f4f4f4;
        }
        .inventory-details .inventory-details-slick-nav .inventory-details-slick-img img{
            width: 100%;
            height: 80px !important;
            object-fit: cover;
        }
        .listing-detail-hero__right{
            min-height: 520px;
            display: flex;
            flex-direction: column;
        }
        .listing-detail-hero__left-info{
            margin-top: auto;
        }
        .listing-detail-hero__left-title{
            font-size: 34px;
            line-height: 1.08;
            font-weight: 900;
            color: #111;
            letter-spacing: -0.02em;
            margin-top: 20px;
            margin-bottom: 8px;
        }
        .listing-detail-hero__left-subtitle{
            color: rgba(17,17,17,.65);
            font-size: 13px;
            margin-bottom: 14px;
        }
        .listing-detail-hero__meta{
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }
        .listing-detail-hero__meta-type{
            font-size: 12px;
            font-weight: 800;
        }
        .listing-detail-hero__meta-type--dealer{
            color: #b60304;
        }
        .listing-detail-hero__meta-type--private{
            color: #b60304;
        }
        .listing-detail-hero__meta-sep{
            color: rgba(0,0,0,.65);
        }
        .listing-detail-hero__meta-name{
            color: #23a549;
            font-weight: 800;
        }
        .listing-detail-hero__actions{
            display: flex;
            flex-wrap: nowrap;
            border: 2px solid #2b2b2b;
            border-radius: 999px;
            overflow: hidden;
            background: #e3e5ea;
            gap: 0;
            justify-content: center;
        }
        .listing-detail-hero__actions a{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 13px 16px;
            font-weight: 800;
            letter-spacing: 0.08em;
            background: #7cb4ff;
            color: white;
            line-height: 1;
            flex: 1 1 0;
            min-width: 0;
            box-sizing: border-box;
            border-radius: 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-decoration: none;
            text-transform: uppercase;
        }
        .listing-detail-hero__actions-city{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 20px;
            border-right: 1px solid #2b2b2b;
            color: #fff;
            background: #7cb4ff;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            flex: 0 0 auto;
            white-space: nowrap;
        }
        .listing-detail-hero__keyinfo{
            margin-top: 22px;
            display: grid;
            gap: 15px 28px;
            color: #474749;
            font-weight: 600;
            text-transform: uppercase;
            justify-content: flex-start;
        }
        .listing-detail-hero__keyinfo .kicon{
            width: 28px;
            height: 28px;
            border: 1px solid rgba(0,0,0,.25);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            color: #2b2b2b;
        }
        .listing-detail-hero__price{
            margin-top: 40px;
            font-size: 55px;
            font-weight: 700;
            color: #2b2b2b;
            letter-spacing: -0.02em;
            text-align: left;
        }
        .listing-detail-description{
            padding: 60px 0;
            background: #e9e6e3;
        }
        .listing-detail-description .container{
            max-width: 1200px;
        }
        .listing-detail-description__title{
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #111;
            margin-bottom: 10px;
        }
        .listing-detail-description__body{
            color: rgba(17,17,17,.75);
            line-height: 1.65;
            font-size: 16px;
        }
        @media (max-width: 1199.98px){
            .listing-detail-hero__grid{grid-template-columns: 520px 1fr;gap: 60px;}
        }
        @media (max-width: 991.98px){
            .listing-detail-hero__grid{grid-template-columns: 1fr;gap: 30px;}
            .listing-detail-hero__media{max-width: 100%;margin-top:0 !important;}
            .listing-detail-hero__right{min-height: 0;}
            .listing-detail-hero__keyinfo{margin-top: 35px;}
            .listing-detail-hero__price{margin-top: 35px;}
            .listing-detail-hero__mobile-image{display:block;margin-bottom:14px;}
            .inventory-details-slick-for{display:none;}
            .inventory-details-slick-nav{display:none;}
        }

        .cd-lightbox{
            position: fixed !important;
            top: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 9999999 !important;
            display: none;
            overflow: hidden;
        }
        .cd-lightbox.is-open{ display: block; }
        .cd-lightbox__backdrop{
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,.86);
            backdrop-filter: blur(6px);
        }
        .cd-lightbox__dialog{
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .cd-lightbox__topbar{
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #fff;
        }
        .cd-lightbox__counter{
            margin-right: auto;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .04em;
        }
        .cd-lightbox__close{
            width: 40px;
            height: 40px;
            border: 0;
            border-radius: 10px;
            background: rgba(255,255,255,.14);
            color: #fff;
            font-size: 24px;
            line-height: 1;
        }
        .cd-lightbox__main{
            flex: 1 1 auto;
            min-height: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
        }
        .cd-lightbox__swiper{
            width: min(1100px, 100%);
            height: 100%;
        }
        .cd-lightbox__swiper .swiper-slide,
        .cd-lightbox__swiper .swiper-zoom-container{
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }
        .cd-lightbox__swiper img{
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
        .cd-lightbox__thumbs{
            flex: 0 0 auto;
            padding: 12px 16px 18px;
        }
        .cd-lightbox__thumbs .swiper{
            width: min(1100px, 100%);
            margin: 0 auto;
        }
        .cd-lightbox__thumbs .swiper-slide{
            opacity: .55;
            cursor: pointer;
        }
        .cd-lightbox__thumbs .swiper-slide-thumb-active{ opacity: 1; }
        .cd-lightbox__thumbs img{
            width: 100%;
            height: 72px;
            object-fit: cover;
            display: block;
            border-radius: 10px;
            border: 2px solid rgba(255,255,255,.18);
            background: #111;
        }
        .cd-lightbox .swiper-button-next,
        .cd-lightbox .swiper-button-prev{
            color: #fff;
        }
        .cd-lightbox .swiper-button-next::after,
        .cd-lightbox .swiper-button-prev::after{
            font-size: 22px;
            font-weight: 900;
        }
        @media (max-width: 767.98px){
            .cd-lightbox__main{ padding: 0 10px; }
            .cd-lightbox__thumbs img{ height: 56px; }
        }
    </style>
@endpush

@section('body-content')
<main>
    @php
        $partTranslation = $car_part?->translations?->firstWhere('lang_code', front_lang())
            ?? $car_part?->translations?->firstWhere('lang_code', 'en');
        $partTitle = html_decode($partTranslation?->title);
        $seller = $car_part?->agent;
        $isDealerSeller = (bool) ($seller?->is_dealer ?? false);
        $isPartSeller = (bool) ($seller?->is_part_seller ?? false);
        $sellerDisplayName = $isDealerSeller && $isPartSeller && !empty($seller?->part_company_name)
            ? html_decode($seller?->part_company_name)
            : html_decode($seller?->name);
        $sellerTypeLabel = $isDealerSeller
            ? ($isPartSeller ? 'Vehicle Part Seller/Accessories' : 'Dealer')
            : 'Private';
        $partAddress = $isDealerSeller && $isPartSeller
            ? html_decode($seller?->part_company_address)
            : html_decode($partTranslation?->address);
        $partCity = null;
        if (!empty($partAddress)) {
            $addressParts = array_values(array_filter(array_map('trim', explode(',', $partAddress))));
            if (count($addressParts) >= 2) {
                $partCity = $addressParts[1];
            } elseif (count($addressParts) === 1) {
                $partCity = $addressParts[0];
            }
        }
        $partDescription = $partTranslation?->description;
        $rawPrice = $car_part->offer_price ?: $car_part->regular_price;
        $numericPrice = is_numeric($rawPrice) ? (float) $rawPrice : null;

        $partImages = [];
        if (!empty($car_part->thumb_image)) {
            $partImages[] = $car_part->thumb_image;
        }
        if (!empty($car_part?->galleries)) {
            foreach ($car_part->galleries as $__g) {
                if (!empty($__g->image)) {
                    $partImages[] = $__g->image;
                }
            }
        }
        $partImages = array_values(array_unique(array_filter($partImages)));
        $firstImage = $partImages[0] ?? null;

        $__sellerPhoneRaw = (string) ($seller?->phone ?? '');
        $__sellerPhone = preg_replace('/\D+/', '', $__sellerPhoneRaw);
        $__whatsAppPhone = $__sellerPhone;
        $__picsCount = is_countable($partImages) ? count($partImages) : 0;
    @endphp

    <div class="lp-mobile d-block d-md-none lp-detail">
        <div class="lp-mobile__filter">
            <button class="lp-mobile__filter-label" type="button" data-bs-toggle="offcanvas" data-bs-target="#lpMobileFilterPartDetail" aria-controls="lpMobileFilterPartDetail">Filter</button>
            <form class="lp-mobile__filter-form" method="GET" action="{{ route('car-parts') }}">
                <input class="lp-mobile__filter-input" type="text" name="search" value="{{ request()->get('search') }}" placeholder="search car & part by key word">
            </form>
        </div>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="lpMobileFilterPartDetail" aria-labelledby="lpMobileFilterPartDetailLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="lpMobileFilterPartDetailLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="d-grid gap-2">
                    <a class="btn btn-dark" href="{{ route('car-parts', request()->query()) }}">Open filters</a>
                    <a class="btn btn-light" href="{{ route('car-parts') }}">Browse all parts</a>
                </div>
            </div>
        </div>

        <div class="lp-detail__nav">
            <a class="lp-detail__back" href="{{ route('car-parts') }}">back</a>
            <div class="lp-detail__spacer"></div>
            <a class="lp-detail__search" href="{{ route('home') }}">HOME</a>
        </div>

        <div class="lp-mobile-card lp-detail-card">
            <div class="lp-mobile-card__top">
                <div class="lp-mobile-card__top-left">{{ strtoupper(trim((string) $sellerDisplayName)) ?: ' ' }}</div>
                <div class="lp-mobile-card__top-right">{{ !empty($car_part?->brand?->name) ? strtoupper(trim((string) html_decode($car_part->brand->name))) : ' ' }}</div>
            </div>

            <div class="lp-detail__media js-cp-open-lightbox" data-cp-index="0" role="button" tabindex="0">
                @if($firstImage)
                    <img class="lp-detail__main-img" src="{{ getImageOrPlaceholder($firstImage, '920x636') }}" alt="img">
                @else
                    <img class="lp-detail__main-img" src="{{ getImageOrPlaceholder($car_part->thumb_image, '920x636') }}" alt="img">
                @endif

                @if($__picsCount > 0)
                    <div class="lp-detail__pics">+{{ $__picsCount }} PIC</div>
                @endif
            </div>

            <div class="lp-detail__thumbs">
                @php
                    $__thumbShow = array_slice($partImages, 0, 3);
                    $__extra = max($__picsCount - count($__thumbShow), 0);
                @endphp
                @foreach($__thumbShow as $__t)
                    <div class="lp-detail__thumb js-cp-open-lightbox" data-cp-index="{{ $loop->index }}" role="button" tabindex="0">
                        <img src="{{ getImageOrPlaceholder($__t, '216x148') }}" alt="thumb">
                    </div>
                @endforeach
                @if($__extra > 0)
                    <div class="lp-detail__thumb lp-detail__thumb--more js-cp-open-lightbox" data-cp-index="{{ count($__thumbShow) }}" role="button" tabindex="0">
                        <span>+{{ $__extra }} PIC</span>
                    </div>
                @endif
            </div>

            <div class="lp-detail__body">
                <div class="lp-detail__title">{{ strtoupper(trim((string) $partTitle)) }}</div>

                <div class="lp-detail__specs">
                    @if(!empty($car_part?->brand?->name))<div><span>brand</span>{{ html_decode($car_part->brand->name) }}</div>@endif
                    @if(!empty($car_part->condition))<div><span>condition</span>{{ html_decode($car_part->condition) }}</div>@endif
                    @if(!empty($car_part->part_number))<div><span>part #</span>{{ html_decode($car_part->part_number) }}</div>@endif
                    @if(!empty($car_part->compatibility))<div><span>compatibility</span>{{ html_decode($car_part->compatibility) }}</div>@endif
                </div>

                <div class="lp-detail__price-row">
                    <div class="lp-detail__label">
                        @if(!empty($seller?->username))
                            <a href="{{ route('dealer', $seller->username) }}" style="text-decoration:none;color:inherit;">
                                {{ $sellerTypeLabel }} | {{ $sellerDisplayName }}
                            </a>
                        @else
                            {{ $sellerTypeLabel }} | {{ $sellerDisplayName }}
                        @endif
                    </div>
                    <div class="lp-detail__price">
                        @if (!is_null($numericPrice))
                            €{{ number_format($numericPrice, 0, '.', ',') }}
                        @else
                            {{ currency($rawPrice) }}
                        @endif
                    </div>
                </div>

                <div class="lp-detail__section">
                    <div class="lp-detail__section-title">Description</div>
                    <div class="lp-detail__section-body">
                        {!! clean($partDescription) !!}
                    </div>
                </div>

                @if($partAddress)
                    <div class="lp-detail__section">
                        <div class="lp-detail__section-title">{{ $sellerTypeLabel }} address</div>
                        <div class="lp-detail__section-body">{{ $partAddress }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="lp-detail__contactbar">
            <button class="lp-detail__contactbtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#lpContactPartSeller" aria-controls="lpContactPartSeller">CONTACT SELLER</button>
        </div>

        <div class="offcanvas offcanvas-bottom" tabindex="-1" id="lpContactPartSeller" aria-labelledby="lpContactPartSellerLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="lpContactPartSellerLabel">Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="lp-detail__actions">
                    @php
                        $__waText = rawurlencode('Hi, I am interested in your car part ad: ' . (string) $partTitle);
                    @endphp
                    @auth('web')
                        <a class="lp-detail__action" href="{{ $__whatsAppPhone ? 'https://wa.me/'.$__whatsAppPhone.'?text='.$__waText : '#' }}" target="_blank">whatsapp chat</a>
                        <a class="lp-detail__action" href="{{ $__sellerPhoneRaw ? 'tel:'.html_decode($__sellerPhoneRaw) : '#' }}">call</a>
                        <a class="lp-detail__action" href="{{ !empty($seller?->email) ? 'mailto:'.html_decode($seller?->email) : '#' }}">email</a>
                    @else
                        <a class="lp-detail__action" href="{{ route('login') }}">whatsapp chat</a>
                        <a class="lp-detail__action" href="{{ route('login') }}">call</a>
                        <a class="lp-detail__action" href="{{ route('login') }}">email</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="d-none d-md-block">
        <section class="inner-banner">
            <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
            <div class="container">
                <div class="col-lg-12">
                    <div class="inner-banner-df">
                        <h1 class="inner-banner-taitel">{{ $partTitle }}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('car-parts') }}">{{ __('translate.Car Parts') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $partTitle }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <section class="inventory-details listing-detail-hero">
            <div class="container">
                <div class="listing-detail-hero__grid">
                    <div class="listing-detail-hero__media">
                        @if($firstImage)
                            <div class="listing-detail-hero__mobile-image">
                                <img src="{{ getImageOrPlaceholder($firstImage, '920x636') }}" alt="img">
                            </div>
                        @endif

                        <div class="inventory-details-slick-for">
                            @foreach ($partImages as $img)
                                <div class="inventory-details-slick-img js-cp-open-lightbox" data-cp-index="{{ $loop->index }}" role="button" tabindex="0">
                                    <img src="{{ getImageOrPlaceholder($img, '920x636') }}" alt="img">
                                </div>
                            @endforeach
                        </div>

                        <div class="inventory-details-slick-nav">
                            @foreach ($partImages as $img)
                                <div class="inventory-details-slick-img js-cp-open-lightbox" data-cp-index="{{ $loop->index }}" role="button" tabindex="0">
                                    <img src="{{ getImageOrPlaceholder($img, '216x148') }}" alt="img">
                                </div>
                            @endforeach
                        </div>

                        <div class="cd-lightbox" id="cpLightbox" aria-hidden="true">
                            <div class="cd-lightbox__backdrop" data-cp-close></div>
                            <div class="cd-lightbox__dialog" role="dialog" aria-modal="true" aria-label="Gallery">
                                <div class="cd-lightbox__topbar">
                                    <div class="cd-lightbox__counter" id="cpLightboxCounter">1 / 1</div>
                                    <button class="cd-lightbox__close" type="button" aria-label="Close" data-cp-close>×</button>
                                </div>
                                <div class="cd-lightbox__main">
                                    <div class="swiper cd-lightbox__swiper" id="cpLightboxMain">
                                        <div class="swiper-wrapper">
                                            @foreach ($partImages as $img)
                                                <div class="swiper-slide" data-cp-type="image">
                                                    <div class="swiper-zoom-container">
                                                        <img loading="lazy" src="{{ getImageOrPlaceholder($img, '1905x1080') }}" alt="img">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="swiper-button-prev"></div>
                                        <div class="swiper-button-next"></div>
                                    </div>
                                </div>
                                <div class="cd-lightbox__thumbs">
                                    <div class="swiper" id="cpLightboxThumbs">
                                        <div class="swiper-wrapper">
                                            @foreach ($partImages as $img)
                                                <div class="swiper-slide">
                                                    <img loading="lazy" src="{{ getImageOrPlaceholder($img, '216x148') }}" alt="thumb">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="listing-detail-hero__left-info">
                            <h2 class="listing-detail-hero__left-title">{{ $partTitle }}</h2>
                            <div class="listing-detail-hero__left-subtitle">{{ __('Go to motorcheck for history check') }}</div>

                            <div class="listing-detail-hero__meta">
                                @if(!empty($seller?->username))
                                    <a href="{{ route('dealer', $seller->username) }}" style="text-decoration:none;">
                                        <span class="listing-detail-hero__meta-type {{ $isDealerSeller ? 'listing-detail-hero__meta-type--dealer' : 'listing-detail-hero__meta-type--private' }}">
                                            {{ $sellerTypeLabel }}
                                        </span>
                                        <span class="listing-detail-hero__meta-sep">|</span>
                                        <span class="listing-detail-hero__meta-name">{{ $sellerDisplayName }}</span>
                                    </a>
                                @else
                                    <span class="listing-detail-hero__meta-type {{ $isDealerSeller ? 'listing-detail-hero__meta-type--dealer' : 'listing-detail-hero__meta-type--private' }}">
                                        {{ $sellerTypeLabel }}
                                    </span>
                                    <span class="listing-detail-hero__meta-sep">|</span>
                                    <span class="listing-detail-hero__meta-name">{{ $sellerDisplayName }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="listing-detail-hero__right">
                        <div class="listing-detail-hero__actions">
                            @if($partCity)
                                <span class="listing-detail-hero__actions-city">{{ strtoupper($partCity) }}</span>
                            @endif
                            @auth('web')
                                @if(!empty($seller?->email))
                                    <a class="action-email" href="mailto:{{ html_decode($seller->email) }}">{{ __('Email') }}</a>
                                @endif
                                @if(!empty($seller?->phone))
                                    <a class="action-call" href="tel:{{ html_decode($seller->phone) }}">{{ __('Call') }}</a>
                                @endif
                                @if(!empty($seller?->id))
                                    <a class="action-chat" href="{{ route('user.messages.start', $seller->id) }}">Chat</a>
                                @endif
                            @else
                                <a class="action-email" href="{{ route('login') }}">{{ __('Email') }}</a>
                                <a class="action-call" href="{{ route('login') }}">{{ __('Call') }}</a>
                                <a class="action-chat" href="{{ route('login') }}">Chat</a>
                            @endauth
                        </div>

                        <div class="listing-detail-hero__keyinfo">
                            @if(!empty($car_part?->brand?->name))
                                <div><span class="kicon"><i class="fa-solid fa-tag"></i></span>{{ html_decode($car_part->brand->name) }}</div>
                            @endif
                            @if(!empty($car_part->condition))
                                <div><span class="kicon"><i class="fa-regular fa-circle-check"></i></span>{{ html_decode($car_part->condition) }}</div>
                            @endif
                            @if(!empty($car_part->part_number))
                                <div><span class="kicon"><i class="fa-solid fa-hashtag"></i></span>{{ html_decode($car_part->part_number) }}</div>
                            @endif
                            @if(!empty($car_part->compatibility))
                                <div><span class="kicon"><i class="fa-solid fa-link"></i></span>{{ html_decode($car_part->compatibility) }}</div>
                            @endif
                        </div>

                        <div class="listing-detail-hero__price">
                            @if (!is_null($numericPrice))
                                €{{ number_format($numericPrice, 0, '.', ',') }}
                            @else
                                {{ currency($rawPrice) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="listing-detail-description">
            <div class="container">
                @if($partAddress)
                    <div>
                        <div class="listing-detail-description__title">{{ __('translate.Locations') }}</div>
                        <div class="listing-detail-description__body">{{ $partAddress }}</div>
                    </div>
                @endif

                <div style="margin-top: 24px;">
                    <div class="listing-detail-description__title">{{ __('translate.Description') }}</div>
                    <div class="listing-detail-description__body">
                        {!! clean($partDescription) !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@push('js_section')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        (function(){
            const modal = document.getElementById('cpLightbox');
            if (!modal || typeof Swiper === 'undefined') return;

            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }

            const counterEl = document.getElementById('cpLightboxCounter');
            const closeEls = modal.querySelectorAll('[data-cp-close]');
            const openEls = document.querySelectorAll('.js-cp-open-lightbox');
            const closeBtn = modal.querySelector('[data-cp-close].cd-lightbox__close');

            let thumbsSwiper = null;
            let mainSwiper = null;
            let lastFocusedEl = null;

            function setBodyLock(locked){
                document.body.style.overflow = locked ? 'hidden' : '';
            }

            function updateCounter(){
                if (!mainSwiper || !counterEl) return;
                counterEl.textContent = (mainSwiper.realIndex + 1) + ' / ' + mainSwiper.slides.length;
            }

            function openAt(index){
                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }

                lastFocusedEl = document.activeElement;
                if (lastFocusedEl && typeof lastFocusedEl.blur === 'function') {
                    lastFocusedEl.blur();
                }

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                setBodyLock(true);

                if (!thumbsSwiper) {
                    thumbsSwiper = new Swiper('#cpLightboxThumbs', {
                        slidesPerView: 5,
                        spaceBetween: 10,
                        watchSlidesProgress: true,
                        breakpoints: {
                            0: { slidesPerView: 4 },
                            768: { slidesPerView: 6 }
                        }
                    });
                }

                if (!mainSwiper) {
                    mainSwiper = new Swiper('#cpLightboxMain', {
                        initialSlide: index || 0,
                        loop: false,
                        zoom: { maxRatio: 3 },
                        keyboard: { enabled: true },
                        navigation: {
                            nextEl: '#cpLightboxMain .swiper-button-next',
                            prevEl: '#cpLightboxMain .swiper-button-prev'
                        },
                        thumbs: { swiper: thumbsSwiper },
                        on: {
                            slideChange: function(){
                                updateCounter();
                            },
                            afterInit: function(){
                                updateCounter();
                            }
                        }
                    });
                } else {
                    mainSwiper.slideTo(index || 0, 0);
                    updateCounter();
                }

                if (closeBtn && typeof closeBtn.focus === 'function') {
                    closeBtn.focus();
                }
            }

            function close(){
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                setBodyLock(false);
                if (mainSwiper && mainSwiper.zoom) mainSwiper.zoom.out();
                if (lastFocusedEl && document.contains(lastFocusedEl) && typeof lastFocusedEl.focus === 'function') {
                    lastFocusedEl.focus();
                }
            }

            openEls.forEach((el) => {
                const open = (e) => {
                    if (e && e.target && e.target.closest && e.target.closest('a')) return;
                    if (e && e.preventDefault) e.preventDefault();
                    if (e && e.stopPropagation) e.stopPropagation();
                    if (e && e.stopImmediatePropagation) e.stopImmediatePropagation();
                    const idx = parseInt(el.getAttribute('data-cp-index') || '0', 10);
                    openAt(Number.isFinite(idx) ? idx : 0);
                };
                el.addEventListener('click', open);
                el.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        open(e);
                    }
                });
            });

            closeEls.forEach((el) => el.addEventListener('click', close));

            document.addEventListener('keydown', (e) => {
                if (!modal.classList.contains('is-open')) return;
                if (e.key === 'Escape') close();
            });
        })();
    </script>
@endpush
