<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $__carBrandModelsJson = json_encode($carBrandModels ?? [], JSON_UNESCAPED_UNICODE);
        $__partBrandModelsJson = json_encode($partBrandModels ?? [], JSON_UNESCAPED_UNICODE);
    @endphp
    <title>CarNPart - Search System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('global/makking-font.css') }}">
    <style>
        body {
            background: #e2e1e1;
            min-height: 100vh;
        }

        .navbar-brand {
            font-size: 32px;
            font-weight: bold;
            color: #333 !important;
        }

        .car-text {
            color: #333;
        }

        .n-text {
            color: #dc3545;
        }

        .part-text {
            color: #333;
        }

        .subtitle {
            color: #999;
            font-size: 14px;
            letter-spacing: 2px;
            margin-left: 10px;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            min-height: 100vh;
        }

        .logo-section {
            position: absolute;
            top: 60px;
            left: -70px;
        }

        .search-section {
            display: flex;
            gap: 40px;
            justify-content: center;
            margin-top: 277px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 0 0 450px;
            max-width: 430px;
            width: 100%;
        }

        .search-btn {
            width: 100%;
            height: 35px;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .search-car-btn {
            background: #dc3545;
            color: white;
        }

        .search-car-btn:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .search-part-btn {
            background: #00aeef;
            color: white;
        }

        .search-part-btn:hover {
            background: #138496;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(23, 162, 184, 0.3);
        }

        .search-form {
            background: #e2e1e1;
            border: 3.5px solid #938b8b;
            border-radius: 20px;
            padding: 25px;
            backdrop-filter: blur(10px);
            padding-bottom:10px;
        }

        .form-control, .form-select {
            height: 30px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background: rgb(214 210 210);
            font-size: 14px;
            margin-bottom: 12px;
            color:#7f848a;
            text-align: center;
        }
        .form-control::placeholder {
            color: #aaa;
        }

        .year-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .make-model-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .price-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .bottom-section {
            position: absolute;
            bottom: 70px;
            right: 50px;
            text-align: right;
        }

        .bottom-section__image{
            display: inline-block;
            max-width: 620px;
            width: 85%;
            height: auto;
        }

        .buy-sell-text {
            font-size: 22px;
            font-family: 'Makking', sans-serif;
            font-weight: 800;
            color: #333;
            display: inline-block;
            margin-right: 0px;
        }

        .separator {
            display: inline-block;
            vertical-align: baseline;
            margin: 0 10px;
            width: auto;
            height: auto;
            background: transparent;
            color: #17a2b8;
            font-size: 22px;
            font-weight: 800;
            line-height: 1;
        }
        .separators {
            display: inline-block;
            width: 3px;
            height: 25px;
            background: #c51212ff;
            vertical-align: middle;
            margin: 0 6px;
        }
        .separatorss {
            display: inline-block;
            width: 4px;
            height: 25px;
            background: #646868;
            vertical-align: middle;
            margin: 0 6px;
        }

        .subtitle-bottom {
            font-size: 22px;
            color: #999;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 300;
        }

        .footer-links {
            position: fixed;
            bottom: 20px;
            left: 50px;
            font-size: 15px;
        }

        .footer-links a {
            color: #666;
            text-decoration: none;
            margin-right: 6px;
        }

        .footer-links a:hover {
            color: #333;
            text-decoration: underline;
        }

        .auth-links {
            position: fixed;
            top: 35px;
            right: 50px;
            z-index: 9999;
        }

        .auth-links a {
            text-decoration: none;
            font-size: 14px;
            font-weight: 400;
            margin-left: 0px;
        }

        .place-ad {
            color: #28a745;
        }

        .car-search-offer {
            margin-top: 80px;
            text-align: center;
        }

        .car-search-offer img {
            width: 90%;
            max-width: 720px;
            height: auto;
            display: inline-block;
        }

        .sign-in {
            color: #dc3545;
        }

        @media (max-width: 991.98px) {
            .main-container {
                padding: 16px;
                padding-top: 74px;
            }

            .logo-section {
                position: fixed;
                top: 14px;
                left: 14px;
                margin-top: 0;
                text-align: left;
                z-index: 9999;
            }

            .logo-section img {
                width: auto !important;
                max-width: 160px;
                height: auto;
            }

            .search-section {
                margin-top: 30px;
                gap: 20px;
            }

            .search-box {
                flex: 0 0 100%;
                max-width: 600px;
            }

            .bottom-section {
                position: static;
                margin-top: 20px;
                text-align: center;
            }

            .bottom-section__image{
                max-width: 520px;
            }

            .auth-links {
                position: fixed;
                top: 20px;
                right: 14px;
                margin: 0;
                text-align: right;
                z-index: 9999;
            }

            .auth-links .separatorss{
                display: inline-block;
                margin: 0 6px;
            }

            .car-search-offer {
                margin-top: 70px;
            }

            .footer-links {
                position: static;
                margin: 16px 0 20px;
                text-align: center;
                letter-spacing: 0.5px;
            }
        }

        @media (max-width: 575.98px) {
            .navbar-brand {
                font-size: 24px;
            }

            .search-btn {
                font-size: 14px;
                letter-spacing: 2px;
                height: 42px;
            }

            .search-form {
                padding: 14px;
                border-radius: 14px;
            }

            .form-control, .form-select {
                height: 40px;
                font-size: 14px;
            }

            .year-row,
            .price-row,
            .make-model-row {
                grid-template-columns: 1fr;
            }

            .buy-sell-text {
                font-size: 18px;
            }

            .subtitle-bottom {
                font-size: 14px;
                letter-spacing: 0.5px;
            }

            .separator {
                height: 22px;
                margin: 0 8px;
            }

            .footer-links {
                font-size: 14px;
                padding: 0 10px;
            }

            .auth-links a {
                font-size: 12px;
            }
        }

        .ls-mobile{
            display:none;
        }

        @media (max-width: 768px){
            .ls-desktop{
                display:none;
            }

            .ls-mobile{
                display:block;
                padding: 18px 16px 22px;
                max-width: 460px;
                margin: 0 auto;
            }

            .ls-mobile__frame{
                background:#e6e6e6;
                
                padding: 18px 14px 16px;
            }

            .ls-mobile__header{
                display:grid;
                grid-template-columns: 1fr auto 1fr;
                align-items:center;
                gap:10px;
            }

            .ls-mobile__logo{
                grid-column: 1 / -1;
                display:flex;
                justify-content:center;
                padding-top: 4px;
            }

            .ls-mobile__logo img{
                max-width: 190px;
                height:auto;
                display:block;
            }

            .ls-mobile__nav{
                margin-top: 10px;
                display:flex;
                align-items:center;
                justify-content:space-between;
                gap:12px;
            }

            .ls-mobile__nav-left{
                display:flex;
                align-items:center;
                gap:12px;
            }

            .ls-mobile__nav a{
                text-decoration:none;
                font-size: 13px;
                font-weight: 500;
                letter-spacing: .2px;
            }

            .ls-mobile__placead{
                color:#b60304;
            }

            .ls-mobile__myad{
                color:#b60304;
            }

            .ls-mobile__signin{
                color:#8b8b8b;
            }

            .ls-mobile__nav-right{
                display:flex;
                align-items:center;
                gap:10px;
            }

            .ls-mobile__menu{
                width: 34px;
                height: 34px;
                border:0;
                background: transparent;
                display:inline-flex;
                align-items:center;
                justify-content:center;
                padding:0;
            }

            .ls-mobile__menu svg{
                width: 22px;
                height: 22px;
                stroke:#6f6f6f;
            }

            .ls-mobile__keyword{
                margin-top: 50px;
            }

            .ls-mobile__keyword input{
                width:100%;
                height: 30px;
                border-radius: 0px;
                border:4px solid rgba(0,0,0,.25);
                background:#dcdcdc;
                padding: 0 14px;
                outline:none;
                font-size: 13px;
            }

            .ls-mobile__intro{
                text-align:center;
                margin-top: 50px;
                color:#9a9a9a;
                font-size: 11px;
                letter-spacing: 1px;
            }

            .ls-mobile__offer{
                text-align:center;
                margin-top: 6px;
                color:#0CA640;
                font-weight:600;
                font-size: 11px;
                letter-spacing: 1px;
            }

            .ls-mobile__card{
                margin-top: 70px;
                border: 4px solid rgba(0,0,0,.35);
                border-radius: 0px;
                padding: 14px 12px;
                background: transparent;
            }

            .ls-mobile__grid{
                display:grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                column-gap: 0px;
            }

            .ls-mobile__grid .ls-mobile__full{
                grid-column: 1 / -1;
            }

            .ls-mobile__pill,
            .ls-mobile__card select,
            .ls-mobile__card input{
                width:100%;
                height: 25px;
                border-radius: 0px;
                border:0;
                background:#d7d7d7;
                padding: 0 14px;
                font-size: 12px;
                color:#6f6f6f;
                outline:none;
            }

            .ls-mobile__actions{
                margin-top: 12px;
            }

            .ls-mobile__btn{
                width:100%;
                height: 25px;
                border-radius: 0px;
                border:0;
                font-weight: 400;
                letter-spacing: 3px;
                color:#fff;
            }

            .ls-mobile__btn--car{
                background:#B60304;
            }

            .ls-mobile__btn--part{
                background:#00A7E1;
            }

            .ls-mobile__footer{
                display:flex;
                justify-content:center;
                flex-wrap:wrap;
                gap:10px;
                padding-top: 50px;
                font-size: 11px;
                color:#8b8b8b;
            }

            .ls-mobile__footer a{
                color:#8b8b8b;
                text-decoration:none;
                text-transform:lowercase;
            }

            .ls-mobile__footer-sep{
                width:2px;
                height: 14px;
                background:#b60304;
                display:inline-block;
                opacity:.8;
            }
        }
    </style>
</head>
<body>

    <div class="ls-mobile">
        <div class="ls-mobile__frame">
            <div class="ls-mobile__header">
                <div class="ls-mobile__logo">
                    <a href="{{ route('home') }}" style="text-decoration:none;">
                        <img src="{{ asset('frontend/assets/images/logo/car-n-part.png') }}" alt="logo">
                    </a>
                </div>
            </div>

            <div class="ls-mobile__nav">
                @auth('web')
                    <div class="ls-mobile__nav-left">
                        <a class="ls-mobile__placead" href="{{ route('user.select-car-purpose') }}">Place Ad</a>
                        <button class="ls-mobile__myad" type="button" data-bs-toggle="offcanvas" data-bs-target="#lsMobileMyAds" aria-controls="lsMobileMyAds">My Ad</button>
                    </div>
                @else
                    <div class="ls-mobile__nav-left">
                        <a class="ls-mobile__placead" href="{{ route('login') }}">Place Ad</a>
                    </div>
                @endauth

                <div class="ls-mobile__nav-right">
                    @auth('web')
                        <a class="ls-mobile__signin" href="{{ route('user.dashboard') }}">{{ auth('web')->user()->name }}</a>
                    @else
                        <a class="ls-mobile__signin" href="{{ route('login') }}">Sign In</a>
                    @endauth

                    <button class="ls-mobile__menu" type="button" aria-label="menu" data-bs-toggle="offcanvas" data-bs-target="#lsMobileMenu" aria-controls="lsMobileMenu">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 7H20" stroke-width="2" stroke-linecap="round"/>
                            <path d="M4 12H20" stroke-width="2" stroke-linecap="round"/>
                            <path d="M4 17H20" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="lsMobileMenu" aria-labelledby="lsMobileMenuLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="lsMobileMenuLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="d-grid gap-2">
                        @auth('web')
                            <a class="btn btn-light text-start" href="{{ route('user.messages.index') }}">messages</a>
                        @endauth

                        <a class="btn btn-light text-start" href="{{ route('car-part-requests.index') }}">part help</a>
                        <a class="btn btn-light text-start" href="{{ route('dealers') }}">dealer</a>
                        <a class="btn btn-light text-start" href="{{ route('contact-us') }}">contact</a>

                        @auth('web')
                            <a class="btn btn-light text-start" href="{{ route('user.change-password') }}">change password</a>
                            <a class="btn btn-danger text-start" href="{{ route('user.logout') }}">log out</a>
                        @endauth
                    </div>
                </div>
            </div>

            @auth('web')
                <div class="offcanvas offcanvas-start" tabindex="-1" id="lsMobileMyAds" aria-labelledby="lsMobileMyAdsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="lsMobileMyAdsLabel">My Ads</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        @php
                            $lsMobileMyAdsCarCount = \Modules\Car\Entities\Car::where('agent_id', auth('web')->id())->count();
                            $lsMobileMyAdsCarPartCount = \App\Models\CarPart::where('agent_id', auth('web')->id())->count();
                            $lsMobileMyAdsSavedCount = 0;
                            if (\Illuminate\Support\Facades\Schema::hasTable('wishlists')) {
                                $lsMobileMyAdsSavedCount = \App\Models\Wishlist::where('user_id', auth('web')->id())->count();
                            }
                        @endphp
                        <div class="d-grid gap-2">
                            @php
                                $__u = auth('web')->user();
                                $__isDealer = (bool) optional($__u)->is_dealer;
                                $__canSellVehicle = !$__isDealer || (bool) optional($__u)->is_vehicle_seller;
                                $__canSellPart = !$__isDealer || (bool) optional($__u)->is_part_seller;
                            @endphp
                            @if($__canSellVehicle)
                                <a class="btn btn-light text-start d-flex justify-content-between align-items-center" href="{{ route('user.car.index') }}">
                                    <span>manage car ad</span>
                                    <span>{{ $lsMobileMyAdsCarCount }}</span>
                                </a>
                            @endif
                            @if($__canSellPart)
                                <a class="btn btn-light text-start d-flex justify-content-between align-items-center" href="{{ route('user.car-part.index') }}">
                                    <span>manage car part ad</span>
                                    <span>{{ $lsMobileMyAdsCarPartCount }}</span>
                                </a>
                            @endif
                            <a class="btn btn-light text-start d-flex justify-content-between align-items-center" href="{{ route('user.orders') }}">
                                <span>purchase history</span>
                                <span></span>
                            </a>
                            <a class="btn btn-light text-start d-flex justify-content-between align-items-center" href="{{ route('user.wishlists') }}">
                                <span>saved ads</span>
                                <span>{{ $lsMobileMyAdsSavedCount }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endauth

            <div class="ls-mobile__keyword">
                <form method="GET" action="{{ route('listings') }}">
                    <input type="text" name="search" placeholder="search car & part by key word" aria-label="keyword search">
                </form>
            </div>

            <div class="ls-mobile__intro">BUY &amp; SELL - NEW &amp; USED - CAR &amp; CAR PART</div>
            <div class="ls-mobile__offer">PLACE FREE AD LIMITED TIME OFFER</div>

            <form method="GET" action="{{ route('listings') }}">
                <div class="ls-mobile__card">
                    <div class="ls-mobile__grid">
                        <select class="ls-mobile__pill" name="brand_id" data-model-source="car" data-model-target="#ls_mobile_car_model">
                            <option value="">All Makes</option>
                            @foreach($brands as $brandSlug => $brandLabel)
                                <option value="{{ $brandSlug }}" {{ request()->get('brand_id') === $brandSlug ? 'selected' : '' }}>{{ $brandLabel }}</option>
                            @endforeach
                        </select>

                        <select class="ls-mobile__pill" name="model" id="ls_mobile_car_model" data-placeholder="Model" disabled>
                            <option value="">Model</option>
                        </select>

                        <select class="ls-mobile__pill" name="min_year">
                            <option value="">Min Year</option>
                            @for ($year = 1980; $year <= 2026; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                        <select class="ls-mobile__pill" name="max_year">
                            <option value="">Max Year</option>
                            @for ($year = 1980; $year <= 2026; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>

                        <select class="ls-mobile__pill" name="min_price">
                            <option value="">Min Price</option>
                            @foreach ([500,1000,2000,3000,4000,5000,6000,7000,8000,9000,10000,12000,14000,16000,18000,20000,25000,30000,35000,40000,45000,50000,55000,60000,65000,70000,75000,80000,85000,90000,95000,100000,125000,150000,175000,200000,250000,300000,350000,400000,450000,500000] as $priceOption)
                                <option value="{{ $priceOption }}">{{ number_format($priceOption) }}</option>
                            @endforeach
                        </select>
                        <select class="ls-mobile__pill" name="max_price">
                            <option value="">Max Price</option>
                            @foreach ([500,1000,2000,3000,4000,5000,6000,7000,8000,9000,10000,12000,14000,16000,18000,20000,25000,30000,35000,40000,45000,50000,55000,60000,65000,70000,75000,80000,85000,90000,95000,100000,125000,150000,175000,200000,250000,300000,350000,400000,450000,500000] as $priceOption)
                                <option value="{{ $priceOption }}">{{ number_format($priceOption) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="ls-mobile__actions">
                    <button class="ls-mobile__btn ls-mobile__btn--car" type="submit">SEARCH CAR</button>
                </div>
            </form>

            <form method="GET" action="{{ route('car-parts') }}">
                <div class="ls-mobile__card" style="margin-top: 70px;">
                    <div class="ls-mobile__grid">
                        <select class="ls-mobile__pill" name="brand_id" data-model-source="part" data-model-target="#ls_mobile_part_model">
                            <option value="">All Makes</option>
                            @foreach($brands as $brandSlug => $brandLabel)
                                <option value="{{ $brandSlug }}" {{ request()->get('brand_id') === $brandSlug ? 'selected' : '' }}>{{ $brandLabel }}</option>
                            @endforeach
                        </select>

                        <select class="ls-mobile__pill" name="model" id="ls_mobile_part_model" data-placeholder="Model" disabled>
                            <option value="">Model</option>
                        </select>

                        <select class="ls-mobile__pill" name="min_year">
                            <option value="">Min Year</option>
                            @for ($year = 1980; $year <= 2026; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                        <select class="ls-mobile__pill" name="max_year">
                            <option value="">Max Year</option>
                            @for ($year = 1980; $year <= 2026; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>

                        <input class="ls-mobile__pill ls-mobile__full" type="text" name="search" placeholder="Search By Key Word">
                    </div>
                </div>

                <div class="ls-mobile__actions">
                    <button class="ls-mobile__btn ls-mobile__btn--part" type="submit">SEARCH PART</button>
                </div>
            </form>

            <div class="ls-mobile__footer">
                <a href="{{ route('contact-us') }}">contact</a>
                <span class="ls-mobile__footer-sep"></span>
                <a href="{{ route('privacy-policy') }}">privacy policy</a>
                <span class="ls-mobile__footer-sep"></span>
                <a href="{{ route('terms-conditions') }}">terms and condition</a>
                <span class="ls-mobile__footer-sep"></span>
                <a href="{{ route('terms-conditions') }}">legal</a>
            </div>
        </div>
    </div>

    <div class="ls-desktop">
        <div class="auth-links">
            @auth('web')
                <a href="{{ route('user.dashboard') }}" class="sign-in">MY ADS</a>
                <span class="separatorss"></span>
                <a href="{{ route('user.select-car-purpose') }}" class="place-ad">PLACE AD</a>
            @else
                <a href="{{ route('login') }}" class="place-ad">PLACE AD</a>
            @endauth
            <span class="separatorss"></span>
            @auth('web')
                <a href="{{ route('user.edit-profile') }}" class="sign-in">{{ auth('web')->user()->name }}</a>
            @else
                <a href="{{ route('login') }}" class="sign-in">SIGN IN</a>
            @endauth
        </div>

        <div class="main-container">
            <div class="logo-section">
                <a href="{{ route('home') }}" style="text-decoration:none;">
                    <img class="d-lg-none" src="{{ asset('frontend/assets/images/logo/car-n-part.png') }}" alt="logo" style="width: 30%;">
                    <img class="d-none d-lg-inline-block" src="{{ getImageOrPlaceholder($setting ? $setting->logo : null, '170x46') }}" alt="logo" style="width: 30%;">
                </a>
            </div>

            <div class="search-section">
                <div class="search-box">
                    <form method="GET" action="{{ route('listings') }}">
                        <button class="search-btn search-car-btn" type="submit">SEARCH CAR</button>
                        <div class="search-form">
                            <div class="make-model-row">
                                <select class="form-select" name="brand_id" data-model-source="car" data-model-target="#ls_desktop_car_model">
                                    <option value="">All Makes</option>
                                    @foreach($brands as $brandSlug => $brandLabel)
                                        <option value="{{ $brandSlug }}" {{ request()->get('brand_id') === $brandSlug ? 'selected' : '' }}>{{ $brandLabel }}</option>
                                    @endforeach
                                </select>

                                <select class="form-select" name="model" id="ls_desktop_car_model" data-placeholder="Model" disabled>
                                    <option value="">Model</option>
                                </select>
                            </div>

                            <div class="year-row">
                                <select class="form-select" name="min_year">
                                    <option value="">Min Year</option>
                                    @for ($year = 1980; $year <= 2026; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <select class="form-select" name="max_year">
                                    <option value="">Max Year</option>
                                    @for ($year = 1980; $year <= 2026; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="price-row">
                                <select class="form-select" name="min_price">
                                    <option value="">Min Price</option>
                                    @foreach ([500,1000,2000,3000,4000,5000,6000,7000,8000,9000,10000,12000,14000,16000,18000,20000,25000,30000,35000,40000,45000,50000,55000,60000,65000,70000,75000,80000,85000,90000,95000,100000,125000,150000,175000,200000,250000,300000,350000,400000,450000,500000] as $priceOption)
                                        <option value="{{ $priceOption }}">{{ number_format($priceOption) }}</option>
                                    @endforeach
                                </select>
                                <select class="form-select" name="max_price">
                                    <option value="">Max Price</option>
                                    @foreach ([500,1000,2000,3000,4000,5000,6000,7000,8000,9000,10000,12000,14000,16000,18000,20000,25000,30000,35000,40000,45000,50000,55000,60000,65000,70000,75000,80000,85000,90000,95000,100000,125000,150000,175000,200000,250000,300000,350000,400000,450000,500000] as $priceOption)
                                        <option value="{{ $priceOption }}">{{ number_format($priceOption) }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </form>

                    <div class="car-search-offer">
                        <img src="{{ asset('frontend/images/place-free-ad-offer.png') }}" alt="Place free ad limited time offer">
                    </div>
                </div>

                <div class="search-box">
                    <form method="GET" action="{{ route('car-parts') }}">
                        <button class="search-btn search-part-btn" type="submit">SEARCH PART</button>
                        <div class="search-form">
                            <div class="make-model-row">
                                <select class="form-select part" name="brand_id" style="background: rgb(214 210 210);" data-model-source="part" data-model-target="#ls_desktop_part_model">
                                    <option value="">All Makes</option>
                                    @foreach($brands as $brandSlug => $brandLabel)
                                        <option value="{{ $brandSlug }}" {{ request()->get('brand_id') === $brandSlug ? 'selected' : '' }}>{{ $brandLabel }}</option>
                                    @endforeach
                                </select>

                                <select class="form-select part" style="background: rgb(214 210 210);" name="model" id="ls_desktop_part_model" data-placeholder="Model" disabled>
                                    <option value="">Model</option>
                                </select>
                            </div>

                            <div class="year-row part">
                                <select class="form-select part" name="min_year" style="background: rgb(214 210 210);">
                                    <option value="">Min Year</option>
                                    @for ($year = 1980; $year <= 2026; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <select class="form-select part" name="max_year" style="background: rgb(214 210 210);">
                                    <option value="">Max Year</option>
                                    @for ($year = 1980; $year <= 2026; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <input type="text" class="form-control part" style="background: rgb(214 210 210);" name="search" placeholder="Search By Key Word">
                        </div>
                    </form>
                </div>
            </div>

            <div class="bottom-section">
                <img class="bottom-section__image" src="{{ asset('frontend/images/buy-sell-banner.png') }}" alt="BUY & SELL | NEW & USED CAR AND CAR PART">
            </div>
        </div>

        <div class="footer-links">
            <a href="{{ route('contact-us') }}">contact</a>
            <span class="separators"></span>
            <a href="{{ route('privacy-policy') }}">privacy policy</a>
            <span class="separators"></span>
            <a href="{{ route('terms-conditions') }}">terms and condition</a>
            <span class="separators"></span>
            <a href="{{ route('terms-conditions') }}">Legal</a>
        </div>
    </div>

    <script>
        (function () {
            const modelMaps = {
                car: {!! $__carBrandModelsJson !!},
                part: {!! $__partBrandModelsJson !!}
            };

            function populateModelSelect(brandSelect) {
                const source = brandSelect.getAttribute('data-model-source');
                const targetSelector = brandSelect.getAttribute('data-model-target');
                const target = document.querySelector(targetSelector);

                if (!source || !targetSelector || !target) {
                    return;
                }

                const selectedBrand = String(brandSelect.value || '');
                const models = (modelMaps[source] && modelMaps[source][selectedBrand]) ? modelMaps[source][selectedBrand] : [];
                const currentValue = target.getAttribute('data-selected') || target.value || '';
                const placeholder = target.getAttribute('data-placeholder') || 'Model';

                target.innerHTML = '';

                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.textContent = placeholder;
                target.appendChild(placeholderOption);

                models.forEach(function (modelName) {
                    const option = document.createElement('option');
                    option.value = modelName;
                    option.textContent = modelName;
                    if (currentValue && currentValue === modelName) {
                        option.selected = true;
                    }
                    target.appendChild(option);
                });

                target.disabled = models.length === 0;
                if (models.length === 0) {
                    target.value = '';
                }
            }

            document.querySelectorAll('[data-model-source]').forEach(function (brandSelect) {
                brandSelect.addEventListener('change', function () {
                    const target = document.querySelector(brandSelect.getAttribute('data-model-target'));
                    if (target) {
                        target.setAttribute('data-selected', '');
                    }
                    populateModelSelect(brandSelect);
                });

                populateModelSelect(brandSelect);
            });
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
