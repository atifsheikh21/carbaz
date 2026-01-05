<!DOCTYPE html>
<html class="no-js" lang="zxx">
	<head>
		<!-- Meta Tags -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="keywords" content="Site keywords here">
		<meta name="description" content="#">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Site Title -->
		<title>{{ __('translate.Login') }}</title>

		<!-- Fav Icon -->
		<link rel="icon" href="{{ asset($setting->favicon) }}">

        <!--  Stylesheet -->
		<link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('backend/css/font-awesome-all.min.css') }}">
		<link rel="stylesheet" href="{{ asset('backend/css/nice-select.min.css') }}">
		<link rel="stylesheet" href="{{ asset('backend/css/reset.css') }}">
		<link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
		<link rel="stylesheet" href="{{ asset('backend/css/dev.css') }}">
        <link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">
		<link rel="stylesheet" href="{{ asset('global/makking-font.css') }}">


	</head>
	<body id="crancy-dark-light">

		<div class="body-bg">

			<section class="crancy-wc crancy-wc__full crancy-bg-cover">
				<div class="crancy-wc__form">
					<!-- Welcome Banner -->
					<div class="crancy-wc__form--middle">
						<div class="crancy-wc__form-inner-flex">
						<div class="crancy-wc__form-inner common-flex">
							<div class="crancy-wc__logo">
								<a href="{{ route('home') }}"><h3>{{ $setting->app_name }}</h3></a>
							</div>

							<div class="crancy-wc__form-inside-df">
							<div class="crancy-wc__form-inside">
								<div class="crancy-wc__form-middle">
									<div class="crancy-wc__form-top">
										<div class="crancy-wc__heading pd-btm-20">
											<h3 class="crancy-wc__form-title crancy-wc__form-title__one m-0">{{ __('translate.Login Here') }}</h3>
										</div>
										<!-- Sign in Form -->
										<form class="crancy-wc__form-main" action="{{ route('admin.store-login') }}" method="post">
											@csrf
											<div class="row">
												<div class="col-12">
													<!-- Form Group -->
													<div class="form-group">
														<div class="form-group__input">

														@if(env('APP_MODE') == 'DEMO')
														<input class="crancy-wc__form-input" type="email" name="email" placeholder="{{ __('translate.Email') }}" value="admin@gmail.com">
														@else
														<input class="crancy-wc__form-input" type="email" name="email" placeholder="{{ __('translate.Email') }}" >
														@endif

															
														</div>
													</div>
												</div>
												<div class="col-12">
													<!-- Form Group -->
													<div class="form-group">
														<div class="form-group__input">
															@if(env('APP_MODE') == 'DEMO')
															<input class="crancy-wc__form-input" placeholder="{{ __('translate.Password') }}" id="password-field" type="password" name="password" value="1234">
															@else
															<input class="crancy-wc__form-input" placeholder="{{ __('translate.Password') }}" id="password-field" type="password" name="password" >
															@endif

															
															<span class="crancy-wc__toggle"><i class="fas fa-eye-slash" id="toggle-icon"></i></span>
														</div>
													</div>
												</div>
											</div>

											<!-- Form Group -->
											<div class="form-group mg-top-30">
												<div class="crancy-wc__button">
													<button class="ntfmax-wc__btn" type="submit">{{ __('translate.Login Now') }}</button>
												</div>
											</div>

										</form>
										<!-- End Sign in Form -->
									</div>

								</div>
							</div>
							</div>

						</div>
						</div>

						<div class="crancy-wc__banner crancy-bg-cover">
							<div class="crancy-wc__banner--img">
								<img src="{{ getImageOrPlaceholder($setting->admin_login, '741x527') }}" alt="#">
							</div>
							<div class="crancy-wc__slider">
								<!-- Sinlge Slider -->
								<div class="single-slider">
									<div class="crancy-wc__slider--single">
										<div class="crancy-wc__slider--content">
											<h4 class="crancy-wc__slider--title">{{ __('translate.Welcome to CARBAZ') }}</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- End Welcome Banner -->
				</div>
			</section>

		</div>

		<!--  Scripts -->
		<script src="{{ asset('global/jquery-3.7.1.min.js') }}"></script>
		<script src="{{ asset('backend/js/jquery-migrate.js') }}"></script>
		<script src="{{ asset('backend/js/popper.min.js') }}"></script>
		<script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('backend/js/nice-select.min.js') }}"></script>
		<script src="{{ asset('backend/js/main.js') }}"></script>
        <script src="{{ asset('global/toastr/toastr.min.js') }}"></script>

        <script>
            @if(Session::has('messege'))
            var type="{{Session::get('alert-type','info') }}"
            switch(type){
                case 'info':
                    toastr.info("{{ Session::get('messege') }}");
                    break;
                case 'success':
                    toastr.success("{{ Session::get('messege') }}");
                    break;
                case 'warning':
                    toastr.warning("{{ Session::get('messege') }}");
                    break;
                case 'error':
                    toastr.error("{{ Session::get('messege') }}");
                    break;
            }
            @endif
        </script>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    toastr.error('{{ $error }}');
                </script>
            @endforeach
        @endif

	</body>
</html>
