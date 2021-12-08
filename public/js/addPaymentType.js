"use strict";

// Class Definition
var KTLogin = function() {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';

    var _handleClientCreate = function() {
        var form = KTUtil.getById('paymenttype');
        var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('savetype');

        if (!form) {
            return;
        }

        FormValidation
            .formValidation(
                form, {
                    fields: {
                        payment_type: {
                            validators: {
                                notEmpty: {
                                    message: 'Payment type is required'
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

                var form_data = new FormData($("#paymenttype")[0]);

                $.ajax({
                    type: 'POST',
                    url: formSubmitUrl,
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        KTUtil.btnRelease(formSubmitButton);
                        $('#addPaymetType').modal('hide');
                        var table = $('#paytype').DataTable();
                        table.ajax.reload();

                        form.reset();

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

    // Public Functions
    return {
        init: function() {
            _handleClientCreate();
        }
    };
}();


var KTupdate = function() {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';

    var _handleClientCreate = function() {
        var form = KTUtil.getById('updatepaymenttype');
        var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('update');

        if (!form) {
            return;
        }

        FormValidation
            .formValidation(
                form, {
                    fields: {
                        payment_type: {
                            validators: {
                                notEmpty: {
                                    message: 'Payment type is required'
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

                var form_data = new FormData($("#updatepaymenttype")[0]);

                $.ajax({
                    type: 'POST',
                    url: formSubmitUrl,
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        KTUtil.btnRelease(formSubmitButton);
                        $('#editPaymetType').modal('hide');
                        var table = $('#paytype').DataTable();
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
    KTupdate.init();
});