"use strict";
// Class Definition
var KTaddAppointment = function() {
	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
	var _handleUpdateRolePermissions = function() {
		var form = KTUtil.getById('saveUserRolePermissions');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton  = KTUtil.getById('saveRolePermission');
	
		if (!form) {
			return;
		}

		FormValidation
		    .formValidation( 
		        form,
		        {
		            fields: 
					{
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

		        var form_data = new FormData($("#saveUserRolePermissions")[0]);
				
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
						
						if(response.status == true) {
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
							toastr.success(response.message);
							
							setTimeout( function() {
								location.href = $("#redirectUrl").val();
							}, 1500);	
						} else {
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
							toastr.error((response.message) ? response.message : "Something went wrong!");
						}
					},
					error: function (data) 
					{
						KTUtil.btnRelease(formSubmitButton, "Add");
						var errors = data.responseJSON;
						
						var errorsHtml='';
						$.each(errors.errors, function( key, value ) {
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
            _handleUpdateRolePermissions();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTaddAppointment.init();
});
