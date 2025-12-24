  (function ($, elementor) {
	"use strict";

	var SearchButton = function ($scope, $) {
		var $section = $scope;

		//sticky fixes for inner section.
		$section.find('a.search-click').on('click', function (e) {
            var $this = $(this),
              $body = $('body'),
              $container = $this.closest('.e-parent')
        
            $('body').find('.search-input').removeClass('active')
            $container.find('.search-input').toggleClass('active')
        
            if ($body.find('.header-search-style-overlay').length ||
              $body.find('.header-search-style-showup').length) {
              $container.find('.show-search').toggleClass('active')
            } else {
              $this.next().fadeToggle()
            }
        
            var opentimeout = setTimeout(function () {
              var element = document.querySelector('.search-input.active')
              if (element !== null) {
                element.focus({
                  preventScroll: true,
                })
              }
            }, 200, function () {
              clearTimeout(opentimeout)
            })
        
            $body.addClass('search-open')
            e.preventDefault()
            e.stopPropagation()
            return false
        })
	};

	jQuery(window).on("elementor/frontend/init", function () {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/penci-header-search.default",
			SearchButton
		);
	});
})(jQuery, window.elementorFrontend);