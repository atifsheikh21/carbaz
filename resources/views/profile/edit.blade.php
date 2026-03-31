@extends('layout')
@section('title')
    <title>{{ __('translate.Edit Profile') }}</title>
@endsection


@section('body-content')

<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Edit Profile') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Edit Profile') }}</li>
                    </ol>
                </nav>
            </div>
            </div>
        </div>
    </section>
    <!-- banner-part-end -->




    <!-- dashboard-part-start -->
    <section class="dashboard">
        <div class="container">
            <div class="row">

                @include('profile.sidebar')


                <div class="col-lg-9">
                    <!-- Profile Settings  -->

                    <div class="row join-a-dealer-bg">
                        <div class="col-lg-8">
                            <h3 class="dealers-information">{{ __('translate.Profile Information') }}</h3>


                            <form action="{{ route('user.update-profile') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="join-a-dealer-form-main">
                                    <div class="join-a-dealer-form-item">
                                        <div class="join-a-dealer-form-inner">
                                            <label for="exampleFormControlInput1" class="form-label">{{ __('translate.Name') }}
                                                <span>*</span></label>
                                            <input type="text" class="form-control" id="exampleFormControlInput1"
                                                placeholder="{{ __('translate.Name') }}" name="name" value="{{ html_decode($user->name) }}">
                                        </div>

                                    </div>

                                    <div class="join-a-dealer-form-item">
                                        <div class="join-a-dealer-form-inner">
                                            <label for="exampleFormControlInput2" class="form-label">
                                                {{ __('translate.Email') }}
                                                <span>*</span></label>
                                            <input type="email" class="form-control" id="exampleFormControlInput2"
                                                placeholder="{{ __('translate.Email') }}" name="email" value="{{ html_decode($user->email) }}" readonly>
                                        </div>
                                        <div class="join-a-dealer-form-inner">

                                            <label for="exampleFormControlInput3" class="form-label">
                                                {{ __('translate.Phone number') }}
                                                <span>*</span></label>

                                            <input type="text" value="{{ html_decode($user->phone) }}" class="form-control"
                                                id="exampleFormControlInput3" name="phone">
                                        </div>
                                    </div>

                                    <div class="join-a-dealer-form-item">
                                        <div class="join-a-dealer-form-inner">
                                            <label for="user_type" class="form-label">{{ __('User Type') }}
                                                <span>*</span></label>
                                            <input type="text" class="form-control" id="user_type" name="user_type" value="{{ $user->is_dealer ? __('translate.Dealer') : __('translate.Individual') }}" readonly>
                                        </div>
                                    </div>

                                    <div class="join-a-dealer-form-item">

                                        <div class="join-a-dealer-form-inner">
                                            <label for="country" class="form-label">{{ __('translate.Country') }}
                                                <span>*</span></label>
                                            <input type="text" class="form-control" id="country" name="country" value="Ireland" readonly>
                                        </div>

                                        <div class="join-a-dealer-form-inner">
                                            <label for="city_id" class="form-label">{{ __('translate.City') }}
                                                <span>*</span></label>
                                            <select class="form-control" id="city_id" name="city_id" disabled>
                                                <option value="">{{ __('translate.Select City') }}</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" {{ (string) $user->city_id === (string) $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    @if ($user->is_dealer)
                                        <div class="join-a-dealer-form-item">
                                            <div class="join-a-dealer-form-inner">
                                                <label for="postal_code" class="form-label">{{ __('Post Code') }}
                                                    <span>*</span></label>
                                                <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ html_decode($user->postal_code) }}" readonly>
                                            </div>
                                        </div>

                                        <div class="join-a-dealer-form-item">
                                            <div class="join-a-dealer-form-inner">
                                                <label for="vehicle_company_name" class="form-label">{{ __('Vehicle company name') }}</label>
                                                <input type="text" class="form-control" id="vehicle_company_name" name="vehicle_company_name" value="{{ old('vehicle_company_name', html_decode($user->vehicle_company_name)) }}">
                                            </div>

                                            <div class="join-a-dealer-form-inner">
                                                <label for="vehicle_company_address" class="form-label">{{ __('Vehicle company address') }}</label>
                                                <input type="text" class="form-control" id="vehicle_company_address" name="vehicle_company_address" value="{{ old('vehicle_company_address', html_decode($user->vehicle_company_address)) }}">
                                            </div>
                                        </div>

                                        <div class="join-a-dealer-form-item">
                                            <div class="join-a-dealer-form-inner">
                                                <label for="part_company_name" class="form-label">{{ __('Car part company name') }}</label>
                                                <input type="text" class="form-control" id="part_company_name" name="part_company_name" value="{{ old('part_company_name', html_decode($user->part_company_name)) }}">
                                            </div>

                                            <div class="join-a-dealer-form-inner">
                                                <label for="part_company_address" class="form-label">{{ __('Car part company address') }}</label>
                                                <input type="text" class="form-control" id="part_company_address" name="part_company_address" value="{{ old('part_company_address', html_decode($user->part_company_address)) }}">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="join-a-dealer-form-item">

                                        <div class="join-a-dealer-form-inner">
                                            <label for="exampleFormControlTextarea6" class="form-label">
                                                {{ __('translate.About Me') }}
                                                </label>
                                            <textarea class="form-control" id="exampleFormControlTextarea6" rows="5"
                                                placeholder="{{ __('translate.About Me') }}" name="about_me">{{ html_decode($user->about_me) }}</textarea>
                                        </div>

                                    </div>

                                </div>

                                <div class="terms-and-conditions-btn">
                                    <button type="submit" class="thm-btn-two">{{ __('translate.Update') }}</button>
                                </div>


                                </div>



                            </form>
                        </div>


                        <div class="col-lg-4">
                            <div class="upload-picture">
                                <div class="upload-picture-img">
                                    <img id="preview-user-avatar-edit-page" src="{{ getImageOrPlaceholder($user->image, '68x68') }}" alt="img">
                                </div>

                                <div class="upload-picture-btn">
                                    <button class="thm-btn-two">
                                        <span>
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M16.5932 10.9551C17.0785 10.9551 17.5147 10.9551 17.9789 10.9551C17.9859 11.0755 18 11.1747 18 11.2739C18 12.762 18 14.25 18 15.738C18 17.0985 17.1067 17.9984 15.7491 17.9984C11.2474 17.9984 6.7456 17.9984 2.25088 17.9984C0.893318 17.9984 0 17.0985 0 15.738C0 14.1579 0 12.5777 0 10.9622C0.464244 10.9622 0.91442 10.9622 1.4068 10.9622C1.4068 11.0897 1.4068 11.2173 1.4068 11.3377C1.4068 12.7903 1.4068 14.2429 1.4068 15.6955C1.4068 16.3191 1.66002 16.5742 2.28605 16.5742C6.75967 16.5742 11.2403 16.5742 15.714 16.5742C16.34 16.5742 16.5932 16.3191 16.5932 15.6955C16.5932 14.1366 16.5932 12.5636 16.5932 10.9551Z" />
                                                <path
                                                    d="M8.30687 2.60052C7.37838 3.53585 6.52023 4.40033 5.64802 5.27898C5.31742 4.91051 5.01496 4.57748 4.72656 4.25861C6.11929 2.86269 7.56126 1.41009 8.96103 0C10.3749 1.42426 11.8239 2.87687 13.2729 4.33656C13.0126 4.59165 12.682 4.90343 12.3233 5.25063C11.4862 4.40742 10.6281 3.53585 9.71367 2.6076C9.71367 6.3206 9.71367 9.94857 9.71367 13.6049C9.23535 13.6049 8.78518 13.6049 8.3139 13.6049C8.30687 9.96274 8.30687 6.33477 8.30687 2.60052Z" />
                                            </svg>
                                        </span>
                                        {{ __('translate.Upload a Picture') }}
                                    </button>

                                    <form id="upload_user_avatar_edit_form" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <input type="file" name="image" onchange="previewEditPageImage(event)" >
                                    </form>
                                </div>

                                <div class="upload-picture-text">
                                    <h5>{{ __('translate.Upload Your Image') }}</h5>
                                    <h6>{{ __('translate.Choose a image PNG, JPEG, JPG') }}</h6>

                                    <h6><span>{{ __('translate.Note') }}:</span> {{ __('translate.Max File Size 1MB') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- dashboard-part-end -->


    @include('profile.logout')


</main>

@endsection



@push('js_section')
<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            $("#upload_user_avatar_edit_form").on("submit", function(e){
                e.preventDefault();

                var isDemo = "{{ env('APP_MODE') }}"
                if(isDemo == 'DEMO'){
                    toastr.error('This Is Demo Version. You Can Not Change Anything');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    data:new FormData(this),
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: "{{ route('user.upload-user-avatar') }}",
                    success: function (response) {
                        toastr.success(response.message)
                    },
                    error: function(response) {
                        console.log(response);
                        toastr.error(response.responseJSON.image.message)
                    }
                });
            })
        });
    })(jQuery);


    function previewEditPageImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview-user-avatar-edit-page');
            output.src = reader.result;

            var output_sidebar = document.getElementById('preview-user-avatar');
            output_sidebar.src = reader.result;
        }

        reader.readAsDataURL(event.target.files[0]);
        $("#upload_user_avatar_edit_form").submit();
    };
</script>

@endpush
