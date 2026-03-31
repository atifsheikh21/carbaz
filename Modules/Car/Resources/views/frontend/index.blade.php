@extends('layout')
@section('title')
    <title>{{ __('translate.Car List') }}</title>
@endsection
@section('body-content')

<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ asset($breadcrumb) }}) ;"></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Car List') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Car List') }}</li>
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
                                                        <img src="{{ getImageOrPlaceholder($car->thumb_image, '120x90') }}" alt="thumb">
                                                    </div>

                                                    <div class="mc-mobile__body">
                                                        <div class="mc-mobile__title">{{ html_decode($car->title) }}</div>
                                                        <div class="mc-mobile__actions">
                                                            <button type="button" class="mc-mobile__action" onclick="deleteCar({{ $car->id }})">remove</button>
                                                            <a class="mc-mobile__action" href="{{ route('user.car.edit', ['car' => $car->id, 'lang_code' => admin_lang()] ) }}">edit</a>
                                                        </div>

                                                        <form action="{{ route('user.car.destroy', $car->id) }}" id="remove_car_{{ $car->id }}" class="d-none" method="POST">
                                                            @csrf
                                                            @method('DELETE')
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

                                <div class="car_list_table d-none d-md-block">
                                    <table class="table">
                                        <thead>
                                            <tr>

                                                <th>{{ __('translate.Image') }}</th>
                                                <th>{{ __('translate.Title') }}</th>
                                                <th>{{ __('translate.Brand') }}</th>
                                                <th>{{ __('translate.Price') }}</th>
                                                <th>{{ __('translate.Featured') }}</th>
                                                <th>{{ __('translate.Status') }}</th>
                                                <th>{{ __('translate.Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cars as $index => $car)
                                                <tr>

                                                    <td>
                                                        <img src="{{ getImageOrPlaceholder($car->thumb_image, '60x60') }}" alt="thumb" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                                                    </td>

                                                    <td>
                                                        {{ html_decode($car->title) }}
                                                    </td>

                                                    <td>{{ $car?->brand?->name }}</td>
                                                    <td>
                                                        @if ($car->offer_price)
                                                            {{ currency($car->offer_price) }}
                                                        @else
                                                            {{ currency($car->regular_price) }}
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if ($car->is_featured == 'enable')
                                                            <button class="no yes">
                                                                {{ __('translate.Yes') }}
                                                            </button>
                                                        @else
                                                            <button class="no">
                                                                {{ __('translate.No') }}
                                                            </button>
                                                        @endif

                                                    </td>
                                                    <td>

                                                        @if ($car->approved_by_admin == 'approved')
                                                            <button class="no yes">
                                                                {{ __('translate.Active') }}
                                                            </button>
                                                        @else
                                                            <button class="no">
                                                                {{ __('translate.Awaiting') }}
                                                            </button>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="actions-btn-item">
                                                            <a href="{{ route('listing', html_decode($car->slug)) }}" class="actions-btn">
                                                                <span>
                                                                    <i class="fa-regular fa-eye"></i>
                                                                </span>
                                                            </a>

                                                            <a href="{{ route('user.car.edit', ['car' => $car->id, 'lang_code' => admin_lang()] ) }}" class="actions-btn edit ">
                                                                <span>
                                                                    <i class="fa-solid fa-pen-to-square"></i>

                                                                </span>
                                                            </a>

                                                            <a href="{{ route('user.car-gallery', $car->id) }}" class="actions-btn edit gallery ">
                                                                <span>
                                                                    <i class="fa-solid fa-image"></i>

                                                                </span>
                                                            </a>




                                                            <button type="button" class="actions-btn delet" onclick="deleteCar({{ $car->id }})">
                                                                <span>
                                                                    <i class="fa-solid fa-trash-can"></i>

                                                                </span>
                                                            </button>

                                                            <form action="{{ route('user.car.destroy', $car->id) }}" id="remove_car_{{ $car->id }}" class="d-none" method="POST">
                                                                @csrf
                                                                @method('DELETE')

                                                            </form>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
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


</main>

@endsection

@push('js_section')
<script src="{{ asset('global/sweetalert/sweetalert2@11.js') }}"></script>


<script>
    "use strict";
        function deleteCar(id){
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
                    $("#remove_car_"+id).submit();
                }

            })
        }
    </script>


@endpush
