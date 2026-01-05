@extends('admin.master_layout')

@section('title')
    <title>Menu Demo</title>
@endsection

@section('body-content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h3>Desktop Menu</h3>
            <x-menu menu="1" class="nav-links" />
        </div>
        
        <div class="col-md-6">
            <h3>Mobile Menu</h3>
            <x-mobile-menu menu="1" />
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <h3>Using Helper Functions</h3>
            <div class="desktop-menu">
                {!! render_menu(1, 'nav-links') !!}
            </div>
            
            <div class="mobile-menu mt-3">
                {!! render_mobile_menu(1) !!}
            </div>
        </div>
    </div>
</div>
@endsection 