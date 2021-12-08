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
	});
	
	$(".productcategory").click( function(){
		$(".saleoptions").hide();
		$(".product_sec").show();
	});
	
	$(".viewpaidplans").click( function(){
		$(".saleoptions").hide();
		$(".paidplan_sec").show();
		$('.modal-back').data('id','paidplansec');
	});
	
	$(".viewvoucher").click( function(){
		$(".saleoptions").hide();
		$(".voucher_sec").show();
		$('.modal-back').data('id','vouchersec');
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
		calcTotal();
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
		calcTotal();
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
				calcTotal();
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
		calcTotal();
	});
	
	function calcTotal()
	{
		var inoviceTotal = 0;
		$(".itmpr-hd").each(function( index ) {
			var val = parseFloat($( this ).val());
			var uid = $(this).data("id");
			var qty = parseFloat($(".qtinpt"+uid).val());
			val = (val * qty );
			inoviceTotal = inoviceTotal + val;
		});
		
		if(inoviceTotal > 0) {
			$("#cartHistory").show();
			$("#completeSale").hide();
		}
		
		var taxAmount = 0;		
		var taxFormula = $("#taxFormula").val();
		$(".tax_sec").html("");
		$(".paymentDetails_sec").html("");
		/* var itemFinalTotal = $(".itemFinalTotal").val();
		$("#itemFinalTotal span").html(itemFinalTotal);
		$("#payamt span").html(itemFinalTotal); */
		
		if($(".serviceTax").length > 0) {
			if($(".serviceTax").length > 1) {
				if(taxFormula == 1) {
					var totalTax = 0;
					$(".serviceTax").each( function() {
						totalTax = totalTax + parseFloat($(this).val());
					});
					
					var subTotal = 0;
					$(".serviceItm").each( function() {
						var uid = $(this).data("id");
						var itemPrice = parseFloat($(".itemprice"+uid).val());
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
								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
								html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								$('.tax_sec .taxId'+tax_id).html(html);
							}
							else
							{		
								html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId'+tax_id+'">';
									html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
									html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
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
					$(".serviceItm").each( function() {
						var uid = $(this).data("id");
						var itemPrice = parseFloat($(".itemprice"+uid).val());
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
								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
								html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								$('.tax_sec .taxId'+tax_id).html(html);
							}
							else
							{		
								html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId'+tax_id+'">';
									html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
									html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
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
					$(".serviceItm").each( function() {
						var uid = $(this).data("id");
						var itemPrice = parseFloat($(".itemprice"+uid).val());
						subTotal = subTotal + itemPrice;
					});	
					
					if(subTotal > 0) {
						var tax_amount = (( subTotal * tax_rate ) / ( tax_rate + 100 ));
						tax_amount = tax_amount.toFixed(2);
						var html = "";
						if ($('.tax_sec .taxId'+tax_id).length) {
							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
							html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
							$('.tax_sec .taxId'+tax_id).html(html);
						}
						else
						{		
							html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId'+tax_id+'">';
								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
								html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
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
					
					$(".serviceItm").each( function() {
						var uid = $(this).data("id");
						var itemPrice = parseFloat($(".itemprice"+uid).val());
						var qty = parseFloat($(".qtinpt"+uid).val());
						itemPrice = (itemPrice * qty);
						subTotal = subTotal + itemPrice;
					});	
					
					if(subTotal > 0) {
						var tax_amount = (( subTotal * tax_rate ) / 100 );
						inoviceTotal = inoviceTotal + tax_amount;
						tax_amount = tax_amount.toFixed(2);
							
						var html = "";
						if ($('.tax_sec .taxId'+tax_id).length) {
							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
							html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
							$('.tax_sec .taxId'+tax_id).html(html);
						}
						else
						{		
							html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId'+tax_id+'">';
								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
								html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
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
		
		if($(".productTax").length > 0) {
			if($(".productTax").length > 1) 
			{
				if(taxFormula == 1) 
				{
					var totalTax = 0;
					var subTotal = 0;
					$(".productTax").each( function() {
						totalTax = totalTax + parseFloat($(this).val());
					});
					
					$(".productItm").each( function() {
						var uid = $(this).data("id");
						var itemPrice = parseFloat($(".itemprice"+uid).val());
						var qty = parseFloat($(".qtinpt"+uid).val());
						itemPrice = (itemPrice * qty);
						var tax_amount = (( itemPrice * totalTax ) / ( totalTax + 100 ));
						var sb = (( itemPrice * 100 ) / ( totalTax + 100 )); 
						subTotal = subTotal + sb;
					});	 
					
					if(subTotal > 0)
					{	
						$(".productTax").each( function() {
							var tax_rate = parseFloat($(this).val());
							var tax_id = $(this).data("id");
							var tax_name = $(this).data("name");
							var tax_amount = (( subTotal * tax_rate ) / 100);
							tax_amount = tax_amount.toFixed(2);
							
							var html = "";
							if ($('.tax_sec .taxId'+tax_id).length) {
								var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
								tax_amount = (parseFloat(tax_amount) + taxAmt);	
								tax_amount = tax_amount.toFixed(2);
								
								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
								html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								$('.tax_sec .taxId'+tax_id).html(html);
							}
							else
							{		
								html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
									html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
									html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
									html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
									html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
									html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								html += '</div>';
								$(".tax_sec").append(html);
							}
						});	
					}	
				} 
				else 
				{	
					var subTotal = 0;
					$(".productItm").each( function() {
						var uid = $(this).data("id");
						var itemPrice = parseFloat($(".itemprice"+uid).val());
						var qty = parseFloat($(".qtinpt"+uid).val());
						itemPrice = (itemPrice * qty);
						subTotal = subTotal + itemPrice;
					});	
					
					if(subTotal > 0)
					{
						$(".productTax").each( function() {
							var tax_rate = parseFloat($(this).val());
							var tax_id = $(this).data("id");
							var tax_name = $(this).data("name");
							var tax_amount = (( subTotal * tax_rate ) / 100);
							inoviceTotal = inoviceTotal + tax_amount;
							tax_amount = tax_amount.toFixed(2);
							
							var html = "";
							if ($('.tax_sec .taxId'+tax_id).length) {
								var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
								tax_amount = (parseFloat(tax_amount) + taxAmt);
								tax_amount = tax_amount.toFixed(2);
								
								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
								html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								$('.tax_sec .taxId'+tax_id).html(html);
							}
							else
							{		
								html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
									html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
									html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
									html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
									html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
									html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								html += '</div>';
								$(".tax_sec").append(html);
							}
						});	
					}	
				}	
			} 
			else 
			{
				if(taxFormula == 1) {
					var tax_rate = parseFloat($(".productTax").val());
					var tax_id = $(".productTax").data("id");
					var tax_name = $(".productTax").data("name");
					var subTotal = 0;
					$(".productItm").each( function() {
						var uid = $(this).data("id");
						var qty = parseFloat($(".qtinpt"+uid).val());
						var itemPrice = parseFloat($(".itemprice"+uid).val());
						itemPrice = (itemPrice * qty);
						subTotal = subTotal + itemPrice;
					});
					
					if(subTotal > 0) {
						var tax_amount = (( subTotal * tax_rate ) / ( tax_rate + 100 ));
						tax_amount = tax_amount.toFixed(2);
						
						var html = "";
						
						if ($('.tax_sec .taxId'+tax_id).length) {
							var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
							tax_amount = (parseFloat(tax_amount) + taxAmt);
							tax_amount = tax_amount.toFixed(2);
							
							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
							html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
							$('.tax_sec .taxId'+tax_id).html(html);
						}
						else
						{		
							html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
								html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
							html += '</div>';
							$(".tax_sec").append(html);
						}
					}	
				} else {
					var tax_rate = parseFloat($(".productTax").val());
					var tax_id = $(".productTax").data("id");
					var tax_name = $(".productTax").data("name");
					var subTotal = 0;
					$(".productItm").each( function() {
						var uid = $(this).data("id");
						var itemPrice = parseFloat($(".itemprice"+uid).val());
						var qty = parseFloat($(".qtinpt"+uid).val());
						itemPrice = (itemPrice * qty);
						subTotal = subTotal + itemPrice;						
					});	
					
					if(subTotal > 0) 
					{	
						var tax_amount = (( subTotal * tax_rate ) / 100 );
						inoviceTotal = inoviceTotal + tax_amount;
						tax_amount = tax_amount.toFixed(2);
							
						var html = "";
						if ($('.tax_sec .taxId'+tax_id).length) {
							var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
							tax_amount = (parseFloat(tax_amount) + taxAmt);
							tax_amount = tax_amount.toFixed(2);
							
							html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
							html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
							html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
							html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
							html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
							$('.tax_sec .taxId'+tax_id).html(html);
						}
						else
						{		
							html += '<div class="border-bottom d-flex flex-wrap justify-content-between ptaxId'+tax_id+'">';
								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
								html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
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
		
		if($(".paidPlanTax").length > 0) {
			
			$(".paidplanItm").each( function() {
				var uid = $(this).data('id');
				var itemId = parseFloat($('.cardId'+uid).find("input[name='item_id[]']").val());
				
				if($("input.paidPlanTax[data-itemid='"+itemId+"']").length > 1) 
				{
					if(taxFormula == 1) {
						var subTotal = 0; var totalTax = 0;
						$("input.paidPlanTax[data-itemid='"+itemId+"']").each( function() {
							totalTax = totalTax + parseFloat($(this).val());
						});	
						var itemPrice = parseFloat($(".itemprice"+uid).val());
						var qty = 1;
						itemPrice = (itemPrice * qty);
						var sb = (( itemPrice * 100 ) / ( totalTax + 100 )); 
						subTotal = subTotal + sb;
						
						if(subTotal > 0) {
							$("input.paidPlanTax[data-itemid='"+itemId+"']").each( function() {
								var tax_rate = parseFloat($(this).val());
								var tax_id = $(this).data('id');
								var tax_name = $(this).data('name');
								var tax_amount = (( subTotal * tax_rate ) / 100);
								tax_amount = tax_amount.toFixed(2);
								
								var html = "";
								if ($('.tax_sec .taxId'+tax_id).length) {
									var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
									tax_amount = (parseFloat(tax_amount) + taxAmt);	
									tax_amount = tax_amount.toFixed(2);
									
									html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
									html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
									html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
									html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
									html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
									$('.tax_sec .taxId'+tax_id).html(html);
								}
								else
								{		
									html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
										html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
										html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
										html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
										html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
										html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
									html += '</div>';
									$(".tax_sec").append(html);
								}
							});		
						}	
					} else {
						var subTotal = 0; var totalTax = 0;
						var itemPrice = parseFloat($(".itemprice"+uid).val());
						var qty = 1;
						itemPrice = (itemPrice * qty);
						subTotal = subTotal + itemPrice;
						
						if(subTotal > 0) {
							$("input.paidPlanTax[data-itemid='"+itemId+"']").each( function() {
								var tax_rate = parseFloat($(this).val());
								var tax_id = $(this).data('id');
								var tax_name = $(this).data('name');
								var tax_amount = (( subTotal * tax_rate ) / 100);
								inoviceTotal = inoviceTotal + tax_amount;
								tax_amount = tax_amount.toFixed(2);
								
								var html = "";
								if ($('.tax_sec .taxId'+tax_id).length) {
									var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
									tax_amount = (parseFloat(tax_amount) + taxAmt);	
									tax_amount = tax_amount.toFixed(2);
									
									html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
									html += '<h5 class="mr-5">-CA $<span>'+tax_amount+'</span></h5>';
									html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
									html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
									html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
									$('.tax_sec .taxId'+tax_id).html(html);
								}
								else
								{		
									html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
										html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
										html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
										html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
										html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
										html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
									html += '</div>';
									$(".tax_sec").append(html);
								}
							});		
						}
					}		
				}
				else 
				{
					if(taxFormula == 1) {
						var subTotal = 0;
						var itemPrice = parseFloat($(".itemprice"+uid).val());
						var qty = 1;
						itemPrice = (itemPrice * qty);
						subTotal = subTotal + itemPrice;
						
						if(subTotal > 0) {
							
							var tax_rate = parseFloat($("input.paidPlanTax[data-itemid='"+itemId+"']").val());
							var tax_id = $("input.paidPlanTax[data-itemid='"+itemId+"']").data('id');
							var tax_name = $("input.paidPlanTax[data-itemid='"+itemId+"']").data('name');
							
							var tax_amount = (( subTotal * tax_rate ) / ( tax_rate + 100 ));
							tax_amount = tax_amount.toFixed(2);
							var html = "";
							
							if ($('.tax_sec .taxId'+tax_id).length) {
								var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
								tax_amount = (parseFloat(tax_amount) + taxAmt);
								tax_amount = tax_amount.toFixed(2);
								
								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
								html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								$('.tax_sec .taxId'+tax_id).html(html);
							}
							else
							{		
								html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
									html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
									html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
									html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
									html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
									html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								html += '</div>';
								$(".tax_sec").append(html);
							}
						}
					} else {
						var subTotal = 0;
						var itemPrice = parseFloat($(".itemprice"+uid).val());
						var qty = 1;
						itemPrice = (itemPrice * qty);
						subTotal = subTotal + itemPrice;
						
						if(subTotal > 0) {
							var tax_rate = parseFloat($("input.paidPlanTax[data-itemid='"+itemId+"']").val());
							var tax_id = $("input.paidPlanTax[data-itemid='"+itemId+"']").data('id');
							var tax_name = $("input.paidPlanTax[data-itemid='"+itemId+"']").data('name');
							
							var tax_amount = (( subTotal * tax_rate ) / 100 );
							inoviceTotal = inoviceTotal + tax_amount;
							tax_amount = tax_amount.toFixed(2);
							
							var html = "";
							
							if ($('.tax_sec .taxId'+tax_id).length) {
								var taxAmt = parseFloat($('.tax_sec .taxId'+tax_id).find("input[name='invoice_tax_amount[]']").val());
								tax_amount = (parseFloat(tax_amount) + taxAmt);
								tax_amount = tax_amount.toFixed(2);
								
								html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
								html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
								html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
								html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
								html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								$('.tax_sec .taxId'+tax_id).html(html);
							}
							else
							{		
								html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxId'+tax_id+'">';
									html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
									html += '<h5 class="mr-5">- CA $<span>'+tax_amount+'</span></h5>';
									html += '<input type="hidden" name="invoice_tax_amount[]" value="'+tax_amount+'" >';
									html += '<input type="hidden" name="invoice_tax_rate[]" value="'+tax_rate+'" >';
									html += '<input type="hidden" name="invoice_tax_id[]" value="'+tax_id+'" >';
								html += '</div>';
								$(".tax_sec").append(html);
							}
						}
					}		
				}		
			});	
		}
		
		var totalTaxAmount = 0;
		$('input[name^="invoice_tax_amount"]').each(function(){
			var val = parseFloat($(this).val());
			totalTaxAmount = totalTaxAmount + val;
		});	
		var inoviceSubTotal = inoviceTotal - totalTaxAmount;
		
		$("#itemSubTotal span").html(inoviceSubTotal.toFixed(2));
		$(".itemSubTotal").val(inoviceSubTotal.toFixed(2));
		$("#itemTotal span").html(inoviceTotal.toFixed(2));
		$(".itemTotal").val(inoviceTotal.toFixed(2));
		
		var finalAmount = inoviceTotal;
		$(".tip-amount").each(function( index ) {
			var val = parseFloat($( this ).val());
			finalAmount = finalAmount + val;
		}); 
		
		$("#payamt span").html(finalAmount.toFixed(2));
		$("#itemFinalTotal span").html(finalAmount.toFixed(2));
		$(".itemFinalTotal").val(finalAmount.toFixed(2));
		$(".totalBalance").val(finalAmount.toFixed(2));
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
		calcTotal();	
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
		calcTotal();		
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
			calcTotal();
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
		$(".tip-row").remove();
		
		calcTotal();	
		$(".addtip_sec").show();
		$(".searchbarSec").show();
		$("#cartHistory").show();
		$("#selectedClient").css("display", "none");
		$("#completeSale").hide();	
		$(".paymentDetails_sec").html("");
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
				html += '<h5 class="cursor-pointer m-0">- CA $'+new_amount+'</h5>';
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
					html += '<h5 class="cursor-pointer m-0">- CA $'+itemFinalTotal+'</h5>';
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
			$('.paidplan_sec').hide();	
			$('.modal-back').data('id','opt');
		}   else if(val == "vouchersec") {
			$('.saleoptions').show();	
			$('.voucher_sec').hide();
			$('.modal-back').data('id','opt');
		} else if(val == "opt") {
			$('.product_sec, .service_sec, .voucher_sec').hide();
			$(".saleoptions").show();
		}		
	});
	calcTotal();
	
	
	var client_id = $("#clientId").val();
	if(client_id > 0) 
	{	
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
					$("#clientHistory").html(response.customerHistory);
					$('#cartHistory').show();	
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
								calcTotal();
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
