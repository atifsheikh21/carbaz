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
                            <a href="{{ route('user.car-part.create') }}" class="thm-btn-two">{{ __('translate.Create New') }}</a>
                        </div>

                        <div class="car_list_table" style="margin-top:12px;">
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
                                            $t = $p->translate;
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

                        {{ $carParts->links('listing_paginate') }}
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection
