@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Create Car Part') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Create Car Part') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Listing') }} >> {{ __('translate.Create Car Part') }}</p>
@endsection

@section('body-content')
<form action="{{ route('admin.car-part.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="crancy-product-card">
                                <h4 class="crancy-product-card__title">{{ __('translate.Basic Information') }}</h4>

                                <div class="row">
                                    <div class="col-12 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Dealer') }} *</label>
                                            <select class="form-select crancy__item-input" name="agent_id" required>
                                                <option value="">{{ __('translate.Select Dealer') }}</option>
                                                @foreach($users as $u)
                                                    <option value="{{ $u->id }}">{{ $u->name }} - {{ $u->email }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Title') }} *</label>
                                            <input class="crancy__item-input" type="text" name="title" value="{{ old('title') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Brand') }}</label>
                                            <select class="form-select crancy__item-input" name="brand_id">
                                                <option value="">{{ __('translate.Select Brand') }}</option>
                                                @foreach($brands as $b)
                                                    <option value="{{ $b->id }}">{{ $b->translate?->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Condition') }} *</label>
                                            <select class="form-select crancy__item-input" name="condition" required>
                                                <option value="Used">{{ __('translate.Used') }}</option>
                                                <option value="New">{{ __('translate.New') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Country') }} *</label>
                                            <input class="crancy__item-input" type="text" value="Ireland" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.City') }} *</label>
                                            <select class="form-select crancy__item-input" name="city_id" required>
                                                <option value="">{{ __('translate.Select City') }}</option>
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}">{{ $city->translate?->name ?? $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Price') }} *</label>
                                            <input class="crancy__item-input" type="text" name="regular_price" value="{{ old('regular_price') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Part Number') }}</label>
                                            <input class="crancy__item-input" type="text" name="part_number" value="{{ old('part_number') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Compatibility') }}</label>
                                            <input class="crancy__item-input" type="text" name="compatibility" value="{{ old('compatibility') }}">
                                        </div>
                                    </div>

                                    <div class="col-12 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Description') }} *</label>
                                            <textarea class="crancy__item-input" name="description" rows="6" required>{{ old('description') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Images') }} *</label>
                                            <input class="crancy__item-input" type="file" name="images[]" multiple accept="image/*" required>
                                        </div>
                                    </div>

                                    <div class="col-12 mg-top-form-20">
                                        <button type="submit" class="crancy-btn">{{ __('translate.Save') }}</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
@endsection
