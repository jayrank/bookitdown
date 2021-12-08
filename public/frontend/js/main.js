
!(function ($) {
    "use strict";

    $('[data-toggle="counter-up"]').counterUp({
        delay: 20,
        time: 2000
    });

    // $('.skills-content').waypoint(function () {
    //     $('.progress .progress-bar').each(function () {
    //         $(this).css("width", $(this).attr("aria-valuenow") + '%');
    //     });
    // }, {
    //     offset: '80%'
    // });

    // $(".testimonials-carousel").owlCarousel({
    //     autoplay: true,
    //     dots: true,
    //     loop: true,
    //     items: 1
    // });

    // $(window).on('load', function () {
    //     var portfolioIsotope = $('.portfolio-container').isotope({
    //         layoutMode: 'fitRows'
    //     });

    //     $('#portfolio-flters li').on('click', function () {
    //         $("#portfolio-flters li").removeClass('filter-active');
    //         $(this).addClass('filter-active');

    //         portfolioIsotope.isotope({
    //             filter: $(this).data('filter')
    //         });
    //         aos_init();
    //     });

    // });

    // $(".portfolio-details-carousel").owlCarousel({
    //     autoplay: true,
    //     dots: true,
    //     loop: true,
    //     items: 1
    // });

    function aos_init() {
        AOS.init({
            duration: 1500,
            once: true
        });
    }
    $(window).on('load', function () {
        aos_init();
    });

})(jQuery);

$(document).on('keyup','#mainSearchBar, #mainSearchBarMobile',function(){
    $.ajaxSetup({
       headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
    });
    var url = $('#searchFilterUrl').val();
    var typeVal = $(this).val();
    $.ajax({
        type:'post',
        url:url,
        data:{type:typeVal},
        success:function(resp){
            if(resp.status == true){
                $('#categorySection').html(resp.cat_html)
                $('#categorySectionMobile').html(resp.cat_html)
                $('#filteredVenues').html(resp.loc_html)
                $('#filteredVenuesMobile').html(resp.loc_html)
            }
            else{
            }
        }
    });
});

$(document).ready(function(){
    var thisVal = $('#selected_id').val();
    $('#mainSearchBar').val(thisVal);
});