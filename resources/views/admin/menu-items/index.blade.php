@extends('admin.master_layout')
@section('title')
<title>{{ __('translate.Menu Items') }} - {{ $menu->translated_name }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Menu Items') }} - {{ $menu->translated_name }}</h3>
    <p class="crancy-header__text">{{ __('translate.Menu Management') }} >> {{ __('translate.Menu Items') }}</p>
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
                                            <h3 class="crancy-card__title">{{ __('translate.Menu Items') }} - {{ $menu->translated_name }}</h3>
                                            <div class="crancy-card__header-right">
                                                <a href="{{ route('admin.menus.menu-items.create', $menu) }}" class="crancy-btn crancy-btn__v1">
                                                    <i class="fas fa-plus"></i> {{ __('translate.Add Menu Item') }}
                                                </a>
                                                <a href="{{ route('admin.menus.index') }}" class="crancy-btn crancy-btn__v2">
                                                    <i class="fas fa-arrow-left"></i> {{ __('translate.Back to Menus') }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="crancy-card__body">
                    @if($menu->menuItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('translate.Title') }}</th>
                                        <th>{{ __('translate.URL') }}</th>
                                        <th>{{ __('translate.Parent') }}</th>
                                        <th>{{ __('translate.Icon') }}</th>
                                        <th>{{ __('translate.Status') }}</th>
                                        <th>{{ __('translate.Sort Order') }}</th>
                                        <th>{{ __('translate.Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($menu->menuItems->where('parent_id', null)->sortBy('sort_order') as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->translated_title }}</strong>
                                            @if($item->hasChildren())
                                                <span class="badge badge-info">{{ $item->children->count() }} {{ __('translate.children') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->url ?: '#' }}</td>
                                        <td>
                                            @if($item->parent)
                                                {{ $item->parent->translated_title }}
                                            @else
                                                <span class="text-muted">{{ __('translate.Root') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->icon)
                                                <i class="{{ $item->icon }}"></i>
                                            @else
                                                <span class="text-muted">{{ __('translate.No icon') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input toggle-status" 
                                                       id="status_{{ $item->id }}" 
                                                       data-url="{{ route('admin.menus.menu-items.toggle-status', $item) }}"
                                                       {{ $item->is_active ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status_{{ $item->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $item->sort_order }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.menus.menu-items.show', $item) }}" 
                                                   class="btn btn-sm btn-info" title="{{ __('translate.View') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.menus.menu-items.edit', $item) }}" 
                                                   class="btn btn-sm btn-primary" title="{{ __('translate.Edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.menus.menu-items.destroy', $item) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            title="{{ __('translate.Delete') }}" 
                                                            onclick="return confirm('{{ __('translate.Are you sure you want to delete this menu item?') }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    {{-- Display child items --}}
                                    @foreach($item->children->sortBy('sort_order') as $child)
                                    <tr>
                                        <td style="padding-left: 30px;">
                                            <i class="fas fa-level-down-alt"></i> {{ $child->translated_title }}
                                        </td>
                                        <td>{{ $child->url ?: '#' }}</td>
                                        <td>
                                            <span class="text-muted">{{ $item->translated_title }}</span>
                                        </td>
                                        <td>
                                            @if($child->icon)
                                                <i class="{{ $child->icon }}"></i>
                                            @else
                                                <span class="text-muted">{{ __('translate.No icon') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input toggle-status" 
                                                       id="status_{{ $child->id }}" 
                                                       data-url="{{ route('admin.menus.menu-items.toggle-status', $child) }}"
                                                       {{ $child->is_active ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status_{{ $child->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $child->sort_order }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.menus.menu-items.show', $child) }}" 
                                                   class="btn btn-sm btn-info" title="{{ __('translate.View') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.menus.menu-items.edit', $child) }}" 
                                                   class="btn btn-sm btn-primary" title="{{ __('translate.Edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.menus.menu-items.destroy', $child) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            title="{{ __('translate.Delete') }}" 
                                                            onclick="return confirm('{{ __('translate.Are you sure you want to delete this menu item?') }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">{{ __('translate.No menu items found.') }}</p>
                            <a href="{{ route('admin.menus.menu-items.create', $menu) }}" class="crancy-btn crancy-btn__v1">
                                <i class="fas fa-plus"></i> {{ __('translate.Add First Menu Item') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-status').change(function() {
        const url = $(this).data('url');
        const checked = $(this).is(':checked');
        
        $.ajax({
            url: url,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('{{ __('translate.Status updated successfully') }}');
                } else {
                    toastr.error('{{ __('translate.Failed to update status') }}');
                }
            },
            error: function() {
                toastr.error('{{ __('translate.Failed to update status') }}');
                // Revert the checkbox
                $(this).prop('checked', !checked);
            }
        });
    });
});
</script>
@endpush 