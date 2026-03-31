@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Edit Car Part') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Edit Car Part') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Listing') }} >> {{ __('translate.Edit Car Part') }}</p>
@endsection

@section('body-content')
<form action="{{ route('admin.car-part.update', $carPart->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="lang_code" value="{{ $lang_code }}">

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
                                                    <option value="{{ $u->id }}" {{ (int)$carPart->agent_id === (int)$u->id ? 'selected' : '' }}>{{ $u->name }} - {{ $u->email }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Title') }} *</label>
                                            <input class="crancy__item-input" type="text" name="title" value="{{ old('title', $translation?->title) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Brand') }}</label>
                                            <select class="form-select crancy__item-input" name="brand_id">
                                                <option value="">{{ __('translate.Select Brand') }}</option>
                                                @foreach($brands as $b)
                                                    <option value="{{ $b->id }}" {{ (int)$carPart->brand_id === (int)$b->id ? 'selected' : '' }}>{{ $b->translate?->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Condition') }} *</label>
                                            <select class="form-select crancy__item-input" name="condition" required>
                                                <option value="Used" {{ $carPart->condition == 'Used' ? 'selected' : '' }}>{{ __('translate.Used') }}</option>
                                                <option value="New" {{ $carPart->condition == 'New' ? 'selected' : '' }}>{{ __('translate.New') }}</option>
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
                                                    <option value="{{ $city->id }}" {{ (string) old('city_id', $carPart->city_id) === (string) $city->id ? 'selected' : '' }}>{{ $city->translate?->name ?? $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Price') }} *</label>
                                            <input class="crancy__item-input" type="text" name="regular_price" value="{{ old('regular_price', $carPart->regular_price) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Part Number') }}</label>
                                            <input class="crancy__item-input" type="text" name="part_number" value="{{ old('part_number', $carPart->part_number) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Compatibility') }}</label>
                                            <input class="crancy__item-input" type="text" name="compatibility" value="{{ old('compatibility', $carPart->compatibility) }}">
                                        </div>
                                    </div>

                                    <div class="col-12 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Description') }} *</label>
                                            <textarea class="crancy__item-input" name="description" rows="6" required>{{ old('description', $translation?->description) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 mg-top-form-20">
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Add Images') }}</label>
                                            <input class="crancy__item-input" type="file" name="images[]" multiple accept="image/*">
                                        </div>
                                    </div>

                                    @if($carPart->galleries->count())
                                        <div class="col-12 mg-top-form-20">
                                            <div class="crancy__item-form--group">
                                                <label class="crancy__item-label">{{ __('translate.Existing Images') }}</label>
                                                <div style="display:flex;flex-wrap:wrap;gap:12px;">
                                                    @foreach($carPart->galleries as $gallery)
                                                        <img src="{{ asset($gallery->image) }}" alt="img" style="width:120px;height:120px;object-fit:cover;border-radius:8px;">
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-12 mg-top-form-20">
                                        <button type="submit" class="crancy-btn">{{ __('translate.Update') }}</button>
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
