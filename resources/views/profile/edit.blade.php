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

                                        <div class="join-a-dealer-form-inner">
                                            <label for="exampleFormControlInput1" class="form-label">{{ __('translate.Designation') }}
                                                <span>*</span></label>
                                            <input type="text" class="form-control" id="exampleFormControlInput1"
                                                placeholder="{{ __('translate.Designation') }}" name="designation" value="{{ html_decode($user->designation) }}">
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

                                            <label for="exampleFormControlInput4" class="form-label">{{ __('translate.Address') }}
                                                <span>*</span></label>

                                            <input type="text" class="form-control" id="exampleFormControlInput4"
                                                placeholder="{{ __('translate.Address') }}" name="address" value="{{ html_decode($user->address) }}">
                                        </div>
                                    </div>

                                    <div class="join-a-dealer-form-item">

                                        <div class="join-a-dealer-form-inner">
                                            <label for="exampleFormControlTextarea5" class="form-label">{{ __('translate.Google Map Embed Link') }}
                                                </label>
                                            <textarea name="google_map" class="form-control" id="exampleFormControlTextarea5" rows="5"
                                                placeholder="{{ __('translate.Google Map Embed Code') }}">{{ html_decode($user->google_map) }}</textarea>
                                        </div>

                                    </div>

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

                                <!-- Social Link  -->

                                <h3 class="dealers-information two">{{ __('translate.Social Link') }}</h3>


                                <div class="join-a-dealer-form-main two">

                                    <div class="join-a-dealer-form-item">

                                        <div class="join-a-dealer-form-inner">

                                            <label for="exampleFormControlInput10" class="form-label">{{ __('translate.Instagram') }}
                                                </label>

                                            <input type="text" class="form-control" id="exampleFormControlInput10"
                                                placeholder="{{ __('translate.Instagram') }}" name="instagram" value="{{ html_decode($user->instagram) }}">

                                            <div class="icon">
                                                <span>
                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM16 5C16.5523 5 17 4.55228 17 4C17 3.44772 16.5523 3 16 3C15.4477 3 15 3.44772 15 4C15 4.55228 15.4477 5 16 5ZM15 10C15 12.7614 12.7614 15 10 15C7.23858 15 5 12.7614 5 10C5 7.23858 7.23858 5 10 5C12.7614 5 15 7.23858 15 10ZM10 13C11.6569 13 13 11.6569 13 10C13 8.34315 11.6569 7 10 7C8.34315 7 7 8.34315 7 10C7 11.6569 8.34315 13 10 13Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="join-a-dealer-form-inner">

                                            <label for="exampleFormControlInput11" class="form-label">{{ __('translate.Facebook') }}
                                                </label>

                                            <input type="text" class="form-control" id="exampleFormControlInput11"
                                            placeholder="{{ __('translate.Facebook') }}" name="facebook" value="{{ html_decode($user->facebook) }}">

                                            <div class="icon">
                                                <span>
                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M16 0H4C1.79086 0 0 1.79086 0 4V16C0 18.2091 1.79086 20 4 20H8.5V14H5V11H8.5V9C8.5 6.79086 10.2909 5 12.5 5H15V8H12.5C11.9477 8 11.5 8.44772 11.5 9V11H15V14H11.5V20H16C18.2091 20 20 18.2091 20 16V4C20 1.79086 18.2091 0 16 0Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="join-a-dealer-form-item">

                                        <div class="join-a-dealer-form-inner">

                                            <label for="exampleFormControlInput13" class="form-label">{{ __('translate.LinkedIn') }}
                                                </label>

                                            <input type="text" class="form-control" id="exampleFormControlInput13"
                                            placeholder="{{ __('translate.LinkedIn') }}" name="linkedin" value="{{ html_decode($user->linkedin) }}">

                                            <div class="icon">
                                                <span>
                                                    <svg width="19" height="20" viewBox="0 0 19 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M4 2C4 3.10457 3.10457 4 2 4C0.895431 4 0 3.10457 0 2C0 0.895431 0.895431 0 2 0C3.10457 0 4 0.895431 4 2ZM4 6.5V20H0V6.5H4ZM7 6.5H11V7.34141C11.6256 7.12031 12.2987 7 13 7C16.3137 7 19 9.68629 19 13V20H15V13C15 11.8954 14.1046 11 13 11C11.8954 11 11 11.8954 11 13V20H7V13V6.5Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="join-a-dealer-form-inner">

                                            <label for="exampleFormControlInput5" class="form-label">{{ __('translate.Twitter') }}
                                                </label>

                                            <input type="text" class="form-control" id="exampleFormControlInput5"
                                            placeholder="{{ __('translate.Twitter') }}" name="twitter" value="{{ html_decode($user->twitter) }}">

                                            <div class="icon">
                                                <span>
                                                    <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12.2863 0C10.1825 0 8.47695 1.79086 8.47695 4C8.47695 4.33382 8.5159 4.65809 8.58927 4.96808C6.56635 4.96808 3.51939 4.55908 0.974682 2.00961C0.636299 1.67059 0.0123287 1.89726 0.0358001 2.37567C0.412925 10.0627 3.70501 12.3049 5.40034 12.4444C4.3056 13.5257 2.71534 14.3791 1.11513 14.7622C0.692808 14.8633 0.58848 15.3256 1.00144 15.4599C2.14647 15.8323 3.78541 15.9758 4.66759 16C10.9084 16 15.9809 10.7471 16.0938 4.22229C16.9131 3.68945 17.438 2.5325 17.7329 1.71291C17.8047 1.5133 17.4775 1.28073 17.2817 1.36226C16.6696 1.61708 15.8926 1.67749 15.2306 1.46181C14.532 0.569266 13.4725 0 12.2863 0Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <!-- Working days  -->

                                <h3 class="dealers-information two">{{ __('translate.Working Days') }}</h3>

                                <div class="join-a-dealer-form-main two">

                                    <div class="join-a-dealer-form-item">

                                        <div class="join-a-dealer-form-inner">

                                            <label for="exampleFormControlInput16" class="form-label">{{ __('translate.Sunday') }}
                                                </label>

                                            <input type="text" class="form-control" id="exampleFormControlInput16"
                                                placeholder="{{ __('translate.Sunday') }}" name="sunday" value="{{ html_decode($user->sunday) }}">
                                        </div>

                                        <div class="join-a-dealer-form-inner">

                                            <label for="exampleFormControlInput17" class="form-label">{{ __('translate.Monday') }}
                                                </label>

                                            <input type="text" class="form-control" id="exampleFormControlInput17"
                                            placeholder="{{ __('translate.Monday') }}" name="monday" value="{{ html_decode($user->monday) }}">

                                        </div>

                                    </div>



                                    <div class="join-a-dealer-form-item">

                                        <div class="join-a-dealer-form-inner">

                                            <label for="exampleFormControlInput18" class="form-label">{{ __('translate.Tuesday') }}
                                                </label>

                                            <input type="text" class="form-control" id="exampleFormControlInput18"
                                            placeholder="{{ __('translate.Tuesday') }}" name="tuesday" value="{{ html_decode($user->tuesday) }}">

                                        </div>

                                        <div class="join-a-dealer-form-inner">

                                            <label for="exampleFormControlInput19" class="form-label">{{ __('translate.Wednesday') }}
                                                </label>

                                            <input type="text" class="form-control" id="exampleFormControlInput19"
                                            placeholder="{{ __('translate.Wednesday') }}" name="wednesday" value="{{ html_decode($user->wednesday) }}">

                                        </div>

                                    </div>

                                    <div class="join-a-dealer-form-item">

                                        <div class="join-a-dealer-form-inner">

                                            <label for="exampleFormControlInput20" class="form-label">{{ __('translate.Thursday') }}
                                                </label>

                                            <input type="text" class="form-control" id="exampleFormControlInput20"
                                            placeholder="{{ __('translate.thursday') }}" name="thursday" value="{{ html_decode($user->thursday) }}">

                                        </div>

                                        <div class="join-a-dealer-form-inner">

                                            <label for="exampleFormControlInput21" class="form-label">{{ __('translate.Friday') }}
                                                </label>

                                            <input type="text" class="form-control" id="exampleFormControlInput21"
                                            placeholder="{{ __('translate.Friday') }}" name="friday" value="{{ html_decode($user->friday) }}">

                                        </div>

                                    </div>

                                    <div class="join-a-dealer-form-item">

                                        <div class="join-a-dealer-form-inner">

                                            <label for="exampleFormControlInput22" class="form-label">{{ __('translate.Saturday') }}
                                                </label>

                                            <input type="text" class="form-control" id="exampleFormControlInput22"
                                            placeholder="{{ __('translate.Saturday') }}" name="saturday" value="{{ html_decode($user->saturday) }}">

                                        </div>

                                        <div class="join-a-dealer-form-inner">

                                            <div class="terms-and-conditions-btn">
                                                <button type="submit" class="thm-btn-two">{{ __('translate.Update') }}</button>
                                            </div>

                                        </div>

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
