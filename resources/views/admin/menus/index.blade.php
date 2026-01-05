@extends('admin.master_layout')
@section('title')
<title>{{ __('translate.Menu Management') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Menu Management') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Menu Management') }} >> {{ __('translate.Menu List') }}</p>
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
                                            <h3 class="crancy-card__title">{{ __('translate.Menu List') }}</h3>
                                            <div class="crancy-card__header-right">
                                                <a href="{{ route('admin.menus.create') }}" class="crancy-btn crancy-btn__v1">
                                                    <i class="fas fa-plus"></i> {{ __('translate.Create Menu') }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="crancy-card__body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('translate.ID') }}</th>
                                    <th>{{ __('translate.Name') }}</th>
                                    <th>{{ __('translate.Location') }}</th>
                                    <th>{{ __('translate.Status') }}</th>
                                    <th>{{ __('translate.Sort Order') }}</th>
                                    <th>{{ __('translate.Items Count') }}</th>
                                    <th>{{ __('translate.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $menu)
                                <tr>
                                    <td>{{ $menu->id }}</td>
                                    <td>{{ $menu->translated_name }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($menu->location) }}</span>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-status" 
                                                   id="status_{{ $menu->id }}" 
                                                   data-url="{{ route('admin.menus.toggle-status', $menu) }}"
                                                   {{ $menu->is_active ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="status_{{ $menu->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $menu->sort_order }}</td>
                                    <td>{{ $menu->menuItems->count() }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.menus.show', $menu) }}" 
                                               class="btn btn-sm btn-info" title="{{ __('translate.View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.menus.edit', $menu) }}" 
                                               class="btn btn-sm btn-primary" title="{{ __('translate.Edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.menus.menu-items.index', $menu) }}" 
                                               class="btn btn-sm btn-success" title="{{ __('translate.Manage Items') }}">
                                                <i class="fas fa-list"></i>
                                            </a>
                                            <form action="{{ route('admin.menus.destroy', $menu) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        title="{{ __('translate.Delete') }}" 
                                                        onclick="return confirm('{{ __('translate.Are you sure you want to delete this menu?') }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('translate.No menus found.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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