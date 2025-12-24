(function($) {
    $.fn.PenciStickyContainer = function(options) {
        // Default Options
        const settings = $.extend({
            offset: 0,              // Sticky offset
            disableOnMobile: false, // Disable sticky on mobile devices
            disableOnTablet: false, // Disable sticky on tablet devices
            mobileBreakpoint: 768,  // Mobile breakpoint width
            tabletBreakpoint: 1024, // Tablet breakpoint width
            stopAt: null            // Custom ID/Class to stop sticky
        }, options);

        return this.each(function() {
            const $element = $(this);
            const stickyClass = 'pencisctn-active';
            const stopClass = 'stop-sticky';
            const scrollUpClass = 'scroll-up';
            const scrollDownClass = 'scroll-down';

            const originalHeight = $element.outerHeight();
            const triggerScroll = originalHeight + settings.offset;
            let isSticky = false;
            let lastScrollTop = 0;

            /**
             * Check if sticky should be disabled based on screen size
             */
            function isDisabledOnDevice() {
                const windowWidth = $(window).width();
                if (settings.disableOnMobile && windowWidth <= settings.mobileBreakpoint) {
                    return true;
                }
                if (settings.disableOnTablet && windowWidth <= settings.tabletBreakpoint) {
                    return true;
                }
                return false;
            }

            /**
             * Check if sticky should stop based on a target element
             */
            function isAtStopElement() {
                if (!settings.stopAt || !$(settings.stopAt).length) return false;
                const stopPosition = $(settings.stopAt).offset().top;
                const scrollTop = $(window).scrollTop();
                const elementHeight = $element.outerHeight();

                return scrollTop + elementHeight >= stopPosition;
            }

            /**
             * Sticky behavior based on scroll
             */
            function handleScroll() {
                const scrollTop = $(window).scrollTop();

                // If disabled on this device, remove sticky and exit
                if (isDisabledOnDevice()) {
                    $element.removeClass(`${stickyClass} ${stopClass} ${scrollUpClass} ${scrollDownClass}`);
                    isSticky = false;
                    return;
                }

                // Add scroll direction classes
                if (scrollTop > lastScrollTop) {
                    $element.removeClass(scrollUpClass).addClass(scrollDownClass);
                } else {
                    $element.removeClass(scrollDownClass).addClass(scrollUpClass);
                }
                lastScrollTop = scrollTop;

                // Stop sticky when scrolling to the target element
                if (isAtStopElement()) {
                    $element.removeClass(stickyClass).addClass(stopClass);
                    isSticky = false;
                    return;
                }

                // Add or remove sticky class based on scroll position
                if (scrollTop > triggerScroll && !isSticky) {
                    $element.addClass(stickyClass).removeClass(stopClass);
                    isSticky = true;
                } else if (scrollTop <= triggerScroll && isSticky) {
                    $element.removeClass(`${stickyClass} ${stopClass}`);
                    isSticky = false;
                }
            }

            /**
             * Resize logic to recalculate sticky behavior
             */
            function handleResize() {
                $(window).on('resize', function() {
                    $element.removeClass(`${stickyClass} ${stopClass} ${scrollUpClass} ${scrollDownClass}`);
                    isSticky = false;
                    handleScroll();
                });
            }

            // Event bindings
            $(window).on('scroll', handleScroll);
            handleResize();
        });
    };
})(jQuery);

(function ($, elementor) {
	"use strict";

	var StickyContainer = function ($scope, $) {
		var $section = $scope,
			$stickyFound = $scope.hasClass("pencisctn-sticky");

		if ($stickyFound) {
			var offset = $section.attr("data-pencisctn-offset"),
				mobile = $section.attr("data-pencisctn-mobile"),
				tablet = $section.attr("data-pencisctn-tablet"),
				stop = $section.attr("data-pencisctn-stop");

			$section.PenciStickyContainer({
				offset: offset, // Offset before sticky applies
				disableOnMobile: mobile, // Disable sticky on mobile devices
				disableOnTablet: tablet, // Keep sticky enabled on tablets
				mobileBreakpoint: 768, // Mobile max width
				tabletBreakpoint: 1024, // Tablet max width
				stopAt: stop,
			});

			
		}

		
	};

	jQuery(window).on("elementor/frontend/init", function () {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/container",
			StickyContainer
		);
        elementorFrontend.hooks.addAction(
			"frontend/element_ready/section",
			StickyContainer
		);
	});
})(jQuery, window.elementorFrontend);