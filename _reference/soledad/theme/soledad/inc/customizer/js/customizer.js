jQuery(document).ready(function ($) {
    "use strict";
    $('.customize-control-dropdown-select2').each(function () {
        $('.customize-control-select2').select2({
            allowClear: true
        });
    });

    $(".customize-control-select2").on("change", function () {
        var select2Val = $(this).val();
        $(this).parent().find('.customize-control-dropdown-select2').val(select2Val).trigger('change');
    });

    $(".pill_checkbox_control .sortable").sortable({
        placeholder: "pill-ui-state-highlight",
        update: function (event, ui) {
            PenciGetAllPillCheckboxes($(this).parent());
        }
    });

    $('.pill_checkbox_control .sortable-pill-checkbox').on('change', function () {
        PenciGetAllPillCheckboxes($(this).parent().parent().parent());
    });

    function PenciGetAllPillCheckboxes($element) {
        var inputValues = $element.find('.sortable-pill-checkbox').map(function () {
            if ($(this).is(':checked')) {
                return $(this).val();
            }
        }).toArray();
        $element.find('.customize-control-sortable-pill-checkbox').val(inputValues).trigger('change');
    }

    $('.customize-control-tinymce-editor').each(function () {
        // Get the toolbar strings that were passed from the PHP Class
        var tinyMCEToolbar1String = _wpCustomizeSettings.controls[$(this).attr('id')].skyrockettinymcetoolbar1;
        var tinyMCEToolbar2String = _wpCustomizeSettings.controls[$(this).attr('id')].skyrockettinymcetoolbar2;
        var tinyMCEMediaButtons = _wpCustomizeSettings.controls[$(this).attr('id')].skyrocketmediabuttons;

        wp.editor.initialize($(this).attr('id'), {
            tinymce: {
                wpautop: true,
                toolbar1: tinyMCEToolbar1String,
                toolbar2: tinyMCEToolbar2String
            },
            quicktags: true,
            mediaButtons: tinyMCEMediaButtons
        });
    });

    $(document).on('keyup', '.box-model-field', function () {
        var $parent = $(this).parents('.box-model-wrapper'),
            $save_field = $parent.find('.box-model-saved'),
            $input_fields = $parent.find('.box-model-field'),
            saved_string = '';

        $input_fields.each(function () {
            var $field = $(this);
            var field_value = $.isNumeric($field.val()) ? parseInt($field.val(), 10) : '-';
            if ($.isNumeric(field_value) || '-' === field_value) {
                saved_string += field_value + ', ';
            }
        });

        $save_field.val(saved_string.replace(/,\s*$/, "")).trigger('change');
    });

    $('[data-type="penci_speed_delete_cache"]').on('click', function (event) {
        var $this = $(this),
            $nonce = $this.data('nonce'),
            $ajaxurl = $this.data('ajaxurl'),
            $parent = $this.closest('.customize-control');
        event.preventDefault();
        $(this).addClass('loading');
        $.ajax({
            type: "post",
            dataType: "json",
            url: $ajaxurl,
            data: {
                action: "penci_speed_delete_cache",
                _nonce: $nonce,
            },
            success: function () {
                $this.removeClass('loading').addClass('success');
                $parent.find('.description span.count').html(0);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('The following error occured: ' + textStatus, errorThrown);
            }
        });
    });


    jQuery(document).ready(function ($) {
        function addHelpIcons() {
            $(".customize-control").each(function () {
                var control = $(this);
                var title = control.find(".customize-control-title");
                var description = control.find(".customize-control-description");
    
                // Ensure the icon is only added once
                if (title.length && description.length && !title.find(".help-icon").length) {
                    var helpIcon = $("<span class='help-icon'>?</span>");
                    title.append(helpIcon);
    
                    // Hide description initially
                    description.hide().removeClass('active');
    
                    // Toggle description on click
                    helpIcon.on("click", function (e) {
                        e.stopPropagation(); // Prevent click from affecting other elements
                        $(".customize-control-description").not(description).slideUp(200).removeClass('active'); // Close other descriptions
                        description.slideToggle(200).toggleClass('active');
                    });

                    tippy(helpIcon[0], {
                        content: description.text(),
                        placement: 'top',
                        animation: 'fade',
                        interactive: true,
                        maxWidth: 220,
                    });
                }
            });
        }
    
        // Observe changes in the Customizer panel for dynamically added elements
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.addedNodes.length) {
                    addHelpIcons();
                }
            });
        });
    
        // Target the main customizer container for observation
        var customizerContainer = document.getElementById("customize-controls");
        if (customizerContainer) {
            observer.observe(customizerContainer, {
                childList: true,
                subtree: true,
            });
        }
    
        // Run initially in case elements are already present
        addHelpIcons();
    });

});
