"use strict";

// Class Definition
var KTAdd = function() {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';

    var _handleClientCreate = function() {

            var form = KTUtil.getById('add_service_frm');
            var formSubmitUrl = KTUtil.attr(form, 'action');
            var formSubmitButton = KTUtil.getById('service_submit');
            if (!form) {
                return;
            }

            serviceValid = FormValidation
                .formValidation(
                    form, {
                        fields: {
                            service_name: {
                                validators: {
                                    notEmpty: {
                                        message: 'Service name is required'
                                    }
                                }
                            },
                            treatment_type: {
                                validators: {
                                    notEmpty: {
                                        message: 'Treatment type is required'
                                    }
                                }
                            },
                            service_category: {
                                validators: {
                                    notEmpty: {
                                        message: 'Service category is required'
                                    }
                                }
                            },
                            'price1': priceValidators
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger(),
                            submitButton: new FormValidation.plugins.SubmitButton(),
                            bootstrap: new FormValidation.plugins.Bootstrap({
                                eleInvalidClass: '', // Repace with uncomment to hide bootstrap validation icons
                                eleValidClass: '', // Repace with uncomment to hide bootstrap validation icons
                            })
                        }
                    }
                )
                .on('core.form.valid', function() {
                    // Show loading state on button
                    KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");
                    setTimeout(function() {
                        KTUtil.btnRelease(formSubmitButton);
                        var form_data = new FormData($("#add_service_frm")[0]);

                        var checkboxs = document.getElementsByName("staff_id[]");
                        var okay = false;

                        for (var i = 0; i < checkboxs.length; i++) {
                            if (checkboxs[i].checked) {
                                okay = true;
                                break;
                            }
                        }

                        if (okay == false) {

                            var errorsHtml = '';
                            toastr.error((errorsHtml) ? errorsHtml : 'At least one staff member must be selected');
                            KTUtil.scrollTop();
                            return

                        } else {

                            $.ajax({
                                type: 'POST',
                                url: formSubmitUrl,
                                data: form_data,
                                dataType: 'json',
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    window.location.href = response.redirect;
                                },
                                error: function(data) {
                                    KTUtil.btnRelease(formSubmitButton, "Save");
                                    var errors = data.responseJSON;
                                    var errorsHtml = '';
                                    $.each(errors.error, function(key, value) {
                                        errorsHtml += '<p>' + value + '</p>';
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

                                    toastr.error((errorsHtml) ? errorsHtml : 'There is something wrong!');
                                    KTUtil.scrollTop();
                                }
                            });
                        }
                    }, 2000);
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

                    toastr.error('Sorry, looks like there are some errors detected, please try again.');
                    KTUtil.scrollTop();
                });
        }
        // Public Functions
		
	var _handleDeleteServicePrice = function() {
		var form = KTUtil.getById('deleteServicePrice');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton  = KTUtil.getById('deleteServicePriceBtn');
	
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

		        var form_data = new FormData($("#deleteServicePrice")[0]);
				
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
							$("#deleteServicePriceModal").modal('hide');
							
							var id = $("#deleteServicePriceId").val();
							$(".pr_rw"+id).remove();
							
							var cnt = $("#total_pricing").val();
							cnt--;
							$("#total_pricing").val(cnt);
							
							$("#deleteServicePrice")[0].reset();
							
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
						KTUtil.btnRelease(formSubmitButton, "Delete");
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
		
    return {
        init: function() {
            _handleClientCreate();
			_handleDeleteServicePrice();
        }
    };
}();


// Class Definition
var KTedit = function() {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';

    var _handleServiceEdit = function() {

            var form = KTUtil.getById('edit_service_frm');
            var formSubmitUrl = KTUtil.attr(form, 'action');
            var formSubmitButton = KTUtil.getById('service_update');
            if (!form) {
                return;
            }

            serviceValid = FormValidation
                .formValidation(
                    form, {
                        fields: {
                            service_name: {
                                validators: {
                                    notEmpty: {
                                        message: 'Service name is required'
                                    }
                                }
                            },
                            treatment_type: {
                                validators: {
                                    notEmpty: {
                                        message: 'Treatment type is required'
                                    }
                                }
                            },
                            service_category: {
                                validators: {
                                    notEmpty: {
                                        message: 'Service category is required'
                                    }
                                }
                            },
                            'price1': priceValidators
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger(),
                            submitButton: new FormValidation.plugins.SubmitButton(),
                            bootstrap: new FormValidation.plugins.Bootstrap({
                                eleInvalidClass: '', // Repace with uncomment to hide bootstrap validation icons
                                eleValidClass: '', // Repace with uncomment to hide bootstrap validation icons
                            })
                        }
                    }
                )
                .on('core.form.valid', function() {
                    // Show loading state on button
                    KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");
                    setTimeout(function() {
                        KTUtil.btnRelease(formSubmitButton);
                        var form_data = new FormData($("#edit_service_frm")[0]);

                        var checkboxs = document.getElementsByName("staff_id[]");
                        var okay = false;

                        for (var i = 0; i < checkboxs.length; i++) {
                            if (checkboxs[i].checked) {
                                okay = true;
                                break;
                            }
                        }

                        if (okay == false) {

                            var errorsHtml = '';
                            toastr.error((errorsHtml) ? errorsHtml : 'At least one staff member must be selected');
                            KTUtil.scrollTop();
                            return

                        } else {

                            $.ajax({
                                type: 'POST',
                                url: formSubmitUrl,
                                data: form_data,
                                dataType: 'json',
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    window.location.href = response.redirect;
                                },
                                error: function(data) {
                                    KTUtil.btnRelease(formSubmitButton, "Save");
                                    var errors = data.responseJSON;
                                    var errorsHtml = '';
                                    $.each(errors.error, function(key, value) {
                                        errorsHtml += '<p>' + value[0] + '</p>';
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

                                    toastr.error((errorsHtml) ? errorsHtml : 'There is something wrong!');
                                    KTUtil.scrollTop();
                                }
                            });
                        }
                    }, 2000);
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

                    toastr.error('Sorry, looks like there are some errors detected, please try again.');
                    KTUtil.scrollTop();
                });
        }
        // Public Functions
    return {
        init: function() {
            _handleServiceEdit();
        }
    };
}();



// save category
var _FormService = function() {
    var form = KTUtil.getById('cat');
    var formSubmitButton = KTUtil.getById('save');

    if (!form) {
        return;
    }

    serviceValid = FormValidation
        .formValidation(
            form, {
                fields: {
                    category_description: {
                        validators: {
                            notEmpty: {
                                message: 'category description is required'
                            }
                        }
                    },
                    appointment_color: {
                        validators: {
                            notEmpty: {
                                message: 'select appointment color type is required'
                            }
                        }
                    },
                    category_title: {
                        validators: {
                            notEmpty: {
                                message: 'category title is required'
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
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

            var form_data = new FormData($(form)[0]);


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

            toastr.error('Sorry, looks like there are some errors detected, please try again.');
            KTUtil.scrollTop();
        });
}

// Class Initialization
jQuery(document).ready(function() {
    KTAdd.init();
    KTedit.init();

    $("#add_pricing_opt").click(function() {

        var cnt = $("#total_pricing").val();
        cnt++;

        var staffdata = $.parseJSON(staff);
        var durationdata = $.parseJSON(duration);
        var html = '';
        var selected = '';
        console.log(staffdata);
        html += '<div class="w-100 pricing_row pr_rw' + cnt + '">';
        html += '<h3 class="col-md-12 font-weight-bolder pricing_opt_txt">Pricing option ' + cnt + ' <a class="remove_pricing_opt" data-id="' + cnt + '" href="javascript:;"><i class="ki ki-close text-danger"></i></a></h3>';
        html += '<div class="w-100 d-flex price-type-container">';
        html += '<div class="col-md-2 ad">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Duration</label>';
        html += '<div class="select-wrapper nw_cl">';
        html += '<div class="_1RNu0qum EJYPiwxT">';
        html += '<select class="select optional form-control ddr" default="default" data-optid="' + cnt + '" name="duration' + cnt + '" id="duration' + cnt + '">';
        $.each(durationdata, function(index, element) {
            if(index == 60) {
                selected = 'selected';
            } else {
                selected = '';
            }
            html += '<option '+selected+' value="' + index + '">' + element + '</option>';
        });
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>	';
        html += '</div>';
        html += '<div class="col-md-2 ad">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Price type</label>';
        html += '<div class="select-wrapper nw_cl">';
        html += '<div class="_1RNu0qum EJYPiwxT">';
        html += '<select class="select optional form-control dpt" data-optid="' + cnt + '" name="price_type' + cnt + '" id="price_type' + cnt + '">';
        html += '<option value="free">Free</option>';
        html += '<option value="from">From</option>';
        html += '<option value="fixed" selected >Fixed</option>';
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-2 ad freehd' + cnt + '">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Price <span class="price-type-text text-muted"></span></label>';
        html += '<input type="text" class="form-control dpr allow_only_decimal custom-price-field" name="price' + cnt + '" data-optid="' + cnt + '" id="price' + cnt + '" autocomplete="off" placeholder="Price"/>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-3 ad freehd' + cnt + '">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Special price <span class="text-muted">(optional)</span></label>';
        html += '<input type="text" class="form-control dsp allow_only_decimal custom-special-price-field" name="special_price' + cnt + '" data-optid="' + cnt + '" id="special_price' + cnt + '" autocomplete="off" placeholder="Special price"/>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-3 ad">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Pricing name <span class="text-muted">(optional)</span></label>';
        html += '<input type="text" class="form-control dpn" name="pricing_name' + cnt + '" data-optid="' + cnt + '" id="pricing_name' + cnt + '" autocomplete="off" placeholder="Pricing name"/>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="modal fade" id="add_pricing_opt' + cnt + '" data-id="add_pricing_opt" tabindex="-1" role="dialog" aria-labelledby="exampleModalSizeXl" style="display: none;" aria-hidden="true">';
        html += '<div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="max-width: 1203px !important;">';
        html += '<div class="modal-content">';
        html += '<div class="modal-header">';
        html += '<h5 class="modal-title text-left" id="exampleModalLabel">Advanced pricing options</h5>';
        html += '<button type="button" class="close close-add-pricing-opt-modal " aria-label="Close" style="right: unset;"><i aria-hidden="true" class="ki ki-close"></i></button>';
        html += '</div>';
        html += '<div class="modal-body">';
        html += '<div class="row custom-pricing-row">';
        html += '<div class="col-md-3">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Pricing name <span class="text-muted">(optional)</span></label>';
        html += '<input type="text" class="form-control dpn" data-optid="' + cnt + '" id="mpricing_name' + cnt + '" autocomplete="off" placeholder="Pricing name"/>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Duration</label>';
        html += '<div class="select-wrapper nw_cl">';
        html += '<div class="_1RNu0qum EJYPiwxT">';
        html += '<select class="select optional form-control ddr" data-optid="' + cnt + '" default="default" id="mduration' + cnt + '">';
        $.each(durationdata, function(index, element) {
            if(index == 60) {
                selected = 'selected';
            } else {
                selected = '';
            }
            html += '<option '+selected+' value="' + index + '">' + element + '</option>';
        });
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Price type</label>';
        html += '<div class="select-wrapper nw_cl">';
        html += '<div class="_1RNu0qum EJYPiwxT">';
        html += '<select class="select optional form-control dpt" data-optid="' + cnt + '" id="mprice_type' + cnt + '">';
        html += '<option value="free">Free</option>';
        html += '<option value="from">From</option>';
        html += '<option value="fixed" selected >Fixed</option>';
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-2 mprice' + cnt + '">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Price</span></label>';
        html += '<input type="text" class="form-control dpr allow_only_decimal custom-price-field" data-optid="' + cnt + '" id="mprice' + cnt + '" autocomplete="off" placeholder="Price"/>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-3 mspecial_price' + cnt + '">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Special price <span class="text-muted">(optional)</span></label>';
        html += '<input type="text" class="form-control dsp allow_only_decimal custom-special-price-field" data-optid="' + cnt + '" id="mspecial_price' + cnt + '" autocomplete="off" placeholder="Special price"/>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="row">';
        html += '<div class="col-md-12"><h3>Set price by staff</h3></div>';
        html += '<div class="col-md-3">';
        html += '<div class="form-group"></div>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Duration</label>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Price type</label>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Price</label>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-3">';
        html += '<div class="form-group">';
        html += '<label class="col-form-label text-right">Special price <span class="text-muted">(optional)</span></label>';
        html += '</div>';
        html += '</div>';
        $(staffdata).each(function(i, val) {
            html += '<div class="staff-price-container col-md-12 row custom-pricing-row">';
            html += '<div class="col-md-3">';
            html += '<div class="form-group">';
            html += '<h5>' + val.user.first_name + ' ' + val.user.last_name + '</h5>';
            html += '<input type="hidden" name="staff_ids' + cnt + '[]" value="' + val.id + '">';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-2">';
            html += '<div class="form-group">';
            html += '<div class="select-wrapper nw_cl">';
            html += '<div class="_1RNu0qum EJYPiwxT">';
            html += '<select class="select optional form-control sdr' + cnt + '" default="default" name="staff_duration' + cnt + '[]">';
            $.each(durationdata, function(index, element) {
                if(index == 60) {
                    selected = 'selected';
                } else {
                    selected = '';
                }
                html += '<option '+selected+' value="' + index + '">' + element + '</option>';
            });
            html += '</select>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-2">';
            html += '<div class="form-group">';
            html += '<div class="select-wrapper nw_cl">';
            html += '<div class="_1RNu0qum EJYPiwxT">';
            html += '<select class="select optional form-control spt' + cnt + ' staff-price-type" name="staff_price_type' + cnt + '[]" >';
            html += '<option value="free">Free</option>';
            html += '<option value="from">From</option>';
            html += '<option value="fixed" selected >Fixed</option>';
            html += '</select>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-2">';
            html += '<div class="form-group">';
            html += '<input type="text" class="form-control spr' + cnt + ' staff-price allow_only_decimal custom-price-field" name="staff_price' + cnt + '[]" autocomplete="off" placeholder="Price"/>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-3">';
            html += '<div class="form-group">';
            html += '<input type="text" class="form-control ssp' + cnt + ' staff-special-price allow_only_decimal custom-special-price-field" name="staff_special_price' + cnt + '[]" autocomplete="off" placeholder="Special price"/>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        });
        html += '</div>';
        html += '</div>';
        html += '<div class="modal-footer">';
        html += '<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>';
        html += '<button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal">Save changes</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-12 d-flex w-100">';
        html += '<a class="advacned_pricing_opt h4 text-blue" data-target="#add_pricing_opt' + cnt + '" data-toggle="modal" href="javascript:;">Advanced pricing options</a>';
        html += '</div>';
        html += '</div>';

        $(".addpricing_option").append(html);
        $("#total_pricing").val(cnt);

        serviceValid.addField(`price${cnt}`, priceValidators)
    });

    $(document).on("keyup", ".dpn", function() {
        var txt = $(this).val();
        var id = $(this).data('optid');
        console.log(id);
        $("#pricing_name" + id).val(txt);
        $("#mpricing_name" + id).val(txt);
    });

    $(document).on("change", ".ddr", function() {
        var txt = $(this).val();
        var id = $(this).data('optid');
        $("#duration" + id).val(txt);
        $("#mduration" + id).val(txt);
        $(".sdr" + id).val(txt);
    });

    $(document).on("change", ".dpt", function() {
        var txt = $(this).val();
        var id = $(this).data('optid');
        $("#price_type" + id).val(txt);

        if (txt == 'from') {
            $("#price_type" + id).closest('.price-type-container').find('.price-type-text').text('('+txt+')');
        } else {
            $("#price_type" + id).closest('.price-type-container').find('.price-type-text').text('');
        }
        
        $("#mprice_type" + id).val(txt);
        $(".spt" + id).val(txt);
        $(".spr"+id+", .ssp"+id).attr("disabled", false);
        $(".spr"+id+", .ssp"+id).removeAttr("readonly");
        if(txt == 'free'){
            $(".spr"+id+", .ssp"+id).val("");
            $(".spr"+id+", .ssp"+id).attr("disabled", true);
            $(".spr"+id+", .ssp"+id).attr("readonly", true);
            serviceValid.removeField(`price${id}`, priceValidators);
        } else {
            serviceValid.addField(`price${id}`, priceValidators);
        }

        if (txt == 'free') {
            $(".freehd" + id).hide();
            $("#mprice" + id).attr("disabled", true);
            $("#mspecial_price" + id).attr("disabled", true);
        } else {
            $(".freehd" + id).show();
            $("#mprice" + id).attr("disabled", false);
            $("#mspecial_price" + id).attr("disabled", false);
        }
    });

    $(document).on("keyup", ".dpr", function() {
        var txt = $(this).val();
        var id = $(this).data('optid');
        $("#price" + id).val(txt);
        $("#mprice" + id).val(txt);

        var requiredFields = $('.spr' + id);
        requiredFields.each(function(index) {
            $(this).attr("placeholder", txt);
        });
    });

    $(document).on("keyup", ".dsp", function() {
        var txt = $(this).val();
        var id = $(this).data('optid');
        $("#special_price" + id).val(txt);
        $("#mspecial_price" + id).val(txt);

        var requiredFields = $('.ssp' + id);
        requiredFields.each(function(index) {
            $(this).attr("placeholder", txt);
        });
    });

    //delete service
    $(document).on("click", '#serdelete', function() {
        var WEBSITE_URL = "{{ url('') }}";
        KTApp.blockPage();
        var url = $(this).data('url');

        $.ajax({
            type: "get",
            url: url,
            dataType: 'json',
            success: function(data) {
                KTApp.unblockPage();
                var re = data.redirect;
                window.setTimeout(function() {
                    window.location.href = re;
                }, 2000);
                if (data.status == true) {
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

                } else {
                    table.ajax.reload();

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
                    toastr.error(data.message);
                    window.setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
            }
        });
    });
    //end

    $(document).on('keydown', ".allow_only_decimal",function (event) {
        if (event.shiftKey == true) {
            event.preventDefault();
        }

        if ((event.keyCode >= 48 && event.keyCode <= 57) || 
            (event.keyCode >= 96 && event.keyCode <= 105) || 
            event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
            event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190 || event.keyCode == 110) {

        } else {
            event.preventDefault();
        }

        if($(this).val().indexOf('.') !== -1 && (event.keyCode == 190 || event.keyCode == 110))
            event.preventDefault(); 
        //if a decimal has been added, disable the "."-button

    });
    
    $(document).on('keyup', '.custom-price-field', function() {
        var hasError = false;
        var thisVal = parseFloat($(this).val());

        if($(this).siblings('.price-validation-error').length == 0) {
            $('<div class="text-danger price-validation-error"></div>').insertAfter(this);
        }
        if($(this).closest('.custom-pricing-row').find(".custom-special-price-field").siblings('.special-price-validation-error').length == 0) {
            $('<div class="text-danger special-price-validation-error"></div>').insertAfter($(this).closest('.custom-pricing-row').find(".custom-special-price-field"));
        }
        if(thisVal > 100000000) {
            $(this).siblings('.price-validation-error').text('Price must be 100000000 or lower');
            hasError = true;
        }

        if(parseFloat($(this).closest('.custom-pricing-row').find(".custom-special-price-field").val()) > thisVal) {
            $(this).closest('.custom-pricing-row').find(".custom-special-price-field").siblings('.special-price-validation-error').text('Special price must be lower than retail price');
            hasError = true;
        } else {
            $(this).closest('.custom-pricing-row').find(".custom-special-price-field").siblings('.special-price-validation-error').text('');
        }

        if(!hasError) {
            $(this).siblings('.price-validation-error').text('');
        }

        disableSubmit();
    })
    $(document).on('keyup', '.custom-special-price-field', function() {
        var hasError = false;
        var thisVal = parseFloat($(this).val());

        if($(this).siblings('.special-price-validation-error').length == 0) {
            $('<div class="text-danger special-price-validation-error"></div>').insertAfter(this);
        }
        if(thisVal > 100000000) {
            $(this).siblings('.special-price-validation-error').text('Price must be 100000000 or lower');
            hasError = true;
        }

        if(thisVal > parseFloat($(this).closest('.custom-pricing-row').find(".custom-price-field").val())) {
            $(this).siblings('.special-price-validation-error').text('Special price must be lower than retail price');
            hasError = true;
        }

        if(!hasError) {
            $(this).siblings('.special-price-validation-error').text('');
        }

        disableSubmit();
    })

    function disableSubmit() {
        if($('.price-validation-error').text() != '') {
            $('#service_update').prop('disabled', true);
            $('#service_submit').prop('disabled', true);
        } else if($('.special-price-validation-error').text() != '') {
            $('#service_update').prop('disabled', true);
            $('#service_submit').prop('disabled', true);
        } else {
            $('#service_update').prop('disabled', false);
            $('#service_submit').prop('disabled', false);
        }
    }
});