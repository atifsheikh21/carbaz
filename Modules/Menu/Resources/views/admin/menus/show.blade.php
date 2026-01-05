@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Menu Details') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Menu Details') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Car') }} >> {{ __('translate.Menu List') }} >> {{ __('translate.View') }}</p>
@endsection

@section('body-content')
    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="crancy-form crancy-form--v2">
                                
                                <!-- Menu Details Card -->
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">{{ __('translate.Menu Information') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">{{ __('translate.Menu Name') }}:</label>
                                                    <p class="form-control-static">{{ $menu->name }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">{{ __('translate.Location') }}:</label>
                                                    <p class="form-control-static">
                                                        <span class="badge bg-info text-white">{{ ucfirst($menu->location) }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">{{ __('translate.Status') }}:</label>
                                                    <p class="form-control-static">
                                                        @if($menu->is_active)
                                                            <span class="badge bg-success text-white">{{ __('translate.Active') }}</span>
                                                        @else
                                                            <span class="badge bg-danger text-white">{{ __('translate.Inactive') }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">{{ __('translate.Sort Order') }}:</label>
                                                    <p class="form-control-static">{{ $menu->sort_order }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        @if($menu->translations->count() > 0)
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">{{ __('translate.Translations') }}:</label>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{ __('translate.Language') }}</th>
                                                                        <th>{{ __('translate.Name') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($menu->translations as $translation)
                                                                        <tr>
                                                                            <td>{{ strtoupper($translation->locale) }}</td>
                                                                            <td>{{ $translation->name }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Menu Items Card -->
                                <div class="card mt-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">{{ __('translate.Menu Items') }}</h4>
                                        <a href="{{ route('admin.menus.menu-items.index', $menu) }}" class="crancy-btn">
                                            <i class="fas fa-list"></i> {{ __('translate.Manage Items') }}
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        @if($menu->menuItems->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('translate.Title') }}</th>
                                                            <th>{{ __('translate.URL') }}</th>
                                                            <th>{{ __('translate.Parent') }}</th>
                                                            <th>{{ __('translate.Status') }}</th>
                                                            <th>{{ __('translate.Sort Order') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($menu->menuItems->where('parent_id', null) as $item)
                                                            <tr>
                                                                <td>{{ $item->title }}</td>
                                                                <td>{{ $item->url }}</td>
                                                                <td>-</td>
                                                                <td>
                                                                    @if($item->is_active)
                                                                        <span class="badge bg-success text-white">{{ __('translate.Active') }}</span>
                                                                    @else
                                                                        <span class="badge bg-danger text-white">{{ __('translate.Inactive') }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $item->sort_order }}</td>
                                                            </tr>
                                                            @foreach($item->children as $child)
                                                                <tr>
                                                                    <td style="padding-left: 30px;">↳ {{ $child->title }}</td>
                                                                    <td>{{ $child->url }}</td>
                                                                    <td>{{ $item->title }}</td>
                                                                    <td>
                                                                        @if($child->is_active)
                                                                            <span class="badge bg-success text-white">{{ __('translate.Active') }}</span>
                                                                        @else
                                                                            <span class="badge bg-danger text-white">{{ __('translate.Inactive') }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $child->sort_order }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-center text-muted">{{ __('translate.No menu items found') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="crancy-form__single">
                                    <div class="crancy-form__btn">
                                        <a href="{{ route('admin.menus.edit', $menu) }}" class="crancy-btn">
                                            <i class="fas fa-edit"></i> {{ __('translate.Edit Menu') }}
                                        </a>
                                        <a href="{{ route('admin.menus.index') }}" class="crancy-btn crancy-btn--secondary">
                                            {{ __('translate.Back to List') }}
                                        </a>
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