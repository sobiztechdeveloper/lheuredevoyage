/* =====================================================
Template Name   : Tavelo
Description     : Travel Booking HTML5 Template
Author          : LunarTemp
Version         : 1.0
=======================================================*/


(function ($) {
    "use strict";


    // multi level dropdown menu
    $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
        }
        var $subMenu = $(this).next('.dropdown-menu');
        $subMenu.toggleClass('show');

        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
            $('.dropdown-submenu .show').removeClass('show');
        });
        return false;
    });



    // data-background    
    $(document).on('ready', function () {
        $("[data-background]").each(function () {
            $(this).css("background-image", "url(" + $(this).attr("data-background") + ")");
        });
    });


    // wow init
    new WOW().init();


    // hero slider
    $('.hero-slider').owlCarousel({
        loop: true,
        nav: true,
        dots: false,
        margin: 0,
        autoplay: true,
        autoplayHoverPause: true,
        autoplayTimeout: 5000,
        items: 1,
        navText: [
            "<i class='far fa-long-arrow-left'></i>",
            "<i class='far fa-long-arrow-right'></i>"
        ],

        onInitialized: function (event) {
            var $firstAnimatingElements = $('.owl-item').eq(event.item.index).find("[data-animation]");
            doAnimations($firstAnimatingElements);
        },

        onChanged: function (event) {
            var $firstAnimatingElements = $('.owl-item').eq(event.item.index).find("[data-animation]");
            doAnimations($firstAnimatingElements);
        }
    });

    //hero slider do animations
    function doAnimations(elements) {
        var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        elements.each(function () {
            var $this = $(this);
            var $animationDelay = $this.data('delay');
            var $animationDuration = $this.data('duration');
            var $animationType = 'animated ' + $this.data('animation');
            $this.css({
                'animation-delay': $animationDelay,
                '-webkit-animation-delay': $animationDelay,
                'animation-duration': $animationDuration,
                '-webkit-animation-duration': $animationDuration,
            });
            $this.addClass($animationType).one(animationEndEvents, function () {
                $this.removeClass($animationType);
            });
        });
    }



    // hero-slider 2
    $('.hero-slider-2').owlCarousel({
        loop: true,
        nav: false,
        dots: false,
        margin: 0,
        animateOut: 'fadeOut',
        autoplay: true,
        autoplayHoverPause: true,
        autoplayTimeout: 5000,
        items: 1,
    });

   

    // partner-slider
    $('.partner-slider').owlCarousel({
        loop: true,
        margin: 15,
        nav: false,
        dots: false,
        autoplay: true,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 3
            },
            1000: {
                items: 6
            }
        }
    });



    // listing-slider
    $('.listing-slider').owlCarousel({
        loop: true,
        margin: 0,
        nav: true,
        dots: false,
        navText: [
            "<i class='far fa-long-arrow-left'></i>",
            "<i class='far fa-long-arrow-right'></i>"
        ],
        autoplay: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });



    // testimonial-slider
    $('.testimonial-slider').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        dots: true,
        autoplay: false,
        navText: [
            "<i class='far fa-angle-left'></i>",
            "<i class='far fa-angle-right'></i>"
        ],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 1
            },
            1200: {
                items: 2
            }
        }
    });


    // destination-slider
    $('.destination-slider').owlCarousel({
        loop: true,
        margin: 25,
        nav: true,
        dots: true,
        navText: [
            "<i class='far fa-angle-left'></i>",
            "<i class='far fa-angle-right'></i>"
        ],
        autoplay: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            },
            1200: {
                items: 4
            },
        }
    });


    // hotel-slider
    $('.hotel-slider').owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        dots: true,
        center: true,
        navText: [
            "<i class='far fa-angle-left'></i>",
            "<i class='far fa-angle-right'></i>"
        ],
        autoplay: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            },
            1200: {
                items: 4
            }
        }
    });



    // banner-slider
    $('.banner-slider').owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        dots: true,
        navText: [
            "<i class='far fa-angle-left'></i>",
            "<i class='far fa-angle-right'></i>"
        ],
        autoplay: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 2
            },
            1200: {
                items: 3
            }
        }
    });



    // car-slider
    $('.car-slider').owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        dots: true,
        center: true,
        navText: [
            "<i class='far fa-angle-left'></i>",
            "<i class='far fa-angle-right'></i>"
        ],
        autoplay: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            },
            1200: {
                items: 4
            }
        }
    });



    // preloader
    $(window).on('load', function () {
        $(".preloader").fadeOut("slow");
    });


    // fun fact counter
    $('.counter').countTo();
    $('.counter-box').appear(function () {
        $('.counter').countTo();
    }, {
        accY: -100
    });



    // magnific popup init
    $(".popup-gallery").magnificPopup({
        delegate: '.popup-img',
        type: 'image',
        gallery: {
            enabled: true
        },
    });

    $(".popup-youtube, .popup-vimeo, .popup-gmaps").magnificPopup({
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });



    // scroll to top
    $(window).scroll(function () {

        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            $("#scroll-top").fadeIn('slow');
        } else {
            $("#scroll-top").fadeOut('slow');
        }
    });

    $("#scroll-top").click(function () {
        $("html, body").animate({ scrollTop: 0 }, 1500);
        return false;
    });


    function updateStickyHeader() {
        var scrolled = $(window).scrollTop() > 50;
        $('body').toggleClass('is-scrolled', scrolled);
        $('.navbar').toggleClass('fixed-top', scrolled);
        $('.header').toggleClass('header-scrolled', scrolled);
    }

    $(window).on('scroll', updateStickyHeader);
    updateStickyHeader();


    // countdown
    if ($('#countdown').length) {
        $('#countdown').countdown('2035/01/30', function (event) {
            $(this).html(event.strftime('' + '<div class="row">' + '<div class="col countdown-single">' + '<h2 class="mb-0">%-D</h2>' + '<h5 class="mb-0">Day%!d</h5>' + '</div>' + '<div class="col countdown-single">' + '<h2 class="mb-0">%H</h2>' + '<h5 class="mb-0">Hours</h5>' + '</div>' + '<div class="col countdown-single">' + '<h2 class="mb-0">%M</h2>' + '<h5 class="mb-0">Minutes</h5>' + '</div>' + '<div class="col countdown-single">' + '<h2 class="mb-0">%S</h2>' + '<h5 class="mb-0">Seconds</h5>' + '</div>' + '</div>'));
        });
    }


    // project filter
    $(window).on('load', function () {
        if ($(".filter-box").children().length > 0) {
            $(".filter-box").isotope({
                itemSelector: '.filter-item',
                masonry: {
                    columnWidth: 1
                },
            });

            $('.filter-btns').on('click', 'li', function () {
                var filterValue = $(this).attr('data-filter');
                $(".filter-box").isotope({ filter: filterValue });
            });

            $(".filter-btns li").each(function () {
                $(this).on("click", function () {
                    $(this).siblings("li.active").removeClass("active");
                    $(this).addClass("active");
                });
            });
        }
    });


    // copywrite date
    let date = new Date().getFullYear();
    $("#date").html(date);


    // nice select
    $(document).ready(function () {
        $('.select').niceSelect();
    });


    // advanced search
    $('.advanced-search').click(function () {
        $('.advanced-search-menu').toggle();
    });


    // price slider
    $(function () {
        $(".price-range").not("#price-range1").slider({
            step: 500,
            range: true,
            min: 0,
            max: 10000,
            values: [1500, 5000],
            slide: function (event, ui) { $(".priceRange").val("$" + ui.values[0].toLocaleString() + " - $" + ui.values[1].toLocaleString()); }
        });
        $(".priceRange").val("$" + $(".price-range").slider("values", 0).toLocaleString() + " - $" + $(".price-range").slider("values", 1).toLocaleString());
    });


    // profile image upload
    $(".profile-img-btn").click(function () {
        $(this).closest(".user-profile-img").find(".profile-img-file").trigger("click");
    });

    $(".profile-img-file").on("change", function () {
        if (!this.files.length) {
            return;
        }

        const $form = $(this).closest(".profile-avatar-form");
        const $preview = $form.find(".profile-avatar-preview");

        if ($preview.length) {
            $preview.attr("src", URL.createObjectURL(this.files[0]));
        }

        $form.trigger("submit");
    });


    // listing images upload
    $(".listing-img-upload").click(function () {
        $(".listing-img-file").click();
    });


    // message bottom scroll
    if ($('.message-content-info').length) {
        $(function () {
            var chatbox = $('.message-content-info');
            var chatheight = chatbox[0].scrollHeight;
            chatbox.scrollTop(chatheight);
        });
    }


    function formatDisplayDate(date) {
        var day = String(date.getDate()).padStart(2, '0');
        var month = String(date.getMonth() + 1).padStart(2, '0');
        var year = date.getFullYear();

        return day + '/' + month + '/' + year;
    }

    // search date picker
    if ($('.date-picker').length) {
        $(".date-picker").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
        });
    }


    // find-car time picker 
    if ($('.time-picker').length) {
        $(function () {
            $(".time-picker").timepicker();
        });
    }


    // flight type search form
    function flightSearchScope($el) {
        var $scope = $el.closest('.flight-search');

        if (!$scope.length) {
            $scope = $el.closest('.search-form');
        }

        return $scope;
    }

    function toggleFlightSectionInputs($section, enabled) {
        $section.find(':input').each(function () {
            $(this).prop('disabled', !enabled);
        });
    }

    // Hidden multi-city rows duplicate field names and overwrite filled values on submit.
    function syncFlightFormInputs($scope) {
        var ft = ($scope.find('.flight-type input[name="flight-type"]:checked').val() || '').toString();
        var isMultiCity = ft === 'multi-city';
        var $mainLeg = $scope.find('.flight-search-item').not('.flight-multicity-item').not('.another-item').first();
        var $multiLegs = $scope.find('.flight-multicity-item, .another-item');

        toggleFlightSectionInputs($mainLeg, !isMultiCity);

        $multiLegs.each(function () {
            var $leg = $(this);
            toggleFlightSectionInputs($leg, isMultiCity && $leg.is(':visible'));
        });
    }

    function applyFlightTripType($radio) {
        var $scope = flightSearchScope($radio);
        var ft = $radio.val();

        if (ft === 'round-way' || ft === 'round_trip') {
            $scope.find('.search-form-return').show();
            $scope.find('.have-to-clone').hide();
            $scope.find('.another-item').remove();
            $scope.find('.search-form-return .return-date').each(function () {
                var $returnDate = $(this);
                if (($returnDate.val() || '').trim() !== '') {
                    return;
                }

                var journeyVal = ($returnDate.closest('.search-form-date').find('.journey-date').val() || '').trim();
                var returnDate = new Date();

                if (journeyVal) {
                    var parts = journeyVal.split('/');
                    if (parts.length === 3) {
                        returnDate = new Date(parseInt(parts[2], 10), parseInt(parts[1], 10) - 1, parseInt(parts[0], 10));
                    }
                } else {
                    returnDate.setDate(returnDate.getDate() + 2);
                }

                returnDate.setDate(returnDate.getDate() + 7);
                $returnDate.val(formatDisplayDate(returnDate));
                $returnDate.closest('.search-form-date').find('.return-day-name').html(returnDate.toLocaleString('en-GB', { weekday: 'long' }));
            });
        } else if (ft === 'multi-city') {
            $scope.find('.search-form-return').hide();
            $scope.find('.have-to-clone').show();
            $scope.find('.another-item').remove();
        } else {
            $scope.find('.search-form-return').hide();
            $scope.find('.have-to-clone').hide();
            $scope.find('.another-item').remove();
        }

        syncFlightFormInputs($scope);
    }

    $('.flight-type input[name="flight-type"]').on('change', function () {
        applyFlightTripType($(this));
    });

    $('.flight-type input[name="flight-type"]:checked').each(function () {
        applyFlightTripType($(this));
    });

    $(document).on('submit', '.flight-search form', function () {
        var $scope = flightSearchScope($(this));
        syncFlightFormInputs($scope);

        var ft = ($scope.find('.flight-type input[name="flight-type"]:checked').val() || '').toString();
        var isMultiCity = ft === 'multi-city';

        $scope.find('.flight-search-item').each(function () {
            var $leg = $(this);
            var isMainLeg = !$leg.is('.flight-multicity-item') && !$leg.is('.another-item');
            var isActiveLeg = isMultiCity
                ? ($leg.is('.flight-multicity-item') || $leg.is('.another-item')) && $leg.is(':visible')
                : isMainLeg;

            if (!isActiveLeg) {
                $leg.find(':input[name]').removeAttr('name');
            }
        });
    });


    // search selected date
    const today = new Date();
    var journeyDate = new Date(),
        returnDate = new Date();

    journeyDate.setDate(today.getDate() + 1);
    returnDate.setDate(today.getDate() + 2);

    $(".journey-date").each(function () {
        var $input = $(this);

        if (!(($input.val() || '').trim())) {
            $input.val(formatDisplayDate(journeyDate));
        }

        var journeyVal = ($input.val() || '').trim();
        if (journeyVal) {
            $input.closest('.search-form-date').find('.journey-day-name').html(
                formatDayName(journeyVal) || journeyDate.toLocaleString('en-GB', { weekday: 'long' })
            );
        }
    });

    $(".return-date").each(function () {
        var $input = $(this);

        if (!(($input.val() || '').trim())) {
            $input.val(formatDisplayDate(returnDate));
        }

        var returnVal = ($input.val() || '').trim();
        if (returnVal) {
            $input.closest('.search-form-date').find('.return-day-name').html(
                formatDayName(returnVal) || returnDate.toLocaleString('en-GB', { weekday: 'long' })
            );
        }
    });

    function formatDayName(dateValue) {
        var parts = (dateValue || '').split('/');

        if (parts.length !== 3) {
            return '';
        }

        var parsed = new Date(parseInt(parts[2], 10), parseInt(parts[1], 10) - 1, parseInt(parts[0], 10));

        if (isNaN(parsed.getTime())) {
            return '';
        }

        return parsed.toLocaleString('en-GB', { weekday: 'long' });
    }

    $(".journey-date").change(function () {
        var ojd = $(this).closest(".search-form-date").find(".journey-date").val();
        const journeyDayName = new Date(ojd).toLocaleString('en-us', { weekday: 'long' });
        $(this).closest(".search-form-date").find(".journey-day-name").html(journeyDayName);
    });

    $(".return-date").change(function () {
        var rd = $(this).closest(".search-form-date").find(".return-date").val();
        const returnDayName = new Date(rd).toLocaleString('en-us', { weekday: 'long' });
        $(this).closest(".search-form-date").find(".return-day-name").html(returnDayName);
    });

    // passenger box dropdown
    $(".passenger-box .dropdown-menu").click(function (e) {
        e.stopPropagation();
    });

    $(".passenger-class-info input[type='radio']").change(function (e) {
        var pcn = $(e.target).closest(".form-check").find("input[type='radio']:checked").val();
        $(e.target).closest(".passenger-box").find(".passenger-class-name").html(pcn);
    });

    $(".plus-btn").on("click", function (e) {
        var box = $(e.target).closest(".passenger-box");
        var qty = $(e.target).closest(".passenger-qty").children(".qty-amount");
        var current = parseInt(qty.val(), 10) || 0;

        if (box.hasClass("insurance-traveler-picker")) {
            var max = parseInt(qty.data("max"), 10) || 20;
            if (current >= max) {
                $(e.target).attr("disabled", "disabled");
                return;
            }
            qty.val(current + 1);
            box.find(".minus-btn").removeAttr("disabled");
            if (current + 1 >= max) {
                $(e.target).attr("disabled", "disabled");
            }
            totalPessenger(e);
            return;
        }

        qty.get(0).value++;
        var c = $(e.target).closest(".passenger-qty").children(".minus-btn");
        current >= 0 && c.removeAttr("disabled");
        totalPessenger(e);
        totalRoom(e);
    }),
        $(".minus-btn").on("click", function (e) {
            var box = $(e.target).closest(".passenger-box");
            var qty = $(e.target).closest(".passenger-qty").children(".qty-amount");
            var current = parseInt(qty.val(), 10) || 0;

            if (box.hasClass("insurance-traveler-picker")) {
                var min = parseInt(qty.data("min"), 10) || 1;
                if (current <= min) {
                    $(e.target).attr("disabled", "disabled");
                    return;
                }
                qty.val(current - 1);
                box.find(".plus-btn").removeAttr("disabled");
                if (current - 1 <= min) {
                    $(e.target).attr("disabled", "disabled");
                }
                totalPessenger(e);
                return;
            }

            if (current <= 1) {
                $(e.target).attr("disabled", "disabled");
            } else {
                qty.get(0).value--;
                totalPessenger(e);
                totalRoom(e);
            }
        })

    function holidayTravelerSummary(box) {
        var pa = parseInt(box.find(".passenger-adult").val(), 10) || 0;
        var pc = parseInt(box.find(".passenger-children").val(), 10) || 0;
        var pi = parseInt(box.find(".passenger-infant").val(), 10) || 0;
        var parts = [];

        if (pa > 0) {
            parts.push(pa + (pa === 1 ? " Adult" : " Adults"));
        }
        if (pc > 0) {
            parts.push(pc + (pc === 1 ? " Child" : " Children"));
        }
        if (pi > 0) {
            parts.push(pi + (pi === 1 ? " Infant" : " Infants"));
        }

        box.find(".holiday-passenger-summary").html(parts.length ? parts.join(", ") : "2 Adults");
    }

    function totalPessenger(e) {
        var box = $(e.target).closest(".passenger-box");

        if (box.hasClass("holiday-traveler-picker")) {
            holidayTravelerSummary(box);
            return;
        }

        if (box.hasClass("insurance-traveler-picker")) {
            var count = parseInt(box.find(".insurance-traveler-count").val(), 10) || 1;
            var label = count === 1 ? "Traveler" : "Travelers";
            box.find(".insurance-traveler-total").html(count);
            box.find(".passenger-total").html(
                '<span class="insurance-traveler-total">' + count + "</span> " + label
            );
            return;
        }

        var pa = parseInt(box.find(".passenger-adult").val(), 10) || 0;
        var pc = parseInt(box.find(".passenger-children").val(), 10) || 0;
        var pi = parseInt(box.find(".passenger-infant").val(), 10) || 0;
        var tp = pa + pc + pi;
        box.find(".passenger-total-amount").html(tp);
    }

    function totalRoom(e) {
        var box = $(e.target).closest(".passenger-box");

        if (box.hasClass("insurance-traveler-picker") || box.hasClass("holiday-traveler-picker")) {
            return;
        }

        var tr = parseInt(box.find(".passenger-room").val(), 10) || 0;
        box.find(".passenger-total-room").html(tr);
    }

    // search multicity form
    $(".multicity-btn").click(function () {

        if (document.querySelectorAll('.flight-search-item').length === 5) {
            return alert("Maximum Flight Limit Reached!!");
        }

        $('.flight-multicity-item .date-picker').datepicker('destroy');

        var cloneMulticity = $(".have-to-clone").clone();
        cloneMulticity.removeClass("have-to-clone");
        cloneMulticity.addClass("another-item");

        cloneMulticity.find(".multicity-btn").addClass("multicity-item-remove").html(`<div>
        <i class="fal fa-circle-xmark"></i> Remove Flight</div>`);
        $(".flight-search-content").append(cloneMulticity);

        syncFlightFormInputs(flightSearchScope($(this)));

        var i = 0;
        $('.flight-multicity-item .date-picker').each(function () {
            $(this).attr("id", 'date' + i).datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
            });
            i++;
        });

    });

    $(document).on('click', '.multicity-item-remove', function () {
        $(this).parent().closest('.flight-multicity-item').remove();
    });

    $(document).on('change', '.flight-multicity-item .date-picker', function (e) {
        var jd = $(e.target).val();
        const journeyDayName = new Date(jd).toLocaleString('en-us', { weekday: 'long' });
        $(e.target).closest(".flight-multicity-item").find('.journey-day-name').html(journeyDayName);
    });


    // swap search form value
    $(document).on('click', '.flight-search-item .search-form-swap', function (e) {
        var swapFrom = $(e.target).closest(".flight-search-item").find('.swap-from').val();
        var swapTo = $(e.target).closest(".flight-search-item").find('.swap-to').val();
        $(e.target).closest(".flight-search-item").find('.swap-from').val(swapTo);
        $(e.target).closest(".flight-search-item").find('.swap-to').val(swapFrom);
    });


})(jQuery);










