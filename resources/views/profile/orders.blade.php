@extends('layout')
@section('title')
    <title>{{ __('translate.Purchase History') }}</title>
@endsection
@section('body-content')

<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }})"></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Purchase History') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Purchase History') }}</li>
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
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="manage-car two">
                                <div class="car_list_table">
                                    <table class="table two">
                                        <thead>
                                            <tr>
                                                <th>
                                                    {{ __('translate.Package') }}
                                                </th>
                                                <th>{{ __('translate.Expiration') }}</th>
                                                <th>{{ __('translate.Status') }}</th>
                                                <th>{{ __('translate.Amount') }}</th>
                                                <th>{{ __('translate.View') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($histories as $index => $history)
                                                <tr>

                                                    <td>
                                                        {{ $history->plan_name }}
                                                    </td>

                                                    <td>{{ $history->expiration }}</td>

                                                    <td>


                                                        @if ($history->status == 'active')
                                                            @if ($history->expiration_date == 'lifetime')
                                                                <button type="button" class="no yes">{{ __('translate.Active') }}
                                                                </button>
                                                            @else
                                                                @if (date('Y-m-d') <= $history->expiration_date)
                                                                    <button type="button" class="no yes">{{ __('translate.Active') }}
                                                                    </button>
                                                                @else
                                                                    <button type="button" class="no">{{ __('translate.Expired') }}
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        @elseif ($history->status == 'pending')
                                                                    <button type="button" class="no">{{ __('translate.Pending') }}
                                                                    </button>
                                                        @elseif ($history->status == 'expired')
                                                            <button type="button" class="no">{{ __('translate.Expired') }}
                                                            </button>
                                                        @endif

                                                    </td>
                                                    <td>
                                                        {{ currency($history->plan_price) }}
                                                    </td>

                                                    <td>
                                                        <div class="actions-btn-item">
                                                            <button type="button" class="actions-btn" data-bs-toggle="modal" data-bs-target="#showPurchaseHistory{{ $index }}">
                                                                <span>
                                                                    <svg width="15" height="10" viewBox="0 0 15 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M7.79633 0.679688C5.12346 0.679688 2.69957 2.14204 0.910975 4.51729C0.765026 4.71188 0.765026 4.98375 0.910975 5.17835C2.69957 7.55646 5.12346 9.01881 7.79633 9.01881C10.4692 9.01881 12.8931 7.55646 14.6817 5.18121C14.8276 4.98661 14.8276 4.71475 14.6817 4.52015C12.8931 2.14204 10.4692 0.679688 7.79633 0.679688ZM7.98807 7.7854C6.21379 7.897 4.74857 6.43465 4.86018 4.65751C4.95176 3.1923 6.13938 2.00467 7.60459 1.9131C9.37887 1.80149 10.8441 3.26384 10.7325 5.04098C10.638 6.50334 9.45042 7.69096 7.98807 7.7854ZM7.89935 6.42893C6.94353 6.48903 6.15369 5.70205 6.21665 4.74623C6.2653 3.95638 6.90633 3.31822 7.69617 3.2667C8.65199 3.20661 9.44183 3.99359 9.37887 4.94941C9.32736 5.74211 8.68633 6.38028 7.89935 6.42893Z"></path>
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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


    <!-- Payment Details Modal  start-->
    @foreach ($histories as $index => $history)
        <div class="modal modal-three fade" id="showPurchaseHistory{{ $index }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">
                            <a href="{{ route('home') }}"><img src="{{ getImageOrPlaceholder($setting->inner_logo, '170x46') }}" alt="logo"></a>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body-item">
                            <ul class="modal-body-list">
                                <li> <span>{{ __('translate.Name') }}:</span> {{ html_decode($user->name) }}</li>
                                <li> <span>{{ __('translate.Phone') }}:</span> {{ html_decode($user->phone) }}</li>
                                <li> <span>{{ __('translate.Email') }}:</span> {{ html_decode($user->email) }}</li>
                                <li> <span>{{ __('translate.Location') }}:</span> {{ html_decode($user->address) }}</li>
                            </ul>
                            <ul class="modal-body-list two">
                                <li> <span>{{ __('translate.Order ID') }}:</span> #{{ $history->order_id }}</li>
                                <li> <span>{{ __('translate.Amount') }}: </span>{{ currency($history->plan_price) }}</li>
                                <li> <span>{{ __('translate.Payment') }}: </span> {{ $history->payment_method }}</li>
                                <li> <span>{{ __('translate.Transaction') }}: </span>{!! clean(nl2br($history->transaction)) !!} </li>
                            </ul>
                        </div>

                        <table class="table table-bordered">

                            <tr>
                                <td>{{ __('translate.Plan') }}</td>
                                <td>{{ $history->plan_name }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('translate.Price') }}</td>
                                <td>{{ currency($history->plan_price) }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('translate.Maximum Car') }}</td>
                                <td>{{ $history->max_car }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('translate.Featured Car') }}</td>
                                <td>{{ $history->featured_car }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('translate.Expiration') }}</td>
                                <td>{{ $history->expiration }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('translate.Expirated Date') }}</td>
                                <td>{{ $history->expiration_date }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('translate.Remaining day') }}</td>
                                <td>
                                    @if ($history->status == 'active')
                                        @if ($history->expiration_date == 'lifetime')
                                            {{ __('translate.Lifetime') }}
                                        @else
                                            @php
                                                $date1 = new DateTime(date('Y-m-d'));
                                                $date2 = new DateTime($history->expiration_date);
                                                $interval = $date1->diff($date2);
                                                $remaining = $interval->days;
                                            @endphp

                                            @if ($remaining > 0)
                                                {{ $remaining }} {{ __('translate.Days') }}
                                            @else
                                                {{ __('translate.Expired') }}
                                            @endif

                                        @endif
                                    @else
                                        {{ __('translate.Expired') }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('translate.Payment Method') }}</td>
                                <td>{{ $history->payment_method }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('translate.Transaction') }}</td>
                                <td>{!! clean(nl2br($history->transaction)) !!}</td>
                            </tr>

                            <tr>
                                <td>{{ __('translate.Plan Status') }}</td>
                                <td>
                                    @if ($history->status == 'active')
                                        @if ($history->expiration_date == 'lifetime')
                                            <div class="badge bg-success">{{ __('translate.Active') }}</div>
                                        @else
                                            @if (date('Y-m-d') <= $history->expiration_date)
                                                <div class="badge bg-success">{{ __('translate.Active') }}</div>
                                            @else
                                                <div class="badge bg-danger">{{ __('translate.Expired') }}</div>
                                            @endif
                                        @endif
                                        @elseif ($history->status == 'pending')
                                            <div class="badge bg-danger">{{ __('translate.Pending') }}</div>
                                    @elseif ($history->status == 'expired')
                                        <div class="badge bg-danger">{{ __('translate.Expired') }}</div>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    {{ __('translate.Payment') }}
                                </td>
                                <td>
                                    @if ($history->payment_status == 'success')
                                        <div class="badge bg-success">{{ __('translate.Success') }}</div>
                                    @else
                                        <div class="badge bg-danger">{{ __('translate.Pending') }}</div>
                                    @endif
                                </td>
                            </tr>

                        </table>

                    </div>

                </div>
            </div>
        </div>
    @endforeach
    <!-- Payment Details Modal  end-->


</main>

@endsection
