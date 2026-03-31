@extends('layout')
@section('title')
    <title>{{ __('translate.Sell Car Parts') }}</title>
@endsection

@section('body-content')
<main>
    <style>
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
                <div class="inner-banner-df">
                    <h1 class="inner-banner-taitel">{{ __('translate.Sell Car Parts') }}</h1>
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
                    <form action="{{ route('user.car-part.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

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
                                        <select class="form-select select2" name="brand_id">
                                            <option value="">{{ __('translate.Select Brand') }}</option>
                                            @foreach($brands as $b)
                                                <option value="{{ $b->id }}" {{ (int) old('brand_id') === (int) $b->id ? 'selected' : '' }}>{{ $b->translate?->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                        <label class="form-label">{{ __('translate.Part Number') }}</label>
                                        <input type="text" class="form-control" name="part_number" value="{{ old('part_number') }}">
                                    </div>
                                </div>

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

                                <div class="description-item two">
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('translate.Compatibility') }}</label>
                                        <input type="text" class="form-control" name="compatibility" value="{{ old('compatibility') }}">
                                    </div>
                                    <div class="description-item-inner">
                                        <label class="form-label">{{ __('Images') }} <span>*</span></label>
                                        <input type="file" class="form-control" name="images[]" id="carPartImages" accept="image/*" multiple required>
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
<script>
    (function () {
        const input = document.getElementById('carPartImages');
        const preview = document.getElementById('carPartImagesPreview');
        if (!input || !preview) {
            return;
        }

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

        input.addEventListener('change', function (event) {
            currentFiles = Array.from(event.target.files || []);
            syncFiles();
            renderPreview();
        });
    })();
</script>
@endsection
