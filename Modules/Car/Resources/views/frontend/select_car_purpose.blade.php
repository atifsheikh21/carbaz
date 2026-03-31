@extends('layout')
@section('title')
    <title>{{ __('translate.Select Purpose') }}</title>
@endsection

@push('style_section')
    <style>
        .select-purpose-simple{
            padding: 0;
        }
        .select-purpose-simple__grid{
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            width: 100%;
        }
        .select-purpose-simple__card{
            background: #fff;
            border-radius: 0;
            padding: 36px 24px;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .select-purpose-simple__actions{
            margin-top: 0;
        }
        .select-purpose-simple__actions .thm-btn-two{
            min-width: 190px;
            text-align: center;
        }
        @media (max-width: 767.98px){
            .select-purpose-simple{
                padding: 24px 0 0;
            }
            .select-purpose-simple__grid{
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .select-purpose-simple__card{
                min-height: auto;
                padding: 28px 18px;
            }
            .select-purpose-simple__actions .thm-btn-two{
                min-width: 170px;
            }
        }
    </style>
@endpush

@section('body-content')

<main>
    <section class="dashboard">
        <div class="container">
            @php
                $__u = Auth::guard('web')->user();
                $__isDealer = (bool) optional($__u)->is_dealer;
                $__canSellVehicle = !$__isDealer || (bool) optional($__u)->is_vehicle_seller;
                $__canSellPart = !$__isDealer || (bool) optional($__u)->is_part_seller;
                $__feeFreeModeEnabled = ($setting?->fee_free_mode ?? 'disable') === 'enable';
            @endphp

            <div class="row">
                @include('profile.sidebar')

                <div class="col-12 col-lg-9">
                    <div class="select-purpose-simple">
                        <div class="select-purpose-simple__grid">
                        @if($__canSellVehicle)
                            <div class="select-purpose-simple__card">
                                <div class="select-purpose-simple__actions">
                                    @if(Auth::guard('web')->user()?->is_dealer)
                                        <a href="{{ route('user.car.create', ['purpose' => 'Sale']) }}" class="thm-btn-two">{{ __('Place Vehicle Ad') }}</a>
                                    @elseif($__feeFreeModeEnabled)
                                        <a href="javascript:;" class="thm-btn-two" data-bs-toggle="modal" data-bs-target="#individualAdFreeInfoModal">{{ __('Place Car Ad') }}</a>
                                    @else
                                        <a href="javascript:;" class="thm-btn-two" data-bs-toggle="modal" data-bs-target="#individualAdPaidModal">{{ __('Place Car Ad') }}</a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($__canSellPart)
                            <div class="select-purpose-simple__card">
                                <div class="select-purpose-simple__actions">
                                    @if(Auth::guard('web')->user()?->is_dealer || $__feeFreeModeEnabled)
                                        <a href="{{ route('user.car-part.create') }}" class="thm-btn-two">{{ __('Place Vehicle Part Ad') }}</a>
                                    @else
                                        <a href="javascript:;" class="thm-btn-two" data-bs-toggle="modal" data-bs-target="#individualAdPaidModal">{{ __('Place Vehicle Part Ad') }}</a>
                                    @endif
                                </div>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
    </section>

    @include('profile.logout')



</main>

@endsection

@if(!Auth::guard('web')->user()?->is_dealer && (($setting?->fee_free_mode ?? 'disable') === 'enable'))
    <div class="modal payment-modal fade" id="individualAdFreeInfoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Post Your Ad') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="payment-modal-item">
                        <h4>{{ __('This ad is currently free to publish because fee free mode is enabled from admin settings.') }}</h4>
                    </div>

                    <div class="payment-modal-from-item">
                        <div class="payment-modal-from-inner" style="display:flex; gap:12px;">
                            <a class="thm-btn-two" href="{{ route('user.car.create', ['purpose' => 'Sale']) }}">{{ __('Continue') }}</a>
                            <button class="thm-btn-two" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(!Auth::guard('web')->user()?->is_dealer && (($setting?->fee_free_mode ?? 'disable') !== 'enable'))
    <div class="modal payment-modal fade" id="individualAdPaidModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Worldpay</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="payment-modal-item">
                        <h4>{{ __('translate.Amount') }}<span>€5.00</span></h4>
                    </div>
                    <div class="payment-modal-item">
                        <h4>{{ __('Worldpay will be used here for private ad payment.') }}</h4>
                    </div>
                    <div class="payment-modal-item">
                        <h4>{{ __('This is a placeholder only. Live Worldpay checkout has not been connected yet.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
