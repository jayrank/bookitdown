var FormValidation = function () {

    // basic validation
    var handleValidation1 = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation

            var form1 = $('#add_export');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);
			
            form1.validate({
                errorElement: 'lable', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                 messages: {
                    select_multi: {
                        maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                        minlength: jQuery.validator.format("At least {0} items must be selected")
                    }
                },
                rules: {
                    job_no: {
                        required: true                        
                    },
                    job_date: {
                        required: true                        
                    },
                    customer_id: {
                        required: true                        
                    },
                    consignee: {
                        required: true                        
                    },
                    invoice_no: {
                        required: true                        
                    },
                    invoice_date: {
                        required: true                        
                    },
                    pol: {
                        required: true                        
                    },
                    pod: {
                        required: true                        
                    },
                    export_status: {
                        required: true                        
                    }
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },
                submitHandler: function (form) 
				{	
					$("#submit").attr("disabled",true);
					$(".overlay-loader").show();
					 var url = $(form1).attr('action');
					 
					var form_data = new FormData(form);
					//alert(form_data);
					$.ajax({
						type: 'POST',       
						url: url,
						data: form_data,
						processData: false,
						contentType: false,
						//contentType: "application/json; charset=utf-8",
						success: function (response)
						{
							if(response.status == true)
							{
								$("#export_msg").html(response.message).css("color","green");  
								$(".overlay-loader").hide();
								
								setTimeout(function(){
									window.location = response.redirect;
								},2000);
							}
							else
							{
								$("#export_msg").html(response.message).css("color","red");
								$(".overlay-loader").hide();
								$("#submit").attr("disabled",false);
								return false;
							}
						}
					});
                }
            });
    }
	
	var initFileupload = function () {

        $('#add_export').fileupload({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: AJAX_URL+'export/do_upload',
            autoUpload: true
        });

        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: AJAX_URL+'export/do_upload',
                type: 'HEAD'
            }).fail(function () {
                $('<span class="alert alert-error"/>')
                    .text('Upload server currently unavailable - ' +
                    new Date())
                    .appendTo('#fileupload');
            });
        }
    }


    return {
        //main function to initiate the module
        init: function () {
            handleValidation1();
			initFileupload();
        }

    };

}();