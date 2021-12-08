{{-- Extends layout --}}
@extends('layouts.app')

@section('content')

<div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid">
	<!--begin::Content-->
	<div class="login-container order-2 order-lg-1 d-flex flex-center flex-row-fluid px-7 pt-lg-0 pb-lg-0 pt-4 pb-6 bg-white">
		<!--begin::Wrapper-->
		<div class="login-content d-flex flex-column pt-lg-0 pt-12">
			<!--begin::Logo-->
			<!--a href="#" class="login-logo pb-xl-20 pb-15">
				<img src="{{ asset('assets/media/logos/logo-4.png') }}" class="max-h-70px" alt="" />
			</a-->
			<!--end::Logo-->
			<!--begin::Signin-->
			<div class="login-form">
				<form class="form" method="POST" novalidate="novalidate" id="kt_login_singin_form" action="{{ route('login') }}">
					@csrf
					<div class="pb-5 pb-lg-15">
						<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Sign In</h3>
						<div class="text-muted font-weight-bold font-size-h4">New Here?
							<a href="{{ route('register') }}" class="text-primary font-weight-bolder">Create Account</a>
						</div>
					</div>
					<div class="form-group">
						<label class="font-size-h6 font-weight-bolder text-dark">Your Email</label>
						<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0" type="email" name="email" value="{{ old('email') }}" autocomplete="off" />
					</div>
					<div class="form-group">
						<div class="d-flex justify-content-between mt-n5">
							<label class="font-size-h6 font-weight-bolder text-dark pt-5">Your Password</label>
						</div>
						<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0" value="" type="password" name="password" autocomplete="off" />
					</div>
					<div class="row m-login__form-sub">
						<div class="col m--align-left">
							<label class="m-checkbox m-checkbox--focus">
								<input type="checkbox" name="remember"> Remember me
								<span></span>
							</label>
						</div>
						<div class="col text-right">
							<a href="{{ route('partner-forgot-password')}}" class="m-link">Forget Password ?</a>
						</div>
					</div>
					<div class="pb-lg-0 pb-5">
						<button type="submit" id="kt_login_singin_form_submit_button" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Sign In</button>
					</div>
					<!--end::Action-->
				</form>
				<!--end::Form-->
			</div>
			<!--end::Signin-->
		</div>
		<!--end::Wrapper-->
	</div>
	<!--begin::Content-->
	<!--begin::Aside-->
	<div class="login-aside order-1 order-lg-2 bgi-no-repeat bgi-position-x-right">
		<div class="login-conteiner bgi-no-repeat bgi-position-x-right bgi-position-y-bottom" style="background-image: url( {{ asset('assets/media/svg/illustrations/login-visual-4.svg') }} );">
			<!--begin::Aside title-->
			<h3 class="pt-lg-40 pl-lg-20 pb-lg-0 pl-10 py-20 m-0 d-flex justify-content-lg-start font-weight-boldest display5 display1-lg text-white">We Got
			<br />A Surprise
			<br />For You</h3>
			<!--end::Aside title-->
		</div>
	</div>
	<!--end::Aside-->
</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
	<script src="{{ asset('js/login.js') }}"></script>
@endsection