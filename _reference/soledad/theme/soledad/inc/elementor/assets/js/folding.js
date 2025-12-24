(function ($) {
    'use strict';

    $(window).on('elementor/frontend/init', function () {

        // Init when any widget renders
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', function ($scope) {
            try {
                initWidgetFoldingForElement($scope);
            } catch (e) {
                console.error(e);
            }

            // Grab the widget's editor model in preview
            var modelCid = $scope.data('model-cid') || $scope.attr('data-model-cid');
            if (!modelCid || !elementor.channels || !elementor.channels.editor) {
                return;
            }

            var model = elementor.channels.editor.request('editor:model', modelCid);
            if (!model) return;

            // Watch only for enable_folding control changes
            model.on('change:enable_folding', function () {
                console.log('enable_folding changed', $scope);
                try {
                    initWidgetFoldingForElement($scope);
                    console.log('enable_folding changed → re-init folding', $scope);
                } catch (e) {
                    console.error(e);
                }
            });
        });

    });
})(jQuery);



function initWidgetFoldingForElement($element) {
    
    if ( ! $element.hasClass( 'penci-widget-folded-yes' ) ) {
        return;
    }
    
    var $container = $element.find(".elementor-widget-container");
    var readMoreText = readLessText = ajax_var_more.more;
    
    // Remove existing button if any
    $element.find(".widget-fold-button-wrapper").remove();
    
    // Check if content height exceeds folded height
    var originalHeight = $container.outerHeight();
    var foldedHeight = $element.outerHeight();
    
    if ( foldedHeight >= originalHeight ) {
        return; // No need to fold
    }
    
    var buttonHtml = `<div class="widget-fold-button-wrapper">
                        <button class="widget-fold-button" data-read-more="${readMoreText}" data-read-less="${readLessText}">
                            ${readMoreText}
                        </button>
                      </div>`;
    
    $container.append(buttonHtml);
    
    // Button click handler
    $element.find(".widget-fold-button").on("click", function(e) {
        e.preventDefault();
        var $button = jQuery(this);
        var $widget = $button.closest(".elementor-element");
        
        if ($widget.hasClass("penci-widget-folded-yes")) {
            // Expand
            $widget.removeClass("penci-widget-folded-yes").addClass("widget-expanded");
            $button.text($button.data("read-less"));
        } else {
            // Fold
            $widget.removeClass("widget-expanded").addClass("penci-widget-folded-yes");
            $button.text($button.data("read-more"));
            
        }
    });
}