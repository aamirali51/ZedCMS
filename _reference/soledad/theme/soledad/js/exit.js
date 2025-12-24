(function ($) {
    "use strict";

    // Exit Popup Functionality
    function exitPopup() {
        // Check if age verification is required and not confirmed
        if (sessionStorage.getItem("penci_epopup_show") === "shown") {
            return;
        }

        // Show the popup
        function showPopup() {
            $.magnificPopup.open({
                items: {
                    src: ".penci-epopup-content",
                },
                type: "inline",
                removalDelay: 500, // Delay removal to allow out-animation
                tClose: false,
                tLoading: false,
                callbacks: {
                    beforeOpen: function () {
                        this.st.mainClass = "mfp-ani-wrap penci-promo-popup-wrapper";
                    },
                    close: function () {
                        sessionStorage.setItem("penci_epopup_show", "shown");
                    },
                },
            });
        }

        // Set a flag to trigger only once
        let exitIntentShown = false;

        // Detect mouseleave at the top of the page
        document.addEventListener("mouseout", function (e) {
            if (e.clientY < 50 && !exitIntentShown) {
                showPopup();
                exitIntentShown = true;
            }
        });
    }

    // Initialize on document ready
    $(document).ready(exitPopup);
})(jQuery);