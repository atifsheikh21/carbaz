@extends('admin.master_layout')
@section('title')
<title>{{ __('translate.Create Menu') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Create Menu') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Menu') }} >> {{ __('translate.Create Menu') }}</p>
@endsection

@section('body-content')
    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12">
                                    <div class="crancy-card">
                                        <div class="crancy-card__header">
                                            <h3 class="crancy-card__title">{{ __('translate.Create New Menu') }}</h3>
                                            <div class="crancy-card__header-right">
                                                <a href="{{ route('admin.menus.index') }}" class="crancy-btn crancy-btn__v2">
                                                    <i class="fas fa-arrow-left"></i> {{ __('translate.Back to Menus') }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="crancy-card__body">
                    <form action="{{ route('admin.menus.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{ __('translate.Name') }} ({{ __('translate.Default') }})</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location">{{ __('translate.Location') }}</label>
                                    <select class="form-control @error('location') is-invalid @enderror" 
                                            id="location" name="location" required>
                                        <option value="header" {{ old('location') == 'header' ? 'selected' : '' }}>{{ __('translate.Header') }}</option>
                                        <option value="footer" {{ old('location') == 'footer' ? 'selected' : '' }}>{{ __('translate.Footer') }}</option>
                                        <option value="sidebar" {{ old('location') == 'sidebar' ? 'selected' : '' }}>{{ __('translate.Sidebar') }}</option>
                                    </select>
                                    @error('location')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order">{{ __('translate.Sort Order') }}</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">{{ __('translate.Active') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <h5>{{ __('translate.Translations') }}</h5>
                        
                        <div id="translations-container">
                            @foreach($languages as $index => $language)
                                @if($language != 'en')
                                <div class="row translation-row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>{{ __('translate.Language') }}</label>
                                            <input type="text" class="form-control" 
                                                   name="translations[{{ $index }}][locale]" 
                                                   value="{{ $language }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>{{ __('translate.Name') }} ({{ strtoupper($language) }})</label>
                                            <input type="text" class="form-control" 
                                                   name="translations[{{ $index }}][name]" 
                                                   value="{{ old("translations.{$index}.name") }}"
                                                   placeholder="{{ __('translate.Enter name in') }} {{ strtoupper($language) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-block remove-translation">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="form-group">
                            <button type="button" class="btn btn-info" id="add-translation">
                                <i class="fas fa-plus"></i> {{ __('translate.Add Translation') }}
                            </button>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="crancy-btn crancy-btn__v1">
                                <i class="fas fa-save"></i> {{ __('translate.Create Menu') }}
                            </button>
                        </div>
                    </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let translationIndex = {{ count($languages) }};
    
    $('#add-translation').click(function() {
        const translationRow = `
            <div class="row translation-row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>{{ __('translate.Language') }}</label>
                        <select class="form-control" name="translations[${translationIndex}][locale]">
                            <option value="">{{ __('translate.Select Language') }}</option>
                            @foreach($languages as $language)
                                <option value="{{ $language }}">{{ strtoupper($language) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>{{ __('translate.Name') }}</label>
                        <input type="text" class="form-control" 
                               name="translations[${translationIndex}][name]" 
                               placeholder="{{ __('translate.Enter name') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-block remove-translation">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        $('#translations-container').append(translationRow);
        translationIndex++;
    });
    
    $(document).on('click', '.remove-translation', function() {
        $(this).closest('.translation-row').remove();
    });
});
</script>
@endpush 