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
            grid-template-columns: 560px 1fr;
            gap: 120px;
            align-items: start;
        }
        .separatorss {
    display: inline-block;
    width: 4px;
    height: 23px;
    background: #b60304;
    vertical-align: sub;
    margin: 0 8px;
}
.separatorsss {
    display: inline-block;
    width: 3px;
    height: 14px;
    background: #b60304;
    vertical-align: sub;
    margin: 0 8px;
}
        .listing-detail-hero__media{
            max-width: 560px;
            display: flex;
            flex-direction: column;
            margin-top: 50px !important;
            min-width: 0;
        }
        .listing-detail-hero .inventory-details-slick-for{
            margin: 0;
            overflow: hidden;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            position: relative;
        }
        .listing-detail-hero .inventory-details-slick-for .slick-list,
        .listing-detail-hero .inventory-details-slick-nav .slick-list{
            overflow: hidden;
            width: 100% !important;
            max-width: 100% !important;
        }
        .listing-detail-hero .inventory-details-slick-for .slick-list,
        .listing-detail-hero .inventory-details-slick-for .slick-track,
        .listing-detail-hero .inventory-details-slick-for .slick-slide{
            height: 360px !important;
        }
        .listing-detail-hero .inventory-details-slick-for .slick-slide > div{
            height: 100%;
        }
        .listing-detail-hero .inventory-details-slick-for .slick-track,
        .listing-detail-hero .inventory-details-slick-nav .slick-track{
            max-width: 100% !important;
        }
        .listing-detail-hero .inventory-details-slick-for .slick-slide,
        .listing-detail-hero .inventory-details-slick-nav .slick-slide{
            margin: 0 !important;
        }
        .listing-detail-hero .inventory-details-slick-for .inventory-details-slick-img{
            height: 360px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            border: 6px solid #2f2f2f;
            background: #f4f4f4;
            overflow: hidden;
            margin: 0 !important;
            box-sizing: border-box;
            width: 100%;
        }
        .listing-detail-hero .inventory-details-slick-for .inventory-details-slick-img img{
            width: 100%;
            height: 100%;
            max-height: 100%;
            object-fit: cover;
            border-radius: 0;
            border: 0;
            background: transparent;
        }
        .listing-detail-hero .inventory-details-slick-arrow{
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border: 0;
            border-radius: 10px;
            background: rgba(0,0,0,.45);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
        }
        .listing-detail-hero .inventory-details-slick-prev{ left: 18px; }
        .listing-detail-hero .inventory-details-slick-next{ right: 18px; }
        .listing-detail-hero .inventory-details-slick-arrow span{
            font-size: 30px;
            line-height: 1;
            margin-top: -2px;
        }
        .listing-detail-hero .inventory-details-slick-nav{
            margin-top: 18px;
        }
        .listing-detail-hero__mobile-image{
            display: none;
        }

        .js-cp-open-lightbox{cursor:pointer;-webkit-tap-highlight-color:transparent;touch-action:manipulation;}
        .lp-detail__media{
            width: 100%;
            height: 260px;
            overflow: hidden;
            border-radius: 16px;
            background: #f4f4f4;
            position: relative;
        }
        .lp-detail__media img{
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
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
            min-height: 640px;
            display: flex;
            flex-direction: column;
        }
        .listing-detail-hero__left-info{
            margin-top: 50px;
        }
        .listing-detail-hero__left-title{
            font-size: 30px;
            line-height: 1.08;
            font-weight: 700;
            color: #111;
            letter-spacing: -0.02em;
            margin-top: 20px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .listing-detail-hero__left-subtitle{
            color: rgba(17,17,17,.65);
            font-size: 13px;
            margin-bottom: 14px;
        }
        .listing-detail-hero__left-subtitle a{ color:#867b85 !important; text-decoration:none; }
        .listing-detail-hero__meta{
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 0px;
            margin-top: 100px;
        }
        .listing-detail-hero__meta-type{
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
                margin-right: 3px;
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
            font-size: 22px;
            font-weight: 400;
            margin-left: 6px;
            text-transform: uppercase;
            margin-left: 4px;
        }
        .listing-detail-hero__actions{
           display: flex;
    border: 2px solid #2b2b2b;
    border-radius: 16px;
    overflow: hidden;
    background: #e3e5ea;
    width: 90%;
    gap: 0;
        }
        .listing-detail-hero__actions a{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0px 0px;
            font-weight: 800;
            letter-spacing: 0.08em;
            background: transparent;
            color: #111;
            line-height: 1;
            flex: 1 1 0;
            min-width: 0;
            box-sizing: border-box;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-decoration: none;
            text-transform: uppercase;
            margin-left: -15px;
    border-radius: 8px;
        }
        .listing-detail-hero__actions a.action-report{
            background: #dc3545;
            color: #ffffff;
        }

        .lp-detail__report-wrap{
            display: flex;
            justify-content: flex-start;
            margin-top: 10px;
        }
        .lp-detail__report-link{
            display: inline-flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0;
            background: transparent;
            color: #6c6c6c;
            border-radius: 0;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            line-height: 1;
            gap: 8px;
        }
        .lp-detail__report-link:hover{
            color: #111;
            background: transparent;
        }

        .lp-detail__divider{
            width: 100%;
            border-top: 1px solid #e2e2e2;
            margin: 14px 0;
            border-bottom: 1px solid black;
        }
        .listing-detail-hero__actions-city{
            padding: 10px 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    letter-spacing: 0.08em;
    background: #00AEEF;
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
        .listing-detail-hero__keyinfo{
           margin-top: 157px;
    display: grid;
    gap: 15px 28px;
    color: #474749;
    font-weight: 600;
    justify-content: flex-start;
    max-width: 340px;
    margin-left: 60px;
        }
        .listing-detail-hero__keyinfo strong{
            font-weight: 900;
            color: #2b2b2b;
        }
        .listing-detail-hero__keyinfo div{
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: none;
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
    font-size: 50px;
    font-weight: 700;
    color: #2b2b2b;
    letter-spacing: -0.02em;
    text-align: end;
    /* display: flex; */
    align-items: center;
    gap: 18px;
    margin-right: 110px;
    /* flex-wrap: wrap; */

        }
        .listing-detail-description{
            padding: 105px 0;
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
        .listing-detail-hero__right{
            display: flex;
            flex-direction: column;
        }
        .listing-detail-hero__spacer{
            flex: 1 1 auto;
        }
        @media (max-width: 1199.98px){
            .listing-detail-hero__grid{grid-template-columns: 520px 1fr;gap: 60px;}
        }
        @media (max-width: 991.98px){
            .listing-detail-hero__grid{grid-template-columns: 1fr;gap: 30px;}
            .listing-detail-hero__media{max-width: 100%;margin-top:0 !important;}
            .listing-detail-hero__right{min-height: 0;}
            .listing-detail-hero__spacer{display:none;}
            .listing-detail-hero__keyinfo{margin-top: 35px;}
            .listing-detail-hero__price{margin-top: 35px;}
            .listing-detail-hero__mobile-image{display:block;margin-bottom:14px;}
            .inventory-details-slick-for{display:none;}
            .inventory-details-slick-nav{display:none;}
            .listing-detail-hero__actions{width: 100%;}
        }

        .listing-detail-hero__keyinfo{
         margin-top: 122px;
    display: grid;
    gap: 15px 28px;
    color: #474749;
    font-weight: 600;
    justify-content: center;
    max-width: 340px;
        }

        .listing-detail-hero__keyinfo strong{
            font-weight: 400;
            color: #2b2b2b;
        }

        .listing-detail-hero__keyinfo div{
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: none;
        }

        .listing-detail-hero__price{
           margin-top: 40px;
    font-size: 55px;
    font-weight: 700;
    color: #2b2b2b;
    letter-spacing: -0.02em;
    text-align: end;
    /* display: flex; */
    align-items: center;
    gap: 18px;
    margin-right: 110px;
    /* flex-wrap: wrap; */

        }

        .listing-detail-hero__warranty{
            border: 0px solid #c9c9c9;
            padding: 0px 0px;
            font-size: 13px;
            line-height: 1;
            color: #666;
            margin-top: -18px;
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
            overflow: hidden;
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease;
        }
        .cd-lightbox.is-open{ visibility: visible; opacity: 1; pointer-events: auto; }
        .cd-lightbox__backdrop{
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,.86);
            backdrop-filter: blur(6px);
            z-index: 0;
        }
        .cd-lightbox__dialog{
            position: absolute;
            inset: 0;
            z-index: 1;
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
            cursor: pointer;
        }
        .cd-lightbox__main{
            flex: 1;
            min-height: 0;
            overflow: hidden;
            padding: 0 16px;
        }
        .cd-lightbox__swiper{
            width: 100%;
            max-width: 1100px;
            height: 100%;
            margin: 0 auto;
        }
        .cd-lightbox__swiper .swiper-wrapper{height:100%;}
        .cd-lightbox__swiper .swiper-slide{
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            height: 100%;
        }
        .cd-lightbox__swiper .swiper-slide img{
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
            -webkit-transform: none;
            transform: none;
            will-change: auto;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            position: relative;
            z-index: 1;
        }

        /* iOS WebKit: disable backdrop blur */
        @supports (-webkit-touch-callout: none){
            .cd-lightbox__backdrop{backdrop-filter:none;}
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
            #lpContactPartSeller{
                height: auto;
                max-height: 70vh;
            }
            #lpContactPartSeller .offcanvas-body{
                overflow-y: auto;
                padding-top: 8px;
                padding-bottom: 96px;
            }
            #lpContactPartSeller .lp-detail__actions{
                display: grid;
                grid-template-columns: 1fr;
                gap: 12px;
            }
            #lpContactPartSeller .lp-detail__action{
                min-height: 48px;
                width: 100%;
                white-space: normal;
                text-align: center;
            }
        }
        .cd-lightbox__bottombar{
            flex: 0 0 auto;
            padding: 12px 16px;
            background: #fff;
            border-top: 1px solid #ddd;
        }
        .cd-lightbox__bottombar .lp-detail__price-row{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .cd-lightbox__bottombar .lp-detail__label{
            font-size: 14px;
            color: #666;
        }
        .cd-lightbox__bottombar .lp-detail__price{
            font-size: 18px;
            font-weight: 700;
            color: #2b2b2b;
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
        $__chatUserId = (int) ($seller?->id ?? ($car_part->agent_id ?? 0));
        $dealerFlagRaw = $seller?->is_dealer ?? null;
        $dealerFlagNorm = strtolower(trim((string) $dealerFlagRaw));
        $isDealerSeller = in_array($dealerFlagNorm, ['1', 'true', 'yes'], true);
        $isPartSeller = (bool) ($seller?->is_part_seller ?? false);
        $sellerDisplayName = $isDealerSeller && $isPartSeller && !empty($seller?->part_company_name)
            ? html_decode($seller?->part_company_name)
            : html_decode($seller?->name);
        $sellerTypeLabel = $isDealerSeller
            ? ($isPartSeller ? 'Part Seller' : 'Dealer')
            : 'Private';
        $partAddress = $isDealerSeller && $isPartSeller
            ? html_decode($seller?->part_company_address)
            : html_decode($partTranslation?->address);
        $partCity = null;
        if (!empty($car_part?->city?->name)) {
            $partCity = (string) $car_part->city->name;
        }
        if (!$partCity && !empty($seller?->city?->name)) {
            $partCity = (string) $seller->city->name;
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
        // Normalize for wa.me: if starts with 0, replace with 353 (Ireland default)
        $__whatsAppPhone = $__sellerPhone;
        if ($__whatsAppPhone !== '' && str_starts_with($__whatsAppPhone, '0')) {
            $__whatsAppPhone = '353' . substr($__whatsAppPhone, 1);
        }
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
                <div class="lp-mobile-card__top-left">
                    @if(!empty($seller?->username))
                        <a href="{{ route('dealer', $seller->username) }}" class="lp-detail__seller">
                            <span class="lp-detail__seller-name" style="color: white;">{{ strtoupper(trim((string) $sellerDisplayName)) ?: ' ' }}</span>
                        </a>
                    @else
                        <span class="lp-detail__seller">
                            <span class="lp-detail__seller-name">{{ strtoupper(trim((string) $sellerDisplayName)) ?: ' ' }}</span>
                        </span>
                    @endif
                </div>
                <div class="lp-mobile-card__top-right">{{ !empty($partCity) ? strtoupper(trim((string) $partCity)) : ' ' }}</div>
            </div>

            <div class="lp-detail__media js-cp-open-lightbox" data-cp-index="0" role="button" tabindex="0">
                @if($firstImage)
                    <img class="lp-detail__main-img" src="{{ getImageOrPlaceholder($firstImage, '920x636') }}" alt="img">
                @elseif(!empty($car_part->thumb_image))
                    <img class="lp-detail__main-img" src="{{ getImageOrPlaceholder($car_part->thumb_image, '920x636') }}" alt="img">
                @else
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:#e8e8e8;color:#999;font-size:14px;font-weight:600;letter-spacing:0.5px;">Image Coming Soon</div>
                @endif

                <div class="lp-detail__heart">
                    @guest('web')
                        <a href="javascript:;" class="before_auth_wishlist" aria-label="wishlist">
                            <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z" stroke-width="1.3" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    @else
                        @php
                            $isInWishlist = App\Models\Wishlist::where('car_part_id', $car_part->id)
                                ->where('user_id', Auth::user()->id)
                                ->first();
                        @endphp
                        <a href="{{ route('user.add-car-part-to-wishlist', $car_part->id) }}" class="{{ $isInWishlist ? 'active' : '' }}" aria-label="wishlist">
                            <svg width="18" height="16" viewBox="0 0 18 16" fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    @endguest
                </div>

                <button type="button" class="ad-share-btn js-ad-share-btn" data-share-url="{{ url()->current() }}" data-share-title="{{ $partTitle ?: config('app.name') }}" aria-label="Share ad">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11A2.99 2.99 0 1 0 15 5c0 .24.04.47.09.7L8.04 9.81a3 3 0 1 0 0 4.38l7.12 4.18c-.05.2-.08.41-.08.63a2.92 2.92 0 1 0 2.92-2.92Z"/>
                    </svg>
                    <span>Share</span>
                </button>
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
                    @php
                        $__fromY = $car_part->from_year ?? null;
                        $__toY = $car_part->to_year ?? null;
                        $__compatYears = '';
                        if (!empty($__fromY) && !empty($__toY)) {
                            $__compatYears = $__fromY . '-' . $__toY;
                        } elseif (!empty($__fromY)) {
                            $__compatYears = (string) $__fromY;
                        } elseif (!empty($__toY)) {
                            $__compatYears = (string) $__toY;
                        }
                    @endphp
                    @if(!empty($car_part?->brand?->name))<div>
                        <span class="meta-label" style="font-weight: 400 !important;">Brand:</span><span class="meta-value">{{ html_decode($car_part->brand->name) }}{{ !empty($car_part?->car_model) ? ' ' . html_decode($car_part?->car_model) : '' }}</span></div>
                    @endif
                    @if(!empty($car_part->condition))<div>
                       <span class="meta-label" style="font-weight: 400 !important;">Condition:</span><span class="meta-value">{{ html_decode($car_part->condition) }}</span></div>
                    @endif
                    @if(!empty($car_part->part_number))<div>
                        <span class="meta-label" style="font-weight: 400 !important;">Part Number:</span><span class="meta-value">{{ html_decode($car_part->part_number) }}</span></div>
                    @endif
                    @if(!empty($__compatYears))<div>
                        <span class="meta-label" style="font-weight: 400 !important;">Compatible:</span><span class="meta-value">{{ html_decode($__compatYears) }}</span></div>
                    @endif
                </div>

                <div class="lp-detail__price-row">
                    <div class="lp-detail__label">
                        @if(!empty($seller?->username))
                            <a href="{{ route('dealer', $seller->username) }}" class="lp-detail__seller">
                                <span class="lp-detail__seller-type">{!! $isDealerSeller && $isPartSeller ? 'PART SELLER' : strtoupper($sellerTypeLabel) !!}</span>
                                <span class="separatorsss"></span>
                                <span class="lp-detail__seller-name" style="color:green;">{{ strtoupper(trim((string) $sellerDisplayName)) ?: ' ' }}</span>
                            </a>
                        @else
                            <span class="lp-detail__seller">
                                <span class="lp-detail__seller-type">{!! $isDealerSeller && $isPartSeller ? 'PART SELLER' : strtoupper($sellerTypeLabel) !!}</span>
                                <span class="lp-detail__seller-sep">|</span>
                                <span class="lp-detail__seller-name">{{ strtoupper(trim((string) $sellerDisplayName)) ?: ' ' }}</span>
                            </span>
                        @endif
                    </div>
                    <div class="lp-detail__price">
                        @if (!is_null($numericPrice))
                            €{{ number_format($numericPrice, 0, '.', ',') }}
                        @else
                            {{ currency($rawPrice) }}
                        @endif

                        @if($isDealerSeller && !empty($car_part->warranty_months))
                            @php
                                $__wm = (int) $car_part->warranty_months;
                                $__wLabel = '';
                                if ($__wm > 0 && $__wm % 12 === 0) {
                                    $__years = (int) ($__wm / 12);
                                    $__wLabel = $__years . ' ' . ($__years === 1 ? 'Year' : 'Years') . ' Warranty';
                                } else {
                                    $__wLabel = $__wm . ' ' . ($__wm === 1 ? 'Month' : 'Months') . ' Warranty';
                                }
                            @endphp
                            <div class="lp-detail__warranty">{{ $__wLabel }}</div>
                        @endif
                    </div>
                </div>

                <div class="lp-detail__section">
                    <div class="lp-detail__section-title">Description</div>
                    <div class="lp-detail__section-body">
                        {!! clean($partDescription) !!}
                    </div>

                    <div class="lp-detail__divider"></div>

                    <div class="lp-detail__report-wrap d-block d-md-none">
                        @auth('web')
                            <a class="lp-detail__report-link" href="#" data-bs-toggle="modal" data-bs-target="#reportCarPartAdModal">
                                <span aria-hidden="true">⚑</span>
                                <span>Report Ad</span>
                            </a>
                        @else
                            <a class="lp-detail__report-link" href="{{ route('login') }}">
                                <span aria-hidden="true">⚑</span>
                                <span>Report Ad</span>
                            </a>
                        @endauth
                    </div>
                </div>

                @if($partAddress)
                    <div class="lp-detail__section">
                        <div class="lp-detail__section-title">Vehicle parts/acceceries trader address</div>
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
                    <a class="lp-detail__action" href="{{ $__whatsAppPhone ? 'https://wa.me/'.$__whatsAppPhone.'?text='.$__waText : '#' }}" target="_blank">whatsapp chat</a>
                    @auth('web')
                        @if($__chatUserId > 0)
                            <a class="lp-detail__action js-chat-coming-soon" href="javascript:;">chat</a>
                        @endif
                    @else
                        <a class="lp-detail__action" href="{{ route('login') }}">chat</a>
                    @endauth
                    <a class="lp-detail__action" href="{{ $__sellerPhoneRaw ? 'tel:'.html_decode($__sellerPhoneRaw) : '#' }}">call</a>
                    <a class="lp-detail__action" href="{{ !empty($seller?->email) ? 'mailto:'.html_decode($seller?->email) : '#' }}">email</a>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none d-md-block">
        <style>
            .cd-lightbox__infobar{
                flex: 0 0 auto;
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 10px 16px;
                color: #fff;
                background: rgba(0,0,0,.35);
                border-top: 1px solid rgba(255,255,255,.10);
            }
            .cd-lightbox__meta{
                display: flex;
                flex-wrap: wrap;
                gap: 10px 14px;
                align-items: center;
                font-size: 12px;
                opacity: .95;
            }
            .cd-lightbox__meta span{ white-space: nowrap; }
            .cd-lightbox__price{
                margin-left: auto;
                font-weight: 800;
                font-size: 16px;
                letter-spacing: .02em;
                white-space: nowrap;
            }
        </style>
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
                        @elseif(!empty($car_part->thumb_image))
                            <div class="listing-detail-hero__mobile-image">
                                <img src="{{ getImageOrPlaceholder($car_part->thumb_image, '920x636') }}" alt="img">
                            </div>
                        @else
                            <div class="listing-detail-hero__mobile-image" style="background:#e8e8e8;display:flex;align-items:center;justify-content:center;color:#999;font-size:14px;font-weight:600;">Image Coming Soon</div>
                        @endif

                        @php
                            $__imgUrl = function ($path) {
                                $path = trim((string) $path);
                                if ($path === '') return '';
                                if (preg_match('~^https?://~i', $path)) return $path;
                                if (env('FILESYSTEM_DISK') === 's3') {
                                    try {
                                        return \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
                                    } catch (\Throwable $e) {
                                    }
                                }
                                return asset($path);
                            };
                        @endphp

                        @php
                            $isCarPartInWishlist = false;
                            if (Auth::guard('web')->check()) {
                                $isCarPartInWishlist = App\Models\Wishlist::where('car_part_id', $car_part->id)
                                    ->where('user_id', Auth::user()->id)
                                    ->first();
                            }
                        @endphp
                        @include('partials.detail_gallery', [
                            'images' => $partImages,
                            'idPrefix' => 'carpart',
                            '__imgUrl' => $__imgUrl,
                            'wishlistType' => 'car_part',
                            'wishlistItemId' => $car_part->id,
                            'isInWishlist' => $isCarPartInWishlist,
                        ])

                        <div class="listing-detail-hero__left-info">
                            <h2 class="listing-detail-hero__left-title">{{ $partTitle }}</h2>
                            

                            <div class="listing-detail-hero__meta">
                                @if(!empty($seller?->username))
                                    <a href="{{ route('dealer', $seller->username) }}" style="text-decoration:none;">
                                        <span class="listing-detail-hero__meta-type {{ $isDealerSeller ? 'listing-detail-hero__meta-type--dealer' : 'listing-detail-hero__meta-type--private' }}">
                                            {{ $sellerTypeLabel }}
                                        </span>
                                        <span class="separatorss"></span>
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
                                @if($__chatUserId > 0)
                                    <a class="action-chat js-chat-coming-soon" style="background: #b9bcc3;" href="javascript:;">Chat</a>
                                @endif

                            @else
                                <a class="action-chat" style="background: #b9bcc3;" href="{{ route('login') }}">Chat</a>

                            @endauth
                            @if(!empty($seller?->email))
                                <a class="action-email" style="background: #c8c8c8;" href="mailto:{{ html_decode($seller->email) }}">{{ __('Email') }}</a>
                            @endif
                            @if(!empty($seller?->phone))
                                <a class="action-call" style="background: ##d2cdcd;" href="tel:{{ html_decode($seller->phone) }}">{{ __('Call') }}</a>
                            @endif

                        </div>

                        <div class="listing-detail-hero__keyinfo">
                            @php
                                $__fromY = $car_part->from_year ?? null;
                                $__toY = $car_part->to_year ?? null;
                                $__compatYears = '';
                                if (!empty($__fromY) && !empty($__toY)) {
                                    $__compatYears = $__fromY . '-' . $__toY;
                                } elseif (!empty($__fromY)) {
                                    $__compatYears = (string) $__fromY;
                                } elseif (!empty($__toY)) {
                                    $__compatYears = (string) $__toY;
                                }
                            @endphp
                            @if(!empty($car_part?->brand?->name))<div>
                        <span class="meta-label" style="font-weight: 400 !important;">Brand:</span><span class="meta-value">{{ html_decode($car_part->brand->name) }}{{ !empty($car_part?->car_model) ? ' ' . html_decode($car_part?->car_model) : '' }}</span></div>
                    @endif
                    @if(!empty($car_part->condition))<div>
                       <span class="meta-label" style="font-weight: 400 !important;">Condition:</span><span class="meta-value">{{ html_decode($car_part->condition) }}</span></div>
                    @endif
                    @if(!empty($car_part->part_number))<div>
                        <span class="meta-label" style="font-weight: 400 !important;">Part Number:</span><span class="meta-value">{{ html_decode($car_part->part_number) }}</span></div>
                    @endif
                    @if(!empty($__compatYears))<div>
                        <span class="meta-label" style="font-weight: 400 !important;">Compatible:</span><span class="meta-value">{{ html_decode($__compatYears) }}</span></div>
                    @endif
                        </div>

                        <div class="listing-detail-hero__spacer"></div>

                        <div class="listing-detail-hero__price">
                            @if (!is_null($numericPrice))
                                <span>€{{ number_format($numericPrice, 0, '.', ',') }}</span>
                            @else
                                <span>{{ currency($rawPrice) }}</span>
                            @endif

                            @if($isDealerSeller && !empty($car_part->warranty_months))
                                @php
                                    $__wm = (int) $car_part->warranty_months;
                                    $__wLabel = '';
                                    if ($__wm > 0 && $__wm % 12 === 0) {
                                        $__years = (int) ($__wm / 12);
                                        $__wLabel = $__years . ' ' . ($__years === 1 ? 'Year' : 'Years') . ' Warranty';
                                    } else {
                                        $__wLabel = $__wm . ' ' . ($__wm === 1 ? 'Month' : 'Months') . ' Warranty';
                                    }
                                @endphp
                                <div class="listing-detail-hero__warranty">{{ $__wLabel }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="listing-detail-description">
            <div class="container">
                <div>
                    <div class="listing-detail-description__title">{{ __('translate.Description') }}</div>
                    <div class="listing-detail-description__body">
                        {!! clean($partDescription) !!}
                    </div>
                </div>

                

                @if($partAddress)
                    <div style="margin-top: 50px;">
                        <div class="listing-detail-description__title">{{ __('Vehicle parts/acceceries trader address') }}</div>
                        <div class="listing-detail-description__body">{{ $partAddress }}</div>
                    </div>
                @endif
                
                <div class="lp-detail__divider"></div>

                <div class="lp-detail__report-wrap d-none d-md-block" style="margin-top: 30px;">
                    @auth('web')
                        <a class="lp-detail__report-link" href="#" data-bs-toggle="modal" data-bs-target="#reportCarPartAdModal">
                            <span aria-hidden="true">⚑</span>
                            <span>Report Ad</span>
                        </a>
                    @else
                        <a class="lp-detail__report-link" href="{{ route('login') }}">
                            <span aria-hidden="true">⚑</span>
                            <span>Report Ad</span>
                        </a>
                    @endauth
                </div>

                <div class="lp-detail__report-wrap d-block d-md-none" style="margin-top: 20px;">
                    @auth('web')
                        <a class="lp-detail__report-link" href="#" data-bs-toggle="modal" data-bs-target="#reportCarPartAdModal">
                            <span aria-hidden="true">⚑</span>
                            <span>Report Ad</span>
                        </a>
                    @else
                        <a class="lp-detail__report-link" href="{{ route('login') }}">
                            <span aria-hidden="true">⚑</span>
                            <span>Report Ad</span>
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="reportCarPartAdModal" tabindex="-1" aria-labelledby="reportCarPartAdModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('report.car-part', $car_part->id) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="reportCarPartAdModalLabel">Report Ad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <select class="form-select" name="reason" required>
                                <option value="Inappropriate">Inappropriate</option>
                                <option value="Spam">Spam</option>
                                <option value="Scam">Scam</option>
                                <option value="Duplicate">Duplicate</option>
                                <option value="Wrong information">Wrong information</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Details (optional)</label>
                            <textarea class="form-control" name="details" rows="4" maxlength="5000"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

<div class="modal fade" id="chatComingSoonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                This feature will be available soon.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="callSellerModalPart" tabindex="-1" aria-labelledby="callSellerModalPartLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="callSellerModalPartLabel">Call Seller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                @if(!empty($seller?->phone))
                    <p class="fw-bold fs-4 mb-1">{{ html_decode($seller->phone) }}</p>
                    <p class="text-muted small mb-0">On desktop? Copy this number and dial it manually.</p>
                @else
                    <p class="text-muted">Phone number not provided.</p>
                @endif
            </div>
            <div class="modal-footer">
                @if(!empty($seller?->phone))
                    <a class="btn btn-success" href="tel:{{ html_decode($seller->phone) }}">Call</a>
                @endif
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('js_section')
    <script>
        // Mobile lightbox opener – delegates to the gallery partial's exposed openAt
        (function(){
            document.addEventListener('click', function(e){
                var t = e.target && e.target.closest ? e.target.closest('.js-cp-open-lightbox') : null;
                if (!t) return;
                if (e.target.closest && e.target.closest('a')) return; // don't intercept anchor clicks (e.g. wishlist)
                e.preventDefault();
                e.stopPropagation();
                var idx = parseInt(t.getAttribute('data-cp-index') || '0', 10);
                if (!isFinite(idx)) idx = 0;
                var gallery = document.querySelector('[data-cd-gallery="carpart"]');
                if (gallery && typeof gallery.__cdOpenLightbox === 'function') {
                    gallery.__cdOpenLightbox(idx);
                }
            });
        })();

        (function(){
            function openChatComingSoon(){
                try {
                    if (window.bootstrap && bootstrap.Modal) {
                        var el = document.getElementById('chatComingSoonModal');
                        if (!el) return;
                        var modal = bootstrap.Modal.getOrCreateInstance(el);
                        modal.show();
                    } else {
                        alert('This feature will be available soon.');
                    }
                } catch (e) {
                    alert('This feature will be available soon.');
                }
            }

            document.addEventListener('click', function(e){
                var t = e.target && (e.target.closest ? e.target.closest('.js-chat-coming-soon') : null);
                if (!t) return;
                e.preventDefault();
                e.stopPropagation();
                openChatComingSoon();
            }, true);
        })();

        (function(){
            try {
                document.addEventListener('click', function(e){
                    var btn = e.target && e.target.closest
                        ? (e.target.closest('.lp-detail__actions .lp-detail__action[href^="tel:"]') || e.target.closest('.action-call[href^="tel:"]'))
                        : null;
                    if (!btn) return;
                    if (window.matchMedia && window.matchMedia('(min-width: 768px)').matches) {
                        var modalEl = document.getElementById('callSellerModalPart');
                        if (!modalEl) return;
                        if (window.bootstrap && bootstrap.Modal) {
                            e.preventDefault();
                            try { if (modalEl.parentElement !== document.body) document.body.appendChild(modalEl); } catch(ex){}
                            bootstrap.Modal.getOrCreateInstance(modalEl).show();
                        }
                    }
                }, true);
            } catch(e){}
        })();
    </script>
@endpush
