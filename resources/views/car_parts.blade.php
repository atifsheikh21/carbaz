@extends('layout')
@section('title')
    <title>{{ __('translate.Car Parts') }}</title>
@endsection

@section('body-content')

@push('style_section')
    <style>
        .listing-list-title{
            display:block;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
            text-transform: uppercase;
        }
        .lp-mobile-card__title{
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }
    </style>
@endpush

<main>
    @php
        $__partBrandModelsJson = json_encode($partBrandModels ?? [], JSON_UNESCAPED_UNICODE);
    @endphp
    <div class="lp-mobile d-block d-md-none">
        <div class="lp-mobile__filter">
            <button class="lp-mobile__filter-label" type="button" data-bs-toggle="offcanvas" data-bs-target="#lpMobileFilter" aria-controls="lpMobileFilter">Filter</button>
            <form class="lp-mobile__filter-form" method="GET" action="{{ route('car-parts') }}">
                <input class="lp-mobile__filter-input" type="text" name="search" value="{{ request()->get('search') }}" placeholder="search car & part by key word">
            </form>
        </div>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="lpMobileFilter" aria-labelledby="lpMobileFilterLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="lpMobileFilterLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form method="GET" action="{{ route('car-parts') }}">
                    <input type="hidden" name="search" value="{{ request()->get('search') }}">

                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <select class="form-select" name="brand_id" data-model-source="part" data-model-target="#car_parts_mobile_model">
                            <option value="">{{ __('translate.Select Brand') }}</option>
                            @foreach ($brands as $brandSlug => $brandLabel)
                                <option {{ request()->get('brand_id') === $brandSlug ? 'selected' : '' }} value="{{ $brandSlug }}">{{ $brandLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Model</label>
                        <select class="form-select" name="model" id="car_parts_mobile_model" data-placeholder="Select brand model" data-selected="{{ request()->get('model') }}" {{ request()->get('brand_id') ? '' : 'disabled' }}>
                            <option value="">Select brand model</option>
                            @foreach (($selectedPartModels ?? []) as $modelOption)
                                <option value="{{ $modelOption }}" {{ request()->get('model') === $modelOption ? 'selected' : '' }}>{{ $modelOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">From Year</label>
                            <select class="form-select" name="from_year">
                                <option value="">From Year</option>
                                @for($y = 1990; $y <= 2026; $y++)
                                    <option value="{{ $y }}" {{ request()->get('from_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">To Year</label>
                            <select class="form-select" name="to_year">
                                <option value="">To Year</option>
                                @for($y = 1990; $y <= 2026; $y++)
                                    <option value="{{ $y }}" {{ request()->get('to_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Min Price</label>
                            <select class="form-select" name="min_price">
                                <option value="">Min Price</option>
                                @for($p = 500; $p <= 500000; $p += 500)
                                    <option value="{{ $p }}" {{ request()->get('min_price') == $p ? 'selected' : '' }}>€{{ number_format($p, 0, '.', ',') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Max Price</label>
                            <select class="form-select" name="max_price">
                                <option value="">Max Price</option>
                                @for($p = 500; $p <= 500000; $p += 500)
                                    <option value="{{ $p }}" {{ request()->get('max_price') == $p ? 'selected' : '' }}>€{{ number_format($p, 0, '.', ',') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-dark">Apply</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lp-mobile__tabs">
            <a class="lp-mobile__tab" href="{{ url()->previous() }}">back</a>
            <a class="lp-mobile__tab" href="{{ route('listings', request()->query()) }}">car ad</a>
            <a class="lp-mobile__tab lp-mobile__tab--active" href="{{ route('car-parts', request()->query()) }}">part ad</a>
            <a class="lp-mobile__tab lp-mobile__tab--right" href="{{ route('home') }}">HOME</a>
        </div>

        <div class="lp-mobile__list">
            @php
                $cityNameMap = [];
                try {
                    $cityIds = $car_parts->pluck('city_id')
                        ->merge($car_parts->pluck('agent.city_id'))
                        ->filter()
                        ->unique()
                        ->values();

                    if ($cityIds->count() > 0) {
                        $cityRows = Modules\City\Entities\City::whereIn('id', $cityIds)->get();
                        foreach ($cityRows as $cityRow) {
                            $cityNameMap[$cityRow->id] = (string) $cityRow->name;
                        }
                    }
                } catch (\Throwable $e) {
                    $cityNameMap = [];
                }
            @endphp
            @forelse($car_parts as $part)
                @php
                    $agent = $part?->agent;
                    $partTranslation = $part?->translations?->firstWhere('lang_code', front_lang())
                        ?? $part?->translations?->firstWhere('lang_code', 'en');
                    $dealerFlagRaw = $agent?->is_dealer ?? null;
                    $dealerFlagNorm = strtolower(trim((string) $dealerFlagRaw));
                    $isDealerSeller = in_array($dealerFlagNorm, ['1', 'true', 'yes'], true);
                    $isPartSeller = (bool) ($agent?->is_part_seller ?? false);
                    $sellerDisplayName = $isDealerSeller && $isPartSeller
                        ? html_decode($agent?->part_company_name)
                        : html_decode($agent?->name);
                    $sellerName = strtoupper(trim((string) $sellerDisplayName));
                    $sellerTypeLabel = $isDealerSeller
                        ? ($isPartSeller ? 'VEHICLE PART SELLER' : 'DEALER')
                        : 'PRIVATE';
                    $picsCount = (int) ($part->galleries_count ?? 0);
                    $sellerPhone = preg_replace('/\s+/', '', (string) ($agent?->phone ?? ''));
                    $rawPrice = $part->offer_price ?: $part->regular_price;
                    $numericPrice = is_numeric($rawPrice) ? (float) $rawPrice : null;
                    $sellerLocation = null;
                    if (!empty($part?->city_id)) {
                        $sellerLocation = strtoupper((string) ($cityNameMap[$part->city_id] ?? ''));
                    }
                    if (!$sellerLocation && !empty($agent?->city_id)) {
                        $sellerLocation = strtoupper((string) ($cityNameMap[$agent->city_id] ?? ''));
                    }
                @endphp

                <div class="lp-mobile-card">
                    <a class="dealer-mobile-card-link" href="{{ route('car-part', $part->slug) }}" aria-label="{{ strtoupper(trim((string) html_decode($partTranslation?->title))) }}"></a>
                    <div class="lp-mobile-card__top">
                        <div class="lp-mobile-card__top-left">{{ $sellerName !== '' ? $sellerName : ' ' }}</div>
                        <div class="lp-mobile-card__top-right">{{ $sellerLocation ?: ' ' }}</div>
                    </div>

                    <div class="lp-mobile-card__media">
                        <img src="{{ getImageOrPlaceholder($part->thumb_image, '640x480') }}" alt="thumb">
                        @if($picsCount > 0)
                            <div class="lp-mobile-card__pics">+{{ $picsCount }} PIC</div>
                        @endif

                        @guest('web')
                            <a href="javascript:;" class="listing-list-fav before_auth_wishlist" aria-label="wishlist">
                                <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z" stroke-width="1.3" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        @else
                            @php
                                $isPartInWishlistMobile = App\Models\Wishlist::where('car_part_id', $part->id)->where('user_id', Auth::user()->id)->first();
                            @endphp
                            <a href="{{ route('user.add-car-part-to-wishlist', $part->id) }}" class="listing-list-fav {{ $isPartInWishlistMobile ? 'active' : '' }}" aria-label="wishlist">
                                <svg width="18" height="16" viewBox="0 0 18 16" fill="{{ $isPartInWishlistMobile ? 'currentColor' : 'none' }}" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z" stroke-width="1.3" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        @endif
                    </div>

                    <div class="lp-mobile-card__body">
                        <div class="lp-mobile-card__title">{{ strtoupper(trim((string) html_decode($partTranslation?->title))) }}</div>
                        <div class="lp-mobile-card__call">
                            @if($sellerPhone)
                                <a href="tel:{{ $sellerPhone }}">CALL</a>
                            @endif
                        </div>

                        <div class="lp-mobile-card__meta">
                            <div><span class="meta-label">Brand:</span> <span class="meta-value">{{ !empty($part?->brand?->name) ? html_decode($part?->brand?->name) : '—' }}{{ !empty($part?->car_model) ? ' ' . html_decode($part?->car_model) : '' }}</span></div>
                            <div><span class="meta-label">Condition:</span> <span class="meta-value">{{ !empty($part->condition) ? html_decode($part->condition) : '—' }}</span></div>
                            <div><span class="meta-label">Part Number:</span> <span class="meta-value">{{ !empty($part->part_number) ? html_decode($part->part_number) : '—' }}</span></div>
                            @php
                                $__fromY = $part->from_year;
                                $__toY = $part->to_year;
                                $__compatYears = '';
                                if (!empty($__fromY) && !empty($__toY)) {
                                    $__compatYears = $__fromY . '-' . $__toY;
                                } elseif (!empty($__fromY)) {
                                    $__compatYears = (string) $__fromY;
                                } elseif (!empty($__toY)) {
                                    $__compatYears = (string) $__toY;
                                }
                            @endphp
                            <div><span class="meta-label">Compatible:</span> <span class="meta-value">{{ $__compatYears !== '' ? $__compatYears : '—' }}</span></div>
                        </div>

                        <div class="lp-mobile-card__bottom">
                            <div class="lp-mobile-card__label">{{ $sellerTypeLabel }}</div>
                            <div class="lp-mobile-card__pricecol">
                                <div class="lp-mobile-card__price">
                                    @if(!is_null($numericPrice))
                                        €{{ number_format($numericPrice, 0, '.', ',') }}
                                    @endif
                                </div>

                                @if ($isDealerSeller && !empty($part->warranty_months))
                                    @php
                                        $__wm = (int) $part->warranty_months;
                                        $__wLabel = '';
                                        if ($__wm > 0 && $__wm % 12 === 0) {
                                            $__years = (int) ($__wm / 12);
                                            $__wLabel = $__years . ' ' . ($__years === 1 ? 'Year' : 'Years') . ' Warranty';
                                        } else {
                                            $__wLabel = $__wm . ' ' . ($__wm === 1 ? 'Month' : 'Months') . ' Warranty';
                                        }
                                    @endphp
                                    <div class="lp-mobile-card__warranty">{{ $__wLabel }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="lp-mobile__empty">{{ __('translate.No Item Found') }}</div>
            @endforelse
        </div>
    </div>

    @push('style_section')
    <style>
        .dealer-mobile-card-link{
            display:none;
        }
        .lp-mobile-card__media{
            position:relative;
        }
        @media (max-width: 991.98px){
            .lp-mobile-card{
                position:relative;
            }
            .dealer-mobile-card-link{
                display:block;
                position:absolute;
                inset:0;
                z-index:1;
            }
            .lp-mobile-card__call{
                z-index:3;
            }
        }
        .listing-list-card{
            position:relative;
        }
        .listing-card-overlay{
            position:absolute;
            inset:0;
            z-index:0;
            display:block;
        }
        .listing-list-card .listing-list-media,
        .listing-list-card .listing-list-content{
            position:relative;
            z-index:1;
        }
        .listing-list-card .listing-list-top-actions{
            z-index:3;
        }
        .listing-list-card .listing-call-btn{
            position:relative;
            z-index:3;
        }
        .listing-list-content[data-href]{
            cursor:pointer;
        }
    </style>
    @endpush

    <div class="d-none d-md-block">
    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
            <div class="col-lg-12">
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('Part Ad') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Part Ad') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="inventory feature-two">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <form action="{{ route('car-parts') }}" method="GET" id="search_form">
                        <div class="inventory-main-box">
                            <div class="inventory-taitel">
                                <h5>{{ __('translate.Select Brand') }}</h5>
                            </div>

                            <div class="location-box">
                                <select class="form-control select2" name="brand_id" data-model-source="part" data-model-target="#car_parts_desktop_model">
                                    <option value="">{{ __('translate.Select Brand') }}</option>
                                    @foreach ($brands as $brandSlug => $brandLabel)
                                        <option {{ request()->get('brand_id') === $brandSlug ? 'selected' : '' }} value="{{ $brandSlug }}">{{ $brandLabel }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="location-box">
                                <select class="form-control select2" name="model" id="car_parts_desktop_model" data-placeholder="Select brand model" data-selected="{{ request()->get('model') }}" {{ request()->get('brand_id') ? '' : 'disabled' }}>
                                    <option value="">Select brand model</option>
                                    @foreach (($selectedPartModels ?? []) as $modelOption)
                                        <option value="{{ $modelOption }}" {{ request()->get('model') === $modelOption ? 'selected' : '' }}>{{ $modelOption }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="inventory-taitel mt-20px">
                                <h5>Year</h5>
                            </div>

                            <div class="location-box">
                                <select class="form-control select2" name="from_year">
                                    <option value="">From Year</option>
                                    @for($y = 1990; $y <= 2026; $y++)
                                        <option value="{{ $y }}" {{ request()->get('from_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="location-box">
                                <select class="form-control select2" name="to_year">
                                    <option value="">To Year</option>
                                    @for($y = 1990; $y <= 2026; $y++)
                                        <option value="{{ $y }}" {{ request()->get('to_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="inventory-taitel mt-20px">
                                <h5>Search by keyword</h5>
                            </div>

                            <div class="location-box">
                                <input type="text" class="form-control" name="search" id="inside_form_search" placeholder="search by keyword" value="{{ request()->get('search') }}">
                            </div>

                            <div class="inventory-taitel mt-20px">
                                <h5>{{ __('translate.Price') }}</h5>
                            </div>

                            <div class="location-box">
                                <select class="form-control select2" name="min_price">
                                    <option value="">Min Price</option>
                                    @for($p = 500; $p <= 500000; $p += 500)
                                        <option value="{{ $p }}" {{ request()->get('min_price') == $p ? 'selected' : '' }}>€{{ number_format($p, 0, '.', ',') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="location-box">
                                <select class="form-control select2" name="max_price">
                                    <option value="">Max Price</option>
                                    @for($p = 500; $p <= 500000; $p += 500)
                                        <option value="{{ $p }}" {{ request()->get('max_price') == $p ? 'selected' : '' }}>€{{ number_format($p, 0, '.', ',') }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="search-here-btn">
                                <button type="submit" class="thm-btn-two">{{ __('translate.Search Here') }}</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-9">
                    

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="row g-5">
                                @forelse ($car_parts as $part)
                                    <div class="col-12">
                                            @php
                                    $agent = $part?->agent;
                                    $partTranslation = $part?->translations?->firstWhere('lang_code', front_lang())
                                        ?? $part?->translations?->firstWhere('lang_code', 'en');
                                    $dealerFlagRaw = $agent?->is_dealer ?? null;
                                                $dealerFlagNorm = strtolower(trim((string) $dealerFlagRaw));
                                                $isDealerSeller = in_array($dealerFlagNorm, ['1', 'true', 'yes'], true);
                                                $isPartSeller = (bool) ($agent?->is_part_seller ?? false);
                                                $sellerName = $isDealerSeller && $isPartSeller
                                                    ? html_decode($agent?->part_company_name)
                                                    : html_decode($agent?->name);
                                                $sellerTypeLabel = $isDealerSeller
                                        ? ($isPartSeller ? 'VEHICLE PART SELLER' : 'DEALER')
                                        : 'PRIVATE';
                                    $sellerLocation = null;
                                    if (!empty($part?->city_id)) {
                                        $sellerLocation = trim((string) ($cityNameMap[$part->city_id] ?? ''));
                                    }
                                    if (!$sellerLocation && !empty($agent?->city_id)) {
                                        $sellerLocation = trim((string) ($cityNameMap[$agent->city_id] ?? ''));
                                    }
                                @endphp

                                        <div class="listing-list-card {{ $isDealerSeller ? 'has-seller-bar' : '' }}">
                                            @if ($isDealerSeller)
                                                <a href="{{ route('car-part', $part->slug) }}" class="listing-list-seller" style="display:flex;justify-content:space-between;align-items:center;gap:12px;text-decoration:none;">
                                                    <span>{{ $sellerName }}</span>
                                                    @if($sellerLocation)
                                                        <span>{{ strtoupper($sellerLocation) }}</span>
                                                    @endif
                                                </a>
                                            @endif

                                            <div class="listing-list-media">
                                                <a href="{{ route('car-part', $part->slug) }}">
                                                    <img src="{{ getImageOrPlaceholder($part->thumb_image, '330x215') }}" alt="thumb">
                                                </a>

                                                @guest('web')
                                                    <a href="javascript:;" class="listing-list-fav before_auth_wishlist" aria-label="wishlist">
                                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z" stroke-width="1.3" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </a>
                                                @else
                                                    @php
                                                        $isPartInWishlist = App\Models\Wishlist::where('car_part_id', $part->id)->where('user_id', Auth::user()->id)->first();
                                                    @endphp
                                                    <a href="{{ route('user.add-car-part-to-wishlist', $part->id) }}" class="listing-list-fav {{ $isPartInWishlist ? 'active' : '' }}" aria-label="wishlist">
                                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="{{ $isPartInWishlist ? 'currentColor' : 'none' }}" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z" stroke-width="1.3" stroke-linejoin="round"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="listing-list-content {{ $isDealerSeller ? 'is-dealer' : 'is-private' }}" data-href="{{ route('car-part', $part->slug) }}">
                                                <div class="listing-list-top-actions">
                                                    @php
                                                        $partAgentPhone = preg_replace('/\s+/', '', (string) ($part?->agent?->phone ?? ''));
                                                    @endphp
                                                    @if ($partAgentPhone)
                                                        <a class="listing-call-btn" href="tel:{{ $partAgentPhone }}" data-phone="{{ $partAgentPhone }}">{{ __('CALL') }}</a>
                                                    @endif
                                                </div>

                                                <div class="listing-list-inner">
                                                    <div class="listing-list-info">
                                                        <a href="{{ route('car-part', $part->slug) }}" class="listing-list-title">
                                                            {{ html_decode($partTranslation?->title) }}
                                                        </a>

                                                        <div class="listing-list-meta">
                                                            <span><span class="meta-label">Brand:</span> <span class="meta-value">{{ !empty($part?->brand?->name) ? html_decode($part?->brand?->name) : '—' }}{{ !empty($part?->car_model) ? ' ' . html_decode($part?->car_model) : '' }}</span></span>
                                                            <span><span class="meta-label">Condition:</span> <span class="meta-value">{{ !empty($part->condition) ? html_decode($part->condition) : '—' }}</span></span>
                                                            <span><span class="meta-label">Part Number:</span> <span class="meta-value">{{ !empty($part->part_number) ? html_decode($part->part_number) : '—' }}</span></span>
                                                            @php
                                                                $__fromY = $part->from_year;
                                                                $__toY = $part->to_year;
                                                                $__compatYears = '';
                                                                if (!empty($__fromY) && !empty($__toY)) {
                                                                    $__compatYears = $__fromY . '-' . $__toY;
                                                                } elseif (!empty($__fromY)) {
                                                                    $__compatYears = (string) $__fromY;
                                                                } elseif (!empty($__toY)) {
                                                                    $__compatYears = (string) $__toY;
                                                                }
                                                            @endphp
                                                            <span><span class="meta-label">Compatible:</span> <span class="meta-value">{{ $__compatYears !== '' ? $__compatYears : '—' }}</span></span>
                                                        </div>
                                                    </div>

                                                    <div class="listing-list-pricecol {{ ($isDealerSeller && !empty($part->warranty_months)) ? 'has-warranty' : 'no-warranty' }}">
                                                        <div class="listing-price">
                                                            @php
                                                                $rawPrice = $part->offer_price ?: $part->regular_price;
                                                                $numericPrice = is_numeric($rawPrice) ? (float) $rawPrice : null;
                                                            @endphp
                                                            @if (!is_null($numericPrice))
                                                                €{{ number_format($numericPrice, 0, '.', ',') }}
                                                            @endif
                                                        </div>

                                                        @if ($isDealerSeller && !empty($part->warranty_months))
                                                            @php
                                                                $__wm = (int) $part->warranty_months;
                                                                $__wLabel = '';
                                                                if ($__wm > 0 && $__wm % 12 === 0) {
                                                                    $__years = (int) ($__wm / 12);
                                                                    $__wLabel = $__years . ' ' . ($__years === 1 ? 'Year' : 'Years') . ' Warranty';
                                                                } else {
                                                                    $__wLabel = $__wm . ' ' . ($__wm === 1 ? 'Month' : 'Months') . ' Warranty';
                                                                }
                                                            @endphp
                                                            <div style="margin-top: 0px; border: 0px solid #c9c9c9; padding: 6px 10px; font-size: 12px; line-height: 1; color: #666; display: block; width: 100%; text-align: right; box-sizing: border-box;">{{ $__wLabel }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="listing-list-bottom-label">
                                                    @if ($isDealerSeller)
                                                        <span class="listing-dealer-name">{{ $sellerTypeLabel }}</span>
                                                    @else
                                                        <span class="listing-private-name">{{ $sellerTypeLabel }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p>{{ __('translate.No Item Found') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    @if ($car_parts->hasPages())
                        {{ $car_parts->links('pagination_box') }}
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>
    </div>
@endsection

@push('js_section')
<script>
(function () {
    const partModelMaps = {!! $__partBrandModelsJson !!};

    function populateModelSelect(brandSelect) {
        const targetSelector = brandSelect.getAttribute('data-model-target');
        const target = document.querySelector(targetSelector);

        if (!targetSelector || !target) {
            return;
        }

        const selectedBrand = String(brandSelect.value || '');
        const models = partModelMaps[selectedBrand] || [];
        const currentValue = target.getAttribute('data-selected') || target.value || '';
        const placeholder = target.getAttribute('data-placeholder') || 'Select brand model';

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

        if (window.jQuery && window.jQuery.fn.select2 && window.jQuery(target).hasClass('select2-hidden-accessible')) {
            window.jQuery(target).trigger('change.select2');
        }
    }

    document.querySelectorAll('[data-model-source="part"]').forEach(function (brandSelect) {
        brandSelect.addEventListener('change', function () {
            const target = document.querySelector(brandSelect.getAttribute('data-model-target'));
            if (target) {
                target.setAttribute('data-selected', '');
            }
            populateModelSelect(brandSelect);
        });

        populateModelSelect(brandSelect);
    });

    const outsideBtn = document.getElementById('outside_form_btn');
    const outsideInput = document.getElementById('outside_form_search');
    const insideInput = document.getElementById('inside_form_search');
    const form = document.getElementById('search_form');

    if (!outsideBtn || !outsideInput || !insideInput || !form) {
        return;
    }

    outsideBtn.addEventListener('click', function () {
        insideInput.value = outsideInput.value;
        form.submit();
    });
})();
</script>
@endpush

<div class="modal fade" id="callSellerModalParts" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Call Seller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="fw-bold fs-4 mb-1" id="callSellerModalPartsNumber"></p>
                <p class="text-muted small mb-0">On desktop? Copy this number and dial it manually.</p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-success" id="callSellerModalPartsCallBtn" href="#">Call</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('js_section')
<script>
(function(){
    try {
        document.addEventListener('click', function(e){
            var content = e.target && e.target.closest ? e.target.closest('.listing-list-content[data-href]') : null;
            if (!content) return;
            if (e.target.closest('.listing-call-btn') || e.target.closest('a') || e.target.closest('button')) return;
            window.location.href = content.getAttribute('data-href');
        });
    } catch(e){}
})();

(function(){
    try {
        document.addEventListener('click', function(e){
            var btn = e.target && e.target.closest ? e.target.closest('.listing-call-btn[data-phone]') : null;
            if (!btn) return;
            if (window.matchMedia && window.matchMedia('(min-width: 768px)').matches) {
                var phone = btn.getAttribute('data-phone') || '';
                var modalEl = document.getElementById('callSellerModalParts');
                if (!modalEl) return;
                document.getElementById('callSellerModalPartsNumber').textContent = phone;
                document.getElementById('callSellerModalPartsCallBtn').href = 'tel:' + phone;
                if (window.bootstrap && bootstrap.Modal) {
                    e.preventDefault();
                    try { if (modalEl.parentElement !== document.body) document.body.appendChild(modalEl); } catch(ex){}
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
            }
        }, true);
    } catch(e){}
})();

(function(){
    window.addEventListener('pageshow', function(e){
        if (e.persisted) { window.location.reload(); }
    });
})();
</script>
@endpush
