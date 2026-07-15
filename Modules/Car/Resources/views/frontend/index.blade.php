@extends('layout')
@section('title')
    <title>{{ __('translate.Car List') }}</title>
@endsection
@section('body-content')

<main>
    <style>
        .mc-desktop-tabs{
            margin-top: 12px;
        }
        .mc-desktop-tabs .mc-mobile__tabs{
            display: inline-flex;
            flex-wrap: wrap;
            gap: 10px;
            background: #f6f7ff;
            border: 1px solid #e6e8ff;
            padding: 8px;
            border-radius: 14px;
        }
        .mc-desktop-tabs .mc-mobile__tab{
            border-radius: 12px;
            padding: 10px 14px;
            background: transparent;
            border: 1px solid transparent;
            font-weight: 600;
            line-height: 1;
        }
        .mc-desktop-tabs .mc-mobile__tab.active{
            background: #ffffff;
            border-color: #dfe3ff;
            box-shadow: 0 6px 18px rgba(16, 24, 40, 0.08);
        }
        .mc-mobile__list .mc-mobile__row{margin:12px 0;}
        .car_list_table .mc-mobile__list .mc-mobile__row{
            border:1px solid #e5e7eb;
            border-radius:10px;
            padding:10px 12px;
            background:#dddada;
        }
        button.mc-mobile__action{
            background: transparent;
            border: 0;
            padding: 0;
            box-shadow: none;
            color: inherit;
            font: inherit;
            line-height: inherit;
            text-decoration: inherit;
            cursor: pointer;
        }
        button.mc-mobile__action:focus{
            outline: none;
        }
    </style>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ asset($breadcrumb) }}) ;"></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('Vehicle Ad') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Vehicle Ad') }}</li>
                    </ol>
                </nav>
            </div>
            </div>
        </div>
    </section>
    <!-- banner-part-end -->

    <!-- dashboard-part-start -->
    <section class="dashboard">
        <div class="container">
            <div class="row">
                @include('profile.sidebar')


                <div class="col-lg-9">
                    <!-- Manage Car  -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="manage-car">

                                <div class="d-block d-md-none">
                                    <div class="mc-mobile">
                                        <div class="mc-mobile__tabs">
                                            <a class="mc-mobile__tab {{ ($status ?? 'all') === 'all' ? 'active' : '' }}" href="{{ route('user.car.index', ['status' => 'all']) }}">all ad {{ $totalCount ?? $cars->total() }}</a>
                                            <a class="mc-mobile__tab {{ ($status ?? 'all') === 'active' ? 'active' : '' }}" href="{{ route('user.car.index', ['status' => 'active']) }}">active ad {{ $activeCount ?? '' }}</a>
                                            <a class="mc-mobile__tab {{ ($status ?? 'all') === 'inactive' ? 'active' : '' }}" href="{{ route('user.car.index', ['status' => 'inactive']) }}">ad not active {{ $inactiveCount ?? '' }}</a>
                                        </div>

                                        <div class="mc-mobile__list">
                                            @foreach ($cars as $index => $car)
                                                <div class="mc-mobile__row">
                                                    <div class="mc-mobile__img">
                                                        <a href="{{ route('listing', html_decode($car->slug)) }}" class="d-inline-block" aria-label="{{ __('View listing') }}">
                                                            <img src="{{ getImageOrPlaceholder($car->thumb_image, '120x90') }}" alt="thumb">
                                                        </a>
                                                    </div>

                                                    <div class="mc-mobile__body">
                                                        <a href="{{ route('listing', html_decode($car->slug)) }}" class="mc-mobile__title" style="color:inherit;text-decoration:none;display:block;">{{ html_decode($car->title) }}</a>
                                                        <div class="mc-mobile__actions">
                                                            <button type="button" class="mc-mobile__action" onclick="deleteCarForm('remove_car_mobile_{{ $car->id }}');">remove</button>
                                                            <a class="mc-mobile__action" href="{{ route('user.car.edit', ['car' => $car->id, 'lang_code' => admin_lang()] ) }}">edit</a>
                                                            @php
                                                                $isExpired = !empty($car->expired_date) && $car->expired_date < ($today ?? date('Y-m-d'));
                                                                $isActive = $car->approved_by_admin === 'approved' && $car->status === 'enable' && (!$car->expired_date || $car->expired_date >= ($today ?? date('Y-m-d')));
                                                            @endphp
                                                            @php
                                                                $__isDealer = (bool) optional(auth('web')->user())->is_dealer;
                                                            @endphp
                                                            @if($__isDealer)
                                                                <button type="button" class="mc-mobile__action" onclick="event.preventDefault(); document.getElementById('toggle_car_{{ $car->id }}').submit();">{{ $isActive ? 'deactivate' : 'activate' }}</button>
                                                            @else
                                                                @if($isActive)
                                                                    <button type="button" class="mc-mobile__action" onclick="event.preventDefault(); document.getElementById('toggle_car_{{ $car->id }}').submit();">deactivate</button>
                                                                @else
                                                                    <button type="button" class="mc-mobile__action" data-bs-toggle="modal" data-bs-target="#individualAdPayModal" data-reactivate-type="car" data-reactivate-id="{{ $car->id }}">activate</button>
                                                                @endif
                                                            @endif
                                                        </div>

                                                        <form action="{{ route('user.car.destroy', $car->id) }}" id="remove_car_mobile_{{ $car->id }}" class="d-none" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>

                                                        <form action="{{ route('user.car.toggle-status', $car->id) }}" id="toggle_car_{{ $car->id }}" class="d-none" method="POST">
                                                            @csrf
                                                        </form>
                                                    </div>

                                                    <div class="mc-mobile__price">
                                                        @if ($car->offer_price)
                                                            {{ currency($car->offer_price) }}
                                                        @else
                                                            {{ currency($car->regular_price) }}
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if ($cars->hasPages())
                                            <div class="py-3">
                                                {{ $cars->links('listing_paginate') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-none d-md-block mc-desktop-tabs">
                                    <div class="mc-mobile__tabs">
                                        <a class="mc-mobile__tab {{ ($status ?? 'all') === 'all' ? 'active' : '' }}" href="{{ route('user.car.index', ['status' => 'all']) }}">all ad {{ $totalCount ?? $cars->total() }}</a>
                                        <a class="mc-mobile__tab {{ ($status ?? 'all') === 'active' ? 'active' : '' }}" href="{{ route('user.car.index', ['status' => 'active']) }}">active ad {{ $activeCount ?? '' }}</a>
                                        <a class="mc-mobile__tab {{ ($status ?? 'all') === 'inactive' ? 'active' : '' }}" href="{{ route('user.car.index', ['status' => 'inactive']) }}">ad not active {{ $inactiveCount ?? '' }}</a>
                                    </div>
                                </div>

                                <div class="car_list_table d-none d-md-block" style="margin-top:12px;">
                                    <div style="display:grid;grid-template-columns:120px 1fr 260px 140px;gap:16px;align-items:center;padding:10px 12px;border:1px solid #eee;border-radius:10px;background:#f7f7f7;font-weight:700;color:#2b2b2b;">
                                        <div>{{ __('translate.Image') }}</div>
                                        <div>{{ __('translate.Title') }}</div>
                                        <div>{{ __('translate.Actions') }}</div>
                                        <div style="text-align:right;">{{ __('translate.Price') }}</div>
                                    </div>

                                    <div style="margin-top:10px;" class="mc-mobile__list">
                                        @foreach ($cars as $index => $car)
                                            <div class="mc-mobile__row" style="display:grid;grid-template-columns:120px 1fr 260px 140px;gap:16px;align-items:center;">
                                                <div class="mc-mobile__img" style="width:auto;">
                                                    <a href="{{ route('listing', html_decode($car->slug)) }}" class="d-inline-block" aria-label="{{ __('View listing') }}">
                                                        <img src="{{ getImageOrPlaceholder($car->thumb_image, '120x90') }}" alt="thumb" style="width:120px;height:90px;object-fit:cover;border-radius:8px;">
                                                    </a>
                                                </div>

                                                <div class="mc-mobile__body" style="padding:0;">
                                                    <a href="{{ route('listing', html_decode($car->slug)) }}" class="mc-mobile__title" style="margin:0;color:inherit;text-decoration:none;display:block;">{{ html_decode($car->title) }}</a>
                                                </div>

                                                <div class="mc-mobile__actions" style="position:static;z-index:auto;display:flex;gap:14px;justify-content:flex-start;">
                                                    <button type="button" class="mc-mobile__action" onclick="deleteCarForm('remove_car_desktop_{{ $car->id }}');">remove</button>
                                                    <a class="mc-mobile__action" href="{{ route('user.car.edit', ['car' => $car->id, 'lang_code' => admin_lang()] ) }}">edit</a>
                                                    @php
                                                        $isExpired = !empty($car->expired_date) && $car->expired_date < ($today ?? date('Y-m-d'));
                                                        $isActive = $car->approved_by_admin === 'approved' && $car->status === 'enable' && (!$car->expired_date || $car->expired_date >= ($today ?? date('Y-m-d')));
                                                    @endphp
                                                    @php
                                                        $__isDealer = (bool) optional(auth('web')->user())->is_dealer;
                                                    @endphp
                                                    @if($__isDealer)
                                                        <button type="button" class="mc-mobile__action" onclick="event.preventDefault(); document.getElementById('toggle_car_desktop_{{ $car->id }}').submit();">{{ $isActive ? 'deactivate' : 'activate' }}</button>
                                                    @else
                                                        @if($isActive)
                                                            <button type="button" class="mc-mobile__action" onclick="event.preventDefault(); document.getElementById('toggle_car_desktop_{{ $car->id }}').submit();">deactivate</button>
                                                        @else
                                                            <button type="button" class="mc-mobile__action" data-bs-toggle="modal" data-bs-target="#individualAdPayModal" data-reactivate-type="car" data-reactivate-id="{{ $car->id }}">activate</button>
                                                        @endif
                                                    @endif

                                                    <form action="{{ route('user.car.destroy', $car->id) }}" id="remove_car_desktop_{{ $car->id }}" class="d-none" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                    <form action="{{ route('user.car.toggle-status', $car->id) }}" id="toggle_car_desktop_{{ $car->id }}" class="d-none" method="POST">
                                                        @csrf
                                                    </form>
                                                </div>

                                                <div class="mc-mobile__price" style="position:static;z-index:auto;text-align:right;padding:0;">
                                                    @if ($car->offer_price)
                                                        {{ currency($car->offer_price) }}
                                                    @else
                                                        {{ currency($car->regular_price) }}
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>


                                <div class="d-none d-md-block">
                                    {{ $cars->links('listing_paginate') }}
                                </div>


                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        </div>
    </section>

    <!-- dashboard-part-end -->

    @include('profile.logout')

    <div class="modal fade" id="individualAdPayModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Activate Ad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="color:#6b7280;">To activate this ad you need to pay the per-ad fee.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('pay-individual-ad-via-worldpay') }}" id="individualAdPayForm">
                        @csrf
                        <input type="hidden" name="redirect_url" id="individualAdRedirectUrl" value="">
                        <button type="submit" class="btn btn-danger">Pay & Activate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</main>

@endsection

@push('js_section')
<script src="{{ asset('global/sweetalert/sweetalert2@11.js') }}"></script>


<script>
    "use strict";

    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('individualAdPayModal');
        if (modal) {
            modal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var type = button ? button.getAttribute('data-reactivate-type') : null;
                var id = button ? button.getAttribute('data-reactivate-id') : null;
                var input = document.getElementById('individualAdRedirectUrl');
                if (input && type && id) {
                    input.value = "{{ url('user/car') }}" + "?status=inactive&reactivate_" + type + "=" + encodeURIComponent(id);
                }
            });
        }

        var params = new URLSearchParams(window.location.search);
        var reactivateCar = params.get('reactivate_car');
        if (reactivateCar) {
            var form = document.getElementById('toggle_car_desktop_' + reactivateCar) || document.getElementById('toggle_car_' + reactivateCar);
            if (form) {
                form.submit();
            }
        }
    });
        function deleteCarForm(formId){
            Swal.fire({
                title: "{{__('Are you realy want to delete this item ?')}}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, Delete It')}}",
                cancelButtonText: "{{__('Cancel')}}",
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(formId);
                    if (form) {
                        form.submit();
                    }
                }
            })
        }
    </script>


@endpush
