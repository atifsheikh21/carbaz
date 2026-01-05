@extends('admin.master_layout')

@section('title')
    <title>Menu Test</title>
@endsection

@section('body-content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h3>Dynamic Menu from Menu Builder</h3>
            <div class="mobile-menu-test">
                @if(App\Helpers\MenuHelper::hasMenu('header'))
                    {!! App\Helpers\MenuHelper::renderMobileMenu('header') !!}
                @else
                    <p>No menu found for 'header' location</p>
                @endif
            </div>
        </div>
        
        <div class="col-md-6">
            <h3>Default Static Menu</h3>
            <div class="mobile-menu-test">
                <ul class="nav-links">
                    <li class="dropdown">
                        <a href="javascript:;">{{ __('translate.Home') }}
                            <span>
                                <i class="fa-solid fa-angle-down"></i>
                            </span>
                        </a>
                        <ul class="d-menu">
                            <li><a href="{{ route('home', ['theme' => 'one']) }}">{{ __('translate.Home-01') }}</a></li>
                            <li><a href="{{ route('home', ['theme' => 'two']) }}">{{ __('translate.Home-02') }}</a></li>
                            <li><a href="{{ route('home', ['theme' => 'three']) }}">{{ __('translate.Home-03') }}</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('about-us') }}">{{ __('translate.About Us') }}</a></li>
                    <li><a href="{{ route('listings') }}">{{ __('translate.Listings') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.mobile-menu-test {
    border: 1px solid #ddd;
    padding: 20px;
    margin: 20px 0;
    background: #f9f9f9;
}

.mobile-menu-test ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.mobile-menu-test li {
    margin: 5px 0;
}

.mobile-menu-test a {
    text-decoration: none;
    color: #333;
    padding: 8px 0;
    display: block;
}

.mobile-menu-test .dropdown > a {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.mobile-menu-test .d-menu {
    margin-left: 20px;
    border-left: 2px solid #eee;
    padding-left: 15px;
}
</style>
@endsection 