@extends('layout')
@section('title')
    <title>{{ __('translate.Car Parts') }}</title>
@endsection

@section('body-content')
<main>
    <style>
        .mc-mobile__row{
            position: relative;
        }
        .mc-mobile__row-link{
            display:block;
            position:absolute;
            inset:0;
            z-index:1;
        }
        .mc-mobile__actions,
        .mc-mobile__price{
            position: relative;
            z-index:2;
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
        .mc-actions{
            display: inline-flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }
        .mc-actions .thm-btn-two{
            min-width: 0;
            padding: 10px 14px;
            line-height: 1;
        }
        .mc-actions .mc-btn-delete{
            border: 0;
        }
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
    </style>
    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ asset($breadcrumb) }}) ;"></div>
        <div class="container">
            <div class="col-lg-12">
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('Vehicle Part Ad / Accessories') }}</h1>
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
                            <h4 style="margin:0;">{{ __('') }}</h4>
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
                                            <a class="mc-mobile__row-link" href="{{ route('car-part', $p->slug) }}" aria-label="{{ html_decode($t?->title) }}"></a>
                                            <div class="mc-mobile__img">
                                                <a href="{{ route('car-part', $p->slug) }}" class="d-inline-block" aria-label="{{ __('View listing') }}">
                                                    <img src="{{ getImageOrPlaceholder($p->thumb_image, '120x90') }}" alt="thumb">
                                                </a>
                                            </div>

                                            <div class="mc-mobile__body">
                                                <a href="{{ route('car-part', $p->slug) }}" class="mc-mobile__title" style="color:inherit;text-decoration:none;display:block;">{{ html_decode($t?->title) }}</a>
                                                <div class="mc-mobile__actions">
                                                    <button type="button" class="mc-mobile__action" onclick="event.preventDefault(); event.stopPropagation(); deleteCarPart('delete_part_mobile_{{ $p->id }}');">remove</button>
                                                    <a class="mc-mobile__action" href="{{ route('user.car-part.edit', $p->id) }}" onclick="event.stopPropagation();">edit</a>
                                                    @php
                                                        $today = $today ?? date('Y-m-d');
                                                        $isExpired = !empty($p->expired_date) && $p->expired_date->format('Y-m-d') < $today;
                                                        $isActive = $p->approved_by_admin === 'approved' && $p->status === 'enable' && (!$p->expired_date || $p->expired_date->format('Y-m-d') >= $today);
                                                        $__isDealer = (bool) optional(auth('web')->user())->is_dealer;
                                                    @endphp
                                                    @if($__isDealer)
                                                        <button type="button" class="mc-mobile__action" onclick="event.preventDefault(); event.stopPropagation(); document.getElementById('toggle_part_{{ $p->id }}').submit();">{{ $isActive ? 'deactivate' : 'activate' }}</button>
                                                    @else
                                                        @if($isActive)
                                                            <button type="button" class="mc-mobile__action" onclick="event.preventDefault(); event.stopPropagation(); document.getElementById('toggle_part_{{ $p->id }}').submit();">deactivate</button>
                                                        @else
                                                            <button type="button" class="mc-mobile__action" onclick="event.stopPropagation();" data-bs-toggle="modal" data-bs-target="#individualAdPayModalPart" data-reactivate-type="part" data-reactivate-id="{{ $p->id }}">activate</button>
                                                        @endif
                                                    @endif
                                                </div>

                                                <form id="delete_part_mobile_{{ $p->id }}" action="{{ route('user.car-part.destroy', $p->id) }}" method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                                <form id="toggle_part_{{ $p->id }}" action="{{ route('user.car-part.toggle-status', $p->id) }}" method="POST" class="d-none">
                                                    @csrf
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
                                        <div class="py-3" style="color:#8b8b8b;">{{ __('No Data Found') }}</div>
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
                            <div class="mc-desktop-tabs">
                                <div class="mc-mobile__tabs" style="margin-top:0;">
                                    <a class="mc-mobile__tab {{ ($status ?? 'all') === 'all' ? 'active' : '' }}" href="{{ route('user.car-part.index', ['status' => 'all']) }}">all ad {{ $totalCount ?? $carParts->total() }}</a>
                                    <a class="mc-mobile__tab {{ ($status ?? 'all') === 'active' ? 'active' : '' }}" href="{{ route('user.car-part.index', ['status' => 'active']) }}">active ad {{ $activeCount ?? '' }}</a>
                                    <a class="mc-mobile__tab {{ ($status ?? 'all') === 'inactive' ? 'active' : '' }}" href="{{ route('user.car-part.index', ['status' => 'inactive']) }}">ad not active {{ $inactiveCount ?? '' }}</a>
                                </div>
                            </div>

                            <div style="display:grid;grid-template-columns:120px 1fr 260px 140px;gap:16px;align-items:center;padding:10px 12px;border:1px solid #eee;border-radius:10px;background:#f7f7f7;font-weight:700;color:#2b2b2b;">
                                <div>{{ __('translate.Image') }}</div>
                                <div>{{ __('translate.Title') }}</div>
                                <div>{{ __('translate.Actions') }}</div>
                                <div style="text-align:right;">{{ __('translate.Price') }}</div>
                            </div>

                            <div style="margin-top:10px;" class="mc-mobile__list">
                                @forelse($carParts as $p)
                                    @php
                                        $t = $p->translations?->firstWhere('lang_code', admin_lang())
                                            ?? $p->translations?->firstWhere('lang_code', 'en');
                                    @endphp
                                    <div class="mc-mobile__row" style="display:grid;grid-template-columns:120px 1fr 260px 140px;gap:16px;align-items:center;">
                                        <a class="mc-mobile__row-link" href="{{ route('car-part', $p->slug) }}" aria-label="{{ html_decode($t?->title) }}" style="z-index:1;"></a>

                                        <div class="mc-mobile__img" style="width:auto;position:relative;z-index:2;">
                                            <a href="{{ route('car-part', $p->slug) }}" class="d-inline-block" aria-label="{{ __('View listing') }}">
                                                <img src="{{ getImageOrPlaceholder($p->thumb_image, '120x90') }}" alt="thumb" style="width:120px;height:90px;object-fit:cover;border-radius:8px;">
                                            </a>
                                        </div>

                                        <div class="mc-mobile__body" style="padding:0;position:relative;z-index:2;">
                                            <a href="{{ route('car-part', $p->slug) }}" class="mc-mobile__title" style="margin:0;color:inherit;text-decoration:none;display:block;">{{ html_decode($t?->title) }}</a>
                                        </div>

                                        <div class="mc-mobile__actions" style="position:relative;z-index:2;display:flex;gap:14px;justify-content:flex-start;">
                                            @php
                                                $today = $today ?? date('Y-m-d');
                                                $isExpired = !empty($p->expired_date) && $p->expired_date->format('Y-m-d') < $today;
                                                $isActive = $p->approved_by_admin === 'approved' && $p->status === 'enable' && (!$p->expired_date || $p->expired_date->format('Y-m-d') >= $today);
                                                $__isDealer = (bool) optional(auth('web')->user())->is_dealer;
                                            @endphp

                                            <button type="button" class="mc-mobile__action" onclick="event.preventDefault(); event.stopPropagation(); deleteCarPart('delete_part_desktop_{{ $p->id }}');">remove</button>
                                            <a class="mc-mobile__action" href="{{ route('user.car-part.edit', $p->id) }}" onclick="event.stopPropagation();">edit</a>

                                            @if($__isDealer)
                                                <button type="button" class="mc-mobile__action" onclick="event.preventDefault(); event.stopPropagation(); document.getElementById('toggle_part_desktop_{{ $p->id }}').submit();">{{ $isActive ? 'deactivate' : 'activate' }}</button>
                                            @else
                                                @if($isActive)
                                                    <button type="button" class="mc-mobile__action" onclick="event.preventDefault(); event.stopPropagation(); document.getElementById('toggle_part_desktop_{{ $p->id }}').submit();">deactivate</button>
                                                @else
                                                    <button type="button" class="mc-mobile__action" onclick="event.stopPropagation();" data-bs-toggle="modal" data-bs-target="#individualAdPayModalPart" data-reactivate-type="part" data-reactivate-id="{{ $p->id }}">activate</button>
                                                @endif
                                            @endif

                                            <form id="delete_part_desktop_{{ $p->id }}" action="{{ route('user.car-part.destroy', $p->id) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <form id="toggle_part_desktop_{{ $p->id }}" action="{{ route('user.car-part.toggle-status', $p->id) }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>

                                        <div class="mc-mobile__price" style="position:relative;z-index:2;text-align:right;padding:0;">
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
                        </div>

                        <div class="d-none d-md-block">
                            {{ $carParts->links('listing_paginate') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="individualAdPayModalPart" tabindex="-1" aria-hidden="true">
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
                    <form method="POST" action="{{ route('pay-individual-ad-via-worldpay') }}" id="individualAdPayFormPart">
                        @csrf
                        <input type="hidden" name="redirect_url" id="individualAdRedirectUrlPart" value="">
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
    function deleteCarPart(formId){
        Swal.fire({
            title: "{{ __('Are you realy want to delete this item ?') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{ __('Yes, Delete It') }}",
            cancelButtonText: "{{ __('Cancel') }}",
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById(formId);
                if (form) {
                    form.submit();
                }
            }
        });
    }
</script>
@endpush

@push('js_section')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('individualAdPayModalPart');
        if (modal) {
            modal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var type = button ? button.getAttribute('data-reactivate-type') : null;
                var id = button ? button.getAttribute('data-reactivate-id') : null;
                var input = document.getElementById('individualAdRedirectUrlPart');
                if (input && type && id) {
                    input.value = "{{ url('user/car-part') }}" + "?status=inactive&reactivate_" + type + "=" + encodeURIComponent(id);
                }
            });
        }

        var params = new URLSearchParams(window.location.search);
        var reactivatePart = params.get('reactivate_part');
        if (reactivatePart) {
            var form = document.getElementById('toggle_part_desktop_' + reactivatePart) || document.getElementById('toggle_part_' + reactivatePart);
            if (form) {
                form.submit();
            }
        }
    });
</script>
@endpush

@include('profile.logout')
