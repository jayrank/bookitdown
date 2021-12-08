"use strict";
// Class Definition
var KTviewOrder = function() {
	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
	var _handleSendOrderEmail = function() {
		var form = KTUtil.getById('sendPurchaseOrderEmail');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('sendEmailButton');

		if (!form) {
			return;
		}

		FormValidation
		    .formValidation( 
		        form,
		        {
		            fields: 
					{
						recipient_email_address: {
							validators: {
								notEmpty: {
									message: 'Recipient email is required'
								},
								emailAddress: {
									message: 'The value is not a valid email address'
								}
							}
						},
						sender_email_address: {
							validators: {
								notEmpty: {
									message: 'Sender email is required'
								},
								emailAddress: {
									message: 'The value is not a valid email address'
								}
							}
						},
						message_subject: {
							validators: {
								notEmpty: {
									message: 'Message subject is required'
								}
							}
						},
						message_content: {
							validators: {
								notEmpty: {
									message: 'Message content is required'
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

		        var form_data = new FormData($("#sendPurchaseOrderEmail")[0]);
				
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
						$("#sendOrderEmail").modal('hide');
						
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

	var _handleIncreaseProductStock = function() {
		var form = KTUtil.getById('increaseProductStock');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('increseProductStockBtn');

		if (!form) {
			return;
		}

		FormValidation
		    .formValidation( 
		        form,
		        {
		            fields: 
					{
						increase_stock_qty: {
							validators: {
								notEmpty: {
									message: 'Increase qty is required'
								}
							}
						},
						increase_stock_price: {
							validators: {
								notEmpty: {
									message: 'Supply price is required'
								}
							}
						},
						order_action: {
							validators: {
								notEmpty: {
									message: 'Increase reason is required'
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

		        var form_data = new FormData($("#increaseProductStock")[0]);
				
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
						$("#increaseStockModal").modal('hide');
						
						var table = $('#productStockLogs').DataTable();
						table.ajax.reload();
						
						if($("#isPostalSame").prop(":checked") == true){ 
						} else {
							var old_value = $("#increase_stock_price").attr('data-oldSupplyPrice');
							$("#increase_stock_price").val(old_value);	
						}
						
						$("#increase_stock_qty").val(0);
						$("#isPostalSame").prop('checked',false);
						$("#order_action").val('New Stock');
						
						location.reload();
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

	var _handleDecreaseProductStock = function() {
		var form = KTUtil.getById('decreaseProductStock');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('decreaseProductStockBtn');

		if (!form) {
			return;
		}

		FormValidation
		    .formValidation( 
		        form,
		        {
		            fields: 
					{
						decrease_stock_qty: {
							validators: {
								notEmpty: {
									message: 'Supply price is required'
								}
							}
						},
						out_order_action: {
							validators: {
								notEmpty: {
									message: 'Increase reason is required'
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

		        var form_data = new FormData($("#decreaseProductStock")[0]);
				
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
						$("#increaseStockModal").modal('hide');
						
						var table = $('#productStockLogs').DataTable();
						table.ajax.reload();
						
						$("#decrease_stock_qty").val(0);
						$("#out_order_action").val('Internal Use');
						
						location.reload();
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
    // Public Functions
    return {
        init: function() {
            _handleSendOrderEmail();
			_handleIncreaseProductStock();
			_handleDecreaseProductStock();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTviewOrder.init();
});
