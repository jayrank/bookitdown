$(document).ready(function() {
    // save edit category
    $(document).on("click", "#save", function() {

        $("#addpaidplan").validate({
            rules: {
                name: {
                    required: true,
                },
                services_ids: {
                    required: true,
                },
                sessions: {
                    required: true,
                },
                sessions_num: {
					 required: {
						depends: function(element) {
							return $("#select-location").val() == 0;
						}
					}
                },
                price: {
                    required: true,
                },
                valid_for: {
                    required: true,
                },
                tax: {
                    required: true,
                },
                color: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Name is required"
                },
                sessions: {
                    required: "Sessions is required"
                },
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });
        //end
        if ($('#addpaidplan').valid()) {
            var url = $('#addpaidplan').attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#addpaidplan").serialize(),
                success: function(response) {
                    window.location.href = response.redirect;
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';

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
    });
    //end

    // save edit category
    $(document).on("click", "#update", function() {

        $("#paidplan").validate({
            rules: {
                name: {
                    required: true,
                },
                services_ids: {
                    required: true,
                },
                sessions: {
                    required: true,
                },
                sessions_num: {
					 required: {
						depends: function(element) {
							return $("#select-location").val() == 0;
						}
					}
                },
                price: {
                    required: true,
                },
                valid_for: {
                    required: true,
                },
                tax: {
                    required: true,
                },
                color: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Name is required"
                },
                sessions: {
                    required: "Sessions is required"
                },
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });
        //end

        //update plans
        if ($('#paidplan').valid()) {
            var url = $('#paidplan').attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#paidplan").serialize(),
                success: function(response) {
                    window.location.href = response.redirect;
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';

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
    });
    //end
    //delete plan
    $(document).on("click", '#delete', function() {
        var WEBSITE_URL = "{{ url('') }}";
        KTApp.blockPage();
        var url = $(this).data('url');

        $.ajax({
            type: "get",
            url: url,
            dataType: 'json',
            success: function(data) {
                KTApp.unblockPage();

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
                    toastr.success(data.message);
                    window.setTimeout(function() {
                        window.location = WEBSITE_URL + "/service/services/plans";
                    }, 2000);
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
});