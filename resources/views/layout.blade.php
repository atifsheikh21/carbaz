<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ Session::get('lang_dir') == 'right_to_left' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ asset($setting ? $setting->favicon : 'frontend/assets/img/default-favicon.ico') }}">

    <!-- Google Fonts for Arabic -->
    @if(Session::get('lang_dir') == 'right_to_left')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700&family=Tajawal:wght@200;300;400;500;700;800&display=swap" rel="stylesheet">
    @endif

    @yield('title')

    <!-- fontawesome csn link  -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/fontawesome/css/all.css') }}">
    <!--bootstrap.min.css  -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <!-- venobox.min.css  -->
    <link rel="stylesheet" href="{{ asset('frontend/css/venobox.min.css') }}">
    <!-- slick.css  -->
    <link rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
    <!-- aos.css  -->
    <link rel="stylesheet" href="{{ asset('frontend/css/aos.css') }}">
    <!-- style.css  -->
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <!-- responsive.css -->
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">

    <link rel="stylesheet" href="{{ asset('global/makking-font.css') }}">

    <link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/custom.css') }}">

    <!-- RTL CSS -->
   @if(Session::get('lang_dir') == 'right_to_left')
        <link rel="stylesheet" href="{{ asset('frontend/css/rtl.css') }}">
    @endif


    @stack('style_section')

    <style>
        .mobile-header__container .logo img,
        .menu-bg .logo img,
        .m-nav .logo img,
        .footer-logo img{
            max-height : 65px;
            max-width: 170px;
        }

        .listing-list-card{
            position: relative;
        }

        .listing-list-content{
            position: relative;
            margin-left: 15px;
        }

        .listing-list-top-actions{
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
        }

        .listing-call-btn{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-decoration: none;
            color: #0CA640;
            font-size: 14px;
            line-height: 1;
        }

        .listing-call-btn:hover{
            color: #0CA640;
            background: rgba(12, 166, 64, 0.06);
        }

        .listing-list-price{
            position: relative;
        }

        .listing-list-price-bottom-right{
            position: absolute;
            right: 40px;
            bottom: 25px;
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
            width: 100%;
            padding: 0 16px 12px 16px;
        }

        .listing-list-date{
            margin-top: 10px;
        }

        @media (max-width: 575.98px){
            .listing-list-top-actions{
                position: static;
                margin-bottom: 8px;
                text-align: right;
            }

            .listing-list-price-bottom-right{
                position: static;
                padding: 0;
                margin-top: 10px;
                justify-content: flex-start;
            }
        }

    </style>


    @if ($google_analytic && $google_analytic->status == 1)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $google_analytic ? $google_analytic->analytic_id : '' }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $google_analytic ? $google_analytic->analytic_id : '' }}');
        </script>
    @endif

    @if ($facebook_pixel && $facebook_pixel->status == 1)
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $facebook_pixel ? $facebook_pixel->app_id : '' }}');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ $facebook_pixel ? $facebook_pixel->app_id : '' }}&ev=PageView&noscript=1"
    /></noscript>
    @endif

</head>

<body>

    <!-- header part start  -->
    @if (Route::is('home'))
    <header class="header {{ Session::get('selected_theme') != 'theme_three' ? 'header-two' : ''  }}  {{ Session::get('selected_theme') == 'theme_two' ? 'header-three' : ''  }}">
    @else
    <header class="header header-two inner-header">
    @endif
        <div class="container header-border ">
            <div class="row      align-items-center">
                <div class="col-lg-7 col-p-0">
                    <div class="header-left-item">
                        <div class="header-left-inner">
                            <div class="icon">
                                <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 12V7C2 4.79086 3.79086 3 6 3H18C20.2091 3 22 4.79086 22 7V17C22 19.2091 20.2091 21 18 21H8M6 8L9.7812 10.5208C11.1248 11.4165 12.8752 11.4165 14.2188 10.5208L18 8M2 15H8M2 18H8"
                                            stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                </span>
                            </div>

                            <div class="text">
                                <p><a href="mailto:{{ $setting ? $setting->email : 'info@carbaz.com' }}">{{ $setting ? $setting->email : 'info@carbaz.com' }}</a></p>
                            </div>
                        </div>
                        <div class="header-left-inner">
                            <div class="icon">
                                <span class="span-two">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15 16L14.8529 16.7354C15.1846 16.8018 15.5196 16.6379 15.6708 16.3354L15 16ZM8 9L7.66459 8.32918C7.36208 8.48043 7.19824 8.81544 7.26456 9.14709L8 9ZM8.35402 8.82299L8.68943 9.49381L8.68943 9.49381L8.35402 8.82299ZM9.31654 6.29136L10.0129 6.01281L9.31654 6.29136ZM8.50289 4.25722L7.80653 4.53576L8.50289 4.25722ZM19.7428 15.4971L19.4642 16.1935L19.7428 15.4971ZM17.7086 14.6835L17.9872 13.9871H17.9872L17.7086 14.6835ZM15.177 15.646L15.8478 15.9814V15.9814L15.177 15.646ZM16.25 11C16.25 11.4142 16.5858 11.75 17 11.75C17.4142 11.75 17.75 11.4142 17.75 11H16.25ZM16.6955 9.46927L17.3884 9.18225L16.6955 9.46927ZM14.5307 7.30448L14.8177 6.61157L14.5307 7.30448ZM13 6.25C12.5858 6.25 12.25 6.58579 12.25 7C12.25 7.41421 12.5858 7.75 13 7.75V6.25ZM20.25 11C20.25 11.4142 20.5858 11.75 21 11.75C21.4142 11.75 21.75 11.4142 21.75 11H20.25ZM20.391 7.93853L21.0839 7.65152L20.391 7.93853ZM16.0615 3.60896L16.3485 2.91605V2.91605L16.0615 3.60896ZM13 2.25C12.5858 2.25 12.25 2.58579 12.25 3C12.25 3.41421 12.5858 3.75 13 3.75V2.25ZM20.25 17.3541V19H21.75V17.3541H20.25ZM5 3.75H6.64593V2.25H5V3.75ZM15 16C15.1471 15.2646 15.1473 15.2646 15.1475 15.2646C15.1476 15.2647 15.1477 15.2647 15.1479 15.2647C15.1481 15.2648 15.1483 15.2648 15.1484 15.2648C15.1487 15.2649 15.1488 15.2649 15.1488 15.2649C15.1488 15.2649 15.1482 15.2648 15.147 15.2645C15.1447 15.264 15.14 15.2631 15.1331 15.2615C15.1193 15.2585 15.0967 15.2533 15.0659 15.2459C15.0044 15.2309 14.9104 15.2066 14.7898 15.1711C14.5482 15.1 14.2016 14.9847 13.7954 14.8106C12.9796 14.461 11.9439 13.8833 11.0303 12.9697L9.96967 14.0303C11.0561 15.1167 12.2704 15.789 13.2046 16.1894C13.6734 16.3903 14.0768 16.525 14.3665 16.6101C14.5115 16.6528 14.6285 16.6832 14.7114 16.7034C14.7529 16.7135 14.7859 16.721 14.8097 16.7263C14.8217 16.7289 14.8313 16.7309 14.8385 16.7325C14.8421 16.7332 14.8451 16.7339 14.8475 16.7343C14.8487 16.7346 14.8498 16.7348 14.8507 16.735C14.8511 16.7351 14.8515 16.7352 14.8519 16.7352C14.8521 16.7353 14.8523 16.7353 14.8524 16.7353C14.8527 16.7354 14.8529 16.7354 15 16ZM11.0303 12.9697C10.1167 12.0561 9.53901 11.0204 9.18936 10.2046C9.01527 9.79836 8.89996 9.45184 8.8289 9.21025C8.79342 9.08962 8.76912 8.99565 8.75414 8.93406C8.74666 8.90329 8.74151 8.88065 8.73847 8.86687C8.73695 8.85999 8.73595 8.85532 8.73546 8.85296C8.73521 8.85178 8.73509 8.85118 8.73508 8.85117C8.73508 8.85116 8.73511 8.8513 8.73517 8.85159C8.7352 8.85174 8.73524 8.85192 8.73528 8.85214C8.7353 8.85225 8.73534 8.85244 8.73535 8.8525C8.73539 8.8527 8.73544 8.85291 8 9C7.26456 9.14709 7.26461 9.14732 7.26466 9.14756C7.26468 9.14765 7.26473 9.1479 7.26477 9.14809C7.26484 9.14846 7.26492 9.14887 7.26501 9.14932C7.2652 9.15022 7.26541 9.15127 7.26566 9.15247C7.26615 9.15488 7.26677 9.15789 7.26753 9.1615C7.26905 9.16873 7.27111 9.17834 7.27374 9.19026C7.279 9.21408 7.28655 9.2471 7.29664 9.28859C7.31682 9.37154 7.34721 9.48851 7.38985 9.6335C7.47504 9.92316 7.60973 10.3266 7.81064 10.7954C8.21099 11.7296 8.88325 12.9439 9.96967 14.0303L11.0303 12.9697ZM8.33541 9.67082L8.68943 9.49381L8.01861 8.15217L7.66459 8.32918L8.33541 9.67082ZM10.0129 6.01281L9.19925 3.97868L7.80653 4.53576L8.62018 6.5699L10.0129 6.01281ZM20.0213 14.8008L17.9872 13.9871L17.4301 15.3798L19.4642 16.1935L20.0213 14.8008ZM14.5062 15.3106L14.3292 15.6646L15.6708 16.3354L15.8478 15.9814L14.5062 15.3106ZM17.9872 13.9871C16.6592 13.4559 15.1458 14.0313 14.5062 15.3106L15.8478 15.9814C16.1386 15.3999 16.8265 15.1384 17.4301 15.3798L17.9872 13.9871ZM8.68943 9.49381C9.96868 8.85419 10.5441 7.34076 10.0129 6.01281L8.62018 6.5699C8.86163 7.17351 8.60008 7.86143 8.01861 8.15217L8.68943 9.49381ZM6.64593 3.75C7.15706 3.75 7.6167 4.06119 7.80653 4.53576L9.19925 3.97868C8.78162 2.93462 7.77042 2.25 6.64593 2.25V3.75ZM21.75 17.3541C21.75 16.2296 21.0654 15.2184 20.0213 14.8008L19.4642 16.1935C19.9388 16.3833 20.25 16.8429 20.25 17.3541H21.75ZM19 20.25C10.5777 20.25 3.75 13.4223 3.75 5H2.25C2.25 14.2508 9.74923 21.75 19 21.75V20.25ZM19 21.75C20.5188 21.75 21.75 20.5188 21.75 19H20.25C20.25 19.6904 19.6904 20.25 19 20.25V21.75ZM3.75 5C3.75 4.30964 4.30964 3.75 5 3.75V2.25C3.48122 2.25 2.25 3.48122 2.25 5H3.75ZM17.75 11C17.75 10.3762 17.6271 9.75855 17.3884 9.18225L16.0026 9.75628C16.1659 10.1506 16.25 10.5732 16.25 11H17.75ZM17.3884 9.18225C17.1497 8.60596 16.7998 8.08232 16.3588 7.64124L15.2981 8.7019C15.5999 9.00369 15.8393 9.36197 16.0026 9.75628L17.3884 9.18225ZM16.3588 7.64124C15.9177 7.20016 15.394 6.85028 14.8177 6.61157L14.2437 7.99739C14.638 8.16072 14.9963 8.40011 15.2981 8.7019L16.3588 7.64124ZM14.8177 6.61157C14.2415 6.37286 13.6238 6.25 13 6.25V7.75C13.4268 7.75 13.8494 7.83406 14.2437 7.99739L14.8177 6.61157ZM21.75 11C21.75 9.85093 21.5237 8.71312 21.0839 7.65152L19.6981 8.22554C20.0625 9.10516 20.25 10.0479 20.25 11H21.75ZM21.0839 7.65152C20.6442 6.58992 19.9997 5.62533 19.1872 4.81282L18.1265 5.87348C18.7997 6.5467 19.3338 7.34593 19.6981 8.22554L21.0839 7.65152ZM19.1872 4.81282C18.3747 4.0003 17.4101 3.35578 16.3485 2.91605L15.7745 4.30187C16.6541 4.66622 17.4533 5.20025 18.1265 5.87348L19.1872 4.81282ZM16.3485 2.91605C15.2869 2.47633 14.1491 2.25 13 2.25V3.75C13.9521 3.75 14.8948 3.93753 15.7745 4.30187L16.3485 2.91605Z" />
                                    </svg>
                                </span>
                            </div>

                            <div class="text">
                                <p><a href="mailto:{{ $setting ? $setting->email : 'info@carbaz.com' }}">{{ $setting ? $setting->email : 'info@carbaz.com' }}</a></p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-5 col-p-0">
                    <div class="header-right-item">
                        <div class="header-right-item-btn-main">
                            <div class="header-right-item-btn">
                                <div class="dropdown two">
                                    <a class=" btn-secondary dropdown-toggle" href="#" role="button"
                                        id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="m-nav">
                                            {{ Session::get('currency_icon') }}
                                        </div>

                                        <div class="mr-2">
                                            {{ Session::get('currency_name') }}
                                        </div>
                                        <span class="btn-arrow">
                                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.0002 0.633816C11.947 0.446997 11.8572 0.28353 11.6808 0.158011C11.3813 -0.0492418 10.9487 -0.0550799 10.6493 0.155092C10.5927 0.195958 10.5361 0.239744 10.4829 0.286449C9.02543 1.56499 7.56465 2.84645 6.10719 4.125C6.07391 4.15419 6.04729 4.18922 5.96743 4.24176C5.94414 4.20673 5.93083 4.16294 5.89755 4.13375C4.42679 2.84062 2.95269 1.5504 1.48192 0.257257C1.22237 0.0295716 0.922896 -0.0579998 0.563523 0.0412478C0.0411014 0.1872 -0.17186 0.776848 0.157565 1.16216C0.194168 1.20595 0.237426 1.24681 0.280683 1.28768C1.97772 2.7764 3.67144 4.26511 5.36848 5.75091C5.67794 6.02238 6.07059 6.07492 6.42663 5.89394C6.51315 5.85015 6.58968 5.78594 6.65956 5.72464C8.30669 4.27971 9.95049 2.83478 11.6009 1.39277C11.784 1.23222 11.947 1.06875 12.0002 0.838149C12.0002 0.771011 12.0002 0.703873 12.0002 0.633816Z" />
                                            </svg>
                                        </span>
                                    </a>

                                    <ul class="dropdown-menu m-nav" aria-labelledby="dropdownMenuLink">
                                        @foreach ($currency_list as $currency_dropdown_item)
                                            <li><a class="dropdown-item" href="{{ route('currency-switcher', ['currency_code' => $currency_dropdown_item->currency_code]) }}">{{ $currency_dropdown_item->currency_name }}</a></li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            <div class="header-right-item-btn">
                                <div class="dropdown">
                                    <a class=" btn-secondary dropdown-toggle" href="#" role="button"
                                        id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span>
                                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <ellipse cx="11.0001" cy="11" rx="4" ry="10" stroke-width="1.5" />
                                                <path
                                                    d="M20.9962 10.7205C19.1938 12.2016 15.3949 13.2222 11 13.2222C6.60511 13.2222 2.80619 12.2016 1.00383 10.7205M20.9962 10.7205C20.8482 5.32691 16.4294 1 11 1C5.57061 1 1.15183 5.32691 1.00383 10.7205M20.9962 10.7205C20.9987 10.8134 21 10.9065 21 11C21 16.5228 16.5228 21 11 21C5.47715 21 1 16.5228 1 11C1 10.9065 1.00128 10.8134 1.00383 10.7205"
                                                    stroke-width="1.5" />
                                            </svg>
                                        </span>
                                        {{ Session::get('front_lang_name') }}

                                        <span class="btn-arrow">
                                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.0002 0.633816C11.947 0.446997 11.8572 0.28353 11.6808 0.158011C11.3813 -0.0492418 10.9487 -0.0550799 10.6493 0.155092C10.5927 0.195958 10.5361 0.239744 10.4829 0.286449C9.02543 1.56499 7.56465 2.84645 6.10719 4.125C6.07391 4.15419 6.04729 4.18922 5.96743 4.24176C5.94414 4.20673 5.93083 4.16294 5.89755 4.13375C4.42679 2.84062 2.95269 1.5504 1.48192 0.257257C1.22237 0.0295716 0.922896 -0.0579998 0.563523 0.0412478C0.0411014 0.1872 -0.17186 0.776848 0.157565 1.16216C0.194168 1.20595 0.237426 1.24681 0.280683 1.28768C1.97772 2.7764 3.67144 4.26511 5.36848 5.75091C5.67794 6.02238 6.07059 6.07492 6.42663 5.89394C6.51315 5.85015 6.58968 5.78594 6.65956 5.72464C8.30669 4.27971 9.95049 2.83478 11.6009 1.39277C11.784 1.23222 11.947 1.06875 12.0002 0.838149C12.0002 0.771011 12.0002 0.703873 12.0002 0.633816Z" />
                                            </svg>
                                        </span>
                                    </a>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        @foreach ($language_list as $language_dropdown_item)
                                            <li><a class="dropdown-item" href="{{ route('language-switcher', ['lang_code' => $language_dropdown_item->lang_code]) }}">{{ $language_dropdown_item->lang_name }}</a></li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                        </div>


                        <div class="header-right-login-btn">
                            @auth('web')
                                <a href="{{ route('user.dashboard') }}" class="@if(Route::is('home')) {{ Session::get('selected_theme') == 'theme_three' ? 'thm-btn' : 'thm-btn-two' }} @else thm-btn-two  @endif">
                                    <span>
                                        <svg width="14" height="18" viewBox="0 0 14 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <ellipse cx="6.99676" cy="13.8885" rx="6.22222" ry="3.11111" stroke-width="1.4"
                                                stroke-linejoin="round" />
                                            <circle cx="6.99611" cy="4.55556" r="3.55556" stroke-width="1.4"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    {{ __('translate.Dashboard') }}
                                </a>
                            @else
                            <a href="{{ route('login') }}" class="@if(Route::is('home')) {{ Session::get('selected_theme') == 'theme_three' ? 'thm-btn' : 'thm-btn-two' }} @else thm-btn-two  @endif">
                                <span>
                                    <svg width="14" height="18" viewBox="0 0 14 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <ellipse cx="6.99676" cy="13.8885" rx="6.22222" ry="3.11111" stroke-width="1.4"
                                            stroke-linejoin="round" />
                                        <circle cx="6.99611" cy="4.55556" r="3.55556" stroke-width="1.4"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span>
                                {{ __('translate.Login') }}
                            </a>
                            @endauth

                        </div>

                    </div>
                </div>
            </div>
        </div>


        <nav class="menu-bg ">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-p-0">
                        <div class="nav-main">
                            <div class="nav-left">
                                <div class="logo">
                                    <a href="{{ route('home') }}">
                                        @if (Route::is('home'))
                                            @if (Session::get('selected_theme') == 'theme_two')
                                                <img src="{{ getImageOrPlaceholder($setting ? $setting->home2_logo2 : null, '170x46') }}" alt="logo">
                                            @elseif (Session::get('selected_theme') == 'theme_three')
                                            <img src="{{ getImageOrPlaceholder($setting ? $setting->home3_logo2 : null, '170x46') }}" alt="logo">
                                            @else
                                                <img src="{{ getImageOrPlaceholder($setting ? $setting->logo : null, '170x46') }}" alt="logo">
                                            @endif
                                        @else
                                            <img src="{{ getImageOrPlaceholder($setting ? $setting->inner_logo : null, '170x46') }}" alt="logo">
                                        @endif
                                    </a>
                                </div>


                                <div class="menu">
                                    @if(App\Helpers\MenuHelper::hasMenu('header'))
                                        {!! App\Helpers\MenuHelper::renderMenu('header', [
                                            'ul_class' => '',
                                            'li_class' => '',
                                            'a_class' => '',
                                            'submenu_class' => 'sub-menu',
                                            'active_class' => 'active'
                                        ]) !!}
                                    @else
                                        <ul>
                                            @if ($setting && $setting->selected_theme == 'all_theme')
                                                <li><a href="{{ route('home') }}">{{ __('translate.Home') }} <i class="fa-solid fa-angle-down"></i> </a>

                                                    <ul class="sub-menu">
                                                        <li><a href="{{ route('home', ['theme' => 'one']) }}">{{ __('translate.Home-01') }} </a> </li>
                                                        <li><a href="{{ route('home', ['theme' => 'two']) }}">{{ __('translate.Home-02') }} </a> </li>
                                                        <li><a href="{{ route('home', ['theme' => 'three']) }}">{{ __('translate.Home-03') }} </a> </li>
                                                    </ul>
                                                </li>
                                            @else
                                                <li><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                                            @endif

                                            <li><a href="{{ route('about-us') }}">{{ __('translate.About Us') }}</a></li>

                                            <li><a href="{{ route('listings') }}">{{ __('translate.Listings') }}</a></li>

                                            <li><a href="{{ route('car-part-requests.index') }}">{{ __('translate.Forum') }}</a></li>

                                            <li><a href="{{ route('dealers') }}">{{ __('translate.Dealers') }}</a></li>

                                            <li><a href="{{ route('blogs') }}">{{ __('translate.Blogs') }}</a></li>



                                            <li><a href="javascript:;">{{ __('translate.Pages') }} <i class="fa-solid fa-angle-down"></i> </a>
                                                <ul class="sub-menu">

                                                    @if ($setting && $setting->add_listing == 'enable')
                                                         <li><a href="{{ route('pricing-plan') }}">{{ __('translate.Pricing Plan') }}</a></li>
                                                    @endif


                                                    <li><a href="{{ route('terms-conditions') }}">{{ __('translate.Terms and Conditions') }}</a></li>

                                                    <li><a href="{{ route('privacy-policy') }}">{{ __('translate.Privacy Policy') }}</a></li>

                                                    @foreach ($custom_pages as $custom_page)
                                                        <li><a href="{{ route('custom-page', $custom_page->slug) }}">{{ $custom_page->page_name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>

                                            <li><a href="{{ route('faq') }}">{{ __('translate.FAQ') }}</a></li>
                                            <li><a href="{{ route('contact-us') }}">{{ __('translate.Contact') }}</a></li>

                                        </ul>
                                    @endif
                                </div>
                            </div>
                            <div class="nav-btn">

                                <a href="{{ route('compare') }}" class="user">
                                    <span class="user-list">{{ count(Session::get('compare_array', [])) }}</span>
                                    <span>
                                        <svg width="20" height="22" viewBox="0 0 20 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                        d="M0.997192 11V9.25C0.997192 6.48858 3.23577 4.25 5.99719 4.25H18.9972L15.6222 0.875"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                        d="M18.9972 11V12.75C18.9972 15.5114 16.7586 17.75 13.9972 17.75H0.997192L4.37219 21.125"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                    </span>
                                </a>
                                @if ($setting && $setting->add_listing == 'enable')

                                    @if (Route::is('home'))
                                        @if (Session::get('selected_theme') == 'theme_one')
                                            <a href="{{ route('user.select-car-purpose') }}" class="thm-btn-two">{{ __('translate.Add Car') }}</a>
                                        @elseif (Session::get('selected_theme') == 'theme_two')
                                            <a href="{{ route('user.select-car-purpose') }}" class="thm-btn-thr">{{ __('translate.Add Car') }}</a>
                                        @elseif (Session::get('selected_theme') == 'theme_three')
                                            <a href="{{ route('user.select-car-purpose') }}" class="thm-btn">{{ __('translate.Add Car') }}</a>
                                        @endif

                                    @else
                                    <a href="{{ route('user.select-car-purpose') }}" class="thm-btn-two">{{ __('translate.Add Car') }}</a>
                                    @endif
                                @endif

                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </nav>

    </header>

    <!-- mobile navigation start -->
    <header class="mobile-header @if (Route::is('home')) {{ Session::get('selected_theme') == 'theme_two' ? 'two' : '' }} {{ Session::get('selected_theme') == 'theme_three' ? 'three' : '' }}  @endif">
        <div class="container-full">
            <div class="mobile-header__container">
                <div class="p-left">
                    <div class="logo">
                        <a href="{{ route('home') }}">
                            @if (Route::is('home'))
                                @if (Session::get('selected_theme') == 'theme_two')
                                    <img src="{{ getImageOrPlaceholder($setting ? $setting->logo : null, '170x46') }}" alt="logo">
                                @elseif (Session::get('selected_theme') == 'theme_three')
                                <img src="{{ getImageOrPlaceholder($setting ? $setting->logo : null, '170x46') }}" alt="logo">
                                @else
                                    <img src="{{ getImageOrPlaceholder($setting ? $setting->logo : null, '170x46') }}" alt="logo">
                                @endif
                            @else
                                <img src="{{ getImageOrPlaceholder($setting ? $setting->logo : null, '170x46') }}" alt="logo">
                            @endif
                        </a>
                    </div>
                </div>
                <div class="p-right">
                    <button id="nav-opn-btn">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
    <!-- offcanvas -->

    <aside id="offcanvas-nav" >


        <nav class="m-nav @if (Route::is('home'))

        {{ Session::get('selected_theme') == 'theme_two' ? 'm-nav-two' : '' }} {{ Session::get('selected_theme') == 'theme_three' ? 'm-nav-three' : '' }} @endif ">
            <button id="nav-cls-btn"><i class="fa-solid fa-xmark"></i></button>

            <div class="logo">
                <a href="{{ route('home') }}  ">

                    @if (Route::is('home'))
                        @if (Session::get('selected_theme') == 'theme_two')
                            <img src="{{ getImageOrPlaceholder($setting ? $setting->home2_logo2 : null, '170x46') }}" alt="logo">
                        @elseif (Session::get('selected_theme') == 'theme_three')
                        <img src="{{ getImageOrPlaceholder($setting ? $setting->home3_logo2 : null, '170x46') }}" alt="logo">
                        @else
                            <img src="{{ getImageOrPlaceholder($setting ? $setting->inner_logo : null, '170x46') }}" alt="logo">
                        @endif
                    @else
                        <img src="{{ getImageOrPlaceholder($setting ? $setting->inner_logo : null, '170x46') }}" alt="logo">
                    @endif
                </a>
            </div>


            <div class="header-right-item">
                <div class="header-right-item-btn-main">
                    <div class="header-right-item-btn">
                        <div class="dropdown two">
                            <a class=" btn-secondary dropdown-toggle" href="#" role="button"
                                id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">

                                <span class="usd-icon">
                                    <svg width="10" height="20" viewBox="0 0 10 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9 6.5C9 4.567 7.32107 3 5.25 3C3.17893 3 1.5 4.567 1.5 6.5C1.5 8.433 3.17893 10 5.25 10"
                                            stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M1.5 13.5C1.5 15.433 3.17893 17 5.25 17C7.32107 17 9 15.433 9 13.5C9 11.567 7.32107 10 5.25 10"
                                            stroke-width="1.5" stroke-linecap="round" />
                                        <path d="M5.25 1V19" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>


                                </span>

                                {{ Session::get('currency_name') }}
                                <span class="btn-arrow">
                                    <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12.0002 0.633816C11.947 0.446997 11.8572 0.28353 11.6808 0.158011C11.3813 -0.0492418 10.9487 -0.0550799 10.6493 0.155092C10.5927 0.195958 10.5361 0.239744 10.4829 0.286449C9.02543 1.56499 7.56465 2.84645 6.10719 4.125C6.07391 4.15419 6.04729 4.18922 5.96743 4.24176C5.94414 4.20673 5.93083 4.16294 5.89755 4.13375C4.42679 2.84062 2.95269 1.5504 1.48192 0.257257C1.22237 0.0295716 0.922896 -0.0579998 0.563523 0.0412478C0.0411014 0.1872 -0.17186 0.776848 0.157565 1.16216C0.194168 1.20595 0.237426 1.24681 0.280683 1.28768C1.97772 2.7764 3.67144 4.26511 5.36848 5.75091C5.67794 6.02238 6.07059 6.07492 6.42663 5.89394C6.51315 5.85015 6.58968 5.78594 6.65956 5.72464C8.30669 4.27971 9.95049 2.83478 11.6009 1.39277C11.784 1.23222 11.947 1.06875 12.0002 0.838149C12.0002 0.771011 12.0002 0.703873 12.0002 0.633816Z" />
                                    </svg>
                                </span>
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                @foreach ($currency_list as $currency_dropdown_item)
                                    <li><a class="dropdown-item" href="{{ route('currency-switcher', ['currency_code' => $currency_dropdown_item->currency_code]) }}">{{ $currency_dropdown_item->currency_name }}</a></li>
                                @endforeach
                            </ul>

                        </div>
                    </div>
                    <div class="header-right-item-btn">
                        <div class="dropdown">

                            <a class=" btn-secondary dropdown-toggle" href="#" role="button"
                                id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <ellipse cx="11.0001" cy="11" rx="4" ry="10" stroke-width="1.5" />
                                        <path
                                            d="M20.9962 10.7205C19.1938 12.2016 15.3949 13.2222 11 13.2222C6.60511 13.2222 2.80619 12.2016 1.00383 10.7205M20.9962 10.7205C20.8482 5.32691 16.4294 1 11 1C5.57061 1 1.15183 5.32691 1.00383 10.7205M20.9962 10.7205C20.9987 10.8134 21 10.9065 21 11C21 16.5228 16.5228 21 11 21C5.47715 21 1 16.5228 1 11C1 10.9065 1.00128 10.8134 1.00383 10.7205"
                                            stroke-width="1.5" />
                                    </svg>
                                </span>
                                {{ Session::get('front_lang_name') }}

                                <span class="btn-arrow">
                                    <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12.0002 0.633816C11.947 0.446997 11.8572 0.28353 11.6808 0.158011C11.3813 -0.0492418 10.9487 -0.0550799 10.6493 0.155092C10.5927 0.195958 10.5361 0.239744 10.4829 0.286449C9.02543 1.56499 7.56465 2.84645 6.10719 4.125C6.07391 4.15419 6.04729 4.18922 5.96743 4.24176C5.94414 4.20673 5.93083 4.16294 5.89755 4.13375C4.42679 2.84062 2.95269 1.5504 1.48192 0.257257C1.22237 0.0295716 0.922896 -0.0579998 0.563523 0.0412478C0.0411014 0.1872 -0.17186 0.776848 0.157565 1.16216C0.194168 1.20595 0.237426 1.24681 0.280683 1.28768C1.97772 2.7764 3.67144 4.26511 5.36848 5.75091C5.67794 6.02238 6.07059 6.07492 6.42663 5.89394C6.51315 5.85015 6.58968 5.78594 6.65956 5.72464C8.30669 4.27971 9.95049 2.83478 11.6009 1.39277C11.784 1.23222 11.947 1.06875 12.0002 0.838149C12.0002 0.771011 12.0002 0.703873 12.0002 0.633816Z" />
                                    </svg>
                                </span>
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                @foreach ($language_list as $language_dropdown_item)
                                    <li><a class="dropdown-item" href="{{ route('language-switcher', ['lang_code' => $language_dropdown_item->lang_code]) }}">{{ $language_dropdown_item->lang_name }}</a></li>
                                @endforeach
                            </ul>

                        </div>
                    </div>
                </div>
            </div>


            <div class="header-right-login-btn">
                @auth('web')
                    <a href="{{ route('user.dashboard') }}" class="@if(Route::is('home')) {{ Session::get('selected_theme') == 'theme_three' ? 'thm-btn' : 'thm-btn-two' }} @else thm-btn-two  @endif">
                        <span>
                            <svg width="14" height="18" viewBox="0 0 14 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <ellipse cx="6.99676" cy="13.8885" rx="6.22222" ry="3.11111" stroke-width="1.4"
                                    stroke-linejoin="round" />
                                <circle cx="6.99611" cy="4.55556" r="3.55556" stroke-width="1.4"
                                    stroke-linejoin="round" />
                            </svg>
                        </span>
                        {{ __('translate.Dashboard') }}
                    </a>
                @else
                <a href="{{ route('login') }}" class="@if(Route::is('home')) {{ Session::get('selected_theme') == 'theme_three' ? 'th m-btn' : 'thm-btn-two' }} @else thm-btn-two  @endif">
                    <span>
                        <svg width="14" height="18" viewBox="0 0 14 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="6.99676" cy="13.8885" rx="6.22222" ry="3.11111" stroke-width="1.4"
                                stroke-linejoin="round" />
                            <circle cx="6.99611" cy="4.55556" r="3.55556" stroke-width="1.4"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    {{ __('translate.Login') }}
                </a>
                @endauth

            </div>

            @if(App\Helpers\MenuHelper::hasMenu('header'))
                {!! App\Helpers\MenuHelper::renderMobileMenu('header') !!}
            @else
                <ul class="nav-links">
                <li class="dropdown">
                <a href="javascript:;">{{ __('translate.Home') }}
                     <span>
                     <i class="fa-solid fa-angle-down"></i>
                    </span>
                 </a>
                    <ul class="d-menu">
                        <li><a href="{{ route('home', ['theme' => 'one']) }}">{{ __('translate.Home-01') }} </a> </li>
                        <li><a href="{{ route('home', ['theme' => 'two']) }}">{{ __('translate.Home-02') }} </a> </li>
                        <li><a href="{{ route('home', ['theme' => 'three']) }}">{{ __('translate.Home-03') }} </a> </li>
                    </ul>
                </li>

                <li><a href="{{ route('about-us') }}">{{ __('translate.About Us') }}</a></li>

                <li><a href="{{ route('listings') }}">{{ __('translate.Listings') }}</a></li>

                <li><a href="{{ route('car-part-requests.index') }}">{{ __('translate.Forum') }}</a></li>

                <li><a href="{{ route('dealers') }}">{{ __('translate.Dealers') }}</a></li>

                <li><a href="{{ route('blogs') }}">{{ __('translate.Blogs') }}</a></li>

                <li class="dropdown">
                    <a href="#">{{ __('translate.Pages') }}
                        <span>
                             <i class="fa-solid fa-angle-down"></i>
                        </span>
                     </a>

                    <ul class="d-menu">

                        @if ($setting && $setting->add_listing == 'enable')
                                                     <li><a href="{{ route('pricing-plan') }}">{{ __('translate.Pricing Plan') }}</a></li>
                                                @endif

                        <li><a href="{{ route('terms-conditions') }}">{{ __('translate.Terms and Conditions') }}</a></li>

                        <li><a href="{{ route('privacy-policy') }}">{{ __('translate.Privacy Policy') }}</a></li>

                        @foreach ($custom_pages as $custom_page)
                            <li><a href="{{ route('custom-page', $custom_page->slug) }}">{{ $custom_page->page_name }}</a></li>
                        @endforeach

                    </ul>

                </li>

                <li><a href="{{ route('faq') }}">{{ __('translate.FAQ') }}</a></li>
                <li><a href="{{ route('contact-us') }}">{{ __('translate.Contact') }}</a></li>
            </ul>

            @endif




        </nav>
    </aside>

    <!-- header part end -->



    @yield('body-content')



    @if ($tawk_chat && $tawk_chat->status == 1)
        <script type="text/javascript">
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){
                var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                s1.async=true;
                s1.src='{{ $tawk_chat ? $tawk_chat->chat_link : '' }}';
                s1.charset='UTF-8';
                s1.setAttribute('crossorigin','*');
                s0.parentNode.insertBefore(s1,s0);
            })();
        </script>
    @endif


    <!-- footer prart start  -->

    @if (Route::is('home'))
    <footer class="footer {{ Session::get('selected_theme') == 'theme_two' ? 'footer-three' : ''  }} {{ Session::get('selected_theme') == 'theme_one' ? 'footer-two' : ''  }}">
    @else
    <footer class="footer footer-two">
    @endif

        <div class="container">
            @if (!Route::is('home'))
            <div class="row  footer-bb    align-items-center ">
                <div class="col-lg-4 col-p-0" data-aos="fade-right" data-aos-delay="50">
                    <h2 class="newsletter-txt">
                        {{ __('translate.Join Our') }} <span>{{ __('translate.Newsletter') }}</span> &
                        {{ __('translate.Get updated.') }}
                    </h2>
                </div>
                <div class="col-lg-8 col-p-0" data-aos="fade-left" data-aos-delay="100">
                    <div class="newsletter-sarch-box-main-item">
                        <div class="newsletter-sarch-box-main">
                            <form action="{{ route('newsletter-request') }}" class="newsletter-sarch-box" method="POST">
                                @csrf
                                <div class="newsletter-sarch-box-item">
                                    <input type="email" class="form-control" id="newsletter_email"
                                placeholder="{{ __('translate.Email Address') }}" name="email">
                                </div>
                                <button type="submit" class="thm-btn-two">{{ __('translate.Subscribe') }}</button>
                            </form>
                            <label class="form-label">
                                {{ __('translate.We only send interesting and relevant emails.') }}</label>
                        </div>


                    </div>
                </div>
            </div>
            @endif


            <div class="row footer-mt-75px   ">
                <div class=" col-xl-4 col-lg-6 col-md-12 " data-aos="fade-right" data-aos-delay="100">
                    <div class="footer-logo">


                        <a href="{{ route('home') }}">
                            @if (Route::is('home'))
                                @if (Session::get('selected_theme') == 'theme_two')
                                    <img src="{{ getImageOrPlaceholder($setting ? $setting->home2_logo : null, '170x46') }}" alt="logo">
                                @elseif (Session::get('selected_theme') == 'theme_three')
                                <img src="{{ getImageOrPlaceholder($setting ? $setting->home3_logo : null, '170x46') }}" alt="logo">
                                @else
                                    <img src="{{ getImageOrPlaceholder($setting ? $setting->logo_2 : null, '170x46') }}" alt="logo">
                                @endif
                            @else
                                <img src="{{ getImageOrPlaceholder($setting ? $setting->logo_2 : null, '170x46') }}" alt="logo">
                            @endif
                        </a>
                    </div>
                    <div class="footer-text-p">
                        <p>{{ $setting ? $setting->about_us : 'Welcome to our car marketplace platform' }}</p>
                    </div>

                    <div class="footer-icon">
                        <div class="text">
                            <h5>{{ __('translate.Follow Us') }}:</h5>
                        </div>

                        <div class="footer-icon-item">
                            <div class="icon">
                                <a href="{{ $setting ? $setting->linkedin : '#' }}" target="_blank">
                                    <span>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_1632_27760)">
                                                <path
                                                    d="M3.24097 0.199951C2.36073 0.199951 1.56047 0.519967 0.960289 1.11997C0.320112 1.75995 0 2.55992 0 3.39992C0 4.2799 0.360108 5.07992 0.960289 5.67992C1.56047 6.27992 2.40072 6.63992 3.20098 6.59994C3.20098 6.59994 3.24097 6.59994 3.28102 6.59994C4.08127 6.59994 4.84149 6.27992 5.44167 5.67992C6.04185 5.07992 6.40196 4.2799 6.40196 3.39992C6.44195 2.55992 6.08184 1.7599 5.48166 1.1599C4.88148 0.51992 4.08123 0.199951 3.24097 0.199951ZM4.88148 5.11995C4.44134 5.55997 3.84115 5.83995 3.20098 5.79997C2.6008 5.79997 1.96062 5.55997 1.52047 5.11995C1.04033 4.67994 0.800256 4.03995 0.800256 3.39997C0.800256 2.75998 1.04033 2.15998 1.52047 1.67998C1.96062 1.23997 2.5608 0.999967 3.24097 0.999967C3.84115 0.999967 4.44134 1.23997 4.88148 1.67998C5.36163 2.15998 5.6017 2.75998 5.6017 3.39997C5.6017 4.03995 5.36163 4.67994 4.88148 5.11995Z" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M0.854336 1.01398C1.48415 0.384353 2.32323 0.0500488 3.24107 0.0500488C4.11972 0.0500488 4.95964 0.384957 5.58951 1.05562C6.213 1.67975 6.59313 2.51723 6.55205 3.40358C6.55108 4.32456 6.17343 5.16067 5.54781 5.7861C4.92088 6.41285 4.12304 6.75003 3.28111 6.75003H3.20495C2.35801 6.79109 1.47939 6.41097 0.854336 5.7861C0.227915 5.15987 -0.149902 4.3224 -0.149902 3.40002C-0.149902 2.51996 0.186205 1.68191 0.854336 1.01398ZM3.20108 6.45003H3.28018C4.03876 6.45003 4.76229 6.14719 5.33571 5.57394C5.90965 5.00017 6.25205 4.2376 6.25205 3.40002V3.39288C6.28974 2.60136 5.95074 1.84094 5.37571 1.26608L5.37229 1.26266C4.80199 0.654543 4.04215 0.350049 3.24107 0.350049C2.39841 0.350049 1.63698 0.655776 1.06644 1.22615C0.454214 1.83819 0.150098 2.60007 0.150098 3.40002C0.150098 4.2376 0.492497 5.00017 1.06644 5.57394C1.64081 6.14813 2.44119 6.48781 3.19359 6.45022L3.20108 6.45003ZM3.24107 1.15006C2.59733 1.15006 2.03636 1.37655 1.62662 1.78616C1.17467 2.23798 0.950354 2.7998 0.950354 3.40006C0.950354 4.00432 1.17715 4.60187 1.62191 5.00946L1.62672 5.01387C2.03646 5.42348 2.63732 5.65006 3.20108 5.65006H3.21044C3.79991 5.68688 4.35984 5.42953 4.77553 5.01397L4.78014 5.00936C5.22491 4.60176 5.4518 4.00432 5.4518 3.40006C5.4518 2.7998 5.22748 2.23798 4.77553 1.78616C4.36397 1.37473 3.80213 1.15006 3.24107 1.15006ZM1.41452 1.574C1.88508 1.10358 2.52447 0.850065 3.24107 0.850065C3.88037 0.850065 4.51889 1.1054 4.98763 1.574C5.49596 2.08218 5.7518 2.72036 5.7518 3.40006C5.7518 4.07473 5.49924 4.75607 4.98534 5.22842C4.52197 5.69054 3.88437 5.99149 3.19641 5.95006C2.56219 5.94883 1.88614 5.69623 1.41682 5.22843C0.902918 4.75608 0.650354 4.07473 0.650354 3.40006C0.650354 2.72036 0.906186 2.08218 1.41452 1.574Z" />
                                                <path
                                                    d="M4.80173 7.40015H1.56075C0.920578 7.40015 0.400391 7.92013 0.400391 8.60015V22.6002C0.400391 23.2401 0.960575 23.8002 1.60075 23.8002H4.80173C5.44191 23.8002 6.00209 23.2401 6.00209 22.6401V8.60015C6.00209 7.96011 5.44191 7.40015 4.80173 7.40015ZM5.20183 22.6401C5.20183 22.8402 5.00176 23.0001 4.80173 23.0001H1.60075C1.40068 23.0001 1.20065 22.8001 1.20065 22.6002V8.60015C1.20065 8.40013 1.36068 8.20016 1.56075 8.20016H4.80173C5.0018 8.20016 5.20183 8.40018 5.20183 8.60015V22.6401Z" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M0.25 8.6C0.25 7.84044 0.834084 7.25 1.56036 7.25H4.80134C5.52431 7.25 6.1517 7.87708 6.1517 8.6V22.64C6.1517 23.3261 5.52105 23.95 4.80134 23.95H1.60036C0.877384 23.95 0.25 23.3229 0.25 22.6V8.6ZM1.56036 7.55C1.00629 7.55 0.55 7.99953 0.55 8.6V22.6C0.55 23.1571 1.04298 23.65 1.60036 23.65H4.80134C5.36198 23.65 5.8517 23.1539 5.8517 22.64V8.6C5.8517 8.04286 5.35872 7.55 4.80134 7.55H1.56036ZM1.41862 8.42326C1.37555 8.47293 1.35026 8.53859 1.35026 8.6V22.6C1.35026 22.6512 1.37737 22.715 1.43133 22.7689C1.48529 22.8229 1.54909 22.85 1.60036 22.85H4.80134C4.86277 22.85 4.92848 22.8247 4.97818 22.7816C5.02746 22.7389 5.05144 22.6879 5.05144 22.64V8.6C5.05144 8.54881 5.02433 8.48505 4.97037 8.4311C4.9164 8.37715 4.8526 8.35002 4.80134 8.35002H1.56036C1.51242 8.35002 1.46131 8.37402 1.41862 8.42326ZM1.19195 8.22674C1.27929 8.126 1.40823 8.05002 1.56036 8.05002H4.80134C4.95015 8.05002 5.0864 8.12289 5.18247 8.21894C5.27854 8.31499 5.35144 8.45122 5.35144 8.6V22.64C5.35144 22.7922 5.27539 22.9211 5.17462 23.0084C5.07428 23.0953 4.93993 23.15 4.80134 23.15H1.60036C1.45155 23.15 1.3153 23.0771 1.21923 22.9811C1.12316 22.885 1.05026 22.7488 1.05026 22.6V8.6C1.05026 8.46139 1.10497 8.32706 1.19195 8.22674Z" />
                                                <path
                                                    d="M18.4055 7H17.5653C16.0048 7 14.5244 7.68002 13.5641 8.76002V8.2C13.5641 7.80002 13.164 7.39998 12.7638 7.39998H8.76266C8.40255 7.39998 7.9624 7.72 7.9624 8.15997V23.08C7.9624 23.52 8.40255 23.8 8.76266 23.8H13.164C13.5241 23.8 13.9643 23.52 13.9643 23.08V14.44C13.9643 13.12 14.9245 12.0799 16.1649 12.0799C16.8051 12.0799 17.4053 12.3199 17.8454 12.76C18.2455 13.12 18.4056 13.68 18.4056 14.4V23C18.4056 23.4 18.8057 23.8 19.2058 23.8H23.207C23.6071 23.8 24.0073 23.4 24.0073 23V12.68C24.0072 9.48002 21.5665 7 18.4055 7ZM23.207 22.96L23.167 23H19.2458L19.2058 14.4C19.2058 13.44 18.9657 12.72 18.4456 12.2C17.8454 11.6 17.0451 11.28 16.2049 11.28C14.5244 11.32 13.204 12.68 13.204 14.44V23H8.80265V8.2H12.7638L12.8038 8.23998V10.76L13.8842 9.67999L13.9242 9.64C14.7244 8.52002 16.1248 7.80002 17.6053 7.80002H18.4455C21.1263 7.80002 23.207 9.96002 23.207 12.68V22.96Z" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M13.7142 8.38728C14.6992 7.43488 16.0983 6.8501 17.5654 6.8501H18.4056C21.6515 6.8501 24.1573 9.39933 24.1574 12.6801V23.0001C24.1574 23.2489 24.0344 23.4851 23.8633 23.6562C23.6922 23.8272 23.4559 23.9501 23.2071 23.9501H19.2059C18.9571 23.9501 18.7208 23.8272 18.5497 23.6562C18.3786 23.4851 18.2557 23.2489 18.2557 23.0001V14.4001C18.2557 13.6976 18.0989 13.1898 17.7452 12.8716L17.7393 12.8663C17.3287 12.4558 16.7674 12.23 16.165 12.23C15.0176 12.23 14.1143 13.1924 14.1143 14.4401V23.0801C14.1143 23.3545 13.9761 23.5746 13.7934 23.7218C13.6128 23.8674 13.3798 23.9501 13.1641 23.9501H8.76276C8.54705 23.9501 8.31405 23.8674 8.13344 23.7218C7.95079 23.5746 7.8125 23.3545 7.8125 23.0801V8.16007C7.8125 7.88736 7.94909 7.65868 8.12909 7.50196C8.30756 7.34656 8.54166 7.25008 8.76276 7.25008H12.7639C13.0128 7.25008 13.2491 7.37296 13.4202 7.54402C13.5913 7.71507 13.7142 7.95131 13.7142 8.2001V8.38728ZM17.5654 7.1501C16.047 7.1501 14.6077 7.81232 13.6763 8.85979L13.4142 9.15456V8.2001C13.4142 8.04891 13.3371 7.88513 13.2081 7.75618C13.0791 7.62722 12.9152 7.55008 12.7639 7.55008H8.76276C8.62375 7.55008 8.45771 7.61361 8.32609 7.72821C8.19598 7.84149 8.1125 7.99281 8.1125 8.16007V23.0801C8.1125 23.2456 8.19428 23.3855 8.32173 23.4883C8.45123 23.5927 8.61836 23.6501 8.76276 23.6501H13.1641C13.3085 23.6501 13.4756 23.5927 13.6051 23.4883C13.7326 23.3855 13.8143 23.2456 13.8143 23.0801V14.4401C13.8143 13.0477 14.8317 11.93 16.165 11.93C16.8416 11.93 17.4794 12.1834 17.9488 12.6512C18.393 13.053 18.5557 13.6642 18.5557 14.4001V23.0001C18.5557 23.1513 18.6328 23.315 18.7618 23.444C18.8908 23.573 19.0546 23.6501 19.2059 23.6501H23.2071C23.3584 23.6501 23.5222 23.573 23.6512 23.444C23.7802 23.3151 23.8574 23.1513 23.8574 23.0001V12.6801C23.8573 9.5609 21.4817 7.1501 18.4056 7.1501H17.5654ZM17.6054 7.95011C16.1714 7.95011 14.8173 8.64827 14.0463 9.7273L14.0391 9.73741L13.9903 9.78617L12.6539 11.1221V8.3501H8.95275V22.8501H13.0541V14.4401C13.0541 12.6017 14.4365 11.1721 16.2014 11.1302L16.205 11.1301C17.0838 11.1301 17.9226 11.4651 18.5517 12.094C19.1088 12.651 19.3558 13.4148 19.3559 14.3998C19.3559 14.3999 19.3559 14.3997 19.3559 14.3998L19.3952 22.8501H23.0571V12.6801C23.0571 10.0389 21.0396 7.95011 18.4456 7.95011H17.6054ZM13.8095 9.54271C14.6402 8.38762 16.0829 7.65011 17.6054 7.65011H18.4456C21.2133 7.65011 23.3571 9.88133 23.3571 12.6801V23.0222L23.2292 23.1501H19.0966L19.0559 14.4008C19.0559 13.466 18.8228 12.7892 18.3396 12.3062C17.7688 11.7356 17.0078 11.4306 16.2068 11.4301C14.6115 11.469 13.3541 12.7592 13.3541 14.4401V23.1501H8.65275V8.0501H12.8261L12.9539 8.17794V10.398L13.7782 9.574L13.8095 9.54271Z" />
                                            </g>
                                        </svg>

                                    </span>
                                </a>
                            </div>
                            <div class="icon">
                                <a href="{{ $setting ? $setting->twitter : '#' }}" target="_blank">
                                    <span>

                                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_1632_27767)">
                                                <path
                                                    d="M0.486891 19.5949C2.70956 21.0049 5.29634 21.7499 7.96714 21.7499C11.8783 21.7499 15.4624 20.2479 18.0602 17.5209C20.5459 14.9109 21.9133 11.4039 21.8453 7.84489C22.7876 7.03889 23.8959 5.49989 23.8959 3.99989C23.8959 3.42489 23.2717 3.05989 22.7656 3.35289C21.8803 3.87289 21.0731 4.00889 20.2428 3.77589C18.5473 2.12389 16.0236 1.77589 13.8969 2.92189C12.0384 3.92189 11.0051 5.75189 11.0981 7.75189C7.95814 7.36889 5.05727 5.79489 3.03466 3.34889C2.70256 2.94989 2.07237 2.99689 1.80829 3.44889C0.833996 5.11689 0.843999 7.04989 1.69125 8.61089C1.28813 8.68189 1.03906 9.02089 1.03906 9.38789C1.03906 10.9569 1.74527 12.3989 2.88261 13.3829C2.67055 13.5869 2.60053 13.8899 2.69055 14.1599C3.1907 15.6619 4.32304 16.8359 5.73847 17.4239C4.19901 18.1589 2.4965 18.4039 0.98104 18.2179C0.196804 18.1119 -0.190312 19.1659 0.486891 19.5949ZM8.1722 17.6809C8.73337 17.2499 8.43528 16.3519 7.73107 16.3369C6.4907 16.3109 5.36136 15.6999 4.65814 14.7389C4.99725 14.7169 5.34835 14.6649 5.68245 14.5749C6.44368 14.3689 6.40767 13.2709 5.63444 13.1159C4.23102 12.8339 3.12969 11.8119 2.71656 10.4959C3.09367 10.5889 3.47779 10.6409 3.86091 10.6479C4.62013 10.6519 4.90722 9.67889 4.28803 9.27189C2.89261 8.35289 2.29744 6.72989 2.69155 5.20389C5.12829 7.67189 8.43428 9.15889 11.9313 9.32689C12.4325 9.35789 12.8086 8.88689 12.6986 8.40989C12.2234 6.35089 13.3738 4.90789 14.6091 4.24289C15.8315 3.58289 17.7941 3.37689 19.2985 4.95489C19.7457 5.42589 21.2541 5.44389 22.0214 5.26489C21.6773 5.91289 21.1481 6.52789 20.653 6.87389C20.4419 7.02189 20.3209 7.26789 20.3339 7.52489C20.4949 10.8099 19.2705 14.0759 16.9748 16.4849C14.6622 18.9119 11.4642 20.2489 7.96814 20.2489C6.57772 20.2489 5.21431 20.0229 3.92592 19.5869C5.46639 19.2889 6.92983 18.6369 8.1722 17.6809Z" />
                                            </g>

                                        </svg>
                                    </span>
                                </a>
                            </div>
                            <div class="icon">
                                <a href="{{ $setting ? $setting->instagram : '#' }}" target="_blank">
                                    <span>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M1.25 6C1.25 3.37665 3.37665 1.25 6 1.25H18C20.6234 1.25 22.75 3.37665 22.75 6V18C22.75 20.6234 20.6234 22.75 18 22.75H6C3.37665 22.75 1.25 20.6234 1.25 18V6ZM6 2.75C4.20507 2.75 2.75 4.20507 2.75 6V18C2.75 19.7949 4.20507 21.25 6 21.25H18C19.7949 21.25 21.25 19.7949 21.25 18V6C21.25 4.20507 19.7949 2.75 18 2.75H6Z" />
                                            <path
                                                d="M19 6C19 6.55228 18.5523 7 18 7C17.4477 7 17 6.55228 17 6C17 5.44772 17.4477 5 18 5C18.5523 5 19 5.44772 19 6Z" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M12 7.75C9.65279 7.75 7.75 9.65279 7.75 12C7.75 14.3472 9.65279 16.25 12 16.25C14.3472 16.25 16.25 14.3472 16.25 12C16.25 9.65279 14.3472 7.75 12 7.75ZM6.25 12C6.25 8.82436 8.82436 6.25 12 6.25C15.1756 6.25 17.75 8.82436 17.75 12C17.75 15.1756 15.1756 17.75 12 17.75C8.82436 17.75 6.25 15.1756 6.25 12Z" />
                                        </svg>


                                    </span>
                                </a>
                            </div>
                            <div class="icon">
                                <a href="{{ $setting ? $setting->facebook : '#' }}" target="_blank">
                                    <span>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_1632_27773)">
                                                <path
                                                    d="M13.6368 23.9531H9.79227C9.15011 23.9531 8.62773 23.4309 8.62773 22.7889V14.12H6.38475C5.74259 14.12 5.22021 13.5976 5.22021 12.9558V9.24115C5.22021 8.59918 5.74259 8.07697 6.38475 8.07697H8.62773V6.2168C8.62773 4.37238 9.20707 2.80316 10.3029 1.67908C11.4037 0.549866 12.9421 -0.046875 14.7517 -0.046875L17.6837 -0.0421143C18.3248 -0.0410156 18.8463 0.481201 18.8463 1.12207V4.57105C18.8463 5.21301 18.3241 5.73523 17.6821 5.73523L15.708 5.73596C15.1059 5.73596 14.9526 5.85663 14.9198 5.89362C14.8658 5.95496 14.8015 6.12836 14.8015 6.60718V8.07678H17.5337C17.7394 8.07678 17.9387 8.1275 18.11 8.22308C18.4794 8.42944 18.7091 8.81964 18.7091 9.24133L18.7076 12.956C18.7076 13.5976 18.1852 14.1198 17.5431 14.1198H14.8015V22.7889C14.8015 23.4309 14.279 23.9531 13.6368 23.9531ZM10.0351 22.5461H13.3939V13.4901C13.3939 13.0615 13.7429 12.7128 14.1715 12.7128H17.3002L17.3015 9.48395H14.1713C13.7427 9.48395 13.3939 9.13532 13.3939 8.70667V6.60718C13.3939 6.0575 13.4498 5.43237 13.8648 4.96252C14.3663 4.39453 15.1567 4.32898 15.7076 4.32898L17.4389 4.32825V1.3645L14.7506 1.36011C11.8424 1.36011 10.0351 3.22119 10.0351 6.2168V8.70667C10.0351 9.13513 9.6864 9.48395 9.25781 9.48395H6.62762V12.7128H9.25781C9.6864 12.7128 10.0351 13.0615 10.0351 13.4901V22.5461Z" />
                                            </g>
                                        </svg>

                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" col-xl-8 col-lg-12 col-12 col-md-12 ">
                    <div class="row footer-ml">
                        <div class="col-xl-4 col-lg-4 col-sm-6 col-md-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="footer-item-text">
                                <h3>{{ __('translate.Popular links') }}</h3>
                            </div>
                            <div class="footer-item-text-link">
                            @if(App\Helpers\MenuHelper::hasMenu('footer'))
                                {!! App\Helpers\MenuHelper::renderMobileMenu('footer') !!}
                            @else
                                <ul>
                                    <li>
                                        <a href="mailto:{{ $setting ? $setting->email : 'info@carbaz.com' }}">
                                            <span>
                                                <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 4L9.93142 9.91715C10.5623 10.3452 11.4377 10.3452 12.0686 9.91715L21 4M2 1H20C20.5523 1 21 1.44772 21 2V18C21 18.5523 20.5523 19 20 19H2C1.44772 19 1 18.5523 1 18V2C1 1.44772 1.44772 1 2 1Z"/>
                                                    </svg>
                                            </span>
                                            {{ $setting ? $setting->email : 'info@carbaz.com' }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('about-us') }}">
                                            <span>
                                                <svg width="13" height="10" viewBox="0 0 13 10" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.62856 9L12.2952 5M12.2952 5L8.62856 0.999999M12.2952 5L1.29523 5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                            {{ __('translate.About') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('listings') }}">
                                            <span>
                                                <svg width="13" height="10" viewBox="0 0 13 10" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.62856 9L12.2952 5M12.2952 5L8.62856 0.999999M12.2952 5L1.29523 5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                            {{ __('translate.Listings') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('contact-us') }}">
                                            <span>
                                                <svg width="13" height="10" viewBox="0 0 13 10" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.62856 9L12.2952 5M12.2952 5L8.62856 0.999999M12.2952 5L1.29523 5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                            {{ __('translate.Contact') }}
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-sm-6 col-md-5 footer-res-mt " data-aos="fade-up"
                            data-aos-delay="300">
                            <div class="footer-item-text">
                                <h3>{{ __('translate.My Profile') }}</h3>
                            </div>
                            <div class="footer-item-text-link">
                                <ul>

                                    <li>
                                        <a href="{{ route('user.dashboard') }}"> <span>
                                        <svg width="13" height="10" viewBox="0 0 13 10" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.62856 9L12.2952 5M12.2952 5L8.62856 0.999999M12.2952 5L1.29523 5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg></span> {{ __('translate.Dashboard') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('user.car.index') }}"> <span>
                                        <svg width="13" height="10" viewBox="0 0 13 10" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.62856 9L12.2952 5M12.2952 5L8.62856 0.999999M12.2952 5L1.29523 5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg></span> {{ __('translate.Manage Car') }}
                                            </a>
                                        </li>
                                    <li>
                                        <a href="{{ route('user.edit-profile') }}"> <span>
                                        <svg width="13" height="10" viewBox="0 0 13 10" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.62856 9L12.2952 5M12.2952 5L8.62856 0.999999M12.2952 5L1.29523 5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg></span> {{ __('translate.Edit Profile') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('user.reviews') }}"> <span>
                                        <svg width="13" height="10" viewBox="0 0 13 10" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.62856 9L12.2952 5M12.2952 5L8.62856 0.999999M12.2952 5L1.29523 5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg></span> {{ __('translate.Review List') }}
                                        </a>
                                    </li>


                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4   col-sm-6 col-md-3" data-aos="fade-up" data-aos-delay="400">

                            <div class="footer-item-text-link two">
                                <div class="footer-item-text">
                                    <h3>{{ __('translate.Contact Us') }}</h3>
                                </div>

                                <ul>
                                    <li>
                                        <a href="tel:{{ $setting ? $setting->phone : '+1234567890' }}">
                                            <span>
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3 1.75C2.30964 1.75 1.75 2.30964 1.75 3C1.75 11.4223 8.57766 18.25 17 18.25C17.6904 18.25 18.25 17.6904 18.25 17V15.3541C18.25 14.8429 17.9388 14.3833 17.4642 14.1935L15.4301 13.3798C14.8265 13.1384 14.1386 13.3999 13.8478 13.9814L13.6708 14.3354C13.5196 14.6379 13.1846 14.8018 12.8529 14.7354L13 14C12.8529 14.7354 12.8532 14.7355 12.8529 14.7354L12.8519 14.7352L12.8507 14.735L12.8475 14.7343L12.8385 14.7325L12.8097 14.7263C12.7859 14.721 12.7529 14.7135 12.7114 14.7034C12.6285 14.6832 12.5115 14.6528 12.3665 14.6101C12.0768 14.525 11.6734 14.3903 11.2046 14.1894C10.2704 13.789 9.05609 13.1167 7.96967 12.0303C6.88325 10.9439 6.21099 9.72958 5.81064 8.79544C5.60973 8.32664 5.47504 7.92316 5.38985 7.6335C5.34721 7.48851 5.31682 7.37154 5.29664 7.28859C5.28655 7.2471 5.279 7.21408 5.27374 7.19026L5.26753 7.1615L5.26566 7.15247L5.26501 7.14932L5.26477 7.14809C5.26472 7.14785 5.26456 7.14709 6 7L5.26456 7.14709C5.19824 6.81544 5.36208 6.48043 5.66459 6.32918L6.01861 6.15217C6.60008 5.86143 6.86163 5.17351 6.62018 4.5699L5.80653 2.53576C5.6167 2.06119 5.15706 1.75 4.64593 1.75H3ZM6.88322 7.38709C8.02553 6.69729 8.51646 5.27171 8.0129 4.01281L7.19925 1.97868C6.78162 0.934616 5.77042 0.25 4.64593 0.25H3C1.48122 0.25 0.25 1.48122 0.25 3C0.25 12.2508 7.74923 19.75 17 19.75C18.5188 19.75 19.75 18.5188 19.75 17V15.3541C19.75 14.2296 19.0654 13.2184 18.0213 12.8008L15.9872 11.9871C14.7283 11.4835 13.3027 11.9745 12.6129 13.1168C12.3906 13.0457 12.111 12.9459 11.7954 12.8106C10.9796 12.461 9.94391 11.8833 9.03033 10.9697C8.11675 10.0561 7.53901 9.02042 7.18936 8.20456C7.05411 7.88897 6.95433 7.60941 6.88322 7.38709ZM10.25 1C10.25 0.585786 10.5858 0.25 11 0.25C12.1491 0.25 13.2869 0.476325 14.3485 0.916054C15.4101 1.35578 16.3747 2.0003 17.1872 2.81282C17.9997 3.62533 18.6442 4.58992 19.0839 5.65152C19.5237 6.71312 19.75 7.85093 19.75 9C19.75 9.41421 19.4142 9.75 19 9.75C18.5858 9.75 18.25 9.41421 18.25 9C18.25 8.04792 18.0625 7.10516 17.6981 6.22554C17.3338 5.34593 16.7997 4.5467 16.1265 3.87348C15.4533 3.20025 14.6541 2.66622 13.7745 2.30187C12.8948 1.93753 11.9521 1.75 11 1.75C10.5858 1.75 10.25 1.41421 10.25 1ZM10.25 5C10.25 4.58579 10.5858 4.25 11 4.25C11.6238 4.25 12.2415 4.37286 12.8177 4.61157C13.394 4.85028 13.9177 5.20016 14.3588 5.64124C14.7998 6.08232 15.1497 6.60596 15.3884 7.18225C15.6271 7.75855 15.75 8.37622 15.75 9C15.75 9.41421 15.4142 9.75 15 9.75C14.5858 9.75 14.25 9.41421 14.25 9C14.25 8.5732 14.1659 8.15059 14.0026 7.75628C13.8393 7.36197 13.5999 7.00369 13.2981 6.7019C12.9963 6.40011 12.638 6.16072 12.2437 5.99739C11.8494 5.83406 11.4268 5.75 11 5.75C10.5858 5.75 10.25 5.41421 10.25 5Z"/>
                                                    </svg>

                                            </span>
                                            {{ $setting ? $setting->phone : '+1234567890' }}
                                        </a>
                                    </li>

                                    <li>
                                        <a href="mailto:{{ $setting ? $setting->email : 'info@carbaz.com' }}">
                                            <span>
                                                <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.25 5C0.25 2.37665 2.37665 0.25 5 0.25H17C19.6234 0.25 21.75 2.37665 21.75 5V15C21.75 17.6234 19.6234 19.75 17 19.75H7C6.58579 19.75 6.25 19.4142 6.25 19C6.25 18.5858 6.58579 18.25 7 18.25H17C18.7949 18.25 20.25 16.7949 20.25 15V5C20.25 3.20507 18.7949 1.75 17 1.75H5C3.20507 1.75 1.75 3.20507 1.75 5V10C1.75 10.4142 1.41421 10.75 1 10.75C0.585786 10.75 0.25 10.4142 0.25 10V5ZM4.37596 5.58397C4.60573 5.23933 5.07138 5.1462 5.41603 5.37596L9.19723 7.89676C10.2889 8.62454 11.7111 8.62454 12.8028 7.89676L16.584 5.37596C16.9286 5.1462 17.3943 5.23933 17.624 5.58397C17.8538 5.92862 17.7607 6.39427 17.416 6.62404L13.6348 9.14484C12.0393 10.2085 9.9607 10.2085 8.36518 9.14484L4.58397 6.62404C4.23933 6.39427 4.1462 5.92862 4.37596 5.58397ZM0.25 13C0.25 12.5858 0.585786 12.25 1 12.25H7C7.41421 12.25 7.75 12.5858 7.75 13C7.75 13.4142 7.41421 13.75 7 13.75H1C0.585786 13.75 0.25 13.4142 0.25 13ZM0.25 16C0.25 15.5858 0.585786 15.25 1 15.25H7C7.41421 15.25 7.75 15.5858 7.75 16C7.75 16.4142 7.41421 16.75 7 16.75H1C0.585786 16.75 0.25 16.4142 0.25 16Z"/>
                                                    </svg>


                                            </span>
                                            {{ $setting ? $setting->email : 'info@carbaz.com' }}
                                        </a>
                                    </li>


                                    <li>
                                        <a href="javascript:;">
                                            <span>
                                                <svg width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9 1.75C5.47857 1.75 2.25 4.48059 2.25 8.11111C2.25 9.82498 3.34675 12.1327 4.85679 14.0668C5.59932 15.0178 6.409 15.8353 7.171 16.4074C7.95947 16.9993 8.59247 17.25 9 17.25C9.42269 17.25 10.0624 17.0094 10.8465 16.4554C11.6072 15.9179 12.4148 15.1481 13.1547 14.2468C14.6599 12.4136 15.75 10.2065 15.75 8.5C15.75 4.45503 12.4938 1.75 9 1.75ZM0.75 8.11111C0.75 3.51941 4.78944 0.25 9 0.25C13.2382 0.25 17.25 3.54497 17.25 8.5C17.25 10.7209 15.9026 13.2638 14.314 15.1987C13.5071 16.1815 12.6038 17.0504 11.7121 17.6804C10.8438 18.294 9.88982 18.75 9 18.75C8.09503 18.75 7.13428 18.2555 6.27041 17.6069C5.38006 16.9385 4.4788 16.0201 3.67446 14.9899C2.09075 12.9614 0.75 10.3246 0.75 8.11111ZM9 5.75C7.75736 5.75 6.75 6.75736 6.75 8C6.75 9.24264 7.75736 10.25 9 10.25C10.2426 10.25 11.25 9.24264 11.25 8C11.25 6.75736 10.2426 5.75 9 5.75ZM5.25 8C5.25 5.92893 6.92893 4.25 9 4.25C11.0711 4.25 12.75 5.92893 12.75 8C12.75 10.0711 11.0711 11.75 9 11.75C6.92893 11.75 5.25 10.0711 5.25 8ZM2.25 21C2.25 20.5858 2.58579 20.25 3 20.25H15C15.4142 20.25 15.75 20.5858 15.75 21C15.75 21.4142 15.4142 21.75 15 21.75H3C2.58579 21.75 2.25 21.4142 2.25 21Z"/>
                                                    </svg>
                                            </span>
                                            {{ $setting ? $setting->address : '123 Main Street, City, Country' }}
                                        </a>
                                    </li>
                                </ul>


                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="copyright">
            <div class="container">
                <div class="row ">
                    <div class="col-lg-6 col-sm-6 col-md-6">
                        <div class="copyright-text">
                            <p>{{ $setting ? $setting->copyright : '© ' . date('Y') . ' Carbaz. All rights reserved.' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6  col-md-6">
                        <div class="copyright-item">
                            <a href="{{ route('privacy-policy') }}">{{ __('translate.Privacy Policy') }}</a>
                            <a href="{{ route('terms-conditions') }}">{{ __('translate.Terms & Conditions') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </footer>

    <!-- footer prart start  end -->


    @if ($cookie_consent && $cookie_consent->status == 1)
        <!-- common-modal start  -->
        <div class="common-modal cookie_consent_modal d-none" >
            <button type="button" class="btn-close cookie_consent_close_btn" aria-label="Close"></button>

            <h5>{{ __('translate.Cookies') }}</h5>
            <p>{{ $cookie_consent ? $cookie_consent->message : 'We use cookies to improve your experience.' }}</p>

            <div class="common-modal-btn">
                <a href="javascript:;" class="thm-btn-two cookie_consent_accept_btn">{{ __('translate.Accept') }}</a>
            </div>

        </div>
        <!-- common-modal end  -->
    @endif




    <!-- back-to-top  -->
    @if (Route::is('home'))
        @if (Session::get('selected_theme') == 'theme_two')
            <div class="back-to-top">
                <span>
                    <svg width="39" height="75" viewBox="0 0 39 75" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.235 75.0022L18.3095 74.9021C18.3095 74.8855 18.3144 74.8688 18.3168 74.8521C18.0878 74.8283 17.8612 74.7854 17.6322 74.7831C15.744 74.7759 13.9751 74.2448 12.2526 73.5542C8.50789 72.0514 5.51357 69.6293 3.26965 66.3332C1.58854 63.8612 0.51897 61.1438 0.192494 58.1906C0.0268193 56.6902 0.0195101 55.1708 0.0146374 53.6585C-0.00485375 47.2806 0.0073282 40.9028 1.90292e-05 34.5249C-0.00485375 30.6382 0.925847 27.0087 3.08205 23.7221C5.88146 19.4567 9.78943 16.6536 14.7986 15.3676C16.9281 14.8222 19.0989 14.6245 21.3014 14.8627C27.6384 15.5486 32.5185 18.5517 36.0001 23.7555C37.4546 25.9298 38.3902 28.3233 38.7678 30.8978C38.9311 32.0171 38.9847 33.1603 38.9896 34.2939C39.0066 41.1386 38.9993 47.9808 38.992 54.8255C38.992 55.8519 39.0066 56.8855 38.9092 57.9048C38.7021 60.0673 38.0735 62.1226 37.0965 64.0731C35.2741 67.7098 32.5843 70.5558 28.9809 72.5372C26.793 73.7423 24.4443 74.5235 21.9348 74.7878C21.4987 74.8331 21.0553 74.8307 20.6119 74.8902C20.7386 74.926 20.8653 74.9641 20.9919 74.9998H17.2399L17.235 75.0022ZM3.08449 44.8395H3.08692C3.08692 48.3309 3.06743 51.8223 3.09911 55.3137C3.10885 56.3973 3.18925 57.4881 3.3598 58.5574C3.7618 61.1128 4.80458 63.4301 6.40772 65.4735C9.05851 68.8553 12.5133 70.9868 16.8355 71.6989C18.8869 72.0371 20.9432 72.0228 22.9654 71.5775C26.8271 70.7249 30.0236 68.7886 32.4649 65.714C34.8891 62.6608 36.0001 59.1885 35.9831 55.3208C35.9489 48.2214 35.9684 41.1195 35.9757 34.0177C35.9757 32.7554 35.8344 31.5099 35.5372 30.2881C34.7015 26.8587 32.94 23.9674 30.1698 21.6978C26.1522 18.4041 21.5401 17.1823 16.3994 18.123C12.8374 18.7756 9.82841 20.4737 7.38959 23.1315C4.53901 26.2371 3.12103 29.8785 3.09423 34.0391C3.07231 37.64 3.08936 41.2386 3.08936 44.8395H3.08449Z" fill="#EE3536"></path>
                        <path d="M38.9361 56.4846C38.9216 56.3798 38.8901 56.275 38.8901 56.1726C38.8877 48.866 38.8853 41.5593 38.8877 34.2526C38.8877 34.0478 38.9192 33.843 38.9361 33.6382V56.487V56.4846Z" fill="#EE3536"></path>
                        <path d="M11.0364 8.95426C10.9997 8.54517 11.2855 8.24461 11.5541 7.94092C13.5504 5.68046 15.5488 3.42314 17.5473 1.16477C17.7576 0.926827 17.9582 0.680536 18.1815 0.455117C18.7973 -0.167918 19.5609 -0.15122 20.1411 0.503123C22.4113 3.06205 24.6773 5.62411 26.941 8.1893C27.4328 8.74658 27.4005 9.48546 26.8839 9.91438C26.3349 10.3694 25.6479 10.313 25.1238 9.72549C23.2677 7.64662 21.4234 5.55836 19.577 3.47219C19.1327 2.97021 19.1402 2.97126 18.6765 3.49723C16.8808 5.53227 15.0808 7.56522 13.2829 9.60025C12.9896 9.93212 12.6563 10.169 12.1764 10.145C11.5196 10.1137 11.0364 9.62948 11.0364 8.95426Z" fill="#EE3536"></path>
                        <path d="M16.0442 34.8738C16.0442 34.0641 16.0004 33.2496 16.054 32.4423C16.1612 30.799 17.5231 29.482 19.2042 29.4439C20.9609 29.4034 22.2668 30.6132 22.5275 32.0779C22.6079 32.5328 22.6395 33.0019 22.6395 33.464C22.6395 34.7381 22.6517 36.0146 22.5908 37.2888C22.508 38.9844 21.0242 40.3205 19.3187 40.3086C17.5694 40.2967 16.1417 38.9725 16.0589 37.2459C16.0223 36.4576 16.0515 35.6645 16.0515 34.8738C16.0515 34.8738 16.0491 34.8738 16.0467 34.8738H16.0442Z" fill="#EE3536"></path>
                    </svg>
                </span>
            </div>
        @elseif (Session::get('selected_theme') == 'theme_one')
            <div class="back-to-top">
                <span>
                    <svg width="39" height="75" viewBox="0 0 39 75" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17.235 75.0022L18.3095 74.9021C18.3095 74.8855 18.3144 74.8688 18.3168 74.8521C18.0878 74.8283 17.8612 74.7854 17.6322 74.7831C15.744 74.7759 13.9751 74.2448 12.2526 73.5542C8.50789 72.0514 5.51357 69.6293 3.26965 66.3332C1.58854 63.8612 0.51897 61.1438 0.192494 58.1906C0.0268193 56.6902 0.0195101 55.1708 0.0146374 53.6585C-0.00485375 47.2806 0.0073282 40.9028 1.90292e-05 34.5249C-0.00485375 30.6382 0.925847 27.0087 3.08205 23.7221C5.88146 19.4567 9.78943 16.6536 14.7986 15.3676C16.9281 14.8222 19.0989 14.6245 21.3014 14.8627C27.6384 15.5486 32.5185 18.5517 36.0001 23.7555C37.4546 25.9298 38.3902 28.3233 38.7678 30.8978C38.9311 32.0171 38.9847 33.1603 38.9896 34.2939C39.0066 41.1386 38.9993 47.9808 38.992 54.8255C38.992 55.8519 39.0066 56.8855 38.9092 57.9048C38.7021 60.0673 38.0735 62.1226 37.0965 64.0731C35.2741 67.7098 32.5843 70.5558 28.9809 72.5372C26.793 73.7423 24.4443 74.5235 21.9348 74.7878C21.4987 74.8331 21.0553 74.8307 20.6119 74.8902C20.7386 74.926 20.8653 74.9641 20.9919 74.9998H17.2399L17.235 75.0022ZM3.08449 44.8395H3.08692C3.08692 48.3309 3.06743 51.8223 3.09911 55.3137C3.10885 56.3973 3.18925 57.4881 3.3598 58.5574C3.7618 61.1128 4.80458 63.4301 6.40772 65.4735C9.05851 68.8553 12.5133 70.9868 16.8355 71.6989C18.8869 72.0371 20.9432 72.0228 22.9654 71.5775C26.8271 70.7249 30.0236 68.7886 32.4649 65.714C34.8891 62.6608 36.0001 59.1885 35.9831 55.3208C35.9489 48.2214 35.9684 41.1195 35.9757 34.0177C35.9757 32.7554 35.8344 31.5099 35.5372 30.2881C34.7015 26.8587 32.94 23.9674 30.1698 21.6978C26.1522 18.4041 21.5401 17.1823 16.3994 18.123C12.8374 18.7756 9.82841 20.4737 7.38959 23.1315C4.53901 26.2371 3.12103 29.8785 3.09423 34.0391C3.07231 37.64 3.08936 41.2386 3.08936 44.8395H3.08449Z"
                            fill="#405FF2" />
                        <path
                            d="M38.9361 56.4846C38.9216 56.3798 38.8901 56.275 38.8901 56.1726C38.8877 48.866 38.8853 41.5593 38.8877 34.2526C38.8877 34.0478 38.9192 33.843 38.9361 33.6382V56.487V56.4846Z"
                            fill="#405FF2" />
                        <path
                            d="M11.0364 8.95426C10.9997 8.54517 11.2855 8.24461 11.5541 7.94092C13.5504 5.68046 15.5488 3.42314 17.5473 1.16477C17.7576 0.926827 17.9582 0.680536 18.1815 0.455117C18.7973 -0.167918 19.5609 -0.15122 20.1411 0.503123C22.4113 3.06205 24.6773 5.62411 26.941 8.1893C27.4328 8.74658 27.4005 9.48546 26.8839 9.91438C26.3349 10.3694 25.6479 10.313 25.1238 9.72549C23.2677 7.64662 21.4234 5.55836 19.577 3.47219C19.1327 2.97021 19.1402 2.97126 18.6765 3.49723C16.8808 5.53227 15.0808 7.56522 13.2829 9.60025C12.9896 9.93212 12.6563 10.169 12.1764 10.145C11.5196 10.1137 11.0364 9.62948 11.0364 8.95426Z"
                            fill="#405FF2" />
                        <path
                            d="M16.0442 34.8738C16.0442 34.0641 16.0004 33.2496 16.054 32.4423C16.1612 30.799 17.5231 29.482 19.2042 29.4439C20.9609 29.4034 22.2668 30.6132 22.5275 32.0779C22.6079 32.5328 22.6395 33.0019 22.6395 33.464C22.6395 34.7381 22.6517 36.0146 22.5908 37.2888C22.508 38.9844 21.0242 40.3205 19.3187 40.3086C17.5694 40.2967 16.1417 38.9725 16.0589 37.2459C16.0223 36.4576 16.0515 35.6645 16.0515 34.8738C16.0515 34.8738 16.0491 34.8738 16.0467 34.8738H16.0442Z"
                            fill="#405FF2" />
                    </svg>
                </span>
            </div>
        @elseif (Session::get('selected_theme') == 'theme_three')
            <div class="back-to-top">
                <span>
                    <svg width="39" height="75" viewBox="0 0 39 75" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.235 75.0024L18.3095 74.9024C18.3095 74.8857 18.3144 74.869 18.3168 74.8524C18.0878 74.8285 17.8612 74.7857 17.6322 74.7833C15.744 74.7762 13.9751 74.2451 12.2526 73.5544C8.50789 72.0516 5.51357 69.6296 3.26965 66.3335C1.58854 63.8614 0.51897 61.144 0.192494 58.1909C0.0268193 56.6905 0.0195101 55.171 0.0146374 53.6587C-0.00485375 47.2809 0.0073282 40.903 1.90292e-05 34.5252C-0.00485375 30.6385 0.925847 27.0089 3.08205 23.7224C5.88146 19.457 9.78943 16.6539 14.7986 15.3678C16.9281 14.8224 19.0989 14.6248 21.3014 14.8629C27.6384 15.5488 32.5185 18.552 36.0001 23.7557C37.4546 25.9301 38.3902 28.3236 38.7678 30.898C38.9311 32.0174 38.9847 33.1605 38.9896 34.2942C39.0066 41.1388 38.9993 47.9811 38.992 54.8257C38.992 55.8522 39.0066 56.8858 38.9092 57.9051C38.7021 60.0676 38.0735 62.1229 37.0965 64.0734C35.2741 67.71 32.5843 70.556 28.9809 72.5375C26.793 73.7426 24.4443 74.5237 21.9348 74.7881C21.4987 74.8333 21.0553 74.8309 20.6119 74.8905C20.7386 74.9262 20.8653 74.9643 20.9919 75H17.2399L17.235 75.0024ZM3.08449 44.8398H3.08692C3.08692 48.3312 3.06743 51.8226 3.09911 55.3139C3.10885 56.3976 3.18925 57.4883 3.3598 58.5576C3.7618 61.1131 4.80458 63.4303 6.40772 65.4737C9.05851 68.8556 12.5133 70.9871 16.8355 71.6992C18.8869 72.0373 20.9432 72.0231 22.9654 71.5777C26.8271 70.7251 30.0236 68.7889 32.4649 65.7143C34.8891 62.6611 36.0001 59.1888 35.9831 55.3211C35.9489 48.2216 35.9684 41.1198 35.9757 34.0179C35.9757 32.7557 35.8344 31.5101 35.5372 30.2884C34.7015 26.8589 32.94 23.9677 30.1698 21.698C26.1522 18.4043 21.5401 17.1826 16.3994 18.1233C12.8374 18.7758 9.82841 20.4739 7.38959 23.1317C4.53901 26.2373 3.12103 29.8787 3.09423 34.0393C3.07231 37.6403 3.08936 41.2388 3.08936 44.8398H3.08449Z" fill="#46D993"></path>
                        <path d="M38.9361 56.4849C38.9216 56.3801 38.8901 56.2753 38.8901 56.1729C38.8877 48.8662 38.8853 41.5595 38.8877 34.2529C38.8877 34.0481 38.9192 33.8432 38.9361 33.6384V56.4872V56.4849Z" fill="#46D993"></path>
                        <path d="M11.0364 8.95451C10.9997 8.54541 11.2855 8.24485 11.5541 7.94116C13.5504 5.68071 15.5488 3.42338 17.5473 1.16501C17.7576 0.927071 17.9582 0.68078 18.1815 0.455361C18.7973 -0.167673 19.5609 -0.150976 20.1411 0.503367C22.4113 3.06229 24.6773 5.62435 26.941 8.18954C27.4328 8.74683 27.4005 9.4857 26.8839 9.91462C26.3349 10.3696 25.6479 10.3133 25.1238 9.72573C23.2677 7.64686 21.4234 5.5586 19.577 3.47243C19.1327 2.97046 19.1402 2.9715 18.6765 3.49748C16.8808 5.53251 15.0808 7.56546 13.2829 9.6005C12.9896 9.93237 12.6563 10.1693 12.1764 10.1453C11.5196 10.114 11.0364 9.62972 11.0364 8.95451Z" fill="#46D993"></path>
                        <path d="M16.0442 34.8741C16.0442 34.0644 16.0004 33.2499 16.054 32.4425C16.1612 30.7992 17.5231 29.4822 19.2042 29.4441C20.9609 29.4036 22.2668 30.6135 22.5275 32.0781C22.6079 32.533 22.6395 33.0022 22.6395 33.4642C22.6395 34.7383 22.6517 36.0149 22.5908 37.289C22.508 38.9847 21.0242 40.3208 19.3187 40.3088C17.5694 40.2969 16.1417 38.9728 16.0589 37.2461C16.0223 36.4578 16.0515 35.6648 16.0515 34.8741C16.0515 34.8741 16.0491 34.8741 16.0467 34.8741H16.0442Z" fill="#46D993"></path>
                    </svg>
                </span>
            </div>
        @endif
    @else
        <div class="back-to-top">
            <span>
                <svg width="39" height="75" viewBox="0 0 39 75" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M17.235 75.0022L18.3095 74.9021C18.3095 74.8855 18.3144 74.8688 18.3168 74.8521C18.0878 74.8283 17.8612 74.7854 17.6322 74.7831C15.744 74.7759 13.9751 74.2448 12.2526 73.5542C8.50789 72.0514 5.51357 69.6293 3.26965 66.3332C1.58854 63.8612 0.51897 61.1438 0.192494 58.1906C0.0268193 56.6902 0.0195101 55.1708 0.0146374 53.6585C-0.00485375 47.2806 0.0073282 40.9028 1.90292e-05 34.5249C-0.00485375 30.6382 0.925847 27.0087 3.08205 23.7221C5.88146 19.4567 9.78943 16.6536 14.7986 15.3676C16.9281 14.8222 19.0989 14.6245 21.3014 14.8627C27.6384 15.5486 32.5185 18.5517 36.0001 23.7555C37.4546 25.9298 38.3902 28.3233 38.7678 30.8978C38.9311 32.0171 38.9847 33.1603 38.9896 34.2939C39.0066 41.1386 38.9993 47.9808 38.992 54.8255C38.992 55.8519 39.0066 56.8855 38.9092 57.9048C38.7021 60.0673 38.0735 62.1226 37.0965 64.0731C35.2741 67.7098 32.5843 70.5558 28.9809 72.5372C26.793 73.7423 24.4443 74.5235 21.9348 74.7878C21.4987 74.8331 21.0553 74.8307 20.6119 74.8902C20.7386 74.926 20.8653 74.9641 20.9919 74.9998H17.2399L17.235 75.0022ZM3.08449 44.8395H3.08692C3.08692 48.3309 3.06743 51.8223 3.09911 55.3137C3.10885 56.3973 3.18925 57.4881 3.3598 58.5574C3.7618 61.1128 4.80458 63.4301 6.40772 65.4735C9.05851 68.8553 12.5133 70.9868 16.8355 71.6989C18.8869 72.0371 20.9432 72.0228 22.9654 71.5775C26.8271 70.7249 30.0236 68.7886 32.4649 65.714C34.8891 62.6608 36.0001 59.1885 35.9831 55.3208C35.9489 48.2214 35.9684 41.1195 35.9757 34.0177C35.9757 32.7554 35.8344 31.5099 35.5372 30.2881C34.7015 26.8587 32.94 23.9674 30.1698 21.6978C26.1522 18.4041 21.5401 17.1823 16.3994 18.123C12.8374 18.7756 9.82841 20.4737 7.38959 23.1315C4.53901 26.2371 3.12103 29.8785 3.09423 34.0391C3.07231 37.64 3.08936 41.2386 3.08936 44.8395H3.08449Z"
                        fill="#405FF2" />
                    <path
                        d="M38.9361 56.4846C38.9216 56.3798 38.8901 56.275 38.8901 56.1726C38.8877 48.866 38.8853 41.5593 38.8877 34.2526C38.8877 34.0478 38.9192 33.843 38.9361 33.6382V56.487V56.4846Z"
                        fill="#405FF2" />
                    <path
                        d="M11.0364 8.95426C10.9997 8.54517 11.2855 8.24461 11.5541 7.94092C13.5504 5.68046 15.5488 3.42314 17.5473 1.16477C17.7576 0.926827 17.9582 0.680536 18.1815 0.455117C18.7973 -0.167918 19.5609 -0.15122 20.1411 0.503123C22.4113 3.06205 24.6773 5.62411 26.941 8.1893C27.4328 8.74658 27.4005 9.48546 26.8839 9.91438C26.3349 10.3694 25.6479 10.313 25.1238 9.72549C23.2677 7.64662 21.4234 5.55836 19.577 3.47219C19.1327 2.97021 19.1402 2.97126 18.6765 3.49723C16.8808 5.53227 15.0808 7.56522 13.2829 9.60025C12.9896 9.93212 12.6563 10.169 12.1764 10.145C11.5196 10.1137 11.0364 9.62948 11.0364 8.95426Z"
                        fill="#405FF2" />
                    <path
                        d="M16.0442 34.8738C16.0442 34.0641 16.0004 33.2496 16.054 32.4423C16.1612 30.799 17.5231 29.482 19.2042 29.4439C20.9609 29.4034 22.2668 30.6132 22.5275 32.0779C22.6079 32.5328 22.6395 33.0019 22.6395 33.464C22.6395 34.7381 22.6517 36.0146 22.5908 37.2888C22.508 38.9844 21.0242 40.3205 19.3187 40.3086C17.5694 40.2967 16.1417 38.9725 16.0589 37.2459C16.0223 36.4576 16.0515 35.6645 16.0515 34.8738C16.0515 34.8738 16.0491 34.8738 16.0467 34.8738H16.0442Z"
                        fill="#405FF2" />
                </svg>

            </span>
        </div>
    @endif

    <!-- back-to-top  -->

    <!-- fontawesome  -->
    <script src="{{ asset('frontend/assets/fontawesome/js/all.js') }}"></script>

    <!-- jquery  -->
    <script src="{{ asset('global/jquery-3.7.1.min.js') }}"></script>

    <!-- bootstrap.bundle.min.js -->
    <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('frontend/assets/js/gaps.js') }}"></script>

    <!-- venobox.js -->
    <script src="{{ asset('frontend/assets/js/venobox.js') }}"></script>
    <!-- slick.min.js -->
    <script src="{{ asset('frontend/assets/js/slick.min.js') }}"></script>
    <!-- aos.js -->
    <script src="{{ asset('frontend/assets/js/aos.js') }}"></script>
    <!-- custom.js -->
    <script src="{{ asset('frontend/assets/js/custom.js') }}"></script>

    <!-- RTL Support JavaScript -->
    @if(Session::get('lang_dir') == 'right_to_left')
        <script src="{{ asset('frontend/js/rtl.js') }}"></script>
    @endif

    <script src="{{ asset('global/toastr/toastr.min.js') }}"></script>

    <script>
        @if(Session::has('messege'))
        var type="{{Session::get('alert-type','info') }}"
        switch(type){
            case 'info':
                toastr.info("{{ Session::get('messege') }}");
                break;
            case 'success':
                toastr.success("{{ Session::get('messege') }}");
                break;
            case 'warning':
                toastr.warning("{{ Session::get('messege') }}");
                break;
            case 'error':
                toastr.error("{{ Session::get('messege') }}");
                break;
        }
        @endif
    </script>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error('{{ $error }}');
            </script>
        @endforeach
    @endif


    @stack('js_section')

    <script>
        (function($) {
            "use strict"
            $(document).ready(function () {
                $('.cookie_consent_close_btn').on('click', function(){
                    $('.cookie_consent_modal').addClass('d-none');
                });

                $('.cookie_consent_accept_btn').on('click',function() {
                    localStorage.setItem('car-listo-cookie','1');
                    $('.cookie_consent_modal').addClass('d-none');
                });

                $('.before_auth_wishlist').on("click", function(){
                    toastr.error("{{ __('translate.Please login first') }}")
                });

            });
        })(jQuery);

        if (localStorage.getItem('car-listo-cookie') != '1') {
            $('.cookie_consent_modal').removeClass('d-none');
        }

    </script>

</body>

</html>
