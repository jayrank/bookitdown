var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
	// This function will display the specified tab of the form...
	var tab = document.getElementsByClassName("edit-content-tab");
	tab[n].style.display = "block";
	if (n == (tab.length - 1)) {
		$(".last-hide").hide();
		$(".last-show").hide();
		$(".next-step").hide();
		$(".previous").show();
		$(".steps").text("3");

	} else {
		$(".last-show").show();
		$(".last-hide").show();
		$(".next-step").show();
		$(".previous").hide();
		$(".next-step").text("Next Step");
	}

	if (n == 0) {
		$(".steps").text("1");
		$(".page-title").text("Email message");
	} else if (n == 1) {
		$(".previous").show();
		$(".steps").text("2");
		$(".page-title").text("Customise message");
	} else if (n == 2) {
		$(".previous").show();
		$(".steps").text("3");
		$(".page-title").text("Choose the audience");
	}
	else if (n == 3) {
		$(".previous").show();
		$(".steps").text("4");
		$(".page-title").text("Choose a payment method and send your message");
	}
}

function nextPrev(n) {
	var totalClients = $('.clients:checked').length;
	var tab = document.getElementsByClassName("edit-content-tab");
	console.log(totalClients);
	if(currentTab == 2 && totalClients <= 0 && n > 0)
	{
		$('#clientsModal').modal('show')
		validationMsg('error','Select at least one client to send your message to');
		return false;
	}

	if(currentTab == 1 && n > 0)
	{
		var selectedServices = $('input[name="service[]"]:checked').length;
		if($("input[name='message_name']").val() == "")
		{
			validationMsg('error','Message name is required');
			return false;		
		}
		else if($("input[name='email_subject']").val() == "")
		{
			validationMsg('error','Email subject is required');
			return false;		
		}
		else if($("input[name='email_reply']").val() == "")
		{
			validationMsg('error','Reply to email field is required');
			return false;		
		}
		else if($("input[name='title']").val() == "")
		{
			validationMsg('error','Title field is required');
			return false;		
		}
		else if($("input[name='desc']").val() == "")
		{
			validationMsg('error','Email message field is required');
			return false;		
		}
		else if($('#selectedImage').val() == "" && $('#enable_photo').prop('checked') == true)
		{
			validationMsg('error','Header image is required');
			return false;		
		}
		else if($("input[name='isbutton']:checked").val() == 2 && $('#button-text-input').val() == "")
		{
			validationMsg('error','Button name field is required');
			return false;		
		}
		else if($("input[name='isbutton']:checked").val() == 2 && isUrlValid($("input[name='btn_url']").val()) == false)
		{
			validationMsg('error','Button url field is required');
			return false;		
		}
		else if($('input[name="isSocial"]:checked').prop('checked') == true && (isUrlValid($('#facebook').val()) == false || isUrlValid($('#instagram').val()) == false || isUrlValid($('#yoursite').val()) == false))
		{
			validationMsg('error','Enter valid url');
			return false;
		}
		else if(selectedServices <= 0 && "input[name='message-type']:checked" == 2)
		{
			validationMsg('error','Minimum one service are mandatory');
			return false;
		}
		if($('input[name="message-type"]:checked').val() == 'special-offer')
		{
			var $services = $('#treeview .serviceBoxs');
			var countCheckedCheckboxes = $services.filter(':checked').length;
			console.log("countCheckedCheckboxes :"+countCheckedCheckboxes);
			if($('#discount-input').val() == "")
			{
				validationMsg('error','Discount value field is required');
				return false;
			}
			else if(countCheckedCheckboxes <= 0)
			{
				validationMsg('error','Select at least one client');
				return false;
			}
		}
	}
	
	tab[currentTab].style.display = "none";
	currentTab = currentTab + n;
	showTab(currentTab);
	console.log("currentTab : "+currentTab);
}
function isUrlValid(url) 
{
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}
$(document).ready(function () {
	$(".title_text").keyup(function () {
		$('.headline-text').text($(this).val())
	});
	$(".body-input").keyup(function () {
		$('.body-text').html($(this).val().replace(/\n/g, '<br />'))
	});
	$("#discount-input").keyup(function () {
		$('.discount-text').html($(this).val())
	});
})
function appoinmentLimit(data) {
	if (data.value === "unlimited") {
		$('.appoinmentLimit').text("")
	} else {
		$('.appoinmentLimit').text(data.value)
	}
}
$(document).on('change','#valid_for',function(){
	$('.validFor').text($(this).val());	
});

function getDiscountType() {
	var type = $('input[name="discount_type"]:checked').val();
	if (type == 1) {
		// $(".selectedPrice").html('CA $');
		$(".isPrice").show();
		$(".isPercent").hide();
	} else {
		// $(".selectedPrice").html('%');
		$(".isPrice").hide();
		$(".isPercent").show();
	}
}

$(".isUpdate").hide();
$(".isSpecialOffer").hide();
$(".isVoucher").hide();
function getMessageType() {
	var type = $('input[name="message-type"]').val();
	
	if (type === '1') {
		$(".isUpdate").show();
		$(".isSpecialOffer").hide();
		$(".isVoucher").hide();
	} else if (type === '3') {
		$(".isUpdate").hide();
		$(".isSpecialOffer").hide();
		$(".isVoucher").show();
	} else if (type === '2') {
		$(".isUpdate").hide();
		$(".isSpecialOffer").show();
		$(".isVoucher").hide();
	}
}

$("#title-input").keyup(function () {
	$('#headline-text').text($(this).val())
});
$(".message").keyup(function () {
	$('.body-text').html($(this).val().replace(/\n/g, '<br />'))
});

$("#button-text-input").keyup(function () {
	$('.button-text').text($(this).val())
});

function isCustomButton() {
	var type = $('input[name="isbutton"]:checked').val();
	if (type === '1') {
		$(".isButton").show();
		$(".isCustomButton").hide();
		$('.button-text').text("Book Now")
	} else if (type === '0') {
		$(".isButton").hide();
		$(".isCustomButton").hide();
	} else if (type === '2') {
		$(".isButton").show();
		$(".isCustomButton").show();
	}
}
$(".isClientGroups").hide();
function checkAudience() {
	var type = $('input[name="audience"]:checked').val();
	if (type == 1) {
		$(".isClientGroups").hide();
	} else if (type == 2) {
		$(".isClientGroups").show();
	}
}
$(document).on('click','.client_group_model',function(){
	console.log($(this).data('type'));
	$('#day_value').attr('data-group-type',$(this).data('type'));
	$('#newClientModal').modal('show');
});
$(document).on('click','.saveEditClient',function(){
	var group_type = $('#day_value').attr('data-group-type');
	if(group_type == 'new_client')
	{
		var dayValue = $('#day_value').val();
		var selected_time = $('#selecte_time').val();
		$('#new_client').attr('data-value',dayValue);
		$('#new_client').attr('data-time',selected_time);
		$('#newClientModal').modal('hide');
		$('#new_client').trigger('click');
	}
	else if(group_type == 'recent_client')
	{
		var dayValue = $('#day_value').val();
		var selected_time = $('#selecte_time').val();
		$('#new_client').attr('data-value',dayValue);
		$('#new_client').attr('data-time',selected_time);
		$('#newClientModal').modal('hide');
		$('#recent_client').trigger('click');	
	}
});
$(document).on('click', '.client_group', function(){
	var id = $(this).val();
	var type = $(this).attr('data-type');
	var value = $(this).attr('data-value');
	var time = $(this).attr('data-time');
	var url = $('#client_group_url').val();
	var dataString = 'id='+id+'&type='+type+'&value='+value+'&time='+time;
	$.ajaxSetup({
	   headers: {
	     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	   }
	});
	$.ajax({
		type:'POST',
		url:url,
		data:dataString,
		success:function(resp){
			if(resp.status == true)
			{
				$('#filteredData').html(resp.html);
				TotalChargeCalculation();
			}
		}
	});
});
function isSocialMedia() {
	if (type = $('input[name="isSocial"]:checked').val()) {
		$(".isSocialMedia").show();
	} else {
		$(".isSocialMedia").hide();
	}
}
$(document).on('change','#is_image',function(){
	$('#image_selector, .isUpdateImage').show();
	if($(this).prop('checked') == false)
	{
		$('#image_selector, .isUpdateImage').hide();
	}
});
function validationMsg(type,message)
{
	if(type == "success")
	{
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
	else
	{

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
		return false;
	}
}
function TotalChargeCalculation()
{
	var totalClients = $('.clients:checked').length;
	var changePerClient = $('#charge_per_client').val();
	var total_pr_client = changePerClient * totalClients;
	var total_gst = total_pr_client * 5 / 100;
	var total_cost = total_pr_client + total_gst
	$('#total_pr_client').html(parseFloat(total_pr_client).toFixed(2));
	$('#subtotal').html(parseFloat(total_pr_client).toFixed(2));
	$('#total_gst').html(parseFloat(total_gst).toFixed(2));
	$('.total_cost').html(parseFloat(total_cost).toFixed(2));
	$('.total_clients').html(totalClients+" Clients");
	$('#totalSelectedClients').val(totalClients+" Clients");
	$('#message_price').val(changePerClient);
	$('#amount').val(total_cost);
	$('#total_payable_price').val(total_cost.toFixed(2));
}

/*$(document).on('submit', '#emailBlastForm' ,function(e){
	e.preventDefault();
	var url = $(this).attr('action');
	var data = $(this).serialize();
	$.ajax({
		type:'post',
		url:url,
		data:data,
		success:function(resp){
			if(resp.status == true)
			{
				validationMsg('success',resp.message);
				setTimeout(function(){
					window.location = marketingUrl+'/'+'marketing_blast_messages';
				},2000)
			}
			else
			{
				validationMsg('error',resp.message);
				setTimeout(function(){
					window.location = marketingUrl+'/'+'marketing_blast_messages';
				},2000)
			}
		}
	});
});*/

/*$(document).on("click", '.file-man-box' , function() {
	$(".file-man-box").css("border","1px solid #e3eaef");
	$(this).css("border", "1px solid red");
	var imageurl = $(this).find('img').attr('data-scr');
	var image = imageURL+'/'+imageurl;

	$('#img-preview').attr('src',image);
	$('.rounded').attr('src',image);
	$('#img-uploader').css("background-image", "url(" + image + ")");
	$('#selectedImage').val('public/uploads/email_blast_images/'+imageurl);

	$('#filemanager').modal('hide');
	$('.modal-backdrop').hide();
	$('.imagePreview').show();
});*/
$(document).on("click", '.image_picker_image' , function() {
	$(".image_picker_image").css("border","1px solid #e3eaef");
	$(this).css("border", "1px solid red");
	var imagePath = $(this).attr('image_path');
	var lib = $(this).attr('image-library');
	var image = imageUrl+'/'+lib+'/'+imagePath;

	$('#img-preview').attr('src',image);
	$('.rounded').attr('src',image);
	$('#img-uploader').css("background-image", "url(" + image + ")");
	$('#selectedImage').val('public/uploads/'+lib+'/'+imagePath);

	$('#filemanager').modal('hide');
	$('.modal-backdrop').hide();
});

$(document).on('click','#saveAsDraftEmailBlast',function(){
	var formData = new FormData($("#emailBlastForm")[0]);
	$('.overlay-loader').show();
	$.ajax({
		type:'POST',
		url: $("#saveAsDraftUrl").val(),
		data:formData,
		contentType: false,
		processData: false,
		success:function(response){
			$('.overlay-loader').hide();
			if(response.status == true) {
				toastr.options = {
					"closeButton": false,
					"debug": false,
					"newestOnTop": true,
					"progressBar": true,
					"positionClass": "toast-top-right",
					"preventDuplicates": false,
					"onclick": null,
					"showDuration": "3000",
					"hideDuration": "1000",
					"timeOut": "4000",
					"extendedTimeOut": "1000",
					"showEasing": "swing",
					"hideEasing": "linear",
					"showMethod": "fadeIn",
					"hideMethod": "fadeOut"
				};
				toastr.success(response.message);
				
				setTimeout(function(){ 
					window.location.href=response.redirect;
				}, 3000);
			} else {
				toastr.options = {
					"closeButton": false,
					"debug": false,
					"newestOnTop": true,
					"progressBar": true,
					"positionClass": "toast-top-right",
					"preventDuplicates": false,
					"onclick": null,
					"showDuration": "5000",
					"hideDuration": "1000",
					"timeOut": "4000",
					"extendedTimeOut": "1000",
					"showEasing": "swing",
					"hideEasing": "linear",
					"showMethod": "fadeIn",
					"hideMethod": "fadeOut"
				};
				toastr.error((response.message) ? response.message : "Something went wrong!");
				
				setTimeout(function(){ 
					window.location.href=response.redirect;
				}, 5000);
			}
		},
		error: function(data){
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
});