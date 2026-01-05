@extends('layout')
@section('title')
    <title>{{ __('translate.Car List') }}</title>
@endsection
@section('body-content')

<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ getImageOrPlaceholder($breadcrumb,'1905x300') }}) "></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Car List') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Car List') }}</li>
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
                    <div class="row gy-5">
                        <!-- Manage Car -->
                        <div class="col-lg-12">
                            <div class="car-images">
                                <h3 class="car-images-taitel">{{ __('translate.Car') }} : {{ html_decode($car->translate->title) }}
                                      <i 
                                        class="fas fa-info-circle text-info"
                                        data-toggle="tooltip"
                                        data-placement="right"
                                        title="Recommended size: 214x144"
                                        style="cursor: pointer;"
                                    ></i>
                                </h3>
                                <div class="car-images-inner car-images-inner-car">

                                    <form id="dropzoneForm" method="post" action="{{ route('user.upload-gallery', $car->id) }}"  enctype="multipart/form-data"  class="dropzone">
                                        @csrf

                                    </form>
                                    <div class="text-center car-images-manage-car-item-btn">
                                        <button id="submit-all" type="submit" class="thm-btn-two">{{ __('translate.Upload Images') }}</button>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- Gallery Images  -->

                        <div class="col-lg-12">
                            <div class="car-images">
                                <h3 class="car-images-taitel">{{ __('translate.Gallery Images') }}</h3>
                                <div class="car-images-inner">
                                    <div class="gallery-img-item">

                                        @foreach ($galleries as $gallery)
                                            <div class="gallery-img-item-thumb">
                                                <img src="{{ getImageOrPlaceholder($gallery->image, '236x220') }}" alt="img">
                                                <button class="car-delet-btn" onclick="deleteGallery({{ $gallery->id }})">
                                                    <span>
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </span>

                                                </button>
                                            </div>

                                            <form action="{{ route('user.delete-gallery', $gallery->id) }}" id="remove_gallery_{{ $gallery->id }}" class="d-none" method="POST">
                                                @csrf
                                                @method('DELETE')

                                            </form>
                                        @endforeach

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

    <!-- dashboard-part-end -->

    @include('profile.logout')

</main>

@endsection

@push('style_section')

    <link rel="stylesheet" href="{{ asset('global/dropzone/dropzone.min.css') }}">
    <style>
        .dropzone {
            background: white;
            border-radius: 5px;
            border: 2px dashed rgb(0, 135, 247);
            border-image: none;
            max-width: 805px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endpush

@push('js_section')

<script src="{{ asset('global/sweetalert/sweetalert2@11.js') }}"></script>

<script src="{{ asset('global/dropzone/dropzone.min.js') }}"></script>

<script>
    "use strict";
    function deleteGallery(id){
        Swal.fire({
            title: "{{__('Are you realy want to delete this item ?')}}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{__('Yes, Delete It')}}",
            cancelButtonText: "{{__('Cancel')}}",
        }).then((result) => {
            if (result.isConfirmed) {
                $("#remove_gallery_"+id).submit();
            }

        })
    }
</script>

<script>
        Dropzone.options.dropzoneForm = {
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            thumbnailHeight: 200,
            thumbnailWidth: 200,
            maxFilesize: 3,
            filesizeBase: 1000,
            addRemoveLinks: true,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
                return time + file.name;
            },
            acceptedFiles: ".jpeg,.jpg,.png,.webp",
            init: function() {
                myDropzone = this;
                $('#submit-all').on('click', function(e) {
                    e.preventDefault();
                    myDropzone.processQueue();
                });

                this.on("complete", function() {
                    if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                        var _this = this;
                        _this.removeAllFiles();
                    }
                });
            },
            success: function(file, response) {
                window.location.href = response.url;
                toastr.success(response.message);
            },
        };

</script>


@endpush
