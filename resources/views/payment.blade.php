@extends('layout')
@section('title')
    <title>{{ __('translate.Payment') }}</title>
@endsection

@section('body-content')
<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Payment') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Payment') }}</li>
                    </ol>
                </nav>
            </div>
            </div>
        </div>
    </section>
    <!-- banner-part-end -->



    <!-- dashboard-part-start -->

    <section class="pricing package-details">
        <div class="container">

            <div class="row">
                <div class="col-lg-8">
                <div class="alert alert-danger" role="alert">
                      {{ __('translate.When you purchase new plan, your previous package features will be destroy') }}
                    </div>
                    <div class="package-details-item">
                        <div class="package-details-table">
                            <table class=" table table-bordered ">
                                <tr>
                                    <td>{{ __('translate.Package') }}</td>
                                    <td>{{ $subscription_plan->plan_name }}</td>
                                </tr>

                                <tr>
                                    <td>{{ __('translate.Price') }}</td>
                                    <td>{{ currency($subscription_plan->plan_price) }}</td>
                                </tr>

                                <tr>
                                    <td>{{ __('translate.Expiration') }}</td>
                                    <td>
                                        @if ($subscription_plan->expiration_date == 'monthly')
                                        {{ __('translate.Monthly') }}
                                        @elseif ($subscription_plan->expiration_date == 'yearly')
                                        {{ __('translate.Yearly') }}
                                        @elseif ($subscription_plan->expiration_date == 'lifetime')
                                        {{ __('translate.Lifetime') }}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ __('translate.Number of Car') }}</td>
                                    <td>
                                        {{ $subscription_plan->max_car }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ __('translate.Number of Featured Car') }}</td>
                                    <td>
                                        {{ $subscription_plan->featured_car }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ __('translate.Listing Image') }}</td>
                                    <td>
                                        {{ __('translate.Unlimited') }}
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="payment">
                        <h2 class="payment-txt">{{ __('translate.Payment Gateway') }}</h2>
                        <div class="payment-inner">
                            @if ($worldpay && $worldpay->status == 1)
                                <div class="payment-inner-item modal-btn" data-bs-toggle="modal" data-bs-target="#worldpayPlaceholderModal">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ getImageOrPlaceholder($worldpay->image, '120x30') }}" alt="img">
                                    </div>
                                    <input type="button" class="payment-inner-item-input">
                                </div>
                            @else
                                <div class="payment-inner-item">
                                    <div class="payment-inner-item-label">
                                        <span>Worldpay</span>
                                    </div>
                                    <input type="button" class="payment-inner-item-input" disabled>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- dashboard-part-end -->


     <div class="modal payment-modal fade" id="worldpayPlaceholderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Worldpay</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="payment-modal-item">
                        <h4>{{ __('translate.Amount') }}<span>{{ currency($subscription_plan->plan_price) }}</span></h4>
                    </div>
                    <div class="payment-modal-item">
                        <h4>{{ __('Worldpay will be used here for frontend payment.') }}</h4>
                    </div>
                    <div class="payment-modal-item">
                        <h4>{{ __('This is a placeholder only. Live Worldpay checkout has not been connected yet.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>


</main>
@endsection
