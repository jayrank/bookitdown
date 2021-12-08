"use strict";

// Class Definition
var KTLogin = function() {
	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';

	var _handleFormSignin = function() {
		var form = KTUtil.getById('kt_login_singin_form');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('kt_login_singin_form_submit_button');

		if (!form) {
			return;
		}

		FormValidation
		    .formValidation( 
		        form,
		        {
		            fields: {
						email: {
							validators: {
								notEmpty: {
									message: 'Email is required'
								},
								emailAddress: {
									message: 'The value is not a valid email address'
								}
							}
						},
						password: {
							validators: {
								notEmpty: {
									message: 'Password is required'
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
				// Show loading state on button
				KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");

				// Simulate Ajax request
				setTimeout(function() {
					KTUtil.btnRelease(formSubmitButton);
				}, 2000);

		        var form_data = new FormData($("#kt_login_singin_form")[0]);
				
				$.ajax({
					type: 'POST',       
					url: formSubmitUrl,
					data: form_data,		
					dataType: 'json',					
					processData: false,
					contentType: false,
					success: function (response)
					{
						KTUtil.btnRelease(formSubmitButton);
						window.location.href = response.intended;
					},
					error: function (data) {
						KTUtil.btnRelease(formSubmitButton, "Sign In");	
						var errors = data.responseJSON;
						
						var errorsHtml='';
						$.each(errors.errors, function( key, value ) {
							errorsHtml += value[0];
						});
						
						swal.fire({
							text: (errorsHtml) ? errorsHtml : "These credentials do not match our records.",
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: "Ok, got it!",
							customClass: {
								confirmButton: "btn font-weight-bold btn-light-primary"
							}
						}).then(function() {
							KTUtil.scrollTop();
						});
					}
				});
		    })
			.on('core.form.invalid', function() {
				Swal.fire({
					text: "Sorry, looks like there are some errors detected, please try again.",
					icon: "error",
					buttonsStyling: false,
					confirmButtonText: "Ok, got it!",
					customClass: {
						confirmButton: "btn font-weight-bold btn-light-primary"
					}
				}).then(function() {
					KTUtil.scrollTop();
				});
		    });
    }

	var _handleFormForgot = function() {
		var form = KTUtil.getById('kt_login_forgot_form');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('kt_login_forgot_form_submit_button');

		if (!form) {
			return;
		}

		FormValidation
		    .formValidation(
		        form,
		        {
		            fields: {
						email: {
							validators: {
								notEmpty: {
									message: 'Email is required'
								},
								emailAddress: {
									message: 'The value is not a valid email address'
								}
							}
						}
		            },
		            plugins: {
						trigger: new FormValidation.plugins.Trigger(),
						submitButton: new FormValidation.plugins.SubmitButton(),
	            		//defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
						bootstrap: new FormValidation.plugins.Bootstrap({
						//	eleInvalidClass: '', // Repace with uncomment to hide bootstrap validation icons
						//	eleValidClass: '',   // Repace with uncomment to hide bootstrap validation icons
						})
		            }
		        }
		    )
		    .on('core.form.valid', function() {
				// Show loading state on button
				KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");

				// Simulate Ajax request
				setTimeout(function() {
					KTUtil.btnRelease(formSubmitButton);
				}, 2000);
		    })
			.on('core.form.invalid', function() {
				Swal.fire({
					text: "Sorry, looks like there are some errors detected, please try again.",
					icon: "error",
					buttonsStyling: false,
					confirmButtonText: "Ok, got it!",
					customClass: {
						confirmButton: "btn font-weight-bold btn-light-primary"
					}
				}).then(function() {
					KTUtil.scrollTop();
				});
		    });
    }

	var _handleFormSignup = function() {
		// Base elements
		var form = KTUtil.getById('kt_login_signup_form');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('kt_login_signup_submit');
		
		if (!form) {
			return;
		}
		
		FormValidation
		    .formValidation(
		        form,
		        {
		            fields: {
						first_name: {
							validators: {
								notEmpty: {
									message: 'First Name is required'
								},
								regexp: {
		                            regexp: /^[a-zs]+$/i,
		                            message: 'Only alphabets are allowed'
		                        }
							}
						},
						last_name: {
							validators: {
								notEmpty: {
									message: 'Last Name is required'
								},
								regexp: {
		                            regexp: /^[a-zs]+$/i,
		                            message: 'Only alphabets are allowed'
		                        }
							}
						},
						company_name: {
							validators: {
								notEmpty: {
									message: 'Company Name is required'
								},
								regexp: {
		                            regexp: /^[a-zs]+$/i,
		                            message: 'Only alphabets are allowed'
		                        }
							}
						},
						address: {
							validators: {
								notEmpty: {
									message: 'Address is required'
								}
							}
						},
						phone_number: {
							validators: {
								notEmpty: {
									message: 'Phone number is required'
								},
								digits: {
									message: 'The value is not a valid Phone number'
								}
							}
						}, 
						email: {
							validators: {
								notEmpty: {
									message: 'Email is required'
								},
								emailAddress: {
									message: 'The value is not a valid email address'
								}
							}
						},
						password: {
							validators: {
								notEmpty: {
									message: 'Password is required'
								},
								stringLength: {
									min:8,
									message: 'At least 8 characters required'
								}
							}
						},
						business_type: {
							validators: {
								notEmpty: {
									message: 'Business type is required'
								}
							}
						},
						agree: {
							validators: {
								notEmpty: {
									message: 'You must accept the terms and conditions'
								}
							}
						}
		            },
		            plugins: {
						trigger: new FormValidation.plugins.Trigger(),
						submitButton: new FormValidation.plugins.SubmitButton(),
	            		//defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
						bootstrap: new FormValidation.plugins.Bootstrap({
						//	eleInvalidClass: '', // Repace with uncomment to hide bootstrap validation icons
						//	eleValidClass: '',   // Repace with uncomment to hide bootstrap validation icons
						})
		            }
		        }
		    )
		    .on('core.form.valid', function() {
				// Show loading state on button
				KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");

				var form_data = new FormData($("#kt_login_signup_form")[0]);
				
				$.ajax({
					type: 'POST',       
					url: formSubmitUrl,
					data: form_data,		
					dataType: 'json',					
					processData: false,
					contentType: false,
					success: function (response)
					{
						KTUtil.btnRelease(formSubmitButton);
						window.location.href = $("#redirectURL").val();
					},
					error: function (data) {
						KTUtil.btnRelease(formSubmitButton, "Sign In");	
						var errors = data.responseJSON;
						KTUtil.scrollTop();
						
						var errorsHtml='';
						$.each(errors.errors, function( key, value ) {
							errorsHtml += value[0];
						});
						
						swal.fire({
							text: (errorsHtml) ? errorsHtml : "Sorry, looks like there are some errors detected, please try again.",
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: "Ok, got it!",
							customClass: {
								confirmButton: "btn font-weight-bold btn-light-primary"
							}
						}).then(function() {
							KTUtil.scrollTop();
						});
					}
				});
		    })
			.on('core.form.invalid', function() {
				KTUtil.scrollTop();
		    });
    }

    // Public Functions
    return {
        init: function() {
            _handleFormSignin();
			_handleFormForgot();
			_handleFormSignup();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTLogin.init();
});
