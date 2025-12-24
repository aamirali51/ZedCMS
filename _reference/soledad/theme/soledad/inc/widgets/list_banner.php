<?php
/**
 * List Banner Widget
 * Display your banners or featured categories/posts/pages on the widget.
 *
 * @package Wordpress
 * @since   1.0
 */

add_action( 'widgets_init', 'penci_list_banner_load_widget' );

function penci_list_banner_load_widget() {
	register_widget( 'penci_list_bannner_widget' );
}

if ( ! class_exists( 'penci_list_bannner_widget' ) ) {
class penci_list_bannner_widget extends WP_Widget {

/**
 * Widget setup.
 */
function __construct() {
	/* Widget settings. */
	$widget_ops = array(
		'classname'   => 'penci_list_imgs_widget',
		'description' => esc_html__( 'A widget helps you display a banner or multi-banners on your sidebar. You also can use this widget to display your featured categories/posts/pages.. Everything is custom', 'soledad' )
	);

	/* Widget control settings. */
	$control_ops = array( 'id_base' => 'penci_list_bannner_widget' );

	/* Create the widget. */
	global $wp_version;
	if ( 4.3 > $wp_version ) {
		$this->WP_Widget( 'penci_list_bannner_widget', penci_get_theme_name('.Soledad',true).esc_html__( 'List Banners', 'soledad' ), $widget_ops, $control_ops );
	} else {
		parent::__construct( 'penci_list_bannner_widget', penci_get_theme_name('.Soledad',true).esc_html__( 'List Banners', 'soledad' ), $widget_ops, $control_ops );
	}
}

/**
 * How to display the widget on the screen.
 */
function widget( $args, $instance ) {
extract( $args );

/* Our variables from the widget settings. */
$title  = isset( $instance['title'] ) ? $instance['title'] : '';
$title  = apply_filters( 'widget_title', $title );
$img1   = isset( $instance['img1'] ) ? $instance['img1'] : '';
$url1   = isset( $instance['url1'] ) ? $instance['url1'] : '';
$text1  = isset( $instance['text1'] ) ? $instance['text1'] : '';
$img2   = isset( $instance['img2'] ) ? $instance['img2'] : '';
$url2   = isset( $instance['url2'] ) ? $instance['url2'] : '';
$text2  = isset( $instance['text2'] ) ? $instance['text2'] : '';
$img3   = isset( $instance['img3'] ) ? $instance['img3'] : '';
$url3   = isset( $instance['url3'] ) ? $instance['url3'] : '';
$text3  = isset( $instance['text3'] ) ? $instance['text3'] : '';
$tab    = isset( $instance['tab'] ) ? $instance['tab'] : '';
$crop   = isset( $instance['crop'] ) ? $instance['crop'] : '';
$height = isset( $instance['height'] ) ? $instance['height'] : '';

/* Before widget (defined by themes). */
echo ent2ncr( $before_widget );

/* Display the widget title if one was input (before and after defined by themes). */
if ( $title ) {
	echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
}

?>
<?php if ( $img1 || $img2 || $img3 ): ?>
<?php
/* Check open a new tab is checked or not */
$target = '';
if ( $tab ): $target = ' target="_blank"'; endif;
?>
<div class="penci-list-banner">
	<?php if ( $img1 ) : /* Banner 1 */ 
	$img1_width = penci_get_image_data_basedurl( $img1, 'w' );
	$img1_height = penci_get_image_data_basedurl( $img1, 'h' );
	?>
	<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
    <div class="penci-promo-item penci-lazy<?php if ( $text1 ): echo ' penci-banner-has-text'; endif; /* Crop */
	if ( $crop && ! $height ): echo ' penci-banner-crop'; endif; ?>"<?php if ( $crop && ! $height ) {
		echo ' data-bgset="' . do_shortcode( $img1 ) . '"';
	} elseif ( $crop && $height ) {
		echo ' data-bgset="' . do_shortcode( $img1 ) . '" style="height:' . $height . 'px;"';
	} ?>>
		<?php } else { ?>
        <div class="penci-promo-item<?php if ( $text1 ): echo ' penci-banner-has-text'; endif; /* Crop */
		if ( $crop && ! $height ): echo ' penci-banner-crop'; endif; ?>"<?php if ( $crop && ! $height ) {
			echo ' style="background-image: url(' . do_shortcode( $img1 ) . ')"';
		} elseif ( $crop && $height ) {
			echo ' style="background-image: url(' . do_shortcode( $img1 ) . '); height:' . $height . 'px;"';
		} ?>>
			<?php } ?>
			<?php if ( $url1 ): ?>
                <a class="penci-promo-url" aria-label="<?php if ( $text1 ) {
					echo $text1;
				} else {
					echo 'Banner1';
				} ?>" href="<?php echo do_shortcode( $url1 ); ?>"<?php echo sanitize_text_field( $target ); ?>></a>
			<?php endif; ?>
			<?php if ( ! $crop ): ?>
				<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
                    <img class="penci-lazy" src="<?php echo penci_holder_image_base( $img1_width, $img1_height ); ?>"
                         data-src="<?php echo do_shortcode( $img1 ); ?>" alt="Promotion Image" width="<?php echo $img1_width; ?>" height="<?php echo $img1_height; ?>">
				<?php } else { ?>
                    <img src="<?php echo do_shortcode( $img1 ); ?>" alt="Promotion Image" width="<?php echo $img1_width; ?>" height="<?php echo $img1_height; ?>">
				<?php } ?>
			<?php endif; ?>
			<?php if ( $text1 ): ?>
                <div class="penci-promo-text">
                    <h4><?php echo do_shortcode( $text1 ); ?></h4>
                </div>
			<?php endif; ?>
        </div>
		<?php endif; ?>

		<?php if ( $img2 ) : /* Banner 2 */ 
		$img2_width = penci_get_image_data_basedurl( $img2, 'w' );
		$img2_height = penci_get_image_data_basedurl( $img2, 'h' );
		?>
		<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
        <div class="penci-promo-item penci-lazy<?php if ( $text2 ): echo ' penci-banner-has-text'; endif; /* Crop */
		if ( $crop && ! $height ): echo ' penci-banner-crop'; endif; ?>"<?php if ( $crop && ! $height ) {
			echo ' data-bgset="' . do_shortcode( $img2 ) . '"';
		} elseif ( $crop && $height ) {
			echo ' data-bgset="' . do_shortcode( $img2 ) . '" style="height:' . $height . 'px;"';
		} ?>>
			<?php } else { ?>
            <div class="penci-promo-item<?php if ( $text2 ): echo ' penci-banner-has-text'; endif; /* Crop */
			if ( $crop && ! $height ): echo ' penci-banner-crop'; endif; ?>"<?php if ( $crop && ! $height ) {
				echo ' style="background-image: url(' . do_shortcode( $img2 ) . ')"';
			} elseif ( $crop && $height ) {
				echo ' style="background-image: url(' . do_shortcode( $img2 ) . '); height:' . $height . 'px;"';
			} ?>>
				<?php } ?>
				<?php if ( $url2 ): ?>
                    <a class="penci-promo-url" aria-label="<?php if ( $text2 ) {
						echo $text2;
					} else {
						echo 'Banner2';
					} ?>" href="<?php echo do_shortcode( $url2 ); ?>"<?php echo sanitize_text_field( $target ); ?>></a>
				<?php endif; ?>
				<?php if ( ! $crop ): ?>
					<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
                        <img class="penci-lazy" src="<?php echo penci_holder_image_base( $img2_width, $img2_height ); ?>"
                             data-src="<?php echo do_shortcode( $img2 ); ?>" alt="Promotion Image" width="<?php echo $img2_width; ?>" height="<?php echo $img2_height; ?>">
					<?php } else { ?>
                        <img src="<?php echo do_shortcode( $img2 ); ?>" alt="Promotion Image" width="<?php echo $img2_width; ?>" height="<?php echo $img2_height; ?>">
					<?php } ?>
				<?php endif; ?>

				<?php if ( $text2 ): ?>
                    <div class="penci-promo-text">
                        <h4><?php echo do_shortcode( $text2 ); ?></h4>
                    </div>
				<?php endif; ?>
            </div>
			<?php endif; ?>

			<?php if ( $img3 ) : /* Banner 3 */ 
			$img3_width = penci_get_image_data_basedurl( $img3, 'w' );
			$img3_height = penci_get_image_data_basedurl( $img3, 'h' );
			?>
		<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
            <div class="penci-promo-item penci-lazy<?php if ( $text3 ): echo ' penci-banner-has-text'; endif; /* Crop */
			if ( $crop && ! $height ): echo ' penci-banner-crop'; endif; ?>"<?php if ( $crop && ! $height ) {
				echo ' data-bgset="' . do_shortcode( $img3 ) . '"';
			} elseif ( $crop && $height ) {
				echo ' data-bgset="' . do_shortcode( $img3 ) . '" style="height:' . $height . 'px;"';
			} ?>>
				<?php } else { ?>
                <div class="penci-promo-item<?php if ( $text3 ): echo ' penci-banner-has-text'; endif; /* Crop */
				if ( $crop && ! $height ): echo ' penci-banner-crop'; endif; ?>"<?php if ( $crop && ! $height ) {
					echo ' style="background-image: url(' . do_shortcode( $img3 ) . ')"';
				} elseif ( $crop && $height ) {
					echo ' style="background-image: url(' . do_shortcode( $img3 ) . '); height:' . $height . 'px;"';
				} ?>>
					<?php } ?>
					<?php if ( $url3 ): ?>
                        <a class="penci-promo-url" aria-label="<?php if ( $text3 ) {
							echo $text3;
						} else {
							echo 'Banner';
						} ?>"
                           href="<?php echo do_shortcode( $url3 ); ?>"<?php echo sanitize_text_field( $target ); ?>></a>
					<?php endif; ?>
					<?php if ( ! $crop ): ?>
						<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
                            <img class="penci-lazy" src="<?php echo penci_holder_image_base( $img3_width, $img3_height ); ?>"
                                 data-src="<?php echo do_shortcode( $img3 ); ?>" alt="Promotion Image" width="<?php echo $img3_width; ?>" height="<?php echo $img3_height; ?>">
						<?php } else { ?>
                            <img src="<?php echo do_shortcode( $img3 ); ?>" alt="Promotion Image" width="<?php echo $img3_width; ?>" height="<?php echo $img3_height; ?>">
						<?php } ?>
					<?php endif; ?>
					<?php if ( $text3 ): ?>
                        <div class="penci-promo-text">
                            <h4><?php echo do_shortcode( $text3 ); ?></h4>
                        </div>
					<?php endif; ?>
                </div>
				<?php endif; ?>
            </div>
			<?php endif; /* End check if doesn't exists any image for this widget */ ?>

			<?php

			/* After widget (defined by themes). */
			echo ent2ncr( $after_widget );
			}

			/**
			 * Update the widget settings.
			 */
			function update( $new_instance, $old_instance ) {
				$instance = $old_instance;

				$data_instance = $this->soledad_widget_defaults();

				foreach ( $data_instance as $data => $value ) {
					$instance[ $data ] = ! empty( $new_instance[ $data ] ) ? $new_instance[ $data ] : '';
				}

				return $instance;
			}

			public function soledad_widget_defaults() {
				$defaults = array( 'title'  => '',
				                   'img1'   => '',
				                   'url1'   => '',
				                   'text1'  => '',
				                   'img2'   => '',
				                   'url2'   => '',
				                   'text2'  => '',
				                   'img3'   => '',
				                   'url3'   => '',
				                   'text3'  => '',
				                   'height' => '',
				                   'tab'    => false,
				                   'crop'   => false
				);

				return $defaults;
			}


			function form( $instance ) {

				/* Set up some default widget settings. */
				$defaults = $this->soledad_widget_defaults();
				$instance = wp_parse_args( (array) $instance, $defaults );

				$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
				?>

                <p>This widget helps you display a banner or multi-banners on your sidebar. You also can use this widget
                    to display your featured categories/posts/pages.. Everything is custom!</p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                           value="<?php echo $instance_title; ?>"/>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'img1' ) ); ?>"><?php esc_html_e( '1st Banner URL:', 'soledad' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'img1' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'img1' ) ); ?>"
                           value="<?php echo esc_url( $instance['img1'] ); ?>"/>
                    <span class="description" style="padding: 0 0 10px 0; font-size: 12px;">You should use image with the width about 400 - 500px.<br>You can check <a
                                href="https://www.wpbeginner.com/beginners-guide/how-to-get-the-url-of-images-you-upload-in-wordpress/"
                                target="_blank" rel="noreferrer noopener">this guide</a> to know how to get URL of an image you upload.</span>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'url1' ) ); ?>"><?php esc_html_e( 'Banner Link for 1st Banner:', 'soledad' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url1' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'url1' ) ); ?>"
                           value="<?php echo esc_url( $instance['url1'] ); ?>"/><br>
                    <span class="description" style="padding: 0 0 10px 0; font-size: 12px;">It's the link you want to redirect when the readers click to 1st banner</span>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'text1' ) ); ?>"><?php esc_html_e( 'Banner Text for 1st Banner:', 'soledad' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text1' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'text1' ) ); ?>"
                           value="<?php echo sanitize_text_field( $instance['text1'] ); ?>"/>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'img2' ) ); ?>"><?php esc_html_e( 'Banner 2nd URL:', 'soledad' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'img2' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'img2' ) ); ?>"
                           value="<?php echo esc_url( $instance['img2'] ); ?>"/>
                    <span class="description" style="padding: 0 0 10px 0; font-size: 12px;">You should use image with the width about 400 - 500px.<br>You can check <a
                                href="https://www.wpbeginner.com/beginners-guide/how-to-get-the-url-of-images-you-upload-in-wordpress/"
                                target="_blank" rel="noreferrer noopener">this guide</a> to know how to get URL of an image you upload.</span>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'url2' ) ); ?>"><?php esc_html_e( 'Banner Link for 2nd Banner:', 'soledad' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url2' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'url2' ) ); ?>"
                           value="<?php echo esc_url( $instance['url2'] ); ?>"/><br>
                    <span class="description" style="padding: 0 0 10px 0; font-size: 12px;">It's the link you want to redirect when the readers click to 2nd banner</span>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'text2' ) ); ?>"><?php esc_html_e( 'Banner Text for 2nd Banner:', 'soledad' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text2' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'text2' ) ); ?>"
                           value="<?php echo sanitize_text_field( $instance['text2'] ); ?>"/>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'img3' ) ); ?>"><?php esc_html_e( 'Banner 3rd URL:', 'soledad' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'img3' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'img3' ) ); ?>"
                           value="<?php echo esc_url( $instance['img3'] ); ?>"/>
                    <span class="description" style="padding: 0 0 10px 0; font-size: 12px;">You should use image with the width about 400 - 500px.<br>You can check <a
                                href="https://www.wpbeginner.com/beginners-guide/how-to-get-the-url-of-images-you-upload-in-wordpress/"
                                target="_blank" rel="noreferrer noopener">this guide</a> to know how to get URL of an image you upload.</span>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'url3' ) ); ?>"><?php esc_html_e( 'Banner Link for 3rd Banner:', 'soledad' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url3' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'url3' ) ); ?>"
                           value="<?php echo esc_url( $instance['url3'] ); ?>"/><br>
                    <span class="description" style="padding: 0 0 10px 0; font-size: 12px;">It's the link you want to redirect when the readers click to 3rd banner</span>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'text3' ) ); ?>"><?php esc_html_e( 'Banner Text for 3rd Banner:', 'soledad' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text3' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'text3' ) ); ?>"
                           value="<?php echo sanitize_text_field( $instance['text3'] ); ?>"/>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'crop' ) ); ?>"><?php esc_html_e( 'Auto Crop Banner Images?:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'crop' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'crop' ) ); ?>" <?php checked( (bool) $instance['crop'], true ); ?> />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e( 'Set Height for Crop Banner Images:', 'soledad' ); ?></label>
                    <input class="widefat" type="number" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>"
                           value="<?php echo absint( $instance['height'] ); ?>"/>
                    <span class="description" style="padding: 0 0 10px 0; font-size: 12px;">When you check to crop banner images, you can set height here for crop banner image</span>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'tab' ) ); ?>"><?php esc_html_e( 'Open Banners in New Tab?:', 'soledad' ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'tab' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'tab' ) ); ?>" <?php checked( (bool) $instance['tab'], true ); ?> />
                </p>


				<?php
			}
			}
			}

			?>
