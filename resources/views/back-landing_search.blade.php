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
            background: linear-gradient(135deg, #e8e8e8 0%, #d5d5d5 50%, #c8d5d9 100%);
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
            left: 0px;
        }

        .search-section {
            display: flex;
            gap: 40px;
            justify-content: center;
            margin-top: 220px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 0 0 450px;
            max-width: 450px;
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
            background: rgb(218 218 218);
            border: 2px solid #010101ff;
            border-radius: 20px;
            padding: 25px;
            backdrop-filter: blur(10px);
            padding-bottom:10px;
        }

        .form-control, .form-select {
            height: 30px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background: rgb(202 202 202);
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

        .buy-sell-text {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            display: inline-block;
            margin-right: 0px;
        }

        .separator {
            display: inline-block;
            width: 4px;
            height: 35px;
            background: #17a2b8;
            vertical-align: middle;
            margin: 0 6px;
        }
        .separators {
            display: inline-block;
            width: 4px;
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
            font-size: 20px;
            color: #999;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-links {
            position: fixed;
            bottom: 20px;
            left: 50px;
            font-size: 16px;
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
        }

        .auth-links a {
            text-decoration: none;
            font-size: 14px;
            font-weight: 800;
            margin-left: 0px;
        }

        .place-ad {
            color: #28a745;
        }

        .sign-in {
            color: #dc3545;
        }

        @media (max-width: 991.98px) {
            .main-container {
                padding: 16px;
            }

            .logo-section {
                position: static;
                margin-top: 16px;
                text-align: center;
            }

            .logo-section img {
                width: auto !important;
                max-width: 180px;
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

            .auth-links {
                position: static;
                margin: 12px 0 0;
                text-align: center;
            }

            .footer-links {
                position: static;
                margin: 16px 0 20px;
                text-align: center;
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
            .price-row {
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
        <a href="{{ route('user.select-car-purpose') }}" class="place-ad">PLACE AD</a>
        <span class="separatorss"></span>
        @auth('web')
            <a href="{{ route('user.dashboard') }}" class="sign-in">DASHBOARD</a>
        @else
            <a href="{{ route('login') }}" class="sign-in">SIGN IN</a>
        @endauth
    </div>

    <div class="main-container">
        <div class="logo-section">
            <a href="{{ route('home') }}" style="text-decoration:none;">
                @if($setting && $setting->logo)
                    <img src="{{ getImageOrPlaceholder($setting->logo, '80x46') }}" alt="logo" style="width: 30%;">
                @else
                    
                @endif
            </a>
        </div>

        <div class="search-section">
            <div class="search-box">
                <form method="GET" action="{{ route('listings') }}">
                    <button class="search-btn search-car-btn" type="submit">SEARCH CAR</button>
                    <div class="search-form">
                        <select class="form-select" name="brand_id">
                            <option value="" selected>All Makes Model</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>

                        <input type="text" class="form-control" name="model" placeholder="Model">

                        <div class="year-row">
                            <input type="number" class="form-control" name="min_year" placeholder="Min Year">
                            <input type="number" class="form-control" name="max_year" placeholder="Max Year">
                        </div>

                        </div>
                </form>
            </div>

            <div class="search-box">
                <form method="GET" action="{{ route('car-parts') }}">
                    <button class="search-btn search-part-btn" type="submit">SEARCH PART</button>
                    <div class="search-form">
                        <select class="form-select part" name="brand_id" style="background: rgb(186 224 239);">
                            <option value="" selected>All Makes Model</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>

                        <div class="price-row part">
                            <input type="number" step="0.01" class="form-control" style="background: rgb(186 224 239);" name="min_price" placeholder="Min Price">
                            <input type="number" step="0.01" class="form-control" style="background: rgb(186 224 239);" name="max_price" placeholder="Max Price">
                        </div>

                        <input type="text" class="form-control part" style="background: rgb(186 224 239);" name="search" placeholder="Search By Key Word">
                    </div>
                </form>
            </div>
        </div>

        <div class="bottom-section">
            <span class="buy-sell-text">BUY & SELL</span>
            <span class="separator"></span>
            <span class="subtitle-bottom">New & Used Car and Car Part</span>
        </div>
    </div>

    <div class="footer-links">
        <a href="{{ route('contact-us') }}">contact</a>
        <span class="separators"></span>
        <a href="{{ route('privacy-policy') }}">privacy policy</a>
        <span class="separators"></span>
        <a href="{{ route('terms-conditions') }}">terms and condition</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
