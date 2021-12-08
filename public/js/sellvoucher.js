jQuery(document).ready(function() {
	$(document).on("click", ".sel-voucher", function(){
		var unId = $(this).data('uid');
		var vid = $(this).data('vid');

		var color = $(this).attr('data-color');
		$('.voucher-prw').removeClass($('.voucher-prw').attr('data-color'));
		$('.voucher-prw').addClass(color);
		$('.voucher-prw').attr('data-color', color);
		
		var vnam = $(".vname"+unId).html();
		var vserv = $(".voucher-srv"+unId).html();
		var vpr = $(".vretail"+unId).html();
		var totalSold = $(".totalSold"+unId).val();
		var numberofsales = $(".numberofsales"+unId).val();
		var enable_sale_limit = $(".enable_sale_limit"+unId).val();
		var maxQty = (numberofsales - totalSold);

		console.log('totalSold::'+totalSold);
		console.log('numberofsales::'+numberofsales);
		console.log('enable_sale_limit::'+enable_sale_limit);
		console.log('maxQty::'+maxQty);

		$('.qty-count--minus').attr('disabled', true);
		if(enable_sale_limit) {
			var defaultQty = ( (numberofsales - totalSold) > 0 ) ? 1 : 0;
			$('.product-qty').attr('max', maxQty);
		} else {
			var defaultQty = 1;
			$('.product-qty').removeAttr('max');
		}

		$('.product-qty').val(defaultQty);

		if(enable_sale_limit && (defaultQty >= maxQty)) {
			$('.qty-count--add').attr('disabled', true);
		} else {
			$('.qty-count--add').removeAttr('disabled');
		}

		$(".vouchernm").html(vnam);
		$(".voucher-vl, .vaoucher-price").html($(".vvalue"+unId).html());
		$(".voucher-pr").html(vpr);
		$(".voucher-servs").html(vserv);
		$(".voucher-valid").html($(".validfor"+unId).val());
		
		var vhtml = "";
		vhtml += '<div class="d-flex justify-content-between">';
			vhtml += '<div class="d-flex">';
				vhtml += '<span class="mr-2"><p class="px-2 py-1 font-weight-bolder rounded-circle border cart-qty">1</p></span>';
				vhtml += '<span>';
					vhtml += '<h6>'+vnam+'</h6>';
					vhtml += '<h6 class=""><a href="#servicesModal" data-toggle="modal">'+vserv+'</a></h6>';
				vhtml += '</span>';
			vhtml += '</div>'; 
			vhtml += '<span>';
				vhtml += '<h6 class="text-muted">CA $<span class="vcart-total">'+vpr+'</span></h6>';
				vhtml += '<input type="hidden" name="voucherId" id="voucherId" value="'+vid+'">';
				vhtml += '<input type="hidden" name="voucherPrs" id="voucherPrs" value="'+vpr+'">';
			vhtml += '</span>';
		vhtml += '</div>';
		
		var csrf = $("input[name=_token]").val();
		var url = $("#getServiceUrl").val();
		var userId = $("#userId").val();
		
		var fd = new FormData();
		fd.append("voucher_id", vid);
		fd.append("userId", userId);
		fd.append("_token", csrf);
		
		$.ajax({
			type: "POST",
			url: url,
			contentType: false,
			processData: false,
			data: fd,
			success: function (response) 
			{
				$("#servicesModal .modal-body").html(response);
			}
		});
		
		$(".vcart-list").html(vhtml);
		nextPrev(1);
		
		$(".my-custom-footer").removeClass("d-none");
		
		var rs = parseFloat($("#voucherPrs").val());
		$(".voucherQty, .cart-qty").html(1);
		$(".inoviceTotal span").html(vpr);
		$("#invoiceTotal").val(vpr);
	});
	
	$(document).on("keyup", ".prntNmInt", function(){
		var fname = $("input[name='print_first_name']").val();
		var lname = $("input[name='print_last_name']").val();
		$(".printFor").html(fname+" "+lname);
	});
	
	$(document).on("keyup", ".prntMsgInt", function(){
		var msg = $(this).val();
		$(".printMsg").html(msg);
	});
	
	$(document).on("keyup", ".emlName", function(){
		var fname = $("input[name='email_first_name']").val();
		var lname = $("input[name='email_last_name']").val();
		$(".mlFor").html(fname+" "+lname);
	});
	
	$(document).on("keyup", ".emlMsg", function(){
		var msg = $(this).val();
		$(".mlMsg").html(msg);
	});
	
	$(document).on("click", "#treatMe", function(){
		$("#recipient_as").val(0);
		nextPrev(3);
	});
	
	$(document).on("click", "#prntFrm", function(){
		$("#recipient_as").val(1);
		nextPrev(1);
	});
	
	$(document).on("click", "#printBtn", function(){
		if($("#print_first_name").val() == "") {
			$("#print_first_name").focus();
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

			toastr.error("First Name field is required.");
			return false;	
		} else {
			nextPrev(2);
		}	
	});	
	
	$(document).on("click", "#emailBtn", function(){
		var front_email = $("#email_address").val();
		
		if($("#email_first_name").val() == "") {
			$("#email_first_name").focus();
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
			toastr.error("First Name field is required.");
			return false;	
		} else if($("#email_address").val() == "") {
			$("#email_address").focus();
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
			toastr.error("Email field is required.");
			return false;	
		} else if(IsEmail($("#email_address").val()) == false){
			$("#email_address").focus();
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
			toastr.error("Please provide valid email address.");
			return false;
		}
		else {
			nextPrev(1);
		}	
	});	
	
	$(document).on("click", "#emailFrm", function(){
		$("#recipient_as").val(2);
		nextPrev(2);
	});
	
	$(document).on("click", "#paymentBtn", function(){
		var card_name = $("#card_name").val();
		var card_number = $("#card_number").val();
		var expiry_date = $("#expiry_date").val();
		var cvv = $("#cvv").val();
		
		if(card_name == "") {
			$("#card_name").focus()
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
			toastr.error("Card name field is required.");
			return false;	
		} else if(card_number == ""){
			$("#card_number").focus()
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
			toastr.error("Card Number field is required.");
			return false;
		} else if(expiry_date == ""){
			$("#expiry_date").focus()
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
			toastr.error("Expiry date field is required.");
			return false;
		} else if(cvv == ""){
			$("#cvv").focus()
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
			toastr.error("CVV field is required.");
			return false;
		} else {
			Stripe.setPublishableKey($('#stripe_publish_key').val());
			var expiry_date = $('#expiry_date').val();
			var expiry_month = expiry_date.split('/')[0];
			var expiry_year = expiry_date.split('/')[1];
			Stripe.createToken({
				number: $('#card_number').val(),
				cvc: $('#cvv').val(),
				exp_month: expiry_month,
				exp_year: expiry_year
			}, stripeResponseHandler);
		}	
	});
	
	$(document).on('click','.printInvoice',function(){
		/* var url       = $("#printVoucher").val();
		var userId    = $("#userId").val();
		var csrf      = $("input[name=_token]").val();
		var invoiceId = 82;
		
		var form_data = new FormData();
		form_data.append("invoice_id",invoiceId);
		form_data.append("userId",userId);
		form_data.append("_token",csrf);
		
		$.ajax({
			type: "POST",
			url: url,
			data: form_data,
			processData: false,
			contentType: false,
			xhrFields: {
				responseType: 'blob'
			},
			success: function (response) 
			{
				var blob = new Blob([response]);
				var link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				link.download = "Voucher.pdf";
				link.click();
				
				setTimeout( function() {
					window.location.href = redirect_url;
				}, 1500);	
			}
		}); */
	});
	
	$(document).on('click','.showSignupForm',function(){
		$(".commonClass").hide();
		$(".signupWithEmailStep").show();
	});

	$(document).on('click','.showLoginForm',function(){
		$(".commonClass").hide();
		$(".loginWithEmailStep").show();
	});
	
	$(document).on('click','#registerFrontUser',function(){
		var csrf = $("input[name=_token]").val();
		var front_name         = $("#front_name").val();
		var front_lastname     = $("#front_lastname").val();
		var front_mobilenumber = $("#front_mobilenumber").val();
		var front_countrycode  = $("#front_countrycode").val();
		var front_email        = $("#front_email").val();
		var front_password     = $("#front_password").val();
		var front_termsprivacy = $("#front_termsprivacy").val();
		
		var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
		
		if(front_name == ''){
			$("#front_name").focus();
			showError('First name is required.');
			return false;
		} else if(front_lastname == ''){
			$("#front_lastname").focus();
			showError('Last name is required.');
			return false;
		} else if(front_mobilenumber == ''){
			$("#front_mobilenumber").focus();
			showError('Mobile number is required.');
			return false;
		} else if(front_email == ''){
			$("#front_email").focus();
			showError('Email is required.');
			return false;
		} else if(IsEmail(front_email) == false){
			$("#front_email").focus();
			showError('Please provide valid email address.');
			return false;
		} else if(front_password == ''){
			$("#front_password").focus();
			showError('Password field is required.');
			return false;
		} else if(front_password.length < 8){
			$("#front_password").focus();
			showError('Minimum 8 characters required for password.');
			return false;
		} else if($('#front_termsprivacy').prop("checked") == false) {
			showError('You have to agree with our terms and conditions.');
			return false;
		} else {
			$("#registerFrontUser").attr('disabled',true);
			$("#registerFrontUser").text("Please wait...");
			
			$.ajax({
				type: "POST",
				url: WEBSITE_URL+"/bookingFlowSignup",
				dataType: 'json',
				data: {front_name : front_name,front_lastname : front_lastname,front_countrycode : front_countrycode,front_mobilenumber : front_mobilenumber,front_email : front_email,front_password: front_password,_token : csrf},
				success: function (response) {
					if(response.status == true){
						$("#registerFrontUser").attr('disabled',false);
						$("#registerFrontUser").text("Sign up");
						
						$("#loggedUserId").val(response.USERID);
						
						$(".loginSettings").hide();
						$(".paymentDetails").show();
						$("#paymentBtn").show();
						
						showSuccess(response.message);
						return true;
					} else {
						$("#registerFrontUser").attr('disabled',false);
						$("#registerFrontUser").text("Sign up");
						
						showError(response.message);
						return false;
					}
				},
				timeout: 5000,
				error: function(e){
					$("#registerFrontUser").attr('disabled',false);
					$("#registerFrontUser").text("Sign up");
					
					var errors = data.responseJSON;
							
					var errorsHtml='';
					$.each(errors.errors, function( key, value ) {
						errorsHtml += value[0];
					});
					
					showError((errorsHtml) ? errorsHtml : 'Request timeout, Please try again later!');
					return false;
				}			
			});
		}
	});

	$(document).on('click','#loginFrontUser',function(){
		var csrf = $("input[name=_token]").val();
		var front_login_email    = $("#front_login_email").val();
		var front_login_password = $("#front_login_password").val();
		
		var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
		
		if(front_login_email == ''){
			$("#front_login_email").focus();
			showError('Email is required.');
			return false;
		} else if(IsEmail(front_login_email) == false){
			$("#front_login_email").focus();
			showError('Please provide valid email address.');
			return false;
		} else if(front_login_password == ''){
			$("#front_login_password").focus();
			showError('Password field is required.');
			return false;
		} else {
			$("#loginFrontUser").attr('disabled',true);
			$("#loginFrontUser").text("Please wait...");
			
			$.ajax({
				type: "POST",
				url: WEBSITE_URL+"/bookingFlowLogin",
				dataType: 'json',
				data: {front_login_email : front_login_email,front_login_password: front_login_password,_token : csrf},
				success: function (response) {
					if(response.status == true){
						$("#loginFrontUser").attr('disabled',false);
						$("#loginFrontUser").text("Log in");
						
						$("#loggedUserId").val(response.USERID);
						
						$(".loginSettings").hide();
						$(".paymentDetails").show();
						$("#paymentBtn").show();
						showSuccess(response.message);
						return true;
					} else {
						$("#loginFrontUser").attr('disabled',false);
						$("#loginFrontUser").text("Log in");
						
						showError(response.message);
						return false;
					}
				},
				timeout: 5000,
				error: function(e){
					$("#loginFrontUser").attr('disabled',false);
					$("#loginFrontUser").text("Log in");
					
					var errors = data.responseJSON;
							
					var errorsHtml='';
					$.each(errors.errors, function( key, value ) {
						errorsHtml += value[0];
					});
					
					showError((errorsHtml) ? errorsHtml : 'Request timeout, Please try again later!');
					return false;
				}			
			});
		}
	});

});	

window.showError = function(message){
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
	toastr.error(message);
}

window.showSuccess = function(message){
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
	toastr.success(message);
}

window.IsEmail = function(email) {
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!regex.test(email)) {
	   return false;
	}else{
	   return true;
	}
}
	
function stripeResponseHandler(status, response) {
	if (response.error) {
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
		toastr.error(response.error.message);
			
	} else {
		var token = response['id'];
		$("#stripeToken").val(token);
		
		var form_data = new FormData($("#voucherfrm")[0]);
		var formSubmitUrl = $("#voucherfrm").attr('action');
		
		$.ajax({
			type: 'POST',       
			url: formSubmitUrl,
			data: form_data,		
			dataType: 'json',					
			processData: false,
			contentType: false,
			success: function (response)
			{
				var redirect_url = response.redirect;
				var inoviceId = response.inoviceId;
				
				$("#success_div").removeClass("d-none");
				$("#voucherfrm").addClass("d-none");
				
				if($("#recipient_as").val() == 1) {
					$(".gnrtpdfmsg").removeClass("d-none");
					var url       = $("#printVoucher").val();
					var userId    = $("#userId").val();
					var csrf      = $("input[name=_token]").val();
					var invoiceId = inoviceId;
					
					var form_data = new FormData();
					form_data.append("invoice_id",invoiceId);
					form_data.append("userId",userId);
					form_data.append("_token",csrf);
					
					$.ajax({
						type: "POST",
						url: url,
						data: form_data,
						processData: false,
						contentType: false,
						xhrFields: {
							responseType: 'blob'
						},
						success: function (response) 
						{
							var blob = new Blob([response]);
							var link = document.createElement('a');
							link.href = window.URL.createObjectURL(blob);
							link.download = "Voucher.pdf";
							link.click();
							
							setTimeout( function() {
								window.location.href = redirect_url;
							}, 1500);	
						}
					});
				} else {
					setTimeout( function() {
						window.location.href = redirect_url;
					}, 1500);
				}	
			},
			error: function (data) {
				var errors = data.responseJSON;
				console.log(JSON.stringify(errors));
				console.log("erros = "+JSON.stringify(errors.error));
				var errorsHtml='';
				$.each(errors.error, function( key, value ) {
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
	}
}

function calcTotal() {
	var rs = parseFloat($("#voucherPrs").val());
	$(".voucherQty, .cart-qty").html(1);
	$(".inoviceTotal span").html(rs);
	$("#invoiceTotal").val(rs);
}	

var currentTab = 0; // Current tab is set to be the first tab (0)

if($("#selVoucherId").val() > 0) {
	currentTab = 1;
	showTab(currentTab);
	calcTotal();
} else {
	showTab(currentTab);
}	

function showTab(n) {
	// This function will display the specified tab of the form...
	var tab = document.getElementsByClassName("step-tab-content");
	
	tab[n].style.display = "block";
	if (n == (tab.length - 1)) {
		$(".my-custom-footer").removeClass("d-none");
		$(".previous").show();
		$(".steps").text("6");
		$(".next-step").text("Save");
		$("#nextBtn, #printBtn, #emailBtn").addClass("d-none");
		$("#paymentBtn").removeClass("d-none");
	} else {
		$(".previous").hide();
		$(".next-step").text("Continue");
		$("#nextBtn").removeClass("d-none");
		$("#paymentBtn").addClass("d-none");
	}
	
	if (n == 0) {
		$(".steps").text("1");
		$(".page-title").text("Select a voucher");
		$(".service-menu").css('display', 'block');
	} else if (n == 1) {
		$(".my-custom-footer").removeClass("d-none");
		$(".previous").show();
		$(".steps").text("2");
		$(".page-title").text("Add quantity");
		$(".service-menu").css('display', 'none');
	} else if (n == 2) {
		$(".my-custom-footer").addClass("d-none");
		$("#nextBtn").removeClass("d-none");
		$("#printBtn, #emailBtn").addClass("d-none");
		$(".previous").show();
		$(".steps").text("3");
		$(".page-title").text("Choose a recipient");
		$(".service-menu").css('display', 'none');
	} else if (n == 3) {
		if($("#recipient_as").val() == 2) {
			console.log("asdasd");
			nextPrev(-1);
		} else { 
			$(".previous").show();
			$("#nextBtn").addClass("d-none");
			$("#printBtn").removeClass("d-none");
			$(".my-custom-footer").removeClass("d-none");
			$(".steps").text("4");
			$(".page-title").text("Print as a gift");
			$(".service-menu").css('display', 'none');
		}	
	} else if (n == 4) {
		if($("#recipient_as").val() == 1) {
			nextPrev(-1);
		} else if($("#recipient_as").val() == 0) { 
			nextPrev(-2);
		}else { 
			$(".my-custom-footer").removeClass("d-none");
			$(".previous").show();
			$("#nextBtn").addClass("d-none");
			$("#emailBtn").removeClass("d-none");
			$(".steps").text("5");
			$(".page-title").text("Email as a gift");
			$(".service-menu").css('display', 'none');
		}	
	} else if (n == 5) {
		$(".previous").show();
		$(".steps").text("6");
		$(".page-title").text("Review and checkout");
		$(".service-menu").css('display', 'none');
	}
}

function nextPrev(n) {
	// This function will figure out which tab to display
	var tab = document.getElementsByClassName("step-tab-content");

	// Hide the current tab:
	tab[currentTab].style.display = "none";
	// Increase or decrease the current tab by 1:
	currentTab = currentTab + n;
	// if you have reached the end of the tab
	// Otherwise, display the correct tab:
	showTab(currentTab);
}

var QtyInput = (function () {
	var $qtyInputs = $(".qty-input");

	if (!$qtyInputs.length) {
		return;
	}

	var $inputs = $qtyInputs.find(".product-qty");
	var $countBtn = $qtyInputs.find(".qty-count");
	var qtyMin = parseInt($inputs.attr("min"));
	var qtyMax = parseInt($inputs.attr("max"));

	$inputs.keyup(function () {
		qtyMin = parseInt($inputs.attr("min"));
		qtyMax = parseInt($inputs.attr("max"));

		var $this = $(this);
		var $minusBtn = $this.siblings(".qty-count--minus");
		var $addBtn = $this.siblings(".qty-count--add");
		var qty = parseInt($this.val());

		console.log('qty::'+qty);
		if (isNaN(qty) || qty <= qtyMin) {
			$this.val(qtyMin);
			
			var rs = parseFloat($("#voucherPrs").val());
			var totalAmt = (rs * qtyMin);
			$(".voucherQty, .cart-qty").html(qtyMin);
			$(".inoviceTotal span").html(totalAmt);
			$("#invoiceTotal").val(totalAmt);
			
			$minusBtn.attr("disabled", true);
			$addBtn.attr('disabled', false);
		} else {
			$minusBtn.attr("disabled", false);

			if (qty >= qtyMax) {
				$this.val(qtyMax);
				
				var rs = parseFloat($("#voucherPrs").val());
				var totalAmt = (rs * qtyMax);
				$(".voucherQty, .cart-qty").html(qtyMax);
				$(".inoviceTotal span").html(totalAmt);
				$("#invoiceTotal").val(totalAmt);
				
				$addBtn.attr('disabled', true);
			} else {
				$this.val(qty);
				
				var rs = parseFloat($("#voucherPrs").val());
				var totalAmt = (rs * qty);
				$(".voucherQty, .cart-qty").html(qty);
				$(".inoviceTotal span").html(totalAmt);
				$("#invoiceTotal").val(totalAmt);
				
				$addBtn.attr('disabled', false);
			}
		}
	});

	$countBtn.click(function () {
		qtyMin = parseInt($inputs.attr("min"));
		qtyMax = parseInt($inputs.attr("max"));
		
		var operator = this.dataset.action;
		var $this = $(this);
		var $input = $this.siblings(".product-qty");
		var qty = parseInt($input.val());

		if (operator == "add") {
			qty += 1;
			if (qty >= qtyMin + 1) {
				$this.siblings(".qty-count--minus").attr("disabled", false);
				
				var rs = parseFloat($("#voucherPrs").val());
				var totalAmt = (rs * qty);
				$(".voucherQty, .cart-qty").html(qty);
				$(".inoviceTotal span").html(totalAmt);
				$("#invoiceTotal").val(totalAmt);
			}

			if (qty >= qtyMax) {
				$this.attr("disabled", true);
			}
		} else {
			qty = qty <= qtyMin ? qtyMin : (qty -= 1);

			if (qty == qtyMin) {
				$this.attr("disabled", true);
			}

			if (qty < qtyMax) {
				$this.siblings(".qty-count--add").attr("disabled", false);
				
				var rs = parseFloat($("#voucherPrs").val());
				var totalAmt = (rs * qty);
				$(".voucherQty, .cart-qty").html(qty);
				$(".inoviceTotal span").html(totalAmt);
				$("#invoiceTotal").val(totalAmt);
			}
		}       

		$input.val(qty);
	});
})();