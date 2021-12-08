"use strict";

// Class Definition
var KTLogin = function() {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';

    var _handleClientCreate = function() {
        var form = KTUtil.getById('editClient');
        var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('editClientSubmitButton');

        if (!form) {
            return;
        }

        FormValidation
            .formValidation(
                form, {
                    fields: {
                        firstname: {
                            validators: {
                                notEmpty: {
                                    message: 'First name is required'
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
						}
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        bootstrap: new FormValidation.plugins.Bootstrap({})
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

                var form_data = new FormData($("#editClient")[0]);

                $.ajax({
                    type: 'POST',
                    url: formSubmitUrl,
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);

                        KTUtil.btnRelease(formSubmitButton);
                        $("#clientModal").modal('hide');
                        var table = $('#clientList').DataTable();
                        table.ajax.reload();
                        window.location.reload();
                        // $("#addClient").reset();

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
                    },
                    error: function(data) {
                        KTUtil.btnRelease(formSubmitButton, "Save");
                        var errors = data.responseJSON;

                        var errorsHtml = '';
                        $.each(errors.errors, function(key, value) {
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
	
	var _handleBlockClient = function() {
		var form = KTUtil.getById('blockClient');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('blockClientBtn');

		if (!form) {
			return;
		}

		FormValidation
		    .formValidation( 
		        form,
		        {
		            fields: 
					{
						block_reason : {
							validators: {
								notEmpty: {
									message: 'Block reason is required'
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

		        var form_data = new FormData($("#blockClient")[0]);
				
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
						
						if(response.status == true){
							location.reload();
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
						KTUtil.btnRelease(formSubmitButton, "Save");	
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
	
	var _handleUnblockClient = function() {
		var form = KTUtil.getById('unblockClient');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('unblockClientBtn');

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

		        var form_data = new FormData($("#unblockClient")[0]);
				
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
						
						if(response.status == true){
							location.reload();
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
						KTUtil.btnRelease(formSubmitButton, "Save");	
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

    var _handleSendConsultationFormEmail = function() {
		var form = KTUtil.getById('consultationFormEmailReminder');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('saveUnpaidInovice');

		if (!form) {
			return;
		}

		FormValidation
		    .formValidation( 
		        form,
		        {
		            fields: {
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

		        var form_data = new FormData($("#consultationFormEmailReminder")[0]);
				
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
						$("#sendEmailReminder").modal('hide');
						
						document.getElementById("consultationFormEmailReminder").reset();
						
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
					},
					error: function (data) {
						KTUtil.btnRelease(formSubmitButton, "Yes, send reminder");	
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
            _handleClientCreate();
			_handleBlockClient();
			_handleUnblockClient();
			_handleSendConsultationFormEmail();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTLogin.init();
});