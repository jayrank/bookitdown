jQuery(document).ready(function() {
	$('#voucherPay').on('shown.bs.modal', function (e) {
		var totalTipAmt = 0
		$(".tip-amount").each(function( index ) {
			var val = parseFloat($( this ).val());
			totalTipAmt = totalTipAmt + val;
		});
		var totalBalance = parseFloat($(".totalBalance").val());
		console.log(totalBalance+" tip ="+totalTipAmt);
		if(totalTipAmt == totalBalance) {
			$(".voucher-info, .voucher-inpt-sec").addClass('d-none');
			$(".voucher-nomatch").addClass('d-none');
			
			var html = "";
			html = '<div class="d-flex flex-column justify-content-center align-items-center"><h6 class="font-weight-bold my-3">Total amount for all checkout items has been covered already. Tip amount can\'t be covered with vouchers.</h6></div>';
			$(".voucher-rslt").html(html);
		} else {
			$(".voucher-default-text").addClass('d-flex');
			$(".voucher-default-text, .voucher-inpt-sec").removeClass('d-none');
			$(".voucher-rslt").html("");
			$(".voucher-info, .voucher-nomatch").addClass('d-none');
			$("#voucher_code").val('');
			$("#redemption_amount").val(0);
			$("#voucher-outstanding span").html(0);	
		}	
	});	
	
	$('#addItemToSale').on('shown.bs.modal', function (e) {
		$('.itemresult, .service_sec, .product_sec, .paidplan_sec, .voucher_sec').hide();
		$(".itemresult .list-group").html("");
		$('.saleoptions').show();
		$('.modal-back').hide();
	})
	
	$('.addTipMdl').on('click', function (e) {
		$("#discount-input").val(0);
		$('.tip-staff-id').each(function( index ) {
			var staffId = $(this).val();
			$("#staffTipped option[value='"+staffId+"']").remove();			
		});	
		$("#addTipModal").modal("show");
	});	

	$(".servicecategory").click( function(){
		$(".saleoptions").hide();
		$(".service_sec").show();
		$('.modal-back').show();
	});
	
	$(".productcategory").click( function(){
		$(".saleoptions").hide();
		$(".product_sec").show();
		$('.modal-back').show();
	});
	
	$(".viewpaidplans").click( function(){
		$(".saleoptions").hide();
		$(".paidplan_sec").show();
		$('.modal-back').data('id','paidplansec');
		$('.modal-back').show();
	});
	
	$(".viewvoucher").click( function(){
		$(".saleoptions").hide();
		$(".voucher_sec").show();
		$('.modal-back').data('id','vouchersec');
		$('.modal-back').show();
	});
	
	jQuery(document).on("click", "#selectedClient", function() {	
		if($("#completeSale").css('display') == 'none') {
			$("#cartHistory").hide();
			$("#clientHistory").show();
			$('.side-overlay').addClass('active');	
		}			
	});
	
	jQuery(document).on("click", ".getservice", function() {	
		KTApp.blockPage();
		var id = $(this).data('id');
		var csrf = $("input[name=_token]").val();
		
		$.ajax({
			type: "POST",
			url: WEBSITE_URL+"/getservicebycat",
			dataType: 'json',
			data: {catid : id,_token : csrf},
			success: function (response) {
				KTApp.unblockPage();
				$('.modal-back').data('id','servicesec');
				$('.itemresult').show();
				$(".itemresult .list-group").html(response.data);
				$('.service_sec').hide();
				$('.modal-back').show();
			}
		});
	});	
	
	jQuery(document).on("click", ".getproduct", function() {	
		KTApp.blockPage();
		var id = $(this).data('id');
		var csrf = $("input[name=_token]").val();
		
		$.ajax({
			type: "POST",
			url: WEBSITE_URL+"/getproductbycat",
			dataType: 'json',
			data: {catid : id,_token : csrf},
			success: function (response) {
				KTApp.unblockPage();
				$('.modal-back').data('id','productsec');
				$('.itemresult').show();
				$(".itemresult .list-group").html(response.data);
				$('.product_sec').hide();
				$('.modal-back').show();
			}
		});
	});	
	
	jQuery(document).on("change", ".staff-list", function() {	
		var uid = $(this).data('uid');
		var pr = $(this).find(':selected').data('pr');
		var spr = $(this).find(':selected').data('spr');
		var duration = $(this).find(':selected').data('dur');
		var staff_name = $(this).find(':selected').text();
		$('.disclist'+uid).prop('selectedIndex',0);
		
		$(".itmduration-txt"+uid).html(duration+" with "+staff_name);
		$(".itemogprice"+uid).val(pr);
		
		if(spr > 0) {
			$(".itmpr-txt"+uid+" span").html(spr);	
			$(".itemprice"+uid).val(spr);
			$(".itmspr-txt"+uid+" span").html(pr);	
			$(".itmspr-txt"+uid).removeClass("d-none");	
			$(".itmpr-inpt"+uid).val(pr);
			$(".itmpr-inpt"+uid).attr("readonly", true);
		} else {
			$(".itmpr-txt"+uid+" span").html(pr);
			$(".itemprice"+uid).val(pr);
			$(".itmspr-txt"+uid).addClass("d-none");
			$(".itmpr-inpt"+uid).val(pr);
			$(".itmpr-inpt"+uid).attr("readonly", false);
		}
		// calcTotal();
		calcTotal2();
	});
	
	jQuery(document).on("change", "input[name=discount-type]", function() {
		var val = $('input[name=discount-type]:checked').val(); 
		
		if(val == 1) {
			$(".isPrice").show();
			$(".isPercent").hide();
		} else {
			$(".isPrice").hide();
			$(".isPercent").show();
		}		
	});
	
	jQuery(document).on("change", ".item-discount", function() {
		var uid = $(this).data('uid');
		var type = $(this).find(':selected').data('type');
		var amt = parseFloat($(this).find(':selected').data('amt'));
		var itemogprice = parseFloat($(".itemogprice"+uid).val());
		var discount_txt = $(this).find(':selected').text();
		var itmtype = $(".itemtype"+uid).val();
		var qty = $(".qtinpt"+uid).val();
		
		$(".itemdisctxt"+uid).val(discount_txt);
		
		if(type == 0) {
			var dicountpr = (itemogprice * amt) / 100;
			dicountpr = dicountpr.toFixed(2);
			var spr = (itemogprice - dicountpr);
			var spr1 = (spr * qty);
			var itemogprice1 = (itemogprice * qty);
			$(".itmpr-txt"+uid+" span").html(spr1.toFixed(2));	
			$(".itemprice"+uid).val(spr.toFixed(2));	
			$(".itmspr-txt"+uid+" span").html(itemogprice1.toFixed(2));	
			$(".itmspr-txt"+uid).removeClass("d-none");	
			$(".itmpr-inpt"+uid).val(itemogprice.toFixed(2));
			$(".itmpr-inpt"+uid).attr("readonly", true);
		} else if(type == 1) {
			var spr = (itemogprice - amt);
			var spr1 = (spr * qty);
			var itemogprice1 = (itemogprice * qty);
			$(".itmpr-txt"+uid+" span").html(spr1.toFixed(2));	
			$(".itemprice"+uid).val(spr);	
			$(".itmspr-txt"+uid+" span").html(itemogprice1.toFixed(2));	
			$(".itmspr-txt"+uid).removeClass("d-none");	
			$(".itmpr-inpt"+uid).val(itemogprice.toFixed(2));
			$(".itmpr-inpt"+uid).attr("readonly", true);
		} else {
			var itemogprice1 = (itemogprice * qty);
			$(".itemdisctxt"+uid).val("");
			$(".itmpr-txt"+uid+" span").html(itemogprice1.toFixed(2));
			$(".itemprice"+uid).val(itemogprice.toFixed(2));
			$(".itmspr-txt"+uid).addClass("d-none");
			$(".itmpr-inpt"+uid).val(itemogprice);
			$(".itmpr-inpt"+uid).attr("readonly", false);
		}	
		// calcTotal();
		calcTotal2();
	});	
	
	jQuery(document).on("click", ".additemcheckout", function() {	
		KTApp.blockPage();
		var locationId = $("#locationId").val();
		var id = $(this).data('id');
		var type = $(this).data('type');
		var csrf = $("input[name=_token]").val();
		
		$.ajax({
			type: "POST",
			url: WEBSITE_URL+"/additemcheckout",
			dataType: 'json',
			data: {id : id,type : type,locationId : locationId,_token : csrf},
			success: function (response) {
				KTApp.unblockPage();
				$("#salesItems").append(response.data);
				$(".additemsale_sec").show();
				$(".emptycart").removeClass("d-flex");
				$(".emptycart").hide();
				$('#addItemToSale').modal("hide");
				$('.modal-back').show();
				
				var taxhtml = "";
				if(response.planTax) {
					var taxes = JSON.parse(response.planTax);
					if (taxes.length > 0) {
						$.each(taxes, function(index, val) {
							var st = $(".plntax"+id+"_"+val.id).length;
							if(st == 0) {
								taxhtml += '<input type="hidden" class="paidPlanTax plntax'+id+'_'+val.id+'" value="'+val.tax_rates+'" data-Itemid="'+id+'" data-id="'+val.id+'" data-name="'+val.tax_name+'">';
							}	
						});	
						$(".taxes-list").append(taxhtml);
					}				
				}	
				// calcTotal();
				calcTotal2();
			}
		});
	});	
	
	jQuery(document).on("click", ".removeItem", function() {	
		var uid = $(this).data("uid");
		$(".cardId"+uid).remove();
		
		if($(".serviceItm").length == 0) {
			$(".tax_sec .taxSec").remove();
		}
		$(".paymentDetails_sec").html("");
		var itemFinalTotal = $(".itemFinalTotal").val();
		$("#itemFinalTotal span").html(itemFinalTotal);
		// calcTotal();
		calcTotal2();
	});
	
	// function calcTotal()
	// {
	// 	var inoviceTotal = 0;
	// 	$(".itmpr-hd").each(function( index ) {
	// 		var val = parseFloat($( this ).val());
	// 		var uid = $(this).data("id");
	// 		var qty = parseFloat($(".qtinpt"+uid).val());
	// 		val = (val * qty );
	// 		inoviceTotal = inoviceTotal + val;
	// 	});
		
	// 	if($("#salesItems .serviceItm, #salesItems .productItm, #salesItems .voucherItm, #salesItems .paidplanItm").length > 0) {
	// 		$("#cartHistory").show();
	// 		$("#completeSale").hide();
	// 	} else {
	// 		$(".additemsale_sec, #cartHistory").hide();
	// 		$(".emptycart").css("display", "flex");
	// 	}	
		
	// 	var taxAmount = 0;		
	// 	var taxFormula = $("#taxFormula").val();
	// 	$(".tax_sec").html("");
	// 	$(".paymentDetails_sec").html("");
	// 	/* var itemFinalTotal = $(".itemFinalTotal").val();
	// 	$("#itemFinalTotal span").html(itemFinalTotal);
	// 	$("#payamt span").html(itemFinalTotal); */
		
	// 	if($(".serviceTax").length > 0) {
	// 		if($(".serviceTax").length > 1) {
	// 			if(taxFormula == 1) {
	// 				var totalTax = 0;
	// 				$(".serviceTax").each( function() {
	// 					totalTax = totalTax + parseFloat($(this).val());
	// 				});
					
	// 				var subTotal = 0;
	// 				$(".serviceItm").each( function() {
	// 					var uid = $(this).data("id");
	// 					var itemPrice = parseFloat($(".itemprice"+uid).val());
	// 					var tax_amount = (( itemPrice * totalTax ) / ( totalTax + 100 ));
	// 					var sb = (( itemPrice * 100 ) / ( totalTax + 100 )); 
	// 					subTotal = subTotal + sb;
	// 				});	
					
	// 				if(subTotal > 0) {
	// 					$(".serviceTax").each( function() {
	// 						var taxAmount = 0;	
	// 						var tax_rate = parseFloat($(this).val());
	// 						var tax_id = $(this).data("id");
	// 						var tax_name = $(this).data("name");
	// 						var tax_amount = (( subTotal * tax_rate ) / 100);
	// 						tax_amount = tax_amount.toFixed(2);
							 
	// 						var html = "";
	// 						if ($('.tax_sec .taxId'+tax_id).length) {
	// 							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 							html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 							$('.tax_sec .taxId'+tax_id).html(html);
	// 						}
	// 						else
	// 						{		
	// 							html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId'+tax_id+'">';
	// 								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 							html += '</div>';
	// 							$(".tax_sec").append(html);
	// 						}
	// 					});	
	// 				}	
	// 			} else {	
	// 				var subTotal = 0;
	// 				$(".serviceItm").each( function() {
	// 					var uid = $(this).data("id");
	// 					var itemPrice = parseFloat($(".itemprice"+uid).val());
	// 					subTotal = subTotal + itemPrice;
	// 				});	
					
	// 				if(subTotal > 0) {
	// 					$(".serviceTax").each( function() {
	// 						var taxAmount = 0;	
	// 						var tax_rate = parseFloat($(this).val());
	// 						var tax_id = $(this).data("id");
	// 						var tax_name = $(this).data("name");
	// 						var tax_amount = (( subTotal * tax_rate ) / 100);
	// 						inoviceTotal = inoviceTotal + tax_amount;
	// 						tax_amount = tax_amount.toFixed(2);
							
	// 						var html = "";
	// 						if ($('.tax_sec .taxId'+tax_id).length) {
	// 							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 							html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 							$('.tax_sec .taxId'+tax_id).html(html);
	// 						}
	// 						else
	// 						{		
	// 							html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId'+tax_id+'">';
	// 								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 							html += '</div>';
	// 							$(".tax_sec").append(html);
	// 						}
	// 					});	
	// 				}	
	// 			}	
	// 		} else {
	// 			if(taxFormula == 1) {
	// 				var tax_rate = parseFloat($(".serviceTax").val());
	// 				var tax_id = $(".serviceTax").data("id");
	// 				var tax_name = $(".serviceTax").data("name");
					
	// 				var subTotal = 0;
	// 				$(".serviceItm").each( function() {
	// 					var uid = $(this).data("id");
	// 					var itemPrice = parseFloat($(".itemprice"+uid).val());
	// 					subTotal = subTotal + itemPrice;
	// 				});	
					
	// 				if(subTotal > 0) {
	// 					var tax_amount = (( subTotal * tax_rate ) / ( tax_rate + 100 ));
	// 					tax_amount = tax_amount.toFixed(2);
	// 					var html = "";
	// 					if ($('.tax_sec .taxId'+tax_id).length) {
	// 						html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 						html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 						html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 						html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 						html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 						$('.tax_sec .taxId'+tax_id).html(html);
	// 					}
	// 					else
	// 					{		
	// 						html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId'+tax_id+'">';
	// 							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 							html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 						html += '</div>';
	// 						$(".tax_sec").append(html);
	// 					}
	// 				}
	// 			} else {
	// 				var tax_rate = parseFloat($(".serviceTax").val());
	// 				var tax_id = $(".serviceTax").data("id");
	// 				var tax_name = $(".serviceTax").data("name");
	// 				var subTotal = 0;
					
	// 				$(".serviceItm").each( function() {
	// 					var uid = $(this).data("id");
	// 					var itemPrice = parseFloat($(".itemprice"+uid).val());
	// 					var qty = parseFloat($(".qtinpt"+uid).val());
	// 					itemPrice = (itemPrice * qty);
	// 					subTotal = subTotal + itemPrice;
	// 				});	
					
	// 				if(subTotal > 0) {
	// 					var tax_amount = (( subTotal * tax_rate ) / 100 );
	// 					inoviceTotal = inoviceTotal + tax_amount;
	// 					tax_amount = tax_amount.toFixed(2);
							
	// 					var html = "";
	// 					if ($('.tax_sec .taxId'+tax_id).length) {
	// 						html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 						html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 						html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 						html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 						html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 						$('.tax_sec .taxId'+tax_id).html(html);
	// 					}
	// 					else
	// 					{		
	// 						html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId'+tax_id+'">';
	// 							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 							html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 						html += '</div>';
	// 						$(".tax_sec").append(html);
	// 					}
	// 				}	
	// 			}	
	// 		}		
	// 	}
		
	// 	if($(".productTax").length > 0) {
	// 		if($(".productTax").length > 1) 
	// 		{
	// 			if(taxFormula == 1) 
	// 			{
	// 				var totalTax = 0;
	// 				var subTotal = 0;
	// 				var tax_amount = 0;
	// 				// $(".productTax").each( function() {
	// 				// 	totalTax = totalTax + parseFloat($(this).val());
	// 				// });
					
	// 				$(".productItm").each( function() {
	// 					totalTax = parseFloat($(this).val());
	// 					var uid = $(this).data("id");
	// 					var itemPrice = parseFloat($(".itemprice"+uid).val());
	// 					var qty = parseFloat($(".qtinpt"+uid).val());
	// 					itemPrice = (itemPrice * qty);
	// 					tax_amount += (( itemPrice * totalTax ) / ( totalTax + 100 ));
	// 					// var sb = (( itemPrice * 100 ) / ( totalTax + 100 )); 
	// 					subTotal += itemPrice;
	// 					totalTax = 0;
	// 				});	 
					
	// 				if(subTotal > 0)
	// 				{	
	// 					$(".productTax").each( function() {
	// 						var tax_rate = parseFloat($(this).val());
	// 						// alert(tax_rate);
	// 						var tax_id = $(this).data("id");
	// 						var tax_name = $(this).data("name");
	// 						// var tax_amount = (( subTotal * tax_rate ) / 100);
	// 						// tax_amount = tax_amount.toFixed(2);
	// 						tax_amount.toFixed(2);
							
	// 						var html = "";
	// 						if ($('.tax_sec .taxId'+tax_id).length) {
	// 							// var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
	// 							// tax_amount = (parseFloat(tax_amount) + taxAmt);	
	// 							// tax_amount = tax_amount.toFixed(2);
								
	// 							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 							html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 							$('.tax_sec .taxId'+tax_id).html(html);
	// 						}
	// 						else
	// 						{		
	// 							html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
	// 								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 							html += '</div>';
	// 							$(".tax_sec").append(html);
	// 						}
	// 					});	
	// 				}	
	// 			} 
	// 			else 
	// 			{	
	// 				var subTotal = 0;
	// 				$(".productItm").each( function() {
	// 					var uid = $(this).data("id");
	// 					var itemPrice = parseFloat($(".itemprice"+uid).val());
	// 					var qty = parseFloat($(".qtinpt"+uid).val());
	// 					itemPrice = (itemPrice * qty);
	// 					subTotal = subTotal + itemPrice;
	// 				});	
					
	// 				if(subTotal > 0)
	// 				{
	// 					$(".productTax").each( function() {
	// 						var tax_rate = parseFloat($(this).val());
	// 						var tax_id = $(this).data("id");
	// 						var tax_name = $(this).data("name");
	// 						var tax_amount = (( subTotal * tax_rate ) / 100);
	// 						inoviceTotal = inoviceTotal + tax_amount;
	// 						tax_amount = tax_amount.toFixed(2);
							
	// 						var html = "";
	// 						if ($('.tax_sec .taxId'+tax_id).length) {
	// 							var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
	// 							tax_amount = (parseFloat(tax_amount) + taxAmt);
	// 							tax_amount = tax_amount.toFixed(2);
								
	// 							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 							html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 							$('.tax_sec .taxId'+tax_id).html(html);
	// 						}
	// 						else
	// 						{		
	// 							html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
	// 								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 							html += '</div>';
	// 							$(".tax_sec").append(html);
	// 						}
	// 					});	
	// 				}	
	// 			}	
	// 		} 
	// 		else 
	// 		{
	// 			if(taxFormula == 1) {
	// 				var tax_rate = parseFloat($(".productTax").val());
	// 				var tax_id = $(".productTax").data("id");
	// 				var tax_name = $(".productTax").data("name");
	// 				var subTotal = 0;
	// 				$(".productItm").each( function() {
	// 					var uid = $(this).data("id");
	// 					var qty = parseFloat($(".qtinpt"+uid).val());
	// 					var itemPrice = parseFloat($(".itemprice"+uid).val());
	// 					itemPrice = (itemPrice * qty);
	// 					subTotal = subTotal + itemPrice;
	// 				});
					
	// 				if(subTotal > 0) {
	// 					var tax_amount = (( subTotal * tax_rate ) / ( tax_rate + 100 ));
	// 					tax_amount = tax_amount.toFixed(2);
						
	// 					var html = "";
						
	// 					if ($('.tax_sec .taxId'+tax_id).length) {
	// 						var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
	// 						tax_amount = (parseFloat(tax_amount) + taxAmt);
	// 						tax_amount = tax_amount.toFixed(2);
							
	// 						html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 						html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 						html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 						html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 						html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 						$('.tax_sec .taxId'+tax_id).html(html);
	// 					}
	// 					else
	// 					{		
	// 						html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
	// 							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 							html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 						html += '</div>';
	// 						$(".tax_sec").append(html);
	// 					}
	// 				}	
	// 			} else {
	// 				var tax_rate = parseFloat($(".productTax").val());
	// 				var tax_id = $(".productTax").data("id");
	// 				var tax_name = $(".productTax").data("name");
	// 				var subTotal = 0;
	// 				$(".productItm").each( function() {
	// 					var uid = $(this).data("id");
	// 					var itemPrice = parseFloat($(".itemprice"+uid).val());
	// 					var qty = parseFloat($(".qtinpt"+uid).val());
	// 					itemPrice = (itemPrice * qty);
	// 					subTotal = subTotal + itemPrice;						
	// 				});	
					
	// 				if(subTotal > 0) 
	// 				{	
	// 					var tax_amount = (( subTotal * tax_rate ) / 100 );
	// 					inoviceTotal = inoviceTotal + tax_amount;
	// 					tax_amount = tax_amount.toFixed(2);
							
	// 					var html = "";
	// 					if ($('.tax_sec .taxId'+tax_id).length) {
	// 						var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
	// 						tax_amount = (parseFloat(tax_amount) + taxAmt);
	// 						tax_amount = tax_amount.toFixed(2);
							
	// 						html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 						html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 						html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 						html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 						html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 						$('.tax_sec .taxId'+tax_id).html(html);
	// 					}
	// 					else
	// 					{		
	// 						html += '<div class="border-bottom d-flex flex-wrap justify-content-between ptaxId'+tax_id+'">';
	// 							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 							html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 						html += '</div>';
	// 						$(".tax_sec").append(html);
	// 					}
	// 				}	
	// 			}	
	// 		}		
	// 	} 	
		
	// 	if($(".paidPlanTax").length > 0) {
			
	// 		$(".paidplanItm").each( function() {
	// 			var uid = $(this).data('id');
	// 			var itemId = parseFloat($('.cardId'+uid).find("input[name='item_id[]']").val());
				
	// 			if($("input.paidPlanTax[data-itemid='"+itemId+"']").length > 1) 
	// 			{
	// 				if(taxFormula == 1) {
	// 					var subTotal = 0; var totalTax = 0;
	// 					$("input.paidPlanTax[data-itemid='"+itemId+"']").each( function() {
	// 						totalTax = totalTax + parseFloat($(this).val());
	// 					});	
	// 					var itemPrice = parseFloat($(".itemprice"+uid).val());
	// 					var qty = 1;
	// 					itemPrice = (itemPrice * qty);
	// 					var sb = (( itemPrice * 100 ) / ( totalTax + 100 )); 
	// 					subTotal = subTotal + sb;
						
	// 					if(subTotal > 0) {
	// 						$("input.paidPlanTax[data-itemid='"+itemId+"']").each( function() {
	// 							var tax_rate = parseFloat($(this).val());
	// 							var tax_id = $(this).data('id');
	// 							var tax_name = $(this).data('name');
	// 							var tax_amount = (( subTotal * tax_rate ) / 100);
	// 							tax_amount = tax_amount.toFixed(2);
								
	// 							var html = "";
	// 							if ($('.tax_sec .taxId'+tax_id).length) {
	// 								var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
	// 								tax_amount = (parseFloat(tax_amount) + taxAmt);	
	// 								tax_amount = tax_amount.toFixed(2);
									
	// 								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 								$('.tax_sec .taxId'+tax_id).html(html);
	// 							}
	// 							else
	// 							{		
	// 								html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
	// 									html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 									html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 									html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 									html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 									html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 								html += '</div>';
	// 								$(".tax_sec").append(html);
	// 							}
	// 						});		
	// 					}	
	// 				} else {
	// 					var subTotal = 0; var totalTax = 0;
	// 					var itemPrice = parseFloat($(".itemprice"+uid).val());
	// 					var qty = 1;
	// 					itemPrice = (itemPrice * qty);
	// 					subTotal = subTotal + itemPrice;
						
	// 					if(subTotal > 0) {
	// 						$("input.paidPlanTax[data-itemid='"+itemId+"']").each( function() {
	// 							var tax_rate = parseFloat($(this).val());
	// 							var tax_id = $(this).data('id');
	// 							var tax_name = $(this).data('name');
	// 							var tax_amount = (( subTotal * tax_rate ) / 100);
	// 							inoviceTotal = inoviceTotal + tax_amount;
	// 							tax_amount = tax_amount.toFixed(2);
								
	// 							var html = "";
	// 							if ($('.tax_sec .taxId'+tax_id).length) {
	// 								var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
	// 								tax_amount = (parseFloat(tax_amount) + taxAmt);	
	// 								tax_amount = tax_amount.toFixed(2);
									
	// 								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 								$('.tax_sec .taxId'+tax_id).html(html);
	// 							}
	// 							else
	// 							{		
	// 								html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
	// 									html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 									html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 									html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 									html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 									html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 								html += '</div>';
	// 								$(".tax_sec").append(html);
	// 							}
	// 						});		
	// 					}
	// 				}		
	// 			}
	// 			else 
	// 			{
	// 				if(taxFormula == 1) {
	// 					var subTotal = 0;
	// 					var itemPrice = parseFloat($(".itemprice"+uid).val());
	// 					var qty = 1;
	// 					itemPrice = (itemPrice * qty);
	// 					subTotal = subTotal + itemPrice;
						
	// 					if(subTotal > 0) {
							
	// 						var tax_rate = parseFloat($("input.paidPlanTax[data-itemid='"+itemId+"']").val());
	// 						var tax_id = $("input.paidPlanTax[data-itemid='"+itemId+"']").data('id');
	// 						var tax_name = $("input.paidPlanTax[data-itemid='"+itemId+"']").data('name');
							
	// 						var tax_amount = (( subTotal * tax_rate ) / ( tax_rate + 100 ));
	// 						tax_amount = tax_amount.toFixed(2);
	// 						var html = "";
							
	// 						if ($('.tax_sec .taxId'+tax_id).length) {
	// 							var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
	// 							tax_amount = (parseFloat(tax_amount) + taxAmt);
	// 							tax_amount = tax_amount.toFixed(2);
								
	// 							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 							html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 							$('.tax_sec .taxId'+tax_id).html(html);
	// 						}
	// 						else
	// 						{		
	// 							html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
	// 								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 							html += '</div>';
	// 							$(".tax_sec").append(html);
	// 						}
	// 					}
	// 				} else {
	// 					var subTotal = 0;
	// 					var itemPrice = parseFloat($(".itemprice"+uid).val());
	// 					var qty = 1;
	// 					itemPrice = (itemPrice * qty);
	// 					subTotal = subTotal + itemPrice;
						
	// 					if(subTotal > 0) {
	// 						var tax_rate = parseFloat($("input.paidPlanTax[data-itemid='"+itemId+"']").val());
	// 						var tax_id = $("input.paidPlanTax[data-itemid='"+itemId+"']").data('id');
	// 						var tax_name = $("input.paidPlanTax[data-itemid='"+itemId+"']").data('name');
							
	// 						var tax_amount = (( subTotal * tax_rate ) / 100 );
	// 						inoviceTotal = inoviceTotal + tax_amount;
	// 						tax_amount = tax_amount.toFixed(2);
							
	// 						var html = "";
							
	// 						if ($('.tax_sec .taxId'+tax_id).length) {
	// 							var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
	// 							tax_amount = (parseFloat(tax_amount) + taxAmt);
	// 							tax_amount = tax_amount.toFixed(2);
								
	// 							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 							html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 							$('.tax_sec .taxId'+tax_id).html(html);
	// 						}
	// 						else
	// 						{		
	// 							html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
	// 								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
	// 								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
	// 								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
	// 								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
	// 							html += '</div>';
	// 							$(".tax_sec").append(html);
	// 						}
	// 					}
	// 				}		
	// 			}		
	// 		});	
	// 	}
		
	// 	var totalTaxAmount = 0;
	// 	$('input[name^="invoice_tax_amount"]').each(function(){
	// 		var val = parseFloat($(this).val());
	// 		totalTaxAmount = totalTaxAmount + val;
	// 	});	
	// 	var inoviceSubTotal = inoviceTotal - totalTaxAmount;
		
	// 	$("#itemSubTotal span").html(inoviceSubTotal.toFixed(2));
	// 	$(".itemSubTotal").val(inoviceSubTotal.toFixed(2));
	// 	$("#itemTotal span").html(inoviceTotal.toFixed(2));
	// 	$(".itemTotal").val(inoviceTotal.toFixed(2));
		
	// 	var finalAmount = inoviceTotal;
	// 	$(".tip-amount").each(function( index ) {
	// 		var val = parseFloat($( this ).val());
	// 		finalAmount = finalAmount + val;
	// 	});
		
	// 	$("#payamt span").html(finalAmount.toFixed(2));
	// 	$("#itemFinalTotal span").html(finalAmount.toFixed(2));
	// 	$(".itemFinalTotal").val(finalAmount.toFixed(2));
	// 	$(".totalBalance").val(finalAmount.toFixed(2));
	// }
	
	function calcTotal2(){
		var subTotal = 0;
		var Total = 0;
		var GrandTotal = 0;
		var tax_amount = 0;
		var tax_formula = null;
		
		if($("#salesItems .serviceItm, #salesItems .productItm, #salesItems .voucherItm, #salesItems .paidplanItm").length > 0) {
			$("#cartHistory").show();
			$("#completeSale").hide();
		} else {
			$(".additemsale_sec, #cartHistory").hide();
			$(".emptycart").css("display", "flex");
		}
		$('.tax_sec').html('');

		if($('.productItm').length > 0){
			$('.productItm').each(function(){

				var findTaxAmount = 0;
				var uid = $(this).find(".itmpr-hd").attr("data-id");
				var qty = 0;
				if($.trim($(".qtinpt"+uid).val()) != ''){
					qty = parseFloat($(".qtinpt"+uid).val());
				}

				var thisKeyword = $(this);

				var itemPrice = parseFloat($(this).find(".itmpr-hd").val());
				var itemId = $(this).find(".itm_id").val();
				itemPrice = (itemPrice * qty );
				
				tax_formula = $(this).find('.prodTaxFormula').val();

				var taxRateForItems = '';

				var temp_tax_id = 0;


				$(this).find('.productTax').each(function(key,value){				
					// var tax_rates = $(this).find('.productTax').val();
					// var tax_name = $(this).find('.productTax').attr('data-name');
					// var tax_id = $(this).find('.productTax').attr('data-id');
					var tax_rates = $(this).val();
					var tax_name = $(this).attr('data-name');
					var tax_id = $(this).attr('data-id');
					temp_tax_id = tax_id;

					var checkNaN = isNaN(tax_rates);
					if(checkNaN == false){
						if(tax_formula == 1){
							
							tax_amount = (( itemPrice * (tax_rates / 100 ) ) / ( 1 + (tax_rates / 100 ) ));

							findTaxAmount += tax_amount;
							taxRateForItems += tax_rates;
							if(key < ($(thisKeyword).find('.productTax').length - 1)){
								taxRateForItems += ", ";
							}
		
							var html = "";
							if ($('.tax_sec .ptaxId'+tax_id).length) {
								var taxAmt = $('.tax_sec .ptaxId'+tax_id).find("input[name='invoice_tax_amount[]']").val();
								tax_amount = (parseFloat(tax_amount) + parseFloat(taxAmt));
								tax_amount = tax_amount.toFixed(2);
							
								html += '<h5>'+tax_name+' '+tax_rates+'%</h5>';
								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rates+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								$('.tax_sec .ptaxId'+tax_id).html(html);
							}
							else
							{		
								tax_amount = tax_amount.toFixed(2);
								html += '<div class="border-bottom d-flex flex-wrap justify-content-between ptaxId'+tax_id+'">';
									html += '<h5>'+tax_name+' '+tax_rates+'%</h5>';
									html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
									html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
									html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rates+'" >';
									html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								html += '</div>';
								$(".tax_sec").append(html);
							}
						}else{
							tax_amount = (( itemPrice * tax_rates ) / 100 );
							var html = "";

							findTaxAmount += tax_amount;
							taxRateForItems += tax_rates;

							if(key < ($(thisKeyword).find('.productTax').length - 1)){
								taxRateForItems += ", ";
							}

							if ($('.tax_sec .ptaxId'+tax_id).length > 0) {
								var taxAmt = parseFloat($('.tax_sec .ptaxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
								// tax_amount = (parseFloat(tax_amount) + taxAmt);
								tax_amount =  parseFloat(tax_amount) + taxAmt;
								tax_amount = tax_amount.toFixed(2);
								
								html += '<h5>'+tax_name+' '+tax_rates+'%</h5>';
								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rates+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								$('.tax_sec .ptaxId'+tax_id).html(html);
							}
							else
							{
								tax_amount = tax_amount.toFixed(2);		
								// tax_amount = (( itemPrice * tax_rates ) / 100 );
								html += '<div class="border-bottom d-flex flex-wrap justify-content-between ptaxId'+tax_id+'">';
								html += '<h5>'+tax_name+' '+tax_rates+'%</h5>';
								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rates+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								html += '</div>';
								$(".tax_sec").append(html);
							}
						}
					}
				});

				ProTaxByItems = '<input type="hidden" class="item_tax_amount'+ itemId +'" name="item_tax_amount'+ itemId +'" value="'+findTaxAmount+'" >';
				
				ProTaxRateByItems = '<input type="hidden" class="item_tax_rate'+ itemId +'" name="item_tax_rate'+ itemId +'" value="'+taxRateForItems+'" >';

				ProTaxIdByItems = '<input type="hidden" class="item_tax_id'+ itemId +'" name="item_tax_id'+ itemId +'" value="'+temp_tax_id+'" >';

				if($(thisKeyword).find('.item_tax_amount'+ itemId +'').length){

				}else{
					$(thisKeyword).find('.productHiddenValues').append(ProTaxByItems);
					$(thisKeyword).find('.productHiddenValues').append(ProTaxRateByItems);
					$(thisKeyword).find('.productHiddenValues').append(ProTaxIdByItems);
				}

				subTotal += itemPrice - findTaxAmount;

				var totalTaxAmount = 0;
				$('input[name^="invoice_tax_amount"]').each(function(){
					var val = parseFloat($(this).val());
					totalTaxAmount = totalTaxAmount + val;
				});	
				Total = subTotal + totalTaxAmount;


				var tipAmount = 0;
				$(".tip-amount").each(function( index ) {
					var val = parseFloat($( this ).val());
					tipAmount = tipAmount + val;
				});
				GrandTotal = Total + tipAmount;

				$("#itemSubTotal span").html(subTotal.toFixed(2));
				$(".itemSubTotal").val(subTotal.toFixed(2));
				$("#itemTotal span").html(Total.toFixed(2));
				$(".itemTotal").val(Total.toFixed(2));
				
				$("#payamt span").html(GrandTotal.toFixed(2));
				$("#itemFinalTotal span").html(GrandTotal.toFixed(2));
				$(".itemFinalTotal").val(GrandTotal.toFixed(2));
				$(".totalBalance").val(GrandTotal.toFixed(2));
			});
		}
		if($(".serviceItm").length > 0){
			$('.serviceItm').each(function(){
				var uid = $(this).find(".itmpr-hd").attr("data-id");
				var qty = 0;
				if($.trim($(".qtinpt"+uid).val()) != ''){
					qty = parseFloat($(".qtinpt"+uid).val());
				}

				var thisKeyword = $(this);
				var taxRateForItems = '';
				var findTaxAmount = 0;
				var itemId = $(this).find(".itm_id").val();


				var itemPrice = parseFloat($(this).find(".itmpr-hd").val());
				itemPrice = (itemPrice * qty );
				subTotal = subTotal + itemPrice;
				tax_formula = $(this).find('.servTaxFormula').val();

				var temp_tax_id = 0;


				$(this).find('.serviceTax').each(function(key,value){				
					// var tax_rates = $(this).find('.productTax').val();
					// var tax_name = $(this).find('.productTax').attr('data-name');
					// var tax_id = $(this).find('.productTax').attr('data-id');
					var tax_rates = $(this).val();
					var tax_name = $(this).attr('data-name');
					var tax_id = $(this).attr('data-id');
					temp_tax_id = tax_id ;

					var checkNaN = isNaN(tax_rates);
					if(checkNaN == false){
						if(tax_formula == 1){
							
							tax_amount = (( itemPrice * tax_rates ) / ( tax_rates + 100 ));

							findTaxAmount += tax_amount;
							taxRateForItems += tax_rates;
							if(key < ($(thisKeyword).find('.serviceTax').length - 1)){
								taxRateForItems += ", ";
							}
		
							var html = "";
							if ($('.tax_sec .ptaxId'+tax_id).length) {
								var taxAmt = parseFloat($('.tax_sec .ptaxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
								// tax_amount = (parseFloat(tax_amount) + taxAmt);
								tax_amount =  parseFloat(tax_amount) + taxAmt;
							
								html += '<h5>'+tax_name+' '+tax_rates+'%</h5>';
								html += '<h5 class="mr-5">CA $<span>'+tax_amount.toFixed(2)+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rates+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								$('.tax_sec .ptaxId'+tax_id).html(html);
							}
							else
							{		
								html += '<div class="border-bottom d-flex flex-wrap justify-content-between ptaxId'+tax_id+'">';
									html += '<h5>'+tax_name+' '+tax_rates+'%</h5>';
									html += '<h5 class="mr-5">CA $<span>'+tax_amount.toFixed(2)+'</span></h5>';
									html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
									html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rates+'" >';
									html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								html += '</div>';
								$(".tax_sec").append(html);
							}
						}else{
							tax_amount = (( itemPrice * tax_rates ) / 100 );

							findTaxAmount += tax_amount;
							taxRateForItems += tax_rates;
							if(key < ($(thisKeyword).find('.serviceTax').length - 1)){
								taxRateForItems += ", ";
							}

							var html = "";
							if ($('.tax_sec .ptaxId'+tax_id).length > 0) {
								alert('.......');
								var taxAmt = parseFloat($('.tax_sec .ptaxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
								// tax_amount = (parseFloat(tax_amount) + taxAmt);
								tax_amount =  tax_amount + taxAmt;
								tax_amount = tax_amount;
								
								html += '<h5>'+tax_name+' '+tax_rates+'%</h5>';
								html += '<h5 class="mr-5">CA $<span>'+tax_amount.toFixed(2)+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rates+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								$('.tax_sec .ptaxId'+tax_id).html(html);
							}
							else
							{		
								// tax_amount = (( itemPrice * tax_rates ) / 100 );
								html += '<div class="border-bottom d-flex flex-wrap justify-content-between ptaxId'+tax_id+'">';
								html += '<h5>'+tax_name+' '+tax_rates+'%</h5>';
								html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount.toFixed(2)+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rates+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								html += '</div>';
								$(".tax_sec").append(html);
							}
						}
					}
				});

				ServTaxByItems = '<input type="hidden" class="item_tax_amount'+ itemId +'" name="item_tax_amount'+ itemId +'" value="'+findTaxAmount+'" >';
				
				ServTaxRateByItems = '<input type="hidden" class="item_tax_rate'+ itemId +'" name="item_tax_rate'+ itemId +'" value="'+taxRateForItems+'" >';

				ServTaxIdByItems = '<input type="hidden" class="item_tax_id'+ itemId +'" name="item_tax_id'+ itemId +'" value="'+temp_tax_id+'" >';

				if($(thisKeyword).find('.item_tax_amount'+ itemId +'').length){

				}else{
					$(thisKeyword).find('.servicesHiddenValues').append(ServTaxByItems);
					$(thisKeyword).find('.servicesHiddenValues').append(ServTaxRateByItems);
					$(thisKeyword).find('.servicesHiddenValues').append(ServTaxIdByItems);
				}

				var totalTaxAmount = 0;
				$('input[name^="invoice_tax_amount"]').each(function(){
					var val = parseFloat($(this).val());
					totalTaxAmount = totalTaxAmount + val;
				});	
				Total = subTotal + totalTaxAmount;


				var tipAmount = 0;
				$(".tip-amount").each(function( index ) {
					var val = parseFloat($( this ).val());
					tipAmount = tipAmount + val;
				});
				GrandTotal = Total + tipAmount;

				$("#itemSubTotal span").html(subTotal.toFixed(2));
				$(".itemSubTotal").val(subTotal.toFixed(2));
				$("#itemTotal span").html(Total.toFixed(2));
				$(".itemTotal").val(Total.toFixed(2));
				
				$("#payamt span").html(GrandTotal.toFixed(2));
				$("#itemFinalTotal span").html(GrandTotal.toFixed(2));
				$(".itemFinalTotal").val(GrandTotal.toFixed(2));
				$(".totalBalance").val(GrandTotal.toFixed(2));
			});
		}
		if($(".voucherItm").length > 0){
			$('.voucherItm').each(function(){
				var uid = $(this).find(".itmpr-hd").attr("data-id");
				var qty = 0;
				if($.trim($(".qtinpt"+uid).val()) != ''){
					qty = parseFloat($(".qtinpt"+uid).val());
				}

				var itemPrice = parseFloat($(this).find(".itmpr-hd").val());
				itemPrice = (itemPrice * qty );
				subTotal = subTotal + itemPrice;

				var totalTaxAmount = 0;
				$('input[name^="invoice_tax_amount"]').each(function(){
					var val = parseFloat($(this).val());
					totalTaxAmount = totalTaxAmount + val;
				});	
				Total = subTotal + totalTaxAmount;


				var tipAmount = 0;
				$(".tip-amount").each(function( index ) {
					var val = parseFloat($( this ).val());
					tipAmount = tipAmount + val;
				});
				GrandTotal = Total + tipAmount;

				$("#itemSubTotal span").html(subTotal.toFixed(2));
				$(".itemSubTotal").val(subTotal.toFixed(2));
				$("#itemTotal span").html(Total.toFixed(2));
				$(".itemTotal").val(Total.toFixed(2));
				
				$("#payamt span").html(GrandTotal.toFixed(2));
				$("#itemFinalTotal span").html(GrandTotal.toFixed(2));
				$(".itemFinalTotal").val(GrandTotal.toFixed(2));
				$(".totalBalance").val(GrandTotal.toFixed(2));
			});
		}

	}
	
	jQuery(document).on("click", "#backtopaymnt", function() {	
		$("#completeSale").hide();
		$("#cartHistory").show();
		$(".paymentDetails_sec").html("");
		var clientId = $("#clientId").val();
		var itemFinalTotal = $(".itemFinalTotal").val();
		
		if(clientId == 0) {
			$(".searchbarSec").show();
			$("#selectedClient").css("display", "none");
		}
		$("#itemFinalTotal span").html(itemFinalTotal);
		$("#payamt span").html(itemFinalTotal);
	});
	
	jQuery(document).on("keyup", ".itmpr_inpt", function() {	
		var val = parseFloat($(this).val() || 0);
		var uid = $(this).data('uid');
		var type = $(".itemtype"+uid).val();
		var qty = $(".qtinpt"+uid).val();
		var itemogprice = $(".itemogprice"+uid).val();
		
		if(val < itemogprice) {
			var itemogprice1 = (itemogprice * qty);
			var val1 = (val * qty);
			$(".itemprice"+uid).val(val);
			$(".itmpr-txt"+uid+" span").html(val1.toFixed(2));	
			$(".itmspr-txt"+uid+" span").html(itemogprice1.toFixed(2));	
			$(".itmspr-txt"+uid).removeClass("d-none");	
		} else {
			var val1 = (val * qty);
			$(".itmpr-txt"+uid+" span").html(val1.toFixed(2));
			$(".itemprice"+uid).val(val);
			$(".itmspr-txt"+uid).addClass("d-none");
		}
		// calcTotal();
		calcTotal2();	
	});
	
	jQuery(document).on("keyup", ".qty_inpt", function() {	
		var qty = $(this).val();
		var uid = $(this).data('uid');
		var itemogprice = parseFloat($(".itemogprice"+uid).val());
		var itemprice = parseFloat($(".itemprice"+uid).val());
		
		if(itemogprice > itemprice) {
			var itemogprice1 = (itemogprice * qty);
			$(".itmspr-txt"+uid+" span").html(itemogprice1.toFixed(2));
			
			var itemprice1 = (itemprice * qty);
			$(".itmpr-txt"+uid+" span").html(itemprice1.toFixed(2));
		} else {
			var itemogprice1 = (itemogprice * qty);
			$(".itmpr-txt"+uid+" span").html(itemogprice1.toFixed(2));
		}
		// calcTotal();	
		calcTotal2();		
	});
	
	jQuery(document).on("click", ".selectclient", function() {	
		var client_id = $(this).data("cid");
		var csrf = $("input[name=_token]").val();
		var url = $("#getCustomerInformation").val();
		
		$.ajax({
			type: "POST",
			url: url,
			data: {client_id : client_id,_token : csrf},
			success: function (response) 
			{
				if(response.status == true) {	
					$('#sidebar').removeClass('active');
					$('.side-overlay').removeClass('active');
					$("#clientId").val(client_id);
					$("#sidebarClient").hide();
					$(".searchbarSec").hide();
					$(".clientList").hide();
					$("#selectedClient").css("display", "flex").html(response.customerData);
					$("#clientHistory").html(response.customerHistory);
					
					if($("#salesItems .card").length > 0) {
						$('#cartHistory').show();	
					}	
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
				}
			}
		});
	});
	
	jQuery(document).on("click", "#saveTip", function() {	
		var type = $('input[name=discount-type]:checked').val(); 
		var tip = parseFloat($("#discount-input").val());
		
		if(tip > 0)
		{	
			var itemTotal = $(".itemTotal").val();
			var staffId = $("#staffTipped").val();
			var staffName = $("#staffTipped").find(':selected').text();
			
			if(type == 1) {
				var tipAmt = tip;
				tipAmt = tipAmt.toFixed(2);
			} else {
				var tipAmt = (itemTotal * tip) / 100;
				tipAmt = tipAmt.toFixed(2);
			}
			
			if ($('.stafftip_sec .stfId'+staffId).length) {
				var html = "";
				html += '<h5 class="cursor-pointer edt-tip">Tip for <span class="tip-staffname">'+staffName+'</span></h5>';
				html += '<div class="d-flex align-items-center edt-tip">';
					html += '<h5 class="cursor-pointer m-0">CA $'+tipAmt+'</h5>';
				html += '</div>';
				html += '<div class="d-flex align-items-center removeTip" data-id="'+staffId+'">';
					html += '<i data-id="'+staffId+'" class="ml-2 fa fa-times text-danger fa-1x"></i>';
				html += '</div>';
				html += '<input type="hidden" class="tip-type" name="tipType[]" value="'+type+'" >';
				html += '<input type="hidden" class="tip-val" name="tipVal[]" value="'+tip+'" >';
				html += '<input type="hidden" class="tip-amount" name="tipAmount[]" value="'+tipAmt+'" >';
				html += '<input type="hidden" class="tip-staff-id" name="tipToStaff[]" value="'+staffId+'" >';
				
				$('.stafftip_sec .stfId'+staffId).html(html);
				$("#addTipModal").modal("hide");
			} else {	
				var html = "";
				html += '<div class="text-blue border-bottom py-4 d-flex flex-wrap justify-content-between tip-row stfId'+staffId+'">';
					html += '<h5 class="cursor-pointer edt-tip">Tip for <span class="tip-staffname stfNm'+staffId+'">'+staffName+'</span></h5>';
					html += '<div class="d-flex align-items-center edt-tip">';
						html += '<h5 class="cursor-pointer m-0">CA $'+tipAmt+'</h5>';
					html += '</div>';
					html += '<div class="d-flex align-items-center removeTip" data-id="'+staffId+'">';
						html += '<i class="ml-2 fa fa-times text-danger fa-1x"></i>';
					html += '</div>';
					html += '<input type="hidden" class="tip-type" name="tipType[]" value="'+type+'" >';
					html += '<input type="hidden" class="tip-val" name="tipVal[]" value="'+tip+'" >';
					html += '<input type="hidden" class="tip-amount" name="tipAmount[]" value="'+tipAmt+'" >';
					html += '<input type="hidden" class="tip-staff-id" name="tipToStaff[]" value="'+staffId+'" >';
				html += '</div>';
				
				$("#staffTipped option[value='"+staffId+"']").remove();
				$(".stafftip_sec").append(html);
				$("#addTipModal").modal("hide");
			}
			// calcTotal();
			calcTotal2();
			if($('#staffTipped option').length == 0) {
				$(".addtip_sec").hide();
			}

			$(".searchbarSec").show();
			$("#cartHistory").show();
			$("#selectedClient").css("display", "none");
			$("#completeSale").hide();	
			$(".paymentDetails_sec").html("");
			var itemFinalTotal = $(".itemFinalTotal").val();
			$("#itemFinalTotal span").html(itemFinalTotal);
		}	
	});
	
	jQuery(document).on("click", ".removePayment", function() {	
		var payId = $(this).data("id");
		$(".paymentSec"+payId).remove();
		var pAmt = parseFloat($(".paymentSec"+payId).find("input[name='invoice_tax_amount[]']").val());
		
		$(".searchbarSec").show();
		$("#cartHistory").show();
		$("#selectedClient").css("display", "none");
		$("#completeSale").hide();	
		//$(".paymentDetails_sec").html("");
		
		var itemFinalTotal = parseFloat($(".itemFinalTotal").val());
		var pAmt = 0;
		$('input[name^="payment_amt[]"]').each( function(){
			pAmt = pAmt + parseFloat($(this).val());
		});	
		
		var rem = (itemFinalTotal - pAmt);
		rem = rem.toFixed(2);
		$(".totalBalance").val(rem);
		$("#itemFinalTotal span").html(rem);
		$("#payamt span").html(rem);
	});
	
	jQuery(document).on("click", ".removeTip", function() {	
		var staffId = $(this).data("id");
		var staffName = $(".stfNm"+staffId).text();
		$(".stfId"+staffId).remove();
		
		if($("#staffTipped option[value='"+staffId+"']").length == 0) {
			$("#staffTipped").append("<option value='"+staffId+"'>"+staffName+"</option>");
		}
		// calcTotal();
		calcTotal2();	
		$(".addtip_sec").show();
		$(".searchbarSec").show();
		$("#cartHistory").show();
		$("#selectedClient").css("display", "none");
		$("#completeSale").hide();	
		$(".paymentDetails_sec").html("");
		/* var itemFinalTotal = $(".itemFinalTotal").val();
		$("#itemFinalTotal span").html(itemFinalTotal); */
	});
	
	jQuery(document).on("click", ".edt-tip", function() {	
		var staffId = $(this).parent().find('.tip-staff-id').val();
		var type = $(this).parent().find('.tip-type').val();
		var val = $(this).parent().find('.tip-val').val();
		var amount = $(this).parent().find('.tip-amount').val();
		var staffName = $(this).parent().find('.tip-staffname').text();
		
		if($("#staffTipped option[value='"+staffId+"']").length == 0) {
			$("#staffTipped").append("<option value='"+staffId+"'>"+staffName+"</option>");
		}	
		$("#staffTipped option[value='"+staffId+"']").prop('selected', true);
		
		if(type == 1) {
			$("#discount-input").val(amount);
			$(".isPrice").show();
			$(".isPercent").hide();
		} else {
			$("#discount-input").val(val);
			$(".isPrice").hide();
			$(".isPercent").show();
		}
		$("#addTipModal").modal("show");
	});
	
	jQuery(document).on("click", ".paymentbtn", function() {	
		var type = $(this).text();
		var id = $(this).data('id');
		var itemFinalTotal = parseFloat($(".totalBalance").val());
		
		$("#paymentId").val(id);
		$("#paymentType").val(type);
		$(".searchbarSec").hide();
		$("#cartHistory").hide();
		$("#selectedClient").css("display", "flex");
		$("#completeSale").show();
		
		if($(".paymentSec"+id).length > 0) {
			var oldAmt = parseFloat($(".paymentSec"+id).find("input[name='payment_amt[]']").val());
			var new_amount = (itemFinalTotal + oldAmt);
			new_amount = new_amount.toFixed(2);
			
			var html = "";
			html += '<h5 class="cursor-pointer edt-tip">'+type+'</h5>';
			html += '<div class="d-flex align-items-center">';
				html += '<h5 class="cursor-pointer m-0">CA $'+new_amount+'</h5>';
			html += '</div>';
			html += '<div class="d-flex align-items-center removePayment" data-id="'+id+'">';
				html += '<i class="ml-2 fa fa-times text-danger fa-1x"></i>';
				html += '<input type="hidden" name="payment_amt[]" value="'+new_amount+'">';
			html += '</div>';
			$(".paymentSec"+id).html(html);
		} else {
			itemFinalTotal = itemFinalTotal.toFixed(2);
			var html = "";
			html += '<div class="text-blue border-bottom py-4 d-flex flex-wrap justify-content-between paymentSec'+id+'">';
				html += '<h5 class="cursor-pointer edt-tip">'+type+'</h5>';
				html += '<div class="d-flex align-items-center">';
					html += '<h5 class="cursor-pointer m-0">CA $'+itemFinalTotal+'</h5>';
				html += '</div>';
				html += '<div class="d-flex align-items-center removePayment" data-id="'+id+'">';
					html += '<i class="ml-2 fa fa-times text-danger fa-1x"></i>';
					html += '<input type="hidden" name="payment_amt[]" value="'+itemFinalTotal+'">';
				html += '</div>';
			html += '</div>';
			$(".paymentDetails_sec").append(html);
		}
		$("#itemFinalTotal span").html("0.00");
	});
	
	$(".modal-back").click( function(){
		var val = $(this).data("id");

		if(val == "servicesec") {
			$('.service_sec').show();	
			$('.itemresult').hide();
			$(".itemresult .list-group").html("");
			$('.modal-back').data('id','opt');
		} else if(val == "productsec") {
			$('.product_sec').show();	
			$('.itemresult').hide();
			$(".itemresult .list-group").html("");
			$('.modal-back').data('id','opt');
		}  else if(val == "paidplansec") {
			$('.saleoptions').show();
			$('.modal-back').hide();
			$('.paidplan_sec').hide();	
			$('.modal-back').data('id','opt');
		}   else if(val == "vouchersec") {
			$('.saleoptions').show();	
			$('.modal-back').hide();
			$('.voucher_sec').hide();
			$('.modal-back').data('id','opt');
		} else if(val == "opt") {
			$('.product_sec, .service_sec, .voucher_sec').hide();
			$(".saleoptions").show();
			$('.modal-back').hide();
		}		
	});
	// calcTotal();
	calcTotal2();
});

$(document).on('keyup','#voucher_code',function(e){
	if($(this).val() != '')
	{	
		var searchCode = $(this).val();
		var csrf = $("input[name=_token]").val();
		var url = $("#searchVoucherUrl").val();
		var me = $(this);
		e.preventDefault();
		
		/* if (me.data('requestRunning')) {
			return;
		}
		me.data('requestRunning', true); */
		$(".voucher-default-text").removeClass('d-flex');
		$(".voucher-default-text").addClass('d-none');
		$.ajax({
			type: 'POST',		
			data: {code : searchCode,_token : csrf},
			url: $("#searchVoucherUrl").val(),
			success: function (response)
			{
				if(response.status == true) {
					$(".voucher-nomatch").addClass('d-none');
					if($(".paymentDetails_sec .paymentSec"+searchCode).length > 0) {
						$(".voucher-rslt").html("");
						var cpndata = response.data;
						var total_value = parseFloat(cpndata.total_value || 0);
						var redeemed = parseFloat(cpndata.redeemed || 0);
						var outstanding = (total_value - redeemed);
						$(".voucher-info").removeClass('d-none');
						$(".voucher-exp").html(cpndata.end_date);
						$(".voucher-pur").html(cpndata.start_date);
						$(".voucher-outstanding span").html(outstanding);
						$(".redeem_sec").hide();
						$(".redeemed-voucher, .unpaid-voucher").addClass("d-none");
						$(".existing-voucher").removeClass("d-none");
					} else {
						$(".redeem_sec").show();
						$(".existing-voucher, .redeemed-voucher, .unpaid-voucher").addClass("d-none");
						$(".voucher-rslt").html("");
						var cpndata = response.data;
						var total_value = parseFloat(cpndata.total_value || 0);
						var redeemed = parseFloat(cpndata.redeemed || 0);
						var outstanding = (total_value - redeemed);
						$(".voucher-exp").html(cpndata.end_date);
						$(".voucher-pur").html(cpndata.start_date);
						
						if(cpndata.payment_id > 0) {
							if(outstanding > 0) {
								if(cpndata.voucher_type == 1) {
									var serviceIds = JSON.parse(cpndata.service_id);
									var is_match = 0; var totalItemAmt = 0;
									
									$("#salesItems .serviceItm").each( function() {
										var uId = $(this).data('id');
										var itemId = $(".cardId"+uId).find("input[name='item_id[]']").val();
										
										if(jQuery.inArray(itemId, serviceIds) !== -1) {
											is_match = 1;
											var itemPrice = parseFloat($(".itemprice"+uId).val());
											totalItemAmt = (totalItemAmt + itemPrice);
										}		
									});	
									
									if(is_match == 0) { 
										$(".voucher-value span").html(outstanding);
										$(".voucher-nomatch").removeClass('d-none');
									} else {
										var totalBalance = parseFloat($(".totalBalance").val());
										
										$(".tip-amount").each(function( index ) {
											var val = parseFloat($( this ).val());
											totalBalance = totalBalance - val;
										});
										
										if(totalBalance > totalItemAmt) {
											var redeemAmt = totalItemAmt;
										} else {
											var redeemAmt = totalBalance;	
										}

										if(outstanding > redeemAmt) {
											$("#redemption_amount").val(redeemAmt);
											$("#redeemed_amount").val(redeemAmt);
										} else {
											$("#redemption_amount").val(outstanding);
											$("#redeemed_amount").val(outstanding);
										}	
										
										$(".voucher-info").removeClass('d-none');
										$(".voucher-outstanding span").html(outstanding);
									}		
								} else 	{
									var totalBalance = parseFloat($(".totalBalance").val());	
									
									$(".voucher-info").removeClass('d-none');
									$(".voucher-outstanding span").html(outstanding);
									
									$(".tip-amount").each(function( index ) {
										var val = parseFloat($( this ).val());
										totalBalance = totalBalance - val;
									});
									
									if(outstanding > totalBalance) {
										$("#redemption_amount").val(totalBalance);
										$("#redeemed_amount").val(totalBalance);
									} else {
										$("#redemption_amount").val(outstanding);
										$("#redeemed_amount").val(outstanding);
									}
								}	
							} else {
								$(".voucher-rslt").html("");
								var cpndata = response.data;
								var total_value = parseFloat(cpndata.total_value || 0);
								var redeemed = parseFloat(cpndata.redeemed || 0);
								var outstanding = (total_value - redeemed);
								$(".voucher-info").removeClass('d-none');
								$(".voucher-exp").html(cpndata.end_date);
								$(".voucher-pur").html(cpndata.start_date);
								$(".voucher-outstanding span").html(outstanding);
								$(".redeem_sec").hide();
								$(".existing-voucher, .unpaid-voucher").addClass("d-none");
								$(".redeemed-voucher").removeClass("d-none");
							}
						} else {
							$(".voucher-rslt").html("");
							var cpndata = response.data;
							var total_value = parseFloat(cpndata.total_value || 0);
							var redeemed = parseFloat(cpndata.redeemed || 0);
							var outstanding = (total_value - redeemed);
							$(".voucher-info").removeClass('d-none');
							$(".voucher-exp").html(cpndata.end_date);
							$(".voucher-pur").html(cpndata.start_date);
							$(".voucher-outstanding span").html(outstanding);
							$(".redeem_sec").hide();
							$(".existing-voucher, .redeemed-voucher").addClass("d-none");
							$(".unpaid-voucher").removeClass("d-none");
						}		
					}		
				} else {
					$(".voucher-info").addClass('d-none');
					$(".voucher-nomatch").addClass('d-none');
					$(".voucher-rslt").html(response.html);
				}
			}, 
			complete: function() {
				/* setTimeout(function() {
					me.data('requestRunning', false);
				}, 500); */
			},
			timeout: 100000
		});	
	}
});

$(document).on('click','#saveUnpaid',function(e){
	if($(".stafftip_sec .tip-row").length > 0) {
		$("#saveUnpaidModal").modal('show');
	} else {
		$("#checkoutSubmitBtn").trigger('click');
	}		
});

$(document).on('click','#saveUnpaidInovice',function(e){
	$(".isRemoveTip").val(1);
	$("#checkoutSubmitBtn").trigger('click');
	$("#saveUnpaidModal").modal('hide');
});
	
$(document).on('click','#redeem_voucher',function(e){
	$("#voucherPay").modal('hide');
	var redeemed_amount = parseFloat($("#redeemed_amount").val());
	var redemption_amount = parseFloat($("#redemption_amount").val());
	var itemTotal = $(".itemTotal").val();
	var code = $("#voucher_code").val();
	var totalBalance = $(".totalBalance").val();
	
	if(redemption_amount > 0) {
		if(redemption_amount > redeemed_amount) {
			var disc_amt = redeemed_amount.toFixed(2);
		} else {
			var disc_amt = redemption_amount.toFixed(2);
		}		
		
		var balance = (totalBalance - disc_amt);
		$(".totalBalance").val(balance);
		$("#itemFinalTotal span").html(balance);
		$("#payamt span").html(balance);
		
		if(balance == 0) {
			$(".searchbarSec").hide();
			$("#cartHistory").hide();
			$("#selectedClient").css("display", "flex");
			$("#completeSale").show();
		}	
		
		var vhtml = '';
		vhtml += '<div class="text-blue border-bottom py-4 d-flex flex-wrap justify-content-between paymentSec'+code+'">';
			vhtml += '<h5 class="cursor-pointer">Voucher ('+code+')</h5>';
			vhtml += '<div class="d-flex align-items-center"><h5 class="cursor-pointer m-0">CA $'+disc_amt+'</h5></div>';
			vhtml += '<div class="d-flex align-items-center removePayment" data-id="'+code+'"><i class="ml-2 fa fa-times text-danger fa-1x"></i></div>';
			vhtml += '<input type="hidden" name="voucher_id[]" value="">';
			vhtml += '<input type="hidden" name="voucher_code[]" value="'+code+'">';
			vhtml += '<input type="hidden" name="payment_amt[]" value="'+disc_amt+'">';
		vhtml += '</div>';
		$(".paymentDetails_sec").append(vhtml);
	}	
});
	
$(document).on('keyup','.searchClients',function(e){
	var searchText = $(this).val();
	var csrf = $("input[name=_token]").val();
	e.preventDefault();
	var url = $("#searchCustomerUrl").val();
	
	$.ajax({
		type: "POST",
		url: url,
		data: {searchText : searchText,_token : csrf},
		success: function (response) 
		{
			if(response.status == true){	
				$(".searchClientDiv").html(response.html);
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
			}
		}
	});
});

var Checkout = function() {
	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
	var _handleAddCheckout = function() {
		var form = KTUtil.getById('checkoutfrm');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('checkoutSubmitBtn');
		if (!form) {
			return;
		}

		FormValidation
		    .formValidation( 
		        form,
		        {
		            fields: {
		            },
		            plugins: {
							trigger: new FormValidation.plugins.Trigger(),
							submitButton: new FormValidation.plugins.SubmitButton(),
							bootstrap: new FormValidation.plugins.Bootstrap({
						})
		            }
		        }
		    )
		    .on('core.form.valid', function() {
				// Show loading state on button
				KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");

		        var form_data = new FormData($("#checkoutfrm")[0]);
				form_data.append("paymenyReceivedyBy", $("#paymenyReceivedyBy").val());
				form_data.append("notes", $("#invoice_notes").val());
				
				$.ajax({
					type: 'POST',       
					url: formSubmitUrl,
					data: form_data,		
					dataType: 'json',					
					processData: false,
					contentType: false,
					success: function (response)
					{
						window.location.href = response.redirect;
					},
					error: function (data) {
						KTUtil.btnRelease(formSubmitButton, "Save");	
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
            _handleAddCheckout();
        }
    };
}();

var CreateVoucher = function() {
	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
	var _handleCreateVoucher = function() {
		var form = KTUtil.getById('createVoucherFrm');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('submitVoucher');
		if (!form) {
			return;
		}
		
		const checkPrice = function() {
			return {
				validate: function(input) {
					const value = input.value;
					var voucher_value = parseFloat($("#crt_voucher_value").val());
					
					if (value === '') {
						return {
							valid: true,
						};
					}
					if (voucher_value < value) {
						return {
							valid: false,
							message: 'The value must be less than or equal to voucher value',
						};
					}
					return {
						valid: true,
					};
				},
			};
		};

		FormValidation
		    .formValidation( 
		        form,
		        {
		            fields: {
						crt_voucher_value: {
							validators: {
								notEmpty: {
									message: 'This field is required'
								}
							}
						},
						crt_voucher_price: {
							validators: {
								notEmpty: {
									message: 'This field is required'
								},
								checkvalue: {
									message: ''
								}
							}
						},
						maxNumberOfSales: {
							validators: {
								notEmpty: {
									message: 'This field is required'
								}
							}
						},
						crt_voucher_name: {
							validators: {
								notEmpty: {
									message: 'This field is required'
								}
							}
						},
		            },
		            plugins: {
							trigger: new FormValidation.plugins.Trigger(),
							submitButton: new FormValidation.plugins.SubmitButton(),
							bootstrap: new FormValidation.plugins.Bootstrap({
						})
		            }
		        }
		    )
			.registerValidator('checkvalue', checkPrice)
		    .on('core.form.valid', function() {
				// Show loading state on button
				KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");
				var service_ids = "";
				
				$('#treeview input[name="value_checkbox[]"]:checked').each( function() {
					var val = $(this).val();
					service_ids = service_ids+val+",";	
				});	
				service_ids = service_ids.slice(0,-1);
				
				KTApp.blockPage();
		        var form_data = new FormData($("#createVoucherFrm")[0]);
				form_data.append("service_ids", service_ids);
				form_data.append("totalService", totalService);
				
				$.ajax({
					type: 'POST',       
					url: formSubmitUrl,
					data: form_data,		
					dataType: 'json',					
					processData: false,
					contentType: false,
					success: function (response)
					{
						var locationId = $("#locationId").val();
						var id = response.id;
						var type = "voucher";
						var csrf = $("input[name=_token]").val();
						
						$.ajax({
							type: "POST",
							url: WEBSITE_URL+"/additemcheckout",
							dataType: 'json',
							data: {id : id,type : type,locationId : locationId,_token : csrf},
							success: function (response) {
								KTApp.unblockPage();
								$("#salesItems").append(response.data);
								$(".additemsale_sec").show();
								$(".emptycart").removeClass("d-flex");
								$(".emptycart").hide();
								$('#addItemToSale').modal("hide");
								
								var taxhtml = "";
								if(response.planTax) {
									var taxes = JSON.parse(response.planTax);
									if (taxes.length > 0) {
										$.each(taxes, function(index, val) {
											var st = $(".plntax"+id+"_"+val.id).length;
											if(st == 0) {
												taxhtml += '<input type="hidden" class="paidPlanTax plntax'+id+'_'+val.id+'" value="'+val.tax_rates+'" data-Itemid="'+id+'" data-id="'+val.id+'" data-name="'+val.tax_name+'">';
											}	
										});	
										$(".taxes-list").append(taxhtml);
									}				
								}	
								// calcTotal();
								calcTotal2();
							}
						});
					},
					error: function (data) {
						KTUtil.btnRelease(formSubmitButton, "Save");	
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
            _handleCreateVoucher();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    Checkout.init();
    CreateVoucher.init();
});
