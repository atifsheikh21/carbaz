<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .menu-test { margin: 20px 0; padding: 20px; border: 1px solid #ccc; }
        .menu-test h3 { color: #333; }
        .menu-test ul { list-style: none; padding: 0; }
        .menu-test li { margin: 5px 0; }
        .menu-test a { text-decoration: none; color: #007bff; }
        .menu-test a:hover { text-decoration: underline; }
        .sub-menu { margin-left: 20px; }
    </style>
</head>
<body>
    <h1>Menu System Test</h1>
    
    <div class="menu-test">
        <h3>Header Menu (Dynamic)</h3>
        @if(App\Helpers\MenuHelper::hasMenu('header'))
            {!! App\Helpers\MenuHelper::renderMenu('header', [
                'ul_class' => 'menu-list',
                'li_class' => 'menu-item',
                'a_class' => 'menu-link',
                'submenu_class' => 'sub-menu',
                'active_class' => 'active'
            ]) !!}
        @else
            <p>No header menu found. Please create one in the admin panel.</p>
        @endif
    </div>

    <div class="menu-test">
        <h3>Footer Menu (Dynamic)</h3>
        @if(App\Helpers\MenuHelper::hasMenu('footer'))
            {!! App\Helpers\MenuHelper::renderMenu('footer', [
                'ul_class' => 'menu-list',
                'li_class' => 'menu-item',
                'a_class' => 'menu-link',
                'submenu_class' => 'sub-menu',
                'active_class' => 'active'
            ]) !!}
        @else
            <p>No footer menu found. Please create one in the admin panel.</p>
        @endif
    </div>

    <div class="menu-test">
        <h3>Menu Information</h3>
        <p><strong>Current Language:</strong> {{ app()->getLocale() }}</p>
        <p><strong>Available Languages:</strong> {{ implode(', ', config('app.available_locales', ['en'])) }}</p>
    </div>

    <div class="menu-test">
        <h3>Admin Links</h3>
        <ul>
            <li><a href="{{ route('admin.menus.index') }}">Manage Menus</a></li>
            <li><a href="{{ route('admin.menus.create') }}">Create New Menu</a></li>
        </ul>
    </div>
</body>
</html> 