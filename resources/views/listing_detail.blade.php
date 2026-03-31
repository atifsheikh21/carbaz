@extends('layout')
@section('title')
    <title>{{ html_decode($car->seo_title) }}</title>
    <meta name="title" content="{{ html_decode($car->seo_title) }}">
    <meta name="description" content="{{ html_decode($car->seo_description) }}">
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
            grid-template-columns: 560px 1fr;
            gap: 120px;
            align-items: start;
        }
        .listing-detail-hero__media{
            max-width: 560px;
            display: flex;
            flex-direction: column;
            margin-top: 50px !important;
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
        .listing-detail-hero__meta{
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
            color: #111;
            margin-bottom: 26px;
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            text-align: right;
            margin-top: 100px
        }
        .listing-detail-hero__meta-type{
            font-weight: 800;
        }
        .listing-detail-hero__meta-type--dealer{
            color: #b60304;
        }
        .listing-detail-hero__meta-type--private{
            color: #23a549;
            font-size: 20px;
        }
        .listing-detail-hero__meta-sep{
            color: rgba(0,0,0,.65);
        }
        .listing-detail-hero__meta-name{
            color: #23a549;
        }
        .lp-detail__seller{
            display: inline-flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
            text-decoration: none;
            color: inherit;
        }
        .lp-detail__seller-type{
            color: #b60304;
            font-weight: 800;
            text-transform: uppercase;
        }
        .lp-detail__seller-sep{
            color: rgba(0,0,0,.65);
            font-weight: 700;
        }
        .lp-detail__seller-name{
            color: #23a549;
            font-weight: 800;
            text-transform: uppercase;
        }
        .listing-detail-hero__badge{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 10px;
            border-radius: 6px;
            background: #0CA640;
            color: #fff;
            font-size: 12px;
            font-weight: 800;
        }
        .listing-detail-hero__location{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 10px;
            border-radius: 6px;
            background: rgba(0,0,0,0.18);
            color: #fff;
            font-size: 12px;
            font-weight: 800;
        }
        .listing-detail-hero__right{
            min-height: 520px;
            display: flex;
            flex-direction: column;
        }
        .listing-detail-hero__keyinfo{
            margin-top: 100px;
            display: grid;
            gap: 15px 28px;
            color: #6f6f6f;
            font-size: 14px;
            text-transform: lowercase;
            justify-content: center;
        }
        .listing-detail-hero__keyinfo div{
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.35;
        }
        .listing-detail-hero__keyinfo div span{
            min-width: 100px;
            color: #8b8b8b;
            flex: 0 0 100px;
        }
        .listing-detail-hero__specs{
            margin-top: 22px;
            display: grid;
            gap: 6px;
            color: #444;
            font-weight: 700;
        }
        .listing-detail-hero__specs div{
            font-size: 14px;
        }
        .listing-detail-hero__title{
            margin-top: 32px;
            font-size: 28px;
            font-weight: 800;
            color: #2b2b2b;
        }
        .listing-detail-hero__subtitle{
            margin-top: 6px;
            font-size: 12px;
            color: #6b6b6b;
        }
        .listing-detail-hero__actions{
            /*margin-top: 100px;*/
            display: flex;
            border: 2px solid #2b2b2b;
            border-radius: 999px;
            overflow: hidden;
            background: #e3e5ea;
            width: 90%;
            
            gap: 0;
        }

        @media (min-width: 992px){
            .listing-detail-hero__actions > *{
                position: relative;
            }

            .listing-detail-hero__actions > * + *{
                margin-left: -10px;
            }

            .listing-detail-hero__actions > :nth-child(1){ z-index: 6; }
            .listing-detail-hero__actions > :nth-child(2){ z-index: 5; }
            .listing-detail-hero__actions > :nth-child(3){ z-index: 4; }
            .listing-detail-hero__actions > :nth-child(4){ z-index: 3; }
            .listing-detail-hero__actions > :nth-child(5){ z-index: 2; }
        }

        .listing-detail-hero__actions-city{
            padding: 10px 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
        }
        .listing-detail-hero__actions a{
            padding: 10px 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #2b2b2b;
            text-decoration: none;
            line-height: 1;
            flex: 1 1 0;
            min-width: 0;
            box-sizing: border-box;
            border-radius: 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .listing-detail-hero__actions a.action-email{ background: #b9bcc3; }
        .listing-detail-hero__actions a.action-call{ background: #d2cdcd; }
        .listing-detail-hero__actions a.action-chat{ background: #e9e6e3; }

        .listing-detail-hero__actions a + a{ border-left: 0; }
        .listing-detail-hero__price{
            margin-top: 175px;
            font-size: 55px;
            font-weight: 700;
            color: #2b2b2b;
            letter-spacing: -0.02em;
            text-align: center;
        }
        .listing-detail-hero__left-info{
            margin-top: auto;
        }
        .listing-detail-hero__left-title{
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #2b2b2b;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 55px;
        }
        .listing-detail-hero__left-subtitle{
            margin-top: -10px;
            font-size: 16px;
            color: #6b6b6b;
        }
        .listing-detail-description{
            background: #e9e6e3;
            padding: 30px 0 10px;
        }
        .listing-detail-description .container{
            max-width: 1200px;
        }
        .inventory-details.py-120px .container{
            max-width: 1100px;
        }
        .cars-listing.feature-two .container{
            max-width: 1100px;
        }
        .cars-listing.feature-two .row.mt-56px{
            row-gap: 26px;
        }
        .listing-detail-description__title{
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #2b2b2b;
            margin-bottom: 10px;
        }
        .listing-detail-description__body{
            color: #4b4b4b;
            line-height: 1.8;
            font-size: 18px;
        }
        .listing-detail-description__body p{
font-size: 18px !important;
font-weight: 300;
        }
        .listing-detail-description .feature-list ul{
            display: flex;
            flex-wrap: wrap;
            gap: 12px 26px;
            padding-left: 0;
            margin: 0;
        }
        .listing-detail-description .feature-list ul li{
            width: auto;
            margin: 0;
        }
        @media (max-width: 575.98px){
            .listing-detail-hero{
                padding: 34px 0;
                display: block !important;
                visibility: visible !important;
                height: auto !important;
                min-height: 0 !important;
            }
            .listing-detail-description,
            .inventory-details.py-120px{
                display: block !important;
                visibility: visible !important;
                height: auto !important;
                min-height: 0 !important;
            }
            .listing-detail-hero__grid{
                display:flex;
                flex-direction:column;
                gap: 20px;
                height: auto !important;
                min-height: 0 !important;
            }
            .listing-detail-hero__media{
                max-width: 100%;
                display: block;
            }
            .listing-detail-hero .inventory-details-slick-for,
            .listing-detail-hero .inventory-details-slick-for .inventory-details-slick-img,
            .listing-detail-hero .inventory-details-slick-for .inventory-details-slick-img img{
                height: auto !important;
                max-height: none !important;
            }

            .listing-detail-hero__mobile-image{
                display: none;
            }

            .listing-detail-hero .inventory-details-slick-for{
                display: block !important;
            }

            .listing-detail-hero .inventory-details-slick-nav{
                display: none !important;
            }

            .listing-detail-hero .inventory-details-slick-for,
            .listing-detail-hero .inventory-details-slick-nav,
            .listing-detail-hero .inventory-details-slick-for .slick-list,
            .listing-detail-hero .inventory-details-slick-for .slick-track,
            .listing-detail-hero .inventory-details-slick-nav .slick-list,
            .listing-detail-hero .inventory-details-slick-nav .slick-track,
            .listing-detail-hero__left-info,
            .listing-detail-hero__right{
                opacity: 1 !important;
                visibility: visible !important;
            }

            [data-aos],
            .aos-init,
            .aos-animate{
                opacity: 1 !important;
                transform: none !important;
                visibility: visible !important;
            }
            .listing-detail-hero__left-info{
                margin-top: 18px;
            }
            .listing-detail-hero__right{
                min-height: auto;
            }
            .listing-detail-hero__left-title{
                font-size: 24px;
                gap: 8px;
            }
            .listing-detail-hero__location{
                padding: 4px 8px;
                font-size: 11px;
            }
            .listing-detail-hero__badge{
                font-size: 11px;
                padding: 4px 8px;
            }
            .listing-detail-hero__actions{
                width: 130%;
                /*display: grid;*/
                grid-template-columns: 1fr 1fr;
                margin-top: 18px;
                border-radius: 14px;
            }
            .listing-detail-hero__actions-city{
                grid-column: 1 / -1;
                padding: 10px 10px;
                border-right: 0;
                border-bottom: 1px solid #2b2b2b;
            }
            .listing-detail-hero__actions a{
                width: 100%;
                padding: 10px 10px;
                letter-spacing: 0.08em;
                font-size: 12px;
                border-left: 0 !important;
                border-top: 1px solid #2b2b2b;
            }
            .listing-detail-hero__actions a:nth-of-type(2){
                border-left: 1px solid #2b2b2b !important;
            }
            .listing-detail-hero__actions a:last-of-type{
                grid-column: 1 / -1;
            }
            .listing-detail-hero__keyinfo{
                grid-template-columns: 1fr;
                gap: 10px;
                margin-top: 14px !important;
            }
            .listing-detail-hero__keyinfo div{
                font-size: 12px;
            }
            .listing-detail-hero__meta{
                justify-content: flex-start;
                text-align: left;
                margin-bottom: 16px;
                margin-top: 30px !important;
            }
            .location-mobile{
                margin-top: 75px !important;
            }
            .listing-detail-hero__price{
                font-size: 30px;
                text-align: left;
                margin-top: 16px !important;
            }

            .listing-detail-description{
                padding: 60px 0 10px;
            }
            .listing-detail-description__body{ font-size: 16px; }
            .listing-detail-description__body p{ font-size: 16px !important; }
            .listing-detail-description .feature-list ul{
                gap: 10px 16px;
            }
            .listing-detail-description .feature-list ul li{
                font-size: 12px;
            }

            .cars-listing.feature-two .row.mt-56px{
                row-gap: 18px;
            }
        }
        @media (max-width: 991.98px){
            .listing-detail-hero{
                padding: 44px 0;
            }
            .listing-detail-hero .container,
            .listing-detail-description .container{
                max-width: 100%;
                padding-left: 16px;
                padding-right: 16px;
            }
            .listing-detail-hero__grid{
                grid-template-columns: 1fr;
                gap: 26px;
            }
            .listing-detail-hero__media{
                max-width: 100%;
                margin-top: 0 !important;
            }
            .listing-detail-hero__right{
                min-height: auto;
            }
            .listing-detail-hero__keyinfo{
                margin-top: 22px;
            }
            .listing-detail-hero__actions{
                margin-top: 22px;
                width: 130%;
                justify-content: flex-start;
                gap: 3px;
            }

            .listing-detail-hero__actions > * + *{ margin-left: 0; }
            .listing-detail-hero__price{
                text-align: left;
                margin-top: 18px;
            }
        }

        @media (max-width: 1199.98px){
            .listing-detail-hero__grid{
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }
            .listing-detail-hero__media{
                max-width: 100%;
            }
            .listing-detail-hero__price{
                margin-top: 60px;
            }
            .listing-detail-hero__actions{
                margin-top: 40px;
            }
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
        .cd-lightbox.is-open{
            display: block;
        }
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
        .cd-lightbox__swiper .swiper-slide{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cd-lightbox__swiper .swiper-zoom-container{
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cd-lightbox__swiper img{
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
        .cd-lightbox__swiper iframe,
        .cd-lightbox__swiper video{
            width: 100%;
            height: min(70vh, 640px);
            border: 0;
            border-radius: 14px;
            background: #000;
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
        .cd-lightbox__thumbs .swiper-slide-thumb-active{
            opacity: 1;
        }
        .cd-lightbox__thumbs img{
            width: 100%;
            height: 72px;
            object-fit: cover;
            display: block;
            border-radius: 10px;
            border: 2px solid rgba(255,255,255,.18);
            background: #111;
        }
        .cd-lightbox__thumb--video{
            position: relative;
        }
        .cd-lightbox__thumb--video::after{
            content: '▶';
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
            text-shadow: 0 2px 12px rgba(0,0,0,.6);
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
            .cd-lightbox__main{
                padding: 0 10px;
            }
            .cd-lightbox__thumbs img{
                height: 56px;
            }
            .cd-lightbox__swiper iframe,
            .cd-lightbox__swiper video{
                height: min(60vh, 420px);
            }
        }
    </style>
@endpush

@section('body-content')

<main>
    <div class="lp-mobile d-block d-md-none lp-detail">
        @php
            $__sellerType = strtolower((string) ($car->seller_type ?? ''));
            if ($__sellerType !== '') {
                $__isDealerAd = str_contains($__sellerType, 'dealer');
            } else {
                $__isDealerAd = ((int) ($dealer?->is_dealer ?? 0)) === 1;
            }
            $__sellerDisplayName = $__isDealerAd && !empty($dealer?->is_vehicle_seller) && !empty($dealer?->vehicle_company_name)
                ? html_decode($dealer->vehicle_company_name)
                : html_decode($dealer?->name);
            $__sellerName = strtoupper(trim((string) $__sellerDisplayName));
            $__cityOnly = null;
            if (!empty($car?->city_id)) {
                $__cityOnly = optional(Modules\City\Entities\City::find($car->city_id))->name;
            }
            if (!$__cityOnly && !empty($car->address)) {
                $addressParts = array_values(array_filter(array_map('trim', explode(',', html_decode($car->address)))));
                if (count($addressParts) >= 2) {
                    $__cityOnly = $addressParts[1];
                } elseif (count($addressParts) === 1) {
                    $__cityOnly = $addressParts[0];
                }
            }
            $__sellerPhoneRaw = (string) ($dealer?->phone ?? '');
            $__sellerPhone = preg_replace('/\D+/', '', $__sellerPhoneRaw);
            $__whatsAppPhone = $__sellerPhone;
            $__priceRaw = $car->offer_price ?: $car->regular_price;
            $__priceNum = is_numeric($__priceRaw) ? (float) $__priceRaw : null;
            $__picsCount = is_countable($galleries) ? count($galleries) : 0;
            $__thumbs = [];
            foreach ($galleries as $__g) { $__thumbs[] = $__g; }
            $__mainImg = $__thumbs[0] ?? null;
        @endphp

        <div class="lp-mobile__filter">
            <button class="lp-mobile__filter-label" type="button" data-bs-toggle="offcanvas" data-bs-target="#lpMobileFilterDetail" aria-controls="lpMobileFilterDetail">Filter</button>
            <form class="lp-mobile__filter-form" method="GET" action="{{ route('listings') }}">
                <input class="lp-mobile__filter-input" type="text" name="search" value="{{ request()->get('search') }}" placeholder="search car & part by key word">
            </form>
        </div>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="lpMobileFilterDetail" aria-labelledby="lpMobileFilterDetailLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="lpMobileFilterDetailLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="d-grid gap-2">
                    <a class="btn btn-dark" href="{{ route('listings', request()->query()) }}">Open filters</a>
                    <a class="btn btn-light" href="{{ route('listings') }}">Browse all ads</a>
                </div>
            </div>
        </div>

        <div class="lp-detail__nav">
            <a class="lp-detail__back" href="{{ route('listings') }}">back</a>
            <div class="lp-detail__spacer"></div>
            <a class="lp-detail__search" href="{{ route('home') }}">HOME</a>
        </div>

        <div class="lp-mobile-card lp-detail-card">
            <div class="lp-mobile-card__top">
                <div class="lp-mobile-card__top-left">
                    @if(!empty($dealer?->username))
                        <a href="{{ route('dealer', $dealer->username) }}" class="lp-detail__seller">
                            <span class="lp-detail__seller-type">{{ $__isDealerAd ? 'DEALER' : 'PRIVATE' }}</span>
                            <span class="lp-detail__seller-sep">|</span>
                            <span class="lp-detail__seller-name">{{ $__sellerName !== '' ? $__sellerName : ' ' }}</span>
                        </a>
                    @else
                        <span class="lp-detail__seller">
                            <span class="lp-detail__seller-type">{{ $__isDealerAd ? 'DEALER' : 'PRIVATE' }}</span>
                            <span class="lp-detail__seller-sep">|</span>
                            <span class="lp-detail__seller-name">{{ $__sellerName !== '' ? $__sellerName : ' ' }}</span>
                        </span>
                    @endif
                </div>
                <div class="lp-mobile-card__top-right">{{ $__cityOnly ? strtoupper($__cityOnly) : ' ' }}</div>
            </div>

            <div class="lp-detail__media js-cd-open-lightbox" data-cd-index="0" role="button" tabindex="0">
                @if($__mainImg)
                    <img class="lp-detail__main-img" src="{{ getImageOrPlaceholder($__mainImg->image, '920x636') }}" alt="img">
                @else
                    <img class="lp-detail__main-img" src="{{ getImageOrPlaceholder($car->thumb_image, '920x636') }}" alt="img">
                @endif

                <div class="lp-detail__heart">
                    @guest('web')
                        <a href="javascript:;" class="before_auth_wishlist" aria-label="wishlist"></a>
                    @else
                        @php
                            $isInWishlist = false;
                            if (\Illuminate\Support\Facades\Schema::hasTable('wishlists')) {
                                $isInWishlist = App\Models\Wishlist::where('car_id',$car->id)
                                    ->where('user_id',Auth::user()->id)
                                    ->first();
                            }
                        @endphp
                        <a href="{{ route('user.add-to-wishlist', $car->id) }}" class="{{ $isInWishlist ? 'active' : '' }}" aria-label="wishlist"></a>
                    @endguest
                </div>

                @if($__picsCount > 0)
                    <div class="lp-detail__pics">+{{ $__picsCount }} PIC</div>
                @endif
            </div>

            <div class="lp-detail__thumbs">
                @php
                    $__thumbShow = array_slice($__thumbs, 0, 3);
                    $__extra = max($__picsCount - count($__thumbShow), 0);
                @endphp
                @foreach($__thumbShow as $__t)
                    <div class="lp-detail__thumb js-cd-open-lightbox" data-cd-index="{{ $loop->index }}" role="button" tabindex="0">
                        <img src="{{ getImageOrPlaceholder($__t->image, '216x148') }}" alt="thumb">
                    </div>
                @endforeach
                @if($__extra > 0)
                    <div class="lp-detail__thumb lp-detail__thumb--more js-cd-open-lightbox" data-cd-index="{{ count($__thumbShow) }}" role="button" tabindex="0">
                        <span>+{{ $__extra }} PIC</span>
                    </div>
                @endif
            </div>

            <div class="lp-detail__body">
                <div class="lp-detail__title">{{ strtoupper(trim((string) html_decode($car->title))) }}</div>
                <div class="lp-detail__subtitle">{{ __('Go to motorcheck for history check') }}</div>

                <div class="lp-detail__specs">
                    @if(!empty($car->year))<div><span>year</span>{{ html_decode($car->year) }}</div>@endif
                    @if(!empty($car->engine_size))<div><span>engine</span>{{ html_decode($car->engine_size) }}</div>@endif
                    @if(!empty($car->fuel_type))<div><span>fuel type</span>{{ html_decode($car->fuel_type) }}</div>@endif
                    @if(!empty($car->transmission))<div><span>transmission</span>{{ html_decode($car->transmission) }}</div>@endif
                    @if(!empty($car->mileage))<div><span>mileage</span>{{ html_decode($car->mileage) }}</div>@endif
                    @if(!empty($car->body_type))<div><span>body type</span>{{ html_decode($car->body_type) }}</div>@endif
                    @if(!empty($car->drive))<div><span>drive</span>{{ html_decode($car->drive) }}</div>@endif
                    @if(!empty($car->interior_color))<div><span>color</span>{{ html_decode($car->interior_color) }}</div>@endif
                </div>

                <div class="lp-detail__price-row">
                    <div class="lp-detail__label">
                        @if(!empty($dealer?->username))
                            <a href="{{ route('dealer', $dealer->username) }}" class="lp-detail__seller">
                                <span class="lp-detail__seller-type">{{ $__isDealerAd ? 'DEALER' : 'PRIVATE' }}</span>
                                <span class="lp-detail__seller-sep">|</span>
                                <span class="lp-detail__seller-name">{{ $__sellerName !== '' ? $__sellerName : ' ' }}</span>
                            </a>
                        @else
                            <span class="lp-detail__seller">
                                <span class="lp-detail__seller-type">{{ $__isDealerAd ? 'DEALER' : 'PRIVATE' }}</span>
                                <span class="lp-detail__seller-sep">|</span>
                                <span class="lp-detail__seller-name">{{ $__sellerName !== '' ? $__sellerName : ' ' }}</span>
                            </span>
                        @endif
                    </div>
                    <div class="lp-detail__price">
                        @if(!is_null($__priceNum))
                            €{{ number_format($__priceNum, 0, '.', ',') }}
                        @endif
                        <div class="lp-detail__warranty">3 Month Warranty</div>
                    </div>
                </div>

                <div class="lp-detail__section">
                    <div class="lp-detail__section-title">Description</div>
                    <div class="lp-detail__section-body">
                        {!! clean(html_decode($car->description)) !!}
                    </div>
                </div>

                @if(!empty($car->address))
                    <div class="lp-detail__section">
                        <div class="lp-detail__section-title">Dealer address</div>
                        <div class="lp-detail__section-body">{{ html_decode($car->address) }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="lp-detail__contactbar">
            <button class="lp-detail__contactbtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#lpContactSeller" aria-controls="lpContactSeller">CONTACT SELLER</button>
        </div>

        <div class="offcanvas offcanvas-bottom" tabindex="-1" id="lpContactSeller" aria-labelledby="lpContactSellerLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="lpContactSellerLabel">Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="lp-detail__actions">
                    @php
                        $__waText = rawurlencode('Hi, I am interested in your car ad: ' . (string) html_decode($car->title));
                    @endphp
                    @auth('web')
                        <a class="lp-detail__action" href="{{ $__whatsAppPhone ? 'https://wa.me/'.$__whatsAppPhone.'?text='.$__waText : '#' }}" target="_blank">whatsapp chat</a>
                        <a class="lp-detail__action" href="{{ $__sellerPhoneRaw ? 'tel:'.html_decode($__sellerPhoneRaw) : '#' }}">call</a>
                        <a class="lp-detail__action" href="{{ !empty($dealer?->email) ? 'mailto:'.html_decode($dealer?->email) : '#' }}">email</a>
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
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Car Details') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Car Details') }}</li>
                    </ol>
                </nav>
            </div>
            </div>
        </div>
    </section>
    <!-- banner-part-end -->



    <!-- Inventory Details-part-start -->


    <section class="inventory-details listing-detail-hero">
        <div class="container">
            <div class="listing-detail-hero__grid">
                <div class="listing-detail-hero__media">
                    @php
                        $__firstGallery = null;
                        foreach ($galleries as $__g) { $__firstGallery = $__g; break; }
                    @endphp
                    @if($__firstGallery)
                        <div class="listing-detail-hero__mobile-image">
                            <img src="{{ getImageOrPlaceholder($__firstGallery->image, '920x636') }}" alt="img">
                        </div>
                    @endif
                    <div class="inventory-details-slick-for">



                            @foreach ($galleries as $gallery)
                                <div class="inventory-details-slick-img js-cd-open-lightbox" data-cd-index="{{ $loop->index }}" role="button" tabindex="0">

                                        <div class="inventory-details-slick-img-tag">
                                        <div class="icon-main">

                                             @guest('web')
                                                    <a href="javascript:;" class="icon before_auth_wishlist">
                                                        <span>
                                                            <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z"
                                                                    stroke-width="1.3" stroke-linejoin="round" />
                                                            </svg>

                                                        </span>
                                                    </a>
                                                    @else
                                                    @php
                                                        $isInWishlist = false;
                                                        if (\Illuminate\Support\Facades\Schema::hasTable('wishlists')) {
                                                            $isInWishlist = App\Models\Wishlist::where('car_id',$car->id)
                                                                ->where('user_id',Auth::user()->id)
                                                                ->first();
                                                        }
                                                    @endphp
                                                    <a href="{{ route('user.add-to-wishlist', $car->id) }}" 
                                                    class="icon {{ $isInWishlist ? 'active' : '' }}">
                                                        <span>
                                                            <svg width="18" height="16" viewBox="0 0 18 16" fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z"
                                                                    stroke-width="1.3" stroke-linejoin="round"/>
                                                            </svg>
                                            </span>
                                            </a>

                                                    @endguest
                                            </div>
                                        </div>


                                    <img src="{{ getImageOrPlaceholder($gallery->image, '920x636') }}" alt="img">
                                </div>
                            @endforeach

                            @if($car->video_id)
                                <div class="inventory-details-slick-img js-cd-open-lightbox" data-cd-index="{{ is_countable($galleries) ? count($galleries) : 0 }}" role="button" tabindex="0">
                                    <span class="inventory-details-vedio">
                                        <img src="{{ getImageOrPlaceholder($car->video_image, '920x636') }}" alt="img">
                                        <span class="overlay">
                                            <a class="my-video-links" data-autoplay="true" data-vbtype="video"
                                                href="https://youtu.be/{{ $car->video_id }}">
                                                <span>
                                                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M40 76.0001C59.8822 76.0001 76.0001 59.8827 76.0001 40C76.0001 20.1178 59.8827 3.99992 40 3.99992C20.1178 3.99992 3.99992 20.1178 3.99992 40C3.99992 59.8822 20.1178 76.0001 40 76.0001ZM40 80C62.0911 80 80 62.0911 80 40C80 17.9084 62.0911 0 40 0C17.9084 0 0 17.9084 0 40C0 62.0911 17.9084 80 40 80Z" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M50.3927 40.0003L31.9984 27.7375V52.2634L50.3927 40.0003ZM54.1089 37.6706C55.7716 38.7791 55.7716 41.2219 54.1089 42.3303L32.3513 56.8357C30.4906 58.0763 27.998 56.742 27.998 54.5057V25.4953C27.998 23.259 30.4906 21.9251 32.3513 23.1657L54.1089 37.6706Z" />
                                                    </svg>
                                                </span>
                                            </a>
                                        </span>
                                    </span>
                                </div>
                            @endif
                    </div>

                    <div class="inventory-details-slick-nav">
                        @foreach ($galleries as $gallery)
                            <div class="inventory-details-slick-img js-cd-open-lightbox" data-cd-index="{{ $loop->index }}" role="button" tabindex="0">
                                <img src="{{ getImageOrPlaceholder($gallery->image, '216x148') }}" alt="img">
                            </div>
                        @endforeach

                        @if($car->video_id)
                            <div class="inventory-details-slick-img js-cd-open-lightbox" data-cd-index="{{ is_countable($galleries) ? count($galleries) : 0 }}" role="button" tabindex="0">
                                <img src="{{ getImageOrPlaceholder($car->video_image, '216x148') }}" alt="img">
                            </div>
                        @endif
                    </div>

                    <div class="cd-lightbox" id="cdLightbox" aria-hidden="true">
                        <div class="cd-lightbox__backdrop" data-cd-close></div>
                        <div class="cd-lightbox__dialog" role="dialog" aria-modal="true" aria-label="Gallery">
                            <div class="cd-lightbox__topbar">
                                <div class="cd-lightbox__counter" id="cdLightboxCounter">1 / 1</div>
                                <button class="cd-lightbox__close" type="button" aria-label="Close" data-cd-close>×</button>
                            </div>
                            <div class="cd-lightbox__main">
                                <div class="swiper cd-lightbox__swiper" id="cdLightboxMain">
                                    <div class="swiper-wrapper">
                                        @foreach ($galleries as $gallery)
                                            <div class="swiper-slide" data-cd-type="image">
                                                <div class="swiper-zoom-container">
                                                    <img loading="lazy" src="{{ getImageOrPlaceholder($gallery->image, '1905x1080') }}" alt="img">
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($car->video_id)
                                            <div class="swiper-slide" data-cd-type="youtube" data-cd-youtube="{{ $car->video_id }}">
                                                <iframe loading="lazy" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen title="video"></iframe>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>
                                </div>
                            </div>
                            <div class="cd-lightbox__thumbs">
                                <div class="swiper" id="cdLightboxThumbs">
                                    <div class="swiper-wrapper">
                                        @foreach ($galleries as $gallery)
                                            <div class="swiper-slide">
                                                <img loading="lazy" src="{{ getImageOrPlaceholder($gallery->image, '216x148') }}" alt="thumb">
                                            </div>
                                        @endforeach
                                        @if($car->video_id)
                                            <div class="swiper-slide cd-lightbox__thumb--video">
                                                <img loading="lazy" src="{{ getImageOrPlaceholder($car->video_image, '216x148') }}" alt="video">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="listing-detail-hero__left-info">
                        <h2 class="listing-detail-hero__left-title">
                            {{ html_decode($car->title) }}
                            @php
                                $cityOnly = null;
                                if (!empty($car?->city_id)) {
                                    $cityOnly = optional(Modules\City\Entities\City::find($car->city_id))->name;
                                }
                                if (!$cityOnly && !empty($car->address)) {
                                    $addressParts = array_values(array_filter(array_map('trim', explode(',', html_decode($car->address)))));
                                    if (count($addressParts) >= 2) {
                                        $cityOnly = $addressParts[1];
                                    } elseif (count($addressParts) === 1) {
                                        $cityOnly = $addressParts[0];
                                    }
                                }
                            @endphp
                        </h2>
                        <div class="listing-detail-hero__left-subtitle">{{ __('Go to motorcheck for history check') }}</div>

                        <div class="listing-detail-hero__meta">
                            @php
                                $__sellerType = strtolower((string) ($car->seller_type ?? ''));
                                if ($__sellerType !== '') {
                                    $__isDealerAd = str_contains($__sellerType, 'dealer');
                                } else {
                                    $__isDealerAd = ((int) ($dealer?->is_dealer ?? 0)) === 1;
                                }
                                $__sellerDisplayName = $__isDealerAd && !empty($dealer?->is_vehicle_seller) && !empty($dealer?->vehicle_company_name)
                                    ? html_decode($dealer->vehicle_company_name)
                                    : html_decode($dealer?->name);
                            @endphp
                            @if(!empty($dealer?->username))
                                <a href="{{ route('dealer', $dealer->username) }}" style="text-decoration:none;">
                                    <span class="listing-detail-hero__meta-type {{ $__isDealerAd ? 'listing-detail-hero__meta-type--dealer' : 'listing-detail-hero__meta-type--private' }}">
                                        {{ $__isDealerAd ? 'DEALER' : 'PRIVATE' }}
                                    </span>
                                    <span class="listing-detail-hero__meta-sep">|</span>
                                    <span class="listing-detail-hero__meta-name">{{ $__sellerDisplayName }}</span>
                                </a>
                            @else
                                <span class="listing-detail-hero__meta-type {{ $__isDealerAd ? 'listing-detail-hero__meta-type--dealer' : 'listing-detail-hero__meta-type--private' }}">
                                    {{ $__isDealerAd ? 'DEALER' : 'PRIVATE' }}
                                </span>
                                <span class="listing-detail-hero__meta-sep">|</span>
                                <span class="listing-detail-hero__meta-name">{{ $__sellerDisplayName }}</span>
                            @endif
                        </div>

                        <div class="location-mobile" style="margin-top: 160px;">
                            <div class="listing-detail-description__title">{{ __('translate.Locations') }}</div>
                            <div class="listing-detail-description__body">
                                {{ html_decode($car->address) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="listing-detail-hero__right">
                    <div class="listing-detail-hero__actions">
                         @if($cityOnly)
                            <span class="listing-detail-hero__actions-city">{{ strtoupper($cityOnly) }}</span>
                        @endif
                        @auth('web')
                            <a class="action-email" href="mailto:{{ html_decode($dealer?->email) }}">{{ __('Email') }}</a>
                            <a class="action-call" href="tel:{{ html_decode($dealer?->phone) }}">{{ __('Call') }}</a>
                            @if(!empty($dealer?->id))
                                <a class="action-chat" href="{{ route('user.messages.start', $dealer->id) }}">Chat</a>
                            @endif
                        @else
                            <a class="action-email" href="{{ route('login') }}">{{ __('Email') }}</a>
                            <a class="action-call" href="{{ route('login') }}">{{ __('Call') }}</a>
                            <a class="action-chat" href="{{ route('login') }}">Chat</a>
                        @endauth
                    </div>

                    <div class="listing-detail-hero__keyinfo">
                        @if($car->year)
                            <div><span>year</span>{{ html_decode($car->year) }}</div>
                        @endif
                        @if($car->engine_size)
                            <div><span>engine</span>{{ html_decode($car->engine_size) }}</div>
                        @endif
                        @if($car->transmission)
                            <div><span>transmission</span>{{ html_decode($car->transmission) }}</div>
                        @endif
                        @if($car->mileage)
                            <div><span>mileage</span>{{ html_decode($car->mileage) }}</div>
                        @endif
                        @if($car->body_type)
                            <div><span>body type</span>{{ html_decode($car->body_type) }}</div>
                        @endif
                        @if($car->drive)
                            <div><span>drive</span>{{ html_decode($car->drive) }}</div>
                        @endif
                        @if($car->interior_color)
                            <div><span>color</span>{{ html_decode($car->interior_color) }}</div>
                        @endif
                        @if($car->exterior_color)
                            <div><span>exterior color</span>{{ html_decode($car->exterior_color) }}</div>
                        @endif
                        @if($car->fuel_type)
                            <div><span>fuel type</span>{{ html_decode($car->fuel_type) }}</div>
                        @endif
                        @if($car->condition)
                            <div>
                                <span>condition</span>
                                @if($car->condition == 'Used')
                                    {{ __('translate.Used') }}
                                @else
                                    {{ __('translate.New') }}
                                @endif
                            </div>
                        @endif
                        @if($car->seller_type)
                            <div><span>seller type</span>{{ html_decode($car->seller_type) }}</div>
                        @endif
                    </div>

                    <div class="listing-detail-hero__price">
                        @if ($car->offer_price)
                            @php
                                $__priceText = currency($car->offer_price);
                                $__priceText = preg_replace('/^[^0-9]+/u', '', (string) $__priceText);
                            @endphp
                            €{{ $__priceText }}
                        @else
                            @php
                                $__priceText = currency($car->regular_price);
                                $__priceText = preg_replace('/^[^0-9]+/u', '', (string) $__priceText);
                            @endphp
                            €{{ $__priceText }}
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </section>

    <section class="listing-detail-description">
        <div class="container">
            <div class="listing-detail-description__title">{{ __('translate.Description') }}</div>
            <div class="listing-detail-description__body">
                {!! clean(html_decode($car->description)) !!}
            </div>

            <div style="margin-top: 24px;">
                <div class="listing-detail-description__title">{{ __('translate.Features') }}</div>
                <div class="listing-detail-description__body">
                    <div class="feature-list">
                        <ul>
                            @foreach ($car_features as $index => $car_feature)
                                <li>
                                    <span>
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                    {{ $car_feature->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="inventory-details py-120px">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    @if ($listing_ads->status == 'enable')
                        <div class="inventory-details-thumb">
                            <a href="{{ $listing_ads->link }}" target="_blank"> <img src="{{ getImageOrPlaceholder($listing_ads->image,'950x130') }}" alt="img"></a>
                        </div>
                    @endif


                    <!-- Description Overview  -->
                    @if(false)
                    <div class="accordion" id="accordionPanelsStayOpenExample1" data-aos="fade-up"
                    data-aos-delay="150">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingtwo">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-collapsetwo" aria-expanded="true"
                                    aria-controls="panelsStayOpen-collapsetwo">
                                    {{ __('translate.Key Information') }}
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapsetwo" class="accordion-collapse collapse show"
                                aria-labelledby="panelsStayOpen-headingtwo">
                                <div class="accordion-body">
                                    <div class="row gx-5">
                                        <div class="col-lg-6 ">
                                            <ul class="key-information" >
                                                <li>
                                                    <span>
                                                        <svg width="19" height="16" viewBox="0 0 19 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M2.58054 6.91356H16.4207C16.5881 6.91356 16.7239 6.77542 16.7239 6.60503C16.7239 6.43465 16.5881 6.29651 16.4207 6.29651H2.58054C2.41309 6.29651 2.27734 6.43465 2.27734 6.60503C2.27734 6.77542 2.41309 6.91356 2.58054 6.91356Z" />
                                                            <path
                                                                d="M18.736 7.73774L17.9522 6.48184L17.6586 5.28173H18.2926C18.46 5.28173 18.5957 5.14359 18.5957 4.9732C18.5957 4.80282 18.46 4.66468 18.2926 4.66468H17.5076L16.7166 1.43155C16.5203 0.628813 15.7296 0 14.9167 0H4.0833C3.27039 0 2.47974 0.628813 2.28336 1.43155L1.49239 4.66468H0.707447C0.540004 4.66468 0.404255 4.80282 0.404255 4.9732C0.404255 5.14359 0.540004 5.28173 0.707447 5.28173H1.3414L1.04779 6.48184L0.263938 7.73778C0.113474 7.97893 0 8.37618 0 8.66179V13.1308C0 13.3012 0.135749 13.4393 0.303191 13.4393H0.404255V15.6915C0.404255 15.8619 0.540004 16.0001 0.707447 16.0001H3.89747C4.06491 16.0001 4.20066 15.8619 4.20066 15.6915V14.3649C4.20066 14.1945 4.06491 14.0564 3.89747 14.0564C3.73002 14.0564 3.59427 14.1945 3.59427 14.3649V15.383H1.01064V13.4393H5.33403C5.33488 13.4393 13.6704 13.4393 13.6704 13.4393C13.6731 13.4393 17.9894 13.4393 17.9894 13.4393V15.383H15.4057V14.3649C15.4057 14.1945 15.27 14.0564 15.1025 14.0564C14.9351 14.0564 14.7993 14.1945 14.7993 14.3649V15.6915C14.7993 15.8619 14.9351 16.0001 15.1025 16.0001H18.2926C18.46 16.0001 18.5957 15.8619 18.5957 15.6915V13.4393H18.6968C18.8643 13.4393 19 13.3012 19 13.1308V8.66179C19 8.37618 18.8865 7.97893 18.736 7.73774ZM0.606383 8.66179C0.606383 8.49346 0.687153 8.21069 0.775847 8.0686L1.58606 6.77042C1.60352 6.7424 1.61646 6.71172 1.62434 6.67955L2.87179 1.58059C2.99957 1.05828 3.55437 0.617048 4.0833 0.617048H14.9167C15.4456 0.617048 16.0005 1.05832 16.1282 1.58063L17.3756 6.67951C17.3835 6.71167 17.3964 6.74236 17.4139 6.77038L18.2241 8.06856C18.3128 8.21069 18.3936 8.49346 18.3936 8.66179V12.8223H13.8767L13.1444 10.9576C13.0984 10.8404 12.9869 10.7636 12.8628 10.7636H6.13716C6.01314 10.7636 5.90164 10.8404 5.85564 10.9576L5.12333 12.8223H0.606383V8.66179ZM13.2237 12.8223H5.77632L6.34252 11.3806H12.6575L13.2237 12.8223Z" />
                                                            <path
                                                                d="M1.51608 11.2778H2.65689C3.56068 11.2778 4.29602 10.5296 4.29602 9.60986C4.29602 8.69017 3.56072 7.94189 2.65689 7.94189H1.74C1.57256 7.94189 1.43681 8.08003 1.43681 8.25042C1.43681 8.42081 1.57256 8.55894 1.74 8.55894H2.65689C3.22632 8.55894 3.68964 9.03037 3.68964 9.60986C3.68964 10.1893 3.22636 10.6608 2.65689 10.6608H1.51608C1.34864 10.6608 1.21289 10.7989 1.21289 10.9693C1.21289 11.1397 1.34864 11.2778 1.51608 11.2778Z" />
                                                            <path
                                                                d="M14.7051 9.60986C14.7051 10.5296 15.4404 11.2778 16.3442 11.2778H17.485C17.6525 11.2778 17.7882 11.1397 17.7882 10.9693C17.7882 10.7989 17.6525 10.6608 17.485 10.6608H16.3442C15.7748 10.6608 15.3115 10.1893 15.3115 9.60986C15.3115 9.03041 15.7747 8.55894 16.3442 8.55894H17.2611C17.4286 8.55894 17.5643 8.42081 17.5643 8.25042C17.5643 8.08003 17.4286 7.94189 17.2611 7.94189H16.3442C15.4404 7.94189 14.7051 8.69013 14.7051 9.60986Z" />
                                                        </svg>

                                                        {{ __('translate.Body Type') }}
                                                    </span>
                                                    {{ html_decode($car->body_type) }}
                                                </li>
                                                <li>
                                                    <span>
                                                        <svg width="19" height="16" viewBox="0 0 19 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M17.4167 7.23819H16.7833C16.5314 7.23819 16.2897 7.3586 16.1116 7.57293C15.9334 7.78726 15.8333 8.07795 15.8333 8.38106V8.76202H15.2V6.85723C15.2 6.55412 15.0999 6.26343 14.9218 6.0491C14.7436 5.83477 14.502 5.71436 14.25 5.71436H13.3C13.216 5.71436 13.1355 5.67422 13.0761 5.60278C13.0167 5.53134 12.9833 5.43444 12.9833 5.3334V4.57149C12.9833 4.26838 12.8832 3.97769 12.7051 3.76336C12.5269 3.54903 12.2853 3.42862 12.0333 3.42862H10.7667V2.28574H12.35C12.602 2.28574 12.8436 2.16533 13.0218 1.951C13.1999 1.73667 13.3 1.44598 13.3 1.14287C13.3 0.839764 13.1999 0.549069 13.0218 0.334739C12.8436 0.120409 12.602 0 12.35 0H6.01667C5.76471 0 5.52307 0.120409 5.34491 0.334739C5.16676 0.549069 5.06667 0.839764 5.06667 1.14287C5.06667 1.44598 5.16676 1.73667 5.34491 1.951C5.52307 2.16533 5.76471 2.28574 6.01667 2.28574H7.6V3.42862H6.01667C5.76471 3.42862 5.52307 3.54903 5.34491 3.76336C5.16676 3.97769 5.06667 4.26838 5.06667 4.57149C5.06667 4.67252 5.0333 4.76942 4.97392 4.84087C4.91453 4.91231 4.83399 4.95245 4.75 4.95245H3.8C3.54804 4.95245 3.30641 5.07285 3.12825 5.28719C2.95009 5.50152 2.85 5.79221 2.85 6.09532V7.61915H1.9V6.09532C1.9 5.79221 1.79991 5.50152 1.62175 5.28719C1.44359 5.07285 1.20196 4.95245 0.95 4.95245C0.698044 4.95245 0.456408 5.07285 0.278249 5.28719C0.100089 5.50152 0 5.79221 0 6.09532L0 12.1906C0 12.4937 0.100089 12.7844 0.278249 12.9988C0.456408 13.2131 0.698044 13.3335 0.95 13.3335C1.20196 13.3335 1.44359 13.2131 1.62175 12.9988C1.79991 12.7844 1.9 12.4937 1.9 12.1906V10.6668H2.85V12.5716C2.85 12.8747 2.95009 13.1654 3.12825 13.3797C3.30641 13.5941 3.54804 13.7145 3.8 13.7145H5.12113C5.20511 13.7145 5.28564 13.7546 5.34502 13.8261L6.87388 15.6653C6.96185 15.7719 7.06651 15.8563 7.18181 15.9138C7.2971 15.9713 7.42073 16.0006 7.54553 16.0002H14.25C14.502 16.0002 14.7436 15.8798 14.9218 15.6655C15.0999 15.4511 15.2 15.1604 15.2 14.8573V13.3335H15.8333V13.7145C15.8333 14.0176 15.9334 14.3083 16.1116 14.5226C16.2897 14.7369 16.5314 14.8573 16.7833 14.8573H17.4167C17.8364 14.8567 18.2389 14.6559 18.5357 14.2988C18.8325 13.9417 18.9995 13.4575 19 12.9526V9.14298C18.9995 8.63798 18.8325 8.15384 18.5357 7.79676C18.2389 7.43967 17.8364 7.23879 17.4167 7.23819ZM5.7 1.14287C5.7 1.04184 5.73336 0.944938 5.79275 0.873495C5.85214 0.802051 5.93268 0.761915 6.01667 0.761915H12.35C12.434 0.761915 12.5145 0.802051 12.5739 0.873495C12.6333 0.944938 12.6667 1.04184 12.6667 1.14287C12.6667 1.24391 12.6333 1.34081 12.5739 1.41225C12.5145 1.48369 12.434 1.52383 12.35 1.52383H6.01667C5.93268 1.52383 5.85214 1.48369 5.79275 1.41225C5.73336 1.34081 5.7 1.24391 5.7 1.14287ZM8.23333 2.28574H10.1333V3.42862H8.23333V2.28574ZM1.26667 12.1906C1.26667 12.2917 1.2333 12.3886 1.17392 12.46C1.11453 12.5315 1.03399 12.5716 0.95 12.5716C0.866015 12.5716 0.785469 12.5315 0.726083 12.46C0.666696 12.3886 0.633333 12.2917 0.633333 12.1906V6.09532C0.633333 5.99428 0.666696 5.89738 0.726083 5.82594C0.785469 5.7545 0.866015 5.71436 0.95 5.71436C1.03399 5.71436 1.11453 5.7545 1.17392 5.82594C1.2333 5.89738 1.26667 5.99428 1.26667 6.09532V12.1906ZM1.9 9.90489V8.38106H2.85V9.90489H1.9ZM14.5667 14.8573C14.5667 14.9584 14.5333 15.0553 14.4739 15.1267C14.4145 15.1982 14.334 15.2383 14.25 15.2383H7.54553C7.46155 15.2383 7.38102 15.1981 7.32165 15.1267L5.79278 13.2874C5.70478 13.181 5.6001 13.0966 5.48482 13.0391C5.36954 12.9816 5.24593 12.9522 5.12113 12.9526H3.8C3.71601 12.9526 3.63547 12.9124 3.57608 12.841C3.5167 12.7695 3.48333 12.6726 3.48333 12.5716V6.09532C3.48333 5.99428 3.5167 5.89738 3.57608 5.82594C3.63547 5.7545 3.71601 5.71436 3.8 5.71436H4.75C5.00196 5.71436 5.24359 5.59395 5.42175 5.37962C5.59991 5.16529 5.7 4.8746 5.7 4.57149C5.7 4.47045 5.73336 4.37355 5.79275 4.30211C5.85214 4.23067 5.93268 4.19053 6.01667 4.19053H12.0333C12.1173 4.19053 12.1979 4.23067 12.2573 4.30211C12.3166 4.37355 12.35 4.47045 12.35 4.57149V5.3334C12.35 5.63651 12.4501 5.92721 12.6282 6.14154C12.8064 6.35587 13.048 6.47627 13.3 6.47628H14.25C14.334 6.47628 14.4145 6.51641 14.4739 6.58785C14.5333 6.6593 14.5667 6.7562 14.5667 6.85723V14.8573ZM15.2 12.5716V9.52393H15.8333V12.5716H15.2ZM18.3667 12.9526C18.3667 13.2557 18.2666 13.5464 18.0884 13.7607C17.9103 13.975 17.6686 14.0954 17.4167 14.0954H16.7833C16.6993 14.0954 16.6188 14.0553 16.5594 13.9838C16.5 13.9124 16.4667 13.8155 16.4667 13.7145V8.38106C16.4667 8.28003 16.5 8.18313 16.5594 8.11168C16.6188 8.04024 16.6993 8.0001 16.7833 8.0001H17.4167C17.6686 8.0001 17.9103 8.12051 18.0884 8.33484C18.2666 8.54917 18.3667 8.83987 18.3667 9.14298V12.9526Z" />
                                                        </svg>

                                                        {{ __('translate.Engine Size') }}
                                                    </span>
                                                    {{ html_decode($car->engine_size) }}
                                                </li>

                                                <li>
                                                    <span>
                                                        <svg width="20" height="23" viewBox="0 0 20 23" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M2.82221 15.8551H2.01367V6.50986C2.01464 5.81158 2.32095 5.14216 2.86543 4.64847C3.4099 4.15478 4.14805 3.87714 4.91794 3.87646H15.078C15.8479 3.87714 16.5861 4.15478 17.1305 4.64847C17.675 5.14216 17.9813 5.81158 17.9823 6.50986V15.8547H17.1738V6.50986C17.173 6.00601 16.9519 5.52301 16.5591 5.1668C16.1662 4.8106 15.6336 4.61028 15.078 4.6098H4.91794C4.36242 4.61028 3.8298 4.8106 3.43691 5.1668C3.04403 5.52301 2.82296 6.00601 2.82221 6.50986V15.8551Z" />
                                                            <path
                                                                d="M13.2638 4.60972H6.73165C6.37874 4.60904 6.0405 4.48156 5.79099 4.25519C5.54148 4.02882 5.40103 3.72201 5.40039 3.40192V1.2078C5.40103 0.88771 5.54148 0.580896 5.79099 0.354527C6.0405 0.128157 6.37874 0.000678442 6.73165 0H13.2638C13.6167 0.000678442 13.955 0.128157 14.2045 0.354527C14.454 0.580896 14.5944 0.88771 14.5951 1.2078V3.40192C14.5944 3.72201 14.454 4.02882 14.2045 4.25519C13.955 4.48156 13.6167 4.60904 13.2638 4.60972ZM6.73165 0.733331C6.59317 0.734006 6.46059 0.784222 6.36271 0.873066C6.26483 0.96191 6.20957 1.0822 6.20893 1.2078V3.40192C6.20957 3.52752 6.26483 3.64781 6.36271 3.73665C6.46059 3.82549 6.59317 3.87571 6.73165 3.87639H13.2638C13.4023 3.87571 13.5349 3.82549 13.6328 3.73665C13.7306 3.64781 13.7859 3.52752 13.7865 3.40192V1.2078C13.7859 1.0822 13.7306 0.96191 13.6328 0.873066C13.5349 0.784222 13.4023 0.734006 13.2638 0.733331H6.73165Z" />
                                                            <path
                                                                d="M13.1431 18.1214H6.85349C6.74627 18.1214 6.64344 18.0828 6.56763 18.014C6.49181 17.9453 6.44922 17.852 6.44922 17.7548V9.68005C6.44954 9.36753 6.58659 9.06789 6.83028 8.84694C7.07397 8.62599 7.40437 8.50178 7.74894 8.50159H12.2476C12.5922 8.50178 12.9226 8.62599 13.1663 8.84694C13.41 9.06789 13.5471 9.36753 13.5474 9.68005V17.754C13.5475 17.8022 13.5371 17.85 13.5168 17.8946C13.4966 17.9391 13.4668 17.9796 13.4293 18.0138C13.3917 18.0479 13.3471 18.075 13.298 18.0934C13.2489 18.1119 13.1963 18.1214 13.1431 18.1214ZM7.25776 17.3881H12.7388V9.68005C12.7386 9.56199 12.6868 9.44882 12.5947 9.36538C12.5026 9.28193 12.3778 9.23501 12.2476 9.23492H7.74894C7.61878 9.23501 7.49396 9.28193 7.40188 9.36538C7.3098 9.44882 7.25797 9.56199 7.25776 9.68005V17.3881Z" />
                                                            <path
                                                                d="M6.85352 14.3715H13.1435V15.1048H6.85352V14.3715Z" />
                                                            <path
                                                                d="M6.85352 5.81824H13.1435V6.55157H6.85352V5.81824Z" />
                                                            <path
                                                                d="M16.8984 22.1441H3.10155C2.27923 22.1433 1.49084 21.8467 0.909369 21.3193C0.327901 20.7919 0.000856138 20.0768 0 19.331V16.8223C0.000427818 16.4682 0.155674 16.1287 0.431689 15.8783C0.707703 15.6279 1.08195 15.487 1.47235 15.4865H3.12257C3.41408 15.4872 3.70042 15.5564 3.9537 15.6873C4.20697 15.8182 4.41856 16.0063 4.56784 16.2334L5.3218 17.388H14.6782L15.4326 16.2334C15.5817 16.0063 15.7932 15.8181 16.0464 15.6872C16.2997 15.5563 16.586 15.4872 16.8774 15.4865H18.5277C18.918 15.487 19.2923 15.6279 19.5683 15.8783C19.8443 16.1287 19.9996 16.4682 20 16.8223V19.331C19.9991 20.0768 19.6721 20.7919 19.0906 21.3193C18.5092 21.8467 17.7208 22.1433 16.8984 22.1441ZM1.47235 16.2198C1.29632 16.2201 1.12761 16.2837 1.00318 16.3966C0.878744 16.5096 0.808752 16.6626 0.808538 16.8223V19.331C0.80918 19.8824 1.05097 20.411 1.48086 20.8009C1.91074 21.1908 2.4936 21.4101 3.10155 21.4107H16.8984C17.5064 21.4101 18.0893 21.1908 18.5191 20.8009C18.949 20.411 19.1908 19.8824 19.1915 19.331V16.8223C19.1912 16.6626 19.1213 16.5096 18.9968 16.3966C18.8724 16.2837 18.7037 16.2201 18.5277 16.2198H16.8774C16.7262 16.2203 16.5777 16.2562 16.4464 16.3241C16.315 16.392 16.2053 16.4896 16.1279 16.6074L15.2555 17.9417C15.2197 17.9964 15.1688 18.0418 15.1079 18.0733C15.0469 18.1048 14.978 18.1214 14.9078 18.1214H5.09096C5.0208 18.1214 4.95186 18.1048 4.8909 18.0733C4.82994 18.0418 4.77907 17.9964 4.74329 17.9417L3.87209 16.6074C3.79474 16.4896 3.68503 16.3919 3.55367 16.324C3.42231 16.2561 3.27378 16.2202 3.12257 16.2198H1.47235Z" />
                                                            <path
                                                                d="M12.0212 2.38332H7.97849C7.87127 2.38332 7.76844 2.34469 7.69263 2.27593C7.61681 2.20717 7.57422 2.1139 7.57422 2.01666V0.366665C7.57422 0.26942 7.61681 0.176157 7.69263 0.107394C7.76844 0.0386307 7.87127 0 7.97849 0H12.0212C12.1284 0 12.2312 0.0386307 12.307 0.107394C12.3829 0.176157 12.4254 0.26942 12.4254 0.366665V2.01666C12.4254 2.1139 12.3829 2.20717 12.307 2.27593C12.2312 2.34469 12.1284 2.38332 12.0212 2.38332ZM8.38276 1.64999H11.6169V0.733331H8.38276V1.64999Z" />
                                                        </svg>

                                                        {{ __('translate.Drive') }}
                                                    </span>
                                                    {{ html_decode($car->drive) }}
                                                </li>

                                                <li>
                                                    <span>
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M10 8.99982C8.89713 8.99982 8 9.89694 8 10.9998C8 12.1027 8.89713 12.9998 10 12.9998C11.1029 12.9998 12 12.1027 12 10.9998C12 9.89694 11.1029 8.99982 10 8.99982ZM10 12.3332C9.26479 12.3332 8.66667 11.7352 8.66667 10.9998C8.66667 10.2645 9.26479 9.66648 10 9.66648C10.7352 9.66648 11.3333 10.2645 11.3333 10.9998C11.3333 11.7352 10.7352 12.3332 10 12.3332Z" />
                                                            <path
                                                                d="M10 0C4.486 0 0 4.486 0 10C0 15.514 4.486 20 10 20C15.514 20 20 15.514 20 10C20 4.486 15.514 0 10 0ZM10 19.3333C4.8535 19.3333 0.666667 15.1465 0.666667 10C0.666667 4.8535 4.8535 0.666667 10 0.666667C15.1465 0.666667 19.3333 4.8535 19.3333 10C19.3333 15.1465 15.1465 19.3333 10 19.3333Z" />
                                                            <path
                                                                d="M10 2C5.58888 2 2 5.58888 2 10C2 14.4111 5.58888 18 10 18C14.4111 18 18 14.4111 18 10C18 5.58888 14.4111 2 10 2ZM10 2.66667C13.116 2.66667 15.7825 4.62104 16.8428 7.36771L14.3454 8.20017C13.4888 8.48567 12.5627 8.44104 11.737 8.07387C10.6332 7.583 9.36671 7.583 8.26321 8.07387L8.13692 8.12988C7.38625 8.46388 6.53521 8.53125 5.74058 8.31933L3.06967 7.60708C4.06417 4.73525 6.79421 2.66667 10 2.66667ZM2.66667 10C2.66667 9.93208 2.66996 9.86496 2.67183 9.7975L2.90333 9.87467C6.18983 10.9701 8.51404 13.8415 8.92746 17.2541C5.39054 16.7335 2.66667 13.6793 2.66667 10ZM10 17.3333C9.86763 17.3333 9.73617 17.3294 9.6055 17.3224C9.21158 13.589 6.69408 10.4356 3.11425 9.2425L2.72233 9.11188C2.75804 8.81771 2.81058 8.52875 2.88017 8.24629L5.56867 8.96325C5.929 9.05929 6.29896 9.10713 6.66858 9.10713C7.26429 9.10713 7.85854 8.98342 8.4075 8.73929L8.53379 8.68329C9.46542 8.26858 10.5341 8.26892 11.467 8.68363C12.4444 9.11754 13.5414 9.17092 14.5563 8.83238L17.0537 7.99988C17.156 8.36017 17.2314 8.7315 17.2776 9.11188L16.8857 9.2425C13.3058 10.4356 10.7883 13.589 10.3944 17.3224C10.2638 17.3294 10.1324 17.3333 10 17.3333ZM11.0726 17.2541C11.486 13.8415 13.8102 10.9701 17.0967 9.87467L17.3282 9.7975C17.33 9.86496 17.3334 9.93208 17.3334 10C17.3333 13.6793 14.6095 16.7335 11.0726 17.2541Z" />
                                                        </svg>

                                                        {{ __('translate.Interior Color') }}
                                                    </span>
                                                    {{ html_decode($car->interior_color) }}
                                                </li>



                                                <li>
                                                    <span>
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M16.6597 2.2666C16.6571 2.25505 16.6501 2.24593 16.6466 2.23503C16.641 2.21631 16.6335 2.19824 16.6243 2.18099C16.6139 2.16113 16.6014 2.14258 16.5871 2.12533C16.5755 2.111 16.5628 2.09766 16.549 2.08529C16.5322 2.07113 16.5138 2.05892 16.4943 2.04867C16.4779 2.03874 16.4604 2.03027 16.4424 2.02327C16.4207 2.01644 16.3983 2.01188 16.3757 2.0096C16.3618 2.00553 16.3477 2.00228 16.3333 2H14.6527C14.5667 0.839681 14.0283 0 13.3333 0C12.6383 0 12.0999 0.839681 12.014 2H11.3193C11.2334 0.839681 10.695 0 10 0C9.30501 0 8.7666 0.839681 8.68066 2H7.986C7.90007 0.839681 7.36165 0 6.66667 0C5.97168 0 5.43327 0.839681 5.34733 2H4.65267C4.56673 0.839681 4.02832 0 3.33333 0C2.63835 0 2.09994 0.839681 2.014 2H0.333333C0.149251 2 0 2.14925 0 2.33333V19.6667C0 19.8507 0.149251 20 0.333333 20H16.3333C16.5174 20 16.6667 19.8507 16.6667 19.6667V18.6667H19.6667C19.7668 18.6667 19.8615 18.6216 19.9246 18.5441C19.988 18.4665 20.013 18.3647 19.993 18.2666L16.6597 2.2666ZM13.3333 0.666667C13.5741 0.666667 13.91 1.17741 13.984 2H12.6839C12.7567 1.17741 13.0926 0.666667 13.3333 0.666667ZM10 0.666667C10.2407 0.666667 10.5767 1.17741 10.6507 2H9.35059C9.42334 1.17741 9.75928 0.666667 10 0.666667ZM6.66667 0.666667C6.90739 0.666667 7.24333 1.17741 7.31738 2H6.01725C6.09001 1.17741 6.42594 0.666667 6.66667 0.666667ZM3.33333 0.666667C3.57406 0.666667 3.90999 1.17741 3.98405 2H2.68392C2.75667 1.17741 3.09261 0.666667 3.33333 0.666667ZM0.666667 2.66667H2.014C2.09994 3.82699 2.63835 4.66667 3.33333 4.66667C3.51742 4.66667 3.66667 4.51742 3.66667 4.33333C3.66667 4.14925 3.51742 4 3.33333 4C3.09261 4 2.75667 3.48926 2.68262 2.66667H5.34733C5.43441 3.82699 5.97168 4.66667 6.66667 4.66667C6.85075 4.66667 7 4.51742 7 4.33333C7 4.14925 6.85075 4 6.66667 4C6.42594 4 6.09001 3.48926 6.01595 2.66667H8.68066C8.76774 3.82699 9.30501 4.66667 10 4.66667C10.1841 4.66667 10.3333 4.51742 10.3333 4.33333C10.3333 4.14925 10.1841 4 10 4C9.75928 4 9.42334 3.48926 9.34928 2.66667H12.014C12.1011 3.82699 12.6383 4.66667 13.3333 4.66667C13.5174 4.66667 13.6667 4.51742 13.6667 4.33333C13.6667 4.14925 13.5174 4 13.3333 4C13.0926 4 12.7567 3.48926 12.6826 2.66667H16V6H0.666667V2.66667ZM16 19.3333H0.666667V6.66667H16V19.3333ZM16.6667 18V5.56673L19.257 18H16.6667Z" />
                                                            <path
                                                                d="M7.33268 11.9999H5.99935C5.81527 11.9999 5.66602 12.1492 5.66602 12.3333V13.6666C5.66602 13.8507 5.81527 13.9999 5.99935 13.9999H7.33268C7.51676 13.9999 7.66602 13.8507 7.66602 13.6666V12.3333C7.66602 12.1492 7.51676 11.9999 7.33268 11.9999ZM6.99935 13.3333H6.33268V12.6666H6.99935V13.3333Z" />
                                                            <path
                                                                d="M10.6667 12H9.33333C9.14925 12 9 12.1493 9 12.3333V13.6667C9 13.8507 9.14925 14 9.33333 14H10.6667C10.8507 14 11 13.8507 11 13.6667V12.3333C11 12.1493 10.8507 12 10.6667 12ZM10.3333 13.3333H9.66667V12.6667H10.3333V13.3333Z" />
                                                            <path
                                                                d="M4.00065 12H2.66732C2.48324 12 2.33398 12.1493 2.33398 12.3333V13.6667C2.33398 13.8507 2.48324 14 2.66732 14H4.00065C4.18473 14 4.33398 13.8507 4.33398 13.6667V12.3333C4.33398 12.1493 4.18473 12 4.00065 12ZM3.66732 13.3333H3.00065V12.6667H3.66732V13.3333Z" />
                                                            <path
                                                                d="M7.33268 15.3334H5.99935C5.81527 15.3334 5.66602 15.4826 5.66602 15.6667V17C5.66602 17.1841 5.81527 17.3334 5.99935 17.3334H7.33268C7.51676 17.3334 7.66602 17.1841 7.66602 17V15.6667C7.66602 15.4826 7.51676 15.3334 7.33268 15.3334ZM6.99935 16.6667H6.33268V16H6.99935V16.6667Z" />
                                                            <path
                                                                d="M10.6667 15.3334H9.33333C9.14925 15.3334 9 15.4826 9 15.6667V17C9 17.1841 9.14925 17.3334 9.33333 17.3334H10.6667C10.8507 17.3334 11 17.1841 11 17V15.6667C11 15.4826 10.8507 15.3334 10.6667 15.3334ZM10.3333 16.6667H9.66667V16H10.3333V16.6667Z" />
                                                            <path
                                                                d="M4.00065 15.3334H2.66732C2.48324 15.3334 2.33398 15.4826 2.33398 15.6667V17C2.33398 17.1841 2.48324 17.3334 2.66732 17.3334H4.00065C4.18473 17.3334 4.33398 17.1841 4.33398 17V15.6667C4.33398 15.4826 4.18473 15.3334 4.00065 15.3334ZM3.66732 16.6667H3.00065V16H3.66732V16.6667Z" />
                                                            <path
                                                                d="M7.33268 8.66669H5.99935C5.81527 8.66669 5.66602 8.81594 5.66602 9.00002V10.3334C5.66602 10.5174 5.81527 10.6667 5.99935 10.6667H7.33268C7.51676 10.6667 7.66602 10.5174 7.66602 10.3334V9.00002C7.66602 8.81594 7.51676 8.66669 7.33268 8.66669ZM6.99935 10H6.33268V9.33335H6.99935V10Z" />
                                                            <path
                                                                d="M10.6667 8.66663H9.33333C9.14925 8.66663 9 8.81588 9 8.99996V10.3333C9 10.5174 9.14925 10.6666 9.33333 10.6666H10.6667C10.8507 10.6666 11 10.5174 11 10.3333V8.99996C11 8.81588 10.8507 8.66663 10.6667 8.66663ZM10.3333 9.99996H9.66667V9.33329H10.3333V9.99996Z" />
                                                            <path
                                                                d="M14.0007 12H12.6673C12.4832 12 12.334 12.1493 12.334 12.3333V13.6667C12.334 13.8507 12.4832 14 12.6673 14H14.0007C14.1847 14 14.334 13.8507 14.334 13.6667V12.3333C14.334 12.1493 14.1847 12 14.0007 12ZM13.6673 13.3333H13.0007V12.6667H13.6673V13.3333Z" />
                                                            <path
                                                                d="M14.0007 15.3334H12.6673C12.4832 15.3334 12.334 15.4826 12.334 15.6667V17C12.334 17.1841 12.4832 17.3334 12.6673 17.3334H14.0007C14.1847 17.3334 14.334 17.1841 14.334 17V15.6667C14.334 15.4826 14.1847 15.3334 14.0007 15.3334ZM13.6673 16.6667H13.0007V16H13.6673V16.6667Z" />
                                                            <path
                                                                d="M14.0007 8.66663H12.6673C12.4832 8.66663 12.334 8.81588 12.334 8.99996V10.3333C12.334 10.5174 12.4832 10.6666 12.6673 10.6666H14.0007C14.1847 10.6666 14.334 10.5174 14.334 10.3333V8.99996C14.334 8.81588 14.1847 8.66663 14.0007 8.66663ZM13.6673 9.99996H13.0007V9.33329H13.6673V9.99996Z" />
                                                            <path
                                                                d="M4.00065 8.66663H2.66732C2.48324 8.66663 2.33398 8.81588 2.33398 8.99996V10.3333C2.33398 10.5174 2.48324 10.6666 2.66732 10.6666H4.00065C4.18473 10.6666 4.33398 10.5174 4.33398 10.3333V8.99996C4.33398 8.81588 4.18473 8.66663 4.00065 8.66663ZM3.66732 9.99996H3.00065V9.33329H3.66732V9.99996Z" />
                                                        </svg>
                                                        {{ __('translate.Year') }}
                                                    </span>
                                                    {{ html_decode($car->year) }}
                                                </li>
                                                <li>
                                                    <span>
                                                        <svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M18.8912 10.0815L18.1165 6.28894C17.9921 5.67736 17.4744 5.25023 16.8574 5.25023H9.3699C9.21132 5.25023 9.08273 5.38046 9.08273 5.54106C9.08273 5.70166 9.21132 5.83189 9.3699 5.83189H16.8574C17.1988 5.83189 17.4852 6.06807 17.554 6.40655L18.3022 10.0695H16.9507C16.812 9.15267 16.0278 8.4482 15.0844 8.4482C14.1409 8.4482 13.3567 9.15267 13.218 10.0695H4.71468L4.83542 9.47587C4.86744 9.31856 4.76745 9.16474 4.61211 9.13236C4.45685 9.10006 4.30492 9.2012 4.2729 9.35851L4.12582 10.0815C2.94275 10.202 2.01608 11.2162 2.01608 12.4455V18.0065C2.01608 18.1672 2.14467 18.2973 2.30326 18.2973H3.11248V19.377C3.11248 19.7205 3.38786 19.9999 3.72637 19.9999H5.58316C5.92233 19.9999 6.19821 19.7205 6.19821 19.377V18.2973H8.01662V18.8495C8.01662 19.0102 8.14521 19.1404 8.30379 19.1404H14.7123C14.871 19.1404 14.9995 19.0101 14.9995 18.8495V18.2973H16.8179V19.377C16.8179 19.7205 17.0938 19.9999 17.433 19.9999H19.2897C19.6289 19.9999 19.9048 19.7205 19.9048 19.377V18.2973H20.7128C20.8715 18.2973 21 18.1671 21 18.0065V12.4443C21 11.2156 20.0737 10.2019 18.8912 10.0815ZM15.0844 9.02977C15.7102 9.02977 16.2348 9.47525 16.3662 10.0694H13.8026C13.934 9.47525 14.4586 9.02977 15.0844 9.02977ZM2.5904 13.7537H4.23205C4.57825 13.7537 4.85992 14.0389 4.85992 14.3895V15.3055H2.5904V13.7537ZM5.62381 19.377C5.62381 19.399 5.60478 19.4182 5.58312 19.4182H3.72633C3.70492 19.4182 3.68679 19.3994 3.68679 19.377V18.2973H5.62381V19.377ZM14.4251 18.5587H8.59089V17.4555H14.4251V18.5587H14.4251ZM19.3304 19.377C19.3304 19.399 19.3114 19.4182 19.2897 19.4182H17.433C17.4113 19.4182 17.3923 19.399 17.3923 19.377V18.2973H19.3304V19.377ZM20.4256 15.3055H18.1562V14.3895C18.1562 14.0389 18.4378 13.7537 18.784 13.7537H20.4256L20.4256 15.3055ZM20.4256 13.172H18.7839C18.121 13.172 17.5817 13.7181 17.5817 14.3895V15.5963C17.5817 15.7569 17.7103 15.8871 17.8689 15.8871H20.4256V17.7157H14.9995V17.1646C14.9995 17.004 14.8709 16.8738 14.7123 16.8738H8.30375C8.14516 16.8738 8.01658 17.004 8.01658 17.1646V17.7157H2.5904V15.8871H5.1471C5.30569 15.8871 5.43427 15.7569 5.43427 15.5963V14.3895C5.43427 13.7181 4.89497 13.172 4.23205 13.172H2.5904V12.4455C2.5904 11.4562 3.38507 10.6513 4.36196 10.6511C4.36216 10.6511 4.36237 10.6512 4.36258 10.6512C4.36274 10.6512 4.36286 10.6511 4.36303 10.6511H18.6542C18.6543 10.6511 18.6545 10.6512 18.6546 10.6512C18.6548 10.6512 18.655 10.6511 18.6552 10.6511C19.6315 10.6513 20.4256 11.4556 20.4256 12.4444V13.172ZM15.6308 13.172H7.38641C7.29592 13.172 7.21069 13.2152 7.15648 13.2886C7.10223 13.362 7.08534 13.4569 7.1109 13.5449L7.73059 15.6784C7.76655 15.8021 7.8787 15.8872 8.0061 15.8872H15.0111C15.1385 15.8872 15.2507 15.8021 15.2866 15.6784L15.9063 13.5449C15.9319 13.4569 15.915 13.362 15.8608 13.2886C15.8065 13.2152 15.7214 13.172 15.6308 13.172ZM14.7963 15.3055H8.22095L7.77021 13.7537H15.247L14.7963 15.3055ZM5.325 1.91809C3.43315 1.91809 1.89399 3.47677 1.89399 5.39269C1.89399 7.30862 3.43315 8.86729 5.325 8.86729C7.21685 8.86729 8.75601 7.30858 8.75601 5.39269C8.75601 3.47681 7.21685 1.91809 5.325 1.91809ZM5.325 8.28564C3.74984 8.28564 2.46834 6.98787 2.46834 5.39269C2.46834 3.79752 3.74984 2.49974 5.325 2.49974C6.90016 2.49974 8.18166 3.79752 8.18166 5.39269C8.18166 6.98787 6.90016 8.28564 5.325 8.28564ZM6.99966 4.41417L7.41962 4.5014C7.575 4.53366 7.67511 4.68735 7.64326 4.84471C7.6154 4.98234 7.49577 5.07719 7.36225 5.07719C7.34314 5.07719 7.32374 5.07523 7.30426 5.07119L6.67397 4.9403L5.89932 5.39327L6.67397 5.8462L7.30426 5.71531C7.45952 5.68319 7.61137 5.78444 7.64326 5.9418C7.67511 6.09916 7.575 6.25285 7.41962 6.28511L6.99978 6.3723L7.13556 6.78415C7.18578 6.93651 7.10458 7.10124 6.95412 7.15209C6.92396 7.16229 6.89326 7.16716 6.86314 7.16716C6.74297 7.16716 6.63095 7.09017 6.59079 6.96835L6.38696 6.35003L5.61218 5.89702V6.80221L6.03966 7.28947C6.14499 7.40954 6.13426 7.59337 6.0157 7.70008C5.961 7.74927 5.8929 7.77349 5.82505 7.77349C5.74586 7.77349 5.667 7.74053 5.61025 7.67586L5.325 7.35069L5.03975 7.67586C4.93442 7.79596 4.7529 7.80674 4.6343 7.70012C4.51574 7.59345 4.50501 7.40962 4.61034 7.28952L5.03782 6.80225V5.89706L4.26325 6.34995L4.06048 6.96802C4.02045 7.09 3.90834 7.16724 3.78797 7.16724C3.75797 7.16724 3.72744 7.16241 3.6974 7.15234C3.54686 7.10169 3.46541 6.93705 3.51542 6.78465L3.65063 6.3725L3.2303 6.28523C3.07492 6.25298 2.97481 6.09928 3.00666 5.94192C3.03851 5.78457 3.19027 5.68331 3.34566 5.71544L3.97595 5.84633L4.7506 5.3934L3.97595 4.94043L3.34566 5.07132C3.32622 5.07536 3.30682 5.07731 3.28767 5.07731C3.15415 5.07731 3.03452 4.98251 3.00666 4.84483C2.97481 4.68748 3.07492 4.53378 3.2303 4.50153L3.65075 4.41421L3.51534 4.00082C3.46541 3.84837 3.54695 3.68377 3.69748 3.63321C3.84814 3.58268 4.01055 3.66525 4.06048 3.81766L4.26329 4.43681L5.03778 4.88966V3.98326L4.6103 3.49599C4.50497 3.37593 4.5157 3.1921 4.63426 3.08539C4.75282 2.97872 4.93434 2.98959 5.03971 3.10965L5.32496 3.43482L5.61021 3.10965C5.71553 2.98959 5.8971 2.97877 6.01566 3.08539C6.13422 3.19206 6.14495 3.37588 6.03962 3.49599L5.61214 3.98326V4.88966L6.38688 4.43669L6.59075 3.81728C6.64085 3.66488 6.80351 3.58248 6.954 3.63333C7.10445 3.68411 7.18578 3.84883 7.13564 4.00119L6.99966 4.41417ZM2.05097 9.64643C0.747534 8.61613 0 7.06569 0 5.39265C0 2.41913 2.38878 0 5.325 0C6.99497 0 8.56196 0.793197 9.56671 2.13338L9.64816 1.34501C9.66469 1.18524 9.80589 1.06917 9.96366 1.08602C10.1214 1.10275 10.2359 1.24579 10.2194 1.40552L10.0625 2.92441C10.047 3.07386 9.9224 3.18498 9.77716 3.18498C9.76722 3.18498 9.75723 3.18448 9.74712 3.1834L8.24844 3.02563C8.09067 3.00902 7.9761 2.86606 7.99249 2.70629C8.00889 2.54656 8.14997 2.43053 8.30782 2.4471L9.14696 2.53541C8.2523 1.30905 6.83573 0.581611 5.32496 0.581611C2.70547 0.581611 0.574315 2.73984 0.574315 5.39261C0.574315 6.88524 1.24134 8.26854 2.40431 9.18784C2.52933 9.28668 2.55156 9.46942 2.454 9.59607C2.39737 9.66952 2.31284 9.70798 2.2274 9.70798C2.16567 9.70798 2.10345 9.68788 2.05097 9.64643Z" />
                                                        </svg>
                                                        {{ __('translate.Condition') }}
                                                    </span>
                                                    @if($car->condition == 'Used')
                                                        {{ __('translate.Used') }}
                                                    @else
                                                        {{ __('translate.New') }}
                                                    @endif

                                                </li>
                                                
                                                <li>
                                                    <span>
                                                        <svg width="19" height="16" viewBox="0 0 19 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M2.58054 6.91356H16.4207C16.5881 6.91356 16.7239 6.77542 16.7239 6.60503C16.7239 6.43465 16.5881 6.29651 16.4207 6.29651H2.58054C2.41309 6.29651 2.27734 6.43465 2.27734 6.60503C2.27734 6.77542 2.41309 6.91356 2.58054 6.91356Z" />
                                                            <path
                                                                d="M18.736 7.73774L17.9522 6.48184L17.6586 5.28173H18.2926C18.46 5.28173 18.5957 5.14359 18.5957 4.9732C18.5957 4.80282 18.46 4.66468 18.2926 4.66468H17.5076L16.7166 1.43155C16.5203 0.628813 15.7296 0 14.9167 0H4.0833C3.27039 0 2.47974 0.628813 2.28336 1.43155L1.49239 4.66468H0.707447C0.540004 4.66468 0.404255 4.80282 0.404255 4.9732C0.404255 5.14359 0.540004 5.28173 0.707447 5.28173H1.3414L1.04779 6.48184L0.263938 7.73778C0.113474 7.97893 0 8.37618 0 8.66179V13.1308C0 13.3012 0.135749 13.4393 0.303191 13.4393H0.404255V15.6915C0.404255 15.8619 0.540004 16.0001 0.707447 16.0001H3.89747C4.06491 16.0001 4.20066 15.8619 4.20066 15.6915V14.3649C4.20066 14.1945 4.06491 14.0564 3.89747 14.0564C3.73002 14.0564 3.59427 14.1945 3.59427 14.3649V15.383H1.01064V13.4393H5.33403C5.33488 13.4393 13.6704 13.4393 13.6704 13.4393C13.6731 13.4393 17.9894 13.4393 17.9894 13.4393V15.383H15.4057V14.3649C15.4057 14.1945 15.27 14.0564 15.1025 14.0564C14.9351 14.0564 14.7993 14.1945 14.7993 14.3649V15.6915C14.7993 15.8619 14.9351 16.0001 15.1025 16.0001H18.2926C18.46 16.0001 18.5957 15.8619 18.5957 15.6915V13.4393H18.6968C18.8643 13.4393 19 13.3012 19 13.1308V8.66179C19 8.37618 18.8865 7.97893 18.736 7.73774ZM0.606383 8.66179C0.606383 8.49346 0.687153 8.21069 0.775847 8.0686L1.58606 6.77042C1.60352 6.7424 1.61646 6.71172 1.62434 6.67955L2.87179 1.58059C2.99957 1.05828 3.55437 0.617048 4.0833 0.617048H14.9167C15.4456 0.617048 16.0005 1.05832 16.1282 1.58063L17.3756 6.67951C17.3835 6.71167 17.3964 6.74236 17.4139 6.77038L18.2241 8.06856C18.3128 8.21069 18.3936 8.49346 18.3936 8.66179V12.8223H13.8767L13.1444 10.9576C13.0984 10.8404 12.9869 10.7636 12.8628 10.7636H6.13716C6.01314 10.7636 5.90164 10.8404 5.85564 10.9576L5.12333 12.8223H0.606383V8.66179ZM13.2237 12.8223H5.77632L6.34252 11.3806H12.6575L13.2237 12.8223Z" />
                                                            <path
                                                                d="M1.51608 11.2778H2.65689C3.56068 11.2778 4.29602 10.5296 4.29602 9.60986C4.29602 8.69017 3.56072 7.94189 2.65689 7.94189H1.74C1.57256 7.94189 1.43681 8.08003 1.43681 8.25042C1.43681 8.42081 1.57256 8.55894 1.74 8.55894H2.65689C3.22632 8.55894 3.68964 9.03037 3.68964 9.60986C3.68964 10.1893 3.22636 10.6608 2.65689 10.6608H1.51608C1.34864 10.6608 1.21289 10.7989 1.21289 10.9693C1.21289 11.1397 1.34864 11.2778 1.51608 11.2778Z" />
                                                            <path
                                                                d="M14.7051 9.60986C14.7051 10.5296 15.4404 11.2778 16.3442 11.2778H17.485C17.6525 11.2778 17.7882 11.1397 17.7882 10.9693C17.7882 10.7989 17.6525 10.6608 17.485 10.6608H16.3442C15.7748 10.6608 15.3115 10.1893 15.3115 9.60986C15.3115 9.03041 15.7747 8.55894 16.3442 8.55894H17.2611C17.4286 8.55894 17.5643 8.42081 17.5643 8.25042C17.5643 8.08003 17.4286 7.94189 17.2611 7.94189H16.3442C15.4404 7.94189 14.7051 8.69013 14.7051 9.60986Z" />
                                                        </svg>
                                                        {{ __('translate.Car Model') }}
                                                    </span>
                                                    {{ html_decode($car->car_model) }}

                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6 ">
                                            <ul class="key-information two">
                                                <li>
                                                    <span>
                                                        <svg width="19" height="16" viewBox="0 0 19 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M19 9.29347C19 6.75456 17.9535 4.45057 16.2608 2.77159C16.2476 2.7544 16.2335 2.73758 16.2175 2.72192C16.2015 2.70626 16.1843 2.69249 16.1668 2.67963C14.4505 1.02368 12.0953 0 9.5 0C6.90472 0 4.54953 1.02374 2.83318 2.67963C2.81561 2.69255 2.79848 2.70632 2.78247 2.72192C2.76646 2.73758 2.75238 2.75434 2.73918 2.77159C1.0465 4.45057 0 6.75456 0 9.29347C0 11.7755 0.987938 14.1089 2.78179 15.8642C2.78204 15.8644 2.78229 15.8647 2.78253 15.865C2.78272 15.8651 2.78285 15.8653 2.78303 15.8654C2.78328 15.8656 2.78353 15.8659 2.78378 15.8661C2.87498 15.9553 2.99452 15.9999 3.11407 15.9999C3.23368 15.9999 3.35328 15.9553 3.44448 15.866C3.45227 15.8584 3.45931 15.8503 3.46641 15.8422L4.90617 14.4337C5.08864 14.2552 5.08864 13.9658 4.90617 13.7873C4.72371 13.6089 4.42787 13.6089 4.24547 13.7873L3.12192 14.8864C1.81179 13.4602 1.05173 11.6653 0.947197 9.75053H2.53616C2.79418 9.75053 3.00337 9.54595 3.00337 9.29347C3.00337 9.041 2.79418 8.83642 2.53616 8.83642H0.947321C1.05596 6.86974 1.86107 5.08137 3.12497 3.70343L4.24547 4.79958C4.33667 4.88879 4.45628 4.9334 4.57582 4.9334C4.69537 4.9334 4.81497 4.88879 4.90617 4.79958C5.08864 4.62102 5.08864 4.33167 4.90617 4.15318L3.78573 3.05697C5.19435 1.82055 7.0224 1.03295 9.03279 0.926727V2.48108C9.03279 2.73356 9.24197 2.93814 9.5 2.93814C9.75803 2.93814 9.96721 2.73356 9.96721 2.48108V0.926727C11.9776 1.03295 13.8056 1.82061 15.2143 3.05703L14.0938 4.15318C13.9113 4.33173 13.9113 4.62108 14.0938 4.79958C14.185 4.88879 14.3046 4.9334 14.4241 4.9334C14.5437 4.9334 14.6633 4.88879 14.7545 4.79958L15.875 3.70343C17.1389 5.08143 17.944 6.86974 18.0526 8.83642H16.4637C16.2057 8.83642 15.9965 9.041 15.9965 9.29347C15.9965 9.54595 16.2057 9.75053 16.4637 9.75053H18.0527C17.9481 11.6653 17.1881 13.4603 15.878 14.8865L14.7545 13.7873C14.5721 13.6089 14.2762 13.6089 14.0938 13.7873C13.9113 13.9659 13.9113 14.2552 14.0938 14.4337L15.5568 15.8649C15.648 15.9541 15.7676 15.9987 15.8871 15.9987C15.9469 15.9987 16.0067 15.9876 16.0629 15.9653C16.1192 15.943 16.1719 15.9095 16.2175 15.8649C18.0118 14.1096 19 11.7758 19 9.29347Z" />
                                                            <path
                                                                d="M11.6465 4.05209C11.4068 3.95818 11.135 4.07202 11.039 4.3064L9.68891 7.60329C9.62599 7.59671 9.56307 7.5922 9.50009 7.5922C8.8425 7.5922 8.24852 7.94852 7.94981 8.5221C7.63759 9.12169 7.71758 9.83811 8.16361 10.4384C8.20921 10.4997 8.26652 10.5559 8.32969 10.6008C8.69206 10.8585 9.09679 10.9947 9.50009 10.9947C10.1577 10.9947 10.7517 10.6384 11.0504 10.0648C11.3626 9.46522 11.2826 8.74886 10.8369 8.14902C10.7913 8.08747 10.7338 8.03116 10.6705 7.98606C10.6364 7.96181 10.6016 7.93969 10.5668 7.91763L11.9064 4.64626C12.0024 4.41201 11.886 4.14594 11.6465 4.05209ZM10.2177 9.64987C10.0793 9.91551 9.80434 10.0805 9.50003 10.0805C9.30043 10.0805 9.09953 10.0123 8.90268 9.87779C8.67842 9.56278 8.63437 9.22127 8.78245 8.93698C8.92075 8.67134 9.19572 8.50631 9.50009 8.50631C9.59715 8.50631 9.69445 8.52277 9.79151 8.55476C9.79468 8.55604 9.79761 8.55769 9.80085 8.55897C9.81107 8.56293 9.82134 8.56616 9.83162 8.56939C9.92071 8.60284 10.0094 8.64891 10.0974 8.709C10.3216 9.02401 10.3657 9.36558 10.2177 9.64987Z" />
                                                        </svg>
                                                        {{ __('translate.Mileage') }}
                                                    </span>
                                                    {{ html_decode($car->mileage) }}
                                                </li>
                                                <li>
                                                    <span>
                                                        <svg class="svg-stock" width="21" height="22"
                                                            viewBox="0 0 21 22" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M14.8035 10.4282H19.4522C19.9387 10.4282 20.333 10.8225 20.333 11.309V20.1191C20.333 20.6056 19.9387 21 19.4522 21H14.166C13.6795 21 13.2852 20.6056 13.2852 20.1191V16.8262M13.2852 15.4177V11.8479M16.8091 13.1591C16.4442 13.1591 16.1483 12.8633 16.1483 12.4984C16.1483 12.1335 16.4441 11.8376 16.8091 11.8376C17.174 11.8376 17.4698 12.1334 17.4698 12.4984C17.4698 12.8633 17.174 13.1591 16.8091 13.1591Z"
                                                                stroke-width="0.8" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M14.6953 16.551V15.0973C14.6953 14.8054 14.932 14.5687 15.2239 14.5687H18.3955C18.6875 14.5687 18.9241 14.8054 18.9241 15.0973V16.551C18.9241 16.8429 18.6875 17.0796 18.3955 17.0796H15.2239C14.932 17.0796 14.6953 16.8429 14.6953 16.551Z"
                                                                stroke-width="0.8" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M14.6953 19.0618V17.6082C14.6953 17.3163 14.932 17.0796 15.2239 17.0796H18.3955C18.6875 17.0796 18.9241 17.3163 18.9241 17.6082V19.0618C18.9241 19.3538 18.6875 19.5905 18.3955 19.5905H15.2239C14.932 19.5905 14.6953 19.3538 14.6953 19.0618Z"
                                                                stroke-width="0.8" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M16.8086 6.2969C18.2713 6.2969 19.4571 5.11115 19.4571 3.64845C19.4571 2.18575 18.2713 1 16.8086 1C15.3459 1 14.1602 2.18575 14.1602 3.64845C14.1602 5.11115 15.3459 6.2969 16.8086 6.2969Z"
                                                                stroke-width="0.8" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M8.74581 7.01855L9.68223 5.95738C10.3547 5.19521 11.5309 5.15859 12.2496 5.8773L14.5808 8.20849C15.2995 8.9272 15.2625 10.1031 14.5007 10.7755L11.2323 13.6592C10.8834 13.967 10.3555 13.9505 10.0267 13.6214L6.83636 10.4314C6.50718 10.1022 6.49068 9.57428 6.79853 9.22539L7.81422 8.07448M13.3216 9.93951C13.0464 10.2148 12.6001 10.2148 12.3249 9.93951L10.5183 8.13295C10.2431 7.8577 10.2431 7.41146 10.5183 7.13621C10.7936 6.86096 11.2398 6.86096 11.5151 7.13621L13.3216 8.94277C13.5969 9.21802 13.5969 9.66426 13.3216 9.93951Z"
                                                                stroke-width="0.8" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M6.97157 15.3545L9.36554 12.961L7.49672 11.0922L5.80055 12.7883C5.6931 12.8958 5.64843 13.0507 5.68264 13.1988L5.7599 13.5328C5.79371 13.6809 5.74944 13.8358 5.642 13.9433L5.36433 14.2209C5.25688 14.3284 5.10196 14.373 4.95387 14.3388L4.61986 14.2616C4.47177 14.2274 4.31644 14.272 4.209 14.3795L3.61986 14.9686C3.51282 15.0761 3.46815 15.231 3.50236 15.3791L3.57922 15.7131C3.61342 15.8612 3.56916 16.0161 3.46171 16.1236L2.87258 16.7127C2.76513 16.8201 2.6098 16.8648 2.46212 16.8306L2.12771 16.7533C1.98002 16.7195 1.82469 16.7638 1.71725 16.8712L1.36191 17.2266C1.29994 17.2885 1.25809 17.3674 1.24119 17.4531L1.00819 18.6411C0.980023 18.7851 1.0255 18.9336 1.12932 19.037L1.42026 19.3284C1.52409 19.4322 1.67258 19.4773 1.81664 19.4491L3.00417 19.2161C3.08988 19.1996 3.16916 19.1574 3.23073 19.0954L5.9756 16.3505"
                                                                stroke-width="0.8" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M8.43068 12.0264L6.93555 13.5215"
                                                                stroke-width="0.8" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M12.418 8.03949L15.3611 5.09631"
                                                                stroke-width="0.8" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M16.8086 5.55975V11.8377" stroke-width="0.8"
                                                                stroke-miterlimit="10" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>

                                                        {{ __('translate.No. of Owners') }}
                                                    </span>
                                                    {{ html_decode($car->number_of_owner) }}
                                                </li>

                                                <li>
                                                    <span>
                                                        <svg width="19" height="16" viewBox="0 0 19 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M2.58054 6.91356H16.4207C16.5881 6.91356 16.7239 6.77542 16.7239 6.60503C16.7239 6.43465 16.5881 6.29651 16.4207 6.29651H2.58054C2.41309 6.29651 2.27734 6.43465 2.27734 6.60503C2.27734 6.77542 2.41309 6.91356 2.58054 6.91356Z" />
                                                            <path
                                                                d="M18.736 7.73774L17.9522 6.48184L17.6586 5.28173H18.2926C18.46 5.28173 18.5957 5.14359 18.5957 4.9732C18.5957 4.80282 18.46 4.66468 18.2926 4.66468H17.5076L16.7166 1.43155C16.5203 0.628813 15.7296 0 14.9167 0H4.0833C3.27039 0 2.47974 0.628813 2.28336 1.43155L1.49239 4.66468H0.707447C0.540004 4.66468 0.404255 4.80282 0.404255 4.9732C0.404255 5.14359 0.540004 5.28173 0.707447 5.28173H1.3414L1.04779 6.48184L0.263938 7.73778C0.113474 7.97893 0 8.37618 0 8.66179V13.1308C0 13.3012 0.135749 13.4393 0.303191 13.4393H0.404255V15.6915C0.404255 15.8619 0.540004 16.0001 0.707447 16.0001H3.89747C4.06491 16.0001 4.20066 15.8619 4.20066 15.6915V14.3649C4.20066 14.1945 4.06491 14.0564 3.89747 14.0564C3.73002 14.0564 3.59427 14.1945 3.59427 14.3649V15.383H1.01064V13.4393H5.33403C5.33488 13.4393 13.6704 13.4393 13.6704 13.4393C13.6731 13.4393 17.9894 13.4393 17.9894 13.4393V15.383H15.4057V14.3649C15.4057 14.1945 15.27 14.0564 15.1025 14.0564C14.9351 14.0564 14.7993 14.1945 14.7993 14.3649V15.6915C14.7993 15.8619 14.9351 16.0001 15.1025 16.0001H18.2926C18.46 16.0001 18.5957 15.8619 18.5957 15.6915V13.4393H18.6968C18.8643 13.4393 19 13.3012 19 13.1308V8.66179C19 8.37618 18.8865 7.97893 18.736 7.73774ZM0.606383 8.66179C0.606383 8.49346 0.687153 8.21069 0.775847 8.0686L1.58606 6.77042C1.60352 6.7424 1.61646 6.71172 1.62434 6.67955L2.87179 1.58059C2.99957 1.05828 3.55437 0.617048 4.0833 0.617048H14.9167C15.4456 0.617048 16.0005 1.05832 16.1282 1.58063L17.3756 6.67951C17.3835 6.71167 17.3964 6.74236 17.4139 6.77038L18.2241 8.06856C18.3128 8.21069 18.3936 8.49346 18.3936 8.66179V12.8223H13.8767L13.1444 10.9576C13.0984 10.8404 12.9869 10.7636 12.8628 10.7636H6.13716C6.01314 10.7636 5.90164 10.8404 5.85564 10.9576L5.12333 12.8223H0.606383V8.66179ZM13.2237 12.8223H5.77632L6.34252 11.3806H12.6575L13.2237 12.8223Z" />
                                                            <path
                                                                d="M1.51608 11.2778H2.65689C3.56068 11.2778 4.29602 10.5296 4.29602 9.60986C4.29602 8.69017 3.56072 7.94189 2.65689 7.94189H1.74C1.57256 7.94189 1.43681 8.08003 1.43681 8.25042C1.43681 8.42081 1.57256 8.55894 1.74 8.55894H2.65689C3.22632 8.55894 3.68964 9.03037 3.68964 9.60986C3.68964 10.1893 3.22636 10.6608 2.65689 10.6608H1.51608C1.34864 10.6608 1.21289 10.7989 1.21289 10.9693C1.21289 11.1397 1.34864 11.2778 1.51608 11.2778Z" />
                                                            <path
                                                                d="M14.7051 9.60986C14.7051 10.5296 15.4404 11.2778 16.3442 11.2778H17.485C17.6525 11.2778 17.7882 11.1397 17.7882 10.9693C17.7882 10.7989 17.6525 10.6608 17.485 10.6608H16.3442C15.7748 10.6608 15.3115 10.1893 15.3115 9.60986C15.3115 9.03041 15.7747 8.55894 16.3442 8.55894H17.2611C17.4286 8.55894 17.5643 8.42081 17.5643 8.25042C17.5643 8.08003 17.4286 7.94189 17.2611 7.94189H16.3442C15.4404 7.94189 14.7051 8.69013 14.7051 9.60986Z" />
                                                        </svg>

                                                        {{ __('translate.Exterior Color') }}
                                                    </span>
                                                    {{ html_decode($car->exterior_color) }}
                                                </li>

                                                <li>
                                                    <span>
                                                        <svg width="17" height="16" viewBox="0 0 17 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M6.97903 13.9541C6.94933 14.1398 6.82886 14.3018 6.65327 14.3921C6.47768 14.4826 6.2672 14.4909 6.08409 14.4147L3.37436 13.293C3.24014 13.2376 3.17336 13.0954 3.2203 12.9651L4.58003 9.17897C4.79763 8.58163 5.44631 8.22141 6.10989 8.32933L6.53487 8.40079C7.26265 8.51977 7.7544 9.16366 7.63963 9.84777L6.97903 13.9541ZM6.31272 13.9288C6.33425 13.9383 6.35927 13.9374 6.37989 13.9262C6.40129 13.9156 6.41595 13.8958 6.41893 13.8732L7.08135 9.77023C7.09497 9.68187 7.09172 9.59194 7.07162 9.50468L6.59257 9.85943C6.36122 10.0285 6.18861 10.2582 6.0968 10.5195L5.05856 13.4111L6.31272 13.9288ZM6.43618 8.92545L6.01134 8.85399C5.96569 8.84633 5.91939 8.84257 5.87296 8.84257C5.53177 8.84342 5.22857 9.04686 5.11782 9.34936L3.8398 12.907L4.53866 13.1961L5.56004 10.3519C5.68661 9.99239 5.92419 9.67616 6.24269 9.44343L6.76207 9.05841C6.66559 8.99157 6.55419 8.94611 6.43618 8.92545Z" />
                                                            <path
                                                                d="M16.2726 2.48745L12.0422 3.65563C11.985 3.67155 11.9318 3.69817 11.8861 3.7339L10.3939 4.89891L10.1534 5.08741L8.20384 14.5839C8.10528 15.0666 7.77718 15.4809 7.31253 15.7089C6.84787 15.9368 6.29763 15.9536 5.81819 15.7544L3.09991 14.629C2.83509 14.923 2.39819 15.0233 2.01822 14.8775L0.584448 14.3264C0.101639 14.1409 -0.129326 13.6239 0.0683098 13.1714L2.67947 7.19807L2.48573 6.98831C2.1243 6.596 2.16982 6.00365 2.5874 5.66384L5.57503 3.23804C5.77617 3.07482 6.03826 2.99327 6.30346 3.01126C6.56879 3.02925 6.81558 3.14531 6.98962 3.33381L7.47281 3.85762L8.55878 3.00968H8.56046L10.5718 1.47642C10.6749 1.39767 10.7939 1.33909 10.9215 1.30409L15.5041 0.0383188C16.0347 -0.107764 16.5915 0.176623 16.748 0.673815L16.9508 1.31928C17.0266 1.55845 16.9975 1.81598 16.8703 2.03523C16.7431 2.25435 16.528 2.41709 16.2726 2.48745ZM7.22616 4.40318L6.56088 3.68212C6.48528 3.60021 6.37803 3.54977 6.26261 3.54187C6.14732 3.5341 6.03333 3.56958 5.94593 3.64044L2.95829 6.06526C2.77674 6.21293 2.75703 6.47033 2.91407 6.64085L3.22168 6.97421C3.29171 7.04993 3.31038 7.15615 3.26992 7.24875L0.592489 13.3713C0.513382 13.5523 0.605846 13.7592 0.798944 13.8333L2.23324 14.3842C2.32609 14.4198 2.43022 14.4194 2.52282 14.3831C2.61528 14.3468 2.68855 14.2774 2.72655 14.1904C2.75547 14.124 2.81175 14.0714 2.88282 14.0445C2.95375 14.0177 3.03325 14.0188 3.10328 14.0478L6.04708 15.2667C6.36869 15.3999 6.73751 15.3883 7.04888 15.2354C7.36038 15.0825 7.58045 14.8049 7.64685 14.4813L9.61543 4.89198C9.62788 4.83183 9.66212 4.7775 9.71231 4.73825L9.80127 4.66873L8.71595 3.57809L7.6213 4.43247C7.50277 4.52483 7.32705 4.51134 7.22616 4.40221V4.40318ZM16.4074 1.4701L16.2051 0.824152C16.1726 0.720119 16.0971 0.632616 15.9956 0.580721C15.894 0.528948 15.7747 0.517038 15.6638 0.547785L11.0841 1.81124C11.0286 1.82656 10.977 1.85196 10.9322 1.88611L9.15428 3.24011L10.237 4.32868L11.5235 3.32372C11.6285 3.24144 11.7504 3.18031 11.8818 3.14422L16.1128 1.97689C16.3435 1.91309 16.4753 1.68631 16.4074 1.4701Z" />
                                                            <path
                                                                d="M15.0144 9.12475C14.4849 9.13593 13.9736 8.94379 13.5991 8.5928C13.2246 8.24181 13.0194 7.76261 13.0315 7.26627C13.0315 6.41991 14.129 4.89151 14.5865 4.39687C14.697 4.2853 14.8521 4.22198 15.0144 4.22198C15.1769 4.22198 15.332 4.2853 15.4425 4.39687C15.9 4.89151 16.9976 6.41991 16.9976 7.26627C17.0095 7.76261 16.8045 8.24181 16.4299 8.5928C16.0554 8.94379 15.5441 9.13593 15.0144 9.12475ZM15.0144 4.74409C14.5148 5.28418 13.598 6.65167 13.598 7.26627C13.5849 7.62199 13.7299 7.96678 13.9985 8.21848C14.267 8.47017 14.635 8.60605 15.0144 8.59377C15.394 8.60605 15.7619 8.47017 16.0305 8.21848C16.2991 7.96678 16.4441 7.62199 16.431 7.26627C16.431 6.65192 15.514 5.28443 15.0144 4.74409Z" />
                                                            <path
                                                                d="M8.87636 4.95142C8.94925 4.96892 9.01188 5.01279 9.05027 5.07344C9.08865 5.13421 9.09968 5.20676 9.08087 5.27506L8.93926 5.79498C8.9071 5.91238 8.79427 5.99454 8.66498 5.99466C8.64125 5.99478 8.61765 5.99199 8.59469 5.9864C8.44309 5.95018 8.35154 5.80555 8.39018 5.66348L8.5318 5.14344C8.55034 5.07514 8.59703 5.01656 8.66161 4.98059C8.72619 4.94461 8.80348 4.93404 8.87636 4.95142Z" />
                                                            <path
                                                                d="M8.63307 6.66367L8.23157 8.2566C8.20135 8.37631 8.08723 8.46102 7.95599 8.46102C7.93395 8.46114 7.91216 8.45871 7.89076 8.45385C7.73878 8.42031 7.64437 8.27775 7.67977 8.13519L8.08114 6.54225C8.10448 6.44989 8.17853 6.37612 8.27553 6.3489C8.37254 6.32155 8.47771 6.34489 8.5515 6.40991C8.62516 6.47505 8.65628 6.57203 8.63307 6.6644V6.66367Z" />
                                                        </svg>

                                                        {{ __('translate.Fuel Type') }}
                                                    </span>
                                                    {{ html_decode($car->fuel_type) }}
                                                </li>

                                                <li>
                                                    <span>
                                                        <svg class="svg-stock" width="18" height="18"
                                                            viewBox="0 0 18 18" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">

                                                            <g mask="url(#mask0_390_111658)">
                                                                <path
                                                                    d="M13.7484 15.8337L13.5343 16.5192C13.3069 17.2472 12.6416 17.7363 11.8789 17.7363H6.13239C5.36967 17.7363 4.70444 17.2472 4.47702 16.5192L2.54789 10.3439C2.25398 9.40317 2.4002 8.4207 2.88054 7.63263"
                                                                    stroke-width="0.6" stroke-miterlimit="10"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path
                                                                    d="M11.541 5.65203C12.2304 5.82007 12.9205 6.0539 13.6079 6.35273C15.1642 7.02948 15.9573 8.75812 15.4525 10.3739L14.1141 14.6591"
                                                                    stroke-width="0.6" stroke-miterlimit="10"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path
                                                                    d="M3.70117 6.72571C3.89629 6.5777 4.11109 6.44942 4.34313 6.34567C5.04098 6.03317 5.74762 5.79231 6.45812 5.62391"
                                                                    stroke-width="0.6" stroke-miterlimit="10"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path
                                                                    d="M10.9609 6.60094C11.6967 6.75531 12.4389 6.99434 13.1884 7.32023C14.2459 7.77973 14.7908 8.95887 14.447 10.0593L12.5271 16.2046C12.4385 16.4883 12.1759 16.6816 11.8785 16.6816H6.13217C5.83475 16.6816 5.57213 16.4883 5.48319 16.2046L3.55416 10.0294C3.21561 8.94519 3.73487 7.77234 4.77198 7.30828C5.51307 6.97676 6.2612 6.73277 7.01635 6.57984"
                                                                    stroke-width="0.6" stroke-miterlimit="10"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path
                                                                    d="M9.7675 8.44635C10.3159 8.72479 10.6914 9.29432 10.6914 9.95139V13.0153C10.6914 13.9473 9.9359 14.7028 9.00391 14.7028C8.07191 14.7028 7.31641 13.9473 7.31641 13.0153V9.95139C7.31641 9.29397 7.69223 8.72479 8.24066 8.446"
                                                                    stroke-width="0.6" stroke-miterlimit="10"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path
                                                                    d="M8.24023 6.71168V10.8012C8.24023 11.2228 8.58199 11.5646 9.00355 11.5646C9.42514 11.5646 9.76689 11.2228 9.76689 10.8012V6.71168"
                                                                    stroke-width="0.6" stroke-miterlimit="10"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M11.9043 8.8125H12.2551" stroke-width="0.6"
                                                                    stroke-miterlimit="10" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M11.9043 11.0734H12.2551"
                                                                    stroke-width="0.6" stroke-miterlimit="10"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M11.9043 13.3342H12.2551"
                                                                    stroke-width="0.6" stroke-miterlimit="10"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path
                                                                    d="M12.3327 1.82244H13.8107C14.0209 1.82244 14.1914 1.99298 14.1914 2.20318V3.56481C14.1914 3.77501 14.0209 3.94556 13.8107 3.94556H12.2969"
                                                                    stroke-width="0.6" stroke-miterlimit="10"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path
                                                                    d="M12.1688 1.55057L11.5508 0.673354C11.3698 0.416502 11.0753 0.263713 10.7611 0.263713H7.24786C6.93367 0.263713 6.6391 0.416502 6.45815 0.673354L5.8401 1.55057C5.38718 2.19348 5.30492 3.02689 5.6234 3.74594L6.72815 6.23989C6.85524 6.52676 7.13951 6.71179 7.45328 6.71179H10.5556C10.8694 6.71179 11.1537 6.52676 11.2808 6.23989L12.3855 3.74594C12.704 3.02692 12.6217 2.19348 12.1688 1.55057Z"
                                                                    stroke-width="0.6" stroke-miterlimit="10"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </g>
                                                        </svg>
                                                        {{ __('translate.Transmission') }}
                                                    </span>
                                                    {{ html_decode($car->transmission) }}
                                                </li>
                                                <li>
                                                    <span>
                                                        <svg width="18" height="21" viewBox="0 0 18 21" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M8.88711 11.2615C8.90893 11.2615 8.93076 11.2615 8.95694 11.2615C8.96567 11.2615 8.9744 11.2615 8.98313 11.2615C8.99622 11.2615 9.01368 11.2615 9.02678 11.2615C10.3056 11.2398 11.34 10.7941 12.1038 9.94144C13.7842 8.06308 13.5049 4.84304 13.4743 4.53576C13.3652 2.22893 12.2653 1.12528 11.3575 0.61025C10.681 0.225057 9.89097 0.017312 9.00932 0H8.97877C8.9744 0 8.96567 0 8.96131 0H8.93512C8.45065 0 7.49916 0.0779042 6.58695 0.592937C5.67038 1.10797 4.55304 2.21161 4.44392 4.53576C4.41337 4.84304 4.13403 8.06308 5.81441 9.94144C6.57386 10.7941 7.60827 11.2398 8.88711 11.2615ZM5.60928 4.64396C5.60928 4.63097 5.61364 4.61799 5.61364 4.60933C5.75767 1.50615 7.97927 1.17289 8.93076 1.17289H8.94821C8.95694 1.17289 8.97004 1.17289 8.98313 1.17289C10.1616 1.19886 12.1649 1.67494 12.3002 4.60933C12.3002 4.62232 12.3002 4.6353 12.3046 4.64396C12.309 4.67425 12.6145 7.6173 11.2265 9.16673C10.6766 9.78131 9.94335 10.0843 8.97877 10.0929C8.97004 10.0929 8.96567 10.0929 8.95694 10.0929C8.94821 10.0929 8.94385 10.0929 8.93512 10.0929C7.9749 10.0843 7.23728 9.78131 6.6917 9.16673C5.30812 7.62596 5.60491 4.66992 5.60928 4.64396Z" />
                                                            <path
                                                                d="M17.9267 16.6022C17.9267 16.5979 17.9267 16.5936 17.9267 16.5892C17.9267 16.5546 17.9223 16.52 17.9223 16.481C17.8961 15.6241 17.8394 13.6202 15.9452 12.9797C15.9321 12.9754 15.9146 12.971 15.9015 12.9667C13.9331 12.469 12.2963 11.3437 12.2789 11.3307C12.0126 11.1446 11.646 11.2095 11.4583 11.4735C11.2706 11.7375 11.3361 12.1011 11.6024 12.2872C11.6766 12.3391 13.4137 13.538 15.5873 14.092C16.6042 14.4512 16.7177 15.5289 16.7483 16.5157C16.7483 16.5546 16.7483 16.5892 16.7526 16.6239C16.757 17.0134 16.7308 17.615 16.661 17.9612C15.9539 18.3594 13.1824 19.7357 8.96613 19.7357C4.76736 19.7357 1.97836 18.3551 1.26693 17.9569C1.19709 17.6107 1.16654 17.0091 1.17527 16.6195C1.17527 16.5849 1.17964 16.5503 1.17964 16.5113C1.21019 15.5246 1.32367 14.4469 2.34063 14.0877C4.51421 13.5337 6.25133 12.3305 6.32553 12.2829C6.59177 12.0968 6.65724 11.7332 6.46956 11.4692C6.28188 11.2052 5.91525 11.1403 5.64901 11.3264C5.63155 11.3394 4.00355 12.4647 2.02637 12.9624C2.00892 12.9667 1.99582 12.971 1.98273 12.9754C0.0884804 13.6202 0.0317404 15.6241 0.00555262 16.4767C0.00555262 16.5157 0.00555252 16.5503 0.0011879 16.5849C0.0011879 16.5892 0.0011879 16.5936 0.0011879 16.5979C-0.00317673 16.823 -0.00754126 17.9785 0.223784 18.5585C0.26743 18.671 0.345993 18.7662 0.450744 18.8312C0.581683 18.9177 3.71985 20.8999 8.97049 20.8999C14.2211 20.8999 17.3593 18.9134 17.4902 18.8312C17.5906 18.7662 17.6736 18.671 17.7172 18.5585C17.9354 17.9829 17.9311 16.8273 17.9267 16.6022Z" />
                                                        </svg>
                                                        {{ __('translate.Seller Type') }}
                                                    </span>
                                                    {{ html_decode($car->seller_type) }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="accordion aos-init aos-animate" id="accordionPanelsStayOpenExample2" data-aos="fade-up"
                    data-aos-delay="200">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingthree">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsethree" aria-expanded="true" aria-controls="panelsStayOpen-collapsethree">
                                    {{ __('translate.Features') }}
                                </button>
                            </h2>

                            <div id="panelsStayOpen-collapsethree" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingthree">
                                <div class="accordion-body">
                                    <div class="feature-list">
                                        <ul>
                                            @foreach ($car_features as $index => $car_feature)
                                                <li>
                                                    <span>
                                                        <i class="fa-solid fa-check"></i>
                                                    </span>
                                                    {{ $car_feature->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif


                    <!-- Video -->
                    @if(false)
                    <div class="accordion" id="accordionPanelsStayOpenExample3" data-aos="fade-up"
                    data-aos-delay="250">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingfour">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-collapsefour" aria-expanded="true"
                                    aria-controls="panelsStayOpen-collapsefour">
                                    {{ __('translate.Video') }}
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapsefour" class="accordion-collapse collapse show"
                                aria-labelledby="panelsStayOpen-headingfour">
                                <div class="accordion-body">
                                    {{ html_decode($car->video_description) }}



                                    <span class="inventory-details-vedio">
                                        <img src="{{ getImageOrPlaceholder($car->video_image, '874x398') }}" alt="img">

                                        <span class="overlay">
                                            <a class="my-video-links" data-autoplay="true" data-vbtype="video"
                                                href="https://youtu.be/{{ $car->video_id }}">
                                                <span>
                                                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M40 76.0001C59.8822 76.0001 76.0001 59.8827 76.0001 40C76.0001 20.1178 59.8827 3.99992 40 3.99992C20.1178 3.99992 3.99992 20.1178 3.99992 40C3.99992 59.8822 20.1178 76.0001 40 76.0001ZM40 80C62.0911 80 80 62.0911 80 40C80 17.9084 62.0911 0 40 0C17.9084 0 0 17.9084 0 40C0 62.0911 17.9084 80 40 80Z" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M50.3927 40.0003L31.9984 27.7375V52.2634L50.3927 40.0003ZM54.1089 37.6706C55.7716 38.7791 55.7716 41.2219 54.1089 42.3303L32.3513 56.8357C30.4906 58.0763 27.998 56.742 27.998 54.5057V25.4953C27.998 23.259 30.4906 21.9251 32.3513 23.1657L54.1089 37.6706Z" />
                                                    </svg>
                                                </span>
                                            </a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(false)
                    @if ($reviews->count() > 0)
                        <!-- Write Your Review -->
                        <div class="accordion aos-init aos-animate" id="accordionPanelsStayOpenExample5" data-aos="fade-up"
                        data-aos-delay="350">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingsix">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsesix" aria-expanded="false" aria-controls="panelsStayOpen-collapsesix">
                                        {{ __('translate.Car Reviews') }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapsesix" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingsix">
                                    <div class="accordion-body">

                                        @foreach ($reviews as $review)
                                            <div class="reviews">
                                                <div class="reviews-item">

                                                    <ul class="icon">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($review->rating < $i)
                                                            <li><span><i class="fa-regular fa-star"></i></span></li>
                                                            @else
                                                            <li><span><i class="fa-solid fa-star"></i></span></li>
                                                            @endif
                                                        @endfor
                                                    </ul>

                                                    <div class="text">
                                                        <h6>{{ $review->created_at->format('M d Y') }}</h6>
                                                    </div>
                                                </div>

                                                <p>
                                                    {{ html_decode($review->comment) }}
                                                </p>

                                                <div class="reviews-inner">
                                                    <div class="reviews-inner-item">
                                                        <div class="reviews-inner-img">
                                                            <img src="{{ getImageOrPlaceholder($review?->user?->image, '119x120') }}" alt="img">
                                                        </div>

                                                        <div class="reviews-inner-text">
                                                            <h3>{{ html_decode($review?->user?->name) }}</h3>
                                                            <p>{{ html_decode($review?->user?->designation) }}</p>
                                                        </div>
                                                    </div>


                                                </div>


                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="accordion aos-init aos-animate" id="accordionPanelsStayOpenExample6" data-aos="fade-up"
                    data-aos-delay="400">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingseven">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseseven" aria-expanded="true" aria-controls="panelsStayOpen-collapseseven">
                                    {{ __('translate.Write Your Review') }}
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseseven" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingseven">
                                <div class="accordion-body">

                                    <div class="write-your-review-item">
                                        <div class="write-your-review-stars">
                                            <ul class="icon">
                                                <li><i onclick="carReview(1)" data-rating="1" class="car_rat fa-solid fa-star"></i></li>
                                                <li><i onclick="carReview(2)" data-rating="2" class="car_rat fa-solid fa-star"></i></li>
                                                <li><i onclick="carReview(3)" data-rating="3" class="car_rat fa-solid fa-star"></i></li>
                                                <li><i onclick="carReview(4)" data-rating="4" class="car_rat fa-solid fa-star"></i></li>
                                                <li><i onclick="carReview(5)" data-rating="5" class="car_rat fa-solid fa-star"></i></li>

                                            </ul>
                                            <span>(5.0)</span>
                                        </div>

                                        <form method="POST" action="{{ route('user.store-review') }}">

                                            @csrf

                                            <input type="hidden" name="agent_id" value="{{ $car->agent_id }}">
                                            <input type="hidden" name="car_id" value="{{ $car->id }}">
                                            <input type="hidden" id="car_rating" name="rating" value="5">

                                            <div class="form-item">
                                                <div class="form-inner">
                                                    <label class="form-label">{{ __('translate.Review') }} <span>*</span> </label>
                                                    <textarea name="comment" class="form-control" id="review" rows="5" placeholder="{{ __('translate.Write here') }}"></textarea>
                                                </div>

                                            </div>

                                            @if($google_recaptcha->status==1)
                                                <div class="form-item">
                                                    <div class="form-inner">
                                                        <div class="g-recaptcha" data-sitekey="{{ $google_recaptcha->site_key }}"></div>
                                                    </div>

                                                </div>
                                            @endif

                                            <button type="submit" class="thm-btn-two">{{ __('translate.Submit Review') }}</button>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- Inventory Details-part-end -->

    <!-- Cars Listing-part-start -->
    @if ($related_listings->count() > 0)
        <section class="cars-listing feature-two">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row feature-taitel align-items-end">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="taitel two" >
                                    <div class="taitel-img">
                                        <span>
                                        <svg width="100" height="8" viewBox="0 0 100 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 6.5C14.3957 2.77999 52.7496 -2.53808 99 6.38995" stroke="#405FF2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>

                                        </span>
                                    </div>
                                    <span>{{ __('translate.Cars Listing') }}</span>
                                </div>
                                <h2 >{{ __('translate.Related Car Listings') }}</h2>
                            </div>
                        </div>

                        <div class="row mt-56px">
                            @foreach ($related_listings as $related_car)
                                <div class="col-lg-4 col-md-6">
                                    <div class="brand-car-item">
                                        <div class="brand-car-item-img">
                                            <img src="{{ getImageOrPlaceholder($related_car->thumb_image, '304x217') }}" alt="thumb">

                                            <div class="brand-car-item-img-text">

                                                <div class="text-df">
                                                    @if ($related_car->offer_price)
                                                        <p class="text">{{ calculate_percentage($related_car->regular_price, $related_car->offer_price) }}% {{ __('translate.Off') }}</p>
                                                    @endif

                                                    @if ($related_car->condition == 'New')
                                                        <p class="text text-two ">{{ __('translate.New') }}</p>
                                                    @else
                                                        <p class="text text-two ">{{ __('translate.Used') }}</p>
                                                    @endif
                                                </div>

                                                <div class="icon-main">

                                                    @guest('web')
                                                        <a  href="javascript:;" class="icon before_auth_wishlist">
                                                            <span>
                                                                <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z"
                                                                    stroke-width="1.3" stroke-linejoin="round"></path>
                                                            </svg>

                                                            </span>
                                                        </a>
                                                    @else
                                                    @php
                                                        $isInWishlist = false;
                                                        if (\Illuminate\Support\Facades\Schema::hasTable('wishlists')) {
                                                            $isInWishlist = App\Models\Wishlist::where('car_id',$car->id)
                                                                ->where('user_id',Auth::user()->id)
                                                                ->first();
                                                        }
                                                    @endphp
                                                    <a href="{{ route('user.add-to-wishlist', $car->id) }}" 
                                                    class="icon {{ $isInWishlist ? 'active' : '' }}">
                                                        <span>
                                                            <svg width="18" height="16" viewBox="0 0 18 16" fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z"
                                                                    stroke-width="1.3" stroke-linejoin="round"/>
                                                            </svg>
                                                        </span>
                                                    </a>

                                                    @endif


                                                    <a href="{{ route('add-to-compare', $related_car->id) }}" class="icon">
                                                        <span>
                                                            <svg width="18" height="20" viewBox="0 0 18 20" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M1 10V9C1 6.23858 3.23858 4 6 4H17L14 1"
                                                                    stroke-width="1.3" stroke-linecap="round"
                                                                    stroke-linejoin="round"></path>
                                                                <path d="M17 10V11C17 13.7614 14.7614 16 12 16H1L4 19"
                                                                    stroke-width="1.3" stroke-linecap="round"
                                                                    stroke-linejoin="round"></path>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="brand-car-inner">
                                            <div class="brand-car-inner-item">
                                                <span>{{ $related_car?->brand?->name }}</span>
                                                <p>
                                                    @if ($related_car->offer_price)
                                                        @php
                                                            $__priceText = currency($related_car->offer_price);
                                                            $__priceText = preg_replace('/^[^0-9]+/u', '', (string) $__priceText);
                                                        @endphp
                                                        €{{ $__priceText }}
                                                    @else
                                                        @php
                                                            $__priceText = currency($related_car->regular_price);
                                                            $__priceText = preg_replace('/^[^0-9]+/u', '', (string) $__priceText);
                                                        @endphp
                                                        €{{ $__priceText }}
                                                    @endif
                                                </p>
                                            </div>

                                            <a href="{{ route('listing', $related_car->slug) }}">
                                                <h3>{{ html_decode($related_car->title) }}</h3>
                                            </a>

                                            <div class="brand-car-inner-item-main">
                                                <div class="brand-car-inner-item-two">
                                                    <div class="brand-car-inner-item-thumb">
                                                        <span>
                                                            <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M20 10.2935C20 7.75456 18.9535 5.45057 17.2608 3.77159C17.2476 3.7544 17.2335 3.73758 17.2175 3.72192C17.2015 3.70626 17.1843 3.69249 17.1668 3.67963C15.4505 2.02368 13.0953 1 10.5 1C7.90472 1 5.54953 2.02374 3.83318 3.67963C3.81561 3.69255 3.79848 3.70632 3.78247 3.72192C3.76646 3.73758 3.75238 3.75434 3.73918 3.77159C2.0465 5.45057 1 7.75456 1 10.2935C1 12.7755 1.98794 15.1089 3.78179 16.8642C3.78204 16.8644 3.78229 16.8647 3.78253 16.865C3.78272 16.8651 3.78285 16.8653 3.78303 16.8654C3.78328 16.8656 3.78353 16.8659 3.78378 16.8661C3.87498 16.9553 3.99452 16.9999 4.11407 16.9999C4.23368 16.9999 4.35328 16.9553 4.44448 16.866C4.45227 16.8584 4.45931 16.8503 4.46641 16.8422L5.90617 15.4337C6.08864 15.2552 6.08864 14.9658 5.90617 14.7873C5.72371 14.6089 5.42787 14.6089 5.24547 14.7873L4.12192 15.8864C2.81179 14.4602 2.05173 12.6653 1.9472 10.7505H3.53616C3.79418 10.7505 4.00337 10.546 4.00337 10.2935C4.00337 10.041 3.79418 9.83642 3.53616 9.83642H1.94732C2.05596 7.86974 2.86107 6.08137 4.12497 4.70343L5.24547 5.79958C5.33667 5.88879 5.45628 5.9334 5.57582 5.9334C5.69537 5.9334 5.81497 5.88879 5.90617 5.79958C6.08864 5.62102 6.08864 5.33167 5.90617 5.15318L4.78573 4.05697C6.19435 2.82055 8.0224 2.03295 10.0328 1.92673V3.48108C10.0328 3.73356 10.242 3.93814 10.5 3.93814C10.758 3.93814 10.9672 3.73356 10.9672 3.48108V1.92673C12.9776 2.03295 14.8056 2.82061 16.2143 4.05703L15.0938 5.15318C14.9113 5.33173 14.9113 5.62108 15.0938 5.79958C15.185 5.88879 15.3046 5.9334 15.4241 5.9334C15.5437 5.9334 15.6633 5.88879 15.7545 5.79958L16.875 4.70343C18.1389 6.08143 18.944 7.86974 19.0526 9.83642H17.4637C17.2057 9.83642 16.9965 10.041 16.9965 10.2935C16.9965 10.546 17.2057 10.7505 17.4637 10.7505H19.0527C18.9481 12.6653 18.1881 14.4603 16.878 15.8865L15.7545 14.7873C15.5721 14.6089 15.2762 14.6089 15.0938 14.7873C14.9113 14.9659 14.9113 15.2552 15.0938 15.4337L16.5568 16.8649C16.648 16.9541 16.7676 16.9987 16.8871 16.9987C16.9469 16.9987 17.0067 16.9876 17.0629 16.9653C17.1192 16.943 17.1719 16.9095 17.2175 16.8649C19.0118 15.1096 20 12.7758 20 10.2935Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                <path d="M12.6465 5.05246C12.4068 4.95855 12.135 5.07238 12.039 5.30676L10.6889 8.60366C10.626 8.59708 10.5631 8.59257 10.5001 8.59257C9.8425 8.59257 9.24852 8.94889 8.94981 9.52246C8.63759 10.1221 8.71758 10.8385 9.16361 11.4387C9.20921 11.5001 9.26652 11.5562 9.32969 11.6012C9.69206 11.8589 10.0968 11.9951 10.5001 11.9951C11.1577 11.9951 11.7517 11.6388 12.0504 11.0652C12.3626 10.4656 12.2826 9.74922 11.8369 9.14938C11.7913 9.08783 11.7338 9.03152 11.6705 8.98643C11.6364 8.96217 11.6016 8.94005 11.5668 8.91799L12.9064 5.64663C13.0024 5.41237 12.886 5.1463 12.6465 5.05246ZM11.2177 10.6502C11.0793 10.9159 10.8043 11.0809 10.5 11.0809C10.3004 11.0809 10.0995 11.0127 9.90268 10.8782C9.67842 10.5631 9.63437 10.2216 9.78245 9.93735C9.92075 9.67171 10.1957 9.50668 10.5001 9.50668C10.5971 9.50668 10.6944 9.52313 10.7915 9.55513C10.7947 9.55641 10.7976 9.55805 10.8008 9.55933C10.8111 9.56329 10.8213 9.56652 10.8316 9.56975C10.9207 9.60321 11.0094 9.64928 11.0974 9.70937C11.3216 10.0244 11.3657 10.3659 11.2177 10.6502Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                </svg>

                                                        </span>
                                                    </div>

                                                    <span>
                                                        {{ html_decode($related_car->mileage) }}
                                                    </span>
                                                </div>
                                                <div class="brand-car-inner-item-two">
                                                    <div class="brand-car-inner-item-thumb">
                                                        <span>
                                                            <svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M15.8901 3.09765L14.3901 1.76431C14.2436 1.63409 14.0063 1.63409 13.8598 1.76431C13.7133 1.89453 13.7133 2.10547 13.8598 2.23565L15.0947 3.33331L13.8598 4.43096C13.7895 4.49346 13.75 4.57809 13.75 4.66665V5.66665C13.75 6.40202 14.4227 6.99999 15.25 6.99999V12.6666C15.25 12.8505 15.0819 13 14.875 13C14.6681 13 14.5 12.8506 14.5 12.6666V12C14.5 11.4485 13.9953 11 13.375 11H13V2.33334C13 1.59797 12.3273 1 11.5 1H4.00001C3.17275 1 2.50001 1.59797 2.50001 2.33334V14.3333C1.67275 14.3333 1 14.9313 1 15.6667V16.6667C1 16.8509 1.16773 17 1.37501 17H14.125C14.3323 17 14.5 16.8509 14.5 16.6667V15.6667C14.5 14.9313 13.8273 14.3333 13 14.3333V11.6667H13.375C13.5819 11.6667 13.75 11.8161 13.75 12V12.6667C13.75 13.2181 14.2546 13.6667 14.875 13.6667C15.4954 13.6667 16 13.2181 16 12.6667V3.33334C16 3.24478 15.9604 3.16015 15.8901 3.09765ZM3.25003 2.33334C3.25003 1.96584 3.58658 1.66669 4.00001 1.66669H11.5C11.9134 1.66669 12.25 1.96584 12.25 2.33334V14.3333H3.24999L3.25003 2.33334ZM13.75 15.6666V16.3333H1.75002V15.6666C1.75002 15.2991 2.08657 15 2.50001 15H13C13.4134 15 13.75 15.2991 13.75 15.6666ZM15.25 6.33333C14.8365 6.33333 14.5 6.03418 14.5 5.66668V4.80468L15.25 4.13803V6.33333Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                <path d="M11.041 2.52344H4.29103C4.08375 2.52344 3.91602 2.66929 3.91602 2.84954V6.76876C3.91602 6.94901 4.08375 7.09487 4.29103 7.09487H11.041C11.2483 7.09487 11.416 6.94901 11.416 6.76876V2.84951C11.416 2.66929 11.2483 2.52344 11.041 2.52344ZM10.666 6.44265H4.666V3.17562H10.666V6.44265Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                </svg>

                                                        </span>
                                                    </div>

                                                    <span>
                                                        {{ html_decode($related_car->fuel_type) }}
                                                    </span>
                                                </div>
                                                <div class="brand-car-inner-item-two">
                                                    <div class="brand-car-inner-item-thumb">
                                                        <span>
                                                            <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M17.9167 8.23819H17.2833C17.0314 8.23819 16.7897 8.3586 16.6116 8.57293C16.4334 8.78726 16.3333 9.07795 16.3333 9.38106V9.76202H15.7V7.85723C15.7 7.55412 15.5999 7.26343 15.4218 7.0491C15.2436 6.83477 15.002 6.71436 14.75 6.71436H13.8C13.716 6.71436 13.6355 6.67422 13.5761 6.60278C13.5167 6.53134 13.4833 6.43444 13.4833 6.3334V5.57149C13.4833 5.26838 13.3832 4.97769 13.2051 4.76336C13.0269 4.54903 12.7853 4.42862 12.5333 4.42862H11.2667V3.28574H12.85C13.102 3.28574 13.3436 3.16533 13.5218 2.951C13.6999 2.73667 13.8 2.44598 13.8 2.14287C13.8 1.83976 13.6999 1.54907 13.5218 1.33474C13.3436 1.12041 13.102 1 12.85 1H6.51667C6.26471 1 6.02307 1.12041 5.84491 1.33474C5.66676 1.54907 5.56667 1.83976 5.56667 2.14287C5.56667 2.44598 5.66676 2.73667 5.84491 2.951C6.02307 3.16533 6.26471 3.28574 6.51667 3.28574H8.1V4.42862H6.51667C6.26471 4.42862 6.02307 4.54903 5.84491 4.76336C5.66676 4.97769 5.56667 5.26838 5.56667 5.57149C5.56667 5.67252 5.5333 5.76942 5.47392 5.84087C5.41453 5.91231 5.33399 5.95245 5.25 5.95245H4.3C4.04804 5.95245 3.80641 6.07285 3.62825 6.28719C3.45009 6.50152 3.35 6.79221 3.35 7.09532V8.61915H2.4V7.09532C2.4 6.79221 2.29991 6.50152 2.12175 6.28719C1.94359 6.07285 1.70196 5.95245 1.45 5.95245C1.19804 5.95245 0.956408 6.07285 0.778249 6.28719C0.600089 6.50152 0.5 6.79221 0.5 7.09532L0.5 13.1906C0.5 13.4937 0.600089 13.7844 0.778249 13.9988C0.956408 14.2131 1.19804 14.3335 1.45 14.3335C1.70196 14.3335 1.94359 14.2131 2.12175 13.9988C2.29991 13.7844 2.4 13.4937 2.4 13.1906V11.6668H3.35V13.5716C3.35 13.8747 3.45009 14.1654 3.62825 14.3797C3.80641 14.5941 4.04804 14.7145 4.3 14.7145H5.62113C5.70511 14.7145 5.78564 14.7546 5.84502 14.8261L7.37388 16.6653C7.46185 16.7719 7.56651 16.8563 7.68181 16.9138C7.7971 16.9713 7.92073 17.0006 8.04553 17.0002H14.75C15.002 17.0002 15.2436 16.8798 15.4218 16.6655C15.5999 16.4511 15.7 16.1604 15.7 15.8573V14.3335H16.3333V14.7145C16.3333 15.0176 16.4334 15.3083 16.6116 15.5226C16.7897 15.7369 17.0314 15.8573 17.2833 15.8573H17.9167C18.3364 15.8567 18.7389 15.6559 19.0357 15.2988C19.3325 14.9417 19.4995 14.4575 19.5 13.9526V10.143C19.4995 9.63798 19.3325 9.15384 19.0357 8.79676C18.7389 8.43967 18.3364 8.23879 17.9167 8.23819ZM6.2 2.14287C6.2 2.04184 6.23336 1.94494 6.29275 1.87349C6.35214 1.80205 6.43268 1.76191 6.51667 1.76191H12.85C12.934 1.76191 13.0145 1.80205 13.0739 1.87349C13.1333 1.94494 13.1667 2.04184 13.1667 2.14287C13.1667 2.24391 13.1333 2.34081 13.0739 2.41225C13.0145 2.48369 12.934 2.52383 12.85 2.52383H6.51667C6.43268 2.52383 6.35214 2.48369 6.29275 2.41225C6.23336 2.34081 6.2 2.24391 6.2 2.14287ZM8.73333 3.28574H10.6333V4.42862H8.73333V3.28574ZM1.76667 13.1906C1.76667 13.2917 1.7333 13.3886 1.67392 13.46C1.61453 13.5315 1.53399 13.5716 1.45 13.5716C1.36601 13.5716 1.28547 13.5315 1.22608 13.46C1.1667 13.3886 1.13333 13.2917 1.13333 13.1906V7.09532C1.13333 6.99428 1.1667 6.89738 1.22608 6.82594C1.28547 6.7545 1.36601 6.71436 1.45 6.71436C1.53399 6.71436 1.61453 6.7545 1.67392 6.82594C1.7333 6.89738 1.76667 6.99428 1.76667 7.09532V13.1906ZM2.4 10.9049V9.38106H3.35V10.9049H2.4ZM15.0667 15.8573C15.0667 15.9584 15.0333 16.0553 14.9739 16.1267C14.9145 16.1982 14.834 16.2383 14.75 16.2383H8.04553C7.96155 16.2383 7.88102 16.1981 7.82165 16.1267L6.29278 14.2874C6.20478 14.181 6.1001 14.0966 5.98482 14.0391C5.86954 13.9816 5.74593 13.9522 5.62113 13.9526H4.3C4.21601 13.9526 4.13547 13.9124 4.07608 13.841C4.0167 13.7695 3.98333 13.6726 3.98333 13.5716V7.09532C3.98333 6.99428 4.0167 6.89738 4.07608 6.82594C4.13547 6.7545 4.21601 6.71436 4.3 6.71436H5.25C5.50196 6.71436 5.74359 6.59395 5.92175 6.37962C6.09991 6.16529 6.2 5.8746 6.2 5.57149C6.2 5.47045 6.23336 5.37355 6.29275 5.30211C6.35214 5.23067 6.43268 5.19053 6.51667 5.19053H12.5333C12.6173 5.19053 12.6979 5.23067 12.7573 5.30211C12.8166 5.37355 12.85 5.47045 12.85 5.57149V6.3334C12.85 6.63651 12.9501 6.92721 13.1282 7.14154C13.3064 7.35587 13.548 7.47627 13.8 7.47628H14.75C14.834 7.47628 14.9145 7.51641 14.9739 7.58785C15.0333 7.6593 15.0667 7.7562 15.0667 7.85723V15.8573ZM15.7 13.5716V10.5239H16.3333V13.5716H15.7ZM18.8667 13.9526C18.8667 14.2557 18.7666 14.5464 18.5884 14.7607C18.4103 14.975 18.1686 15.0954 17.9167 15.0954H17.2833C17.1993 15.0954 17.1188 15.0553 17.0594 14.9838C17 14.9124 16.9667 14.8155 16.9667 14.7145V9.38106C16.9667 9.28003 17 9.18313 17.0594 9.11168C17.1188 9.04024 17.1993 9.0001 17.2833 9.0001H17.9167C18.1686 9.0001 18.4103 9.12051 18.5884 9.33484C18.7666 9.54917 18.8667 9.83987 18.8667 10.143V13.9526Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                </svg>

                                                        </span>
                                                    </div>

                                                    <span>
                                                        {{ html_decode($related_car->engine_size) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="brand-car-btm-txt-btm">
                                                <h6 class="brand-car-btm-txt"><span>{{ __('translate.Listed by') }} :</span>{{ html_decode($related_car?->dealer?->name) }}
                                                </h6>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach


                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="inventory-details" style="padding: 0 0 60px; background:#fff;">
            <div class="container" style="max-width: 980px;">
                <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;">
                    @auth('web')
                        @if(!empty($dealer?->id))
                            <a href="{{ route('user.messages.start', $dealer->id) }}" class="thm-btn-two" style="min-width:220px;text-align:center;">Chat with seller</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="thm-btn-two" style="min-width:220px;text-align:center;">{{ __('translate.Login') }}</a>
                        <a href="{{ route('login') }}" class="thm-btn-two" style="min-width:220px;text-align:center;">Chat with seller</a>
                    @endauth
                </div>
            </div>
        </section>
    @endif

    <!--Cars Listing-part-end -->

</main>
	</div>

@endsection


@push('js_section')

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        (function(){
            const modal = document.getElementById('cdLightbox');
            if (!modal || typeof Swiper === 'undefined') return;

            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }

            const counterEl = document.getElementById('cdLightboxCounter');
            const closeEls = modal.querySelectorAll('[data-cd-close]');
            const openEls = document.querySelectorAll('.js-cd-open-lightbox');
            const closeBtn = modal.querySelector('[data-cd-close].cd-lightbox__close');

            let thumbsSwiper = null;
            let mainSwiper = null;
            let lastActiveIndex = 0;
            let lastFocusedEl = null;

            function setBodyLock(locked){
                if (locked) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }

            function stopAllVideos(){
                modal.querySelectorAll('iframe').forEach((iframe) => {
                    iframe.src = 'about:blank';
                });
            }

            function ensureYoutubeForActiveSlide(){
                if (!mainSwiper) return;
                const slide = mainSwiper.slides[mainSwiper.activeIndex];
                if (!slide) return;
                const type = slide.getAttribute('data-cd-type');
                if (type !== 'youtube') return;

                const videoId = slide.getAttribute('data-cd-youtube');
                const iframe = slide.querySelector('iframe');
                if (!videoId || !iframe) return;

                const src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(videoId) + '?autoplay=1&mute=1&rel=0&playsinline=1';
                if (iframe.src !== src) iframe.src = src;
            }

            function updateCounter(){
                if (!mainSwiper || !counterEl) return;
                const total = mainSwiper.slides.length;
                const current = mainSwiper.realIndex + 1;
                counterEl.textContent = current + ' / ' + total;
            }

            function openAt(index){
                lastActiveIndex = index || 0;

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
                    thumbsSwiper = new Swiper('#cdLightboxThumbs', {
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
                    mainSwiper = new Swiper('#cdLightboxMain', {
                        initialSlide: lastActiveIndex,
                        loop: false,
                        zoom: { maxRatio: 3 },
                        keyboard: { enabled: true },
                        navigation: {
                            nextEl: '#cdLightboxMain .swiper-button-next',
                            prevEl: '#cdLightboxMain .swiper-button-prev'
                        },
                        thumbs: { swiper: thumbsSwiper },
                        on: {
                            slideChange: function(){
                                stopAllVideos();
                                updateCounter();
                                ensureYoutubeForActiveSlide();
                            },
                            afterInit: function(){
                                updateCounter();
                                ensureYoutubeForActiveSlide();
                            }
                        }
                    });
                } else {
                    mainSwiper.slideTo(lastActiveIndex, 0);
                    stopAllVideos();
                    updateCounter();
                    ensureYoutubeForActiveSlide();
                }

                if (closeBtn && typeof closeBtn.focus === 'function') {
                    closeBtn.focus();
                }
            }

            function close(){
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                setBodyLock(false);
                stopAllVideos();
                if (mainSwiper) mainSwiper.zoom && mainSwiper.zoom.out();

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
                    const idx = parseInt(el.getAttribute('data-cd-index') || '0', 10);
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
