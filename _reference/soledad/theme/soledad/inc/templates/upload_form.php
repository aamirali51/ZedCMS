<?php
extract( $args );
$btn_class              = is_admin() ? ' button button-primary' : '';
$upload_preview_wrapper = $wrapper = isset( $wrapper ) && ! empty( $wrapper ) ? $wrapper : 'upload_preview_container'; ?>
<div id="<?php echo esc_attr( $id ); ?>" class="penci_upload_wrapper <?php echo esc_attr( $class ); ?>">

	<?php if ( apply_filters( 'penci_enable_upload', true ) ) : ?>
        <div class="<?php echo esc_attr( $upload_preview_wrapper ); ?>">
            <ul>
				<?php

				if ( $source && is_array( $source ) ) {
					$output = '';

					foreach ( $source as $item ) {
						if ( is_string( $item ) && ! is_numeric( $item ) ) {
							$output .=
								'<li>
                                <input type="hidden" name="' . $name . '[]" value="' . esc_attr( $item ) . '">
                                <img src="' . esc_url( $item ) . '">
                                <div class="remove"><i class="penciicon-close-button"></i></div>
                            </li>';
						} else {
							$image = wp_get_attachment_image_src( $item, apply_filters( 'penci_upload_preview_size', 'thumbnail' ) );

							if ( $image ) {
								$output .=
									'<li>
									<input type="hidden" name="' . $name . '[]" value="' . esc_attr( $item ) . '">
									<img alt="" src="' . esc_url( $image[0] ) . '"/>
									<div class="remove"><i class="penciicon-close-button"></i></div>
								</li>';
							}
						}
					}

					echo $output;
				}
				?>
            </ul>
        </div>
        <div id="<?php echo esc_attr( $button ); ?>" class="penci-upload-img-btn <?php echo $btn_class; ?>">
            <i class="fa fa-folder-open-o"></i>
            <span><?php echo isset( $heading ) && $heading ? $heading : penci_get_setting( 'penci_trans_cimage' ); ?></span>
        </div>
	<?php else : ?>
		<?php echo apply_filters( 'penci_enable_upload_msg', '' ); ?>
	<?php endif ?>

</div>

<?php if ( apply_filters( 'penci_enable_upload', true ) ) : ?>
    <script>
        (function ($) {
            $(document).on('ready', function () {
                var file_frame;

                $('#<?php echo esc_js( $button ); ?>').on('click', function (event) {
                    event.preventDefault();

                    var container = $(this).closest('.penci_upload_wrapper');

                    if (file_frame) {
                        file_frame.open();
                        return;
                    }

                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: '<?php echo penci_get_setting( 'penci_trans_admedia' ); ?>',
                        button: {
                            text: '<?php echo penci_get_setting( 'penci_trans_insert' ); ?>',
                        },
                        library: {
                            type:
							<?php
							$type = isset( $type ) && ! empty( $type ) ? $type : '';
							echo json_encode( $type );
							?>
                        },
                        multiple:
						<?php
						$multi = $multi ? 'true' : 'false';
						echo $multi;
						$multi = $multi === 'true' ? true : false;
						?>
                    });

                    file_frame.on('select', function () {
                        var output = '',
                            attachment = file_frame.state().get('selection').toJSON();

                        for (var i = 0; i < attachment.length; i++) {
                            output +=
                                '<li>' +
                                '<input type="hidden" name="<?php echo esc_attr( $name ); ?>[]" value="' + attachment[i]['id'] + '">' +
                                '<img src="' + attachment[i]['url'] + '">' +
                                '<div class="remove"><i class="penciicon-close-button"></i></div>' +
                                '</li>';
                        }


						<?php if ( $multi ) : ?>
                        container.find('ul').append(output);
						<?php else : ?>
                        container.find('ul').html(output);
						<?php endif ?>
                    });

                    file_frame.open();
                });

                $('#<?php echo esc_js( $id ); ?>').find(".<?php echo esc_attr( $upload_preview_wrapper ); ?>").on('click', '.remove', function () {
                    var parent = $(this).parent();
                    $(parent).fadeOut(function () {
                        $(this).remove();
                    });
                });

                $('#<?php echo esc_js( $id ); ?>').find('.<?php echo esc_attr( $upload_preview_wrapper ); ?> ul').sortable();
            });
        })(jQuery);
    </script>
<?php endif ?>
