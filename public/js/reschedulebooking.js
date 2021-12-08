jQuery(document).ready(function() {
	
	$(document).on("click", ".noprefernce", function(){
		var staffId = $(".staffListSec .selStaff").data("stid");
		var userId = $("#userId").val();
		var csrf = $("input[name=_token]").val();
		var serviceIds = [];
		$("#staffId").val(staffId);
		
		$("input[name='itemPrId[]']").each( function() {
			var sId = $(this).val();
			serviceIds.push(sId);
		});
		
		$.ajax({
			type: "POST",
			url: WEBSITE_URL+"/getBookingSlot",
			dataType: 'json',
			data: {service_ids : serviceIds, userId : userId, staffId : staffId, _token : csrf},
			success: function (response) {

				if(response.status == true) 
				{
					var serviceData = response.data;		
					var timeData = response.availability;		
					var shtml = "";	
					$("#select-service-list").html("");
					
					$.each(serviceData, function(index, val) {
						var html = "";
						html += '<div class="mb-3 servRw itemRw">';
							html += '<span class="title d-flex justify-content-between">';
								html += '<h6>'+val.service_name+'</h6>';
								html += '<h6 class="text-muted">$'+val.service_spprice+'</h6>';
							html += '</span>';
							html += '<span class="title d-flex justify-content-between">';
								html += '<h6 class="text-muted">'+val.service_duration_txt+'</h6>';
								html += '<input type="hidden" name="itemPrId[]" value="'+val.service_price_id+'">';
								html += '<input type="hidden" name="itemPr[]" value="'+val.service_spprice+'">';
								html += '<input type="hidden" name="itemDur[]" value="'+val.service_duration+'">';
								html += '<input type="hidden" name="itemType[]" value="service">';
							html += '</span>';
						html += '</div>';
						$("#select-service-list").append(html);
					});
					
					var timeHtml = "";
					
					$(".user_availability").html("");

					if(timeData.length > 0) {
						$.each(timeData, function(index, times) {
							var slots = times;
							$.each(slots, function(ind, val) {
								timeHtml += '<a href="javascript:;" class="list-group-item d-flex justify-content-between align-items-center selTimeSlots" data-booktime="'+val.start+'">';
									timeHtml += '<h6 class="mb-0">'+val.start+' to '+val.end+'</h6><i class="fa fa-chevron-right"></i>';
								timeHtml += '</a>';
							});
						});	
						$(".user_availability").html(timeHtml);
					} else {
						timeHtml += '<a href="javascript:;" class="list-group-item d-flex justify-content-between align-items-center">';
							timeHtml += '<h6 class="mb-0">No time slot found.</h6><i class="fa fa-chevron-right"></i>';
						timeHtml += '</a>';
						$(".user_availability").html(timeHtml);
					}		
					calcTotal();
					nextPrev(1);
				}
			}
		});
		
	});
	
	$(document).on("click", ".selStaff", function(){
		var staffId = $(this).data("stid");
		var userId = $("#userId").val();
		var csrf = $("input[name=_token]").val();
		var serviceIds = [];
		$("#staffId").val(staffId);
		
		$("input[name='itemPrId[]']").each( function() {
			var sId = $(this).val();
			serviceIds.push(sId);
		});		
		
		$.ajax({
			type: "POST",
			url: WEBSITE_URL+"/getBookingSlot",
			dataType: 'json',
			data: {service_ids : serviceIds, userId : userId, staffId : staffId, _token : csrf},
			success: function (response) {

				if(response.status == true) 
				{
					var serviceData = response.data;		
					var timeData = response.availability;		
					var shtml = "";	
					$("#select-service-list").html("");
					
					$.each(serviceData, function(index, val) {
						var html = "";
						html += '<div class="mb-3 servRw itemRw">';
							html += '<span class="title d-flex justify-content-between">';
								html += '<h6>'+val.service_name+'</h6>';
								html += '<h6 class="text-muted">$'+val.service_spprice+'</h6>';
							html += '</span>';
							html += '<span class="title d-flex justify-content-between">';
								html += '<h6 class="text-muted">'+val.service_duration_txt+'</h6>';
								html += '<input type="hidden" name="itemPrId[]" value="'+val.service_price_id+'">';
								html += '<input type="hidden" name="itemPr[]" value="'+val.service_spprice+'">';
								html += '<input type="hidden" name="itemDur[]" value="'+val.service_duration+'">';
								html += '<input type="hidden" name="itemType[]" value="service">';
							html += '</span>';
						html += '</div>';
						$("#select-service-list").append(html);
					});
					
					var timeHtml = "";
					
					$(".user_availability").html("");

					if(timeData.length > 0) {
						$.each(timeData, function(index, times) {
							var slots = times;
							$.each(slots, function(ind, val) {
								timeHtml += '<a href="javascript:;" class="list-group-item d-flex justify-content-between align-items-center selTimeSlots" data-booktime="'+val.start+'">';
									timeHtml += '<h6 class="mb-0">'+val.start+' to '+val.end+'</h6><i class="fa fa-chevron-right"></i>';
								timeHtml += '</a>';
							});
						});	
						$(".user_availability").html(timeHtml);
					} else {
						timeHtml += '<a href="javascript:;" class="list-group-item d-flex justify-content-between align-items-center">';
							timeHtml += '<h6 class="mb-0">No time slot found.</h6><i class="fa fa-chevron-right"></i>';
						timeHtml += '</a>';
						$(".user_availability").html(timeHtml);
					}		
					calcTotal();
					nextPrev(1);
				}
			}
		});
		
	});
	
	$(document).on("click", "#submitBooking", function(){
		var form_data = new FormData($("#bookingfrm")[0]);
		var formSubmitUrl = $("#bookingfrm").attr('action');
		
		$.ajax({
			type: 'POST',       
			url: formSubmitUrl,
			data: form_data,		
			dataType: 'json',					
			processData: false,
			contentType: false,
			success: function (response)
			{
				$("#success_div").removeClass("d-none");
				$("#bookingfrm").addClass("d-none");
				
				setTimeout( function() {
					window.location.href = response.redirect;
				}, 1500);	
			},
			error: function (data) {
				
				var errors = data.responseJSON;
				
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
		return false;
	});
	
	$(document).on("click", ".selTimeSlots", function(){
		var time = $(this).data('booktime');	
		$("#bookingTime").val(time);
		$(".bktm").html(time);
		nextPrev(1);
	});
});

function calcTotal()
{
	console.log("calc");
	var cartTotal = 0; var inoviceTotal = 0;
	$("input[name='itemPr[]']").each( function() {
		var total = parseFloat($(this).val());
		inoviceTotal = inoviceTotal + total;
	});	
	
	var taxAmount = 0;		
	var taxFormula = $("#taxFormula").val();
	
	if($(".serviceTax").length > 0) {
		if($(".serviceTax").length > 1) {
			if(taxFormula == 1) {
				var totalTax = 0;
				$(".serviceTax").each( function() {
					totalTax = totalTax + parseFloat($(this).val());
				});
				
				var subTotal = 0;
				$("input[name='itemPr[]']").each( function() {
					var itemPrice = parseFloat($(this).val());
					var tax_amount = (( itemPrice * totalTax ) / ( totalTax + 100 ));
					var sb = (( itemPrice * 100 ) / ( totalTax + 100 )); 
					subTotal = subTotal + sb;
				});
				
				if(subTotal > 0) {
					$(".serviceTax").each( function() {
						var taxAmount = 0;	
						var tax_rate = parseFloat($(this).val());
						var tax_id = $(this).data("id");
						var tax_name = $(this).data("name");
						var tax_amount = (( subTotal * tax_rate ) / 100);
						tax_amount = tax_amount.toFixed(2);
						 
						var html = "";
						if ($('.tax_sec .taxId'+tax_id).length) {
							/* html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
							html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>'; */
							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
							$('.tax_sec .taxId'+tax_id).html(html);
						}
						else
						{		
							html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId'+tax_id+'">';
								/* html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>'; */
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
							html += '</div>';
							$(".tax_sec").append(html);
						}
					});	
				}	
			} else {	
				var subTotal = 0;
				$("input[name='itemPr[]']").each( function() {
					var itemPrice = parseFloat($(this).val());
					subTotal = subTotal + itemPrice;
				});
				
				if(subTotal > 0) {
					$(".serviceTax").each( function() {
						var taxAmount = 0;	
						var tax_rate = parseFloat($(this).val());
						var tax_id = $(this).data("id");
						var tax_name = $(this).data("name");
						var tax_amount = (( subTotal * tax_rate ) / 100);
						inoviceTotal = inoviceTotal + tax_amount;
						tax_amount = tax_amount.toFixed(2);
						
						var html = "";
						if ($('.tax_sec .taxId'+tax_id).length) {
							/* html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
							html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>'; */
							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
							$('.tax_sec .taxId'+tax_id).html(html);
						}
						else
						{		
							html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId'+tax_id+'">';
								/* html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>'; */
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
							html += '</div>';
							$(".tax_sec").append(html);
						}
					});	
				}	
			}	
		} else {
			if(taxFormula == 1) {
				var tax_rate = parseFloat($(".serviceTax").val());
				var tax_id = $(".serviceTax").data("id");
				var tax_name = $(".serviceTax").data("name");
				
				var subTotal = 0;
				$("input[name='itemPr[]']").each( function() {
					var itemPrice = parseFloat($(this).val());
					subTotal = subTotal + itemPrice;
				});
				
				if(subTotal > 0) {
					var tax_amount = (( subTotal * tax_rate ) / ( tax_rate + 100 ));
					tax_amount = tax_amount.toFixed(2);
					var html = "";
					if ($('.tax_sec .taxId'+tax_id).length) {
						html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
						html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
						html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
						$('.tax_sec .taxId'+tax_id).html(html);
					}
					else 
					{		
						html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId'+tax_id+'">';
							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
						html += '</div>';
						$(".tax_sec").append(html);
					}
				}
			} else {
				var tax_rate = parseFloat($(".serviceTax").val());
				var tax_id = $(".serviceTax").data("id");
				var tax_name = $(".serviceTax").data("name");
				var subTotal = 0;
				
				$("input[name='itemPr[]']").each( function() {
					var itemPrice = parseFloat($(this).val());
					subTotal = subTotal + itemPrice;
				});
				
				if(subTotal > 0) {
					var tax_amount = (( subTotal * tax_rate ) / 100 );
					inoviceTotal = inoviceTotal + tax_amount;
					tax_amount = tax_amount.toFixed(2);
						
					var html = "";
					if ($('.tax_sec .taxId'+tax_id).length) {
						html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
						html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
						html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
						$('.tax_sec .taxId'+tax_id).html(html);
					}
					else
					{		
						html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId'+tax_id+'">';
							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
						html += '</div>';
						$(".tax_sec").append(html);
					}
				}	
			}	
		}		
	}
	
	var totalTaxAmount = 0;
	$('input[name^="invoice_tax_amount"]').each(function(){
		var val = parseFloat($(this).val());
		totalTaxAmount = totalTaxAmount + val;
	});	
	
	if(totalTaxAmount > 0 && inoviceTotal > 0) {
		$(".taxlbl").html("Taxes");
		$(".taxAmt").html("$"+ totalTaxAmount.toFixed(2));
	} else {
		$(".taxlbl").html("");
		$(".taxAmt").html("");
	}		
	
	$(".cartTotal").html("$ "+inoviceTotal.toFixed(2));
	$("#inoviceTotal").val(inoviceTotal.toFixed(2));
	
	var totalServ = 0;
	$("input[name='itemPr[]']").each( function() {
		totalServ = totalServ + 1;
	});	
	$(".totalService").html(totalServ+" Service");
}	

var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
	// This function will display the specified tab of the form...
	var tab = document.getElementsByClassName("step-tab-content");

	if (n == (tab.length - 1)) {
		$(".previous").show();
		$(".steps").text("4");
		$(".next-step").hide();
		$("#submitBooking").removeClass("d-none");
	} else {
		$(".next-step").show();
		$("#submitBooking").addClass("d-none");
		$(".previous").hide();
		$(".next-step").text("Next");
	}

	if (n == 0) {
		$(".next-step").hide();
		var staffId = $("#selStaffId").val();
		var userId = $("#userId").val();
		var csrf = $("input[name=_token]").val();
		var serviceIds = [];
		
		$("input[name='itemPrId[]']").each( function() {
			var sId = $(this).val();
			serviceIds.push(sId);
		});		
		$(".staffListSec").html("");
		
		$.ajax({
			type: "POST",
			url: WEBSITE_URL+"/getStaffList",
			dataType: 'json',
			data: {service_ids : serviceIds, userId : userId, staffId : staffId, _token : csrf},
			success: function (response) {
				tab[n].style.display = "block";
				if(response.status == true) 
				{
					$("#is_skip_staff").val(response.is_skip_staff);
					if(response.is_skip_staff == 0)
					{	
						var staffData = response.data;
						if(staffData.length > 0) {
							var shtml = "";
							$(".noprefernce").removeClass("d-none");
							$(".noprefernce").addClass("d-flex");
							$.each(staffData, function(index, val) {
								shtml += '<a href="javascript:;" class="p-4 d-flex justify-content-between align-items-center list-group-item list-group-item-action selStaff" data-stid="'+val.staff_id+'">';
									shtml += '<div class="staff-flexbox">';
										shtml += '<div class="user-symbol">'+val.staff_name.slice(0,1)+'</div>';
									shtml += '</div>';
									shtml += '<div class="staff-flexbox">';
										shtml += '<h6 class="font-weight-bolder m-0">'+val.staff_name+'</h6>';
									shtml += '</div>';
									shtml += '<div class="staff-flexbox">';
										if(parseFloat(val.total_amount) >= parseFloat(val.total_special_amount)) {
											shtml += '<h6 class="font-weight-bolder mb-0">$ '+val.total_special_amount+'</h6>';
											shtml += '<h6 class="mb-0 text-muted"><strike>$ '+val.total_amount+'</strike></h6>';
										} else {
											shtml += '<h6 class="font-weight-bolder mb-0">$ '+val.total_amount+'</h6>';
										}
									shtml += '</div>';
									shtml += '<div class="staff-flexbox">';
										shtml += '<i class="feather-chevron-right" style="font-size: 22px"></i>';
									shtml += '</div>';
								shtml += '</a>';
							});
	 
							$(".staffListSec").html(shtml);
						} else { 
							$(".noprefernce").addClass("d-none");
							$(".noprefernce").removeClass("d-flex");
							var shtml = "";
							shtml += '<a href="javascript:;" class="p-4 d-flex justify-content-between align-items-center list-group-item list-group-item-action selStaff" data-stid="0">';
								shtml += '<div class="staff-flexbox">';
									shtml += '<div class="user-symbol">N</div>';
								shtml += '</div>';
								shtml += '<div class="staff-flexbox">';
									shtml += '<h6 class="font-weight-bolder m-0">No staff found!</h6>';
								shtml += '</div>';
								shtml += '<div class="staff-flexbox">';
									shtml += '';
								shtml += '</div>';
								shtml += '<div class="staff-flexbox">';
									shtml += '';
								shtml += '</div>';
							shtml += '</a>';
							$(".staffListSec").html(shtml);	
						}	
						
						calcTotal();
					} else {
						var staffData = response.data;
						var serviceData = response.servicedata;		
						var timeData = response.availability;		
						var shtml = "";	
						$("#select-service-list").html("");
						$("#staffId").val(staffData[0].staff_id);
						
						$.each(serviceData, function(index, val) {
							var html = "";
							html += '<div class="mb-3 servRw itemRw">';
								html += '<span class="title d-flex justify-content-between">';
									html += '<h6>'+val.service_name+'</h6>';
									html += '<h6 class="text-muted">$'+val.service_spprice+'</h6>';
								html += '</span>';
								html += '<span class="title d-flex justify-content-between">';
									html += '<h6 class="text-muted">'+val.service_duration_txt+'</h6>';
									html += '<input type="hidden" name="itemPrId[]" value="'+val.service_price_id+'">';
									html += '<input type="hidden" name="itemPr[]" value="'+val.service_spprice+'">';
									html += '<input type="hidden" name="itemDur[]" value="'+val.service_duration+'">';
									html += '<input type="hidden" name="itemType[]" value="service">';
								html += '</span>';
							html += '</div>';
							$("#select-service-list").append(html);
						});
						
						var timeHtml = "";
						
						$(".user_availability").html("");
						if(timeData.length > 0) {
							$.each(timeData, function(index, times) {
								var slots = times;
								$.each(slots, function(ind, val) {
									timeHtml += '<a href="javascript:;" class="list-group-item d-flex justify-content-between align-items-center selTimeSlots" data-booktime="'+val.start+'">';
										timeHtml += '<h6 class="mb-0">'+val.start+' to '+val.end+'</h6><i class="fa fa-chevron-right"></i>';
									timeHtml += '</a>';
								});
							});	
						} else {
							timeHtml += '<a href="javascript:;" class="list-group-item d-flex justify-content-between align-items-center">';
								timeHtml += '<h6 class="mb-0">Sorry, you cannot book on this day.</h6>';
							timeHtml += '</a>';
						}		
						$(".user_availability").html(timeHtml);

						calcTotal();
						nextPrev(1);
					}		
				}
			}
		});
		$(".datetime_conform").addClass("d-none");
		$(".previous").hide();
		$(".steps").text("1");
		$(".page-title").text("Select staff");
		$(".service-menu").css('display', 'none');
	} else if (n == 1) {
		tab[n].style.display = "block";
		$(".next-step").hide();
		$(".previous").show();
		$(".steps").text("2");
		$(".datetime_conform").addClass("d-none");
		$(".page-title").text("Select time");
		$(".service-menu").css('display', 'none');
	} else if (n == 2) {
		tab[n].style.display = "block";
		$(".previous").show();
		$(".steps").text("3");
		$(".page-title").text("Review and confirm");
		$(".datetime_conform").removeClass("d-none");
		$(".service-menu").css('display', 'none');
	}
	else if (n == 3) {
		console.log("Asd");
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

// Class Initialization
jQuery(document).ready(function() {
    
});
