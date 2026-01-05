@extends('admin.master_layout')

@section('title')
    <title>{{ __('translate.Menu Item Details') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Menu Item Details') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Menu') }} >> {{ __('translate.Menu Item Details') }}</p>
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

                                <div class="crancy-customer-filter">
                                    <div class="crancy-customer-filter__single crancy-customer-filter__single--csearch d-flex items-center justify-between create_new_btn_box">
                                        <div class="crancy-header__form crancy-header__form--customer create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Menu Item Details') }}</h4>

                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.menus.menu-items.index', $menu) }}" class="crancy-btn">
                                                    <i class="fas fa-arrow-left"></i> {{ __('translate.Back to Menu Items') }}
                                                </a>
                                                <a href="{{ route('admin.menus.menu-items.edit', $menuItem) }}" class="crancy-btn">
                                                    <i class="fas fa-edit"></i> {{ __('translate.Edit') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Item Details -->
                                <div class="crancy-dashboard__content-inner mg-top-30">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="crancy-table crancy-table--v3">
                                                <div class="crancy-customer-filter">
                                                    <div class="crancy-customer-filter__single">
                                                        <h4 class="crancy-product-card__title">{{ __('translate.Basic Information') }}</h4>
                                                    </div>
                                                </div>
                                                
                                                <div class="crancy-table__main crancy-table__main-v3">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th width="30%" class="crancy-table__column-2 crancy-table__h2">{{ __('translate.Title') }}</th>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">{{ $menuItem->translated_title }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.URL') }}</th>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">{{ $menuItem->url ?: '#' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.Target') }}</th>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">{{ $menuItem->target }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.Icon') }}</th>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">
                                                                @if($menuItem->icon)
                                                                    <i class="{{ $menuItem->icon }}"></i> {{ $menuItem->icon }}
                                                                @else
                                                                    <span class="text-muted">{{ __('translate.No icon') }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.CSS Class') }}</th>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">{{ $menuItem->css_class ?: __('translate.None') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.Parent') }}</th>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">
                                                                @if($menuItem->parent)
                                                                    {{ $menuItem->parent->translated_title }}
                                                                @else
                                                                    <span class="text-muted">{{ __('translate.Root item') }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.Status') }}</th>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">
                                                                <span class="badge {{ $menuItem->is_active ? 'bg-success' : 'bg-danger' }} text-white">
                                                                    {{ $menuItem->is_active ? __('translate.Active') : __('translate.Inactive') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.Sort Order') }}</th>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">{{ $menuItem->sort_order }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="crancy-table crancy-table--v3">
                                                <div class="crancy-customer-filter">
                                                    <div class="crancy-customer-filter__single">
                                                        <h4 class="crancy-product-card__title">{{ __('translate.Translations') }}</h4>
                                                    </div>
                                                </div>
                                                
                                                <div class="crancy-table__main crancy-table__main-v3">
                                                    <table class="crancy-table__main crancy-table__main-v3">
                                                        <thead class="crancy-table__head">
                                                            <tr>
                                                                <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.Language') }}</th>
                                                                <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.Title') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="crancy-table__body">
                                                            @foreach($menuItem->translations as $translation)
                                                            <tr>
                                                                <td class="crancy-table__column-2 crancy-table__data-2">
                                                                    <span class="badge bg-info text-white">{{ strtoupper($translation->locale) }}</span>
                                                                </td>
                                                                <td class="crancy-table__column-2 crancy-table__data-2">{{ $translation->title }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                @if($menuItem->hasChildren())
                                                <div class="crancy-customer-filter mg-top-20">
                                                    <div class="crancy-customer-filter__single">
                                                        <h4 class="crancy-product-card__title">{{ __('translate.Child Items') }}</h4>
                                                    </div>
                                                </div>
                                                
                                                <div class="crancy-table__main crancy-table__main-v3">
                                                    <table class="crancy-table__main crancy-table__main-v3">
                                                        <thead class="crancy-table__head">
                                                            <tr>
                                                                <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.Title') }}</th>
                                                                <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.Sort Order') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="crancy-table__body">
                                                            @foreach($menuItem->children as $child)
                                                            <tr>
                                                                <td class="crancy-table__column-2 crancy-table__data-2">{{ $child->translated_title }}</td>
                                                                <td class="crancy-table__column-2 crancy-table__data-2">
                                                                    <span class="badge bg-primary text-white">{{ $child->sort_order }}</span>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @endif
                                            </div>
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
    <!-- End crancy Dashboard -->
@endsection

@push('js_section')

@endpush