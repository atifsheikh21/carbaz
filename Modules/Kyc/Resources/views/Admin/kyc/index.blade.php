@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Manage Kyc') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Manage Kyc') }}</h3>
    <p class="crancy-header__text">{{ __('translate.KYC') }} >> {{ __('translate.Document') }}</p>
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
                                <!-- crancy Table -->
                                <div id="crancy-table__main_wrapper" class="dt-bootstrap5 no-footer">

                                    <table class="crancy-table__main crancy-table__main-v3  no-footer" id="dataTable">
                                        <!-- crancy Table Head -->
                                        <thead class="crancy-table__head">
                                            <tr>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting"  >
                                                    {{ __('translate.SN') }}
                                                </th>

                                                <th class="crancy-table__column-3 crancy-table__h3 sorting"  >
                                                    {{ __('translate.Document') }}
                                                </th>

                                                <th class="crancy-table__column-4 crancy-table__h4 sorting"  >
                                                    {{ __('translate.Name') }}
                                                </th>

                                                <th class="crancy-table__column-5 crancy-table__h5 sorting">
                                                    {{ __('translate.Document Name') }}
                                                </th>

                                                <th class="crancy-table__column-6 crancy-table__h6 sorting">
                                                    {{ __('translate.Status') }}
                                                </th>

                                                <th class="crancy-table__column-7 crancy-table__h7 sorting">
                                                    {{ __('translate.Action') }}
                                                </th>

                                            </tr>
                                        </thead>
                                        <!-- crancy Table Body -->
                                        <tbody class="crancy-table__body">
                                            @foreach ($kycs as $index => $kyc)
                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ ++$index }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-3 crancy-table__data-3">
                                                        <a href="{{ asset($kyc->file) }}">
                                                            <img width="120" src="{{ getImageOrPlaceholder($kyc->file, '120x90') }}" alt="">
                                                        </a>
                                                    </td>

                                                    <td class="crancy-table__column-4 crancy-table__data-4">
                                                        <h4 class="crancy-table__product-title">
                                                            <a href="{{ route('admin.user-show',$kyc->influncer->id) }}">{{ $kyc->influncer->name}}</a>
                                                        </h4>
                                                    </td>

                                                    <td class="crancy-table__column-5 crancy-table__data-5">
                                                        <h4 class="crancy-table__product-title">{{ $kyc->kyc_type->name}}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-6 crancy-table__data-6">
                                                        @if ($kyc->status == 0)
                                                            <span class="badge bg-danger text-white">{{ __('translate.Pending') }}</span>
                                                        @endif
                                                        @if ($kyc->status == 1)
                                                            <span class="badge bg-success text-white">{{ __('translate.Approved') }}</span>
                                                        @endif
                                                        @if ($kyc->status == 2)
                                                            <span class="badge bg-danger text-white">{{ __('translate.Reject') }}</span>
                                                        @endif
                                                    </td>

                                                    <td class="crancy-table__column-7 crancy-table__data-7">
                                                        <a onclick="itemStatusConfrimation({{ $kyc->id }})" href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal2" class="crancy-btn"><i class="fas fa-edit"></i> {{ __('translate.Edit') }}</a>

                                                        <a onclick="itemDeleteConfrimation({{ $kyc->id }})" href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal" class="crancy-btn delete_danger_btn"><i class="fas fa-trash"></i> {{ __('translate.Delete') }}</a>
                                                    </td>
                                                </tr>
                                            @endforeach

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
    @foreach ($kycs as $index => $kyc1)
    <!-- Change Status Confirmation Modal -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('translate.Approve KYC') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.update-kyc-status', ['id' => $kyc1->id]) }}" class="delet_modal_form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p><b>{{__('translate.Message')}}:</b> {{$kyc1->message}}</p><br>
                            </div>
                            <div class="col-md-12">
                                <label>{{__('translate.Status')}} <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control">
                                    <option {{ $kyc1->status == 0 ? 'selected' : '' }} value="0">{{__('translate.Pending')}}</option>
                                    <option {{ $kyc1->status == 1 ? 'selected' : '' }} value="1">{{__('translate.Approved')}}</option>
                                    <option {{ $kyc1->status == 2 ? 'selected' : '' }} value="2">{{__('translate.Reject')}}</option>
                                </select>
                            </div>

                            <div class="col-md-6 pt-5">
                                <button type="submit" class="btn btn-primary">{{ __('translate.Confirm') }}</button>
                            </div>
                            <div class="col-md-6 pt-5">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    @endforeach
@endsection

@push('js_section')
    <script>
        "use strict"
        function itemDeleteConfrimation(id){
            $("#item_delect_confirmation").attr("action",'{{ url("admin/delete-kyc-info/") }}'+"/"+id)
        }
    </script>
@endpush


