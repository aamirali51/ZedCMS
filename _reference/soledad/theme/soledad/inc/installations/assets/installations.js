(function ($) {
	"use strict";

	var addNotice = function (type, message) {
		$("body").append(
			'<div class="penci-notice penci-notice-' +
				type +
				'">' +
				message +
				"</div>"
		);
		setTimeout(() => {
			$(".penci-notice").fadeOut("slow", function () {
				$(this).remove();
			});
		}, 5000);
	};

	var hideNotice = function () {
		$(".penci-notice").remove();
	};

	function wizardInstallPlugins() {

		var checkPlugin = function ($link, callback) {
			setTimeout(function () {
				$.ajax({
					url: soledadInstallations.ajaxUrl,
					method: "POST",
					data: {
						action: "soledad_check_plugins",
						penci_plugin: $link.data("plugin"),
						security: soledadInstallations.check_plugins_nonce,
					},
					success: function (response) {
						if ("success" === response.status) {
							changeNextButtonStatus(
								response.data.required_plugins
							);
							changePageStatus(response.data.is_all_activated);
						} else {
							addNotice("warning", response.message);
							removeLinkClasses($link);
							hideNotice();
						}

						if (
							response?.data?.status === "deactivate" &&
							!$('.penci-install-plugins').hasClass('penci-install-all')
						) {
							reloadPage($link);
						}

						callback(response);
					},
				});
			}, 1000);
		};

		var activatePlugin = function ($link, callback) {
			$.ajax({
				url: penci_plugin_data[$link.data("plugin")][
					"activate_url"
				].replaceAll("&amp;", "&"),
				method: "GET",
				success: function () {
					checkPlugin($link, function (response) {
						if ("success" === response.status) {
							if ("activate" === response.data.status) {
								activatePlugin($link, callback);
							} else {
								removeLinkClasses($link);
								changeLinkAction(
									"activate",
									"deactivate",
									$link,
									response
								);
								changeLinkAction(
									"install",
									"deactivate",
									$link,
									response
								);
								changeLinkAction(
									"update",
									"deactivate",
									$link,
									response
								);
								callback();
							}
						}
					});
				},
			});
		};

		var deactivatePlugin = function ($link) {
			$.ajax({
				url: soledadInstallations.ajaxUrl,
				method: "POST",
				data: {
					action: "soledad_deactivate_plugin",
					penci_plugin: $link.data("plugin"),
					security: soledadInstallations.deactivate_plugin_nonce,
				},
				success: function (response) {
					if ("error" === response.status) {
						addNotice("warning", response.message);
						removeLinkClasses($link);
						hideNotice();
						return;
					}

					checkPlugin($link, function (response) {
						if ("success" === response.status) {
							if ("activate" === response.data.status) {
								removeLinkClasses($link);
								changeLinkAction(
									"deactivate",
									"activate",
									$link,
									response
								);
							} else {
								deactivatePlugin($link);
							}
						}
					});
				},
			});
		};

		function parsePlugins($link, callback) {
			$.ajax({
				url: $link.attr("href"),
				method: "POST",
				success: function () {
					setTimeout(function () {
						checkPlugin($link, function (response) {
							if ("success" === response.status) {
								if ("activate" === response.data.status) {
									activatePlugin($link, callback);
								} else {
									removeLinkClasses($link);
									changeLinkAction(
										"activate",
										"deactivate",
										$link,
										response
									);
									callback();
								}
							}
						});
					}, 1000);
				},
			});
		}

		function reloadPage($link) {
			if ($link.parents(".soledad-plugin-wrapper").length) {
				location.reload();
			}
		}

		function addLinkClasses($link) {
			$link
				.parents(".soledad-plugin-wrapper")
				.addClass("soledad-loading");
			$link
				.parents(".soledad-plugin-wrapper")
				.siblings()
				.addClass("penci-install-disabled");
			$(".soledad-wizard-footer").addClass("penci-install-disabled");

			$link.text(
				soledadInstallations[
					$link.data("action") + "_process_plugin_btn_text"
				]
			);
		}

		function removeLinkClasses($link) {
			$link
				.parents(".soledad-plugin-wrapper")
				.removeClass("soledad-loading");
			$link
				.parents(".soledad-plugin-wrapper")
				.siblings()
				.removeClass("penci-install-disabled");
			$(".soledad-wizard-footer").removeClass("penci-install-disabled");
		}

		function changeNextButtonStatus(status) {
			var $nextBtn = $(".soledad-next");
			if ("has_required" === status) {
				$nextBtn.addClass("penci-install-disabled");
			} else {
				$nextBtn.removeClass("penci-install-disabled");
			}
		}

		function changePageStatus(status) {
			var $page = $(".soledad-plugins");
			if ("yes" === status) {
				$page.addClass("penci-install-all-active");
			} else {
				$page.removeClass("penci-install-all-active");
			}
		}

		function changeLinkAction(actionBefore, actionAfter, $link, response) {
			if (response && response.data.version) {
				$link
					.parents(".soledad-plugin-wrapper")
					.find(".penci-install-plugin-version span")
					.text(response.data.version);
			}

			$link
				.removeClass("soledad-" + actionBefore)
				.addClass("soledad-" + actionAfter);
			$link.attr(
				"href",
				penci_plugin_data[$link.data("plugin")][
					actionAfter + "_url"
				].replaceAll("&amp;", "&")
			);
			$link.data("action", actionAfter);
			$link.text(soledadInstallations[actionAfter]);
		}

		$(document).on(
			"click",
			".soledad-ajax-plugin:not(.soledad-deactivate)",
			function (e) {
				e.preventDefault();

				var $link = $(this);
				addLinkClasses($link);
				parsePlugins($link, function () {});
			}
		);

		$(document).on(
			"click",
			".soledad-ajax-plugin.soledad-deactivate",
			function (e) {
				e.preventDefault();

				var $link = $(this);
				addLinkClasses($link);
				deactivatePlugin($link);
			}
		);

		$(document).on("click", ".soledad-wizard-all-plugins", function (e) {
			e.preventDefault();

			$('.penci-install-plugins').addClass('penci-install-all');

			var itemQueue = [];

			function activationAction() {
				if (itemQueue.length) {
					var $link = $(itemQueue.shift());

					if ($link.parents(".soledad-compatible-plugins").length) {
						return;
					}

					addLinkClasses($link);

					parsePlugins($link, function () {
						activationAction();
					});
				}
			}

			$(
				".soledad-plugin-wrapper .soledad-ajax-plugin:not(.soledad-deactivate)"
			).each(function () {
				itemQueue.push($(this));
			});

			activationAction();
		});
	}

	function wizardBuilderSelect() {
		$(".soledad-wizard-builder-select > div").on("click", function () {
			var $this = $(this);
			var builder = $(this).data("builder");

			$this.addClass("penci-install-active");
			$this.siblings().removeClass("penci-install-active");
			$(".soledad-btn.soledad-" + builder)
				.removeClass("penci-install-hidden")
				.addClass("penci-install-shown")
				.siblings(".soledad-next")
				.addClass("penci-install-hidden")
				.removeClass("penci-install-shown");
		});
	}

	function wizardInstallChildTheme() {
		$(".soledad-install-child-theme").on("click", function (e) {
			e.preventDefault();
			var $btn = $(this);

			$btn.addClass("soledad-loading");

			$.ajax({
				url: soledadInstallations.ajaxUrl,
				method: "POST",
				data: {
					action: "soledad_install_child_theme",
					security: soledadInstallations.install_child_theme_nonce,
				},
				dataType: "json",
				success: function (response) {
					$btn.removeClass("soledad-loading");

					if (response && "success" === response.status) {
						$(".soledad-wizard-child-theme").addClass(
							"penci-install-installed"
						);
						addNotice("success", "Successfully installed the child theme.");

						location.reload();
					} else if (
						response &&
						"dir_not_exists" === response.status
					) {
						addNotice(
							"error",
							"The directory can't be created on the server. Please, install the child theme manually or contact our support for help."
						);
					} else {
						addNotice(
							"error",
							"The child theme can't be installed. Skip this step and install the child theme manually via Appearance -> Themes."
						);
					}
				},
				error: function () {
					$btn.removeClass("soledad-loading");

					addNotice(
						"error",
						"The child theme can't be installed. Skip this step and install the child theme manually via Appearance -> Themes."
					);
				},
			});
		});
	}

	function wizardUninstallDemo() {
		$(".penci-wuninstall-demo").on("click", function (e) {
			e.preventDefault();

			var t = $(this),
				w = t.closest('.demo-selector');

			w.addClass("loading");

			var r = confirm("Are you sure?");
				if (r !== true) {
					return false;
				}

			$.get(
				soledadInstallations.ajaxUrl,
				{
					action: "penci_soledad_unintall_demo",
					type: "unintall_demo",
					_wpnonce: soledadInstallations.demononce,
				},
				function (response) {
					if (response.success) {
						addNotice("success", "Unintall Demo completed!");
						$(".soledad-wizard-content-inner").removeClass("loading");
						$(".demos-container").removeClass("has-imported");
						w.removeClass('demo-selector-installed loading');
					} else {
						addNotice("error", response.data);
					}
				}
			).fail(function () {
				addNotice("error", "Failed");
			});
		});
	}
	
	jQuery(document).ready(function () {
		wizardInstallPlugins();
		wizardBuilderSelect();
		wizardInstallChildTheme();
		wizardUninstallDemo();
	});
})(jQuery);