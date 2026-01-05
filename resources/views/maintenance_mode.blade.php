

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>{{ env('APP_NAME') }}</title>

    <!-- Fav Icon -->
	<link rel="icon" href="{{ asset($setting->favicon) }}">

    <link rel="stylesheet" href="{{ asset('frontend/assets/fontawesome/css/all.css') }}">
    <!--bootstrap.min.css  -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <!-- venobox.min.css  -->
    <link rel="stylesheet" href="{{ asset('frontend/css/venobox.min.css') }}">
    <!-- slick.css  -->
    <link rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
    <!-- aos.css  -->
    <link rel="stylesheet" href="{{ asset('frontend/css/aos.css') }}">
    <!-- style.css  -->
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <!-- responsive.css -->
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">



</head>

<body>


	<section class="error">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 ">

                    <div class="error-img-main">
                    <div class="error-img error-img-two  ">
                            <img src="{{ getImageOrPlaceholder($setting->maintenance_image, '1085x361') }}" alt="img">
                        </div>
                    </div>

                        <div class="error-text">
                            <p>{!! clean(nl2br($setting->maintenance_text)) !!}</p>
                        </div>

                    </div>
                </div>
            </div>
        </section>





    <!-- fontawesome  -->
    <script src="{{ asset('frontend/assets/fontawesome/js/all.js') }}"></script>

    <!-- jquery  -->
    <script src="{{ asset('global/jquery-3.7.1.min.js') }}"></script>

    <!-- bootstrap.bundle.min.js -->
    <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('frontend/assets/js/gaps.js') }}"></script>

    <!-- venobox.js -->
    <script src="{{ asset('frontend/assets/js/venobox.js') }}"></script>
    <!-- slick.min.js -->
    <script src="{{ asset('frontend/assets/js/slick.min.js') }}"></script>
    <!-- aos.js -->
    <script src="{{ asset('frontend/assets/js/aos.js') }}"></script>
    <!-- custom.js -->
    <script src="{{ asset('frontend/assets/js/custom.js') }}"></script>


</body>

</html>
