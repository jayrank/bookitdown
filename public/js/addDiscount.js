"use strict";
// Class Definition
var KTLogin = function() {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
    var _handleClientCreate = function() {
        var form = KTUtil.getById('adddiscount');
        var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('saveDis');
        if (!form) {
            return;
        }

        FormValidation
            .formValidation(
                form, {
                    fields: {
                        name: {
                            validators: {
                                notEmpty: {
                                    message: 'Discount name is required'
                                }
                            }
                        },
                        value: {
                            validators: {
                                notEmpty: {
                                    message: 'Discount value is required'
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
                    var form_data = new FormData($("#adddiscount")[0]);

                    $.ajax({
                        type: 'POST',
                        url: formSubmitUrl,
                        data: form_data,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            KTApp.unblockPage();
                            toastr.success(data.message);
                            $('#addNewDiscountModal').modal('hide');
                            var table = $('#distable').DataTable();
                            table.ajax.reload();
                        },
                        error: function(data) {
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
                }, 2000);


                //save tax
                // $(document).on('click', '#savetype', function() {


                // });
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

// Class Definition
var KTupdate = function() {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
    var _handleClientCreate = function() {
        var form = KTUtil.getById('editdiscount');
        var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('updateDis');
        if (!form) {
            return;
        }

        FormValidation
            .formValidation(
                form, {
                    fields: {
                        name: {
                            validators: {
                                notEmpty: {
                                    message: 'Discount name is required'
                                }
                            }
                        },
                        value: {
                            validators: {
                                notEmpty: {
                                    message: 'Discount value is required'
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
                    var form_data = new FormData($("#editdiscount")[0]);

                    $.ajax({
                        type: 'POST',
                        url: formSubmitUrl,
                        data: form_data,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            KTApp.unblockPage();
                            toastr.success(data.message);
                            $('#editNewDiscountModal').modal('hide');
                            var table = $('#distable').DataTable();
                            table.ajax.reload();
                        },
                        error: function(data) {
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
                }, 2000);


                //save tax
                // $(document).on('click', '#savetype', function() {


                // });
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