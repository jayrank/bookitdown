"use strict";
// Class Definition
var KTLogin = function() {
	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
	var _handleClientCreate = function() {
		var form = KTUtil.getById('updateProfile');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('updateProfilebtn');
		if (!form) {
			return;
		}

		FormValidation
		    .formValidation( 
		        form,
		        {
		            fields: {
						first_name:{
							validators: {
								notEmpty: {
									message: 'First name is required'
								}
							}
						},
						last_name:{
							validators: {
								notEmpty: {
									message: 'Last name is required'
								}
							}
						},
						mobile_number:{
							validators: {
								notEmpty: {
									message: 'Mobile number is required'
								},
								integer: {
									message: 'Please enter correct mobile number',
									thousandsSeparator: '',
									decimalSeparator: ''
								}
							}
						},
						email:{
							validators: {
								notEmpty: {
									message: 'Email address is required'
								},
								emailAddress: {
									message: 'The value is not a valid email address'
								}
							}
						},
						current_password:{
							validators: {
								notEmpty: {
									message: 'Email address is required'
								}
							}
						},
						new_password:{
							validators: {
								notEmpty: {
									message: 'Email address is required'
								}
							}
						},
						confirm_password:{
							validators: {
								notEmpty: {
									message: 'Email address is required'
								},
								identical: {
									compare: function() {
										return form.querySelector('[name="new_password"]').value;
									},
									message: 'The password and its confirm are not the same'
								}
							}
						}
		            },
		            plugins: {
							trigger: new FormValidation.plugins.Trigger(),
							submitButton: new FormValidation.plugins.SubmitButton(),
							bootstrap: new FormValidation.plugins.Bootstrap({
						})
		            }
		        }
		    )
		    .on('core.form.valid', function() {
				KTApp.blockPage();
				// Show loading state on button
				KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");

		        var form_data = new FormData($("#updateProfile")[0]);
				
				$.ajax({
					type: 'POST',       
					url: formSubmitUrl,
					data: form_data,
					dataType: 'json',
					processData: false,
					contentType: false,
					success: function (response)
					{
						window.location.href = response.redirect;
					},
					error: function (data) {
						KTApp.unblockPage();
						KTUtil.btnRelease(formSubmitButton, "Save");
						var errors = data.responseJSON;
						
						var errorsHtml='';
						$.each(errors.error, function( key, value ) {
							errorsHtml += value[0];
						});
						
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
						toastr.error((errorsHtml) ? errorsHtml : "Something went wrong!");
					}
				});
		    })
			.on('core.form.invalid', function() {
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
				toastr.error("Sorry, looks like there are some errors detected, please try again."); 
		    });
    }

    // Public Functions
    return {
        init: function() {
            _handleClientCreate();
        }
    };
}();


window.addEventListener('DOMContentLoaded', function () {
		var image = document.getElementById('cropper_image');
		var cropBoxData;
		var canvasData;
		var cropper;

		$('#modal_cropper').on('shown.bs.modal', function () {
			cropper = new Cropper(image, {
				aspectRatio: 1 / 1,
				preview: '.preview'
			});
		}).on('hidden.bs.modal', function () {
			cropper.destroy();
			cropper = null;
		});


		$("#crop").click(function () {
			KTApp.blockPage();
			var canvas = cropper.getCroppedCanvas({
				width: 1000,
				height: 290,
			});

			canvas.toBlob(function (blob) {
				var url = URL.createObjectURL(blob);
				var reader = new FileReader();
				
				reader.readAsDataURL(blob);
				reader.onloadend = function () {
					var base64data = reader.result;
					var csrf = $("input[name=_token]").val();
					
					$.ajax({
						type: "POST",
						dataType: "json",
						url: $("#profilePicURL").val(),
						data: { image: base64data,_token : csrf},
						success: function (data) {
							$('#modal_cropper').modal('hide');
							location.reload();
						}
					});
				}
			});
		});
	});
	
// Class Initialization
jQuery(document).ready(function() {
    KTLogin.init();
	
	$('#wizard-picture').on("change", function() {
		console.log(JSON.stringify(this.files));
		var file = this.files[0];
		var fileType = file["type"];
		var validImageTypes = ["image/jpeg", "image/png", "image/jpg"];
		if ($.inArray(fileType, validImageTypes) < 0) {
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
			toastr.error("Please select a valid image file. support format jpeg, jpg and png."); 			
			return false;
		} else {	
			var reader = new FileReader();
			reader.onload = function(event) {
				$("#crop_type").val('profile');
				$("#cropper_image").attr('src', event.target.result);
				$('#modal_cropper').modal('show');
				$("#wizard-picture").val("");
			}
			reader.readAsDataURL(this.files[0]);
		}	
	});
});
