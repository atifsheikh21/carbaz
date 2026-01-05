@extends('admin.master_layout')

@section('title')
    <title>{{ __('translate.Edit Menu Item') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Edit Menu Item') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Menu') }} >> {{ __('translate.Edit Menu Item') }}</p>
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
                                <div class="col-12 mg-top-30">
                                    <!-- Product Card -->
                                    <div class="crancy-product-card translation_main_box">

                                        <div class="crancy-customer-filter">
                                            <div class="crancy-customer-filter__single crancy-customer-filter__single--csearch">
                                                <div class="crancy-header__form crancy-header__form--customer">
                                                    <h4 class="crancy-product-card__title">{{ __('translate.Switch to language translation') }}</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="translation_box">
                                            <ul >
                                                @foreach ($language_list as $language)
                                                <li><a href="{{ route('admin.menus.menu-items.edit', ['menu_id' => $menuItem->id, 'lang_code' => $language->lang_code] ) }}">
                                                    @if (request()->get('lang_code') == $language->lang_code)
                                                        <i class="fas fa-eye"></i>
                                                    @else
                                                        <i class="fas fa-edit"></i>
                                                    @endif

                                                    {{ $language->lang_name }}</a></li>
                                                @endforeach
                                            </ul>

                                            <div class="alert alert-secondary" role="alert">

                                                @php
                                                    $edited_language = $language_list->where('lang_code', request()->get('lang_code'))->first();
                                                @endphp

                                            <p>{{ __('translate.Your editing mode') }} : <b>{{ $edited_language->lang_name }}</b></p>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- End Product Card -->
                                </div>
                            </div>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->

    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <form action="{{ route('admin.menus.menu-items.update', $menuItem) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="lang_code" value="{{ $menu_translate->locale }}">
                                <input type="hidden" name="translate_id" value="{{ $menu_translate->id }}">

                                <div class="row">
                                    <div class="col-12">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('translate.Edit Menu Item') }}</h4>

                                                <a href="{{ route('admin.menus.menu-items.index', $menu) }}" class="crancy-btn "><i class="fa fa-list"></i> {{ __('translate.Back to Menu Items') }}</a>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Title') }} ({{ __('translate.Default') }}) * </label>
                                                        <input class="crancy__item-input @error('title') is-invalid @enderror" type="text" name="title" value="{{ old('title', $menu_translate->title) }}">
                                                        @error('title')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                @if (admin_lang() == request()->get('lang_code'))
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                            <label class="crancy__item-label">{{ __('translate.CSS Class') }}</label>
                                                            <input type="text" class="crancy__item-input @error('css_class') is-invalid @enderror" 
                                                                   id="css_class" name="css_class" value="{{ old('css_class', $menuItem->css_class) }}">
                                                            @error('css_class')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                            <label class="crancy__item-label">{{ __('translate.Target') }}</label>
                                                            <select class="crancy__item-select @error('target') is-invalid @enderror" 
                                                                    id="target" name="target" required>
                                                                <option value="_self" {{ old('target', $menuItem->target) == '_self' ? 'selected' : '' }}>{{ __('translate.Same Window') }}</option>
                                                                <option value="_blank" {{ old('target', $menuItem->target) == '_blank' ? 'selected' : '' }}>{{ __('translate.New Window') }}</option>
                                                                <option value="_parent" {{ old('target', $menuItem->target) == '_parent' ? 'selected' : '' }}>{{ __('translate.Parent Frame') }}</option>
                                                                <option value="_top" {{ old('target', $menuItem->target) == '_top' ? 'selected' : '' }}>{{ __('translate.Top Frame') }}</option>
                                                            </select>
                                                            @error('target')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                            <label class="crancy__item-label">{{ __('translate.Parent Item') }}</label>
                                                            <select class="crancy__item-select @error('parent_id') is-invalid @enderror" 
                                                                    id="parent_id" name="parent_id">
                                                                <option value="">{{ __('translate.No Parent (Root Item)') }}</option>
                                                                @foreach($parentItems as $parent)
                                                                    <option value="{{ $parent->id }}" 
                                                                            {{ old('parent_id', $menuItem->parent_id) == $parent->id ? 'selected' : '' }}>
                                                                        {{ $parent->translated_title }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('parent_id')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                            <label class="crancy__item-label">{{ __('translate.Sort Order') }}</label>
                                                            <input type="number" class="crancy__item-input @error('sort_order') is-invalid @enderror" 
                                                                   id="sort_order" name="sort_order" value="{{ old('sort_order', $menuItem->sort_order) }}" min="0">
                                                            @error('sort_order')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                            <label class="crancy__item-label">{{ __('translate.URL / Route') }}</label>
                                                            <select class="crancy__item-select @error('url') is-invalid @enderror" name="url" id="url">
                                                                <option value="custom" {{ old('url') === 'custom' ? 'selected' : '' }}>
                                                                    {{ __('translate.Custom URL (Manual Entry)') }}
                                                                </option>
                                                                @foreach($frontendRoutes as $route)
                                                                    <option value="{{ $route['route'] }}" 
                                                                        {{ old('url') === $route['route'] ? 'selected' : '' }}>
                                                                        {{ $route['name'] }} ({{ $route['route'] }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('url')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="crancy__item-form--group mg-top-form-20" id="custom_url_input">
                                                            <label class="crancy__item-label">{{ __('translate.URL') }}</label>
                                                            <input class="crancy__item-input" type="text" name="custom_url" value="{{ old('url', $menuItem->url) }}">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                            <label class="crancy__item-label">{{__('Visibility Status')}} </label>
                                                            <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                                <label class="crancy__item-switch">
                                                                <input value="1" {{ old('is_active', $menuItem->is_active) ? 'checked' : '' }} name="is_active" type="checkbox" >
                                                                <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                            </div>

                                            <button class="crancy-btn mg-top-25" type="submit">{{ __('translate.Update') }}</button>

                                        </div>
                                        <!-- End Product Card -->
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->
@endsection

@push('js_section')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlSelect = document.getElementById('url');
        const customUrlInput = document.getElementById('custom_url_input');

        function toggleCustomInput() {
            if (urlSelect.value === 'custom') {
                customUrlInput.style.display = 'block';
            } else {
                customUrlInput.style.display = 'none';
            }
        }

        urlSelect.addEventListener('change', toggleCustomInput);
        toggleCustomInput(); // initial check
    });
</script>
@endpush
