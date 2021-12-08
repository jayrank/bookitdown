{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')

<link rel="stylesheet" href="{{ asset('frontend/css/cropper.css') }}">

<style type="text/css">
	/*Profile Pic Start*/
	.picture-container {
		position: relative;
		cursor: pointer;
		text-align: center;
	}

	.picture-container h6 {
		color: #037aff;
		margin-top: 10px;
		font-size: 14px;
	}

	.picture {
		width: 96px;
		height: 96px;
		background-color: #f7f7f8;
		border: 1px solid #dee3e7;
		color: #FFFFFF;
		border-radius: 50%;
		margin: 0px auto;
		overflow: hidden;
		transition: all 0.2s;
		-webkit-transition: all 0.2s;
		position: relative;
	}

	.picture:hover {
		border-color: #037aff;
		background-color: #037aff;
	}

	.picture input[type="file"] {
		cursor: pointer;
		display: block;
		height: 100%;
		left: 0;
		opacity: 0 !important;
		position: absolute;
		top: 0;
		width: 100%;
	}

	.picture-src {
		width: 35%;
		position: absolute;
		top: 32%;
		left: 32%;
	}

	.main_form .form-group {
		width: 48%;
		float: left;
		/* padding: 0 16px 24px 16px; */
		margin-right: 15px;
	}

	.main_form .form-group:last-child {
		margin-right: 0;
		margin-bottom: 1.75rem !important;
	}

	.mb-15.nw_set .form-group:last-child {
		margin-bottom: 0px;
	}

	.mb-15.nw_set {
		margin-bottom: 0 !important;
		font-size: 15px;
	}

	.mb-15.nw_set span a {
		color: #037aff;
	}

	.form-group label {
		font-size: 15px;
	}
	
	.picture img {
		width: 100%;
		height: 100%;
	}
</style>
@endsection

@section('content')

	<div class="modal fade" id="modal_cropper" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalLabel">Cropper</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="img-container">
						<div class="row">
							<div class="col-md-8">
								<img id="cropper_image" src="https://avatars0.githubusercontent.com/u/3456749">
							</div>
							<div class="col-md-4">
								<div class="preview"></div>
							</div>
						</div>
						<input type="hidden" id="crop_type">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="crop">Crop</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container">
				<form method="POST" enctype="multipart/form-data" action="{{ route('updateMyProfile') }}" id="updateProfile">
					@csrf
					<input type="hidden" id="profilePicURL" value="{{ route('updateProfileImage') }}">
					<div class="row">
						<div class="col-lg-6">
							<!--begin::Card-->
							<div class="_3ARuHb">
								<h3 class="card-title">Personal Details</h3>
								<h3 class="font-size-lg text-dark font-weight-bold mb-6">Set your name and
									contact information, the email address entered here is used for your login
									access</h3>
							</div>
						</div>
						<div class="col-lg-6">
							<!--begin::Card-->
							<div class="card card-custom gutter-b">
								<div class="card-body">
									<div class="mb-15 nw_set">
										<div class="form-group">
											<div class="picture-container">
												<div class="picture">
													@if($userData->profile_pic != "")
														<img src="{{ asset('uploads/profilepic/'.$userData->profile_pic) }}">
													@else
														<span class="_3Q2N2k _30vxLD _1d3jRi _1t1fP1" data-qa="avatar-placeholder-icon">
															<svg viewBox="0 0 31 31"
																xmlns="http://www.w3.org/2000/svg"
																class="picture-src" id="wizardPicturePreview"
																title="">
																<path d="M18.93 13.012a.75.75 0 0 1 1.228.129l6 11a.75.75 0 0 1-.658 1.109h-20a.75.75 0 0 1-.651-1.122l4-7A.75.75 0 0 1 9.95 16.9l3.438 2.578 5.543-6.466zM6.793 23.75h17.445l-4.875-8.937-5.293 6.175a.75.75 0 0 1-1.019.112l-3.32-2.49-2.938 5.14zM12 13.25a3.25 3.25 0 1 1 0-6.5 3.25 3.25 0 0 1 0 6.5zm0-1.5a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5zM3.5.75h24a2.75 2.75 0 0 1 2.75 2.75v24a2.75 2.75 0 0 1-2.75 2.75h-24A2.75 2.75 0 0 1 .75 27.5v-24A2.75 2.75 0 0 1 3.5.75zm0 1.5c-.69 0-1.25.56-1.25 1.25v24c0 .69.56 1.25 1.25 1.25h24c.69 0 1.25-.56 1.25-1.25v-24c0-.69-.56-1.25-1.25-1.25h-24z" fill="#67768C" fill-rule="nonzero"></path>
															</svg>
														</span>	
													@endif	
													<input type="file" id="wizard-picture" name="wizard-picture" class="">
												</div>
												<h6 class="upload-profile-photo-text">Upload profile photo</h6>
											</div>
										</div>
										<div class="main_form">
											<div class="form-group">
												<label for="exampleSelect1">First name</label>
												<input type="text" class="form-control form-control-solid" name="first_name" value="{{ $userData->first_name }}">
											</div>
											<div class="form-group">
												<label for="exampleSelect1">Last name</label>
												<input type="text" class="form-control form-control-solid" name="last_name" value="{{ $userData->last_name }}">
											</div>
										</div>
										<div class="form-group">
											<label for="exampleSelect1">Mobile number</label>
											<input type="tel" class="form-control form-control-solid" name="mobile_number" value="{{ $userData->phone_number }}">
										</div>
										<div class="form-group">
											<label>Email</label>
											<input type="email" disabled class="form-control" name="email" value="{{ $userData->email }}">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="_3ARuHb">
								<h3 class="card-title">Change Password</h3>
								<h3 class="font-size-lg text-dark font-weight-bold mb-6">To make an update,
									enter your existing password followed by a new one. If you don't know your
									existing password, you can logout and use the Reset Password link on the Log
									In page.</h3>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="card card-custom gutter-b ">
								<div class="card-body">
									<div class="mb-15 nw_set">
										<div class="form-group">
											<label>Current password</label>
											<div class="input-icon input-icon-right">
												<input type="password" id="currentPassword" class="form-control" name="current_password">
												<span><i onclick="currentHideShow(this)" class="fa fa-eye"></i></span>
											</div>
										</div>
										<div class="form-group">
											<label>New password</label>
											<div class="input-icon input-icon-right">
												<input type="password" id="newPassword" class="form-control" name="new_password">
												<span><i onclick="newHideShow(this)" class="fa fa-eye"></i></span>
											</div>
										</div>
										<div class="form-group">
											<label>Verify password</label>
											<div class="input-icon input-icon-right">
												<input type="password" id="verifyPassword" class="form-control" name="confirm_password">
												<span><i onclick="verifyHideShow(this)" class="fa fa-eye"></i></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="_3ARuHb">
								<h3 class="card-title">My Notification Settings</h3>
								<h3 class="font-size-lg text-dark font-weight-bold mb-6">Receive notifications
									about important activity in your account</h3>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="card card-custom gutter-b ">
								<div class="card-body">
									<div class="mb-15 nw_set">
										<span>Go to <a href="https://salon.tjcg.in/my-notification-settings.html">my notification settings</a></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12 text-right">
							<button type="submit" id="updateProfilebtn" class="btn btn-primary mr-2 black">Save</button>
						</div>
					</div>
				</form>	
			</div>
		</div>
	</div>
@endsection

{{-- Scripts Section --}}
@section('scripts') 

<script src="{{ asset('frontend/js/cropper.js') }}"></script>
<script src="{{ asset('js/updateprofile.js') }}"></script>

<script> 
	$(document).ready(function () {
		// Prepare the preview for profile picture
		/* $("#wizard-picture").change(function () {
			readURL(this);
		}); */

		$(document).on('click','.upload-profile-photo-text', function(){
			$('#wizard-picture').click();
		});
	});
	
	/* function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
			}
			reader.readAsDataURL(input.files[0]);
		}
	} */
	
	function currentHideShow(param) {
		var x = document.getElementById("currentPassword");
		if (x.type === "password") {
			x.type = "text";
			param.classList.remove("fa-eye");
			param.classList.add("fa-eye-slash");
		} else {
			x.type = "password";
			param.classList.remove("fa-eye-slash");
			param.classList.add("fa-eye");
		}
	}
	function verifyHideShow(param) {
		var x = document.getElementById("verifyPassword");
		if (x.type === "password") {
			x.type = "text";
			param.classList.remove("fa-eye");
			param.classList.add("fa-eye-slash");
		} else {
			x.type = "password";
			param.classList.remove("fa-eye-slash");
			param.classList.add("fa-eye");
		}
	}
	function newHideShow(param) {
		var x = document.getElementById("newPassword");
		if (x.type === "password") {
			x.type = "text";
			param.classList.remove("fa-eye");
			param.classList.add("fa-eye-slash");
		} else {
			x.type = "password";
			param.classList.remove("fa-eye-slash");
			param.classList.add("fa-eye");
		}
	}
</script>

	@if(Session::has('message'))
	<script>
		toastr.options = {
			"closeButton": false,
			"debug": false,
			"newestOnTop": true,
			"progressBar": true,
			"positionClass": "toast-top-right",
			"preventDuplicates": false,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "4000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		};

		toastr.success('{{ Session::get('message') }}');
	</script>
	@endif
@endsection	