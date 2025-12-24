(function ($) {
	"use strict";
	var ELPENCI_PL = ELPENCI_PL || {};

	ELPENCI_PL.abc_heading = function () {
		$(document).on(
			"click",
			".penci_az_taxonomy_listing_head a",
			function (event) {
				event.preventDefault();

				var target = $($(this).attr("href")); // Convert href into a jQuery object
				var newUrl = $(this).attr("href"); // Get the href for URL update

				if (target.length) {
					var offset = $(".penci-header-wrap").outerHeight() + 20;
					$("html, body").animate(
						{
							scrollTop: target.offset().top - offset,
						},
						500
					);

					history.pushState(null, null, newUrl);
				}
			}
		);

		var hash = window.location.hash;
		if (hash && hash.startsWith("#penci_az")) {
			var target = $(hash); // Convert hash to jQuery object

			if (target.length) {
				var offset = $(".penci-header-wrap").outerHeight() + 20;
				$("html, body").animate(
					{
						scrollTop: target.offset().top - offset,
					},
					500
				);
			}
		}
	};

	// Add space for Elementor Menu Anchor link
	$(window).on("elementor/frontend/init", function () {
		if (window.elementorFrontend) {
			elementorFrontend.hooks.addAction(
				"frontend/element_ready/penci-az-taxonomy-listing.default",
				function ($scope) {
					ELPENCI_PL.abc_heading();
				}
			);
		}
	});
})(jQuery);
