// inventory module related js functions 

$(document).on('click', ".selectSupplier", function() {
    var supplier_id = $(this).attr('data-supplier_id');
    var supplier_name = $(this).attr('data-supplier_name');
    var address = $(this).attr('data-address');
    $('#supplier_name').html(supplier_name);
    $('#supplier_address').html(address);
    $("#supplier_id").val(supplier_id);
});

$(document).on('click', ".selectLocation", function() {
    var location_id = $(this).attr('data-location_id');
    var location_name = $(this).attr('data-location_name');
    var location_address = $(this).attr('data-location_address');
    $('#deliver_to').html(location_name + ' <br> ' + location_address);
    $("#location_id").val(location_id);
});

$(document).on('keyup blur', "#searchCategory", function() {
    var searchKeyWord = $(this).val();
    var csrf = $("input[name=_token]").val();
    $.ajax({
        type: "POST",
        url: WEBSITE_URL + "/order/getCategory",
        dataType: 'json',
        data: { searchKeyWord: searchKeyWord, _token: csrf },
        success: function(data) {
            //KTApp.unblockPage();
            $("#supplierCategory").html(data.html);
        }
    });
});

$(document).on('keyup blur', "#searchProduct", function() {
    var searchKeyWord = $(this).val();
    var supplier_id = $("#supplier_id").val();
    var category_id = $("#category_id").val();
    var csrf = $("input[name=_token]").val();
    $.ajax({
        type: "POST",
        url: WEBSITE_URL + "/order/getProducts",
        dataType: 'json',
        data: { searchKeyWord: searchKeyWord, category_id: category_id, supplier_id: supplier_id, _token: csrf },
        success: function(data) {
            $("#supplierProducts").html(data.html);
        }
    });
});

$(document).on('click', ".selectProductCategory", function() {
    KTApp.blockPage();
    var category_id = $(this).attr('data-category_id');
    var supplier_id = $("#supplier_id").val();
    var csrf = $("input[name=_token]").val();

    $("#category_id").val(category_id);

    $.ajax({
        type: "POST",
        url: WEBSITE_URL + "/order/getProducts",
        dataType: 'json',
        data: { category_id: category_id, supplier_id: supplier_id, _token: csrf },
        success: function(data) {
            KTApp.unblockPage();
            $("#supplierProducts").html(data.html);
            $('#cartItemView').show();
        }
    });
});

$(document).on('click', '.createOrder', function() {
    KTApp.blockPage();
    var product_id = $(this).attr('data-pro-id');
    var location_id = $('#location_id').val();
    var category_id = $('#category_id').val();
    var supplier_id = $("#supplier_id").val();
    var csrf = $("input[name=_token]").val();
    $.ajax({
        type: "POST",
        url: WEBSITE_URL + "/order/addToCartItem",
        dataType: 'json',
        data: { category_id: category_id, supplier_id: supplier_id, product_id: product_id, location_id: location_id, _token: csrf },
        success: function(data) {
            KTApp.unblockPage();
            $(".cartdesign").show();
            $(".emtyCartMessage").hide();

            $("#cartTableData").append(data.html);
            $('#addProductModal').modal('hide');
            $("#currentModalTab").val(0);
            getCartTotal();
        }
    });
});
$(document).on('input', '.cart_item_qty', function() {
    var qty = ($(this).val()) ? $(this).val() : 0;
    var uid = $(this).attr('data-uid');
    var pprice = ($("#pprice_" + uid).val()) ? $("#pprice_" + uid).val() : 0;

    // var allPrices = [];

    var total = parseFloat(pprice) * parseInt(qty);
    $('#total_pprice_' + uid).html(total.toFixed(2));
    $('#all_product_total' + uid).val(total.toFixed(2));

    /*$('.all_product_total').each(function() {
        allPrices.push($(this).val());
    });
    $('#cart_total').html(sum(allPrices).toFixed(2));
    $('#order_total').val(sum(allPrices).toFixed(2));*/
    getCartTotal();
});

$(document).on('input', '.all_product_prices', function() {
    var pprice = ($(this).val()) ? $(this).val() : 0;
    var uid = $(this).attr('data-uid');
    var qty = ($('#item_qty_' + uid).val()) ? $('#item_qty_' + uid).val() : 0;

    // var allPrices = [];

    var total = parseFloat(pprice) * parseInt(qty);

    $('#total_pprice_' + uid).html(total.toFixed(2));
    $('#all_product_total' + uid).val(total.toFixed(2));

    /*$('.all_product_total').each(function() {
        allPrices.push($(this).val());
    });
    $('#cart_total').html(sum(allPrices).toFixed(2));
    $('#order_total').val(sum(allPrices).toFixed(2));*/
    getCartTotal();
});

$("#saveOrder").on('submit', function(e) {
    e.preventDefault();
    KTApp.blockPage();
    var formData = $(this).serialize();
    var url = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: formData,
        success: function(response) {
            KTApp.unblockPage();
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
            }
        }
    });
});

$("#receiveSaveOrder").on('submit', function(e) {
    e.preventDefault();
    KTApp.blockPage();
    var formData = $(this).serialize();
    var url = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: formData,
        success: function(response) {
            KTApp.unblockPage();
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
            }
        }
    });
});

function sum(input) {
    if (toString.call(input) !== "[object Array]")
        return false;
    var total = 0;
    for (var i = 0; i < input.length; i++) {
        if (isNaN(input[i])) {
            continue;
        }
        total += Number(input[i]);
    }
    return total;
}

function getCartTotal()
{
    var allPrices = [];
    $('.all_product_total').each(function() {
        allPrices.push($(this).val());
    });
    $('#cart_total').html(sum(allPrices).toFixed(2));
    $('#order_total').val(sum(allPrices).toFixed(2));
}

function removeCartProduct(uid) {
    $("." + uid).remove();
    var rowCount = $('#cartTableData tr').length;

    if (rowCount == 0) {
        $(".cartdesign").hide();
        $(".emtyCartMessage").show();
    }

    getCartTotal();
}

$(document).on('click', '#addProductBtn', function() {
    $(".productCategoryList").show();
    $(".productList").hide();
    $("#currentModalTab").val(0);
    $(".clickOnPreviousTab").hide();
});

$(document).on('click', '.selectInStockLocation', function() {
    var location_id = $(this).attr('data-location_id');
    var in_stock = $(this).attr('data-in_stock');
    var location_name = $(this).attr('data-location_name');

    $(".addLocationStockDiv").hide();
    $(".addStockDiv").show();

    $("#instock_location_id").val(location_id);
    $(".selectLocQty").html(in_stock);
    $(".selectLocName").html(location_name);
});

$(document).on('click', '.selectOutStockLocation', function() {
    var location_id = $(this).attr('data-location_id');
    var in_stock = $(this).attr('data-in_stock');
    var location_name = $(this).attr('data-location_name');

    $(".removeLocationOutStockDiv").hide();
    $(".removeOutStockDiv").show();

    $("#outstock_location_id").val(location_id);
    $(".selectLocOutQty").html(in_stock);
    $(".selectLocOutName").html(location_name);
});

$('#increaseStockModal').on('hidden.bs.modal', function() {
    var instock_total_location = $("#instock_total_location").val();
    if (instock_total_location > 1) {
        $(".addLocationStockDiv").show();
        $(".addStockDiv").hide();
        $("#increase_stock_qty").val(0);
        $("#isPostalSame").prop('checked', false);
        $("#order_action").val('New Stock');
    } else {
        $("#increase_stock_qty").val(0);
        $("#isPostalSame").prop('checked', false);
        $("#order_action").val('New Stock');
    }
});

$('#decreaseStockModal').on('hidden.bs.modal', function() {
    var outstock_total_location = $("#outstock_total_location").val();
    if (outstock_total_location > 1) {
        $(".removeLocationOutStockDiv").show();
        $(".removeOutStockDiv").hide();
        $("#decrease_stock_qty").val(0);
        $("#out_order_action").val('Internal Use');
    } else {
        $("#decrease_stock_qty").val(0);
        $("#out_order_action").val('Internal Use');
    }
});
// inventory module related js functions 

// calander modue related js functions

// Class definition
var KTBootstrapDaterangepicker = function() {

    // Private functions
    var demos = function() {

        // predefined ranges
        var start = moment().subtract(29, 'days');
        var end = moment();

        $('#kt_daterangepicker_6').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',

            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
            $('#kt_daterangepicker_6 .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
        });

        $('#kt_daterangepicker_salesapp').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
            $('#kt_daterangepicker_salesapp .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
            $('#kt_daterangepicker_salesapp .form-control').change();
            /*var start_date = start.format('YYYY-MM-DD');
            var end_date   = end.format('YYYY-MM-DD');
            var csrf = $("input[name=_token]").val();
			
			
            	
            if (table instanceof $.fn.dataTable.Api) {
            	table.search($('#myInputTextField').val()).draw();
            } else {

            	var location_id = $("#userLocations").val();
            	var staff_id    = $("#userStaff").val();

            	$('#salesAppointmentList').DataTable().destroy();
            	var table = $('#salesAppointmentList').DataTable({
            		processing: true,
            		serverSide: true,
            		"bLengthChange": false,
            		"ordering": false,
            		ajax: {
            			type: "POST",
            			url : $("#getSalesAppointmentList").val(),
            			data: {
            				_token : csrf,
            				location_id : location_id,
            				staff_id : staff_id,
            				start_date : start_date,
            				end_date : end_date
            			}
            		},
            		columns: [
            			{data: 'ref_no', profile: 'ref_no'},
            			{data: 'client_name', name: 'client_name'},
            			{data: 'service_name', name: 'service_name'},
            			{data: 'appointment_date', name: 'appointment_date'},
            			{data: 'appointment_time', name: 'appointment_time'},
            			{data: 'duration', name: 'duration'},
            			{data: 'location_name', name: 'location_name'},
            			{data: 'staff_name', name: 'staff_name'},
            			{data: 'price', name: 'price'},
            			{data: 'status', name: 'status'},
            		]			
            	});	
            }*/
        });

        $('#kt_daterangepicker_salesInvoice').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
            $('#kt_daterangepicker_salesInvoice .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
            $('#kt_daterangepicker_salesInvoice .form-control').change();

            /*var start_date = start.format('YYYY-MM-DD');
            var end_date   = end.format('YYYY-MM-DD');
            var csrf = $("input[name=_token]").val();
			
            $('#salesInvoices').DataTable().destroy();
			
            var table = $('#salesInvoices').DataTable({
            	processing: true,
            	serverSide: true,
            	"bLengthChange": false,
            	"ordering": false,
            	ajax: {
            		type: "POST",
            		url : $("#getSalesInvoiceList").val(),
            		data: {_token : csrf,start_date : start_date,end_date : end_date}
            	},
            	columns: [
            		{data: 'invoice_id', profile: 'invoice_id'},
            		{data: 'client_name', name: 'client_name'},
            		{data: 'invoice_status', name: 'invoice_status'},
            		{data: 'invoice_date', name: 'invoice_date'},
            		{data: 'billing_name', name: 'billing_name'},
            		{data: 'location_name', name: 'location_name'},
            		{data: 'tips', name: 'tips'},
            		{data: 'gross_total', name: 'gross_total'},
            	]			
            });	*/
        });

        $('#kt_daterangepicker_dailysales').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
            $('#kt_daterangepicker_dailysales .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
            KTApp.blockPage();
            var start_date = start.format('YYYY-MM-DD');
            var end_date = end.format('YYYY-MM-DD');
            var csrf = $("input[name=_token]").val();

            var location_id = $("#userLocations").val();

            $.ajax({
                type: "POST",
                url: $("#getDailySalesFilter").val(),
                data: { start_date: start_date, end_date: end_date, location_id: location_id, _token: csrf },
                success: function(response) {
                    $("#loadTransactionSummary").html(response.htmldata);
                    KTApp.unblockPage();
                }
            });
        });

        $('#kt_daterangepicker_analytics').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
            $('#kt_daterangepicker_analytics .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));

            getAnalytics();

            // KTApp.blockPage();
            // var start_date = start.format('YYYY-MM-DD');
            // var end_date = end.format('YYYY-MM-DD');
            // var csrf = $("input[name=_token]").val();
            // var location_id = $("#userLocations").val();
            // var staff_id = $("#userStaff").val();

            // $.ajax({
            //     type: "POST",
            //     url: $("#getAnalytics").val(),
            //     data: { location_id: location_id, staff_id: staff_id, start_date: start_date, end_date: end_date, _token: csrf },
            //     dataType: 'json',
            //     success: function(response) {
            //         KTApp.unblockPage();

            //         $(".TotalAppoCounter").text(response.returnData.TotalAppoCounter);
            //         $(".CompletedAppoCounter").text(response.returnData.CompletedAppoCounter);
            //         $(".TotalCompletedPer").text(response.returnData.TotalCompletedPer);
            //         $(".NotCompletedAppoCounter").text(response.returnData.NotCompletedAppoCounter);
            //         $(".TotalNotCompletedPer").text(response.returnData.TotalNotCompletedPer);
            //         $(".CancelledAppoCounter").text(response.returnData.CancelledAppoCounter);
            //         $(".TotalCancelledPer").text(response.returnData.TotalCancelledPer);
            //         $(".NoshowAppoCounter").text(response.returnData.NoshowAppoCounter);
            //         $(".TotalNoshowPer").text(response.returnData.TotalNoshowPer);

            //         $(".TotalSale").text(response.returnData.TotalSale);
            //         $(".TotalServiceSale").text(response.returnData.TotalServiceSale);
            //         $(".TotalServiceSalePer").text(response.returnData.TotalServiceSalePer);
            //         $(".TotalProductSale").text(response.returnData.TotalProductSale);
            //         $(".TotalProductSalePer").text(response.returnData.TotalProductSalePer);
            //         $(".TotalLateCancellationFees").text(response.returnData.TotalLateCancellationFees);
            //         $(".TotalLateCancellationPer").text(response.returnData.TotalLateCancellationPer);
            //         $(".TotalNoShowFees").text(response.returnData.TotalNoShowFees);
            //         $(".TotalNoShowFeesPer").text(response.returnData.TotalNoShowFeesPer);

            //         $(".AvgTotalSale").text(response.returnData.AvgTotalSale);
            //         $(".TotalInvoices").text(response.returnData.TotalInvoices);
            //         $(".AvgServiceSale").text(response.returnData.AvgServiceSale);
            //         $(".AvgProductSale").text(response.returnData.AvgProductSale);

            //         $(".TotalOnlineAppoCounter").text(response.returnData.TotalOnlineAppoCounter);
            //         $(".CompletedOnlineAppoCounter").text(response.returnData.CompletedOnlineAppoCounter);
            //         $(".NotCompletedOnlineAppoCounter").text(response.returnData.NotCompletedOnlineAppoCounter);
            //         $(".CancelledOnlineAppoCounter").text(response.returnData.CancelledOnlineAppoCounter);
            //         $(".NoshowOnlineAppoCounter").text(response.returnData.NoshowOnlineAppoCounter);
            //         $(".TotalOnlineCompletedPer").text(response.returnData.TotalOnlineCompletedPer);
            //         $(".TotalOnlineNotCompletedPer").text(response.returnData.TotalOnlineNotCompletedPer);
            //         $(".TotalOnlineCancelledPer").text(response.returnData.TotalOnlineCancelledPer);
            //         $(".TotalOnlineNoshowPer").text(response.returnData.TotalOnlineNoshowPer);
            //         $(".OnlineAppointmentPercentage").text(response.returnData.OnlineAppointmentPercentage);
            //     }
            // });
        });
    }

    return {
        // public functions
        init: function() {
            demos();
        }
    };
}();

var KTBootstrapDatepicker = function() {
    var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }

    var editdate = ($("#new_appointment_date").val() != '') ? $("#new_appointment_date").val() : '';

    if (editdate != '') {
        var date = new Date(editdate);
    } else {
        var date = new Date();
    }

    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    // Private functions
    var demos = function() {
        // minimum setup
        $('#new_appointment_date').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            autoclose: true,
            format: 'DD, dd M yyyy',
            templates: arrows
        }).on('changeDate', function(selected) {
            var selectedDate = selected.format('yyyy-mm-dd');
            $("#appointmentStartDate").val(selectedDate);

            $(".blankRepeat").show();
            $(".fillRepeat").hide();

            $("#repeatAppointmentFrequency").val('');
            $("#repeatAppointmentCount").val('');
            $("#repeatAppointmentDate").val('');
        });

        $('#schedule_date_end').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            autoclose: true,
            format: 'D, dd M',
            orientation: "bottom left",
            templates: arrows
        }).on('changeDate', function(selected) {
            var selectedDate = selected.format('yyyy-mm-dd');
            $("#repeatAppointmentDate").val(selectedDate);
            $("#repeatDateEnd").val(selectedDate);
        });

        $('#new_appointment_date, #schedule_date_end').datepicker('setDate', today);
    }
    return {
        // public functions
        init: function() {
            demos();
        }
    };
}();

jQuery(document).ready(function() {
    KTBootstrapDaterangepicker.init();
    KTBootstrapDatepicker.init();
});

function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function removeThis(classname) {
    $("." + classname).remove();
}

function calculateHoursCharges() {
    var serviceChargeAll = 0;
    var durationSecondsAll = 0;

    $('.serviceCharge').each(function() {
        var serviceCharge = ($(this).val()) ? $(this).val() : 0;
        serviceChargeAll = serviceChargeAll + parseFloat(serviceCharge);
    });

    $('.serviceDuration').each(function() {
        var durationSeconds = ($(this).val()) ? $(this).val() : 0;
        durationSecondsAll = durationSecondsAll + parseInt(durationSeconds);
    });

    var seconds = Number(durationSecondsAll);
    var h = Math.floor(seconds % (3600 * 24) / 3600);
    var m = Math.floor(seconds % 3600 / 60);
    var hDisplay = h > 0 ? h + (h == 1 ? "h" : "h") : "";
    var mDisplay = m > 0 ? m + (m == 1 ? "min" : "min") : "";

    var hstring = '';
    if (hDisplay != '') {
        hstring = hDisplay;
    }
    if (mDisplay != '') {
        hstring = hDisplay + ' ' + mDisplay;
    }

    $(".totalCharge").html(serviceChargeAll);
    $(".totalTime").html(hstring);
}

setTimeout(function(){
    changeSelectService();
},1000);
$(document).on('change', '.serviceSelect', function() {
    changeSelectService();
});

function changeSelectService() {

    var serviceId = $('.serviceSelect').val();
    var selectedValue = $('option:selected', '.serviceSelect').attr('data-timeDuration');
    var isExtraTime = $('option:selected', '.serviceSelect').attr('data-isExtraTime');
    var extraTimeType = $('option:selected', '.serviceSelect').attr('data-extraTimeType');
    var timeExtraDuration = $('option:selected', '.serviceSelect').attr('data-timeExtraDuration');
    var serviceCharge = $('option:selected', '.serviceSelect').attr('data-serviceCharge');
    var rowValue = $('.serviceSelect').attr('data-rowNumber');

    $("." + rowValue + "Extra").remove();

    if (selectedValue == '') {
        $("." + rowValue + " .serviceDuration").attr('disabled', true);
        $("." + rowValue + " .serviceDuration").val('');
        $("." + rowValue + " .serviceCharge").val(0);
        $("." + rowValue + " .isExtraTime").val('');
        $("." + rowValue + " .extraTimeType").val('');
        $("." + rowValue + " .timeExtraDuration").val('');
    } else {
        $("." + rowValue + " .serviceDuration").attr('disabled', false);
        $("." + rowValue + " .serviceDuration").val(selectedValue);
        $("." + rowValue + " .serviceCharge").val(serviceCharge);
        $("." + rowValue + " .isExtraTime").val(isExtraTime);
        $("." + rowValue + " .extraTimeType").val(extraTimeType);
        $("." + rowValue + " .timeExtraDuration").val(timeExtraDuration);
    }

    var addExtraTime = '';
    if (isExtraTime == 1) {
        var extraTimeText = '';
        if (extraTimeType == 0) {
            extraTimeText = 'processing';
        } else if (extraTimeType == 1) {
            extraTimeText = 'blocked';
        }

        addExtraTime += '<li class="StepProgress-item current ' + rowValue + 'Extra">';
        addExtraTime += '<p>' + timeExtraDuration + 'min of ' + extraTimeText + ' time after </p>';
        addExtraTime += '</li>';

        $(".addServices").append(addExtraTime);
    }
    calculateHoursCharges();

}

$(document).on('change', '.serviceStaff', function() {
    var csrf = $("input[name=_token]").val();
    var divRowId = $(this).attr('data-rowNumber');
    var staffId = $(this).val();
    var serviceId = ($('.' + divRowId + ' .serviceSelect').val()) ? $('.' + divRowId + ' .serviceSelect').val() : 0;

    $.ajax({
        type: "POST",
        url: $("#getStaffPriceDetails").val(),
        data: { staffId: staffId, serviceId: serviceId, _token: csrf },
        dataType: 'json',
        success: function(response) {
            if (response.special_price > 0) {
                $('.' + divRowId + ' .serviceCharge').val(response.special_price);
            } else {
                $('.' + divRowId + ' .serviceCharge').val(response.price);
            }

            $('.' + divRowId + ' .serviceDuration').val(response.timeDuration);

            var ExtraTime = ($("." + divRowId + " .timeExtraDuration").val()) ? ($("." + divRowId + " .timeExtraDuration").val() * 60) : 0;
            var isNewService = $("#isNewService").val();
            var hours = 0;
            var minutes = 0;
            var actualTime = 0;
            var timeDuration = 0;

            actualTime = response.timeDuration;
            timeDuration = actualTime + ExtraTime;

            hours = Math.floor(timeDuration / 3600);
            //how many seconds are left
            timeDuration = timeDuration - (hours * 3600);
            //how many minutes fits in the amount of leftover seconds
            minutes = Math.floor(timeDuration / 60);

            if (isNewService == 1) {
                var prevStartTime = $("#prevStartTime").val();

                if (typeof prevStartTime !== "undefined" && prevStartTime != '') {
                    var new_appointment_date = $("#appointmentStartDate").val();
                    var selectedDate = new_appointment_date + ' ' + prevStartTime;

                    var jqueryDate = new Date(selectedDate);
                    jqueryDate.setHours(jqueryDate.getHours() + hours);
                    jqueryDate.setMinutes(jqueryDate.getMinutes() + minutes);

                    var newDateTime = jqueryDate.toTimeString();
                    newDateTime = newDateTime.split(' ')[0];

                    $(".addServices li:last-child .serviceStartTime").val(newDateTime);
                    $("#prevStartTime").val(newDateTime);
                } else {
                    $(".addServices li:last-child .serviceStartTime").val('');
                }
            } else {
                var prevStartTime = $('.' + divRowId + ' .serviceStartTime').val();

                if (typeof prevStartTime !== "undefined" && prevStartTime != '') {
                    var new_appointment_date = $("#appointmentStartDate").val();
                    var selectedDate = new_appointment_date + ' ' + prevStartTime;

                    var jqueryDate = new Date(selectedDate);
                    jqueryDate.setHours(jqueryDate.getHours() + hours);
                    jqueryDate.setMinutes(jqueryDate.getMinutes() + minutes);

                    var newDateTime = jqueryDate.toTimeString();
                    newDateTime = newDateTime.split(' ')[0];

                    var elem = $('.' + divRowId + ' .serviceStartTime').closest('li').next().find('.serviceStartTime');
                    $(elem).val(newDateTime);

                    $("#prevStartTime").val(newDateTime);
                } else {
                    var elem = $('.' + divRowId + ' .serviceStartTime').closest('li').next().find('.serviceStartTime');
                    $(elem).val(newDateTime);
                }
            }

            calculateHoursCharges();
            $("#isNewService").val(0);
        }
    });

});

$(document).on('change', '.serviceStaffEdit', function() {
    var csrf = $("input[name=_token]").val();
    var divRowId = $(this).attr('data-rowNumber');
    var staffId = $(this).val();
    var serviceId = ($('.' + divRowId + ' .serviceSelect').val()) ? $('.' + divRowId + ' .serviceSelect').val() : 0;

    $.ajax({
        type: "POST",
        url: $("#getStaffPriceDetails").val(),
        data: { staffId: staffId, serviceId: serviceId, _token: csrf },
        dataType: 'json',
        success: function(response) {
            if (response.special_price > 0) {
                $('.' + divRowId + ' .serviceCharge').val(response.special_price);
            } else {
                $('.' + divRowId + ' .serviceCharge').val(response.price);
            }

            $('.' + divRowId + ' .serviceDuration').val(response.timeDuration);

            var ExtraTime = ($("." + divRowId + " .timeExtraDuration").val()) ? ($("." + divRowId + " .timeExtraDuration").val() * 60) : 0;
            var isNewService = $("#isNewService").val();
            var hours = 0;
            var minutes = 0;
            var actualTime = 0;
            var timeDuration = 0;

            actualTime = response.timeDuration;
            timeDuration = actualTime + ExtraTime;

            hours = Math.floor(timeDuration / 3600);
            //how many seconds are left
            timeDuration = timeDuration - (hours * 3600);
            //how many minutes fits in the amount of leftover seconds
            minutes = Math.floor(timeDuration / 60);

            if (isNewService == 1) {
                var prevStartTime = $("#prevStartTime").val();

                if (typeof prevStartTime !== "undefined" && prevStartTime != '') {
                    var new_appointment_date = $("#appointmentStartDate").val();
                    var selectedDate = new_appointment_date + ' ' + prevStartTime;

                    var jqueryDate = new Date(selectedDate);
                    jqueryDate.setHours(jqueryDate.getHours() + hours);
                    jqueryDate.setMinutes(jqueryDate.getMinutes() + minutes);

                    var newDateTime = jqueryDate.toTimeString();
                    newDateTime = newDateTime.split(' ')[0];

                    $(".addServices li:last-child .serviceStartTime").val(newDateTime);
                    $("#prevStartTime").val(newDateTime);
                } else {
                    $(".addServices li:last-child .serviceStartTime").val('');
                }
            } else {
                var prevStartTime = $('.' + divRowId + ' .serviceStartTime').val();

                if (typeof prevStartTime !== "undefined" && prevStartTime != '') {
                    var new_appointment_date = $("#appointmentStartDate").val();
                    var selectedDate = new_appointment_date + ' ' + prevStartTime;

                    var jqueryDate = new Date(selectedDate);
                    jqueryDate.setHours(jqueryDate.getHours() + hours);
                    jqueryDate.setMinutes(jqueryDate.getMinutes() + minutes);

                    var newDateTime = jqueryDate.toTimeString();
                    newDateTime = newDateTime.split(' ')[0];

                    var elem = $('.' + divRowId + ' .serviceStartTime').closest('li').next().find('.serviceStartTime');
                    $(elem).val(newDateTime);

                    $("#prevStartTime").val(newDateTime);
                } else {
                    var elem = $('.' + divRowId + ' .serviceStartTime').closest('li').next().find('.serviceStartTime');
                    $(elem).val(newDateTime);
                }
            }

            calculateHoursCharges();
        }
    });
});

$(document).on('change', '.serviceStaff:last', function() {
    var uniqueID = makeid(15);
    var addExtraTime = '';

    var replaceid = $(".addServices li:first-child .serviceStartTime").attr('data-rownumber');

    var innerHtmlContent = $(".addServices li:first-child").html();
    var newContent = innerHtmlContent.replace(replaceid, uniqueID);
    newContent = newContent.replace(replaceid, uniqueID);
    newContent = newContent.replace(replaceid, uniqueID);
    newContent = newContent.replace(replaceid, uniqueID);

    addExtraTime += '<li class="StepProgress-item current ' + uniqueID + '">';
    addExtraTime += newContent;
    addExtraTime += '</li>';

    $(".addServices").append(addExtraTime);

    $("." + uniqueID + " .serviceSelect").val('');
    $("." + uniqueID + " .serviceDuration").val('');
    //$("." + uniqueID + " .serviceDuration").attr('disabled', true);
    $("." + uniqueID + " .serviceStaff").val('');

    $("." + uniqueID + " .serviceCharge").val(0);
    $("." + uniqueID + " .durationSeconds").val(0);
    $("." + uniqueID + " .isExtraTime").val(0);
    $("." + uniqueID + " .extraTimeType").val(0);
    $("." + uniqueID + " .timeExtraDuration").val(0);

    $("#isNewService").val(1);
});

$(document).on('change', '.serviceStaffEdit:last', function() {
    var uniqueID = makeid(15);
    var addExtraTime = '';

    var replaceid = $(".addServices li.liwithnodata:last .serviceStartTime").attr('data-rownumber');
    var innerHtmlContent = $(".addServices li.liwithnodata:last").html();

    var newContent = innerHtmlContent.replace(replaceid, uniqueID);
    newContent = newContent.replace(replaceid, uniqueID);
    newContent = newContent.replace(replaceid, uniqueID);
    newContent = newContent.replace(replaceid, uniqueID);

    addExtraTime += '<li class="StepProgress-item current ' + uniqueID + ' liwithnodata">';
    addExtraTime += newContent;
    addExtraTime += '</li>';

    $(".addServices").append(addExtraTime);
    $(".addServices li." + uniqueID + " .badge").css('display', 'block');

    $("." + uniqueID + " .serviceStartTime").val('');
    $("." + uniqueID + " .serviceSelect").val('');
    $("." + uniqueID + " .serviceDuration").val('');
    //$("." + uniqueID + " .serviceDuration").attr('disabled', true);
    $("." + uniqueID + " .serviceStaffEdit").val('');

    $("." + uniqueID + " .serviceCharge").val(0);
    $("." + uniqueID + " .durationSeconds").val(0);
    $("." + uniqueID + " .isExtraTime").val(0);
    $("." + uniqueID + " .extraTimeType").val(0);
    $("." + uniqueID + " .timeExtraDuration").val(0);
});

$(document).on('change', '.serviceDuration', function() {
    calculateHoursCharges();
});

$(document).on('change', '.serviceStartTime:last', function() {
    $("#prevStartTime").val($(this).val());
});

$(document).on('change', '.frequencySelect', function() {
    if ($(this).val() == 'no-repeat') {
        $(".frequencyEndDiv").hide();
        $(".frequencyEndDiv").val('');
        $('#schedule_date_end').val('');
    } else {
        $(".frequencyEndDiv").show();
    }
});

$(document).on('change', '.frequencyEnd', function() {
    if ($(this).val() == 'specific_date') {
        $(".frequencyDateDiv").show();
        $("#schedule_date_end").attr('required', true);
    } else {
        $(".frequencyDateDiv").hide();
        $('#schedule_date_end').val('');
        $("#schedule_date_end").attr('required', false);
    }
});

$(document).on('click', '.openRepeatModal', function() {
    $("#repeatModal").modal('show');
    $(".blankRepeat").show();
    $(".fillRepeat").hide();

    $("#repeatAppointmentFrequency").val('');
    $("#repeatAppointmentCount").val('');
    $("#repeatAppointmentDate").val('');
});

$(document).on('keyup', '.searchClients', function(e) {
    var searchText = $(this).val();
    var csrf = $("input[name=_token]").val();
    e.preventDefault();
    var url = $("#searchClientAction").val();

    $.ajax({
        type: "POST",
        url: url,
        data: { searchText: searchText, _token: csrf },
        dataType: 'json',
        success: function(response) {
            if (response.status == true) {
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

window.getClientHistory = function(client_id) {
    var url = $("#getClientInformation").val();
    var csrf = $("input[name=_token]").val();

    $.ajax({
        type: "POST",
        url: url,
        data: { client_id: client_id, _token: csrf },
        dataType: 'json',
        success: function(response) {
            if (response.status == true) {
                $('#sidebar').removeClass('active');
                $('.side-overlay').removeClass('active');

                $("#clientId").val(client_id);
                $(".searchBar").hide();
                $(".searchBlankBar").hide();
                $(".userHistory").show();
                $(".clientList").hide();

                $(".userHistory").html(response.html);
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

$(document).on('click', '.clickBlockClient', function() {
    var client_id = $(this).attr('data-client_id');
    $("#block_client_id").val(client_id);
});

$(document).on('click', '.clickUnblockClient', function() {
    var client_id = $(this).attr('data-client_id');
    $("#unblock_client_id").val(client_id);
});

$(document).on('click', '.cancelAppointmentLink', function() {
    var appointmentID = $(this).attr('data-appointmentID');
    $("#cancel_appointment_id").val(appointmentID);
});

$(document).on('click', '.appointmentNotesLink', function() {
    var appointmentID = $(this).attr('data-appointmentID');
    $("#edit_appointment_id").val(appointmentID);
});

$(document).on('change', '#block_reason', function() {
    if ($(this).val() == 'Other') {
        $(".blockNotes").show();
    } else {
        $(".blockNotes").hide();
    }
});

$(document).on('click', '.removeClientFromAppoin', function() {
    $("#clientId").val('');
    $(".searchBar").show();
    $(".searchBlankBar").show();
    $(".userHistory").hide();
    $(".userHistory").html('');
    $(".clientList").show();
});

$(document).on('change', '#changeAppointmentStatus', function(e) {
    var url = $("#appointmentStatusAction").val();
    var csrf = $("input[name=_token]").val();
    var appointment_status = $(this).val();
    var appointment_id = $(this).attr('data-appointmentID');

    $.ajax({
        type: "POST",
        url: url,
        data: { appointment_id: appointment_id, appointment_status: appointment_status, _token: csrf },
        dataType: 'json',
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
            }
        }
    });
});

$(document).on('click', '.markAsNoShow', function(e) {
    var url = $("#appointmentNoshowAction").val();
    var csrf = $("input[name=_token]").val();
    var appointment_id = $(this).attr('data-appointmentID');
    var noshow_status = $(this).attr('data-noShowStatus');

    $.ajax({
        type: "POST",
        url: url,
        data: { appointment_id: appointment_id, noshow_status: noshow_status, _token: csrf },
        dataType: 'json',
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
            }
        }
    });
});

$(document).on('click', '.markAsUndoNoShow', function(e) {
    var url = $("#appointmentNoshowAction").val();
    var csrf = $("input[name=_token]").val();
    var appointment_id = $(this).attr('data-appointmentID');
    var noshow_status = $(this).attr('data-noShowStatus');

    $.ajax({
        type: "POST",
        url: url,
        data: { appointment_id: appointment_id, noshow_status: noshow_status, _token: csrf },
        dataType: 'json',
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
            }
        }
    });
});

$(document).on('click', '.removeService', function() {
    var removeClass = $(this).attr('data-rownumber');
    $("." + removeClass).fadeOut(1000);
    $("." + removeClass + "Extra").fadeOut(1000);
    setTimeout(function() {
        $("." + removeClass).remove();
        $("." + removeClass + "Extra").remove();
    }, 1000);

    calculateHoursCharges();
});

$(document).on('click', '.removeSavedServices', function() {
    var liRawNumber = $(this).attr('data-rowNumber');
    var appointmentServiceID = $(this).attr('data-appointmentServiceID');
    var clientID = $(this).attr('data-clientID');

    var url = $("#removeAppointmentService").val();
    var csrf = $("input[name=_token]").val();

    $.ajax({
        type: "POST",
        url: url,
        data: { appointmentServiceID: appointmentServiceID, _token: csrf },
        dataType: 'json',
        success: function(response) {
            if (response.status == true) {
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
                toastr.success((response.message) ? response.message : "Something went wrong!");

                $("." + liRawNumber).fadeOut(1000);
                $("." + liRawNumber + "Extra").fadeOut(1000);
                setTimeout(function() {
                    $("." + liRawNumber).remove();
                    $("." + liRawNumber + "Extra").remove();
                }, 1000);

                calculateHoursCharges();
                getClientHistory(clientID);
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

$('#updateAppointmentConfirmation').on('hidden.bs.modal', function() {
    $("#staffFilter").trigger('change');
});

$(document).on('click', '.unlimitedStockError', function() {
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
    toastr.error("Inventory cannot be adjusted for unlimited stock products");
    return false;
});

$(document).on('click', '.markInvoiceVoid', function() {
    var invoiceID = $(this).attr('data-InvoiceID');
    $("#void_invoice_id").val(invoiceID);
});

$(document).on('click', '.printInvoice', function() {
    var url = $("#printInvoiceAction").val();
    var csrf = $("input[name=_token]").val();
    var invoiceId = $(this).attr('data-InvoiceID');

    $.ajax({
        type: "POST",
        url: url,
        data: { invoice_id: invoiceId, _token: csrf },
        success: function(response) {
            $(".printInvoiceContent").html(response);
            $.print('#printInvoiceContent');
            //printRdiv("printInvoiceContent");
            setInterval(function() {
                $(".printInvoiceContent").html('');
            }, 1000);
        }
    });
});

$(document).on('keyup', '#recipient_first_name', function() {
    var thisval = $(this).val();
    $(".personFirstName").html(thisval);
});

$(document).on('keyup', '#recipient_last_name', function() {
    var thisval = $(this).val();
    $(".personLastName").html(thisval);
});

$(document).on('keyup', '#recipient_personalized_email', function() {
    var thisval = $(this).val();
    $(".voucherPersonalizedMessage").html(thisval);
});

$(document).on('click', '#filterApplyBtn', function() {
    /*var location_id = $("#userLocations").val();
    var staff_id    = $("#userStaff").val();
    var csrf = $("input[name=_token]").val();

    var date = $('#kt_daterangepicker_salesapp .form-control').val();
    var date_array = date.split(' - ');
    if(typeof date_array[0] !== 'undefined' && date_array[0] != '') {
    	date_array[0] = new Date(date_array[0]);
    	var start_date = date_array[0].getFullYear() + '-' + (date_array[0].getMonth() + 1) + '-' + date_array[0].getDate();
    } else {
    	var start_date = '';
    }
    if(typeof date_array[1] !== 'undefined') {
    	date_array[0] = new Date(date_array[1]);
    	var end_date = date_array[1].getFullYear() + '-' + (date_array[1].getMonth() + 1) + '-' + date_array[1].getDate();
    } else {
    	var end_date = '';
    }
    		
    if (table instanceof $.fn.dataTable.Api) {
    	table.search($('#myInputTextField').val()).draw();
    } else {
    	$('#salesAppointmentList').DataTable().destroy();
    	
    	var table = $('#salesAppointmentList').DataTable({	
    		processing: true,
    		serverSide: true,
    		"bLengthChange": false,
    		"ordering": false,
    		ajax: {
    			type: "POST",
    			url : $("#getSalesAppointmentList").val(),
    			data: {
    				_token : csrf,
    				location_id : location_id,
    				staff_id : staff_id,
    				start_date : start_date,
    				end_date : end_date
    			}
    		},
    		columns: [
    			{data: 'ref_no', profile: 'ref_no'},
    			{data: 'client_name', name: 'client_name'},
    			{data: 'service_name', name: 'service_name'},
    			{data: 'appointment_date', name: 'appointment_date'},
    			{data: 'appointment_time', name: 'appointment_time'},
    			{data: 'duration', name: 'duration'},
    			{data: 'location_name', name: 'location_name'},
    			{data: 'staff_name', name: 'staff_name'},
    			{data: 'price', name: 'price'},
    			{data: 'status', name: 'status'},
    		]			
    	});	
    }*/

    // table.search($(this).val()).draw();

    document.getElementById("myFilter").style.width = "0%";
});

$(document).on('click', '#filterApplyInvoiceBtn', function() {
    /*var location_id = $("#userLocations").val();
    var csrf = $("input[name=_token]").val();
    		
    $('#salesInvoices').DataTable().destroy();
	
    var table = $('#salesInvoices').DataTable({	
    	processing: true,
    	serverSide: true,
    	"bLengthChange": false,
    	"ordering": false,
    	ajax: {
    		type: "POST",
    		url : $("#getSalesInvoiceList").val(),
    		data: {_token : csrf,location_id : location_id}
    	},
    	columns: [
    		{data: 'invoice_id', profile: 'invoice_id'},
    		{data: 'client_name', name: 'client_name'},
    		{data: 'invoice_status', name: 'invoice_status'},
    		{data: 'invoice_date', name: 'invoice_date'},
    		{data: 'billing_name', name: 'billing_name'},
    		{data: 'location_name', name: 'location_name'},
    		{data: 'tips', name: 'tips'},
    		{data: 'gross_total', name: 'gross_total'},
    	]			
    });	
	
    table.search($(this).val()).draw();*/

    document.getElementById("myFilter").style.width = "0%";
});

$(document).on('click', '#filterApplyVoucherBtn', function() {
    /*var voucher_status = $("#voucherStatus").val();
    var csrf = $("input[name=_token]").val();
    		
    $('#salesVoucher').DataTable().destroy();
	
    var table = $('#salesVoucher').DataTable({	
    	processing: true,
    	serverSide: true,
    	"bLengthChange": false,
    	"ordering": false,
    	ajax: {
    		type: "POST",
    		url : $("#getSalesVoucherList").val(),
    		data: {_token : csrf,voucher_status : voucher_status}
    	},
    	columns: [
    		{data: 'issue_date', profile: 'issue_date'},
    		{data: 'expiry_date', name: 'expiry_date'},
    		{data: 'invoice_no', name: 'invoice_no'},
    		{data: 'client_name', name: 'client_name'},
    		{data: 'voucher_type', name: 'voucher_type'},
    		{data: 'voucher_status', name: 'voucher_status'},
    		{data: 'voucher_code', name: 'voucher_code'},
    		{data: 'voucher_total', name: 'voucher_total'},
    		{data: 'redeemed_amount', name: 'redeemed_amount'},
    		{data: 'remaining_amount', name: 'remaining_amount'},
    	]			
    });	
	
    table.search($(this).val()).draw();*/

    document.getElementById("myFilter").style.width = "0%";
});

$(document).on('click', '.getVoucherService', function() {
    var url = $("#getVoucherServices").val();
    var voucherId = $(this).attr('data-voucherID');
    var csrf = $("input[name=_token]").val();

    $.ajax({
        type: "POST",
        url: url,
        data: { voucherId: voucherId, _token: csrf },
        success: function(response) {
            $("#viewVoucherServices").modal('show');
            $("#internalBody").html(response.htmldata);
        }
    });
});

$(document).on('click', '#filterApplyDailysaleBtn', function() {
    var location_id = $("#userLocations").val();

    var datefilter = $("#kt_datepicker_1").val();
    var start_date = '';
    var end_date = '';

    if (datefilter != '') {
        var expDate = datefilter.split("-");

        var expstartdate = expDate[0].trim();
        var date = new Date(expstartdate),
            yr = date.getFullYear(),
            month = (date.getMonth()+1) < 10 ? '0' + (date.getMonth()+1) : (date.getMonth()+1),
            day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
            start_date = yr + '-' + month + '-' + day;

        var expenddate = expDate[1].trim();
        var date2 = new Date(expenddate),
            yr2 = date2.getFullYear(),
            month2 = (date2.getMonth()+1) < 10 ? '0' + (date2.getMonth()+1) : (date2.getMonth()+1),
            day2 = date2.getDate() < 10 ? '0' + date2.getDate() : date2.getDate(),
            end_date = yr2 + '-' + month2 + '-' + day2;
    }

    KTApp.blockPage();
    $.ajax({
        type: "POST",
        url: $("#getDailySalesFilter").val(),
        data: { start_date: start_date, end_date: end_date, location_id: location_id, _token: $("input[name=_token]").val() },
        dataType: 'json',
        success: function(response) {
            $("#loadTransactionSummary").html(response.htmldata);
            KTApp.unblockPage();
        }
    });

    document.getElementById("myFilter").style.width = "0%";
});

$(document).on('click', '.generateDailySales', function() {
    KTApp.blockPage();

    var location_id = $("#userLocations").val();
    var datefilter = $("#kt_datepicker_1").val();

    if (datefilter != '') {
        var expDate = datefilter.split("-");

        var expstartdate = expDate[0].trim();
        var date = new Date(expstartdate),
            yr = date.getFullYear(),
            month = (date.getMonth()+1) < 10 ? '0' + (date.getMonth()+1) : (date.getMonth()+1),
            day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
            startdate = yr + '-' + month + '-' + day;

        var expenddate = expDate[1].trim();
        var date2 = new Date(expenddate),
            yr2 = date2.getFullYear(),
            month2 = (date2.getMonth()+1) < 10 ? '0' + (date2.getMonth()+1) : (date2.getMonth()+1),
            day2 = date2.getDate() < 10 ? '0' + date2.getDate() : date2.getDate(),
            enddate = yr2 + '-' + month2 + '-' + day2;

        var fileName = 'daily_sale_' + startdate + '_' + enddate + '.pdf';
    } else {
        var fileName = 'daily_sale.pdf';
    }

    $.ajax({
        type: "POST",
        url: $("#getDailySalesPDF").val(),
        data: { location_id: location_id, datefilter: datefilter, _token: $("input[name=_token]").val() },
        xhrFields: {
            responseType: 'blob'
        },
        success: function(response) {
            KTApp.unblockPage();

            var blob = new Blob([response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = fileName;
            link.click();
        },
        timeout: 5000,
        error: function(e) {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            toastr.error('Request timeout, Please try again later!');

            KTApp.unblockPage();
            return false;
        }
    });
});

$(document).on('click', '#filterApplyAnalyticsBtn', function() {

    // var date = $("#kt_datepicker_1").val();

    // if(date != '') {
    //     var new1 = date.split('-');
    //     var startdate = new Date(new1[0]);
    //     var enddate = new Date(new1[1]);
    //     var start_date = moment(startdate).format('YYYY-MM-DD');
    //     var end_date = moment(enddate).format('YYYY-MM-DD');
    // } else {
    //     var new1 = '';
    //     var startdate = '';
    //     var enddate = '';
    //     var start_date = '';
    //     var end_date = '';
    // }

    // var location_id = $("#userLocations").val();
    // var staff_id = $("#userStaff").val();

    // KTApp.blockPage();
    // $.ajax({
    //     type: "POST",
    //     url: $("#getAnalytics").val(),
    //     data: { location_id: location_id, staff_id: staff_id, start_date: start_date, end_date: end_date, _token: $("input[name=_token]").val() }, //
    //     dataType: 'json',
    //     success: function(response) {
    //         KTApp.unblockPage();

    //         $(".TotalAppoCounter").text(response.returnData.TotalAppoCounter);
    //         $(".CompletedAppoCounter").text(response.returnData.CompletedAppoCounter);
    //         $(".TotalCompletedPer").text(response.returnData.TotalCompletedPer);
    //         $(".NotCompletedAppoCounter").text(response.returnData.NotCompletedAppoCounter);
    //         $(".TotalNotCompletedPer").text(response.returnData.TotalNotCompletedPer);
    //         $(".CancelledAppoCounter").text(response.returnData.CancelledAppoCounter);
    //         $(".TotalCancelledPer").text(response.returnData.TotalCancelledPer);
    //         $(".NoshowAppoCounter").text(response.returnData.NoshowAppoCounter);
    //         $(".TotalNoshowPer").text(response.returnData.TotalNoshowPer);

    //         $(".TotalSale").text(response.returnData.TotalSale);
    //         $(".TotalServiceSale").text(response.returnData.TotalServiceSale);
    //         $(".TotalServiceSalePer").text(response.returnData.TotalServiceSalePer);
    //         $(".TotalProductSale").text(response.returnData.TotalProductSale);
    //         $(".TotalProductSalePer").text(response.returnData.TotalProductSalePer);
    //         $(".TotalLateCancellationFees").text(response.returnData.TotalLateCancellationFees);
    //         $(".TotalLateCancellationPer").text(response.returnData.TotalLateCancellationPer);
    //         $(".TotalNoShowFees").text(response.returnData.TotalNoShowFees);
    //         $(".TotalNoShowFeesPer").text(response.returnData.TotalNoShowFeesPer);

    //         $(".AvgTotalSale").text(response.returnData.AvgTotalSale);
    //         $(".TotalInvoices").text(response.returnData.TotalInvoices);
    //         $(".AvgServiceSale").text(response.returnData.AvgServiceSale);
    //         $(".AvgProductSale").text(response.returnData.AvgProductSale);

    //         $(".TotalOnlineAppoCounter").text(response.returnData.TotalOnlineAppoCounter);
    //         $(".CompletedOnlineAppoCounter").text(response.returnData.CompletedOnlineAppoCounter);
    //         $(".NotCompletedOnlineAppoCounter").text(response.returnData.NotCompletedOnlineAppoCounter);
    //         $(".CancelledOnlineAppoCounter").text(response.returnData.CancelledOnlineAppoCounter);
    //         $(".NoshowOnlineAppoCounter").text(response.returnData.NoshowOnlineAppoCounter);
    //         $(".TotalOnlineCompletedPer").text(response.returnData.TotalOnlineCompletedPer);
    //         $(".TotalOnlineNotCompletedPer").text(response.returnData.TotalOnlineNotCompletedPer);
    //         $(".TotalOnlineCancelledPer").text(response.returnData.TotalOnlineCancelledPer);
    //         $(".TotalOnlineNoshowPer").text(response.returnData.TotalOnlineNoshowPer);
    //         $(".OnlineAppointmentPercentage").text(response.returnData.OnlineAppointmentPercentage);
    //     }
    // });
    getAnalytics();

    document.getElementById("myFilter").style.width = "0%";
});

$(document).on("click", ".applyPaymentLink", function() {
    var type = $(this).text();
    var id = $(this).data('id');
    var itemFinalTotal = parseFloat($(".totalBalance").val());

    $("#paymentId").val(id);
    $("#paymentType").val(type);

    $("#cartHistory").hide();
    $("#completeSale").show();
});

$(document).on('click', '#applyPaymentBack', function() {
    $("#paymentId").val('');
    $("#paymentType").val('');

    $("#cartHistory").show();
    $("#completeSale").hide();
});

$(document).on('click', '#confirmInvoiceDetails', function() {
    var paymenyReceivedyBy = $("#paymenyReceivedyBy").val();
    var invoice_notes = $("#invoice_notes").val();

    $("#paymenyReceivedyByField").val(paymenyReceivedyBy);
    $("#paymenyReceivedyNotes").val(invoice_notes);
});

$(document).on('click', '#saveUnpaid', function() {
    $("#isUnpaid").val(1);
    $("#checkoutSubmitBtn").trigger('click');
});

$(document).on('keyup', '#voucher_code', function(e) {
    if ($(this).val() != '') {
        var searchCode = $(this).val();
        var csrf = $("input[name=_token]").val();
        var url = $("#searchVoucherUrl").val();
        var me = $(this);
        e.preventDefault();

        $(".voucher-default-text").removeClass('d-flex');
        $(".voucher-default-text").addClass('d-none');
        $.ajax({
            type: 'POST',
            data: { code: searchCode, _token: csrf },
            url: $("#searchVoucherUrl").val(),
            success: function(response) {
                if (response.status == true) {
                    $(".voucher-nomatch").addClass('d-none');
                    if ($(".paymentDetails_sec .paymentSec" + searchCode).length > 0) {
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

                        if (cpndata.payment_id > 0) {
                            if (outstanding > 0) {
                                if (cpndata.voucher_type == 1) {
                                    var serviceIds = JSON.parse(cpndata.service_id);
                                    var is_match = 0;
                                    var totalItemAmt = 0;

                                    $("#salesItems .serviceItm").each(function() {
                                        var uId = $(this).data('id');
                                        var itemId = $(".cardId" + uId).find("input[name='item_id[]']").val();

                                        if (jQuery.inArray(itemId, serviceIds) !== -1) {
                                            is_match = 1;
                                            var itemPrice = parseFloat($(".itemprice" + uId).val());
                                            totalItemAmt = (totalItemAmt + itemPrice);
                                        }
                                    });

                                    if (is_match == 0) {
                                        $(".voucher-value span").html(outstanding);
                                        $(".voucher-nomatch").removeClass('d-none');
                                    } else {
                                        var totalBalance = parseFloat($(".totalBalance").val());

                                        $(".tip-amount").each(function(index) {
                                            var val = parseFloat($(this).val());
                                            totalBalance = totalBalance - val;
                                        });

                                        if (totalBalance > totalItemAmt) {
                                            var redeemAmt = totalItemAmt;
                                        } else {
                                            var redeemAmt = totalBalance;
                                        }

                                        if (outstanding > redeemAmt) {
                                            $("#redemption_amount").val(redeemAmt);
                                            $("#redeemed_amount").val(redeemAmt);
                                        } else {
                                            $("#redemption_amount").val(outstanding);
                                            $("#redeemed_amount").val(outstanding);
                                        }

                                        $(".voucher-info").removeClass('d-none');
                                        $(".voucher-outstanding span").html(outstanding);
                                    }
                                } else {
                                    var totalBalance = parseFloat($(".totalBalance").val());

                                    $(".voucher-info").removeClass('d-none');
                                    $(".voucher-outstanding span").html(outstanding);

                                    $(".tip-amount").each(function(index) {
                                        var val = parseFloat($(this).val());
                                        totalBalance = totalBalance - val;
                                    });

                                    if (outstanding > totalBalance) {
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

$(document).on('click', '.dailySalesExcelExport', function() {
    KTApp.blockPage();

    var location_id = $("#userLocations").val();
    var datefilter = $("#kt_datepicker_1").val();

    if (datefilter != '') {
        var expDate = datefilter.split("-");

        var expstartdate = expDate[0].trim();
        var date = new Date(expstartdate),
            yr = date.getFullYear(),
            month = (date.getMonth()+1) < 10 ? '0' + (date.getMonth()+1) : (date.getMonth()+1),
            day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
            startdate = yr + '-' + month + '-' + day;

        var expenddate = expDate[1].trim();
        var date2 = new Date(expenddate),
            yr2 = date2.getFullYear(),
            month2 = (date2.getMonth()+1) < 10 ? '0' + (date2.getMonth()+1) : (date2.getMonth()+1),
            day2 = date2.getDate() < 10 ? '0' + date2.getDate() : date2.getDate(),
            enddate = yr2 + '-' + month2 + '-' + day2;

        var fileName = 'daily_sale_' + startdate + '_' + enddate + '.xls';
    } else {
        var fileName = 'daily_sale.xls';
    }

    $.ajax({
        type: "POST",
        url: $("#dailySalesExcelExport").val(),
        data: { location_id: location_id, datefilter: datefilter, fileName: fileName, _token: $("input[name=_token]").val() },
        xhrFields: {
            responseType: 'blob'
        },
        success: function(response) {
            KTApp.unblockPage();

            var blob = new Blob([response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = fileName;
            link.click();
        },
        timeout: 5000,
        error: function(e) {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            toastr.error('Request timeout, Please try again later!');

            KTApp.unblockPage();
            return false;
        }
    });
});

$(document).on('click', '.dailySalesCsvExport', function() {
    KTApp.blockPage();

    var location_id = $("#userLocations").val();
    var datefilter = $("#kt_datepicker_1").val();

    if (datefilter != '') {
        var expDate = datefilter.split("-");

        var expstartdate = expDate[0].trim();
        var date = new Date(expstartdate),
            yr = date.getFullYear(),
            month = (date.getMonth()+1) < 10 ? '0' + (date.getMonth()+1) : (date.getMonth()+1),
            day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
            startdate = yr + '-' + month + '-' + day;

        var expenddate = expDate[1].trim();
        var date2 = new Date(expenddate),
            yr2 = date2.getFullYear(),
            month2 = (date2.getMonth()+1) < 10 ? '0' + (date2.getMonth()+1) : (date2.getMonth()+1),
            day2 = date2.getDate() < 10 ? '0' + date2.getDate() : date2.getDate(),
            enddate = yr2 + '-' + month2 + '-' + day2;

        var fileName = 'daily_sale_' + startdate + '_' + enddate + '.csv';
    } else {
        var fileName = 'daily_sale.csv';
    }

    $.ajax({
        type: "POST",
        url: $("#dailySalesCsvExport").val(),
        data: { location_id: location_id, datefilter: datefilter, fileName: fileName, _token: $("input[name=_token]").val() },
        xhrFields: {
            responseType: 'blob'
        },
        success: function(response) {
            KTApp.unblockPage();

            var blob = new Blob([response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = fileName;
            link.click();
        },
        timeout: 5000,
        error: function(e) {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            toastr.error('Request timeout, Please try again later!');

            KTApp.unblockPage();
            return false;
        }
    });
});

$(document).on('click', '.sendEmailReminder', function() {
    var consultationFormId = $(this).attr('data-consultationFormId');
    $("#sendEmailReminder").modal('show');
    $("#CSID").val(consultationFormId);
});

function printDiv(id) 
{

  var divToPrint=document.getElementById(id);

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="">'+divToPrint.innerHTML+'<script> window.print() </script></body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},1000);

}
