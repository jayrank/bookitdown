/*
Template Name: Swiggiweb - Online Food Ordering Website Template
Author: ScheduleDown
Author URI: https://themeforest.net/user/ScheduleDown
Version: 1.0
*/
(function ($) {
    "use strict"; // Start of use strict

    // Tooltip
    $('[data-toggle="tooltip"]').tooltip();


    $('.offer-slider').slick({
        //   centerMode: true,
        //   centerPadding: '30px',
        slidesToShow: 4,
        arrows: true,
        responsive: [{
            breakpoint: 768,
            settings: {
                arrows: true,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 2
            }
        }
        ]
    });


    $('.cat-slider').slick({
        //   centerMode: true,
        //   centerPadding: '30px',
        slidesToShow: 8,
        arrows: true,
        responsive: [{
            breakpoint: 768,
            settings: {
                arrows: true,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 4
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 4
            }
        }
        ]
    });

    // Trending slider

    $('.trending-slider').slick({
        //   centerMode: true,
        //   centerPadding: '30px',
        slidesToShow: 3,
        arrows: true,
        responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 1
            }
        }
        ]
    });

    $('.voucher-slider').slick({
        //   centerMode: true,
        //   centerPadding: '30px',
        slidesToShow: 3,
        arrows: true,
        responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '20px',
                slidesToShow: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '20px',
                slidesToShow: 1
            }
        }
        ]
    });
    $('.img-slider').slick({
        //   centerMode: true,
        //   centerPadding: '30px',
        slidesToShow: 4,
        arrows: true,
        responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '20px',
                slidesToShow: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '20px',
                slidesToShow: 1
            }
        }
        ]
    });

    // Most popular slider

    $('.popular-slider').slick({
        centerMode: true,
        centerPadding: '30px',
        slidesToShow: 1,
        arrows: true,
        responsive: [{
            breakpoint: 768,
            settings: {
                arrows: true,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: true,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 1
            }
        }
        ]
    });

    // Osahan Slider
    $('.osahan-slider').slick({
        centerMode: false,
        slidesToShow: 56,
        arrows: false,
        dots: true
    });

    // osahan-slider-map
    $('.osahan-slider-map').slick({
        //   centerMode: true,
        //   centerPadding: '30px',
        autoplay: true,
        slidesToShow: 5,
        arrows: true,
        responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false,
                autoplay: true,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 3
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                autoplay: true,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 3
            }
        }
        ]
    });


    var $main_nav = $('#main-nav');
    var $toggle = $('.toggle');

    var defaultOptions = {
        disableAt: false,
        customToggle: $toggle,
        levelSpacing: 40,
        // navTitle: 'ScheduleDown',
        levelTitles: true,
        levelTitleAsBack: true,
        pushContent: '#container',
        insertClose: 2
    };

    // call our plugin
    var Nav = $main_nav.hcOffcanvasNav(defaultOptions);

})(jQuery); // End of use strict