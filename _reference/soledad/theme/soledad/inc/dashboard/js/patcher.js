(function($) {
	'use strict';

	$(document).on('click', '.penci-patch-apply', function (e) {
		e.preventDefault();

		var $this = $(this);
		var patchesMap = $this.data('patches-map');
		var fileMap = [];

		for(var i = 0; i < patchesMap.length; i++) {
			fileMap[i] = 'soledad/' + patchesMap[i];
		}

		var confirmation = confirm( `${penci_patch_notice.single_patch_confirm} \r\r\n` + fileMap.join('\r\n') );

		if ( ! confirmation ) {
			return;
		}

		addLoading();
		cleanNotice();

		sendAjax($this.data('id'), function(response) {
            if ( 'undefined' !== typeof response.message ) {
                printNotice(response.status, response.message);
            }

            if ( 'undefined' !== typeof response.status && 'success' === response.status ) {
                $this.parents('.penci-patch-item').addClass('penci-applied');
                updatePatcherCounter();
            }

            removeLoading();
        });
	});

	$(document).on('click', '.penci-patch-apply-all', function (e) {
		e.preventDefault();

		var $applyAllBtn = $(this);
        var $patches     = $('.penci-patch-item:not(.penci-table-row-heading):not(.penci-applied)').get();

		cleanNotice();

		if ( 0 === $patches.length ) {
			printNotice('success', penci_patch_notice.all_patches_applied);
			return;
		}

		if ( ! confirm(penci_patch_notice.all_patches_confirm) ) {
			return;
		}

		$applyAllBtn.parent().addClass('penci-loading');
        addLoading();
        recursiveApply($patches);
	});

    function recursiveApply($patches){
        var $applyAllBtn = $('.penci-patch-apply-all');

        if ( 0 === $patches.length ) {
            $applyAllBtn.parent().addClass('penci-applied');
            $applyAllBtn.parent().removeClass('penci-loading');
            removeLoading();

            return;
        }

        var $patch = $($patches.pop());
        var id     = $patch.find('.penci-patch-apply').data('id');

        sendAjax(id , function(response) {
            if ( 'undefined' !== typeof response.message && 'error' === response.status ) {
				$applyAllBtn.parent().removeClass('penci-loading');
                printNotice(response.status, response.message);
            }

			if ( 0 === $patches.length ) {
				printNotice('success', penci_patch_notice.all_patches_applied);
			}

            if ( 'undefined' !== typeof response.status && 'success' === response.status ) {
                $patch.addClass('penci-applied');
				updatePatcherCounter();

                recursiveApply($patches);
            } else {
                removeLoading();
            }
        });
    }

	function sendAjax(id, cb) {
		$.ajax({
			url    : PENCIDASHBOARD.ajaxUrl,
			data   : {
				action   : 'penci_patch_action',
				security : PENCIDASHBOARD.patcher_nonce,
				id,
			},
			timeout: 1000000,
			error  : function() {
				printNotice('error', penci_patch_notice.ajax_error);
			},
			success: cb
		});
	}

	// Helpers.
	function printNotice(type, message) {
		$('.penci-notices-wrapper').append(`
			<div class="penci-notice penci-${type}">
				${message}
			</div>
		`);

		setTimeout(function(){
			$('.penci-notice').addClass('penci-hidden');
		}, 7000);
	}

	function cleanNotice() {
		$('.penci-notices-wrapper').text('');
	}

	function addLoading() {
		$('.penci-box-content').addClass('penci-loading');
		$('.penci-patch-apply-all').addClass('penci-disabled');
	}

	function removeLoading() {
		$('.penci-box-content').removeClass('penci-loading');
		$('.penci-patch-apply-all').removeClass('penci-disabled');
	}

	function updatePatcherCounter() {
		var $counters = document.querySelectorAll('.penci-patcher-counter');

		$counters.forEach( $counter => {
			if ( null === $counter) {
				return;
			}

			var $count = parseInt($counter.querySelector('.patcher-count').innerText);

			if ( 1 === $count ) {
				$counter.classList.add('penci-hidden');
			} else {
				$counter.querySelector('.patcher-count').innerText = --$count;
			}
		});
	}

})(jQuery);