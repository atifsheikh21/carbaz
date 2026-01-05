@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Car Gallery') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Car Gallery') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Car') }} >> {{ __('translate.Car Gallery') }}</p>
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
                            <div class="col-12 mg-top-30">
                                <!-- Product Card -->
                                <div class="crancy-product-card">
                                    <div class="create_new_btn_inline_box">
                                        <h4 class="crancy-product-card__title">{{ __('translate.Car') }} : {{ html_decode($car->translate->title) }}</h4>
                                    </div>

                                    <div class="row mg-top-30">

                                        <form id="dropzoneForm" method="post" action="{{ route('admin.upload-gallery', $car->id) }}"  enctype="multipart/form-data"  class="dropzone">
                                        @csrf

                                        </form>
                                        <div class="text-center">

                                            <button id="submit-all" class="crancy-btn mg-top-25" type="submit">{{ __('translate.Upload Images') }}</button>
                                            </button>
                                        </div>

                                    </div>



                                </div>
                                <!-- End Product Card -->
                            </div>
                        </div>
                    </div>
                    <!-- End Dashboard Inner -->
                </div>
            </div>

        </div>
    </div>
</section>
<!-- End crancy Dashboard -->

    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">

                            <div class="crancy-table crancy-table--v3 mg-top-30">

                                <div class="crancy-customer-filter">
                                    <div class="crancy-customer-filter__single crancy-customer-filter__single--csearch d-flex items-center justify-between create_new_btn_box">
                                        <div class="crancy-header__form crancy-header__form--customer create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Gallery Images') }}</h4>
                                        </div>
                                    </div>
                                </div>

                                <!-- crancy Table -->
                                <div id="crancy-table__main_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">

                                    <table class="crancy-table__main crancy-table__main-v3 dataTable no-footer">
                                        <!-- crancy Table Head -->
                                        <thead class="crancy-table__head">
                                            <tr>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Image') }}
                                                </th>

                                                <th class="crancy-table__column-3 crancy-table__h3 sorting">
                                                    {{ __('translate.Action') }}
                                                </th>

                                            </tr>
                                        </thead>
                                        <!-- crancy Table Body -->
                                        <tbody class="crancy-table__body">
                                            @foreach ($galleries as $index => $gallery_image)
                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <img class="car_gallery_image" src="{{ asset($gallery_image->image) }}" alt="">
                                                    </td>


                                                    <td class="crancy-table__column-2 crancy-table__data-2">

                                                        <a onclick="itemDeleteConfrimation({{ $gallery_image->id }})" href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal" class="crancy-btn delete_danger_btn"><i class="fas fa-trash"></i> {{ __('translate.Delete') }}</a>

                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                        <!-- End crancy Table Body -->
                                    </table>
                                </div>
                                <!-- End crancy Table -->
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->


  <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('translate.Delete Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('translate.Are you realy want to delete this item?') }}</p>
                </div>
                <div class="modal-footer">
                    <form action="" id="item_delect_confirmation" class="delet_modal_form" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('translate.Yes, Delete') }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
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

    <script src="{{ asset('global/dropzone/dropzone.min.js') }}"></script>

    <script>
        "use strict"
        function itemDeleteConfrimation(id){
            $("#item_delect_confirmation").attr("action",'{{ url("admin/listing/delete-gallery/") }}'+"/"+id)
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
