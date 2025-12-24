(function ($) {
	"use strict";
	var ELPENCI_CR = ELPENCI_CR || {};

	ELPENCI_CR.PenciCustomCursor = function () {
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
			return;
		}
		if ($("body").hasClass("penci-cc-cursor")) {
			const cursorInnerEl = document.querySelector(
				".penci-ccircle-cinner"
			);
			const cursorOuterEl = document.querySelector(
				".penci-ccircle-couter"
			);

			if (!cursorInnerEl || !cursorOuterEl) return;

			let lastY,
				lastX = 0;
			let magneticFlag = false;

			// move
			window.onmousemove = function (event) {
				if (!magneticFlag) {
					cursorOuterEl.style.transform =
						"translate(" +
						event.clientX +
						"px, " +
						event.clientY +
						"px" +
						")";
				}
				cursorInnerEl.style.transform =
					"translate(" +
					event.clientX +
					"px, " +
					event.clientY +
					"px" +
					")";
				lastY = event.clientY;
				lastX = event.clientX;

				//iframe fix
				if ($(event.target).is("iframe")) {
					cursorOuterEl.style.visibility = "hidden";
					cursorInnerEl.style.visibility = "hidden";
				} else {
					cursorOuterEl.style.visibility = "visible";
					cursorInnerEl.style.visibility = "visible";
				}
			};

			// links hover
			$("body").on("mouseenter", "a, .cursor-as-pointer", function () {
				cursorInnerEl.classList.add("penci-clinkh");
				cursorOuterEl.classList.add("penci-clinkh");
			});
			$("body").on("mouseleave", "a, .cursor-as-pointer", function () {
				if (
					$(this).is("a") &&
					$(this).closest(".cursor-as-pointer").length
				) {
					return;
				}
				cursorInnerEl.classList.remove("penci-clinkh");
				cursorOuterEl.classList.remove("penci-clinkh");
			});

			// additional hover cursor class
			$("body").on("mouseenter", "[data-cursor-class]", function () {
				const cursorClass = $(this).attr("data-cursor-class");

				if (cursorClass.indexOf("dark-color") != -1) {
					cursorInnerEl.classList.add("dark-color");
					cursorOuterEl.classList.add("dark-color");
				}

				if (cursorClass.indexOf("cursor-link") != -1) {
					cursorInnerEl.classList.add("cursor-link");
					cursorOuterEl.classList.add("cursor-link");
				}
			});
			$("body").on("mouseleave", "[data-cursor-class]", function () {
				const cursorClass = $(this).attr("data-cursor-class");
				if (cursorClass.indexOf("dark-color") != -1) {
					cursorInnerEl.classList.remove("dark-color");
					cursorOuterEl.classList.remove("dark-color");
				}

				if (cursorClass.indexOf("cursor-link") != -1) {
					cursorInnerEl.classList.remove("cursor-link");
					cursorOuterEl.classList.remove("cursor-link");
				}
			});

			// magnet elements
			$("body").on(
				"mouseenter",
				".cursor-magnet, .icon-button",
				function () {
					const $elem = $(this);
					const scrollTop =
						window.pageYOffset ||
						document.documentElement.scrollTop;
					cursorOuterEl.style.transition = "all .2s ease-out";
					cursorOuterEl.style.transform =
						"translate(" +
						$elem.offset().left +
						"px, " +
						($elem.offset().top - scrollTop) +
						"px" +
						")";
					cursorOuterEl.style.width = $elem.width() + "px";
					cursorOuterEl.style.height = $elem.height() + "px";
					cursorOuterEl.style.marginLeft = 0;
					cursorOuterEl.style.marginTop = 0;
					magneticFlag = true;
				}
			);

			$("body").on(
				"mouseleave",
				".cursor-magnet, .icon-button",
				RemoveMagneticFromCursor
			);

			function RemoveMagneticFromCursor() {
				cursorOuterEl.style.transition = null;
				cursorOuterEl.style.width = null;
				cursorOuterEl.style.height = null;
				cursorOuterEl.style.marginLeft = null;
				cursorOuterEl.style.marginTop = null;
				magneticFlag = false;
			}

			// Custom leave trigger
			$("body").on("penci_mouseleave", function () {
				RemoveMagneticFromCursor();
				cursorOuterEl.style.transform = cursorInnerEl.style.transform;
				cursorInnerEl.classList.remove("penci-clinkh");
				cursorOuterEl.classList.remove("penci-clinkh");
			});

			$("body").on("mouseenter", "iframe", function () {
				cursorOuterEl.style.visibility = "hidden";
				cursorInnerEl.style.visibility = "hidden";
			});

			cursorInnerEl.style.visibility = "visible";
			cursorOuterEl.style.visibility = "visible";
		}
	};

	$(document).ready(function () {
		ELPENCI_CR.PenciCustomCursor();
	});

})(jQuery);