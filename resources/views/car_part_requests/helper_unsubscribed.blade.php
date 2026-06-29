@extends('layout')
@section('title')
    <title>Helper alerts unsubscribed</title>
@endsection

@section('body-content')
<main>
    <section class="login">
        <div class="container">
            <div class="row login-bg">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="login-head">
                        <h3>You've been removed from the helper list.</h3>
                        <span>You can re-enable this in account settings.</span>
                    </div>
                    <a href="{{ route('car-part-requests.index') }}" class="thm-btn-two">Back to forum</a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
