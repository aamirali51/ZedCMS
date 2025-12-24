jQuery(document).ready(function ($) {

    $(document).on('mouseenter', '.penci-block-mega', function () {
        var t = $(this),
            left = 0,
            right = 0,
            wappercontent = t.find('.penci-dropdown-menu'),
            itemoffset = t.offset(),
            blockcontainer = t.find('.penci-mega-content-container'),
            blockw = wappercontent.outerWidth(),
            vw = $(window).width(),
            cw = t.closest('.container').outerWidth(),
            rightoff = vw - itemoffset.left - (vw - cw) / 2,
            pl = parseFloat( t.closest('.penci_container').css('padding-left') ),
            pr = parseFloat( t.closest('.penci_container').css('padding-right') ),
            ps = pl || pr ? (pl + pr)/2 : 0,
            blockid = blockcontainer.data('blockid');


        t.css({'--pcctnspc' : ps + 'px'})

        if (t.hasClass('penci-megapos-center') && itemoffset.left > blockw / 2 && vw - itemoffset.left - t.outerWidth() > blockw / 2) {
            left = (blockw / 2 - t.outerWidth() + ps / 2) * -1;
            right = (blockw / 2 - t.outerWidth() / 2) * -1;
        } else if (rightoff < blockw) {
            left = (blockw - rightoff - ps) * -1;
            right = (vw - itemoffset.left - t.outerWidth() - (vw - cw)  / 2) * -1 - ps;
        }

        if ($('body').hasClass('rtl')) {
            wappercontent.css({'right': right + 'px'});
        } else {
            wappercontent.css({'left': left + 'px'});
        }

        wappercontent.css({'display': 'block'});

        if (sessionStorage.getItem("penci_megamenu_"+blockid) && blockcontainer.is(':empty') && t.hasClass('ajax-mega-menu') ) {
            // Restore the contents of the text field
            blockcontainer.html(sessionStorage.getItem("penci_megamenu_"+blockid));
            $(document).trigger('penci-mega-complete');
        } else if (blockcontainer.is(':empty') && t.hasClass('ajax-mega-menu')) {
            $.ajax({
                url: penci_megamenu_var.url,
                data: {
                    'action': 'penci_get_ajax_menu_mega_content',
                    'ids': blockid,
                    'nonce': penci_megamenu_var.nonce
                },
                dataType: 'json',
                method: 'POST',
                beforeSend: function (response) {
                    wappercontent.addClass('loading');
                },
                success: function (response) {
                    if (response.data.status === 'success') {
                        var dataInsert = response.data.data;
                        sessionStorage.setItem("penci_megamenu_"+blockid, dataInsert);
                        blockcontainer.html(dataInsert);
                        $(document).trigger('penci-mega-loaded');
                        wappercontent.removeClass('loading');
                    } else {
                        console.log('loading html dropdowns returns wrong data - ', response.data.message);
                    }
                },
                complete: function () {
                    $(document).trigger('penci-mega-complete');
                },
                error: function () {
                    console.log('loading html dropdowns ajax error');
                }
            });
        }
    });
});
