@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Car Parts') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Car Parts') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Listing') }} >> {{ __('translate.Car Parts') }}</p>
@endsection

@section('body-content')
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
                                        <h4 class="crancy-product-card__title">{{ __('translate.Car Parts') }}</h4>
                                        <a href="{{ route('admin.car-part.create') }}" class="crancy-btn">{{ __('translate.Create New') }}</a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="crancy-table__main crancy-table__main-v3 no-footer">
                                    <thead class="crancy-table__head">
                                        <tr>
                                            <th>{{ __('translate.ID') }}</th>
                                            <th>{{ __('translate.Title') }}</th>
                                            <th>{{ __('translate.Dealer') }}</th>
                                            <th>{{ __('translate.Price') }}</th>
                                            <th>{{ __('translate.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="crancy-table__body">
                                        @foreach($carParts as $p)
                                            @php $t = $p->translate; @endphp
                                            <tr>
                                                <td>{{ $p->id }}</td>
                                                <td>{{ html_decode($t?->title) }}</td>
                                                <td>{{ html_decode($p->agent?->name) }}</td>
                                                <td>
                                                    @if($p->offer_price)
                                                        {{ currency($p->offer_price) }}
                                                    @else
                                                        {{ currency($p->regular_price) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.car-part.edit', ['car_part' => $p->id, 'lang_code' => admin_lang()]) }}" class="crancy-btn">{{ __('translate.Edit') }}</a>
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
</section>
@endsection
