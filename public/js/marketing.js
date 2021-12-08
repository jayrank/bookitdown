var currentTab = 0;
showTab(currentTab);
function showTab(n) {
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
		$(".page-title").text("Edit Content");
	} else if (n == 1) {
		$(".previous").show();
		$(".steps").text("2");
		$(".page-title").text("Discount Offer");
	} else if (n == 2) {
		$(".previous").show();
		$(".steps").text("3");
		$(".page-title").text("Campaign Audience");
	}
	else if (n == 3) {
		$(".previous").show();
		$(".steps").text("4");
		$(".page-title").text("Update Campaign");
	}
}

function nextPrev(n) {
	var tab = document.getElementsByClassName("edit-content-tab");
	if(currentTab == 0 && n != -1)
	{
		if($('#email-subject').val() == "")
		{
			validationMsg('error','Email subject is required');
		}
		else if($('#headline-input').val() == "")
		{
			validationMsg('error','Headline text is required');	
		}
		else if($('#body-input').val() == "")
		{
			validationMsg('error','Body text is required');	
		}
		else if($('#selectedImage').val() == "")
		{
			validationMsg('error','Header image is required');		
		}
		else
		{
			tab[currentTab].style.display = "none";
			currentTab = currentTab + n;
			showTab(currentTab);
		}
	}
	else if(currentTab == 1 && n != -1)
	{
		var totalSelectedServices = $('input[name="service[]"]:checked').length;
		if($('#discount-input').val() == "")
		{
			validationMsg('error','Discount value is required');
		}
		else if($('#discount-input').val() > 100){
			validationMsg('error','Discount value must be less than 100');	
		}
		else if(totalSelectedServices <= 0)
		{
			$('#servicesModal').modal('show');
			validationMsg('error','Minimum one service is mandatory is required');
		}
		else
		{
			tab[currentTab].style.display = "none";
			currentTab = currentTab + n;
			showTab(currentTab);
		}
	}
	else if(currentTab == 2 && n != -1)
	{
		tab[currentTab].style.display = "none";
		currentTab = currentTab + n;
		showTab(currentTab);
	}
	if(n == -1)
	{
		tab[currentTab].style.display = "none";
		currentTab = currentTab + n;
		showTab(currentTab);
	}
}
function appoinmentLimit(data) {
	if (data.value === "unlimited") {
		$('.appoinmentLimit').text(" appointments")
	} else {
		$('.appoinmentLimit').text(data.value+" appointment")
		if(data.value > 1){
			$('.appoinmentLimit').text(data.value+" appointments")
		}
	}
}
$(document).on('change', '#valid_for', function(){
	$('.validFor').text($(this).val());
});
function getDiscountType() {
	var type = $('input[name="discount_type"]:checked').val();
	if (type === '1') {
		$(".isPrice").show();
		$(".isPercent").hide();
	} else {
		$(".isPrice").hide();
		$(".isPercent").show();
	}
}
$(document).on('change','.discountType',function(){
	console.log($(this).val());
	if ($(this).val() == '1') {
		$('#selectedDiscount').html('CA $');
		$(".isPrice").show();
		$(".isPercent").hide();
	} else {
		$('#selectedDiscount').html('%');
		$(".isPrice").hide();
		$(".isPercent").show();
	}
});
$(document).on('change','#daysBeforeBirthday',function(){
	var thisText = $('#daysBeforeBirthday option:selected').data('text');
	$('#selected_birthday_days').html(thisText);
});
function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#img-uploader').css("background-image", "url(" + e.target.result + ")");
			$('#img-preview').attr('src', e.target.result);
		};
		reader.readAsDataURL(input.files[0]);
	}
}
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

$(document).on('submit','#smartCampaign',function(e){
	e.preventDefault();
	$('.overlay-loader').show();
	var data = $(this).serialize();
	var ajaxUrl = baseUrl+'/saveCampaign';
	$.ajax({
		type:'POST',
		url:ajaxUrl,
		data:data,
		success:function(resp){
			$('.overlay-loader').hide();
			if(resp.status == true)
			{
				validationMsg('success',resp.message);	
				setTimeout(function(){
					window.location = resp.redirect;
				},1000);
			}
			else
			{
				validationMsg('error',resp.message);
				setTimeout(function(){
					window.location = resp.redirect;
				},1000);
			}
		}
	});
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


$(".numbers").keypress(function (e) {
 	//if the letter is not digit then display error and don't type anything
 	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
    	//display error message
	    $("#errmsg").html("Digits Only").show().fadeOut("slow");
	        return false;
	}
});