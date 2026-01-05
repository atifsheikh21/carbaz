@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Purchase History') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Purchase History') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Subscription Plan') }} >> {{ __('translate.Purchase History') }}</p>
@endsection

@section('body-content')
    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">

                            <div class="crancy-table crancy-table--v3 mg-top-30">

                                <div class="crancy-customer-filter">
                                    <div class="crancy-customer-filter__single crancy-customer-filter__single--csearch d-flex items-center justify-between create_new_btn_box">
                                        <div class="crancy-header__form crancy-header__form--customer create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Purchase History') }}</h4>
                                        </div>
                                    </div>
                                </div>

                                <!-- crancy Table -->
                                <div id="crancy-table__main_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">

                                    <table class="crancy-table__main crancy-table__main-v3 dataTable no-footer">

                                        <tbody class="crancy-table__body review-detials   plan-details">

                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.User Name') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $history?->user?->name }}</h4>
                                                    </td>

                                                </tr>

                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.Plan') }}</h4>
                                                    </td>


                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $history->plan_name }}</h4>
                                                    </td>

                                                </tr>

                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.Price') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ currency($history->plan_price) }}</h4>
                                                    </td>

                                                </tr>

                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.Maximum Car') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $history->max_car }}</h4>
                                                    </td>

                                                </tr>

                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.Featured Car') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $history->featured_car }}</h4>
                                                    </td>

                                                </tr>

                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.Expiration') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $history->expiration }}</h4>
                                                    </td>

                                                </tr>

                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.Expiration Date') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $history->expiration_date }}</h4>
                                                    </td>

                                                </tr>

                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.Remaining day') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
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

                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.Plan Status') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
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

                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.Payment Status') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                    @if ($history->payment_status == 'success')
                                                                <div class="badge bg-success">{{ __('translate.Success') }}</div>
                                                            @else
                                                                <div class="badge bg-danger">{{ __('translate.Pending') }}</div>
                                                            @endif
                                                    </td>

                                                </tr>

                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.Payment Method') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        {{ $history->payment_method }}
                                                    </td>


                                                </tr>


                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.Transaction') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        {!! clean($history->transaction) !!}
                                                    </td>


                                                </tr>


                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ __('translate.Action') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">

                                                        @if ($history->payment_status == 'pending')
                                                            <a href="javascript:;" class="crancy-btn" data-bs-toggle="modal" data-bs-target="#paymentApproval">{{ __('translate.Payment Approval') }}</a>
                                                        @endif


                                                        <a onclick="itemDeleteConfrimation({{ $history->id }})" href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal" class="crancy-btn delete_danger_btn"><i class="fas fa-trash"></i> {{ __('translate.Delete') }}</a>
                                                    </td>


                                                </tr>



                                        </tbody>
                                        <!-- End crancy Table Body -->
                                    </table>
                                </div>
                                <!-- End crancy Table -->
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->


  <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('translate.Delete Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('translate.Are you realy want to delete this item?') }}</p>
                </div>
                <div class="modal-footer">
                    <form action="" id="item_delect_confirmation" class="delet_modal_form" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('translate.Yes, Delete') }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- payment approval --}}

    <div class="modal fade" id="paymentApproval" tabindex="-1" aria-labelledby="paymentApproval" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentApproval">{{ __('translate.Payment Approval') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('translate.Are you realy want to approved this payment?') }}</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.purchase-history-payment-approved', $history->id) }}" id="item_delect_confirmation" class="delet_modal_form" method="POST">
                        @csrf

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('translate.Yes, Approved') }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js_section')
    <script>
        "use strict"
        function itemDeleteConfrimation(id){
            $("#item_delect_confirmation").attr("action",'{{ url("admin/purchase-history-destroy/") }}'+"/"+id)
        }
    </script>
@endpush
