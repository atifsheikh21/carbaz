@props([
    'title',
    'partDescription',
    'engineType',
    'mileage',
    'nct',
    'price',
    'location',
    'imageUrl',
    'isDealer' => false,
    'dealerName' => null,
    'hasWarranty' => false,
    'warrantyMonths' => null,
    'callHref' => null,
    'dealerLogoUrl' => null,
])

<div class="car-card">
    <div class="car-card__media">
        <img class="car-card__image" src="{{ $imageUrl }}" alt="{{ $title }}">

        <div class="car-card__heart" aria-hidden="true">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 21s-6.716-4.37-9.333-8.2C.667 9.8 1.2 6.533 3.6 4.8c2.133-1.533 5.2-1.067 6.933 1.133L12 7.2l1.467-1.267c1.733-2.2 4.8-2.666 6.933-1.133 2.4 1.733 2.933 5 0.933 8C18.716 16.63 12 21 12 21z" fill="#ff8a80"/>
            </svg>
        </div>

        <div class="car-card__location">{{ strtoupper($location) }}</div>

        @if (!empty($dealerLogoUrl))
            <img class="car-card__dealer-logo" src="{{ $dealerLogoUrl }}" alt="">
        @endif
    </div>

    <div class="car-card__v-divider" aria-hidden="true"></div>

    <div class="car-card__details">
        <div class="car-card__call">
            @if (!empty($callHref))
                <a class="car-card__call-link" href="{{ $callHref }}">CALL</a>
            @else
                <span class="car-card__call-link">CALL</span>
            @endif
        </div>

        <div class="car-card__content">
            <div class="car-card__info">
                <div class="car-card__title">{{ $title }}</div>

                <div class="car-card__specs">
                    <div class="car-card__spec">{{ $partDescription }}</div>
                    <div class="car-card__spec">{{ $engineType }}</div>
                    <div class="car-card__spec">{{ $mileage }}</div>
                    <div class="car-card__spec">{{ $nct }}</div>
                </div>

                <div class="car-card__seller">
                    @if ($isDealer)
                        <div class="car-card__dealer-badge">DEALER</div>
                        @if (!empty($dealerName))
                            <div class="car-card__dealer-name">{{ strtoupper($dealerName) }}</div>
                        @endif
                    @else
                        <div class="car-card__private">Private</div>
                    @endif
                </div>
            </div>

            <div class="car-card__price-block">
                <div class="car-card__price">€{{ number_format((float) $price, 0, '.', ',') }}</div>

                @if ($isDealer)
                    @if ($hasWarranty && !empty($warrantyMonths))
                        <div class="car-card__warranty">{{ (int) $warrantyMonths }} Month Warranty</div>
                    @elseif ($hasWarranty)
                        <div class="car-card__warranty">Warranty</div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
