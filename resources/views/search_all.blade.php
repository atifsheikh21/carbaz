@extends('layout')

@section('title')
    <title>Search</title>
    <meta name="title" content="Search">
    <meta name="description" content="Search cars and car parts">
@endsection

@section('body-content')
<main>
    <div class="container py-4">
        <div class="d-block d-md-none">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-3">Search Results</h2>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Cars</h3>
                <a href="{{ route('listings', request()->all()) }}" class="btn btn-sm btn-outline-primary">View all cars</a>
            </div>

            <div class="lp-mobile__list">
                @php
                    $cityNameMap = [];
                    try {
                        $cityIds = $cars->pluck('city_id')->filter()->unique()->values();
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
                @forelse ($cars as $car)
                    @php
                        $dealer = $car?->dealer;
                        $dealerFlagRaw = $car?->dealer?->is_dealer ?? null;
                        $dealerFlagNorm = strtolower(trim((string) $dealerFlagRaw));
                        $sellerTypeNorm = strtolower(trim((string) ($car->seller_type ?? '')));
                        $isDealerSeller = in_array($dealerFlagNorm, ['1', 'true', 'yes'], true) || str_contains($sellerTypeNorm, 'dealer');
                        $isVehicleSeller = (bool) ($dealer?->is_vehicle_seller ?? false);
                        $sellerDisplayName = $isDealerSeller && $isVehicleSeller
                            ? html_decode($dealer?->vehicle_company_name)
                            : html_decode($dealer?->name);
                        $sellerName = strtoupper(trim((string) $sellerDisplayName));
                        $sellerLocation = null;
                        if (!empty($car?->city_id)) {
                            $sellerLocation = strtoupper((string) ($cityNameMap[$car->city_id] ?? ''));
                        }
                        $sellerPhone = preg_replace('/\s+/', '', (string) ($car?->dealer?->phone ?? ''));
                        $rawPrice = $car->offer_price ?: $car->regular_price;
                        $numericPrice = is_numeric($rawPrice) ? (float) $rawPrice : null;
                    @endphp
                    <div class="lp-mobile-card">
                        <a class="dealer-mobile-card-link" href="{{ route('listing', $car->slug) }}" aria-label="{{ strtoupper(trim((string) html_decode($car->title))) }}"></a>

                        <div class="lp-mobile-card__top">
                            <div class="lp-mobile-card__top-left">{{ $sellerName !== '' ? $sellerName : ' ' }}</div>
                            <div class="lp-mobile-card__top-right">{{ $sellerLocation ?: ' ' }}</div>
                        </div>

                        <div class="lp-mobile-card__media">
                            <img src="{{ getImageOrPlaceholder($car->thumb_image, '640x480') }}" alt="thumb">
                        </div>

                        <div class="lp-mobile-card__body">
                            <div class="lp-mobile-card__title">{{ strtoupper(trim((string) html_decode($car->title))) }}</div>
                            <div class="lp-mobile-card__call">
                                @if($sellerPhone)
                                    <a href="tel:{{ $sellerPhone }}">CALL</a>
                                @endif
                            </div>

                            <div class="lp-mobile-card__bottom">
                                <div class="lp-mobile-card__label">{{ $isDealerSeller ? 'DEALER' : 'PRIVATE' }}</div>
                                <div>
                                    <div class="lp-mobile-card__price">
                                        @if(!is_null($numericPrice))
                                            €{{ number_format($numericPrice, 0, '.', ',') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="lp-mobile__empty">No cars found.</div>
                @endforelse
            </div>

            @if ($cars->hasPages())
                <div class="py-3">
                    {{ $cars->links('pagination_box') }}
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3 class="mb-0">Car Parts</h3>
                <a href="{{ route('car-parts', request()->all()) }}" class="btn btn-sm btn-outline-primary">View all parts</a>
            </div>

            <div class="lp-mobile__list">
                @php
                    $cityNameMapParts = [];
                    try {
                        $cityIdsParts = $car_parts->pluck('city_id')
                            ->merge($car_parts->pluck('agent.city_id'))
                            ->filter()
                            ->unique()
                            ->values();

                        if ($cityIdsParts->count() > 0) {
                            $cityRowsParts = Modules\City\Entities\City::whereIn('id', $cityIdsParts)->get();
                            foreach ($cityRowsParts as $cityRow) {
                                $cityNameMapParts[$cityRow->id] = (string) $cityRow->name;
                            }
                        }
                    } catch (\Throwable $e) {
                        $cityNameMapParts = [];
                    }
                @endphp
                @forelse ($car_parts as $part)
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
                        $sellerPhone = preg_replace('/\s+/', '', (string) ($agent?->phone ?? ''));
                        $rawPrice = $part->offer_price ?: $part->regular_price;
                        $numericPrice = is_numeric($rawPrice) ? (float) $rawPrice : null;
                        $sellerLocation = null;
                        if (!empty($part?->city_id)) {
                            $sellerLocation = strtoupper((string) ($cityNameMapParts[$part->city_id] ?? ''));
                        }
                        if (!$sellerLocation && !empty($agent?->city_id)) {
                            $sellerLocation = strtoupper((string) ($cityNameMapParts[$agent->city_id] ?? ''));
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
                        </div>

                        <div class="lp-mobile-card__body">
                            <div class="lp-mobile-card__title">{{ strtoupper(trim((string) html_decode($partTranslation?->title))) }}</div>
                            <div class="lp-mobile-card__call">
                                @if($sellerPhone)
                                    <a href="tel:{{ $sellerPhone }}">CALL</a>
                                @endif
                            </div>

                            <div class="lp-mobile-card__bottom">
                                <div class="lp-mobile-card__label">{{ $sellerTypeLabel }}</div>
                                <div>
                                    <div class="lp-mobile-card__price">
                                        @if(!is_null($numericPrice))
                                            €{{ number_format($numericPrice, 0, '.', ',') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="lp-mobile__empty">No car parts found.</div>
                @endforelse
            </div>

            @if ($car_parts->hasPages())
                <div class="py-3">
                    {{ $car_parts->links('pagination_box') }}
                </div>
            @endif
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
        <div class="row">
            <div class="col-12">
                <h2 class="mb-3">Search Results</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">Cars</h3>
                    <a href="{{ route('listings', request()->all()) }}" class="btn btn-sm btn-outline-primary">View all cars</a>
                </div>

                @forelse ($cars as $car)
                    <div class="listing-list-card mb-3">
                        <div class="listing-list-media">
                            <a href="{{ route('listing', $car->slug) }}">
                                <img src="{{ getImageOrPlaceholder($car->thumb_image, '330x215') }}" alt="thumb">
                            </a>
                        </div>

                        <div class="listing-list-content">
                            <div class="listing-list-inner">
                                <div class="listing-list-info">
                                    <a href="{{ route('listing', $car->slug) }}" class="listing-list-title">
                                        {{ html_decode($car->title) }}
                                    </a>
                                </div>

                                <div class="listing-list-pricecol">
                                    <div class="listing-price">
                                        @php
                                            $rawPrice = $car->offer_price ?: $car->regular_price;
                                            $numericPrice = is_numeric($rawPrice) ? (float) $rawPrice : null;
                                        @endphp
                                        @if (!is_null($numericPrice))
                                            €{{ number_format($numericPrice, 0, '.', ',') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>No cars found.</p>
                @endforelse

                @if ($cars->hasPages())
                    {{ $cars->links('pagination_box') }}
                @endif
            </div>

            <div class="col-12 col-lg-6 mt-4 mt-lg-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">Car Parts</h3>
                    <a href="{{ route('car-parts', request()->all()) }}" class="btn btn-sm btn-outline-primary">View all parts</a>
                </div>

                @forelse ($car_parts as $part)
                    @php
                        $partTranslation = $part?->translations?->firstWhere('lang_code', front_lang())
                            ?? $part?->translations?->firstWhere('lang_code', 'en');
                    @endphp
                    <div class="listing-list-card mb-3">
                        <div class="listing-list-media">
                            <a href="{{ route('car-part', $part->slug) }}">
                                <img src="{{ getImageOrPlaceholder($part->thumb_image, '330x215') }}" alt="thumb">
                            </a>
                        </div>

                        <div class="listing-list-content">
                            <div class="listing-list-inner">
                                <div class="listing-list-info">
                                    <a href="{{ route('car-part', $part->slug) }}" class="listing-list-title">
                                        {{ html_decode($partTranslation?->title) }}
                                    </a>
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
                        </div>
                    </div>
                @empty
                    <p>No car parts found.</p>
                @endforelse

                @if ($car_parts->hasPages())
                    {{ $car_parts->links('pagination_box') }}
                @endif
            </div>
        </div>
        </div>
    </div>
</main>
@endsection
