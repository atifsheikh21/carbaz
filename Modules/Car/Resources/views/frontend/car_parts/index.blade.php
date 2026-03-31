@extends('layout')
@section('title')
    <title>{{ __('translate.Car Parts') }}</title>
@endsection

@section('body-content')
<main>
    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ asset($breadcrumb) }}) ;"></div>
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

    <section class="dashboard">
        <div class="container">
            <div class="row">
                @include('profile.sidebar')

                <div class="col-lg-9">
                    <div class="manage-car">
                        <div class="d-flex align-items-center justify-content-between" style="gap:12px;">
                            <h4 style="margin:0;">{{ __('translate.Car Parts') }}</h4>
                            @php
                                $__u = Auth::guard('web')->user();
                                $__isDealer = (bool) optional($__u)->is_dealer;
                                $__canSellPart = !$__isDealer || (bool) optional($__u)->is_part_seller;
                            @endphp
                            @if($__canSellPart)
                                <a href="{{ route('user.car-part.create') }}" class="thm-btn-two">{{ __('translate.Create New') }}</a>
                            @endif
                        </div>

                        <div class="d-block d-md-none" style="margin-top:12px;">
                            <div class="mc-mobile">
                                <div class="mc-mobile__tabs" style="margin-top:0;">
                                    <a class="mc-mobile__tab {{ ($status ?? 'all') === 'all' ? 'active' : '' }}" href="{{ route('user.car-part.index', ['status' => 'all']) }}">all ad {{ $totalCount ?? $carParts->total() }}</a>
                                    <a class="mc-mobile__tab {{ ($status ?? 'all') === 'active' ? 'active' : '' }}" href="{{ route('user.car-part.index', ['status' => 'active']) }}">active ad {{ $activeCount ?? '' }}</a>
                                    <a class="mc-mobile__tab {{ ($status ?? 'all') === 'inactive' ? 'active' : '' }}" href="{{ route('user.car-part.index', ['status' => 'inactive']) }}">ad not active {{ $inactiveCount ?? '' }}</a>
                                </div>

                                <div class="mc-mobile__list">
                                    @forelse($carParts as $p)
                                        @php
                                            $t = $p->translations?->firstWhere('lang_code', admin_lang())
                                                ?? $p->translations?->firstWhere('lang_code', 'en');
                                        @endphp
                                        <div class="mc-mobile__row">
                                            <div class="mc-mobile__img">
                                                <img src="{{ getImageOrPlaceholder($p->thumb_image, '120x90') }}" alt="thumb">
                                            </div>

                                            <div class="mc-mobile__body">
                                                <div class="mc-mobile__title">{{ html_decode($t?->title) }}</div>
                                                <div class="mc-mobile__actions">
                                                    <button type="button" class="mc-mobile__action" onclick="event.preventDefault(); document.getElementById('delete_part_{{ $p->id }}').submit();">remove</button>
                                                    <a class="mc-mobile__action" href="{{ route('user.car-part.edit', $p->id) }}">edit</a>
                                                </div>

                                                <form id="delete_part_{{ $p->id }}" action="{{ route('user.car-part.destroy', $p->id) }}" method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>

                                            <div class="mc-mobile__price">
                                                @if ($p->offer_price)
                                                    {{ currency($p->offer_price) }}
                                                @else
                                                    {{ currency($p->regular_price) }}
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-3" style="color:#8b8b8b;">{{ __('translate.No Data Found') }}</div>
                                    @endforelse
                                </div>

                                @if ($carParts->hasPages())
                                    <div class="py-3">
                                        {{ $carParts->links('listing_paginate') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="car_list_table d-none d-md-block" style="margin-top:12px;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('translate.Title') }}</th>
                                        <th>{{ __('translate.Brand') }}</th>
                                        <th>{{ __('translate.Price') }}</th>
                                        <th>{{ __('translate.Status') }}</th>
                                        <th class="text-end">{{ __('translate.Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($carParts as $p)
                                        @php
                                            $t = $p->translations?->firstWhere('lang_code', admin_lang())
                                                ?? $p->translations?->firstWhere('lang_code', 'en');
                                        @endphp
                                        <tr>
                                            <td>{{ html_decode($t?->title) }}</td>
                                            <td>{{ html_decode($p?->brand?->name) }}</td>
                                            <td>
                                                @if ($p->offer_price)
                                                    {{ currency($p->offer_price) }}
                                                @else
                                                    {{ currency($p->regular_price) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($p->approved_by_admin == 'approved')
                                                    <button class="no yes">{{ __('translate.Active') }}</button>
                                                @else
                                                    <button class="no">{{ __('translate.Awaiting') }}</button>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('user.car-part.edit', $p->id) }}" class="thm-btn-two">{{ __('translate.Edit') }}</a>
                                                <button type="button" class="thm-btn-two" onclick="event.preventDefault(); document.getElementById('delete_part_{{ $p->id }}').submit();">{{ __('translate.Delete') }}</button>
                                                <form id="delete_part_{{ $p->id }}" action="{{ route('user.car-part.destroy', $p->id) }}" method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">{{ __('translate.No Data Found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-none d-md-block">
                            {{ $carParts->links('listing_paginate') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection
