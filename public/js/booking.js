jQuery(document).ready(function() {

    $('.service-chk').on('click', function(e) {
        var nm = $(this).attr("name");

        if ($(this).is(':checked')) {
            if ($(this).hasClass('subServ')) {
                $("input[name=" + nm + "]").prop("checked", false);
            }
            $(this).prop("checked", true);
        } else {
            //$("input[name="+nm+"]").prop("checked", false);
        }

        $("#select-service-list").html("");

        $(".service-chk").each(function() {
            var uniqId = $(this).data("unqid");
            var sId = $(this).data("sid");
            var snm = $(".servicedtl" + uniqId).find(".service-name").val();
            var subnm = $(".servicedtl" + uniqId).find(".service-subname").val();
            var spr = parseFloat($(".servicedtl" + uniqId).find(".service-pr span").text());
            var duration = $(".servicedtl" + uniqId).find(".service-duration").text();

            spr = (spr > 0) ? spr : 0;

            if ($(this).prop('checked') == true) {
                var html = "";

                html += '<div class="mb-3 servRw itemRw' + uniqId + '">';
                html += '<span class="title d-flex justify-content-between">';
                html += '<h6>' + snm + '</h6>';
                if (spr > 0) {
                    html += '<h6 class="text-muted">$' + spr + '</h6>';
                } else {
                    html += '<h6 class="text-muted">Free</h6>';
                }
                html += '</span>';
                html += '<span class="title d-flex justify-content-between">';
                html += '<h6 class="text-muted">' + duration + ' ' + subnm + '</h6>';
                html += '<input type="hidden" name="itemPrId[]" value="' + sId + '">';
                html += '<input type="hidden" name="itemPr[]" value="' + spr + '">';
                html += '<input type="hidden" name="itemType[]" value="service">';
                html += '</span>';
                html += '</div>';

                if ($(".servRw").length > 0) {
                    $("#select-service-list").append(html);
                } else {
                    $("#select-service-list").html(html);
                }
            } else {
                $(".itemRw" + uniqId).remove();
            }
        });

        calcTotal();
    });

    $(document).on("click", ".noprefernce", function() {
        var staffId = $(".staffListSec .selStaff").data("stid");
        var userId = $("#userId").val();
        var csrf = $("input[name=_token]").val();
        var locationID = $('#locationID').val();
        var serviceIds = [];
        $("#staffId").val(staffId);

        $(".service-chk").each(function() {
            var sId = $(this).data("sid");
            if ($(this).prop('checked') == true) {
                serviceIds.push(sId);
            }
        });

        $.ajax({
            type: "POST",
            url: WEBSITE_URL + "/getBookingSlot",
            dataType: 'json',
            data: { service_ids: serviceIds, userId: userId, staffId: staffId, locationID: locationID, _token: csrf },
            success: function(response) {

                if (response.status == true) {
                    var serviceData = response.data;
                    var timeData = response.availability;
                    var shtml = "";
                    $("#select-service-list").html("");

                    $.each(serviceData, function(index, val) {
                        var html = "";
                        html += '<div class="mb-3 servRw itemRw">';
                        html += '<span class="title d-flex justify-content-between">';
                        html += '<h6>' + val.service_name + '</h6>';
                        if (val.service_spprice > 0) {
                            html += '<h6 class="text-muted">$' + val.service_spprice + '</h6>';
                        } else {
                            html += '<h6 class="text-muted">Free</h6>';
                        }
                        html += '</span>';
                        html += '<span class="title d-flex justify-content-between">';
                        html += '<h6 class="text-muted">' + val.service_duration_txt + '</h6>';
                        html += '<input type="hidden" name="itemPrId[]" value="' + val.service_price_id + '">';
                        html += '<input type="hidden" name="itemPr[]" value="' + val.service_spprice + '">';
                        html += '<input type="hidden" name="itemDur[]" value="' + val.service_duration + '">';
                        html += '<input type="hidden" name="itemType[]" value="service">';
                        html += '</span>';
                        html += '</div>';
                        $("#select-service-list").append(html);
                    });

                    var timeHtml = "";

                    $(".user_availability").html("");

                    if(response.closed) {
                        timeHtml += response.closedMessage;
                        $(".user_availability").html(timeHtml);
                    } else if (timeData.length > 0) {
                        $.each(timeData, function(index, times) {
                            var slots = times;
                            $.each(slots, function(ind, val) {
                                if (val.booked) {
                                    var bookedClass = 'disabled';
                                } else {
                                    var bookedClass = '';
                                }
                                timeHtml += '<a href="javascript:;" class="' + bookedClass + ' list-group-item d-flex justify-content-between align-items-center selTimeSlots" data-booktime="' + val.start + '">';
                                timeHtml += '<h6 class="mb-0">' + val.start + ' to ' + val.end + '</h6><i class="fa fa-chevron-right"></i>';
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

    $(document).on("click", ".selStaff", function() {
        var staffId = $(this).data("stid");
        var userId = $("#userId").val();
        var csrf = $("input[name=_token]").val();
        var locationID = $('#locationID').val();
        var serviceIds = [];
        $("#staffId").val(staffId);

        $(".service-chk").each(function() {
            var sId = $(this).data("sid");
            if ($(this).prop('checked') == true) {
                serviceIds.push(sId);
            }
        });

        $.ajax({
            type: "POST",
            url: WEBSITE_URL + "/getBookingSlot",
            dataType: 'json',
            data: { service_ids: serviceIds, userId: userId, staffId: staffId, locationID: locationID, _token: csrf },
            success: function(response) {

                if (response.status == true) {
                    var serviceData = response.data;
                    var timeData = response.availability;
                    var shtml = "";
                    $("#select-service-list").html("");

                    $.each(serviceData, function(index, val) {
                        // console.log("val : "+val);
                        var html = "";
                        html += '<div class="mb-3 servRw itemRw">';
                        html += '<span class="title d-flex justify-content-between">';
                        html += '<h6>' + val.service_name + '</h6>';
                        if (val.service_spprice > 0) {
                            html += '<h6 class="text-muted">$' + val.service_spprice + '</h6>';
                        } else {
                            html += '<h6 class="text-muted">Free</h6>';
                        }
                        html += '</span>';
                        html += '<span class="title d-flex justify-content-between">';
                        html += '<h6 class="text-muted">' + val.service_duration_txt + '</h6>';
                        html += '<input type="hidden" name="itemPrId[]" value="' + val.service_price_id + '">';
                        html += '<input type="hidden" name="itemPr[]" value="' + val.service_spprice + '">';
                        html += '<input type="hidden" name="itemDur[]" value="' + val.service_duration + '">';
                        html += '<input type="hidden" name="itemType[]" value="service">';
                        html += '</span>';
                        html += '</div>';
                        $("#select-service-list").append(html);
                    });

                    var timeHtml = "";

                    $(".user_availability").html("");

                    if(response.closed) {
                        timeHtml += response.closedMessage;
                        $(".user_availability").html(timeHtml);
                    } else if (timeData.length > 0) {
                        $.each(timeData, function(index, times) {
                            var slots = times;
                            $.each(slots, function(ind, val) {
                                if (val.booked) {
                                    var bookedClass = 'disabled';
                                } else {
                                    var bookedClass = '';
                                }
                                timeHtml += '<a href="javascript:;" class="' + bookedClass + ' list-group-item d-flex justify-content-between align-items-center selTimeSlots" data-booktime="' + val.start + '">';
                                timeHtml += '<h6 class="mb-0">' + val.start + ' to ' + val.end + '</h6><i class="fa fa-chevron-right"></i>';
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

    $(document).on("click", "#submitBooking", function() {
        var form_data = new FormData($("#bookingfrm")[0]);
        var formSubmitUrl = $("#bookingfrm").attr('action');

        $.ajax({
            type: 'POST',
            url: formSubmitUrl,
            data: form_data,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                $("#success_div").removeClass("d-none");
                $("#bookingfrm").addClass("d-none");

                setTimeout(function() {
                    window.location.href = response.redirect;
                }, 1500);
            },
            error: function(data) {

                var errors = data.responseJSON;

                var errorsHtml = '';
                $.each(errors.error, function(key, value) {
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

    $(document).on("click", ".selTimeSlots", function() {
        var loggedUserId = $("#loggedUserId").val();

        if (loggedUserId == '') {
            $("#submitBooking").attr('disabled', true);
        } else {
            var time = $(this).data('booktime');
            $("#bookingTime").val(time);
        }
        nextPrev(1);
    });
});

function calcTotal() {
    var cartTotal = 0;
    var inoviceTotal = 0;
    $("input[name='itemPr[]']").each(function() {
        var total = parseFloat($(this).val());
        inoviceTotal = inoviceTotal + total;
    });

    var taxAmount = 0;
    var taxFormula = $("#taxFormula").val();


    if ($(".serviceTax").length > 0) {
        if ($(".serviceTax").length > 1) {
            if (taxFormula == 1) {
                var totalTax = 0;
                $(".serviceTax").each(function() {
                    totalTax = totalTax + parseFloat($(this).val());
                });

                var subTotal = 0;
                $("input[name='itemPr[]']").each(function() {
                    var itemPrice = parseFloat($(this).val());
                    var tax_amount = ((itemPrice * totalTax) / (totalTax + 100));
                    var sb = ((itemPrice * 100) / (totalTax + 100));
                    subTotal = subTotal + sb;
                });

                if (subTotal > 0) {
                    $(".serviceTax").each(function() {
                        var taxAmount = 0;
                        var tax_rate = parseFloat($(this).val());
                        var tax_id = $(this).data("id");
                        var tax_name = $(this).data("name");
                        var tax_amount = ((subTotal * tax_rate) / 100);
                        tax_amount = tax_amount.toFixed(2);

                        var html = "";
                        if ($('.tax_sec .taxId' + tax_id).length) {
                            /* html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
                            html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>'; */
                            html += '<input type="hidden" name="invoice_tax_amount[]" value="' + tax_amount + '" >';
                            html += '<input type="hidden" name="invoice_tax_rate[]" value="' + tax_rate + '" >';
                            html += '<input type="hidden" name="invoice_tax_id[]" value="' + tax_id + '" >';
                            $('.tax_sec .taxId' + tax_id).html(html);
                        } else {
                            html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId' + tax_id + '">';
                            /* html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
                            html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>'; */
                            html += '<input type="hidden" name="invoice_tax_amount[]" value="' + tax_amount + '" >';
                            html += '<input type="hidden" name="invoice_tax_rate[]" value="' + tax_rate + '" >';
                            html += '<input type="hidden" name="invoice_tax_id[]" value="' + tax_id + '" >';
                            html += '</div>';
                            $(".tax_sec").append(html);
                        }
                    });
                }
            } else {
                var subTotal = 0;
                $("input[name='itemPr[]']").each(function() {
                    var itemPrice = parseFloat($(this).val());
                    subTotal = subTotal + itemPrice;
                });

                if (subTotal > 0) {
                    $(".serviceTax").each(function() {
                        var taxAmount = 0;
                        var tax_rate = parseFloat($(this).val());
                        var tax_id = $(this).data("id");
                        var tax_name = $(this).data("name");
                        var tax_amount = ((subTotal * tax_rate) / 100);
                        inoviceTotal = inoviceTotal + tax_amount;
                        tax_amount = tax_amount.toFixed(2);

                        var html = "";
                        if ($('.tax_sec .taxId' + tax_id).length) {
                            /* html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
                            html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>'; */
                            html += '<input type="hidden" name="invoice_tax_amount[]" value="' + tax_amount + '" >';
                            html += '<input type="hidden" name="invoice_tax_rate[]" value="' + tax_rate + '" >';
                            html += '<input type="hidden" name="invoice_tax_id[]" value="' + tax_id + '" >';
                            $('.tax_sec .taxId' + tax_id).html(html);
                        } else {
                            html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId' + tax_id + '">';
                            /* html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
                            html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>'; */
                            html += '<input type="hidden" name="invoice_tax_amount[]" value="' + tax_amount + '" >';
                            html += '<input type="hidden" name="invoice_tax_rate[]" value="' + tax_rate + '" >';
                            html += '<input type="hidden" name="invoice_tax_id[]" value="' + tax_id + '" >';
                            html += '</div>';
                            $(".tax_sec").append(html);
                        }
                    });
                }
            }
        } else {
            if (taxFormula == 1) {
                var tax_rate = parseFloat($(".serviceTax").val());
                var tax_id = $(".serviceTax").data("id");
                var tax_name = $(".serviceTax").data("name");

                var subTotal = 0;
                $("input[name='itemPr[]']").each(function() {
                    var itemPrice = parseFloat($(this).val());
                    subTotal = subTotal + itemPrice;
                });

                if (subTotal > 0) {
                    var tax_amount = ((subTotal * tax_rate) / (tax_rate + 100));
                    tax_amount = tax_amount.toFixed(2);
                    var html = "";
                    if ($('.tax_sec .taxId' + tax_id).length) {
                        /* html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
                        html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>'; */
                        html += '<input type="hidden" name="invoice_tax_amount[]" value="' + tax_amount + '" >';
                        html += '<input type="hidden" name="invoice_tax_rate[]" value="' + tax_rate + '" >';
                        html += '<input type="hidden" name="invoice_tax_id[]" value="' + tax_id + '" >';
                        $('.tax_sec .taxId' + tax_id).html(html);
                    } else {
                        html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId' + tax_id + '">';
                        /* html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
                        html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>'; */
                        html += '<input type="hidden" name="invoice_tax_amount[]" value="' + tax_amount + '" >';
                        html += '<input type="hidden" name="invoice_tax_rate[]" value="' + tax_rate + '" >';
                        html += '<input type="hidden" name="invoice_tax_id[]" value="' + tax_id + '" >';
                        html += '</div>';
                        $(".tax_sec").append(html);
                    }
                }
            } else {
                var tax_rate = parseFloat($(".serviceTax").val());
                var tax_id = $(".serviceTax").data("id");
                var tax_name = $(".serviceTax").data("name");
                var subTotal = 0;

                $("input[name='itemPr[]']").each(function() {
                    var itemPrice = parseFloat($(this).val());
                    subTotal = subTotal + itemPrice;
                });

                if (subTotal > 0) {
                    var tax_amount = ((subTotal * tax_rate) / 100);
                    inoviceTotal = inoviceTotal + tax_amount;
                    tax_amount = tax_amount.toFixed(2);

                    var html = "";
                    if ($('.tax_sec .taxId' + tax_id).length) {
                        /* html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
                        html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>'; */
                        html += '<input type="hidden" name="invoice_tax_amount[]" value="' + tax_amount + '" >';
                        html += '<input type="hidden" name="invoice_tax_rate[]" value="' + tax_rate + '" >';
                        html += '<input type="hidden" name="invoice_tax_id[]" value="' + tax_id + '" >';
                        $('.tax_sec .taxId' + tax_id).html(html);
                    } else {
                        html += '<div class="border-bottom d-flex flex-wrap justify-content-between taxSec taxId' + tax_id + '">';
                        /* html += '<h5>'+tax_name+' '+tax_rate+'%</h5>';
                        html += '<h5 class="mr-5">CA $<span>'+tax_amount+'</span></h5>'; */
                        html += '<input type="hidden" name="invoice_tax_amount[]" value="' + tax_amount + '" >';
                        html += '<input type="hidden" name="invoice_tax_rate[]" value="' + tax_rate + '" >';
                        html += '<input type="hidden" name="invoice_tax_id[]" value="' + tax_id + '" >';
                        html += '</div>';
                        $(".tax_sec").append(html);
                    }
                }
            }
        }
    }

    var totalTaxAmount = 0;
    $('input[name^="invoice_tax_amount"]').each(function() {
        var val = parseFloat($(this).val());
        totalTaxAmount = totalTaxAmount + val;
    });

    if (totalTaxAmount > 0 && inoviceTotal > 0) {
        $(".taxlbl").html("Taxes");
        $(".taxAmt").html("$" + totalTaxAmount.toFixed(2));
    } else {
        $(".taxlbl").html("");
        $(".taxAmt").html("");
    }

    if (inoviceTotal > 0) {
        $(".cartTotal").html("$ " + inoviceTotal.toFixed(2));
    } else {
        $(".cartTotal").html("Free");
    }
    $("#inoviceTotal").val(inoviceTotal.toFixed(2));

    var totalServ = 0;
    $(".service-chk").each(function() {
        if ($(this).prop('checked') == true) {
            totalServ = totalServ + 1;
        }
    });
    $(".totalService").html(totalServ + " Service");
}

var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n, prev = 0) {
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
        tab[n].style.display = "block";
        $("#select-service-list").html("");
        $(".service-chk").each(function() {
            var uniqId = $(this).attr("data-unqid");
            var sId = $(this).attr("data-sid");
            var snm = $(".servicedtl" + uniqId).find(".service-name").val();
            var subnm = $(".servicedtl" + uniqId).find(".service-subname").val();
            var spr = parseFloat($(".servicedtl" + uniqId).find(".service-pr span").text());
            var duration = $(".servicedtl" + uniqId).find(".service-duration").text();
            spr = (spr > 0) ? spr : 0;
            // console.log("sId : "+sId);
            if ($(this).prop('checked') == true) {
                var html = "";

                html += '<div class="mb-3 servRw itemRw' + uniqId + '">';
                html += '<span class="title d-flex justify-content-between">';
                html += '<h6>' + snm + '</h6>';
                if (spr > 0) {
                    html += '<h6 class="text-muted">$' + spr + '</h6>';
                } else {
                    html += '<h6 class="text-muted">Free</h6>';
                }
                html += '</span>';
                html += '<span class="title d-flex justify-content-between">';
                html += '<h6 class="text-muted">' + duration + ' ' + subnm + '</h6>';
                html += '<input type="hidden" name="itemPrId[]" value="' + sId + '">';
                html += '<input type="hidden" name="itemPr[]" value="' + spr + '">';
                html += '<input type="hidden" name="itemType[]" value="service">';
                html += '</span>';
                html += '</div>';

                if ($(".servRw").length > 0) {
                    $("#select-service-list").append(html);
                } else {
                    $("#select-service-list").html(html);
                }
            } else {
                $(".itemRw" + uniqId).remove();
            }
        });

        calcTotal();
        $(".next-step").show();
        $(".steps").text("1");
        $(".page-title").text("Select services");
        $(".service-menu").css('display', 'block');
    } else if (n == 1) {

        if (prev == 1 && $("#is_skip_staff").val() == 1) {
            console.log("is skip");
            nextPrev(-1);
        } else {
            $(".next-step").hide();
            var locationID = $('#locationID').val();
            var staffId = $("#selStaffId").val();
            var userId = $("#userId").val();
            var csrf = $("input[name=_token]").val();
            var serviceIds = [];

            $(".service-chk").each(function() {
                var sId = $(this).data("sid");
                if ($(this).prop('checked') == true) {
                    serviceIds.push(sId);
                }
            });
            $(".staffListSec").html("");
            $.ajax({
                type: "POST",
                url: WEBSITE_URL + "/getStaffList",
                dataType: 'json',
                data: { service_ids: serviceIds, userId: userId, staffId: staffId, locationID: locationID, _token: csrf },
                success: function(response) {
                    tab[n].style.display = "block";
                    if (response.status == true) {
                        $("#is_skip_staff").val(response.is_skip_staff);
                        if (response.is_skip_staff == 0) {
                            var staffData = response.data;

                            if (staffData.length > 0) {
                                var shtml = "";
                                $(".noprefernce").removeClass("d-none");
                                $(".noprefernce").addClass("d-flex");
                                $.each(staffData, function(index, val) {
                                    shtml += '<a href="javascript:;" class="p-4 d-flex justify-content-between align-items-center list-group-item list-group-item-action selStaff" data-stid="' + val.staff_id + '">';
                                    shtml += '<div class="staff-flexbox">';
                                    shtml += '<div class="user-symbol">' + val.staff_name.slice(0, 1) + '</div>';
                                    shtml += '</div>';
                                    shtml += '<div class="staff-flexbox">';
                                    shtml += '<h6 class="font-weight-bolder m-0">' + val.staff_name + '</h6>';
                                    shtml += '</div>';
                                    shtml += '<div class="staff-flexbox">';
                                    if (val.total_amount > 0) {
                                        if (parseFloat(val.total_amount) >= parseFloat(val.total_special_amount)) {
                                            shtml += '<h6 class="font-weight-bolder mb-0">$ ' + val.total_special_amount + '</h6>';
                                            shtml += '<h6 class="mb-0 text-muted"><strike>$ ' + val.total_amount + '</strike></h6>';
                                        } else {
                                            shtml += '<h6 class="font-weight-bolder mb-0">$ ' + val.total_amount + '</h6>';
                                        }
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
                                html += '<h6>' + val.service_name + '</h6>';
                                if (val.service_spprice > 0) {
                                    html += '<h6 class="text-muted">$' + val.service_spprice + '</h6>';
                                } else {
                                    html += '<h6 class="text-muted">Free</h6>';
                                }
                                html += '</span>';
                                html += '<span class="title d-flex justify-content-between">';
                                html += '<h6 class="text-muted">' + val.service_duration_txt + '</h6>';
                                html += '<input type="hidden" name="itemPrId[]" value="' + val.service_price_id + '">';
                                html += '<input type="hidden" name="itemPr[]" value="' + val.service_spprice + '">';
                                html += '<input type="hidden" name="itemDur[]" value="' + val.service_duration + '">';
                                html += '<input type="hidden" name="itemType[]" value="service">';
                                html += '</span>';
                                html += '</div>';
                                $("#select-service-list").append(html);
                            });

                            var timeHtml = "";

                            $(".user_availability").html("");
                            if (timeData.length > 0) {
                                $.each(timeData, function(index, times) {
                                    var slots = times;
                                    $.each(slots, function(ind, val) {

                                        if (val.booked) {
                                            var bookedClass = 'disabled';
                                        } else {
                                            var bookedClass = '';
                                        }
                                        timeHtml += '<a href="javascript:;" class="' + bookedClass + ' list-group-item d-flex justify-content-between align-items-center selTimeSlots" data-booktime="' + val.start + '">';
                                        timeHtml += '<h6 class="mb-0">' + val.start + ' to ' + val.end + '</h6><i class="fa fa-chevron-right"></i>';
                                        timeHtml += '</a>';
                                    });
                                });
                            } else {
                                timeHtml += '<a href="javascript:;" class="list-group-item d-flex justify-content-between align-items-center">';
                                timeHtml += '<h6 class="mb-0">Sorry, you can book on this day.</h6>';
                                timeHtml += '</a>';
                            }
                            $(".user_availability").html(timeHtml);

                            calcTotal();
                            nextPrev(1);
                        }
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
                }
            });

            $(".previous").show();
            $(".steps").text("2");
            $(".page-title").text("Select staff");
            $(".service-menu").css('display', 'none');
        }
    } else if (n == 2) {
        $('#my_calendar_simple').rescalendar({
            id: 'my_calendar_simple',
            jumpSize: 3,
            calSize: 6,
            dataKeyField: 'name',
            dataKeyValues: ['']
        });
        tab[n].style.display = "block";
        $(".next-step").hide();
        $(".previous").show();
        $(".steps").text("3");
        $(".page-title").text("Select time");
        $(".service-menu").css('display', 'none');
    } else if (n == 3) {

        tab[n].style.display = "block";
        $(".previous").show();
        $(".steps").text("4");
        $(".page-title").text("Review and confirm");
        $(".service-menu").css('display', 'none');
    }
}

function nextPrev(n) {
    // This function will figure out which tab to display
    var tab = document.getElementsByClassName("step-tab-content");

    var prev = 0;
    if (n < 0) {
        var prev = 1;
    }
    // Hide the current tab:
    tab[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;
    // if you have reached the end of the tab
    // Otherwise, display the correct tab:
    showTab(currentTab, prev);
}

// Class Initialization
jQuery(document).ready(function() {

});

$(document).on('click', '.showSignupForm', function() {
    $(".commonClass").hide();
    $(".signupWithEmailStep").show();
});

$(document).on('click', '.showLoginForm', function() {
    $(".commonClass").hide();
    $(".loginWithEmailStep").show();
});

$(document).on('click', '.showForgotpasswordForm', function() {
    $(".commonClass").hide();
    $(".forgotEmailStep").show();
});

window.showError = function(message) {
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

window.showSuccess = function(message) {
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
    if (!regex.test(email)) {
        return false;
    } else {
        return true;
    }
}

$(document).on('click', '#registerFrontUser', function() {
    var csrf = $("input[name=_token]").val();
    var front_name = $("#front_name").val();
    var front_lastname = $("#front_lastname").val();
    var front_mobilenumber = $("#front_mobilenumber").val();
    var front_countrycode = $("#front_countrycode").val();
    var front_email = $("#front_email").val();
    var front_password = $("#front_password").val();
    var front_termsprivacy = $("#front_termsprivacy").val();

    var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    if (front_name == '') {
        $("#front_name").focus();
        showError('First name is required.');
        return false;
    } else if (front_lastname == '') {
        $("#front_lastname").focus();
        showError('Last name is required.');
        return false;
    } else if (front_mobilenumber == '') {
        $("#front_mobilenumber").focus();
        showError('Mobile number is required.');
        return false;
    } else if (front_email == '') {
        $("#front_email").focus();
        showError('Email is required.');
        return false;
    } else if (IsEmail(front_email) == false) {
        $("#front_email").focus();
        showError('Please provide valid email address.');
        return false;
    } else if (front_password == '') {
        $("#front_password").focus();
        showError('Password field is required.');
        return false;
    } else if (front_password.length < 8) {
        $("#front_password").focus();
        showError('Minimum 8 characters required for password.');
        return false;
    } else if ($('#front_termsprivacy').prop("checked") == false) {
        showError('You have to agree with our terms and conditions.');
        return false;
    } else {
        $("#registerFrontUser").attr('disabled', true);
        $("#registerFrontUser").text("Please wait...");

        $.ajax({
            type: "POST",
            url: WEBSITE_URL + "/bookingFlowSignup",
            dataType: 'json',
            data: { front_name: front_name, front_lastname: front_lastname, front_countrycode: front_countrycode, front_mobilenumber: front_mobilenumber, front_email: front_email, front_password: front_password, _token: csrf },
            success: function(response) {
                console.log(response);
                if (response.status == true) {
                    $("#registerFrontUser").attr('disabled', false);
                    $("#registerFrontUser").text("Sign up");

                    $("#loggedUserId").val(response.USERID);

                    $(".loginSettings").hide();
                    $(".noteSettings").show();
                    $("#submitBooking").attr('disabled', false);

                    showSuccess(response.message);
                    return true;
                } else {
                    $("#registerFrontUser").attr('disabled', false);
                    $("#registerFrontUser").text("Sign up");

                    showError(response.message);
                    return false;
                }
            },
            timeout: 5000,
            error: function(e) {
                $("#registerFrontUser").attr('disabled', false);
                $("#registerFrontUser").text("Sign up");

                var errors = data.responseJSON;

                var errorsHtml = '';
                $.each(errors.errors, function(key, value) {
                    errorsHtml += value[0];
                });

                showError((errorsHtml) ? errorsHtml : 'Request timeout, Please try again later!');
                return false;
            }
        });
    }
});

$(document).on('click', '#loginFrontUser', function() {
    var csrf = $("input[name=_token]").val();
    var front_login_email = $("#front_login_email").val();
    var front_login_password = $("#front_login_password").val();

    var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    if (front_login_email == '') {
        $("#front_login_email").focus();
        showError('Email is required.');
        return false;
    } else if (IsEmail(front_login_email) == false) {
        $("#front_login_email").focus();
        showError('Please provide valid email address.');
        return false;
    } else if (front_login_password == '') {
        $("#front_login_password").focus();
        showError('Password field is required.');
        return false;
    } else {
        $("#loginFrontUser").attr('disabled', true);
        $("#loginFrontUser").text("Please wait...");

        $.ajax({
            type: "POST",
            url: WEBSITE_URL + "/bookingFlowLogin",
            dataType: 'json',
            data: { front_login_email: front_login_email, front_login_password: front_login_password, _token: csrf },
            success: function(response) {
                if (response.status == true) {
                    $("#loginFrontUser").attr('disabled', false);
                    $("#loginFrontUser").text("Log in");

                    $("#loggedUserId").val(response.USERID);

                    $(".loginSettings").hide();
                    $(".noteSettings").show();
                    $("#submitBooking").attr('disabled', false);

                    showSuccess(response.message);
                    return true;
                } else {
                    $("#loginFrontUser").attr('disabled', false);
                    $("#loginFrontUser").text("Log in");

                    showError(response.message);
                    return false;
                }
            },
            timeout: 5000,
            error: function(e) {
                $("#loginFrontUser").attr('disabled', false);
                $("#loginFrontUser").text("Log in");

                var errors = data.responseJSON;

                var errorsHtml = '';
                $.each(errors.errors, function(key, value) {
                    errorsHtml += value[0];
                });

                showError((errorsHtml) ? errorsHtml : 'Request timeout, Please try again later!');
                return false;
            }
        });
    }
});