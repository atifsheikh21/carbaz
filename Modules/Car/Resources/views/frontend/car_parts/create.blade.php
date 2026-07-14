@extends('layout')
@section('title')
    <title>{{ __('translate.Sell Car Parts') }}</title>
@endsection

@section('body-content')
@php
    $authUser = Auth::guard('web')->user();
    $sellerTypeLabel = ($authUser && $authUser->is_dealer) ? 'Dealer / Trader' : 'Private';
@endphp
<main>
    <style>
        .place-ad-back-btn{
            display:inline-block;
            border:1px solid #cfcfcf;
            background:#fff;
            padding:10px 18px;
            border-radius:4px;
            font-weight:600;
            color:#111;
            text-decoration:none;
            line-height:1;
            position:absolute;
            left:0;
            top:0;
        }
        .place-ad-banner-col{position:relative;}
        .car-part-upload-preview{
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(140px,1fr));
            gap:14px;
            margin-top:14px;
        }
        .car-part-upload-preview__item{
            position:relative;
            border:1px solid #d9d9d9;
            border-radius:10px;
            overflow:hidden;
            background:#fff;
        }
        .car-part-upload-preview__item img{
            width:100%;
            height:120px;
            object-fit:cover;
            display:block;
        }
        .car-part-upload-preview__remove{
            position:absolute;
            top:8px;
            right:8px;
            border:0;
            border-radius:50%;
            width:28px;
            height:28px;
            background:rgba(0,0,0,.7);
            color:#fff;
            font-size:16px;
            line-height:1;
        }
        .car-part-upload-preview__name{
            padding:8px 10px;
            font-size:12px;
            word-break:break-word;
        }
    </style>
    <section class="inner-banner">
        <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
            <div class="col-lg-12">
                <div class="place-ad-banner-col">
                    <a href="#" class="place-ad-back-btn d-none d-md-inline-block" onclick="event.preventDefault(); history.back();">{{ __('Back') }}</a>
                </div>
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ $sellerTypeLabel }} - {{ __('Create Ad') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('user.car-part.index') }}">{{ __('translate.Car Parts') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Create') }}</li>
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
                    @if(($feeFreeModeEnabled ?? false) === true)
                        <div class="alert alert-success" role="alert" style="margin-bottom:16px;">
                            <strong>Free Posting Enabled:</strong> During launch, posting is free for all users. Each ad remains active for 30 days.
                        </div>
                    @endif
                    <form action="{{ route('user.car-part.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if($errors->any())
                            <div class="alert alert-danger" style="margin-bottom: 20px;">
                                <ul style="margin: 0; padding-left: 20px;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="car-images">
                            <h3 class="car-images-taitel">{{ __('translate.Basic Information') }}</h3>
                            <div class="car-images-inner">
                                <div class="description-item">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Title') }} <span>*</span></label>
                                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                                    </div>
                                </div>

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Brand') }}</label>
                                        <select class="form-select select2" name="brand_id" id="car_part_brand_id">
                                            <option value="" disabled {{ old('brand_id') ? '' : 'selected' }} hidden>{{ __('translate.Select Brand') }}</option>
                                            @foreach($makerOptions as $brandSlug => $brandLabel)
                                                <option value="{{ $brandSlug }}" {{ old('brand_id') === $brandSlug ? 'selected' : '' }}>{{ $brandLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('Model') }}</label>
                                        <select class="form-select select2" name="car_model" id="car_part_model">
                                            <option value="">{{ __('Select Model') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('Compatible From Year') }}</label>
                                        <select class="form-select" name="from_year">
                                            <option value="">{{ __('Select') }}</option>
                                            @for ($year = 1980; $year <= 2026; $year++)
                                                <option value="{{ $year }}" {{ (string) old('from_year') === (string) $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('Compatible To Year') }}</label>
                                        <select class="form-select" name="to_year">
                                            <option value="">{{ __('Select') }}</option>
                                            @for ($year = 1980; $year <= 2026; $year++)
                                                <option value="{{ $year }}" {{ (string) old('to_year') === (string) $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="description-item">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Country') }} <span>*</span></label>
                                        <input type="hidden" name="country_id" value="{{ $ireland?->id }}">
                                        <input type="text" class="form-control" value="{{ $ireland?->name ?? 'Ireland' }}" readonly>
                                    </div>
                                </div>

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Condition') }} <span>*</span></label>
                                        <select class="form-select" name="condition" required>
                                            <option value="Used">{{ __('translate.Used') }}</option>
                                            <option value="New">{{ __('translate.New') }}</option>
                                        </select>
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Part Number') }} </label>
                                        <input type="text" class="form-control" name="part_number" value="{{ old('part_number') }}">
                                    </div>
                                </div>

                                @if(Auth::guard('web')->check() && Auth::guard('web')->user()?->is_dealer)
                                    <div class="description-item">
                                        <div class="description-item-inner">
                                            <label class="form-label">{{ __('Warranty') }}</label>
                                            <select class="form-select" name="warranty_months" id="warranty_months">
                                                <option value="">{{ __('Select') }}</option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ (string) old('warranty_months') === (string) $i ? 'selected' : '' }}>{{ $i }} {{ $i === 1 ? __('month') : __('months') }}</option>
                                                @endfor
                                                @for($y = 1; $y <= 12; $y++)
                                                    @php($m = $y * 12)
                                                    <option value="{{ $m }}" {{ (string) old('warranty_months') === (string) $m ? 'selected' : '' }}>{{ $y }} {{ $y === 1 ? __('year') : __('years') }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.City') }} <span>*</span></label>
                                        <select class="form-select select2" name="city_id" required>
                                            <option value="">{{ __('translate.Select City') }}</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ (int) old('city_id') === (int) $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Price') }} <span>*</span></label>
                                        <input type="text" class="form-control" name="regular_price" value="{{ old('regular_price') }}" required>
                                    </div>
                                </div>

                                <div class="description-item">
                                    <div class="description-item-inner">
                                        <label class="form-label" style="display:block;">{{ __('Images') }}</label>
                                        <label for="carPartImages" class="car-part-upload-btn" style="display:inline-block;cursor:pointer;padding:8px 20px;background:#405FF2;color:#fff;border-radius:6px;font-size:14px;font-weight:600;margin-bottom:6px;">{{ __('Upload Images') }}</label>
                                        <input type="file" class="form-control" name="images[]" id="carPartImages" accept="image/jpeg,image/png" multiple style="display:none;">
                                        <small class="text-muted">{{ __('Maximum 8 images allowed') }}</small>
                                        <div id="carPartImagesPreview" class="car-part-upload-preview"></div>
                                    </div>
                                </div>

                                <div class="description-item">
                                    <div class="description-item-inner" style="width:100%">
                                        <label class="form-label">{{ __('translate.Description') }} <span>*</span></label>
                                        <textarea class="form-control" name="description" rows="5" required>{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="text-end" style="margin-top:12px;">
                                    <button type="submit" class="thm-btn-two">{{ __('translate.Save') }}</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<div id="adSubmittingOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:999999;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:18px;">
    <div style="text-align:center;padding:20px 24px;background:rgba(0,0,0,.4);border-radius:12px;backdrop-filter:blur(2px);">
        <div style="font-size:16px;margin-bottom:8px;">{{ __('Please wait') }}</div>
        <div style="font-size:22px;">{{ __('Your ad is being placed...') }}</div>
        <div style="font-size:14px;margin-top:10px;opacity:.8;">{{ __('System is reviewing your ad. Do not close this window.') }}</div>
    </div>
</div>

@include('partials.image_upload_optimizer')
<script>
    (function () {
        const brandModelsMap = @json($brandModelsMap ?? []);
        const brandSelect = document.getElementById('car_part_brand_id');
        const modelSelect = document.getElementById('car_part_model');

        function fillModelOptions(brandKey) {
            if (!modelSelect) {
                return;
            }

            const current = modelSelect.value;
            modelSelect.innerHTML = '';
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Select Model';
            modelSelect.appendChild(placeholder);

            const models = (brandKey && brandModelsMap && brandModelsMap[brandKey]) ? brandModelsMap[brandKey] : [];
            models.forEach((m) => {
                const opt = document.createElement('option');
                opt.value = m;
                opt.textContent = m;
                modelSelect.appendChild(opt);
            });

            const oldModel = "{{ old('car_model') }}";
            if (oldModel) {
                modelSelect.value = oldModel;
            } else if (current) {
                modelSelect.value = current;
            }

            if (window.jQuery && jQuery.fn && jQuery.fn.select2) {
                try {
                    jQuery(modelSelect).trigger('change.select2');
                } catch (e) {}
            }
        }

        if (brandSelect && modelSelect) {
            brandSelect.addEventListener('change', function () {
                fillModelOptions(this.value);
            });
            fillModelOptions(brandSelect.value || "{{ old('brand_id') }}");
        }

        const input = document.getElementById('carPartImages');
        const preview = document.getElementById('carPartImagesPreview');
        if (!input || !preview) {
            return;
        }

        const maxImages = 8;
        let currentFiles = [];

        function syncFiles() {
            const dataTransfer = new DataTransfer();
            currentFiles.forEach((file) => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
        }

        function renderPreview() {
            preview.innerHTML = '';
            currentFiles.forEach((file, index) => {
                const item = document.createElement('div');
                item.className = 'car-part-upload-preview__item';

                const image = document.createElement('img');
                image.alt = file.name;
                image.src = URL.createObjectURL(file);

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'car-part-upload-preview__remove';
                removeBtn.textContent = '×';
                removeBtn.addEventListener('click', function () {
                    currentFiles.splice(index, 1);
                    syncFiles();
                    renderPreview();
                });

                const name = document.createElement('div');
                name.className = 'car-part-upload-preview__name';
                name.textContent = file.name;

                item.appendChild(image);
                item.appendChild(removeBtn);
                item.appendChild(name);
                preview.appendChild(item);
            });
        }

        input.addEventListener('change', async function (event) {
            const newFiles = await window.optimizeImageFilesForUpload(event.target.files || []);
            let skipped = false;
            newFiles.forEach((file) => {
                const exists = currentFiles.some((currentFile) => currentFile.name === file.name && currentFile.size === file.size && currentFile.lastModified === file.lastModified);
                if (exists) {
                    return;
                }
                if (currentFiles.length < maxImages) {
                    currentFiles.push(file);
                } else {
                    skipped = true;
                }
            });
            if (skipped) {
                alert('Maximum 8 images allowed');
            }
            syncFiles();
            renderPreview();
        });

        var f = document.querySelector('form[action="{{ route('user.car-part.store') }}"]');
        if (f) {
            f.addEventListener('submit', function(){
                var ov = document.getElementById('adSubmittingOverlay');
                if (ov){ ov.style.display = 'flex'; }
            });
        }
    })();
</script>
@endsection
