"use strict";
$(document).ready(function () {

//+++++++++++++++++++++++++++++++++++++++++++
//     bainner slider Jquery Code Start
//+++++++++++++++++++++++++++++++++++++++++++     
    $("#owl-demo").owlCarousel({
        autoPlay: true,
        navigation: true, // Show next and prev buttons
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true,
        items: 1,
        navigationText: ["prev","next"],
        pagination: false
    });

//+++++++++++++++++++++++++++++++++++++++++++
//    isotop gallry Jquery Code start
//+++++++++++++++++++++++++++++++++++++++++++

// external js: isotope.pkgd.js

// init Isotope
    var $grid = $('.grid').isotope({
        itemSelector: '.element-item',
        layoutMode: 'fitRows'
    });
// filter functions
    var filterFns = {
        // show if number is greater than 50
        numberGreaterThan50: function () {
            var number = $(this).find('.number').text();
            return parseInt(number, 10) > 50;
        },
        // show if name ends with -ium
        ium: function () {
            var name = $(this).find('.name').text();
            return name.match(/ium$/);
        }
    };
// bind filter button click
    $('.filters-button-group').on('click', 'button', function () {
        var filterValue = $(this).attr('data-filter');
        // use filterFn if matches value
        filterValue = filterFns[ filterValue ] || filterValue;
        $grid.isotope({filter: filterValue});
    });
// change is-checked class on buttons
    $('.button-group').each(function (i, buttonGroup) {
        var $buttonGroup = $(buttonGroup);
        $buttonGroup.on('click', 'button', function () {
            $buttonGroup.find('.is-checked').removeClass('is-checked');
            $(this).addClass('is-checked');
        });
    });
//+++++++++++++++++++++++++++++++++++++++++++
//     bainner slider Jquery Code Start
//+++++++++++++++++++++++++++++++++++++++++++     
    $("#blog-detail-owl").owlCarousel({
        autoPlay: true,
        navigation: true, // Show next and prev buttons
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true,
        items: 1,
        navigationText: ['<i class="fa fa-angle-left slide-angle"></i>', '<i class="fa fa-angle-right slide-angle"></i>'],
        pagination: false
    });
//+++++++++++++++++++++++++++++++++++++++++++
//     back top Jquery Code Start
//+++++++++++++++++++++++++++++++++++++++++++ 
$("#back-top").click(function () {
        $('html,body').animate({scrollTop: '0px'}, 2000);
    });

//+++++++++++++++++++++++++++++++++++++++++++
//    Pri-loding Jquery Code Start
//+++++++++++++++++++++++++++++++++++++++++++
    jQuery(window).load(function () {
        jQuery(".spinner-wrapper").delay(700).fadeOut("slow");
    });

});