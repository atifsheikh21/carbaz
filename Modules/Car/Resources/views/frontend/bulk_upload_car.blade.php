@extends('layout')
@section('title')
    <title>{{ __('Bulk Upload Vehicles') }}</title>
@endsection

@push('style_section')
<style>
    .bulk-upload-page {
        padding: 40px 0 60px;
    }
    .bulk-upload-card {
        background: #fff;
        border-radius: 8px;
        padding: 32px 36px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        margin-bottom: 28px;
    }
    .bulk-upload-card__title {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 6px;
    }
    .bulk-upload-card__sub {
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    .bulk-upload-columns-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-bottom: 8px;
    }
    .bulk-upload-columns-table th {
        background: #f3f4f6;
        text-align: left;
        padding: 8px 12px;
        font-weight: 600;
        color: #333;
        border: 1px solid #e5e7eb;
    }
    .bulk-upload-columns-table td {
        padding: 7px 12px;
        border: 1px solid #e5e7eb;
        color: #555;
    }
    .bulk-upload-columns-table td.col-required {
        color: #b60304;
        font-weight: 600;
    }
    .bulk-upload-columns-table td.col-optional {
        color: #888;
    }
    .bulk-upload-sample-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #1a1a1a;
        color: #fff;
        padding: 10px 22px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
        margin-bottom: 4px;
    }
    .bulk-upload-sample-btn:hover {
        background: #b60304;
        color: #fff;
        text-decoration: none;
    }
    .bulk-upload-file-zone {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 32px 24px;
        text-align: center;
        background: #fafafa;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        margin-bottom: 20px;
        position: relative;
    }
    .bulk-upload-file-zone:hover,
    .bulk-upload-file-zone.drag-over {
        border-color: #b60304;
        background: #fff5f5;
    }
    .bulk-upload-file-zone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }
    .bulk-upload-file-zone__icon {
        font-size: 36px;
        margin-bottom: 10px;
        color: #aaa;
    }
    .bulk-upload-file-zone__label {
        font-size: 15px;
        font-weight: 600;
        color: #444;
        margin-bottom: 4px;
    }
    .bulk-upload-file-zone__hint {
        font-size: 13px;
        color: #999;
    }
    .bulk-upload-file-zone__selected {
        margin-top: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #b60304;
    }
    .bulk-upload-submit-btn {
        background: #b60304;
        color: #fff;
        border: none;
        padding: 12px 36px;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
        width: 100%;
    }
    .bulk-upload-submit-btn:hover {
        background: #950203;
    }
    .bulk-upload-submit-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    .bulk-upload-notice {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 6px;
        padding: 14px 18px;
        font-size: 13px;
        color: #92400e;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    .bulk-upload-notice strong {
        display: block;
        margin-bottom: 4px;
        font-size: 14px;
    }
    .bulk-errors-list {
        background: #fff5f5;
        border: 1px solid #fca5a5;
        border-radius: 6px;
        padding: 16px 20px;
        margin-bottom: 24px;
    }
    .bulk-errors-list__title {
        font-weight: 700;
        color: #b60304;
        margin-bottom: 8px;
        font-size: 14px;
    }
    .bulk-errors-list ul {
        margin: 0;
        padding-left: 18px;
        font-size: 13px;
        color: #555;
    }
    .bulk-errors-list ul li {
        margin-bottom: 4px;
    }
    @media (max-width: 767.98px) {
        .bulk-upload-card {
            padding: 20px 16px;
        }
    }
</style>
@endpush

@section('body-content')
<main>
    <section class="dashboard bulk-upload-page">
        <div class="container">
            <div class="row">
                @include('profile.sidebar')

                <div class="col-12 col-lg-9">

                    {{-- Back link --}}
                    <div style="margin-bottom:20px;">
                        <a href="{{ route('user.select-car-purpose') }}" style="font-size:14px;color:#b60304;text-decoration:none;">
                            &larr; {{ __('Back to Place Ad') }}
                        </a>
                    </div>

                    {{-- Validation errors from CSV header/file --}}
                    @if ($errors->any())
                        <div class="bulk-errors-list">
                            <div class="bulk-errors-list__title">{{ __('Upload Error') }}</div>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Row-level errors from the previous import --}}
                    @if (session('bulk_errors') && count(session('bulk_errors')) > 0)
                        <div class="bulk-errors-list">
                            <div class="bulk-errors-list__title">{{ __('Some rows were skipped') }}</div>
                            <ul>
                                @foreach (session('bulk_errors') as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Step 1: Download sample --}}
                    <div class="bulk-upload-card">
                        <div class="bulk-upload-card__title">{{ __('Step 1 — Download the sample CSV') }}</div>
                        <div class="bulk-upload-card__sub">
                            {{ __('Download the sample file below, fill in your vehicle details (one row per car), and save it as a CSV. Do not change the column headers.') }}
                        </div>

                        <a href="{{ route('user.car.bulk-upload.sample') }}" class="bulk-upload-sample-btn">
                            &#8595; {{ __('Download Sample CSV') }}
                        </a>

                        <div style="margin-top:24px;overflow-x:auto;">
                            <table class="bulk-upload-columns-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Column') }}</th>
                                        <th>{{ __('Required') }}</th>
                                        <th>{{ __('Notes') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $colMeta = [
                                            'title'           => ['req' => true,  'note' => __('Ad headline, e.g. "2019 Ford Focus 1.0 Petrol"')],
                                            'description'     => ['req' => true,  'note' => __('Full description of the vehicle')],
                                            'brand'           => ['req' => true,  'note' => __('Must exactly match a brand in the system (e.g. Toyota, Ford)')],
                                            'car_model'       => ['req' => false, 'note' => __('Model name (e.g. Corolla)')],
                                            'condition'       => ['req' => true,  'note' => __('Must be: New or Used')],
                                            'price'           => ['req' => true,  'note' => __('Numeric price in EUR (e.g. 15500)')],
                                            'year'            => ['req' => false, 'note' => __('4-digit year (e.g. 2018)')],
                                            'mileage'         => ['req' => false, 'note' => __('Numeric mileage (e.g. 85000)')],
                                            'mileage_unit'    => ['req' => false, 'note' => __('km or miles — required if mileage is provided')],
                                            'fuel_type'       => ['req' => false, 'note' => __('e.g. Diesel, Petrol, Electric, Hybrid')],
                                            'transmission'    => ['req' => false, 'note' => __('e.g. Manual, Automatic')],
                                            'body_type'       => ['req' => false, 'note' => __('e.g. Saloon, SUV, Hatchback')],
                                            'engine_size'     => ['req' => false, 'note' => __('Engine size in cc (e.g. 1400)')],
                                            'drive'           => ['req' => false, 'note' => __('e.g. Front Wheel Drive, All Wheel Drive')],
                                            'interior_color'  => ['req' => false, 'note' => __('e.g. Black, Beige')],
                                            'exterior_color'  => ['req' => false, 'note' => __('e.g. White, Silver')],
                                            'number_of_owner' => ['req' => false, 'note' => __('Number of previous owners')],
                                            'warranty_months' => ['req' => false, 'note' => __('Warranty in months (e.g. 12)')],
                                            'city'            => ['req' => true,  'note' => __('Must exactly match a city in the system (e.g. Dublin)')],
                                        ];
                                    @endphp
                                    @foreach ($columns as $col)
                                        @php $m = $colMeta[$col] ?? ['req' => false, 'note' => '']; @endphp
                                        <tr>
                                            <td><code>{{ $col }}</code></td>
                                            <td class="{{ $m['req'] ? 'col-required' : 'col-optional' }}">
                                                {{ $m['req'] ? __('Required') : __('Optional') }}
                                            </td>
                                            <td>{{ $m['note'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Step 2: Upload CSV --}}
                    <div class="bulk-upload-card">
                        <div class="bulk-upload-card__title">{{ __('Step 2 — Upload your completed CSV') }}</div>
                        <div class="bulk-upload-card__sub">
                            {{ __('Select your filled CSV file and click Import. Each row will be saved as a draft ad.') }}
                        </div>

                        <div class="bulk-upload-notice">
                            <strong>{{ __('What happens after import?') }}</strong>
                            {{ __('Each row is created as a disabled (draft) ad. Go to Manage Vehicle Ads, open each ad, upload images, and then enable the ad to publish it live.') }}
                        </div>

                        <form method="POST" action="{{ route('user.car.bulk-upload.store') }}" enctype="multipart/form-data" id="bulkUploadForm">
                            @csrf

                            <div class="bulk-upload-file-zone" id="fileZone">
                                <input type="file" name="csv_file" id="csv_file" accept=".csv,text/csv">
                                <div class="bulk-upload-file-zone__icon">&#128196;</div>
                                <div class="bulk-upload-file-zone__label">{{ __('Click to select your CSV file') }}</div>
                                <div class="bulk-upload-file-zone__hint">{{ __('Only .csv files accepted, max 5 MB') }}</div>
                                <div class="bulk-upload-file-zone__selected" id="fileSelectedName" style="display:none;"></div>
                            </div>

                            <button type="submit" class="bulk-upload-submit-btn" id="bulkSubmitBtn" disabled>
                                {{ __('Import Vehicles') }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>

    @include('profile.logout')
</main>
@endsection

@push('js_section')
<script>
(function () {
    var fileInput = document.getElementById('csv_file');
    var fileZone  = document.getElementById('fileZone');
    var fileName  = document.getElementById('fileSelectedName');
    var submitBtn = document.getElementById('bulkSubmitBtn');
    var form      = document.getElementById('bulkUploadForm');

    if (!fileInput) return;

    fileInput.addEventListener('change', function () {
        if (this.files && this.files.length > 0) {
            fileName.textContent = this.files[0].name;
            fileName.style.display = 'block';
            submitBtn.disabled = false;
        } else {
            fileName.style.display = 'none';
            submitBtn.disabled = true;
        }
    });

    // Drag-over visual feedback.
    fileZone.addEventListener('dragover', function (e) {
        e.preventDefault();
        fileZone.classList.add('drag-over');
    });
    fileZone.addEventListener('dragleave', function () {
        fileZone.classList.remove('drag-over');
    });
    fileZone.addEventListener('drop', function (e) {
        fileZone.classList.remove('drag-over');
    });

    // Show spinner on submit.
    form.addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitBtn.textContent = '{{ __("Importing...") }}';
    });
})();
</script>
@endpush
