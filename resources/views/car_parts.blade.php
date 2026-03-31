@extends('layout')
@section('title')
    <title>{{ __('translate.Car Parts') }}</title>
@endsection

@section('body-content')
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
                            <label class="form-label">Min Price</label>
                            <input class="form-control" type="number" step="0.01" name="min_price" value="{{ request()->get('min_price') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Max Price</label>
                            <input class="form-control" type="number" step="0.01" name="max_price" value="{{ request()->get('max_price') }}">
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
                        ? ($isPartSeller ? 'CAR PART SELLER' : 'DEALER')
                        : 'PRIVATE';
                    $picsCount = (int) ($part->galleries_count ?? 0);
                    $sellerPhone = preg_replace('/\s+/', '', (string) ($agent?->phone ?? ''));
                    $rawPrice = $part->offer_price ?: $part->regular_price;
                    $numericPrice = is_numeric($rawPrice) ? (float) $rawPrice : null;
                @endphp

                <div class="lp-mobile-card">
                    <a class="dealer-mobile-card-link" href="{{ route('car-part', $part->slug) }}" aria-label="{{ strtoupper(trim((string) html_decode($partTranslation?->title))) }}"></a>
                    <div class="lp-mobile-card__top">
                        <div class="lp-mobile-card__top-left">{{ $sellerName !== '' ? $sellerName : ' ' }}</div>
                        <div class="lp-mobile-card__top-right"> </div>
                    </div>

                    <div class="lp-mobile-card__media">
                        <img src="{{ getImageOrPlaceholder($part->thumb_image, '640x480') }}" alt="thumb">
                        @if($picsCount > 0)
                            <div class="lp-mobile-card__pics">+{{ $picsCount }} PIC</div>
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
                            @if(!empty($part?->brand?->name))
                                <div>{{ html_decode($part?->brand?->name) }}</div>
                            @endif
                            @if(!empty($part->condition))
                                <div>{{ html_decode($part->condition) }}</div>
                            @endif
                            @if(!empty($part->part_number))
                                <div>{{ html_decode($part->part_number) }}</div>
                            @endif
                            @if(!empty($part->compatibility))
                                <div>{{ html_decode($part->compatibility) }}</div>
                            @endif
                        </div>

                        <div class="lp-mobile-card__bottom">
                            <div class="lp-mobile-card__label">{{ $sellerTypeLabel }}</div>
                            <div class="lp-mobile-card__price">
                                @if(!is_null($numericPrice))
                                    €{{ number_format($numericPrice, 0, '.', ',') }}
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
                    <form action="" id="search_form">
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
                                <h5>{{ __('translate.Price') }}</h5>
                            </div>

                            <div class="location-box">
                                <input type="number" class="form-control" name="min_price" placeholder="{{ __('translate.Min Price') }}" value="{{ request()->get('min_price') }}">
                            </div>
                            <div class="location-box">
                                <input type="number" class="form-control" name="max_price" placeholder="{{ __('translate.Max Price') }}" value="{{ request()->get('max_price') }}">
                            </div>

                            <input type="hidden" value="{{ request()->get('search') }}" name="search" id="inside_form_search">

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
                                                    ? ($isPartSeller ? 'CAR PART SELLER' : 'DEALER')
                                                    : 'PRIVATE';
                                            @endphp

                                        <div class="listing-list-card {{ $isDealerSeller ? 'has-seller-bar' : '' }}">
                                            @if ($isDealerSeller)
                                                <div class="listing-list-seller">
                                                    {{ $sellerName }}
                                                </div>
                                            @endif

                                            <div class="listing-list-media">
                                                <a href="{{ route('car-part', $part->slug) }}">
                                                    <img src="{{ getImageOrPlaceholder($part->thumb_image, '330x215') }}" alt="thumb">
                                                </a>
                                            </div>

                                            <div class="listing-list-content {{ $isDealerSeller ? 'is-dealer' : 'is-private' }}">
                                                <div class="listing-list-top-actions">
                                                    @php
                                                        $partAgentPhone = preg_replace('/\s+/', '', (string) ($part?->agent?->phone ?? ''));
                                                    @endphp
                                                    @if ($partAgentPhone)
                                                        <a class="listing-call-btn" href="tel:{{ $partAgentPhone }}">{{ __('CALL') }}</a>
                                                    @endif
                                                </div>

                                                <div class="listing-list-inner">
                                                    <div class="listing-list-info">
                                                        <a href="{{ route('car-part', $part->slug) }}" class="listing-list-title">
                                                            {{ html_decode($partTranslation?->title) }}
                                                        </a>

                                                        <div class="listing-list-meta">
                                                            @if (!empty($part?->brand?->name))
                                                                <span>{{ $part?->brand?->name }}</span>
                                                            @endif
                                                            @if (!empty($part->condition))
                                                                <span>{{ html_decode($part->condition) }}</span>
                                                            @endif
                                                            @if (!empty($part->part_number))
                                                                <span>{{ html_decode($part->part_number) }}</span>
                                                            @endif
                                                            @if (!empty($part->compatibility))
                                                                <span>{{ html_decode($part->compatibility) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="listing-list-pricecol">
                                                        <div class="listing-price">
                                                            @php
                                                                $rawPrice = $part->offer_price ?: $part->regular_price;
                                                                $numericPrice = is_numeric($rawPrice) ? (float) $rawPrice : null;
                                                            @endphp
                                                            @if (!is_null($numericPrice))
                                                                €{{ number_format($numericPrice, 0, '.', ',') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="listing-list-bottom-label">
                                                    @if ($isDealerSeller)
                                                        <span class="listing-dealer-name">{{ $sellerTypeLabel }}</span>
                                                    @else
                                                        <span class="listing-private-name">PRIVATE</span>
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
