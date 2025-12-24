(function ($) {
    "use strict";

    // Handle feedback click
    $(".penci-article-feedback span").on("click", function () {
        const $this = $(this);
        const value = parseInt($this.attr("data-value"), 10);
        const postID = $(".penci-article-feedback").attr("data-post-id");
        const thankYouMessage = $(".penci-article-feedback").attr("data-thank-text");
        const feedbackContainer = $this.closest(".penci-article-feedback");

        // Prevent sending AJAX if feedback already submitted
        if (getCookie(`penci_afb_${postID}`) || feedbackContainer.hasClass("penci-afb-disabled")) {
            return false;
        }

        // Send AJAX request
        $.ajax({
            url: `/wp-json/article_feedback/v1/feedback/${postID}`,
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                id: postID,
                value: value,
            }),
            success: function ( response ) {
                setCookie(`penci_afb_${postID}`, "1");
                $('.penci-afb-title').html(thankYouMessage);
                feedbackContainer.find('.penci-afb-count-yes').html(response.yes);
                feedbackContainer.find('.penci-afb-count-no').html(response.no);
            },
            error: function (xhr) {
                console.error("Feedback submission failed:", xhr.responseText);
            },
        });

        // Disable feedback and show thank-you message
        setTimeout(() => {
            feedbackContainer.addClass("penci-afb-disabled");
        }, 20);
    });

    // Set a cookie
    function setCookie(name, value) {
        const date = new Date();
        date.setTime(date.getTime() + 365 * 24 * 60 * 60 * 1000); // 1 year
        document.cookie = `${name}=${value || ""}; expires=${date.toUTCString()}; path=/`;
    }

    // Get a cookie
    function getCookie(name) {
        const nameEQ = `${name}=`;
        const cookies = document.cookie.split(";");
        for (let cookie of cookies) {
            cookie = cookie.trim();
            if (cookie.indexOf(nameEQ) === 0) {
                return cookie.substring(nameEQ.length);
            }
        }
        return null;
    }
})(jQuery);