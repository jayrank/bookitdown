"use strict";
// Class Definition
var KTLogin = function() {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
    var _handleClientCreate = function() {
        var form = KTUtil.getById('ajaxAddProduct');
        var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('addProductSubmitButton');
        if (!form) {
            return;
        }

        FormValidation
            .formValidation(
                form, {
                    fields: {
                        product_name: {
                            validators: {
                                notEmpty: {
                                    message: 'Product name is required'
                                }
                            }
                        },
                        retail_price: {
                            validators: {
                                notEmpty: {
                                    message: 'Retail price is required'
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

                var form_data = new FormData($("#ajaxAddProduct")[0]);

                $.ajax({
                    type: 'POST',
                    url: formSubmitUrl,
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == true) {
                            window.location.href = response.redirect;
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
                    error: function(data) {
                        KTUtil.btnRelease(formSubmitButton, "Save");
                        var errors = data.responseJSON;

                        var errorsHtml = '';
                        $.each(errors.error, function(key, value) {
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

// add order Definition
var addOrder = function() {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
    var _handleClientCreate = function() {
        var form = KTUtil.getById('saveOrder');
        var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('createOrder');
        if (!form) {
            return;
        }

        jQuery(document).on("click", "#createOrder", function() {
            // Show loading state on button
            KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");

            // Simulate Ajax request
            setTimeout(function() {
                KTUtil.btnRelease(formSubmitButton);
            }, 2000);

            var form_data = new FormData($("#saveOrder")[0]);

            $.ajax({
                type: 'POST',
                url: formSubmitUrl,
                data: form_data,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = response.redirect;
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
                error: function(data) {
                    KTUtil.btnRelease(formSubmitButton, "Save");
                    var errors = data.responseJSON;

                    var errorsHtml = '';
                    $.each(errors.error, function(key, value) {
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
    addOrder.init();
});