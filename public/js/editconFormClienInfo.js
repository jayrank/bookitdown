"use strict";
function makeid(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
	  result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}

// Class Definition
var KTLogin = function(ischeck) {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';

    var _handleClientCreate = function() {
        var form = KTUtil.getById('saveClientinfo');
        var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('saveClient');

        if (!form) {
            return;
        }

        FormValidation
            .formValidation(
                form, {
                    fields: {
                        section_title: {
                            validators: {
                                notEmpty: {
                                    message: 'Title is required'
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
                    //Set the Valid Flag to True if at-least one CheckBox is checked.
                }, 2000);
                checked = $(".checkbox input[type=checkbox]:checked").length;

                if (checked > 0) {
					
					var country_code_list = $("#country_code_list").html();
					
                    $("#spnError").hide();
                    ischeck = 2;
                    if (ischeck == 2) {
                        $('#addClientDetailModal').modal('hide');
                        $('#ClientInfoShow').show();
                        $('#builderclient').show();
                        $('.customse').show();
                        // $('.setp1').text(setp2);
                        // $('.setp2').text(setp2);
                        $('.cusStep2').text(setp2);
                        setp1 = setp2;
                        if (temp == 1) {
                            var html = '';
                            html += '<div id="builderclient" role="tabpanel" aria-labelledby="builder-tab-1 sectionHtml">';
                            // html += '<div class="card w-70 mx-auto mt-16">';
                            html += '<div class="my-card-lable-purple" style="position: relative;max-width: 35%;">';
                            html += 'Section <span class="setp1">' + setp1 + '</span> of <span class="setp2">' + setp2 + '</span> Client details';
                            html += '</div>';
                            html += '<div class="card-header d-flex justify-content-between">';
                            // html += '<input type="hidden" name="clisectionid" id="sectionid" value="' + sectionid + '">';    
                            html += '<div>';
                            html += '<h3><span class="title" ></span></h3>';
                            html += '<span class="m-0 text-dakr-50 sectionDes" ></span>';
                            html += '</div>';
                            html += '<div class="dropdown dropdown-inline">';
                            html += '<a href="#" class="btn btn-clean text-dark btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true"aria-expanded="false">';
                            html += '<i class="ki ki-bold-more-ver text-dark"></i>';
                            html += '</a>';
                            html += '<div class="dropdown-menu dropdown-menu-right text-center">';
                            html += '<ul class="navi navi-hover">';
                            html += '<li class="navi-item edit" data-toggle="modal" data-target="#addClientDetailModal">';
                            html += '<a class="navi-link">';
                            html += '<span class="navi-text"> Edit </span>';
                            html += '</a>';
                            html += '</li>';
                            html += '<li class="navi-item">';
                            html += '<a class="navi-link" id="deleteClinemo">';
                            html += '<span class="navi-text text-danger"> Delete </span>';
                            html += '</a>';
                            html += '</li>';
                            html += '</ul>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="card-body">';
                            html += '<div class="form-group fnameshow" style="display: none">';
                            html += '<label class="font-weight-bolder">';
                            html += 'First name';
                            html += '</label>';
                            html += '<input type="text" disabled class="form-control" >';
                            html += '</div>';

                            html += '<div class="form-group lnameshow" style="display: none">';
                            html += '<label class="font-weight-bolder">';
                            html += 'Last name';
                            html += '</label>';
                            html += '<input type="text" disabled class="form-control" >';
                            html += '</div>';

                            html += '<div class="form-group emailshow" style="display: none">';
                            html += '<label class="font-weight-bolder">';
                            html += 'Email';
                            html += '</label>';
                            html += '<input type="email" disabled class="form-control" >';
                            html += '</div>';

                            html += '<div class="form-group showbirthday" style="display: none">';
                            html += '<label class="font-weight-bolder">';
                            html += 'Brithday';
                            html += '</label>';
                            html += '<input type="date" disabled class="form-control" >';
                            html += '</div>';

							html += '<div class="form-group showmobile" style="display: none">';
                            html += '<label class="font-weight-bolder">';
                            html += 'Country Code';
                            html += '</label>';
                            html += '<select class="form-control" name="country_code_select" id="country_code_select">'+country_code_list+'</select>';
                            html += '</div>';

                            html += '<div class="form-group showmobile" style="display: none">';
                            html += '<label class="font-weight-bolder">';
                            html += 'Mobile Number';
                            html += '</label>';
                            html += '<input type="text" disabled class="form-control" >';
                            html += '</div>';

                            html += '<div class="form-group showgender" style="display: none">';
                            html += '<label class="font-weight-bolder">';
                            html += 'Gender';
                            html += '</label>';
                            html += '<select readonly><option value="">Please select</option><option value="Male">Male</option><option value="Female">Female</option><option value="Other">Other</option><option value="I dont want to share">I dont want to share</option></select>';
                            html += '</div>';

                            html += '<div class="form-group showaddress" style="display: none">';
                            html += '<label class="font-weight-bolder">';
                            html += 'Address';
                            html += '</label>';
                            html += '<textarea rows="4" disabled class="form-control"></textarea>';
                            html += '</div>';
                            html += '</div>';
                            // html += '</div>';
                            html += '</div>';
                            $("#appenddata").append(html);
                            setp2++;
                            sectionid++;

                            //
                            var html2 = '';
                            html2 += '<div class="preview-tab">';
                            html2 += '<div class="card-body">';
                            html2 += '<div class="form-group fnameshow" style="display: none" >';
                            html2 += '<label class="font-weight-bolder">First name';
                            html2 += '</label>';
                            html2 += '<input type="text" class="form-control" name="fname">';
                            html2 += '</div>';

                            html2 += '<div class="form-group lnameshow" style="display: none" >';
                            html2 += '<label class="font-weight-bolder">Last name';
                            html2 += '</label>';
                            html2 += '<input type="text" class="form-control" name="lname">';
                            html2 += '</div>';

                            html2 += '<div class="form-group emailshow" style="display: none" >';
                            html2 += '<label class="font-weight-bolder">Email';
                            html2 += '</label>';
                            html2 += '<input type="email"  class="form-control" >';
                            html2 += '</div>';

                            html2 += '<div class="form-group showbirthday" style="display: none" >';
                            html2 += '<label class="font-weight-bolder">Brithday';
                            html2 += '</label>';
                            html2 += '<input type="date" class="form-control" >';
                            html2 += '</div>';

							html2 += '<div class="form-group showmobile" style="display: none">';
                            html2 += '<label class="font-weight-bolder">';
                            html2 += 'Country Code';
                            html2 += '</label>';
                            html2 += '<select class="form-control" name="country_code_select2" id="country_code_select2">'+country_code_list+'</select>';
                            html2 += '</div>';
							
                            html2 += '<div class="form-group showmobile" style="display: none" >';
                            html2 += '<label class="font-weight-bolder">Mobile Number';
                            html2 += '</label>';
                            html2 += '<input type="text" class="form-control" >';
                            html2 += '</div>';

                            html2 += '<div class="form-group showgender" style="display: none" >';
                            html2 += '<label class="font-weight-bolder">Gender';
                            html2 += '</label>';
                            html2 += '<select readonly><option value="">Please select</option><option value="Male">Male</option><option value="Female">Female</option><option value="Other">Other</option><option value="I dont want to share">I dont want to share</option></select>';
                            html2 += '</div>';

                            html2 += '<div class="form-group showaddress" style="display: none" >';
                            html2 += '<label class="font-weight-bolder">Address';
                            html2 += '</label>';
                            html2 += '<textarea rows="4" class="form-control"></textarea>';
                            html2 += '</div>';
                            html2 += '</div>';
                            html2 += '</div>';
                            //
                            $(".apppreview").append(html2);
                            //
                            var html3 = '';
                            html3 += '<div class="forms-tab">';
                            html3 += '<div class="card-body">';
                            html3 += '<div class="form-group fnameshow" style="display: none" >';
                            html3 += '<label class="font-weight-bolder">First name';
                            html3 += '</label>';
                            html3 += '<input type="text" class="form-control" name="fname">';
                            html3 += '</div>';

                            html3 += '<div class="form-group lnameshow" style="display: none" >';
                            html3 += '<label class="font-weight-bolder">Last name';
                            html3 += '</label>';
                            html3 += '<input type="text" class="form-control" name="lname">';
                            html3 += '</div>';

                            html3 += '<div class="form-group emailshow" style="display: none" >';
                            html3 += '<label class="font-weight-bolder">Email';
                            html3 += '</label>';
                            html3 += '<input type="email"  class="form-control" >';
                            html3 += '</div>';

                            html3 += '<div class="form-group showbirthday" style="display: none" >';
                            html3 += '<label class="font-weight-bolder">Brithday';
                            html3 += '</label>';
                            html3 += '<input type="date" class="form-control" >';
                            html3 += '</div>';
				
							html3 += '<div class="form-group showmobile" style="display: none">';
                            html3 += '<label class="font-weight-bolder">';
                            html3 += 'Country Code';
                            html3 += '</label>';
                            html3 += '<select class="form-control" name="country_code_select3" id="country_code_select3">'+country_code_list+'</select>';
                            html3 += '</div>';

                            html3 += '<div class="form-group showmobile" style="display: none" >';
                            html3 += '<label class="font-weight-bolder">Mobile Number';
                            html3 += '</label>';
                            html3 += '<input type="text" class="form-control" >';
                            html3 += '</div>';

                            html3 += '<div class="form-group showgender" style="display: none" >';
                            html3 += '<label class="font-weight-bolder">Gender';
                            html3 += '</label>';
                            html3 += '<select readonly><option value="">Please select</option><option value="Male">Male</option><option value="Female">Female</option><option value="Other">Other</option><option value="I dont want to share">I dont want to share</option></select>';
                            html3 += '</div>';

                            html3 += '<div class="form-group showaddress" style="display: none" >';
                            html3 += '<label class="font-weight-bolder">Address';
                            html3 += '</label>';
                            html3 += '<textarea rows="4" class="form-control"></textarea>';
                            html3 += '</div>';
                            html3 += '</div>';
                            html3 += '</div>';
                            $(".appendpre").append(html3);
                            $(".preview-tab").show();
                            $(".forms-tab").show();
                            //
                        }
                        temp++;
                        if ($('#section_title').val() != null) {
                            $('.title').text($('#section_title').val());
                        }
                        if ($('#section_des').val() != null) {
                            $('.sectionDes').text($('#section_des').val());
                        }
                        if ($('#first_name').prop("checked") == true) {
                            $('.fnameshow').show();
                        } else {
                            $('.fnameshow').hide();
                        }
                        if ($('#last_name').prop("checked") == true) {
                            $('.lnameshow').show();
                        } else {
                            $('.lnameshow').hide();
                        }
                        if ($('#email').prop("checked") == true) {
                            $('.emailshow').show();
                        } else {
                            $('.emailshow').hide();
                        }
                        if ($('#birthday').prop("checked") == true) {
                            $('.showbirthday').show();
                        } else {
                            $('.showbirthday').hide();
                        }
                        if ($('#mobile').prop("checked") == true) {
                            $('.showmobile').show();
                        } else {
                            $('.showmobile').hide();
                        }
                        if ($('#gender').prop("checked") == true) {
                            $('.showgender').show();
                        } else {
                            $('.showgender').hide();
                        }
                        if ($('#address').prop("checked") == true) {
                            $('.showaddress').show();
                        } else {
                            $('.showaddress').hide();
                        }

                        $('.showclientmodal').attr('data-target', '');
                        $(".next-step").attr('disabled', false);

                    }
                    if (ischeck == 1) {
                        saveclientinformation();
                    }
                } else {
                    $("#spnError").show();
                }


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
var KTcustomse = function(ischeck) {
    var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';

    var _handleClientCreate = function() {
        var form = KTUtil.getById('addcustomsec');
        // var formSubmitUrl = KTUtil.attr(form, 'action');
        var formSubmitButton = KTUtil.getById('addCusSection');

        if (!form) {
            return;
        }

        FormValidation
            .formValidation(
                form, {
                    fields: {
                        sectiontitle: {
                            validators: {
                                notEmpty: {
                                    message: 'Title is required'
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

				var unique_id = makeid(10);

                var setitle = $("#sectiontitle").val();
                var sedes = $("#sectiondes").val();
                $('#addCustomSectionModal').modal('hide');
                $('#ClientInfoShow').show();
                $('.customse').show();
                $('.setp2').text(setp2);
                $('.cusStep2').text(setp2);
                setp1 = setp2;
                var html = '';
                html += '<div id="custom' + sectionid + '" class="sectionHtml">';
                html += '<div class="my-card-lable-orange " style="position: relative;max-width: 35%;">';
                html += 'Section <span class="cusStep1">' + setp1 + '</span> of <span class="cusStep2">' + setp2 + '</span>: Custom section';
                html += '</div>';
                html += '<form name="qnaform" id="qnaform">';
                html += '<div class="card-header d-flex justify-content-between">';
                html += '<div>';
                html += '<h3 id="cusshowTitle">' + setitle + '</h3><input type="hidden" name="title[]" value="' + setitle + '">';
                html += '<p class="m-0 text-dakr-50" id="cusDes">' + sedes + '</p><input type="hidden" name="des[]" value="' + sedes + '">';
                html += '</div>';
                html += '<input type="hidden" name="sectionid[]" id="sectionid" value="' + sectionid + '">';
                html += '<div class="dropdown dropdown-inline">';
                html += '<a href="#" class="btn btn-clean text-dark btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                html += '<i class="ki ki-bold-more-ver text-dark"></i>';
                html += '</a>';
                html += '<div class="dropdown-menu dropdown-menu-right text-center">';
                html += '<ul class="navi navi-hover">';
                html += '<li class="navi-item">';
                html += '<a href="" class="navi-link">';
                html += '<span class="navi-text">Edit</span>';
                html += '</a>';
                html += '</li>';
                html += '<li class="navi-item">';
                html += '<a class="navi-link">';
                html += '<span class="navi-text text-danger" id="deleteCustom" data-id="' + sectionid + '">Delete</span>';
                html += '</a>';
                html += '</li>';
                html += '</ul>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="card-body showdata firstclass" id="divIndex'+unique_id+'">';
                html += '<div class="card">';
                html += '<div class="card-body bg-content">';
                html += '<div class="d-flex justify-content-between ">';
                html += '<div class="form-group mr-2 w-100"><label class="font-weight-bolder" for="exampleSelect1">Answer type or item</label><select class="form-control dropDownId" data-id="' + sectionid + '" tabindex="null"><option data-icon="fas fa-grip-lines" value="shortAnswer">Short answer</option><option data-icon="fas fa-align-left" value="longAnswer">Long answer</option><option data-icon="far fa-dot-circle" value="singleAnswer">Single answer</option><option data-icon="far fa-check-square" value="singleCheckbox">Singlecheckbox</option><option data-icon="fas fa-tasks" value="multipleCheckbox">Multiplechoice</option><option data-icon="far fa-caret-square-down" value="dropdown">Drop-down</option><option data-icon="fas fa-adjust" value="yesOrNo">Yes or No</option><option data-icon="far fa-font-case" value="des">Description text</option></select></div>';

                html += '<div class="form-group ml-2 w-100">';
                html += '<label class="font-weight-bolder" for="exampleSelect1">Question</label>';
                html += '<input type="text" class="form-control" id="question" placeholder="New question copy">';
                html += '<span class="navi-text text-danger" id="error"></span>';
                html += '</div>';

                html += '</div>';
                html += '<div class="inputappend' + sectionid + '"></div>'
                html += '<hr>';
                html += '<div class="d-flex flex-wrap justify-content-between align-items-center">';
                html += '<div class="">';
                html += '<div class="form-group mb-0">';
                html += '<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">';
                html += '<label class="d-flex align-item-center font-weight-bolder">';
                html += '<input type="checkbox" checked="checked" id="required" >';
                html += '<span></span>&nbsp;Required';
                html += '</label>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="d-flex align-items-center">';
                html += '<span class="border-right p-3">';
                html += '<a href="javascript:;" class="chageToUpper"><i class="mx-1 fas fa-long-arrow-alt-up fa-2x"></i></a>';
                html += '<a href="javascript:;" class="chageToLower"><i class="mx-1 fas fa-long-arrow-alt-down fa-2x"></i></a>';
                html += '</span>';
                html += '<span class="border-right p-3">';
                html += '<button type="button" class="mx-2 btn btn-sm btn-white copyNewQnaCus"><i class="mx-1 far fa-clone fa-2x"></i></button>';
                html += '<button type="button" class="mx-2 btn btn-sm btn-white deleteNewQnaCus"><i class="mx-1 fas fa-trash fa-2x text-danger"></i></button>';
                html += '</span>';
                html += '<span class="p-3">';
                html += '<button type="button" class="mx-2 btn btn-sm btn-white closeqna"><i class="p-0 fa fa-times"></i></button>';
                html += '<button type="button" class="mx-2 btn btn-sm btn-primary saveques"><i class="p-0 fas fa-check"></i></button>';
                html += '</span>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div id="addnewqnainsec"></div>'
                html += '</form>';
                html += '<div class="card-footer" style="1px solid #EBEDF3;">';
                html += '<span class="cursor-pointer text-blue addnewqnatomodel" data-toggle="modal"data-target="#addNewQuestionModal">';
                html += '<i class="text-blue fa fa-plus mr-3"></i>Add a new Question or Item';
                html += '</span>';
                html += '</div></div><br><br>';
                $("#appenddata").append(html);

                $(".preview-tab").show();
                $('.dropDownId').selectpicker();
                $(".next-step").attr('disabled', false);

                setp2++;
                sectionid++;
                document.getElementById('addcustomsec').reset();


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
    KTLogin.init(ischeck);
    KTcustomse.init();
});