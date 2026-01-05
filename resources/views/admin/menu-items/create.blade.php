@extends('admin.master_layout')
@section('title')
<title>{{ __('translate.Create Menu Item') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Create Menu Item') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Menu Management') }} >> {{ __('translate.Create Menu Item') }}</p>
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
                                            <h3 class="crancy-card__title">{{ __('translate.Create Menu Item') }}</h3>
                                            <div class="crancy-card__header-right">
                                                <a href="{{ route('admin.menus.menu-items.index', $menu) }}" class="crancy-btn crancy-btn__v2">
                                                    <i class="fas fa-arrow-left"></i> {{ __('translate.Back to Menu Items') }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="crancy-card__body">
                    <form action="{{ route('admin.menus.menu-items.store', $menu) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">{{ __('translate.Title') }} ({{ __('translate.Default') }})</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="url">{{ __('translate.URL') }}</label>
                                    <input type="text" class="form-control @error('url') is-invalid @enderror" 
                                           id="url" name="url" value="{{ old('url') }}" placeholder="/about-us">
                                    @error('url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="target">{{ __('translate.Target') }}</label>
                                    <select class="form-control @error('target') is-invalid @enderror" 
                                            id="target" name="target" required>
                                        <option value="_self" {{ old('target') == '_self' ? 'selected' : '' }}>{{ __('translate.Same Window') }}</option>
                                        <option value="_blank" {{ old('target') == '_blank' ? 'selected' : '' }}>{{ __('translate.New Window') }}</option>
                                        <option value="_parent" {{ old('target') == '_parent' ? 'selected' : '' }}>{{ __('translate.Parent Frame') }}</option>
                                        <option value="_top" {{ old('target') == '_top' ? 'selected' : '' }}>{{ __('translate.Top Frame') }}</option>
                                    </select>
                                    @error('target')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="parent_id">{{ __('translate.Parent Item') }}</label>
                                    <select class="form-control @error('parent_id') is-invalid @enderror" 
                                            id="parent_id" name="parent_id">
                                        <option value="">{{ __('translate.No Parent (Root Item)') }}</option>
                                        @foreach($parentItems as $parent)
                                            <option value="{{ $parent->id }}" 
                                                    {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->translated_title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sort_order">{{ __('translate.Sort Order') }}</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icon">{{ __('translate.Icon') }} ({{ __('translate.Font Awesome') }})</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon') }}" 
                                           placeholder="fas fa-home">
                                    @error('icon')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="css_class">{{ __('translate.CSS Class') }}</label>
                                    <input type="text" class="form-control @error('css_class') is-invalid @enderror" 
                                           id="css_class" name="css_class" value="{{ old('css_class') }}">
                                    @error('css_class')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">{{ __('translate.Active') }}</label>
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
                                            <label>{{ __('translate.Title') }} ({{ strtoupper($language) }})</label>
                                            <input type="text" class="form-control" 
                                                   name="translations[{{ $index }}][title]" 
                                                   value="{{ old("translations.{$index}.title") }}"
                                                   placeholder="{{ __('translate.Enter title in') }} {{ strtoupper($language) }}">
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
                                <i class="fas fa-save"></i> {{ __('translate.Create Menu Item') }}
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
                        <label>{{ __('translate.Title') }}</label>
                        <input type="text" class="form-control" 
                               name="translations[${translationIndex}][title]" 
                               placeholder="{{ __('translate.Enter title') }}">
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