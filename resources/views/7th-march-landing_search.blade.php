<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body>
    <div class="auth-links">
        @auth('web')
            <a href="{{ route('user.select-car-purpose') }}" class="place-ad">PLACE AD</a>
        @else
            <a href="{{ route('login') }}" class="place-ad">PLACE AD</a>
        @endauth
        <span class="separatorss"></span>
        @auth('web')
            <a href="{{ route('user.dashboard') }}" class="sign-in">{{ auth('web')->user()->name }}</a>
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
                            <select class="form-select" name="brand_id">
                                <option value="" selected>All Makes</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>

                            <input type="text" class="form-control" name="model" placeholder="Model">
                        </div>

                        <div class="year-row">
                            <input type="number" class="form-control" name="min_year" placeholder="Min Year">
                            <input type="number" class="form-control" name="max_year" placeholder="Max Year">
                        </div>

                        <div class="price-row">
                            <input type="number" step="0.01" class="form-control" name="min_price" placeholder="Min Price">
                            <input type="number" step="0.01" class="form-control" name="max_price" placeholder="Max Price">
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
                            <select class="form-select part" name="brand_id" style="background: rgb(214 210 210);">
                                <option value="" selected>All Makes</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>

                            <input type="text" class="form-control part" style="background: rgb(214 210 210);" name="model" placeholder="Model">
                        </div>

                        <div class="year-row part">
                            <input type="number" class="form-control" style="background: rgb(214 210 210);" name="min_year" placeholder="Min Year">
                            <input type="number" class="form-control" style="background: rgb(214 210 210);" name="max_year" placeholder="Max Year">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
