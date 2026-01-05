@extends('layout')
@section('title')
    <title>{{ __('translate.Car Parts') }}</title>
@endsection

@section('body-content')
<main>
    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
            <div class="col-lg-12">
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('translate.Car Parts') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Car Parts') }}</li>
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
                                <select class="form-control select2" name="brand_id">
                                    <option value="">{{ __('translate.Select Brand') }}</option>
                                    @foreach ($brands as $brand)
                                        <option {{ request()->get('brand_id') == $brand->id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
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
                    <div class="inventory-ber">
                        <div class="inventory-ber-left">
                            <div class="inventory-sarch-ber-item">
                                <div class="inventory-sarch-ber">
                                    <input type="text" class="form-control" id="outside_form_search" name="search" placeholder="{{ __('translate.Search') }}" value="{{ request()->get('search') }}">
                                    <button id="outside_form_btn" type="button" class="thm-btn-two">{{ __('translate.Search Now') }}</button>
                                </div>

                                <div class="inventory-sarch-ber-text">
                                    <p>{{ __('translate.Switch tab for list or grid view layout') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="inventory-ber-right">
                            <div class="inventory-ber-right-btn">
                                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                                            <span>
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.88404 0.221924H2.58645C1.28267 0.221924 0.22168 1.28292 0.22168 2.5867V6.88375C0.22168 8.18753 1.28267 9.24853 2.58645 9.24853H6.88351C8.18729 9.24853 9.24828 8.18753 9.24828 6.88375V2.5867C9.24881 1.28292 8.18781 0.221924 6.88404 0.221924ZM7.67229 6.88428C7.67229 7.31887 7.31863 7.67254 6.88404 7.67254H2.58645C2.15186 7.67254 1.7982 7.31887 1.7982 6.88428V2.58722C1.7982 2.15263 2.15186 1.79897 2.58645 1.79897H6.88351C7.3181 1.79897 7.67177 2.15263 7.67177 2.58722L7.67229 6.88428ZM17.5161 0.221924H13.2185C11.9147 0.221924 10.8537 1.28292 10.8537 2.5867V6.88375C10.8537 8.18753 11.9147 9.24853 13.2185 9.24853H17.5161C18.8198 9.24853 19.8808 8.18753 19.8808 6.88375V2.5867C19.8808 1.28292 18.8204 0.221924 17.5161 0.221924ZM18.3043 6.88428C18.3043 7.31887 17.9507 7.67254 17.5161 7.67254H13.2185C12.7839 7.67254 12.4302 7.31887 12.4302 6.88428V2.58722C12.4302 2.15263 12.7839 1.79897 13.2185 1.79897H17.5161C17.9507 1.79897 18.3043 2.15263 18.3043 2.58722V6.88428ZM6.88404 10.3479H2.58645C1.28267 10.3479 0.22168 11.4089 0.22168 12.7127V17.0097C0.22168 18.3135 1.28267 19.3745 2.58645 19.3745H6.88351C8.18729 19.3745 9.24828 18.3135 9.24828 17.0097V12.7127C9.24881 11.4084 8.18781 10.3479 6.88404 10.3479ZM7.67229 17.0097C7.67229 17.4443 7.31863 17.798 6.88404 17.798H2.58645C2.15186 17.798 1.7982 17.4443 1.7982 17.0097V12.7127C1.7982 12.2781 2.15186 11.9244 2.58645 11.9244H6.88351C7.3181 11.9244 7.67177 12.2781 7.67177 12.7127L7.67229 17.0097ZM17.5161 10.3479H13.2185C11.9147 10.3479 10.8537 11.4089 10.8537 12.7127V17.0097C10.8537 18.3135 11.9147 19.3745 13.2185 19.3745H16.4293C16.8644 19.3745 17.2176 19.0214 17.2176 18.5862C17.2176 18.1511 16.8644 17.798 16.4293 17.798H13.2185C12.7839 17.798 12.4302 17.4443 12.4302 17.0097V12.7127C12.4302 12.2781 12.7839 11.9244 13.2185 11.9244H17.5161C17.9507 11.9244 18.3043 12.2781 18.3043 12.7127V16.3145C18.3043 16.7496 18.6575 17.1027 19.0926 17.1027C19.5277 17.1027 19.8808 16.7496 19.8808 16.3145V12.7127C19.8808 11.4084 18.8198 10.3479 17.5161 10.3479Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                </svg>
                                            </span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="row g-5">
                                @forelse ($car_parts as $part)
                                    <div class="col-lg-4 col-sm-6 col-md-6">
                                        <div class="brand-car-item">
                                            <div class="brand-car-item-img">
                                                <img src="{{ getImageOrPlaceholder($part->thumb_image, '330x215') }}" alt="thumb">
                                                <div class="brand-car-item-img-text">
                                                    <div class="text-df">
                                                        @if ($part->offer_price)
                                                            <p class="text">{{ calculate_percentage($part->regular_price, $part->offer_price) }}% {{ __('translate.Off') }}</p>
                                                        @endif
                                                        @if ($part->condition == 'New')
                                                            <p class="text text-two">{{ __('translate.New') }}</p>
                                                        @else
                                                            <p class="text text-two">{{ __('translate.Used') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="brand-car-inner">
                                                <div class="brand-car-inner-item">
                                                    <span>{{ $part?->brand?->name }}</span>
                                                    <p>
                                                        @if ($part->offer_price)
                                                            {{ currency($part->offer_price) }}
                                                        @else
                                                            {{ currency($part->regular_price) }}
                                                        @endif
                                                    </p>
                                                </div>

                                                <a href="{{ route('car-part', $part->slug) }}">
                                                    <h3>{{ html_decode($part->frontTranslate?->title) }}</h3>
                                                </a>
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
@endsection

@push('js_section')
<script>
(function () {
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
