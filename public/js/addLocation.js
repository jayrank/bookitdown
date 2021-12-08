"use strict";

// Class Definition
var KTLogin = function() {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
    var _handleClientCreate = function() {
        var form = KTUtil.getById('addlocation');
        var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('addlocationsubmit');

        if (!form) {
            return;
        }

        FormValidation
            .formValidation(
                form, {

                    fields: {
                        location_name: {
                            validators: {
                                notEmpty: {
                                    message: 'Location name is required'
                                }
                            }
                        },
                        location_email: {
                            validators: {
                                notEmpty: {
                                    message: 'email is mandatory'
                                }
                            }
                        },
                        location_phone: {
                            validators: {
                                notEmpty: {
                                    message: 'phone number is mandatory'
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
				
				alert("asdasdasD");
				return false;

                var form_data = new FormData($("#addlocation")[0]);

                $.ajax({
                    type: 'POST',
                    url: formSubmitUrl,
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        KTUtil.btnRelease(formSubmitButton);

                        document.getElementById("addlocation").reset();

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

                        window.setTimeout(function() {
                            window.location = WEBSITE_URL + "/setup/location";
                        }, 2000);
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
                        window.setTimeout(function() {
                            window.location.reload();
                        }, 2000);
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
                window.setTimeout(function() {
                    window.location.load();
                }, 2000);
            });
    }

    // Public Functions
    return {
        init: function() {
            _handleClientCreate();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTLogin.init();
});