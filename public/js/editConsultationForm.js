"use strict";
// Class Definition
function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

var KTaddConsultationForm = function() {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
    var _handlesaveConsultationForm = function() {
        var form = KTUtil.getById('saveConsultationForm');
        var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('submitConsultationFormBtn');

        if (!form) {
            return;
        }

        FormValidation
            .formValidation(
                form, {
                    fields: {
                        'consultation_form_name': {
                            validators: {
                                notEmpty: {
                                    message: 'Consultation form title is required'
                                }
                            }
                        },
                        'selectedServices': {
                            validators: {
                                notEmpty: {
                                    message: 'Service field is required'
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

                var form_data = new FormData($("#saveConsultationForm")[0]);

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

                            KTUtil.btnRelease(formSubmitButton, "Save");
                        }
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

    var _handleAddClientInformationForm = function() {
        var form = KTUtil.getById('addClientSectionForm');
        var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('addClientSectionBtn');

        if (!form) {
            return;
        }

        FormValidation
            .formValidation(
                form, {
                    fields: {
                        'addClientSectionTitle': {
                            validators: {
                                notEmpty: {
                                    message: 'Section title is required'
                                }
                            }
                        },
                        'clientDetailFields[]': {
                            validators: {
                                choice: {
                                    min: 1,
                                    message: 'Please check at least 1 option'
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

                var addClientSectionTitle = $("#addClientSectionTitle").val();
                var addClientSectionDescription = $("#addClientSectionDescription").val();
                var clientSectionFirstName = $("#clientSectionFirstName").val();
                var clientSectionLastName = $("#clientSectionLastName").val();
                var clientSectionEmail = $("#clientSectionEmail").val();
                var clientSectionBirthday = $("#clientSectionBirthday").val();
                var clientSectionMobileNumber = $("#clientSectionMobileNumber").val();
                var clientSectionGender = $("#clientSectionGender").val();
                var clientSectionAddress = $("#clientSectionAddress").val();

                $(".defaultView").hide();
                $(".afterView").show();
                $("#addClientDetailModal").modal('hide');

                $(".detailsFormBtn").attr('disabled', false);

                var unique_id = $("#clientUniqueDivID").val();
                if (unique_id == '') {
                    var unique_id = makeid(15);
                    $("#clientUniqueDivID").val(unique_id);
                }

                var clientInfoHtml = '';

                var checkClientFormIncluded = $(".clientFormIncluded").length;

                if (checkClientFormIncluded == 0) {
                    var current_section = (parseInt($("#total_section").val())) + 1;
                    $("#total_section").val(current_section);
                    var total_section = $("#total_section").val();
                    $(".TotalSection").html(total_section);

                    clientInfoHtml += '<div class="card w-70 mx-auto mt-16 customFieldDivCount sectionIdentitiyClass' + current_section + '" id="' + unique_id + '">';
                    clientInfoHtml += '<div class="my-card-lable-purple">Section <span class="CurrentSection">' + current_section + '</span> of <span class="TotalSection">' + total_section + '</span> : Client details</div>';

                    clientInfoHtml += '<input type="hidden" name="section_id[]" value="' + current_section + '">';
                    clientInfoHtml += '<input type="hidden" name="form_type[]" value="client_form">';

                    clientInfoHtml += '<div class="clientFormIncluded">';
                }
                clientInfoHtml += '<div class="card-header d-flex justify-content-between">';
                clientInfoHtml += '<div>';

                if (addClientSectionTitle != '') {
                    clientInfoHtml += '<h3>' + addClientSectionTitle + '</h3>';
                    clientInfoHtml += '<input type="hidden" name="clientFromSectionTitle" value="' + addClientSectionTitle + '">';
                }

                if (addClientSectionDescription != '') {
                    clientInfoHtml += '<p class="m-0 text-dakr-50">' + addClientSectionDescription + '</p>';
                    clientInfoHtml += '<input type="hidden" name="clientFromSectionDesc" value="' + addClientSectionDescription + '">';
                }

                clientInfoHtml += '</div>';
                clientInfoHtml += '<div class="dropdown dropdown-inline">';
                clientInfoHtml += '<a href="#" class="btn btn-clean text-dark btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                clientInfoHtml += '<i class="ki ki-bold-more-ver text-dark"></i>';
                clientInfoHtml += '</a>';
                clientInfoHtml += '<div class="dropdown-menu dropdown-menu-right text-center">';
                clientInfoHtml += '<ul class="navi navi-hover">';
                clientInfoHtml += '<li class="navi-item">';
                clientInfoHtml += '<a href="javascript:;" class="navi-link" data-toggle="modal" data-target="#addClientDetailModal">';
                clientInfoHtml += '<span class="navi-text">Edit</span>';
                clientInfoHtml += '</a>';
                clientInfoHtml += '</li>';
                clientInfoHtml += '<li class="navi-item">';
                clientInfoHtml += '<a href="javascript:;" class="navi-link removeMe" data-deleteId="' + unique_id + '">';
                clientInfoHtml += '<span class="navi-text text-danger">Delete</span>';
                clientInfoHtml += '</a>';
                clientInfoHtml += '</li>';
                clientInfoHtml += '</ul>';
                clientInfoHtml += '</div>';
                clientInfoHtml += '</div>';
                clientInfoHtml += '</div>';
                clientInfoHtml += '<div class="card-body">';

                if ($("#clientSectionFirstName").is(':checked')) {
                    clientInfoHtml += '<div class="form-group">';
                    clientInfoHtml += '<label class="font-weight-bolder">First name</label>';
                    clientInfoHtml += '<input type="text" disabled class="form-control">';
                    clientInfoHtml += '<input type="hidden" class="form-control" name="first_name" id="first_name" value="1">';
                    clientInfoHtml += '</div>';
                } else {
                    clientInfoHtml += '<input type="hidden" class="form-control" name="first_name" id="first_name" value="0">';
                }

                if ($("#clientSectionLastName").is(':checked')) {
                    clientInfoHtml += '<div class="form-group">';
                    clientInfoHtml += '<label class="font-weight-bolder">Last name</label>';
                    clientInfoHtml += '<input type="text" disabled class="form-control">';
                    clientInfoHtml += '<input type="hidden" class="form-control" name="last_name" id="last_name" value="1">';
                    clientInfoHtml += '</div>';
                } else {
                    clientInfoHtml += '<input type="hidden" class="form-control" name="last_name" id="last_name" value="0">';
                }

                if ($("#clientSectionEmail").is(':checked')) {
                    clientInfoHtml += '<div class="form-group">';
                    clientInfoHtml += '<label class="font-weight-bolder">Email</label>';
                    clientInfoHtml += '<input type="email" disabled class="form-control">';
                    clientInfoHtml += '<input type="hidden" class="form-control" name="email" id="email" value="1">';
                    clientInfoHtml += '</div>';
                } else {
                    clientInfoHtml += '<input type="hidden" class="form-control" name="email" id="email" value="0">';
                }

                if ($("#clientSectionBirthday").is(':checked')) {
                    clientInfoHtml += '<div class="form-group">';
                    clientInfoHtml += '<label class="font-weight-bolder">Birthday</label>';
                    clientInfoHtml += '<input type="date" disabled class="form-control">';
                    clientInfoHtml += '<input type="hidden" class="form-control" name="birthday" id="birthday" value="1">';
                    clientInfoHtml += '</div>';
                } else {
                    clientInfoHtml += '<input type="hidden" class="form-control" name="birthday" id="birthday" value="0">';
                }

                if ($("#clientSectionMobileNumber").is(':checked')) {
                    clientInfoHtml += '<div class="form-group selectCountryCodeClass">';
                    clientInfoHtml += '<label class="font-weight-bolder">Mobile Number</label>';
                    clientInfoHtml += '<input type="text" class="form-control" id="countryCodeSelection">';
                    clientInfoHtml += '<input type="hidden" class="form-control" name="mobile" id="mobile" value="1">';
                    clientInfoHtml += '</div>';
                } else {
                    clientInfoHtml += '<input type="hidden" class="form-control" name="mobile" id="mobile" value="0">';
                }

                if ($("#clientSectionGender").is(':checked')) {
                    clientInfoHtml += '<div class="form-group">';
                    clientInfoHtml += '<label class="font-weight-bolder">Gender</label>';
                    clientInfoHtml += '<div class="dropdown">';
                    clientInfoHtml += '<select class="custom-select" disabled>';
                    clientInfoHtml += '<option selected>Select Option</option>';
                    clientInfoHtml += '<option>Male</option>';
                    clientInfoHtml += '<option>Female</option>';
                    clientInfoHtml += '<option>Other</option>';
                    clientInfoHtml += '<option>I dont want to share</option>';
                    clientInfoHtml += '</select>';
                    clientInfoHtml += '<input type="hidden" class="form-control" name="gender" id="gender" value="1">';
                    clientInfoHtml += '</div>';
                    clientInfoHtml += '</div>';
                } else {
                    clientInfoHtml += '<input type="hidden" class="form-control" name="gender" id="gender" value="0">';
                }

                if ($("#clientSectionAddress").is(':checked')) {
                    clientInfoHtml += '<div class="form-group">';
                    clientInfoHtml += '<label class="font-weight-bolder">Address</label>';
                    clientInfoHtml += '<textarea rows="4" disabled class="form-control"></textarea>';
                    clientInfoHtml += '<input type="hidden" class="form-control" name="address" id="address" value="1">';
                    clientInfoHtml += '</div>';
                } else {
                    clientInfoHtml += '<input type="hidden" class="form-control" name="address" id="address" value="0">';
                }


                if (checkClientFormIncluded == 0) {
                    clientInfoHtml += '</div>';
                    clientInfoHtml += '</div>';
                    clientInfoHtml += '</div>';
                }

                if (checkClientFormIncluded == 0) {
                    $(".appendTheContentHere").append(clientInfoHtml);
                } else {
                    $(".clientFormIncluded").html(clientInfoHtml);
                }

                var sectionPreview = '';
                var sectionFormPreview = '';

                if ($(".clientInforPreview").length == 0) {
                    $(".clientInforPreview").remove();
                    $(".clientInforForm").remove();

                    sectionPreview += '<div class="preview-tab clientInforPreview ' + unique_id + '" data-sectionTitle="' + addClientSectionTitle + '" data-sectionDescription="' + addClientSectionDescription + '">';
                    sectionFormPreview += '<div class="forms-tab clientInforForm ' + unique_id + '" data-sectionTitle="' + addClientSectionTitle + '" data-sectionDescription="' + addClientSectionDescription + '">';
                }
                sectionPreview += '<div class="card-body">';
                sectionFormPreview += '<div class="card-body">';

                if ($("#clientSectionFirstName").is(':checked')) {
                    sectionPreview += '<div class="form-group">';
                    sectionPreview += '<label class="font-weight-bolder">First name</label>';
                    sectionPreview += '<input type="text" class="form-control">';
                    sectionPreview += '</div>';

                    sectionFormPreview += '<div class="form-group">';
                    sectionFormPreview += '<label class="font-weight-bolder">First name</label>';
                    sectionFormPreview += '<input type="text" class="form-control">';
                    sectionFormPreview += '</div>';
                }

                if ($("#clientSectionLastName").is(':checked')) {
                    sectionPreview += '<div class="form-group">';
                    sectionPreview += '<label class="font-weight-bolder">Last name</label>';
                    sectionPreview += '<input type="text" class="form-control">';
                    sectionPreview += '</div>';

                    sectionFormPreview += '<div class="form-group">';
                    sectionFormPreview += '<label class="font-weight-bolder">Last name</label>';
                    sectionFormPreview += '<input type="text" class="form-control">';
                    sectionFormPreview += '</div>';
                }

                if ($("#clientSectionEmail").is(':checked')) {
                    sectionPreview += '<div class="form-group">';
                    sectionPreview += '<label class="font-weight-bolder">Email</label>';
                    sectionPreview += '<input type="email" class="form-control">';
                    sectionPreview += '</div>';

                    sectionFormPreview += '<div class="form-group">';
                    sectionFormPreview += '<label class="font-weight-bolder">Email</label>';
                    sectionFormPreview += '<input type="email" class="form-control">';
                    sectionFormPreview += '</div>';
                }

                if ($("#clientSectionBirthday").is(':checked')) {
                    sectionPreview += '<div class="form-group">';
                    sectionPreview += '<label class="font-weight-bolder">Birthday</label>';
                    sectionPreview += '<input type="date" class="form-control">';
                    sectionPreview += '</div>';

                    sectionFormPreview += '<div class="form-group">';
                    sectionFormPreview += '<label class="font-weight-bolder">Birthday</label>';
                    sectionFormPreview += '<input type="date" class="form-control">';
                    sectionFormPreview += '</div>';
                }

                if ($("#clientSectionMobileNumber").is(':checked')) {
                    sectionPreview += '<div class="form-group selectCountryCodeClass">';
                    sectionPreview += '<label class="font-weight-bolder">Mobile Number</label>';
                    sectionPreview += '<input type="text" class="form-control countryCodeSelectionPreview">';
                    sectionPreview += '</div>';

                    sectionFormPreview += '<div class="form-group selectCountryCodeClass">';
                    sectionFormPreview += '<label class="font-weight-bolder">Mobile Number</label>';
                    sectionFormPreview += '<input type="text" class="form-control countryCodeSelectionPreview">';
                    sectionFormPreview += '</div>';
                }

                if ($("#clientSectionGender").is(':checked')) {
                    sectionPreview += '<div class="form-group">';
                    sectionPreview += '<label class="font-weight-bolder">Gender</label>';
                    sectionPreview += '<div class="dropdown">';
                    sectionPreview += '<select class="custom-select">';
                    sectionPreview += '<option selected>Select Option</option>';
                    sectionPreview += '<option>Male</option>';
                    sectionPreview += '<option>Female</option>';
                    sectionPreview += '<option>Other</option>';
                    sectionPreview += '<option>I dont want to share</option>';
                    sectionPreview += '</select>';
                    sectionPreview += '</div>';
                    sectionPreview += '</div>';

                    sectionFormPreview += '<div class="form-group">';
                    sectionFormPreview += '<label class="font-weight-bolder">Gender</label>';
                    sectionFormPreview += '<div class="dropdown">';
                    sectionFormPreview += '<select class="custom-select">';
                    sectionFormPreview += '<option selected>Select Option</option>';
                    sectionFormPreview += '<option>Male</option>';
                    sectionFormPreview += '<option>Female</option>';
                    sectionFormPreview += '<option>Other</option>';
                    sectionFormPreview += '<option>I dont want to share</option>';
                    sectionFormPreview += '</select>';
                    sectionFormPreview += '</div>';
                    sectionFormPreview += '</div>';
                }

                if ($("#clientSectionAddress").is(':checked')) {
                    sectionPreview += '<div class="form-group">';
                    sectionPreview += '<label class="font-weight-bolder">Address</label>';
                    sectionPreview += '<textarea rows="4" class="form-control"></textarea>';
                    sectionPreview += '</div>';

                    sectionFormPreview += '<div class="form-group">';
                    sectionFormPreview += '<label class="font-weight-bolder">Address</label>';
                    sectionFormPreview += '<textarea rows="4" class="form-control"></textarea>';
                    sectionFormPreview += '</div>';
                }

                sectionPreview += '</div>';
                sectionFormPreview += '</div>';

                if ($(".clientInforPreview").length == 0) {
                    sectionPreview += '</div>';
                    sectionFormPreview += '</div>';

                    $(".sectionTabDiv").append(sectionPreview);
                    $(".sectionTabDivForm").append(sectionFormPreview);
                } else {
                    $(".clientInforPreview").html(sectionPreview);
                    $(".clientInforForm").html(sectionFormPreview);
                }

                showPreviewTab(0);
                showFormsTab(0);

                var phone_number = window.intlTelInput(document.querySelector("#countryCodeSelection"), {
                    separateDialCode: true,
                    preferredCountries: ["ca"],
                    hiddenInput: "full",
                    utilsScript: $("#utilScript").val()
                });

                var phone_number = window.intlTelInput(document.querySelector(".countryCodeSelectionPreview"), {
                    separateDialCode: true,
                    preferredCountries: ["ca"],
                    hiddenInput: "full",
                    utilsScript: $("#utilScript").val()
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

    var _handleAddSubSectionForm = function() {
        var form = KTUtil.getById('addSubSectionForm');
        var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('addSubSectionBtn');

        if (!form) {
            return;
        }

        FormValidation
            .formValidation(
                form, {
                    fields: {
                        'subSectionTitle': {
                            validators: {
                                notEmpty: {
                                    message: 'Section title is required'
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
                var subSectionTitle = $("#subSectionTitle").val();
                var subSectionDescription = $("#subSectionDescription").val();
                var current_section = (parseInt($("#total_section").val())) + 1;
                $("#total_section").val(current_section);
                var total_section = $("#total_section").val();
                $(".TotalSection").html(total_section);

                $(".detailsFormBtn").attr('disabled', false);

                $(".defaultView").hide();
                $(".afterView").show();
                $("#addCustomSectionModal").modal('hide');

                var unique_id = makeid(15);
                var subSectionHtml = '';

                subSectionHtml += '<div class="card w-70 mx-auto mt-16 customFieldDivCount sectionIdentitiyClass' + current_section + '" id="' + unique_id + '">';
                subSectionHtml += '<div class="my-card-lable-orange">';
                subSectionHtml += 'Section <span class="CurrentSection">' + current_section + '</span> of <span class="TotalSection">' + total_section + '</span> : Custom section';
                subSectionHtml += '</div>';
                subSectionHtml += '<input type="hidden" name="section_id[]" value="' + current_section + '">';
                subSectionHtml += '<input type="hidden" name="form_type[]" value="custom_form">';
                subSectionHtml += '<div class="card-header d-flex justify-content-between">';
                subSectionHtml += '<div>';

                if (subSectionTitle != '') {
                    subSectionHtml += '<h3 class="sectionTitle">' + subSectionTitle + '</h3>';
                }

                if (subSectionDescription != '') {
                    subSectionHtml += '<p class="m-0 text-dakr-50 sectionDescription">' + subSectionDescription + '</p>';
                }

                subSectionHtml += '<input type="hidden" class="customSectionTitle" name="customSectionTitle[' + current_section + ']" value="' + subSectionTitle + '">';
                subSectionHtml += '<input type="hidden" class="customSectionDescription" name="customSectionDescription[' + current_section + ']" value="' + subSectionDescription + '">';

                subSectionHtml += '</div>';
                subSectionHtml += '<div class="dropdown dropdown-inline">';
                subSectionHtml += '<a href="#" class="btn btn-clean text-dark btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                subSectionHtml += '<i class="ki ki-bold-more-ver text-dark"></i>';
                subSectionHtml += '</a>';
                subSectionHtml += '<div class="dropdown-menu dropdown-menu-right text-center">';
                subSectionHtml += '<ul class="navi navi-hover">';
                subSectionHtml += '<li class="navi-item">';
                subSectionHtml += '<a href="javascript:;" class="navi-link editCustomSectionClass" data-uniqueid="' + unique_id + '">';
                subSectionHtml += '<span class="navi-text">Edit</span>';
                subSectionHtml += '</a>';
                subSectionHtml += '</li>';
                subSectionHtml += '<li class="navi-item">';
                subSectionHtml += '<a href="javascript:;" class="navi-link removeMe" data-deleteId="' + unique_id + '">';
                subSectionHtml += '<span class="navi-text text-danger">Delete</span>';
                subSectionHtml += '</a>';
                subSectionHtml += '</li>';
                subSectionHtml += '</ul>';
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';

                var subCustomFields = makeid(30);

                subSectionHtml += '<div class="inputFields">';

                subSectionHtml += '<div class="card-body questionFieldDivs" id="' + subCustomFields + '">';
                subSectionHtml += '<div class="customQuestionDiv hideCustomQuestionDiv" style="display:none;" data-subUniqueID="' + subCustomFields + '">';
                subSectionHtml += '</div>';
                subSectionHtml += '<div class="card showCustomQuestionDiv" data-subUniqueID="' + subCustomFields + '">';

                subSectionHtml += '<input type="hidden" class="subUniqueIDClass" name="subUniqueID[' + current_section + '][]" value="' + subCustomFields + '">';

                subSectionHtml += '<div class="card-body bg-content">';
                subSectionHtml += '<div class="d-flex justify-content-between">';
                subSectionHtml += '<div class="form-group mr-2 w-100">';
                subSectionHtml += '<label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label>';
                //subSectionHtml += '<div class="dropdown bootstrap-select form-control">';
                subSectionHtml += '<select class="form-control selectpickerelemtnt" tabindex="null" name="answer_type[' + current_section + '][]" onchange="getQuestionType(\'' + subCustomFields + '\',\'' + current_section + '\',this.value)">';
                subSectionHtml += '<option value="shortAnswer"><i class="fas fa-grip-lines"></i> Short answer</option>';
                subSectionHtml += '<option value="longAnswer"><i class="fas fa-align-left"></i> Long answer</option>';
                subSectionHtml += '<option value="singleAnswer"><i class="far fa-dot-circle"></i> Single answer</option>';
                subSectionHtml += '<option value="singleCheckbox"><i class="far fa-check-square"></i> Single checkbox</option>';
                subSectionHtml += '<option value="multipleCheckbox"><i class="fas fa-tasks"></i> Multiple choice</option>';
                subSectionHtml += '<option value="dropdown"><i class="far fa-caret-square-down"></i> Drop-down</option>';
                subSectionHtml += '<option value="yesOrNo"><i class="fas fa-adjust"></i> Yes or No</option>';
                subSectionHtml += '<option value="des"><i class="far fa-font-case"></i> Description text</option>';
                subSectionHtml += '</select>';
                //subSectionHtml += '</div>';
                subSectionHtml += '</div>';
                subSectionHtml += '<div class="form-group ml-2 w-100">';
                subSectionHtml += '<label class="font-weight-bolder" for="exampleSelect1">Question</label>';
                subSectionHtml += '<input type="text" class="form-control customQuestionField" name="question[' + current_section + '][]" placeholder="New question" data-isDescriptive="No">';
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';

                subSectionHtml += '<div class="optionAnwersDiv">';

                subSectionHtml += '</div>';

                subSectionHtml += '<hr>';
                subSectionHtml += '<div class="d-flex flex-wrap justify-content-between align-items-center">';
                subSectionHtml += '<div class="">';
                subSectionHtml += '<div class="form-group mb-0">';

                subSectionHtml += '<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">';
                subSectionHtml += '<label class="d-flex align-item-center font-weight-bolder">';
                subSectionHtml += '<input type="checkbox" class="isRequiredFieldCheck" checked="checked" name="is_required[' + current_section + '][]" data-subuniqueid="' + subCustomFields + '" data-uniqueid="' + unique_id + '" data-sectionId="' + current_section + '">';
                subSectionHtml += '<input type="hidden" class="isRequiredFieldValue" id="requiredValueField' + subCustomFields + '" name="is_required_value[' + current_section + '][]" value="1">';
                subSectionHtml += '<span></span>&nbsp;Required';
                subSectionHtml += '</label>';
                subSectionHtml += '</div>';

                subSectionHtml += '</div>';
                subSectionHtml += '</div>';
                subSectionHtml += '<div class="d-flex align-items-center">';
                subSectionHtml += '<span class="border-right p-3">';
                subSectionHtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToUpper">';
                subSectionHtml += '<i class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i>';
                subSectionHtml += '</button>';
                subSectionHtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToLower">';
                subSectionHtml += '<i class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i>';
                subSectionHtml += '</button>';
                subSectionHtml += '</span>';
                subSectionHtml += '<span class="border-right p-3">';
                subSectionHtml += '<button type="button" class="mx-2 btn btn-sm btn-white cloneSubCustomField" data-sectionId="' + current_section + '">';
                subSectionHtml += '<i class="mx-1 far fa-clone fa-2x"></i>';
                subSectionHtml += '</button>';
                subSectionHtml += '<button type="button" class="mx-2 btn btn-sm btn-white removeSubCustomField" data-subUniqueID="' + subCustomFields + '" data-sectionId="' + current_section + '" data-uniqueid="' + unique_id + '">';
                subSectionHtml += '<i class="mx-1 fas fa-trash fa-2x text-danger"></i>';
                subSectionHtml += '</button>';
                subSectionHtml += '</span>';
                subSectionHtml += '<span class="p-3">';
                subSectionHtml += '<button type="button" class="mx-2 btn btn-sm btn-white closeCompleteSection" data-subUniqueID="' + subCustomFields + '" data-sectionId="' + current_section + '">';
                subSectionHtml += '<i class="p-0 fa fa-times"></i>';
                subSectionHtml += '</button>';
                subSectionHtml += '<button type="button" class="mx-2 btn btn-sm btn-primary markCompleteSection" data-subUniqueID="' + subCustomFields + '" data-sectionId="' + current_section + '">';
                subSectionHtml += '<i class="p-0 fas fa-check"></i>';
                subSectionHtml += '</button>';
                subSectionHtml += '</span>';
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';

                subSectionHtml += '</div>';

                subSectionHtml += '<div class="card-footer">';
                subSectionHtml += '<span class="cursor-pointer text-blue addNewQuestionClick" data-toggle="modal" data-target="#addNewQuestionModal" data-uniqueid="' + unique_id + '" data-sectionId="' + current_section + '">';
                subSectionHtml += '<i class="text-blue fa fa-plus mr-3"></i>Add a new Question or item';
                subSectionHtml += '</span>';
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';

                $(".appendTheContentHere").append(subSectionHtml);

                //$('.selectpicker').selectpicker();
                $("#addSubSectionForm")[0].reset();

                var sectionPreview = '';
                sectionPreview += '<div class="preview-tab ' + unique_id + '" data-sectionTitle="' + subSectionTitle + '" data-sectionDescription="' + subSectionDescription + '">';
                sectionPreview += '<div class="card-body sectionPreviewTab' + current_section + '">';

                sectionPreview += '</div>';
                sectionPreview += '</div>';

                $(".sectionTabDiv").append(sectionPreview);
                showPreviewTab(0);

                var sectionForm = '';
                sectionForm += '<div class="forms-tab ' + unique_id + '" data-sectionTitle="' + subSectionTitle + '" data-sectionDescription="' + subSectionDescription + '">';
                sectionForm += '<div class="card-body sectionPreviewTab' + current_section + '">';

                sectionForm += '</div>';
                sectionForm += '</div>';

                $(".sectionTabDivForm").append(sectionForm);
                showFormsTab(0);
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

    var _handleEditSubSectionForm = function() {
            var form = KTUtil.getById('editSubSectionForm');
            var formSubmitUrl = KTUtil.attr(form, 'action');
            var formSubmitButton = KTUtil.getById('editSubSectionBtn');

            if (!form) {
                return;
            }

            FormValidation
                .formValidation(
                    form, {
                        fields: {
                            'eidtSubSectionTitle': {
                                validators: {
                                    notEmpty: {
                                        message: 'Section title is required'
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
                    var subSectionTitle = ($("#eidtSubSectionTitle").val()) ? $("#eidtSubSectionTitle").val() : '';
                    var subSectionDescription = ($("#editSubSectionDescription").val()) ? $("#editSubSectionDescription").val() : '';
                    var divUniqueId = $("#divUniqueId").val();

                    $("#" + divUniqueId + " .sectionTitle").text(subSectionTitle);
                    $("#" + divUniqueId + " .sectionDescription").text(subSectionDescription);

                    $("#" + divUniqueId + " .customSectionTitle").val(subSectionTitle);
                    $("#" + divUniqueId + " .customSectionDescription").val(subSectionDescription);

                    $("#editSubSectionForm")[0].reset();
                    $("#editCustomSectionModal").modal('hide');
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
            _handlesaveConsultationForm();
            _handleAddClientInformationForm();
            _handleAddSubSectionForm();
            _handleEditSubSectionForm();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTaddConsultationForm.init();

    var list = [];
    $("#treeview").hummingbird();
    $("#treeview").on("CheckUncheckDone", function() {
        var count = $('#treeview input[name="value_checkbox[]"]:checked').length;
        var allCount = $('#treeview input[type="checkbox"]:checked').length;
        var allCheck = $('#treeview input[type="checkbox"]').length;

        if (allCheck == allCount) {
            $("#serviceInput").val('All Service Selected')
        } else {
            $("#serviceInput").val(count + ' service Selected')
        }
    });
    // show count when load data
    var count = $('input[name="value_checkbox[]"]:checked').length;
    var allCount = $('input[type="checkbox"]:checked').length;
    var allCheck = $('input[type="checkbox"]').length;

    if (allCheck == allCount) {
        $("#serviceInput").val('All Service Selected')
    } else {
        $("#serviceInput").val(count + ' service Selected')
    }
});

var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
    // This function will display the specified tab of the form...
    var tab = document.getElementsByClassName("add-voucher-tab");
    tab[n].style.display = "block";

    if (n == (tab.length - 1)) {
        $(".next-step").text("Save");
        $(".previous").show();
        $(".steps").text("2");

        setTimeout(function() {
            $(".next-step").attr("type", "submit");
        }, 1000);

    } else {
        $(".previous").hide();
        $(".next-step").text("Next Step");
        $(".next-step").attr("type", "button");
    }

    if (n == 0) {
        $(".steps").text("1");
        $(".main-title").text("Add sections to your consultation form")
    } else if (n == 1) {
        $(".previous").show();
        $(".steps").text("2");
        $(".main-title").text("Add your consultation form details")
    }
}

function nextPrev(n) {

    if (n == 1 && currentTab == 1) {
        return false;
    }

    if (n == 1) {
        var returnresponse = false;
        var stat = 1;

        if ($(".clientFormIncluded").length == 0 && $(".customQuestionField").length == 0) {
            return false;
        }

        if ($(".customQuestionField").length > 0) {
            $(".customQuestionField").each(function() {
                if ($(this).val() == '' && stat == 1 && $(this).attr('data-isDescriptive') == 'No') {
                    stat = 0;
                }
            });
        }

        if (stat == 0) {

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
            toastr.error("Fields are required.");

            return false;
        }
    }

    // This function will figure out which tab to display
    var tab = document.getElementsByClassName("add-voucher-tab");
    // Hide the current tab:
    tab[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;
    // if you have reached the end of the tab
    // Otherwise, display the correct tab:
    showTab(currentTab);
}

var currentFormsTab = 0; // Current tab is set to be the first tab (0)
//showFormsTab(currentFormsTab); // Display the current tab

function showFormsTab(n) {
    // This function will display the specified tab of the form...
    var formsTab = document.getElementsByClassName("forms-tab");
    $(".total-forms-tab").text(formsTab.length);
    $(".forms-current-steps").text(n + 1);
    formsTab[n].style.display = "block";

    $(".showSectionTitleNameForm").text(formsTab[n].getAttribute('data-sectionTitle'));
    $(".showSectionDescriptionTextForm").text(formsTab[n].getAttribute('data-sectionDescription'));

    if ((currentFormsTab + 1) == formsTab.length) {
        $(".forms-previous").show();
        $(".forms-next").hide();
    } else {
        $(".forms-previous").show();
        $(".forms-next").show();
    }

    if (n == 0) {
        $(".forms-previous").hide();
    }
}

function nextPrevForms(n) {
    // This function will figure out which tab to display
    var formsTab = document.getElementsByClassName("forms-tab");
    // Hide the current tab:
    formsTab[currentFormsTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentFormsTab = currentFormsTab + n;
    // if you have reached the end of the tab
    // Otherwise, display the correct tab:
    showFormsTab(currentFormsTab);
}

var currentPreviewTab = 0; // Current tab is set to be the first tab (0)
//showPreviewTab(currentPreviewTab); // Display the current tab

function showPreviewTab(n) {
    // This function will display the specified tab of the form...
    var previewTab = document.getElementsByClassName("preview-tab");
    $(".total-preview-tab").text(previewTab.length);
    $(".preview-current-steps").text(n + 1);
    previewTab[n].style.display = "block";

    $(".showSectionTitleName").text(previewTab[n].getAttribute('data-sectionTitle'));
    $(".showSectionDescriptionText").text(previewTab[n].getAttribute('data-sectionDescription'));

    if ((currentPreviewTab + 1) == previewTab.length) {
        $(".preview-previous").show();
        $(".preview-next").hide();
    } else {
        $(".preview-previous").show();
        $(".preview-next").show();
    }

    if (n == 0) {
        $(".preview-previous").hide();
    }
}

function nextPrevPreview(n) {
    // This function will figure out which tab to display
    var previewTab = document.getElementsByClassName("preview-tab");
    // Hide the current tab:
    previewTab[currentPreviewTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentPreviewTab = currentPreviewTab + n;
    // if you have reached the end of the tab
    // Otherwise, display the correct tab:
    showPreviewTab(currentPreviewTab);
}

$(document).on('click', '.removeMe', function() {
    var deleteId = $(this).attr('data-deleteId');
    $("#" + deleteId).remove();
    $("." + deleteId).remove();

    $(".TotalSection").html($(".customFieldDivCount").length);
    $("#total_section").val($(".customFieldDivCount").length);

    $(".customFieldDivCount").each(function(index) {
        var indexval = parseInt(index) + 1;
        $(this).find('.CurrentSection').text(indexval);
    });

    if ($(".customFieldDivCount").length == 0) {
        $(".defaultView").show();
        $(".afterView").hide();
        $("#addSubSectionForm")[0].reset();
        $("#addClientSectionTitle").val('');
        $("#addClientSectionDescription").val('');
        $(".detailsFormBtn").attr('disabled', true);
    }

    if ($(".clientFormIncluded").length == 0) {
        $("#clientUniqueDivID").val('');
    }
});

$(document).on("click", '.selectCountryCodeClass .iti__country', function(e) {
    e.preventDefault();

    if ($(this).hasClass("iti__active")) {
        var country_code = $(this).attr("data-dial-code");
        $("#country_code").val(country_code);
    } else {
        var country_code = $(this).attr("data-dial-code");
        $("#country_code").val(country_code);
    }
});

$(document).on('click', '.checkClientSection', function() {
    $("#custom-section").attr('checked', true);
    if ($(".clientFormIncluded").length > 0) {
        $("#clientSectionRadio").attr('disabled', true);
    } else {
        $("#clientSectionRadio").attr('disabled', false);
    }
});

$(document).on('click', '#addCustomSectionBtn', function() {
    var inputValue = $("input[name=section]:checked").val();

    if (inputValue == 'client-section') {
        $("#addNewSectionModal").modal('hide');
        $("#addClientDetailModal").modal('show');
        $("#clientSectionRadio").attr('disabled', true);
        $("#custom-section").attr('checked', true);
    } else if (inputValue == 'custom-section') {
        $("#addNewSectionModal").modal('hide');
        $("#addCustomSectionModal").modal('show');
    }
});

$(document).on('click', '.editCustomSectionClass', function() {
    $("#editCustomSectionModal").modal('show');
    var uniqueid = $(this).attr('data-uniqueid');

    var sectionTitle = $("#" + uniqueid + " .customSectionTitle").val();
    var sectionDescription = $("#" + uniqueid + " .customSectionDescription").val();

    $("#divUniqueId").val(uniqueid);
    $("#eidtSubSectionTitle").val(sectionTitle);
    $("#editSubSectionDescription").val(sectionDescription);
});

function getQuestionType(uniqueid, section_id, selectedValue) {

    $("#" + uniqueid + " .optionAnwersDiv").html('');
    $("#" + uniqueid + " .customQuestionField").attr('readonly', false);
    $("#" + uniqueid + " .customQuestionField").removeAttr('data-isDescriptive');
    $("#" + uniqueid + " .customQuestionField").attr('data-isDescriptive', 'No');

    if (selectedValue == 'shortAnswer') {

        var question = $("#" + uniqueid + " .customQuestionField").val();

        var customField = '';
        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">';
        customField += question;
        customField += '</label>';
        customField += '<input type="text" class="form-control">';
        customField += '</div>';

        $("#" + uniqueid + " .customQuestionDiv").html(customField);

    } else if (selectedValue == 'longAnswer') {

        var question = $("#" + uniqueid + " .customQuestionField").val();

        var customField = '';
        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">';
        customField += question;
        customField += '</label>';
        customField += '<textarea class="form-control"></textarea>';
        customField += '</div>';

        $("#" + uniqueid + " .customQuestionDiv").html(customField);

    } else if (selectedValue == 'singleAnswer') {

        var answersHtml = '';

        answersHtml += '<div class="appendNewAnswersDiv">';
        answersHtml += '<div class="d-flex justify-content-between">';
        answersHtml += '<div class="form-group ml-2 w-100">';
        answersHtml += '<label class="font-weight-bolder">Answer 1</label>';
        answersHtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + section_id + '][' + uniqueid + '][]">';
        answersHtml += '</div>';
        answersHtml += '</div>';
        answersHtml += '<div class="d-flex justify-content-between">';
        answersHtml += '<div class="form-group ml-2 w-100">';
        answersHtml += '<label class="font-weight-bolder">Answer 2</label>';
        answersHtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + section_id + '][' + uniqueid + '][]">';
        answersHtml += '</div>';
        answersHtml += '</div>';
        answersHtml += '</div>';

        answersHtml += '<div>';
        answersHtml += '<span class="cursor-pointer text-blue addNewQuestionAnswers" data-subUniqueID="' + uniqueid + '" data-sectionId="' + section_id + '"><i class="text-blue fa fa-plus mr-3"></i>Add next answer</span>';
        answersHtml += '</div>';

        $("#" + uniqueid + " .optionAnwersDiv").html(answersHtml);

    } else if (selectedValue == 'singleCheckbox') {

        var question = $("#" + uniqueid + " .customQuestionField").val();

        var customField = '';

        customField += '<div class="form-group">';
        customField += '<div class="form-group">';
        customField += '<div class="checkbox-list">';
        customField += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + uniqueid + '"><span></span> <p class="customQuestionLabel">' + question + '</p></label>';
        customField += '</div>';
        customField += '</div>';
        customField += '</div>';

        $("#" + uniqueid + " .customQuestionDiv").html(customField);

    } else if (selectedValue == 'multipleCheckbox') {

        var answersHtml = '';

        answersHtml += '<div class="appendNewAnswersDiv">';
        answersHtml += '<div class="d-flex justify-content-between">';
        answersHtml += '<div class="form-group ml-2 w-100">';
        answersHtml += '<label class="font-weight-bolder">Answer 1</label>';
        answersHtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + section_id + '][' + uniqueid + '][]">';
        answersHtml += '</div>';
        answersHtml += '</div>';
        answersHtml += '<div class="d-flex justify-content-between">';
        answersHtml += '<div class="form-group ml-2 w-100">';
        answersHtml += '<label class="font-weight-bolder">Answer 2</label>';
        answersHtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + section_id + '][' + uniqueid + '][]">';
        answersHtml += '</div>';
        answersHtml += '</div>';
        answersHtml += '</div>';

        answersHtml += '<div>';
        answersHtml += '<span class="cursor-pointer text-blue addNewQuestionAnswers" data-subUniqueID="' + uniqueid + '" data-sectionId="' + section_id + '"><i class="text-blue fa fa-plus mr-3"></i>Add next answer</span>';
        answersHtml += '</div>';

        $("#" + uniqueid + " .optionAnwersDiv").html(answersHtml);

    } else if (selectedValue == 'dropdown') {

        var answersHtml = '';

        answersHtml += '<div class="appendNewAnswersDiv">';
        answersHtml += '<div class="d-flex justify-content-between">';
        answersHtml += '<div class="form-group ml-2 w-100">';
        answersHtml += '<label class="font-weight-bolder">Answer 1</label>';
        answersHtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + section_id + '][' + uniqueid + '][]">';
        answersHtml += '</div>';
        answersHtml += '</div>';
        answersHtml += '<div class="d-flex justify-content-between">';
        answersHtml += '<div class="form-group ml-2 w-100">';
        answersHtml += '<label class="font-weight-bolder">Answer 2</label>';
        answersHtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + section_id + '][' + uniqueid + '][]">';
        answersHtml += '</div>';
        answersHtml += '</div>';
        answersHtml += '</div>';

        answersHtml += '<div>';
        answersHtml += '<span class="cursor-pointer text-blue addNewQuestionAnswers" data-subUniqueID="' + uniqueid + '" data-sectionId="' + section_id + '><i class="text-blue fa fa-plus mr-3"></i>Add next answer</span>';
        answersHtml += '</div>';

        $("#" + uniqueid + " .optionAnwersDiv").html(answersHtml);

    } else if (selectedValue == 'yesOrNo') {

        var question = $("#" + uniqueid + " .customQuestionField").val();

        var customField = '';
        customField += '<div class="form-group">';
        customField += '<div class="form-group ml-2 w-100 d-flex extra-time">';
        customField += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>	';
        customField += '<br>';
        customField += '<label class="option m-3">';
        customField += '<span class="option-control">';
        customField += '<span class="radio">';
        customField += '<input type="radio" name="yesNoQue' + uniqueid + '" value="Yes" checked="checked">';
        customField += '<span></span>';
        customField += '</span>';
        customField += '</span>';
        customField += '<span class="option-label">';
        customField += '<span class="option-head">';
        customField += '<span class="option-title">Yes</span>';
        customField += '</span>';
        customField += '</span>';
        customField += '</label>';
        customField += '<label class="option m-3">';
        customField += '<span class="option-control">';
        customField += '<span class="radio">';
        customField += '<input type="radio" name="yesNoQue' + uniqueid + '" value="No">';
        customField += '<span></span>';
        customField += '</span>';
        customField += '</span>';
        customField += '<span class="option-label">';
        customField += '<span class="option-head">';
        customField += '<span class="option-title">';
        customField += 'No';
        customField += '</span>';
        customField += '</span>';
        customField += '</label>';
        customField += '</div>';
        customField += '</div>';

        $("#" + uniqueid + " .customQuestionDiv").html(customField);

    } else if (selectedValue == 'des') {

        var answersHtml = '';

        answersHtml += '<div class="appendNewAnswersDiv">';
        answersHtml += '<div class="d-flex justify-content-between">';
        answersHtml += '<div class="form-group ml-2 w-100">';
        answersHtml += '<label class="font-weight-bolder">Text description</label>';
        answersHtml += '<textarea type="text" class="form-control questionAnswers" name="answerField[' + section_id + '][' + uniqueid + '][]" rows="5"></textarea>';
        answersHtml += '</div>';
        answersHtml += '</div>';
        answersHtml += '</div>';

        $("#" + uniqueid + " .optionAnwersDiv").html(answersHtml);
        $("#" + uniqueid + " .customQuestionField").val('');
        $("#" + uniqueid + " .customQuestionField").attr('readonly', true);

        $("#" + uniqueid + " .customQuestionField").removeAttr('data-isDescriptive');
        $("#" + uniqueid + " .customQuestionField").attr('data-isDescriptive', 'Yes');
    }
}

$(document).on('click', '.markCompleteSection', function() {
    var subUniqueID = $(this).attr('data-subUniqueID');
    var sectionId = $(this).attr('data-sectionid');

    var getQuestion = $("#" + subUniqueID + " .customQuestionField").val();
    var questionType = $("#" + subUniqueID + " .selectpickerelemtnt").val();

    if (getQuestion == '' && questionType != 'des') {
        $("#" + subUniqueID + " .customQuestionField").focus()
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
        toastr.error("Question field is required.");
        return false;
    }

    var questionAnswers = $("#" + subUniqueID + " .questionAnswers").val();

    if (questionType == 'des' && questionAnswers == '') {
        $("#" + subUniqueID + " .questionAnswers").focus()
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
        toastr.error("Description field is required.");
        return false;
    }

    if (questionType == 'shortAnswer') {

        var question = $("#" + subUniqueID + " .customQuestionField").val();

        var customField = '';
        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">';
        customField += question;
        customField += '</label>';
        customField += '<input type="text" class="form-control">';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

        var previewHtml = '';
        if ($(".previewSection" + subUniqueID).length > 0) {

            previewHtml += '<label class="font-weight-bolder customQuestionLabel">';
            previewHtml += question;
            previewHtml += '</label>';
            previewHtml += '<input type="text" class="form-control">';

            $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).html(previewHtml);
        } else {

            previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
            previewHtml += '<label class="font-weight-bolder customQuestionLabel">';
            previewHtml += question;
            previewHtml += '</label>';
            previewHtml += '<input type="text" class="form-control">';
            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId).append(previewHtml);
        }

    } else if (questionType == 'longAnswer') {

        var question = $("#" + subUniqueID + " .customQuestionField").val();

        var customField = '';
        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">';
        customField += question;
        customField += '</label>';
        customField += '<textarea class="form-control"></textarea>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

        var previewHtml = '';
        if ($(".previewSection" + subUniqueID).length > 0) {

            previewHtml += '<label class="font-weight-bolder customQuestionLabel">';
            previewHtml += question;
            previewHtml += '</label>';
            previewHtml += '<textarea class="form-control"></textarea>';

            $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).html(previewHtml);
        } else {

            previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
            previewHtml += '<label class="font-weight-bolder customQuestionLabel">';
            previewHtml += question;
            previewHtml += '</label>';
            previewHtml += '<textarea class="form-control"></textarea>';
            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId).append(previewHtml);
        }

    } else if (questionType == 'singleAnswer') {

        var question = $("#" + subUniqueID + " .customQuestionField").val();
        var answerstatus = 1;

        $("#" + subUniqueID + " .questionAnswers").each(function(index) {
            if ($(this).val() == '') {
                answerstatus = 0;
            }
        });

        if (answerstatus == 0) {
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
            toastr.error("Answer field is required.");
            return false;
        }

        var customField = '';

        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
        customField += '<div class="radio-list">';

        $("#" + subUniqueID + " .questionAnswers").each(function(index) {
            var ans = $(this).val();

            customField += '<label class="radio">';
            customField += '<input type="radio" name="radios' + subUniqueID + '">';
            customField += '<span></span>' + ans;
            customField += '</label>';
        });

        customField += '</div>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

        var previewHtml = '';
        if ($(".previewSection" + subUniqueID).length > 0) {

            previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
            previewHtml += '<div class="radio-list">';

            $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                var ans = $(this).val();

                previewHtml += '<label class="radio">';
                previewHtml += '<input type="radio" name="radios' + subUniqueID + '">';
                previewHtml += '<span></span>' + ans;
                previewHtml += '</label>';
            });

            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).html(previewHtml);
        } else {

            previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
            previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
            previewHtml += '<div class="radio-list">';

            $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                var ans = $(this).val();

                previewHtml += '<label class="radio">';
                previewHtml += '<input type="radio" name="radios' + subUniqueID + '">';
                previewHtml += '<span></span>' + ans;
                previewHtml += '</label>';
            });

            previewHtml += '</div>';
            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId).append(previewHtml);
        }

    } else if (questionType == 'singleCheckbox') {

        var question = $("#" + subUniqueID + " .customQuestionField").val();

        var customField = '';

        customField += '<div class="form-group">';
        customField += '<div class="form-group">';
        customField += '<div class="checkbox-list">';
        customField += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span> <p class="customQuestionLabel">' + question + '</p></label>';
        customField += '</div>';
        customField += '</div>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

        var previewHtml = '';
        if ($(".previewSection" + subUniqueID).length > 0) {

            previewHtml += '<div class="form-group">';
            previewHtml += '<div class="checkbox-list">';
            previewHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span> <p class="customQuestionLabel">' + question + '</p></label>';
            previewHtml += '</div>';
            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).html(previewHtml);
        } else {

            previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + ' " id="previewSubSectionnext' + subUniqueID + '">';
            previewHtml += '<div class="form-group">';
            previewHtml += '<div class="checkbox-list">';
            previewHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span> <p class="customQuestionLabel">' + question + '</p></label>';
            previewHtml += '</div>';
            previewHtml += '</div>';
            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId).append(previewHtml);
        }

    } else if (questionType == 'multipleCheckbox') {

        var question = $("#" + subUniqueID + " .customQuestionField").val();
        var answerstatus = 1;

        $("#" + subUniqueID + " .questionAnswers").each(function(index) {
            if ($(this).val() == '') {
                answerstatus = 0;
            }
        });

        if (answerstatus == 0) {
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
            toastr.error("Answer field is required.");
            return false;
        }

        var customField = '';

        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
        customField += '<div class="radio-list">';

        $("#" + subUniqueID + " .questionAnswers").each(function(index) {
            var ans = $(this).val();

            customField += '<div class="checkbox-list">';
            customField += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span>' + ans + '</label>';
            customField += '</div>';
        });

        customField += '</div>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

        var previewHtml = '';
        if ($(".previewSection" + subUniqueID).length > 0) {

            previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
            previewHtml += '<div class="radio-list">';

            $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                var ans = $(this).val();

                previewHtml += '<div class="checkbox-list">';
                previewHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span>' + ans + '</label>';
                previewHtml += '</div>';
            });

            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).html(previewHtml);
        } else {

            previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
            previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
            previewHtml += '<div class="radio-list">';

            $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                var ans = $(this).val();

                previewHtml += '<div class="checkbox-list">';
                previewHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span>' + ans + '</label>';
                previewHtml += '</div>';
            });

            previewHtml += '</div>';
            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId).append(previewHtml);
        }

    } else if (questionType == 'dropdown') {

        var question = $("#" + subUniqueID + " .customQuestionField").val();
        var answerstatus = 1;

        $("#" + subUniqueID + " .questionAnswers").each(function(index) {
            if ($(this).val() == '') {
                answerstatus = 0;
            }
        });

        if (answerstatus == 0) {
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
            toastr.error("Answer field is required.");
            return false;
        }

        var customField = '';

        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
        customField += '<div class="dropdown">';
        customField += '<select class="custom-select">';
        customField += '<option value="">Please select</option>';
        $("#" + subUniqueID + " .questionAnswers").each(function(index) {
            var ans = $(this).val();
            customField += '<option value="' + ans + '">' + ans + '</option>';
        });

        customField += '</select>';
        customField += '</div>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

        var previewHtml = '';
        if ($(".previewSection" + subUniqueID).length > 0) {

            previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
            previewHtml += '<div class="dropdown">';
            previewHtml += '<select class="custom-select">';
            previewHtml += '<option value="">Please select</option>';
            $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                var ans = $(this).val();
                previewHtml += '<option value="' + ans + '">' + ans + '</option>';
            });

            previewHtml += '</select>';
            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).html(previewHtml);
        } else {

            previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
            previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
            previewHtml += '<div class="dropdown">';
            previewHtml += '<select class="custom-select">';
            previewHtml += '<option value="">Please select</option>';
            $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                var ans = $(this).val();
                previewHtml += '<option value="' + ans + '">' + ans + '</option>';
            });

            previewHtml += '</select>';
            previewHtml += '</div>';
            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId).append(previewHtml);
        }

    } else if (questionType == 'yesOrNo') {

        var question = $("#" + subUniqueID + " .customQuestionField").val();

        var customField = '';
        customField += '<div class="form-group">';
        customField += '<div class="form-group ml-2 w-100 d-flex extra-time">';
        customField += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>	';
        customField += '<br>';
        customField += '<label class="option m-3">';
        customField += '<span class="option-control">';
        customField += '<span class="radio">';
        customField += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="Yes" checked="checked">';
        customField += '<span></span>';
        customField += '</span>';
        customField += '</span>';
        customField += '<span class="option-label">';
        customField += '<span class="option-head">';
        customField += '<span class="option-title">Yes</span>';
        customField += '</span>';
        customField += '</span>';
        customField += '</label>';
        customField += '<label class="option m-3">';
        customField += '<span class="option-control">';
        customField += '<span class="radio">';
        customField += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="No">';
        customField += '<span></span>';
        customField += '</span>';
        customField += '</span>';
        customField += '<span class="option-label">';
        customField += '<span class="option-head">';
        customField += '<span class="option-title">';
        customField += 'No';
        customField += '</span>';
        customField += '</span>';
        customField += '</label>';
        customField += '</div>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

        var previewHtml = '';
        if ($(".previewSection" + subUniqueID).length > 0) {

            previewHtml += '<div class="form-group ml-2 w-100 d-flex extra-time">';
            previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>	';
            previewHtml += '<br>';
            previewHtml += '<label class="option m-3">';
            previewHtml += '<span class="option-control">';
            previewHtml += '<span class="radio">';
            previewHtml += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="Yes" checked="checked">';
            previewHtml += '<span></span>';
            previewHtml += '</span>';
            previewHtml += '</span>';
            previewHtml += '<span class="option-label">';
            previewHtml += '<span class="option-head">';
            previewHtml += '<span class="option-title">Yes</span>';
            previewHtml += '</span>';
            previewHtml += '</span>';
            previewHtml += '</label>';
            previewHtml += '<label class="option m-3">';
            previewHtml += '<span class="option-control">';
            previewHtml += '<span class="radio">';
            previewHtml += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="No">';
            previewHtml += '<span></span>';
            previewHtml += '</span>';
            previewHtml += '</span>';
            previewHtml += '<span class="option-label">';
            previewHtml += '<span class="option-head">';
            previewHtml += '<span class="option-title">';
            previewHtml += 'No';
            previewHtml += '</span>';
            previewHtml += '</span>';
            previewHtml += '</label>';
            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).html(previewHtml);
        } else {

            previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
            previewHtml += '<div class="form-group ml-2 w-100 d-flex extra-time">';
            previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>	';
            previewHtml += '<br>';
            previewHtml += '<label class="option m-3">';
            previewHtml += '<span class="option-control">';
            previewHtml += '<span class="radio">';
            previewHtml += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="Yes" checked="checked">';
            previewHtml += '<span></span>';
            previewHtml += '</span>';
            previewHtml += '</span>';
            previewHtml += '<span class="option-label">';
            previewHtml += '<span class="option-head">';
            previewHtml += '<span class="option-title">Yes</span>';
            previewHtml += '</span>';
            previewHtml += '</span>';
            previewHtml += '</label>';
            previewHtml += '<label class="option m-3">';
            previewHtml += '<span class="option-control">';
            previewHtml += '<span class="radio">';
            previewHtml += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="No">';
            previewHtml += '<span></span>';
            previewHtml += '</span>';
            previewHtml += '</span>';
            previewHtml += '<span class="option-label">';
            previewHtml += '<span class="option-head">';
            previewHtml += '<span class="option-title">';
            previewHtml += 'No';
            previewHtml += '</span>';
            previewHtml += '</span>';
            previewHtml += '</label>';
            previewHtml += '</div>';
            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId).append(previewHtml);
        }

    } else if (questionType == 'des') {
        var question = $("#" + subUniqueID + " .customQuestionField").val();
        var questionAnswers = $("#" + subUniqueID + " .questionAnswers").val();

        var customField = '';
        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder">Text description';
        customField += '</label>';
        customField += '<textarea type="text" class="form-control questionAnswers" name="answerField[' + subUniqueID + '][]" rows="5">' + questionAnswers + '</textarea>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

        var previewHtml = '';
        if ($(".previewSection" + subUniqueID).length > 0) {

            previewHtml += '<label class="font-weight-bolder">Text description';
            previewHtml += '</label>';
            previewHtml += '<textarea type="text" class="form-control questionAnswers" name="answerField[' + subUniqueID + '][]" rows="5">' + questionAnswers + '</textarea>';

            $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).html(previewHtml);
        } else {

            previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
            previewHtml += '<label class="font-weight-bolder">Text description';
            previewHtml += '</label>';
            previewHtml += '<textarea type="text" class="form-control questionAnswers" name="answerField[' + subUniqueID + '][]" rows="5">' + questionAnswers + '</textarea>';

            previewHtml += '</div>';

            $(".sectionPreviewTab" + sectionId).append(previewHtml);
        }
    }

    $("#" + subUniqueID + " .customQuestionLabel").text(getQuestion);

    $("#" + subUniqueID + " .hideCustomQuestionDiv").show();
    $("#" + subUniqueID + " .showCustomQuestionDiv").hide();
});

$(document).on('click', '.hideCustomQuestionDiv', function() {
    var subUniqueID = $(this).attr('data-subUniqueID');

    $("#" + subUniqueID + " .hideCustomQuestionDiv").hide();
    $("#" + subUniqueID + " .showCustomQuestionDiv").show();
});

$(document).on('click', '.closeCompleteSection', function() {
    var subUniqueID = $(this).attr('data-subUniqueID');

    var oldQuestion = $("#" + subUniqueID + " .customQuestionLabel").text();
    var currentQuestion = $("#" + subUniqueID + " .customQuestionField").val();
    var questionType = $("#" + subUniqueID + " .selectpickerelemtnt").val();

    if (currentQuestion == '' && questionType != 'des') {
        return false;
    }

    if (questionType == 'shortAnswer') {

        var question = (oldQuestion != '' && typeof oldQuestion != "undefined") ? oldQuestion : currentQuestion;

        var customField = '';
        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">';
        customField += question;
        customField += '</label>';
        customField += '<input type="text" class="form-control">';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);
    } else if (questionType == 'longAnswer') {

        var question = (oldQuestion != '' && typeof oldQuestion != "undefined") ? oldQuestion : currentQuestion;

        var customField = '';
        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">';
        customField += question;
        customField += '</label>';
        customField += '<textarea class="form-control"></textarea>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

    } else if (questionType == 'singleAnswer') {

        var question = (oldQuestion != '' && typeof oldQuestion != "undefined") ? oldQuestion : currentQuestion;
        var answerstatus = 1;

        $("#" + subUniqueID + " .questionAnswers").each(function(index) {
            if ($(this).val() == '') {
                answerstatus = 0;
            }
        });

        if (answerstatus == 0) {
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
            toastr.error("Answer field is required.");
            return false;
        }

        var customField = '';

        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
        customField += '<div class="radio-list">';

        $("#" + subUniqueID + " .questionAnswers").each(function(index) {
            var ans = $(this).val();

            customField += '<label class="radio">';
            customField += '<input type="radio" name="radios' + subUniqueID + '">';
            customField += '<span></span>' + ans;
            customField += '</label>';
        });

        customField += '</div>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

    } else if (questionType == 'singleCheckbox') {

        var question = (oldQuestion != '' && typeof oldQuestion != "undefined") ? oldQuestion : currentQuestion;

        var customField = '';

        customField += '<div class="form-group">';
        customField += '<div class="form-group">';
        customField += '<div class="checkbox-list">';
        customField += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span> <p class="customQuestionLabel">' + question + '</p></label>';
        customField += '</div>';
        customField += '</div>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

    } else if (questionType == 'multipleCheckbox') {

        var question = (oldQuestion != '' && typeof oldQuestion != "undefined") ? oldQuestion : currentQuestion;
        var answerstatus = 1;

        $("#" + subUniqueID + " .questionAnswers").each(function(index) {
            if ($(this).val() == '') {
                answerstatus = 0;
            }
        });

        if (answerstatus == 0) {
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
            toastr.error("Answer field is required.");
            return false;
        }

        var customField = '';

        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
        customField += '<div class="radio-list">';

        $("#" + subUniqueID + " .questionAnswers").each(function(index) {
            var ans = $(this).val();

            customField += '<div class="checkbox-list">';
            customField += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span>' + ans + '</label>';
            customField += '</div>';
        });

        customField += '</div>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

    } else if (questionType == 'dropdown') {

        var question = (oldQuestion != '' && typeof oldQuestion != "undefined") ? oldQuestion : currentQuestion;
        var answerstatus = 1;

        $("#" + subUniqueID + " .questionAnswers").each(function(index) {
            if ($(this).val() == '') {
                answerstatus = 0;
            }
        });

        if (answerstatus == 0) {
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
            toastr.error("Answer field is required.");
            return false;
        }

        var customField = '';

        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
        customField += '<div class="dropdown">';
        customField += '<select class="custom-select">';
        customField += '<option value="">Please select</option>';
        $("#" + subUniqueID + " .questionAnswers").each(function(index) {
            var ans = $(this).val();
            customField += '<option value="' + ans + '">' + ans + '</option>';
        });

        customField += '</select>';
        customField += '</div>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

    } else if (questionType == 'yesOrNo') {

        var question = (oldQuestion != '' && typeof oldQuestion != "undefined") ? oldQuestion : currentQuestion;

        var customField = '';
        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
        customField += '<div class="form-group ml-2 w-100 d-flex extra-time">';
        customField += '<label class="option m-3">';
        customField += '<span class="option-control">';
        customField += '<span class="radio">';
        customField += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="Yes" checked="checked">';
        customField += '<span></span>';
        customField += '</span>';
        customField += '</span>';
        customField += '<span class="option-label">';
        customField += '<span class="option-head">';
        customField += '<span class="option-title">Yes</span>';
        customField += '</span>';
        customField += '</span>';
        customField += '</label>';
        customField += '<label class="option m-3">';
        customField += '<span class="option-control">';
        customField += '<span class="radio">';
        customField += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="No">';
        customField += '<span></span>';
        customField += '</span>';
        customField += '</span>';
        customField += '<span class="option-label">';
        customField += '<span class="option-head">';
        customField += '<span class="option-title">';
        customField += 'No';
        customField += '</span>';
        customField += '</span>';
        customField += '</label>';
        customField += '</div>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);

    } else if (questionType == 'des') {

        var question = (oldQuestion != '' && typeof oldQuestion != "undefined") ? oldQuestion : currentQuestion;
        var questionAnswers = $("#" + subUniqueID + " .questionAnswers").val();

        var customField = '';
        customField += '<div class="form-group">';
        customField += '<label class="font-weight-bolder">';
        customField += questionAnswers;
        customField += '</label>';
        customField += '<textarea type="text" class="form-control questionAnswers" name="answerField[' + subUniqueID + '][]" rows="5"></textarea>';
        customField += '</div>';

        $("#" + subUniqueID + " .customQuestionDiv").html(customField);
    }

    $("#" + subUniqueID + " .customQuestionField").val((oldQuestion != '' && typeof oldQuestion != "undefined") ? oldQuestion : currentQuestion);
    $("#" + subUniqueID + " .hideCustomQuestionDiv").show();
    $("#" + subUniqueID + " .showCustomQuestionDiv").hide();
});

$(document).on('click', '.removeSubCustomField', function() {
    var uniqueid = $(this).attr('data-uniqueid');
    var subUniqueID = $(this).attr('data-subUniqueID');

    if ($("#" + uniqueid + " .questionFieldDivs").length == 1) {
        return false;
    } else {
        $("#" + subUniqueID).remove();
        $("#previewSubSection" + subUniqueID).remove();
        $(".previewSubSectionnext" + subUniqueID).remove();
    }
});

$(document).on('click', '.chageToUpper', function() {
    var currentDiv = $(this).closest('.questionFieldDivs').attr('id');
    var previousDiv = $(this).closest('.questionFieldDivs').prev('.questionFieldDivs').attr('id');

    if (typeof previousDiv === "undefined") {} else {
        var div1 = $('#' + currentDiv);
        var div2 = $('#' + previousDiv);

        var div1SelectVal = $(div1).find('select').val();
        var div2SelectVal = $(div2).find('select').val();

        var tdiv1 = div1.clone(true);
        var tdiv2 = div2.clone(true);

        if (!div2.is(':empty')) {
            div1.replaceWith(tdiv2);
            div2.replaceWith(tdiv1);

            // tdiv2.find('.bootstrap-select').replaceWith(function() { return $('select', this); });
            // tdiv2.find('select').selectpicker();

            // tdiv1.find('.bootstrap-select').replaceWith(function() { return $('select', this); });
            // tdiv1.find('select').selectpicker();

            tdiv2.find('select').val(div2SelectVal);
            tdiv1.find('select').val(div1SelectVal);
        }

        var div1preview = $('#previewSubSection' + currentDiv);
        var div2preview = $('.previewSubSectionnext' + previousDiv);

        var tdiv1preview = div1preview.clone(true);
        var tdiv2preview = div2preview.clone(true);

        if (!div2preview.is(':empty')) {
            div1preview.replaceWith(tdiv2preview);
            div2preview.replaceWith(tdiv1preview);
        }
    }
});

$(document).on('click', '.chageToLower', function() {
    var currentDiv = $(this).closest('.questionFieldDivs').attr('id');
    var nextDiv = $(this).closest('.questionFieldDivs').next('.questionFieldDivs').attr('id');

    if (typeof nextDiv === "undefined") {} else {
        var div1 = $('#' + currentDiv);
        var div2 = $('#' + nextDiv);

        var div1SelectVal = $(div1).find('select').val();
        var div2SelectVal = $(div2).find('select').val();

        var tdiv1 = div1.clone(true);
        var tdiv2 = div2.clone(true);

        if (!div2.is(':empty')) {
            div1.replaceWith(tdiv2);
            div2.replaceWith(tdiv1);

            // tdiv2.find('.bootstrap-select').replaceWith(function() { return $('select', this); });
            // tdiv2.find('select').selectpicker();

            // tdiv1.find('.bootstrap-select').replaceWith(function() { return $('select', this); });
            // tdiv1.find('select').selectpicker();

            tdiv2.find('select').val(div2SelectVal);
            tdiv1.find('select').val(div1SelectVal);
        }

        var div1preview = $('#previewSubSection' + currentDiv);
        var div2preview = $('.previewSubSectionnext' + nextDiv);

        var tdiv1preview = div1preview.clone(true);
        var tdiv2preview = div2preview.clone(true);

        if (!div2preview.is(':empty')) {
            div1preview.replaceWith(tdiv2preview);
            div2preview.replaceWith(tdiv1preview);
        }
    }
});

$(document).on('click', '.cloneSubCustomField', function() {
    var clonedata = $(this).closest('.questionFieldDivs').clone(true);
    var SelectVal = $(this).closest('.questionFieldDivs').find('.selectpickerelemtnt').val();

    var sectionid = $(this).attr('data-sectionId');

    //clonedata.find('.bootstrap-select').replaceWith(function() { return $('select', this); });
    $(this).parents('.inputFields').append(clonedata);

    //$(this).parents('.inputFields').find('.questionFieldDivs:last').find('.select').selectpicker();

    var subCustomFields = makeid(30);
    $(this).parents('.inputFields').find('.questionFieldDivs:last').attr('id', subCustomFields);
    $(this).parents('.inputFields').find('.questionFieldDivs:last').find('.hideCustomQuestionDiv').attr('data-subuniqueid', subCustomFields);
    $(this).parents('.inputFields').find('.questionFieldDivs:last').find('.showCustomQuestionDiv').attr('data-subuniqueid', subCustomFields);

    $(this).parents('.inputFields').find('.questionFieldDivs:last').find('.subUniqueIDClass').val(subCustomFields);

    $(this).parents('.inputFields').find('.questionFieldDivs:last').find('.selectpickerelemtnt').val(SelectVal);
    $(this).parents('.inputFields').find('.questionFieldDivs:last').find('.selectpickerelemtnt').attr('onchange', 'getQuestionType(\'' + subCustomFields + '\',\'' + sectionid + '\',this.value)');
    $(this).parents('.inputFields').find('.questionFieldDivs:last').find('.removeSubCustomField').attr('data-subuniqueid', subCustomFields);
    $(this).parents('.inputFields').find('.questionFieldDivs:last').find('.closeCompleteSection').attr('data-subuniqueid', subCustomFields);
    $(this).parents('.inputFields').find('.questionFieldDivs:last').find('.markCompleteSection').attr('data-subuniqueid', subCustomFields);
    $(this).parents('.inputFields').find('.questionFieldDivs:last').find('.addNewQuestionAnswers').attr('data-subuniqueid', subCustomFields);
    $(this).parents('.inputFields').find('.questionFieldDivs:last').find('.isRequiredFieldCheck').attr('data-subuniqueid', subCustomFields);
    $(this).parents('.inputFields').find('.questionFieldDivs:last').find('.isRequiredFieldValue').attr('id', 'requiredValueField' + subCustomFields);
    $(this).parents('.inputFields').find('.questionFieldDivs:last').find('.questionAnswers').attr('name', 'answerField[' + sectionid + '][' + subCustomFields + '][]');


    $($(this).parents('.inputFields').find('.questionFieldDivs:last').find('.customAnswerDivID')).each(function(index) {
        var subAnswerUniqueId = makeid(30);
        $(this).attr('id', subAnswerUniqueId);
        $(this).find('.removeAnswerMine').attr('data-subAnswerUniqueId', subAnswerUniqueId);
    });
});

$(document).on('click', '.addNewQuestionAnswers', function() {
    var subUniqueID = $(this).attr('data-subUniqueID');
    var sectionId = $(this).attr('data-sectionId');

    var answerLength = parseInt($("#" + subUniqueID + " .questionAnswers").length) + 1;

    var subAnswerUniqueId = makeid(30);

    var answerHtml = '';
    answerHtml += '<div class="d-flex justify-content-between customAnswerDivID" id="' + subAnswerUniqueId + '">';
    answerHtml += '<div class="form-group ml-2 w-100">';
    answerHtml += '<label class="font-weight-bolder">Answer ' + answerLength + '</label>';
    answerHtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + sectionId + '][' + subUniqueID + '][]">';
    answerHtml += '</div>';
    answerHtml += '<div class="form-group mb-0 ml-2 removeAnswerMine" data-subAnswerUniqueId="' + subAnswerUniqueId + '">';
    answerHtml += '<label>&nbsp;</label>';
    answerHtml += '<button class="btn btn-sm btn-white" type="button"><i class="p-0 fa fa-trash text-danger"></i></button>';
    answerHtml += '</div>';
    answerHtml += '</div>';

    $("#" + subUniqueID + " .appendNewAnswersDiv").append(answerHtml);
});

$(document).on('click', '.removeAnswerMine', function() {
    var subAnswerUniqueId = $(this).attr('data-subAnswerUniqueId');
    $("#" + subAnswerUniqueId).remove();
});

$(document).on('click', '#addQuestionType', function() {
    var new_unique_id = $("#new_unique_id").val();
    var new_section_id = $("#new_section_id").val();
    var questionType = $('input[name=newQuestionType]:checked').val();

    var subAnswerUniqueId = makeid(30);

    if (questionType == 'shortAnswer') {

        var newQuestionhtml = '';
        newQuestionhtml += '<div class="card-body questionFieldDivs" id="' + subAnswerUniqueId + '">';
        newQuestionhtml += '<div class="customQuestionDiv hideCustomQuestionDiv" style="display:none;" data-subuniqueid="' + subAnswerUniqueId + '"></div>';
        newQuestionhtml += '<div class="card showCustomQuestionDiv" data-subuniqueid="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<input type="hidden" class="subUniqueIDClass" name="subUniqueID[' + new_section_id + '][]" value="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<div class="card-body bg-content">';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group mr-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label>';
        newQuestionhtml += '<select class="form-control selectpickerelemtnt" tabindex="null" name="answer_type[' + new_section_id + '][]" onchange="getQuestionType(\'' + subAnswerUniqueId + '\',\'' + new_section_id + '\',this.value)">';
        newQuestionhtml += '<option value="shortAnswer" selected> Short answer</option>';
        newQuestionhtml += '<option value="longAnswer"> Long answer</option>';
        newQuestionhtml += '<option value="singleAnswer"> Single answer</option>';
        newQuestionhtml += '<option value="singleCheckbox"> Single checkbox</option>';
        newQuestionhtml += '<option value="multipleCheckbox"> Multiple choice</option>';
        newQuestionhtml += '<option value="dropdown"> Drop-down</option>';
        newQuestionhtml += '<option value="yesOrNo"> Yes or No</option>';
        newQuestionhtml += '<option value="des"> Description text</option>';
        newQuestionhtml += '</select>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="form-group ml-2 w-100"><label class="font-weight-bolder" for="exampleSelect1">Question</label><input type="text" class="form-control customQuestionField" name="question[' + new_section_id + '][]" placeholder="New question" data-isDescriptive="No"></div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="optionAnwersDiv"></div>';
        newQuestionhtml += '<hr>';
        newQuestionhtml += '<div class="d-flex flex-wrap justify-content-between align-items-center">';
        newQuestionhtml += '<div class="">';
        newQuestionhtml += '<div class="form-group mb-0">';

        newQuestionhtml += '<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">';
        newQuestionhtml += '<label class="d-flex align-item-center font-weight-bolder">';
        newQuestionhtml += '<input type="checkbox" class="isRequiredFieldCheck" checked="checked" name="is_required[' + new_section_id + '][]" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<input type="hidden" class="isRequiredFieldValue" id="requiredValueField' + subAnswerUniqueId + '" name="is_required_value[' + new_section_id + '][]" value="1">';
        newQuestionhtml += '<span></span>&nbsp;Required';
        newQuestionhtml += '</label>';
        newQuestionhtml += '</div>';

        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="d-flex align-items-center">';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToUpper">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToLower">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white cloneSubCustomField" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 far fa-clone fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white removeSubCustomField" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 fas fa-trash fa-2x text-danger"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white closeCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fa fa-times"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-primary markCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fas fa-check"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        $("#" + new_unique_id + " .inputFields").append(newQuestionhtml);
    } else if (questionType == 'longAnswer') {

        var newQuestionhtml = '';
        newQuestionhtml += '<div class="card-body questionFieldDivs" id="' + subAnswerUniqueId + '">';
        newQuestionhtml += '<div class="customQuestionDiv hideCustomQuestionDiv" style="display:none;" data-subuniqueid="' + subAnswerUniqueId + '"></div>';
        newQuestionhtml += '<div class="card showCustomQuestionDiv" data-subuniqueid="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<input type="hidden" class="subUniqueIDClass" name="subUniqueID[' + new_section_id + '][]" value="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<div class="card-body bg-content">';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group mr-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label>';
        newQuestionhtml += '<select class="form-control selectpickerelemtnt" tabindex="null" name="answer_type[' + new_section_id + '][]" onchange="getQuestionType(\'' + subAnswerUniqueId + '\',\'' + new_section_id + '\',this.value)">';
        newQuestionhtml += '<option value="shortAnswer"> Short answer</option>';
        newQuestionhtml += '<option value="longAnswer" selected> Long answer</option>';
        newQuestionhtml += '<option value="singleAnswer"> Single answer</option>';
        newQuestionhtml += '<option value="singleCheckbox"> Single checkbox</option>';
        newQuestionhtml += '<option value="multipleCheckbox"> Multiple choice</option>';
        newQuestionhtml += '<option value="dropdown"> Drop-down</option>';
        newQuestionhtml += '<option value="yesOrNo"> Yes or No</option>';
        newQuestionhtml += '<option value="des"> Description text</option>';
        newQuestionhtml += '</select>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="form-group ml-2 w-100"><label class="font-weight-bolder" for="exampleSelect1">Question</label><input type="text" class="form-control customQuestionField" name="question[' + new_section_id + '][]" placeholder="New question" data-isDescriptive="No"></div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="optionAnwersDiv"></div>';
        newQuestionhtml += '<hr>';
        newQuestionhtml += '<div class="d-flex flex-wrap justify-content-between align-items-center">';
        newQuestionhtml += '<div class="">';
        newQuestionhtml += '<div class="form-group mb-0">';

        newQuestionhtml += '<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">';
        newQuestionhtml += '<label class="d-flex align-item-center font-weight-bolder">';
        newQuestionhtml += '<input type="checkbox" class="isRequiredFieldCheck" checked="checked" name="is_required[' + new_section_id + '][]" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<input type="hidden" class="isRequiredFieldValue" id="requiredValueField' + subAnswerUniqueId + '" name="is_required_value[' + new_section_id + '][]" value="1">';
        newQuestionhtml += '<span></span>&nbsp;Required';
        newQuestionhtml += '</label>';
        newQuestionhtml += '</div>';

        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="d-flex align-items-center">';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToUpper">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToLower">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white cloneSubCustomField" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 far fa-clone fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white removeSubCustomField" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 fas fa-trash fa-2x text-danger"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white closeCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fa fa-times"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-primary markCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fas fa-check"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';

        $("#" + new_unique_id + " .inputFields").append(newQuestionhtml);
    } else if (questionType == 'singleAnswer') {

        var newQuestionhtml = '';
        newQuestionhtml += '<div class="card-body questionFieldDivs" id="' + subAnswerUniqueId + '">';
        newQuestionhtml += '<div class="customQuestionDiv hideCustomQuestionDiv" style="display:none;" data-subuniqueid="' + subAnswerUniqueId + '"></div>';
        newQuestionhtml += '<div class="card showCustomQuestionDiv" data-subuniqueid="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<input type="hidden" class="subUniqueIDClass" name="subUniqueID[' + new_section_id + '][]" value="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<div class="card-body bg-content">';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group mr-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label>';
        newQuestionhtml += '<select class="form-control selectpickerelemtnt" tabindex="null" name="answer_type[' + new_section_id + '][]" onchange="getQuestionType(\'' + subAnswerUniqueId + '\',\'' + new_section_id + '\',this.value)">';
        newQuestionhtml += '<option value="shortAnswer"> Short answer</option>';
        newQuestionhtml += '<option value="longAnswer"> Long answer</option>';
        newQuestionhtml += '<option value="singleAnswer" selected> Single answer</option>';
        newQuestionhtml += '<option value="singleCheckbox"> Single checkbox</option>';
        newQuestionhtml += '<option value="multipleCheckbox"> Multiple choice</option>';
        newQuestionhtml += '<option value="dropdown"> Drop-down</option>';
        newQuestionhtml += '<option value="yesOrNo"> Yes or No</option>';
        newQuestionhtml += '<option value="des"> Description text</option>';
        newQuestionhtml += '</select>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="form-group ml-2 w-100"><label class="font-weight-bolder" for="exampleSelect1">Question</label><input type="text" class="form-control customQuestionField" name="question[' + new_section_id + '][]" placeholder="New question" data-isDescriptive="No"></div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="optionAnwersDiv">';
        newQuestionhtml += '<div class="appendNewAnswersDiv">';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group ml-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder">Answer 1</label>';
        newQuestionhtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + new_section_id + '][' + subAnswerUniqueId + '][]">';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group ml-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder">Answer 2</label>';
        newQuestionhtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + new_section_id + '][' + subAnswerUniqueId + '][]">';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div>';
        newQuestionhtml += '<span class="cursor-pointer text-blue addNewQuestionAnswers" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="text-blue fa fa-plus mr-3"></i>Add next answer';
        newQuestionhtml += '</span>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<hr>';
        newQuestionhtml += '<div class="d-flex flex-wrap justify-content-between align-items-center">';
        newQuestionhtml += '<div class="">';
        newQuestionhtml += '<div class="form-group mb-0">';

        newQuestionhtml += '<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">';
        newQuestionhtml += '<label class="d-flex align-item-center font-weight-bolder">';
        newQuestionhtml += '<input type="checkbox" class="isRequiredFieldCheck" checked="checked" name="is_required[' + new_section_id + '][]" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<input type="hidden" class="isRequiredFieldValue" id="requiredValueField' + subAnswerUniqueId + '" name="is_required_value[' + new_section_id + '][]" value="1">';
        newQuestionhtml += '<span></span>&nbsp;Required';
        newQuestionhtml += '</label>';
        newQuestionhtml += '</div>';

        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="d-flex align-items-center">';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToUpper">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToLower">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white cloneSubCustomField" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 far fa-clone fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white removeSubCustomField" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 fas fa-trash fa-2x text-danger"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white closeCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fa fa-times"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-primary markCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fas fa-check"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        $("#" + new_unique_id + " .inputFields").append(newQuestionhtml);
    } else if (questionType == 'singleCheckbox') {

        var newQuestionhtml = '';
        newQuestionhtml += '<div class="card-body questionFieldDivs" id="' + subAnswerUniqueId + '">';
        newQuestionhtml += '<div class="customQuestionDiv hideCustomQuestionDiv" style="display:none;" data-subuniqueid="' + subAnswerUniqueId + '"></div>';
        newQuestionhtml += '<div class="card showCustomQuestionDiv" data-subuniqueid="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<input type="hidden" class="subUniqueIDClass" name="subUniqueID[' + new_section_id + '][]" value="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<div class="card-body bg-content">';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group mr-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label>';
        newQuestionhtml += '<select class="form-control selectpickerelemtnt" tabindex="null" name="answer_type[' + new_section_id + '][]" onchange="getQuestionType(\'' + subAnswerUniqueId + '\',\'' + new_section_id + '\',this.value)">';
        newQuestionhtml += '<option value="shortAnswer"> Short answer</option>';
        newQuestionhtml += '<option value="longAnswer"> Long answer</option>';
        newQuestionhtml += '<option value="singleAnswer"> Single answer</option>';
        newQuestionhtml += '<option value="singleCheckbox" selected> Single checkbox</option>';
        newQuestionhtml += '<option value="multipleCheckbox"> Multiple choice</option>';
        newQuestionhtml += '<option value="dropdown"> Drop-down</option>';
        newQuestionhtml += '<option value="yesOrNo"> Yes or No</option>';
        newQuestionhtml += '<option value="des"> Description text</option>';
        newQuestionhtml += '</select>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="form-group ml-2 w-100"><label class="font-weight-bolder" for="exampleSelect1">Question</label><input type="text" class="form-control customQuestionField" name="question[' + new_section_id + '][]" placeholder="New question" data-isDescriptive="No"></div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="optionAnwersDiv"></div>';
        newQuestionhtml += '<hr>';
        newQuestionhtml += '<div class="d-flex flex-wrap justify-content-between align-items-center">';
        newQuestionhtml += '<div class="">';
        newQuestionhtml += '<div class="form-group mb-0">';

        newQuestionhtml += '<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">';
        newQuestionhtml += '<label class="d-flex align-item-center font-weight-bolder">';
        newQuestionhtml += '<input type="checkbox" class="isRequiredFieldCheck" checked="checked" name="is_required[' + new_section_id + '][]" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<input type="hidden" class="isRequiredFieldValue" id="requiredValueField' + subAnswerUniqueId + '" name="is_required_value[' + new_section_id + '][]" value="1">';
        newQuestionhtml += '<span></span>&nbsp;Required';
        newQuestionhtml += '</label>';
        newQuestionhtml += '</div>';

        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="d-flex align-items-center">';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToUpper">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToLower">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white cloneSubCustomField" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 far fa-clone fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white removeSubCustomField" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 fas fa-trash fa-2x text-danger"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white closeCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fa fa-times"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-primary markCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fas fa-check"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        $("#" + new_unique_id + " .inputFields").append(newQuestionhtml);
    } else if (questionType == 'multipleCheckbox') {

        var newQuestionhtml = '';
        newQuestionhtml += '<div class="card-body questionFieldDivs" id="' + subAnswerUniqueId + '">';
        newQuestionhtml += '<div class="customQuestionDiv hideCustomQuestionDiv" style="display:none;" data-subuniqueid="' + subAnswerUniqueId + '"></div>';
        newQuestionhtml += '<div class="card showCustomQuestionDiv" data-subuniqueid="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<input type="hidden" class="subUniqueIDClass" name="subUniqueID[' + new_section_id + '][]" value="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<div class="card-body bg-content">';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group mr-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label>';
        newQuestionhtml += '<select class="form-control selectpickerelemtnt" tabindex="null" name="answer_type[' + new_section_id + '][]" onchange="getQuestionType(\'' + subAnswerUniqueId + '\',\'' + new_section_id + '\',this.value)">';
        newQuestionhtml += '<option value="shortAnswer"> Short answer</option>';
        newQuestionhtml += '<option value="longAnswer"> Long answer</option>';
        newQuestionhtml += '<option value="singleAnswer"> Single answer</option>';
        newQuestionhtml += '<option value="singleCheckbox"> Single checkbox</option>';
        newQuestionhtml += '<option value="multipleCheckbox" selected> Multiple choice</option>';
        newQuestionhtml += '<option value="dropdown"> Drop-down</option>';
        newQuestionhtml += '<option value="yesOrNo"> Yes or No</option>';
        newQuestionhtml += '<option value="des"> Description text</option>';
        newQuestionhtml += '</select>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="form-group ml-2 w-100"><label class="font-weight-bolder" for="exampleSelect1">Question</label><input type="text" class="form-control customQuestionField" name="question[' + new_section_id + '][]" placeholder="New question" data-isDescriptive="No"></div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="optionAnwersDiv">';
        newQuestionhtml += '<div class="appendNewAnswersDiv">';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group ml-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder">Answer 1</label>';
        newQuestionhtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + new_section_id + '][' + subAnswerUniqueId + '][]">';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group ml-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder">Answer 2</label>';
        newQuestionhtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + new_section_id + '][' + subAnswerUniqueId + '][]">';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div>';
        newQuestionhtml += '<span class="cursor-pointer text-blue addNewQuestionAnswers" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="text-blue fa fa-plus mr-3"></i>Add next answer';
        newQuestionhtml += '</span>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<hr>';
        newQuestionhtml += '<div class="d-flex flex-wrap justify-content-between align-items-center">';
        newQuestionhtml += '<div class="">';
        newQuestionhtml += '<div class="form-group mb-0">';

        newQuestionhtml += '<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">';
        newQuestionhtml += '<label class="d-flex align-item-center font-weight-bolder">';
        newQuestionhtml += '<input type="checkbox" class="isRequiredFieldCheck" checked="checked" name="is_required[' + new_section_id + '][]" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<input type="hidden" class="isRequiredFieldValue" id="requiredValueField' + subAnswerUniqueId + '" name="is_required_value[' + new_section_id + '][]" value="1">';
        newQuestionhtml += '<span></span>&nbsp;Required';
        newQuestionhtml += '</label>';
        newQuestionhtml += '</div>';

        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="d-flex align-items-center">';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToUpper">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToLower">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white cloneSubCustomField" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 far fa-clone fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white removeSubCustomField" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 fas fa-trash fa-2x text-danger"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white closeCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fa fa-times"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-primary markCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fas fa-check"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        $("#" + new_unique_id + " .inputFields").append(newQuestionhtml);
    } else if (questionType == 'dropdown') {

        var newQuestionhtml = '';
        newQuestionhtml += '<div class="card-body questionFieldDivs" id="' + subAnswerUniqueId + '">';
        newQuestionhtml += '<div class="customQuestionDiv hideCustomQuestionDiv" style="display:none;" data-subuniqueid="' + subAnswerUniqueId + '"></div>';
        newQuestionhtml += '<div class="card showCustomQuestionDiv" data-subuniqueid="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<input type="hidden" class="subUniqueIDClass" name="subUniqueID[' + new_section_id + '][]" value="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<div class="card-body bg-content">';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group mr-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label>';
        newQuestionhtml += '<select class="form-control selectpickerelemtnt" tabindex="null" name="answer_type[' + new_section_id + '][]" onchange="getQuestionType(\'' + subAnswerUniqueId + '\',\'' + new_section_id + '\',this.value)">';
        newQuestionhtml += '<option value="shortAnswer"> Short answer</option>';
        newQuestionhtml += '<option value="longAnswer"> Long answer</option>';
        newQuestionhtml += '<option value="singleAnswer"> Single answer</option>';
        newQuestionhtml += '<option value="singleCheckbox"> Single checkbox</option>';
        newQuestionhtml += '<option value="multipleCheckbox"> Multiple choice</option>';
        newQuestionhtml += '<option value="dropdown" selected> Drop-down</option>';
        newQuestionhtml += '<option value="yesOrNo"> Yes or No</option>';
        newQuestionhtml += '<option value="des"> Description text</option>';
        newQuestionhtml += '</select>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="form-group ml-2 w-100"><label class="font-weight-bolder" for="exampleSelect1">Question</label><input type="text" class="form-control customQuestionField" name="question[' + new_section_id + '][]" placeholder="New question" data-isDescriptive="No"></div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="optionAnwersDiv">';
        newQuestionhtml += '<div class="appendNewAnswersDiv">';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group ml-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder">Answer 1</label>';
        newQuestionhtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + new_section_id + '][' + subAnswerUniqueId + '][]">';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group ml-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder">Answer 2</label>';
        newQuestionhtml += '<input type="text" class="form-control questionAnswers" name="answerField[' + new_section_id + '][' + subAnswerUniqueId + '][]">';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div>';
        newQuestionhtml += '<span class="cursor-pointer text-blue addNewQuestionAnswers" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="text-blue fa fa-plus mr-3"></i>Add next answer';
        newQuestionhtml += '</span>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<hr>';
        newQuestionhtml += '<div class="d-flex flex-wrap justify-content-between align-items-center">';
        newQuestionhtml += '<div class="">';
        newQuestionhtml += '<div class="form-group mb-0">';

        newQuestionhtml += '<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">';
        newQuestionhtml += '<label class="d-flex align-item-center font-weight-bolder">';
        newQuestionhtml += '<input type="checkbox" class="isRequiredFieldCheck" checked="checked" name="is_required[' + new_section_id + '][]" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<input type="hidden" class="isRequiredFieldValue" id="requiredValueField' + subAnswerUniqueId + '" name="is_required_value[' + new_section_id + '][]" value="1">';
        newQuestionhtml += '<span></span>&nbsp;Required';
        newQuestionhtml += '</label>';
        newQuestionhtml += '</div>';

        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="d-flex align-items-center">';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToUpper">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToLower">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white cloneSubCustomField" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 far fa-clone fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white removeSubCustomField" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 fas fa-trash fa-2x text-danger"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white closeCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fa fa-times"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-primary markCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fas fa-check"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        $("#" + new_unique_id + " .inputFields").append(newQuestionhtml);
    } else if (questionType == 'yesOrNo') {

        var newQuestionhtml = '';
        newQuestionhtml += '<div class="card-body questionFieldDivs" id="' + subAnswerUniqueId + '">';
        newQuestionhtml += '<div class="customQuestionDiv hideCustomQuestionDiv" style="display:none;" data-subuniqueid="' + subAnswerUniqueId + '"></div>';
        newQuestionhtml += '<div class="card showCustomQuestionDiv" data-subuniqueid="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<input type="hidden" class="subUniqueIDClass" name="subUniqueID[' + new_section_id + '][]" value="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<div class="card-body bg-content">';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group mr-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label>';
        newQuestionhtml += '<select class="form-control selectpickerelemtnt" tabindex="null" name="answer_type[' + new_section_id + '][]" onchange="getQuestionType(\'' + subAnswerUniqueId + '\',\'' + new_section_id + '\',this.value)">';
        newQuestionhtml += '<option value="shortAnswer"> Short answer</option>';
        newQuestionhtml += '<option value="longAnswer"> Long answer</option>';
        newQuestionhtml += '<option value="singleAnswer"> Single answer</option>';
        newQuestionhtml += '<option value="singleCheckbox"> Single checkbox</option>';
        newQuestionhtml += '<option value="multipleCheckbox"> Multiple choice</option>';
        newQuestionhtml += '<option value="dropdown"> Drop-down</option>';
        newQuestionhtml += '<option value="yesOrNo" selected> Yes or No</option>';
        newQuestionhtml += '<option value="des"> Description text</option>';
        newQuestionhtml += '</select>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="form-group ml-2 w-100"><label class="font-weight-bolder" for="exampleSelect1">Question</label><input type="text" class="form-control customQuestionField" name="question[' + new_section_id + '][]" placeholder="New question" data-isDescriptive="No"></div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="optionAnwersDiv"></div>';
        newQuestionhtml += '<hr>';
        newQuestionhtml += '<div class="d-flex flex-wrap justify-content-between align-items-center">';
        newQuestionhtml += '<div class="">';
        newQuestionhtml += '<div class="form-group mb-0">';

        newQuestionhtml += '<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">';
        newQuestionhtml += '<label class="d-flex align-item-center font-weight-bolder">';
        newQuestionhtml += '<input type="checkbox" class="isRequiredFieldCheck" checked="checked" name="is_required[' + new_section_id + '][]" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<input type="hidden" class="isRequiredFieldValue" id="requiredValueField' + subAnswerUniqueId + '" name="is_required_value[' + new_section_id + '][]" value="1">';
        newQuestionhtml += '<span></span>&nbsp;Required';
        newQuestionhtml += '</label>';
        newQuestionhtml += '</div>';

        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="d-flex align-items-center">';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToUpper">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToLower">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white cloneSubCustomField" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 far fa-clone fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white removeSubCustomField" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 fas fa-trash fa-2x text-danger"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white closeCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fa fa-times"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-primary markCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fas fa-check"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        $("#" + new_unique_id + " .inputFields").append(newQuestionhtml);
    } else if (questionType == 'des') {

        var newQuestionhtml = '';
        newQuestionhtml += '<div class="card-body questionFieldDivs" id="' + subAnswerUniqueId + '">';
        newQuestionhtml += '<div class="customQuestionDiv hideCustomQuestionDiv" style="display:none;" data-subuniqueid="' + subAnswerUniqueId + '"></div>';
        newQuestionhtml += '<div class="card showCustomQuestionDiv" data-subuniqueid="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<input type="hidden" class="subUniqueIDClass" name="subUniqueID[' + new_section_id + '][]" value="' + subAnswerUniqueId + '">';

        newQuestionhtml += '<div class="card-body bg-content">';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group mr-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label>';
        newQuestionhtml += '<select class="form-control selectpickerelemtnt" tabindex="null" name="answer_type[' + new_section_id + '][]" onchange="getQuestionType(\'' + subAnswerUniqueId + '\',\'' + new_section_id + '\',this.value)">';
        newQuestionhtml += '<option value="shortAnswer"> Short answer</option>';
        newQuestionhtml += '<option value="longAnswer"> Long answer</option>';
        newQuestionhtml += '<option value="singleAnswer"> Single answer</option>';
        newQuestionhtml += '<option value="singleCheckbox"> Single checkbox</option>';
        newQuestionhtml += '<option value="multipleCheckbox"> Multiple choice</option>';
        newQuestionhtml += '<option value="dropdown"> Drop-down</option>';
        newQuestionhtml += '<option value="yesOrNo"> Yes or No</option>';
        newQuestionhtml += '<option value="des" selected> Description text</option>';
        newQuestionhtml += '</select>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="form-group ml-2 w-100"><label class="font-weight-bolder" for="exampleSelect1">Question</label><input type="text" class="form-control customQuestionField" name="question[' + new_section_id + '][]" placeholder="New question" readonly data-isDescriptive="Yes"></div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="optionAnwersDiv">';
        newQuestionhtml += '<div class="appendNewAnswersDiv">';
        newQuestionhtml += '<div class="d-flex justify-content-between">';
        newQuestionhtml += '<div class="form-group ml-2 w-100">';
        newQuestionhtml += '<label class="font-weight-bolder">Text description</label>';
        newQuestionhtml += '<textarea type="text" class="form-control questionAnswers" name="answerField[' + new_section_id + '][' + subAnswerUniqueId + '][]" rows="5"></textarea>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<hr>';
        newQuestionhtml += '<div class="d-flex flex-wrap justify-content-between align-items-center">';
        newQuestionhtml += '<div class="">';
        newQuestionhtml += '<div class="form-group mb-0">';

        newQuestionhtml += '<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">';
        newQuestionhtml += '<label class="d-flex align-item-center font-weight-bolder">';
        newQuestionhtml += '<input type="checkbox" class="isRequiredFieldCheck" checked="checked" name="is_required[' + new_section_id + '][]" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<input type="hidden" class="isRequiredFieldValue" id="requiredValueField' + subAnswerUniqueId + '" name="is_required_value[' + new_section_id + '][]" value="1">';
        newQuestionhtml += '<span></span>&nbsp;Required';
        newQuestionhtml += '</label>';
        newQuestionhtml += '</div>';

        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '<div class="d-flex align-items-center">';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToUpper">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToLower">';
        newQuestionhtml += '<i class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="border-right p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white cloneSubCustomField" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 far fa-clone fa-2x"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white removeSubCustomField" data-subuniqueid="' + subAnswerUniqueId + '" data-uniqueid="' + new_unique_id + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="mx-1 fas fa-trash fa-2x text-danger"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '<span class="p-3">';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-white closeCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fa fa-times"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '<button type="button" class="mx-2 btn btn-sm btn-primary markCompleteSection" data-subuniqueid="' + subAnswerUniqueId + '" data-sectionId="' + new_section_id + '">';
        newQuestionhtml += '<i class="p-0 fas fa-check"></i>';
        newQuestionhtml += '</button>';
        newQuestionhtml += '</span>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        newQuestionhtml += '</div>';
        $("#" + new_unique_id + " .inputFields").append(newQuestionhtml);
    }

    $("#addNewQuestionModal").modal('hide');
});

$(document).on('click', '.addNewQuestionClick', function() {
    var uniqueid = $(this).attr('data-uniqueid');
    var sectionId = $(this).attr('data-sectionId');

    $("#new_unique_id").val(uniqueid);
    $("#new_section_id").val(sectionId);
});

$(document).on('click', '.selectedServices', function() {

    var serviceIds = [];
    $("#selectedServices").val(serviceIds);

    $(".servicePriceIds").each(function() {
        if (this.checked) {
            serviceIds.push(this.value);
        }
    });

    $("#selectedServices").val(serviceIds);
});

$(document).on('click', '.isRequiredFieldCheck', function() {
    var subuniqueid = $(this).attr('data-subuniqueid');
    if (this.checked) {
        $("#requiredValueField" + subuniqueid).val(1);
    } else {
        $("#requiredValueField" + subuniqueid).val(0);
    }
});

//load data when page load
jQuery(document).ready(function() {

    var array = JSON.parse('[' + serviceId + ']');
    var result = array.map(function(a) { return a; }).join();

    $("#selectedServices").val(result);

    Object.keys(groupbyarray).forEach(function(keys) {
        if (groupbyarray[keys][0]['title']) {

            var sectioncount = groupbyarray[keys].length;

            //add custome section
            var subSectionTitle = groupbyarray[keys][0]['title'];
            var subSectionDescription = groupbyarray[keys][0]['des'];
            var current_section = (parseInt($("#total_section").val())) + 1;
            $("#total_section").val(current_section);
            var total_section = $("#total_section").val();
            $(".TotalSection").html(total_section);

            $(".detailsFormBtn").attr('disabled', false);

            $(".defaultView").hide();
            $(".afterView").show();
            $("#addCustomSectionModal").modal('hide');

            var unique_id = makeid(15);
            var subSectionHtml = '';

            var sectionPreview = '';
            sectionPreview += '<div class="preview-tab ' + unique_id + '" data-sectionTitle="' + subSectionTitle + '" data-sectionDescription="' + subSectionDescription + '">';
            sectionPreview += '<div class="card-body sectionPreviewTab' + current_section + '">';

            sectionPreview += '</div>';
            sectionPreview += '</div>';

            $(".sectionTabDiv").append(sectionPreview);
            showPreviewTab(0);

            var sectionForm = '';
            sectionForm += '<div class="forms-tab ' + unique_id + '" data-sectionTitle="' + subSectionTitle + '" data-sectionDescription="' + subSectionDescription + '">';
            sectionForm += '<div class="card-body sectionPreviewTab' + current_section + '">';

            sectionForm += '</div>';
            sectionForm += '</div>';
            $(".sectionTabDivForm").append(sectionForm);
            showFormsTab(0);

            subSectionHtml += '<div class="card w-70 mx-auto mt-16 customFieldDivCount sectionIdentitiyClass' + current_section + '" id="' + unique_id + '">';
            subSectionHtml += '<div class="my-card-lable-orange">';
            subSectionHtml += 'Section <span class="CurrentSection">' + current_section + '</span> of <span class="TotalSection">' + total_section + '</span> : Custom section';
            subSectionHtml += '</div>';
            subSectionHtml += '<input type="hidden" name="section_id[]" value="' + current_section + '">';
            subSectionHtml += '<input type="hidden" name="form_type[]" value="custom_form">';
            subSectionHtml += '<div class="card-header d-flex justify-content-between">';
            subSectionHtml += '<div>';

            if (subSectionTitle != '') {
                subSectionHtml += '<h3 class="sectionTitle">' + subSectionTitle + '</h3>';
            }

            if (subSectionDescription != '') {
                subSectionHtml += '<p class="m-0 text-dakr-50 sectionDescription">' + subSectionDescription + '</p>';
            }

            subSectionHtml += '<input type="hidden" class="customSectionTitle" name="customSectionTitle[' + current_section + ']" value="' + subSectionTitle + '">';
            subSectionHtml += '<input type="hidden" class="customSectionDescription" name="customSectionDescription[' + current_section + ']" value="' + subSectionDescription + '">';

            subSectionHtml += '</div>';
            subSectionHtml += '<div class="dropdown dropdown-inline">';
            subSectionHtml += '<a href="#" class="btn btn-clean text-dark btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            subSectionHtml += '<i class="ki ki-bold-more-ver text-dark"></i>';
            subSectionHtml += '</a>';
            subSectionHtml += '<div class="dropdown-menu dropdown-menu-right text-center">';
            subSectionHtml += '<ul class="navi navi-hover">';
            subSectionHtml += '<li class="navi-item">';
            subSectionHtml += '<a href="javascript:;" class="navi-link editCustomSectionClass" data-uniqueid="' + unique_id + '">';
            subSectionHtml += '<span class="navi-text">Edit</span>';
            subSectionHtml += '</a>';
            subSectionHtml += '</li>';
            subSectionHtml += '<li class="navi-item">';
            subSectionHtml += '<a href="javascript:;" class="navi-link removeMe" data-deleteId="' + unique_id + '">';
            subSectionHtml += '<span class="navi-text text-danger">Delete</span>';
            subSectionHtml += '</a>';
            subSectionHtml += '</li>';
            subSectionHtml += '</ul>';
            subSectionHtml += '</div>';
            subSectionHtml += '</div>';
            subSectionHtml += '</div>';

            var subCustomFields = makeid(30);

            subSectionHtml += '<div class="inputFields">';
            if (sectioncount <= 1) { sectioncount = 0; } else { sectioncount = sectioncount - 1; };

            for (var j = 0; j <= sectioncount; j++) {
                subSectionHtml += '<div class="card-body questionFieldDivs" id="' + subCustomFields + j + '">';
                subSectionHtml += '<div class="customQuestionDiv hideCustomQuestionDiv" data-subUniqueID="' + subCustomFields + j + '">';

                //show qna
                var subUniqueID = subCustomFields + j;
                var sectionId = current_section;
                var getQuestion = groupbyarray[keys][j]['question']; // $("#" + subUniqueID + " .customQuestionField").val();
                var questionType = groupbyarray[keys][j]['answer_type']; //$("#" + subUniqueID + " .selectpickerelemtnt").val();

                if (questionType == 'shortAnswer') {
                    var question = groupbyarray[keys][j]['question']; //$("#" + subUniqueID + " .customQuestionField").val();

                    // var customField = '';
                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<label class="font-weight-bolder customQuestionLabel">';
                    subSectionHtml += question;
                    subSectionHtml += '</label>';
                    subSectionHtml += '<input type="text" class="form-control">';
                    subSectionHtml += '</div>';

                    // $("#" + subUniqueID + " .customQuestionDiv").append(customField);

                    var previewHtml = '';
                    if ($(".previewSection" + subUniqueID).length > 0) {

                        previewHtml += '<label class="font-weight-bolder customQuestionLabel">';
                        previewHtml += question;
                        previewHtml += '</label>';
                        previewHtml += '<input type="text" class="form-control">';

                        $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).append(previewHtml);
                    } else {

                        previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
                        previewHtml += '<label class="font-weight-bolder customQuestionLabel">';
                        previewHtml += question;
                        previewHtml += '</label>';
                        previewHtml += '<input type="text" class="form-control">';
                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId).append(previewHtml);
                    }

                } else if (questionType == 'longAnswer') {

                    var question = groupbyarray[keys][j]['question']; //$("#" + subUniqueID + " .customQuestionField").val();

                    // var customField = '';
                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<label class="font-weight-bolder customQuestionLabel">';
                    subSectionHtml += question;
                    subSectionHtml += '</label>';
                    subSectionHtml += '<textarea class="form-control"></textarea>';
                    subSectionHtml += '</div>';

                    // $("#" + subUniqueID + " .customQuestionDiv").html(customField);

                    var previewHtml = '';
                    if ($(".previewSection" + subUniqueID).length > 0) {

                        previewHtml += '<label class="font-weight-bolder customQuestionLabel">';
                        previewHtml += question;
                        previewHtml += '</label>';
                        previewHtml += '<textarea class="form-control"></textarea>';

                        $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).append(previewHtml);
                    } else {

                        previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
                        previewHtml += '<label class="font-weight-bolder customQuestionLabel">';
                        previewHtml += question;
                        previewHtml += '</label>';
                        previewHtml += '<textarea class="form-control"></textarea>';
                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId).append(previewHtml);
                    }

                } else if (questionType == 'singleAnswer') {

                    var question = groupbyarray[keys][j]['question']; //$("#" + subUniqueID + " .customQuestionField").val();
                    var answerstatus = 1;

                    $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                        if ($(this).val() == '') {
                            answerstatus = 0;
                        }
                    });

                    if (answerstatus == 0) {
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
                        toastr.error("Answer field is required.");
                        return false;
                    }

                    // var customField = '';

                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
                    subSectionHtml += '<div class="radio-list">';
                    var ansdata = JSON.parse(groupbyarray[keys][j]['3ans']);
                    var lenans = JSON.parse(groupbyarray[keys][j]['3ans']).length;

                    if (lenans <= 1) { lenans = 1; } else { lenans = lenans - 1; };

                    for (var A = 0; A <= lenans; A++) {

                        subSectionHtml += '<label class="radio">';
                        subSectionHtml += '<input type="radio" name="radios' + subUniqueID + '">';
                        subSectionHtml += '<span></span>' + ansdata[A];
                        subSectionHtml += '</label>';
                    }
                    // $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                    //     var ans = $(this).val();

                    //     subSectionHtml += '<label class="radio">';
                    //     subSectionHtml += '<input type="radio" name="radios' + subUniqueID + '">';
                    //     subSectionHtml += '<span></span>' + ans;
                    //     subSectionHtml += '</label>';
                    // });

                    subSectionHtml += '</div>';
                    subSectionHtml += '</div>';

                    // $("#" + subUniqueID + " .customQuestionDiv").html(customField);

                    var previewHtml = '';
                    if ($(".previewSection" + subUniqueID).length > 0) {

                        previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
                        previewHtml += '<div class="radio-list">';

                        if (lenans <= 1) { lenans = 1; } else { lenans = lenans - 1; };

                        for (var A = 0; A <= lenans; A++) {

                            previewHtml += '<label class="radio">';
                            previewHtml += '<input type="radio" name="radios' + subUniqueID + '">';
                            previewHtml += '<span></span>' + ansdata[A];
                            previewHtml += '</label>';
                        }
                        // $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                        //     var ans = $(this).val();

                        //     previewHtml += '<label class="radio">';
                        //     previewHtml += '<input type="radio" name="radios' + subUniqueID + '">';
                        //     previewHtml += '<span></span>' + ans;
                        //     previewHtml += '</label>';
                        // });

                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).append(previewHtml);
                    } else {

                        previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
                        previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
                        previewHtml += '<div class="radio-list">';
                        if (lenans <= 1) { lenans = 1; } else { lenans = lenans - 1; };

                        for (var A = 0; A <= lenans; A++) {

                            previewHtml += '<label class="radio">';
                            previewHtml += '<input type="radio" name="radios' + subUniqueID + '">';
                            previewHtml += '<span></span>' + ansdata[A];
                            previewHtml += '</label>';
                        }
                        // $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                        //     var ans = $(this).val();

                        //     previewHtml += '<label class="radio">';
                        //     previewHtml += '<input type="radio" name="radios' + subUniqueID + '">';
                        //     previewHtml += '<span></span>' + ans;
                        //     previewHtml += '</label>';
                        // });

                        previewHtml += '</div>';
                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId).append(previewHtml);
                    }

                } else if (questionType == 'singleCheckbox') {

                    var question = groupbyarray[keys][j]['question']; //$("#" + subUniqueID + " .customQuestionField").val();

                    // var customField = '';

                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<div class="checkbox-list">';
                    subSectionHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span> <p class="customQuestionLabel">' + question + '</p></label>';
                    subSectionHtml += '</div>';
                    subSectionHtml += '</div>';
                    subSectionHtml += '</div>';

                    // $("#" + subUniqueID + " .customQuestionDiv").html(customField);

                    var previewHtml = '';
                    if ($(".previewSection" + subUniqueID).length > 0) {

                        previewHtml += '<div class="form-group">';
                        previewHtml += '<div class="checkbox-list">';
                        previewHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span> <p class="customQuestionLabel">' + question + '</p></label>';
                        previewHtml += '</div>';
                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).append(previewHtml);
                    } else {

                        previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
                        previewHtml += '<div class="form-group">';
                        previewHtml += '<div class="checkbox-list">';
                        previewHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span> <p class="customQuestionLabel">' + question + '</p></label>';
                        previewHtml += '</div>';
                        previewHtml += '</div>';
                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId).append(previewHtml);
                    }

                } else if (questionType == 'multipleCheckbox') {

                    var question = groupbyarray[keys][j]['question']; //$("#" + subUniqueID + " .customQuestionField").val();
                    var answerstatus = 1;

                    $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                        if ($(this).val() == '') {
                            answerstatus = 0;
                        }
                    });

                    if (answerstatus == 0) {
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
                        toastr.error("Answer field is required.");
                        return false;
                    }

                    // var customField = '';

                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
                    subSectionHtml += '<div class="radio-list">';

                    var ansdata = JSON.parse(groupbyarray[keys][j]['5ans']);
                    var lenans = JSON.parse(groupbyarray[keys][j]['5ans']).length;

                    if (lenans <= 1) { lenans = 1; } else { lenans = lenans - 1; };

                    for (var A = 0; A <= lenans; A++) {

                        subSectionHtml += '<div class="checkbox-list">';
                        subSectionHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span>' + ansdata[A] + '</label>';
                        subSectionHtml += '</div>';
                    }
                    // $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                    //     var ans = $(this).val();

                    //     subSectionHtml += '<div class="checkbox-list">';
                    //     subSectionHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span>' + ans + '</label>';
                    //     subSectionHtml += '</div>';
                    // });

                    subSectionHtml += '</div>';
                    subSectionHtml += '</div>';

                    // $("#" + subUniqueID + " .customQuestionDiv").html(customField);

                    var previewHtml = '';
                    if ($(".previewSection" + subUniqueID).length > 0) {

                        previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
                        previewHtml += '<div class="radio-list">';
                        if (lenans <= 1) { lenans = 1; } else { lenans = lenans - 1; };

                        for (var A = 0; A <= lenans; A++) {

                            previewHtml += '<div class="checkbox-list">';
                            previewHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span>' + ansdata[A] + '</label>';
                            previewHtml += '</div>';
                        }
                        // $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                        //     var ans = $(this).val();

                        //     previewHtml += '<div class="checkbox-list">';
                        //     previewHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span>' + ans + '</label>';
                        //     previewHtml += '</div>';
                        // });

                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).append(previewHtml);
                    } else {

                        previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
                        previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
                        previewHtml += '<div class="radio-list">';

                        if (lenans <= 1) { lenans = 1; } else { lenans = lenans - 1; };

                        for (var A = 0; A <= lenans; A++) {

                            previewHtml += '<div class="checkbox-list">';
                            previewHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span>' + ansdata[A] + '</label>';
                            previewHtml += '</div>';
                        }
                        // $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                        //     var ans = $(this).val();

                        //     previewHtml += '<div class="checkbox-list">';
                        //     previewHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subUniqueID + '"><span></span>' + ans + '</label>';
                        //     previewHtml += '</div>';
                        // });

                        previewHtml += '</div>';
                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId).append(previewHtml);
                    }

                } else if (questionType == 'dropdown') {

                    var question = groupbyarray[keys][j]['question']; //$("#" + subUniqueID + " .customQuestionField").val();
                    var answerstatus = 1;

                    $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                        if ($(this).val() == '') {
                            answerstatus = 0;
                        }
                    });

                    if (answerstatus == 0) {
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
                        toastr.error("Answer field is required.");
                        return false;
                    }

                    // var customField = '';

                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
                    subSectionHtml += '<div class="dropdown">';
                    subSectionHtml += '<select class="custom-select">';
                    subSectionHtml += '<option value="">Please select</option>';
                    var ansdata = JSON.parse(groupbyarray[keys][j]['6ans']);
                    var lenans = JSON.parse(groupbyarray[keys][j]['6ans']).length;

                    if (lenans <= 1) { lenans = 1; } else { lenans = lenans - 1; };

                    for (var A = 0; A <= lenans; A++) {
                        subSectionHtml += '<option value="' + ansdata[A] + '">' + ansdata[A] + '</option>';
                    }
                    // $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                    //     var ans = $(this).val();
                    //     subSectionHtml += '<option value="' + ans + '">' + ans + '</option>';
                    // });

                    subSectionHtml += '</select>';
                    subSectionHtml += '</div>';
                    subSectionHtml += '</div>';

                    // $("#" + subUniqueID + " .customQuestionDiv").html(customField);

                    var previewHtml = '';
                    if ($(".previewSection" + subUniqueID).length > 0) {

                        previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
                        previewHtml += '<div class="dropdown">';
                        previewHtml += '<select class="custom-select">';
                        previewHtml += '<option value="">Please select</option>';
                        if (lenans <= 1) { lenans = 1; } else { lenans = lenans - 1; };

                        for (var A = 0; A <= lenans; A++) {
                            previewHtml += '<option value="' + ansdata[A] + '">' + ansdata[A] + '</option>';
                        }
                        // $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                        //     var ans = $(this).val();
                        //     previewHtml += '<option value="' + ans + '">' + ans + '</option>';
                        // });

                        previewHtml += '</select>';
                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).append(previewHtml);
                    } else {

                        previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
                        previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>';
                        previewHtml += '<div class="dropdown">';
                        previewHtml += '<select class="custom-select">';
                        previewHtml += '<option value="">Please select</option>';
                        if (lenans <= 1) { lenans = 1; } else { lenans = lenans - 1; };

                        for (var A = 0; A <= lenans; A++) {
                            previewHtml += '<option value="' + ansdata[A] + '">' + ansdata[A] + '</option>';
                        }
                        // $("#" + subUniqueID + " .questionAnswers").each(function(index) {
                        //     var ans = $(this).val();
                        //     previewHtml += '<option value="' + ans + '">' + ans + '</option>';
                        // });

                        previewHtml += '</select>';
                        previewHtml += '</div>';
                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId).append(previewHtml);
                    }

                } else if (questionType == 'yesOrNo') {

                    var question = groupbyarray[keys][j]['question']; // $("#" + subUniqueID + " .customQuestionField").val();

                    // var customField = '';
                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<div class="form-group ml-2 w-100 d-flex extra-time">';
                    subSectionHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>	';
                    subSectionHtml += '<br>';
                    subSectionHtml += '<label class="option m-3">';
                    subSectionHtml += '<span class="option-control">';
                    subSectionHtml += '<span class="radio">';
                    subSectionHtml += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="Yes" checked="checked">';
                    subSectionHtml += '<span></span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '<span class="option-label">';
                    subSectionHtml += '<span class="option-head">';
                    subSectionHtml += '<span class="option-title">Yes</span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '</label>';
                    subSectionHtml += '<label class="option m-3">';
                    subSectionHtml += '<span class="option-control">';
                    subSectionHtml += '<span class="radio">';
                    subSectionHtml += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="No">';
                    subSectionHtml += '<span></span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '<span class="option-label">';
                    subSectionHtml += '<span class="option-head">';
                    subSectionHtml += '<span class="option-title">';
                    subSectionHtml += 'No';
                    subSectionHtml += '</span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '</label>';
                    subSectionHtml += '</div>';
                    subSectionHtml += '</div>';

                    // $("#" + subUniqueID + " .customQuestionDiv").html(customField);

                    var previewHtml = '';
                    if ($(".previewSection" + subUniqueID).length > 0) {

                        previewHtml += '<div class="form-group ml-2 w-100 d-flex extra-time">';
                        previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>	';
                        previewHtml += '<br>';
                        previewHtml += '<label class="option m-3">';
                        previewHtml += '<span class="option-control">';
                        previewHtml += '<span class="radio">';
                        previewHtml += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="Yes" checked="checked">';
                        previewHtml += '<span></span>';
                        previewHtml += '</span>';
                        previewHtml += '</span>';
                        previewHtml += '<span class="option-label">';
                        previewHtml += '<span class="option-head">';
                        previewHtml += '<span class="option-title">Yes</span>';
                        previewHtml += '</span>';
                        previewHtml += '</span>';
                        previewHtml += '</label>';
                        previewHtml += '<label class="option m-3">';
                        previewHtml += '<span class="option-control">';
                        previewHtml += '<span class="radio">';
                        previewHtml += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="No">';
                        previewHtml += '<span></span>';
                        previewHtml += '</span>';
                        previewHtml += '</span>';
                        previewHtml += '<span class="option-label">';
                        previewHtml += '<span class="option-head">';
                        previewHtml += '<span class="option-title">';
                        previewHtml += 'No';
                        previewHtml += '</span>';
                        previewHtml += '</span>';
                        previewHtml += '</label>';
                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).append(previewHtml);
                    } else {

                        previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
                        previewHtml += '<div class="form-group ml-2 w-100 d-flex extra-time">';
                        previewHtml += '<label class="font-weight-bolder customQuestionLabel">' + question + '</label>	';
                        previewHtml += '<br>';
                        previewHtml += '<label class="option m-3">';
                        previewHtml += '<span class="option-control">';
                        previewHtml += '<span class="radio">';
                        previewHtml += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="Yes" checked="checked">';
                        previewHtml += '<span></span>';
                        previewHtml += '</span>';
                        previewHtml += '</span>';
                        previewHtml += '<span class="option-label">';
                        previewHtml += '<span class="option-head">';
                        previewHtml += '<span class="option-title">Yes</span>';
                        previewHtml += '</span>';
                        previewHtml += '</span>';
                        previewHtml += '</label>';
                        previewHtml += '<label class="option m-3">';
                        previewHtml += '<span class="option-control">';
                        previewHtml += '<span class="radio">';
                        previewHtml += '<input type="radio" name="yesNoQue' + subUniqueID + '" value="No">';
                        previewHtml += '<span></span>';
                        previewHtml += '</span>';
                        previewHtml += '</span>';
                        previewHtml += '<span class="option-label">';
                        previewHtml += '<span class="option-head">';
                        previewHtml += '<span class="option-title">';
                        previewHtml += 'No';
                        previewHtml += '</span>';
                        previewHtml += '</span>';
                        previewHtml += '</label>';
                        previewHtml += '</div>';
                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId).append(previewHtml);
                    }

                } else if (questionType == 'des') {

                    var question = groupbyarray[keys][j]['question'];
                    var questionAnswers = groupbyarray[keys][j]['8ans'];

                    // var customField = '';
                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<label class="font-weight-bolder">Text description';
                    // subSectionHtml += questionAnswers;
                    subSectionHtml += '</label>';
                    subSectionHtml += '<textarea type="text" class="form-control questionAnswers" name="answerField[' + sectionId + '][' + subUniqueID + '][]" rows="5">' + questionAnswers + '</textarea>';
                    subSectionHtml += '</div>';

                    // $("#" + subUniqueID + " .customQuestionDiv").html(customField);

                    var previewHtml = '';
                    if ($(".previewSection" + subUniqueID).length > 0) {

                        previewHtml += '<label class="font-weight-bolder">Text description';
                        previewHtml += questionAnswers;
                        previewHtml += '</label>';
                        previewHtml += '<textarea type="text" class="form-control questionAnswers" name="answerField[' + sectionId + '][' + subUniqueID + '][]" rows="5">' + questionAnswers + '</textarea>';

                        $(".sectionPreviewTab" + sectionId + " .previewSection" + subUniqueID).append(previewHtml);
                    } else {

                        previewHtml += '<div class=" previewSubSectionnext' + subUniqueID + ' form-group previewSection' + subUniqueID + '" id="previewSubSectionnext' + subUniqueID + '">';
                        previewHtml += '<label class="font-weight-bolder">Text description';
                        previewHtml += '</label>';
                        previewHtml += '<textarea type="text" class="form-control questionAnswers" name="answerField[' + sectionId + '][' + subUniqueID + '][]" rows="5">' + questionAnswers + '</textarea>';
                        previewHtml += '</div>';

                        $(".sectionPreviewTab" + sectionId).append(previewHtml);
                    }
                }

                $("#" + subUniqueID + " .customQuestionLabel").text(getQuestion);

                // $("#" + subUniqueID + " .hideCustomQuestionDiv").show();
                // $("#" + subUniqueID + " .showCustomQuestionDiv").hide();
                //end

                subSectionHtml += '</div>';
                subSectionHtml += '<div class="card showCustomQuestionDiv" style="display:none;" data-subUniqueID="' + subCustomFields + j + '">';

                subSectionHtml += '<input type="hidden" class="subUniqueIDClass" name="subUniqueID[' + current_section + '][]" value="' + subCustomFields + j + '">';

                subSectionHtml += '<div class="card-body bg-content">';
                subSectionHtml += '<div class="d-flex justify-content-between">';
                subSectionHtml += '<div class="form-group mr-2 w-100">';
                subSectionHtml += '<label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label>';
                //subSectionHtml += '<div class="dropdown bootstrap-select form-control">';
                subSectionHtml += '<select class="form-control selectpickerelemtnt" tabindex="null" name="answer_type[' + current_section + '][]" onchange="getQuestionType(\'' + subCustomFields + j + '\',\'' + current_section + '\',this.value)">';
                if (groupbyarray[keys][j]['answer_type'] == 'shortAnswer') { subSectionHtml += '<option value="shortAnswer" selected ><i class="fas fa-grip-lines"></i> Short answer</option>'; } else { subSectionHtml += '<option value="shortAnswer" ><i class="fas fa-grip-lines"></i> Short answer</option>'; }
                if (groupbyarray[keys][j]['answer_type'] == 'longAnswer') { subSectionHtml += '<option value="longAnswer" selected ><i class="fas fa-align-left"></i> Long answer</option>'; } else { subSectionHtml += '<option value="longAnswer" ><i class="fas fa-grip-lines"></i> Long answer</option>'; }
                if (groupbyarray[keys][j]['answer_type'] == 'singleAnswer') { subSectionHtml += '<option value="singleAnswer" selected ><i class="far fa-dot-circle"></i> Single answer</option>'; } else { subSectionHtml += '<option value="singleAnswer" ><i class="fas fa-grip-lines"></i> Single answer</option>'; }
                if (groupbyarray[keys][j]['answer_type'] == 'singleCheckbox') { subSectionHtml += '<option value="singleCheckbox" selected ><i class="far fa-check-square"></i> Single checkbox</option>'; } else { subSectionHtml += '<option value="singleCheckbox" ><i class="fas fa-grip-lines"></i> Single checkbox</option>'; }
                if (groupbyarray[keys][j]['answer_type'] == 'multipleCheckbox') { subSectionHtml += '<option value="multipleCheckbox" selected ><i class="fas fa-tasks"></i> Multiple choice</option>'; } else { subSectionHtml += '<option value="multipleCheckbox" ><i class="fas fa-grip-lines"></i> Multiple choice</option>'; }
                if (groupbyarray[keys][j]['answer_type'] == 'dropdown') { subSectionHtml += '<option value="dropdown" selected ><i class="far fa-caret-square-down"></i> Drop-down</option>'; } else { subSectionHtml += '<option value="dropdown" ><i class="fas fa-grip-lines"></i> Drop-down</option>'; }
                if (groupbyarray[keys][j]['answer_type'] == 'yesOrNo') { subSectionHtml += '<option value="yesOrNo" selected ><i class="fas fa-adjust"></i> Yes or No</option>'; } else { subSectionHtml += '<option value="yesOrNo" ><i class="fas fa-grip-lines"></i> Yes or No</option>'; }
                if (groupbyarray[keys][j]['answer_type'] == 'des') { subSectionHtml += '<option value="des" selected ><i class="far fa-font-case"></i> Description text</option>'; } else { subSectionHtml += '<option value="des" ><i class="fas fa-grip-lines"></i> Description text</option>'; }
                subSectionHtml += '</select>';
                //subSectionHtml += '</div>';
                subSectionHtml += '</div>';
                subSectionHtml += '<div class="form-group ml-2 w-100">';
                subSectionHtml += '<label class="font-weight-bolder" for="exampleSelect1">Question</label>';
                if (questionType == 'des') {
                    subSectionHtml += '<input type="text" class="form-control customQuestionField" readonly name="question[' + current_section + '][]" placeholder="New question" data-isDescriptive="Yes">';
                } else {
                    subSectionHtml += '<input type="text" class="form-control customQuestionField" name="question[' + current_section + '][]" value="' + groupbyarray[keys][j]['question'] + '" placeholder="New question" data-isDescriptive="No">';
                }
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';

                subSectionHtml += '<div class="optionAnwersDiv">';
                //qna ans
                if (questionType == 'shortAnswer') {

                    var question = groupbyarray[keys][j]['question']; //$("#" + uniqueid + " .customQuestionField").val();

                    // var customField = '';
                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<label class="font-weight-bolder customQuestionLabel">';
                    subSectionHtml += question;
                    subSectionHtml += '</label>';
                    subSectionHtml += '<input type="text" class="form-control">';
                    subSectionHtml += '</div>';

                    // $("#" + uniqueid + " .customQuestionDiv").html(customField);

                } else if (questionType == 'longAnswer') {

                    var question = groupbyarray[keys][j]['question']; //$("#" + uniqueid + " .customQuestionField").val();

                    // var customField = '';
                    // subSectionHtml += '<div class="form-group">';
                    // subSectionHtml += '<label class="font-weight-bolder customQuestionLabel">';
                    // subSectionHtml += question;
                    // subSectionHtml += '</label>';
                    // subSectionHtml += '<textarea class="form-control"></textarea>';
                    // subSectionHtml += '</div>';

                    // $("#" + uniqueid + " .customQuestionDiv").html(customField);

                } else if (questionType == 'singleAnswer') {

                    var ansdata = JSON.parse(groupbyarray[keys][j]['3ans']);
                    var lenans = JSON.parse(groupbyarray[keys][j]['3ans']).length;

                    if (lenans <= 1) { lenans = 1; } else { lenans = lenans - 1; };

                    subSectionHtml += '<div class="appendNewAnswersDiv">';

                    for (var A = 0; A <= lenans; A++) {
                        subSectionHtml += '<div class="d-flex justify-content-between">';
                        subSectionHtml += '<div class="form-group ml-2 w-100">';
                        subSectionHtml += '<label class="font-weight-bolder">Answer ' + A + '</label>';
                        subSectionHtml += '<input type="text" class="form-control questionAnswers" value="' + ansdata[A] + '" name="answerField[' + current_section + '][' + subCustomFields + j + '][]">';
                        subSectionHtml += '</div>';
                        subSectionHtml += '</div>';
                    }
                    subSectionHtml += '</div>';

                    subSectionHtml += '<div>';
                    subSectionHtml += '<span class="cursor-pointer text-blue addNewQuestionAnswers" data-subUniqueID="' + subCustomFields + j + '" data-sectionId="' + current_section + '"><i class="text-blue fa fa-plus mr-3"></i>Add next answer</span>';
                    subSectionHtml += '</div>';

                    // $("#" + uniqueid + " .optionAnwersDiv").html(answersHtml);

                } else if (questionType == 'singleCheckbox') {

                    var question = groupbyarray[keys][j]['question']; //$("#" + uniqueid + " .customQuestionField").val();

                    // var customField = '';

                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<div class="checkbox-list">';
                    subSectionHtml += '<label class="checkbox"><input type="checkbox" name="Checkboxes' + subCustomFields + j + '"><span></span> <p class="customQuestionLabel">' + question + '</p></label>';
                    subSectionHtml += '</div>';
                    subSectionHtml += '</div>';
                    subSectionHtml += '</div>';

                    // $("#" + subCustomFields + j + " .customQuestionDiv").html(customField);

                } else if (questionType == 'multipleCheckbox') {

                    // var answersHtml = '';
                    var ansdata = JSON.parse(groupbyarray[keys][j]['5ans']);
                    var lenans = JSON.parse(groupbyarray[keys][j]['5ans']).length;

                    if (lenans <= 1) { lenans = 1; } else { lenans = lenans - 1; };

                    subSectionHtml += '<div class="appendNewAnswersDiv">';

                    for (var A = 0; A <= lenans; A++) {
                        subSectionHtml += '<div class="d-flex justify-content-between">';
                        subSectionHtml += '<div class="form-group ml-2 w-100">';
                        subSectionHtml += '<label class="font-weight-bolder">Answer ' + A + '</label>';
                        subSectionHtml += '<input type="text" class="form-control questionAnswers" value="' + ansdata[A] + '" name="answerField[' + current_section + '][' + subCustomFields + j + '][]">';
                        subSectionHtml += '</div>';
                        subSectionHtml += '</div>';
                    }
                    subSectionHtml += '</div>';

                    subSectionHtml += '<div>';
                    subSectionHtml += '<span class="cursor-pointer text-blue addNewQuestionAnswers" data-subUniqueID="' + subCustomFields + j + '" data-sectionId="' + current_section + '"><i class="text-blue fa fa-plus mr-3"></i>Add next answer</span>';
                    subSectionHtml += '</div>';

                    // $("#" + subCustomFields + j + " .optionAnwersDiv").html(answersHtml);

                } else if (questionType == 'dropdown') {

                    // var answersHtml = '';
                    var ansdata = JSON.parse(groupbyarray[keys][j]['5ans']);
                    var lenans = JSON.parse(groupbyarray[keys][j]['5ans']).length;

                    subSectionHtml += '<div class="appendNewAnswersDiv">';
                    for (var A = 0; A <= lenans; A++) {

                        subSectionHtml += '<div class="d-flex justify-content-between">';
                        subSectionHtml += '<div class="form-group ml-2 w-100">';
                        subSectionHtml += '<label class="font-weight-bolder">Answer ' + A + '</label>';
                        subSectionHtml += '<input type="text" class="form-control questionAnswers" value="' + ansdata[A] + '" name="answerField[' + current_section + '][' + subCustomFields + j + '][]">';
                        subSectionHtml += '</div>';
                        subSectionHtml += '</div>';
                    }
                    subSectionHtml += '</div>';

                    subSectionHtml += '<div>';
                    subSectionHtml += '<span class="cursor-pointer text-blue addNewQuestionAnswers" data-subUniqueID="' + subCustomFields + j + '" data-sectionId="' + current_section + '><i class="text-blue fa fa-plus mr-3"></i>Add next answer</span>';
                    subSectionHtml += '</div>';

                    // $("#" + subCustomFields + j + " .optionAnwersDiv").html(answersHtml);

                } else if (questionType == 'yesOrNo') {

                    var question = groupbyarray[keys][j]['question']; //$("#" + uniqueid + " .customQuestionField").val();

                    // var customField = '';
                    subSectionHtml += '<div class="form-group">';
                    subSectionHtml += '<div class="form-group ml-2 w-100 d-flex extra-time">';
                    subSectionHtml += '<label class="font-weight-bolder customQuestionLabel">' + subCustomFields + j + '</label>	';
                    subSectionHtml += '<br>';
                    subSectionHtml += '<label class="option m-3">';
                    subSectionHtml += '<span class="option-control">';
                    subSectionHtml += '<span class="radio">';
                    subSectionHtml += '<input type="radio" name="yesNoQue' + subCustomFields + j + '" value="Yes" checked="checked">';
                    subSectionHtml += '<span></span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '<span class="option-label">';
                    subSectionHtml += '<span class="option-head">';
                    subSectionHtml += '<span class="option-title">Yes</span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '</label>';
                    subSectionHtml += '<label class="option m-3">';
                    subSectionHtml += '<span class="option-control">';
                    subSectionHtml += '<span class="radio">';
                    subSectionHtml += '<input type="radio" name="yesNoQue' + subCustomFields + j + '" value="No">';
                    subSectionHtml += '<span></span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '<span class="option-label">';
                    subSectionHtml += '<span class="option-head">';
                    subSectionHtml += '<span class="option-title">';
                    subSectionHtml += 'No';
                    subSectionHtml += '</span>';
                    subSectionHtml += '</span>';
                    subSectionHtml += '</label>';
                    subSectionHtml += '</div>';
                    subSectionHtml += '</div>';

                    // $("#" + uniqueid + " .customQuestionDiv").html(customField);

                } else if (questionType == 'des') {

                    // var answersHtml = '';

                    subSectionHtml += '<div class="appendNewAnswersDiv">';
                    subSectionHtml += '<div class="d-flex justify-content-between">';
                    subSectionHtml += '<div class="form-group ml-2 w-100">';
                    subSectionHtml += '<label class="font-weight-bolder">Text description</label>';
                    subSectionHtml += '<textarea type="text" class="form-control questionAnswers" name="answerField[' + sectionId + '][' + subUniqueID + j + '][]" rows="5">' + groupbyarray[keys][j]['8ans'] + '</textarea>';
                    subSectionHtml += '</div>';
                    subSectionHtml += '</div>';
                    subSectionHtml += '</div>';

                    // $("#" + uniqueid + " .optionAnwersDiv").html(answersHtml);
                    $("#" + subCustomFields + j + " .customQuestionField").val('');
                    $("#" + subCustomFields + j + " .customQuestionField").attr('readonly', true);

                    $("#" + subCustomFields + j + " .customQuestionField").removeAttr('data-isDescriptive');
                    $("#" + subCustomFields + j + " .customQuestionField").attr('data-isDescriptive', 'Yes');
                }
                //end
                subSectionHtml += '</div>';

                subSectionHtml += '<hr>';
                subSectionHtml += '<div class="d-flex flex-wrap justify-content-between align-items-center">';
                subSectionHtml += '<div class="">';
                subSectionHtml += '<div class="form-group mb-0">';

                subSectionHtml += '<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">';
                subSectionHtml += '<label class="d-flex align-item-center font-weight-bolder">';
                subSectionHtml += '<input type="checkbox" class="isRequiredFieldCheck" checked="checked" name="is_required[' + current_section + '][]" data-subuniqueid="' + subCustomFields + j + '" data-uniqueid="' + unique_id + '" data-sectionId="' + current_section + '">';
                subSectionHtml += '<input type="hidden" class="isRequiredFieldValue" id="requiredValueField' + subCustomFields + j + '" name="is_required_value[' + current_section + '][]" value="1">';
                subSectionHtml += '<span></span>&nbsp;Required';
                subSectionHtml += '</label>';
                subSectionHtml += '</div>';

                subSectionHtml += '</div>';
                subSectionHtml += '</div>';
                subSectionHtml += '<div class="d-flex align-items-center">';
                subSectionHtml += '<span class="border-right p-3">';
                subSectionHtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToUpper">';
                subSectionHtml += '<i class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i>';
                subSectionHtml += '</button>';
                subSectionHtml += '<button type="button" class="mx-2 btn btn-sm btn-white chageToLower">';
                subSectionHtml += '<i class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i>';
                subSectionHtml += '</button>';
                subSectionHtml += '</span>';
                subSectionHtml += '<span class="border-right p-3">';
                subSectionHtml += '<button type="button" class="mx-2 btn btn-sm btn-white cloneSubCustomField" data-sectionId="' + current_section + '">';
                subSectionHtml += '<i class="mx-1 far fa-clone fa-2x"></i>';
                subSectionHtml += '</button>';
                subSectionHtml += '<button type="button" class="mx-2 btn btn-sm btn-white removeSubCustomField" data-subUniqueID="' + subCustomFields + j + '" data-sectionId="' + current_section + '" data-uniqueid="' + unique_id + '">';
                subSectionHtml += '<i class="mx-1 fas fa-trash fa-2x text-danger"></i>';
                subSectionHtml += '</button>';
                subSectionHtml += '</span>';
                subSectionHtml += '<span class="p-3">';
                subSectionHtml += '<button type="button" class="mx-2 btn btn-sm btn-white closeCompleteSection" data-subUniqueID="' + subCustomFields + j + '" data-sectionId="' + current_section + '">';
                subSectionHtml += '<i class="p-0 fa fa-times"></i>';
                subSectionHtml += '</button>';
                subSectionHtml += '<button type="button" class="mx-2 btn btn-sm btn-primary markCompleteSection" data-subUniqueID="' + subCustomFields + j + '" data-sectionId="' + current_section + '">';
                subSectionHtml += '<i class="p-0 fas fa-check"></i>';
                subSectionHtml += '</button>';
                subSectionHtml += '</span>';
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';
                subSectionHtml += '</div>';
            }
            subSectionHtml += '</div>';

            subSectionHtml += '<div class="card-footer">';
            subSectionHtml += '<span class="cursor-pointer text-blue addNewQuestionClick" data-toggle="modal" data-target="#addNewQuestionModal" data-uniqueid="' + unique_id + '" data-sectionId="' + current_section + '">';
            subSectionHtml += '<i class="text-blue fa fa-plus mr-3"></i>Add a new Question or item';
            subSectionHtml += '</span>';
            subSectionHtml += '</div>';
            subSectionHtml += '</div>';

            $(".appendTheContentHere").append(subSectionHtml);

            //$('.selectpicker').selectpicker();
            $("#addSubSectionForm")[0].reset();

            //end
        }
        if (groupbyarray[keys][0]['first_name']) {

            //show client data
            var addClientSectionTitle = groupbyarray[keys][0]['section_title'];
            var addClientSectionDescription = groupbyarray[keys][0]['section_des'];
            var clientSectionFirstName = groupbyarray[keys][0]['first_name'];
            var clientSectionLastName = groupbyarray[keys][0]['last_name'];
            var clientSectionEmail = groupbyarray[keys][0]['email'];
            var clientSectionBirthday = groupbyarray[keys][0]['birthday'];
            var clientSectionMobileNumber = groupbyarray[keys][0]['mobile'];
            var clientSectionGender = groupbyarray[keys][0]['gender'];
            var clientSectionAddress = groupbyarray[keys][0]['address'];

            $(".defaultView").hide();
            $(".afterView").show();
            $("#addClientDetailModal").modal('hide');

            $(".detailsFormBtn").attr('disabled', false);

            var unique_id = $("#clientUniqueDivID").val();
            if (unique_id == '') {
                var unique_id = makeid(15);
                $("#clientUniqueDivID").val(unique_id);
            }

            var clientInfoHtml = '';

            var checkClientFormIncluded = $(".clientFormIncluded").length;

            if (checkClientFormIncluded == 0) {
                var current_section = (parseInt($("#total_section").val())) + 1;
                $("#total_section").val(current_section);
                var total_section = $("#total_section").val();
                $(".TotalSection").html(total_section);

                clientInfoHtml += '<div class="card w-70 mx-auto mt-16 customFieldDivCount sectionIdentitiyClass' + current_section + '" id="' + unique_id + '">';
                clientInfoHtml += '<div class="my-card-lable-purple">Section <span class="CurrentSection">' + current_section + '</span> of <span class="TotalSection">' + total_section + '</span> : Client details</div>';

                clientInfoHtml += '<input type="hidden" name="section_id[]" value="' + current_section + '">';
                clientInfoHtml += '<input type="hidden" name="form_type[]" value="client_form">';
                clientInfoHtml += '<input type="hidden" name="form_id" value="form_id">';

                clientInfoHtml += '<div class="clientFormIncluded">';
            }
            clientInfoHtml += '<div class="card-header d-flex justify-content-between">';
            clientInfoHtml += '<div>';

            if (addClientSectionTitle != '') {
                clientInfoHtml += '<h3>' + addClientSectionTitle + '</h3>';
                clientInfoHtml += '<input type="hidden" name="clientFromSectionTitle" value="' + addClientSectionTitle + '">';
            }

            if (addClientSectionDescription != '') {
                clientInfoHtml += '<p class="m-0 text-dakr-50">' + addClientSectionDescription + '</p>';
                clientInfoHtml += '<input type="hidden" name="clientFromSectionDesc" value="' + addClientSectionDescription + '">';
            }

            clientInfoHtml += '</div>';
            clientInfoHtml += '<div class="dropdown dropdown-inline">';
            clientInfoHtml += '<a href="#" class="btn btn-clean text-dark btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            clientInfoHtml += '<i class="ki ki-bold-more-ver text-dark"></i>';
            clientInfoHtml += '</a>';
            clientInfoHtml += '<div class="dropdown-menu dropdown-menu-right text-center">';
            clientInfoHtml += '<ul class="navi navi-hover">';
            clientInfoHtml += '<li class="navi-item">';
            clientInfoHtml += '<a href="javascript:;" class="navi-link" data-toggle="modal" data-target="#addClientDetailModal">';
            clientInfoHtml += '<span class="navi-text">Edit</span>';
            clientInfoHtml += '</a>';
            clientInfoHtml += '</li>';
            clientInfoHtml += '<li class="navi-item">';
            clientInfoHtml += '<a href="javascript:;" class="navi-link removeMe" data-deleteId="' + unique_id + '">';
            clientInfoHtml += '<span class="navi-text text-danger">Delete</span>';
            clientInfoHtml += '</a>';
            clientInfoHtml += '</li>';
            clientInfoHtml += '</ul>';
            clientInfoHtml += '</div>';
            clientInfoHtml += '</div>';
            clientInfoHtml += '</div>';
            clientInfoHtml += '<div class="card-body">';

            if (clientSectionFirstName == 1) {
                clientInfoHtml += '<div class="form-group">';
                clientInfoHtml += '<label class="font-weight-bolder">First name</label>';
                clientInfoHtml += '<input type="text" disabled class="form-control">';
                clientInfoHtml += '<input type="hidden" class="form-control" name="first_name" id="first_name" value="1">';
                clientInfoHtml += '</div>';
            } else {
                clientInfoHtml += '<input type="hidden" class="form-control" name="first_name" id="first_name" value="0">';
            }

            if (clientSectionLastName == 1) {
                clientInfoHtml += '<div class="form-group">';
                clientInfoHtml += '<label class="font-weight-bolder">Last name</label>';
                clientInfoHtml += '<input type="text" disabled class="form-control">';
                clientInfoHtml += '<input type="hidden" class="form-control" name="last_name" id="last_name" value="1">';
                clientInfoHtml += '</div>';
            } else {
                clientInfoHtml += '<input type="hidden" class="form-control" name="last_name" id="last_name" value="0">';
            }

            if (clientSectionEmail == 1) {
                clientInfoHtml += '<div class="form-group">';
                clientInfoHtml += '<label class="font-weight-bolder">Email</label>';
                clientInfoHtml += '<input type="email" disabled class="form-control">';
                clientInfoHtml += '<input type="hidden" class="form-control" name="email" id="email" value="1">';
                clientInfoHtml += '</div>';
            } else {
                clientInfoHtml += '<input type="hidden" class="form-control" name="email" id="email" value="0">';
            }

            if (clientSectionBirthday == 1) {
                clientInfoHtml += '<div class="form-group">';
                clientInfoHtml += '<label class="font-weight-bolder">Birthday</label>';
                clientInfoHtml += '<input type="date" disabled class="form-control">';
                clientInfoHtml += '<input type="hidden" class="form-control" name="birthday" id="birthday" value="1">';
                clientInfoHtml += '</div>';
            } else {
                clientInfoHtml += '<input type="hidden" class="form-control" name="birthday" id="birthday" value="0">';
            }

            if (clientSectionMobileNumber == 1) {
                clientInfoHtml += '<div class="form-group selectCountryCodeClass">';
                clientInfoHtml += '<label class="font-weight-bolder">Mobile Number</label>';
                clientInfoHtml += '<input type="text" class="form-control" id="countryCodeSelection">';
                clientInfoHtml += '<input type="hidden" class="form-control" name="mobile" id="mobile" value="1">';
                clientInfoHtml += '</div>';
            } else {
                clientInfoHtml += '<input type="hidden" class="form-control" name="mobile" id="mobile" value="0">';
            }

            if (clientSectionGender == 1) {
                clientInfoHtml += '<div class="form-group">';
                clientInfoHtml += '<label class="font-weight-bolder">Gender</label>';
                clientInfoHtml += '<div class="dropdown">';
                clientInfoHtml += '<select class="custom-select" disabled>';
                clientInfoHtml += '<option selected>Select Option</option>';
                clientInfoHtml += '<option>Male</option>';
                clientInfoHtml += '<option>Female</option>';
                clientInfoHtml += '<option>Other</option>';
                clientInfoHtml += '<option>I dont want to share</option>';
                clientInfoHtml += '</select>';
                clientInfoHtml += '<input type="hidden" class="form-control" name="gender" id="gender" value="1">';
                clientInfoHtml += '</div>';
                clientInfoHtml += '</div>';
            } else {
                clientInfoHtml += '<input type="hidden" class="form-control" name="gender" id="gender" value="0">';
            }

            if (clientSectionAddress == 1) {
                clientInfoHtml += '<div class="form-group">';
                clientInfoHtml += '<label class="font-weight-bolder">Address</label>';
                clientInfoHtml += '<textarea rows="4" disabled class="form-control"></textarea>';
                clientInfoHtml += '<input type="hidden" class="form-control" name="address" id="address" value="1">';
                clientInfoHtml += '</div>';
            } else {
                clientInfoHtml += '<input type="hidden" class="form-control" name="address" id="address" value="0">';
            }


            if (checkClientFormIncluded == 0) {
                clientInfoHtml += '</div>';
                clientInfoHtml += '</div>';
                clientInfoHtml += '</div>';
            }

            if (checkClientFormIncluded == 0) {
                $(".appendTheContentHere").append(clientInfoHtml);
            } else {
                $(".clientFormIncluded").html(clientInfoHtml);
            }

            var sectionPreview = '';
            var sectionFormPreview = '';

            if ($(".clientInforPreview").length == 0) {
                $(".clientInforPreview").remove();
                $(".clientInforForm").remove();

                sectionPreview += '<div class="preview-tab clientInforPreview ' + unique_id + '" data-sectionTitle="' + addClientSectionTitle + '" data-sectionDescription="' + addClientSectionDescription + '">';
                sectionFormPreview += '<div class="forms-tab clientInforForm ' + unique_id + '" data-sectionTitle="' + addClientSectionTitle + '" data-sectionDescription="' + addClientSectionDescription + '">';
            }
            sectionPreview += '<div class="card-body">';
            sectionFormPreview += '<div class="card-body">';

            if ($("#clientSectionFirstName").is(':checked')) {
                sectionPreview += '<div class="form-group">';
                sectionPreview += '<label class="font-weight-bolder">First name</label>';
                sectionPreview += '<input type="text" class="form-control">';
                sectionPreview += '</div>';

                sectionFormPreview += '<div class="form-group">';
                sectionFormPreview += '<label class="font-weight-bolder">First name</label>';
                sectionFormPreview += '<input type="text" class="form-control">';
                sectionFormPreview += '</div>';
            }

            if ($("#clientSectionLastName").is(':checked')) {
                sectionPreview += '<div class="form-group">';
                sectionPreview += '<label class="font-weight-bolder">Last name</label>';
                sectionPreview += '<input type="text" class="form-control">';
                sectionPreview += '</div>';

                sectionFormPreview += '<div class="form-group">';
                sectionFormPreview += '<label class="font-weight-bolder">Last name</label>';
                sectionFormPreview += '<input type="text" class="form-control">';
                sectionFormPreview += '</div>';
            }

            if ($("#clientSectionEmail").is(':checked')) {
                sectionPreview += '<div class="form-group">';
                sectionPreview += '<label class="font-weight-bolder">Email</label>';
                sectionPreview += '<input type="email" class="form-control">';
                sectionPreview += '</div>';

                sectionFormPreview += '<div class="form-group">';
                sectionFormPreview += '<label class="font-weight-bolder">Email</label>';
                sectionFormPreview += '<input type="email" class="form-control">';
                sectionFormPreview += '</div>';
            }

            if ($("#clientSectionBirthday").is(':checked')) {
                sectionPreview += '<div class="form-group">';
                sectionPreview += '<label class="font-weight-bolder">Birthday</label>';
                sectionPreview += '<input type="date" class="form-control">';
                sectionPreview += '</div>';

                sectionFormPreview += '<div class="form-group">';
                sectionFormPreview += '<label class="font-weight-bolder">Birthday</label>';
                sectionFormPreview += '<input type="date" class="form-control">';
                sectionFormPreview += '</div>';
            }

            if ($("#clientSectionMobileNumber").is(':checked')) {
                sectionPreview += '<div class="form-group selectCountryCodeClass">';
                sectionPreview += '<label class="font-weight-bolder">Mobile Number</label>';
                sectionPreview += '<input type="text" class="form-control countryCodeSelectionPreview">';
                sectionPreview += '</div>';

                sectionFormPreview += '<div class="form-group selectCountryCodeClass">';
                sectionFormPreview += '<label class="font-weight-bolder">Mobile Number</label>';
                sectionFormPreview += '<input type="text" class="form-control countryCodeSelectionPreview">';
                sectionFormPreview += '</div>';
            }

            if ($("#clientSectionGender").is(':checked')) {
                sectionPreview += '<div class="form-group">';
                sectionPreview += '<label class="font-weight-bolder">Gender</label>';
                sectionPreview += '<div class="dropdown">';
                sectionPreview += '<select class="custom-select">';
                sectionPreview += '<option selected>Select Option</option>';
                sectionPreview += '<option>Male</option>';
                sectionPreview += '<option>Female</option>';
                sectionPreview += '<option>Other</option>';
                sectionPreview += '<option>I dont want to share</option>';
                sectionPreview += '</select>';
                sectionPreview += '</div>';
                sectionPreview += '</div>';

                sectionFormPreview += '<div class="form-group">';
                sectionFormPreview += '<label class="font-weight-bolder">Gender</label>';
                sectionFormPreview += '<div class="dropdown">';
                sectionFormPreview += '<select class="custom-select">';
                sectionFormPreview += '<option selected>Select Option</option>';
                sectionFormPreview += '<option>Male</option>';
                sectionFormPreview += '<option>Female</option>';
                sectionFormPreview += '<option>Other</option>';
                sectionFormPreview += '<option>I dont want to share</option>';
                sectionFormPreview += '</select>';
                sectionFormPreview += '</div>';
                sectionFormPreview += '</div>';
            }

            if ($("#clientSectionAddress").is(':checked')) {
                sectionPreview += '<div class="form-group">';
                sectionPreview += '<label class="font-weight-bolder">Address</label>';
                sectionPreview += '<textarea rows="4" class="form-control"></textarea>';
                sectionPreview += '</div>';

                sectionFormPreview += '<div class="form-group">';
                sectionFormPreview += '<label class="font-weight-bolder">Address</label>';
                sectionFormPreview += '<textarea rows="4" class="form-control"></textarea>';
                sectionFormPreview += '</div>';
            }

            sectionPreview += '</div>';
            sectionFormPreview += '</div>';

            if ($(".clientInforPreview").length == 0) {
                sectionPreview += '</div>';
                sectionFormPreview += '</div>';

                $(".sectionTabDiv").append(sectionPreview);
                $(".sectionTabDivForm").append(sectionFormPreview);
            } else {
                $(".clientInforPreview").html(sectionPreview);
                $(".clientInforForm").html(sectionFormPreview);
            }

            showPreviewTab(0);
            showFormsTab(0);

            var phone_number = window.intlTelInput(document.querySelector("#countryCodeSelection"), {
                separateDialCode: true,
                preferredCountries: ["ca"],
                hiddenInput: "full",
                utilsScript: $("#utilScript").val()
            });

            var phone_number = window.intlTelInput(document.querySelector(".countryCodeSelectionPreview"), {
                separateDialCode: true,
                preferredCountries: ["ca"],
                hiddenInput: "full",
                utilsScript: $("#utilScript").val()
            });
            //end
        }
    });




});