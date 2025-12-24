<?php
/**
 * About me widget
 * Display your information on footer or sidebar
 *
 * @package Wordpress
 * @since   1.0
 */

add_action( 'widgets_init', 'penci_about_load_widget' );

function penci_about_load_widget() {
	register_widget( 'penci_about_widget' );
}

if ( ! class_exists( 'penci_about_widget' ) ) {
	class penci_about_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_about_widget',
				'description' => esc_html__( 'A widget that displays an About widget', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'id_base' => 'penci_about_widget' );

			/* Create the widget. */
			global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_about_widget', penci_get_theme_name('.Soledad',true).esc_html__( 'About Me', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_about_widget', penci_get_theme_name('.Soledad',true).esc_html__( 'About Me', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );

			/* Our variables from the widget settings. */
			$title         = isset( $instance['title'] ) ? $instance['title'] : '';
			$title         = apply_filters( 'widget_title', $title );
			$align         = isset( $instance['align'] ) ? $instance['align'] : '';
			$image         = isset( $instance['image'] ) ? $instance['image'] : '';
			$circle        = isset( $instance['circle'] ) ? $instance['circle'] : '';
			$lazyload      = isset( $instance['lazyload'] ) ? $instance['lazyload'] : '';
			$imageurl      = isset( $instance['imageurl'] ) ? $instance['imageurl'] : '';
			$target        = isset( $instance['target'] ) ? $instance['target'] : '';
			$imagemaxwidth = isset( $instance['imagemaxwidth'] ) ? $instance['imagemaxwidth'] : '';
			$heading       = isset( $instance['heading'] ) ? $instance['heading'] : '';
			$description   = isset( $instance['description'] ) ? $instance['description'] : '';
			$fheading      = isset( $instance['fheading'] ) ? absint( $instance['fheading'] ) : '';
			$fdesc         = isset( $instance['fdesc'] ) ? absint( $instance['fdesc'] ) : '';

			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo( $before_title . $title . $after_title );
			}

			$inline_style      = '';
			$open_image        = '';
			$close_image       = '';
			$target_html       = '';
			$inline_style_html = '';

			if ( $circle ):
				$inline_style .= 'border-radius: 50%; -webkit-border-radius: 50%;';
			endif;
			if ( $imagemaxwidth ):
				$inline_style .= 'max-width: ' . $imagemaxwidth . 'px !important;';
			endif;
			if ( $inline_style ) {
				$inline_style_html = ' style="' . $inline_style . '"';
			}
			if ( $imageurl ):
				if ( $target ): $target_html = ' target="_blank"'; endif;
				$open_image  = '<a href="' . do_shortcode( $imageurl ) . '"' . $target_html . '>';
				$close_image = '</a>';
			endif;
			$rand        = rand( 1000, 10000 );
			$image_width = $image_height = '';
			?>
            <div id="penci-aboutmewg-<?php echo $rand; ?>"
                 class="about-widget<?php if ( $align ): echo ' ' . $align; endif; ?>">
				<?php if ( $image ) :
					$image_width = penci_get_image_data_basedurl( $image, 'w' );
					$image_height = penci_get_image_data_basedurl( $image, 'h' );
					?>
					<?php echo $open_image; ?>
					<?php if ( ! $lazyload ) { ?>
                    <img class="penci-widget-about-image nopin penci-lazy" nopin="nopin"
                         width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>"
                         src="<?php echo penci_holder_image_base( $image_width, $image_height ); ?>"
                         data-src="<?php echo esc_url( $image ); ?>"
                         alt="<?php echo esc_attr( $title ); ?>"<?php echo $inline_style_html; ?>/>
				<?php } else { ?>
                    <img class="penci-widget-about-image nopin" nopin="nopin" width="<?php echo $image_width; ?>"
                         height="<?php echo $image_height; ?>" src="<?php echo esc_url( $image ); ?>"
                         alt="<?php echo esc_attr( $title ); ?>"<?php echo $inline_style_html; ?>/>
				<?php } ?>
					<?php echo $close_image; ?>
				<?php endif; ?>

				<?php if ( $heading ) : ?>
                    <h2 class="about-me-heading"><?php echo do_shortcode( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $description ) : ?>
                    <div class="about-me-desc"><p><?php echo do_shortcode( $description ); ?></p></div>
				<?php endif; ?>

            </div>
			<?php
			$attrstyle = '';
			if ( $fheading ) {
				$attrstyle .= '#penci-aboutmewg-' . $rand . ' .about-me-heading{ font-size: ' . $fheading . 'px; }';
			}
			if ( $fdesc ) {
				$attrstyle .= '#penci-aboutmewg-' . $rand . ' .about-me-desc, #penci-aboutmewg-' . $rand . ' .about-me-desc p{ font-size: ' . $fdesc . 'px; }';
			}

			if ( $attrstyle ) {
				echo '<style type="text/css">' . $attrstyle . '</style>';
			}

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
			$defaults = array(
				'title'         => 'About Me',
				'align'         => '',
				'fheading'      => '',
				'fdesc'         => '',
				'image'         => '',
				'circle'        => '',
				'imagemaxwidth' => '',
				'lazyload'      => '',
				'imageurl'      => '',
				'target'        => '',
				'heading'       => '',
				'description'   => ''
			);

			return $defaults;
		}

		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults = $this->soledad_widget_defaults();
			$instance = wp_parse_args( (array) $instance, $defaults );

			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			?>
            <!-- Widget Title: Text Input -->

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <!-- Align -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>">Align This Widget:</label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>" class="widefat categories"
                        style="width:100%;">
                    <option value='pc_aligncenter' <?php if ( '' == $instance['align'] ) {
						echo 'selected="selected"';
					} ?>>Align Center
                    </option>
                    <option value='pc_alignleft' <?php if ( 'pc_alignleft' == $instance['align'] ) {
						echo 'selected="selected"';
					} ?>>Align Left
                    </option>
                    <option value='pc_alignright' <?php if ( 'pc_alignright' == $instance['align'] ) {
						echo 'selected="selected"';
					} ?>>Align Right
                    </option>
                </select>
            </p>

            <!-- image url -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php esc_html_e( 'About Image URL:', 'soledad' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>"
                       value="<?php echo esc_url( $instance['image'] ); ?>"/><br/>
                <small><?php _e( 'Insert your image URL. To get good for load, let use image about 400px width.<br>You can check <a href="https://www.wpbeginner.com/beginners-guide/how-to-get-the-url-of-images-you-upload-in-wordpress/" target="_blank" rel="noreferrer noopener">this guide</a> to know how to get URL of an image you upload.', 'soledad' ); ?></small>
            </p>

            <!-- Circle image -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'circle' ) ); ?>"><?php esc_html_e( 'Make About Image Circle:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'circle' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'circle' ) ); ?>" <?php checked( (bool) $instance['circle'], true ); ?> /><br/>
                <small><?php esc_html_e( 'To use this feature, please use square image for your image above to get best display.', 'soledad' ); ?></small>
            </p>

            <!-- Lazyload image -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'lazyload' ) ); ?>"><?php esc_html_e( 'Disable Lazyload for About Me Image:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'lazyload' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'lazyload' ) ); ?>" <?php checked( (bool) $instance['lazyload'], true ); ?> />
            </p>

            <!-- Link for image -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'imageurl' ) ); ?>"><?php esc_html_e( 'Add Link for About Image:', 'soledad' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'imageurl' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'imageurl' ) ); ?>"
                       value="<?php echo sanitize_text_field( $instance['imageurl'] ); ?>"/>
                <small>If you want to clickable on the about me image link to other page, put the link here. Include
                    <strong style="font-weight: bold;">http://</strong> or <strong
                            style="font-weight: bold;">https://</strong> on the link</small>
            </p>

            <!-- Open new tab image -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"><?php esc_html_e( 'Click About Image Open in New Tab?', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" <?php checked( (bool) $instance['target'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'imagemaxwidth' ) ); ?>"><?php esc_html_e( 'Custom Max Width for About Me Image:', 'soledad' ); ?></label>
                <input type="number" style="width: 65px; display: inline-block;"
                       id="<?php echo esc_attr( $this->get_field_id( 'imagemaxwidth' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'imagemaxwidth' ) ); ?>"
                       value="<?php echo esc_attr( $instance['imagemaxwidth'] ); ?>" size="3"/> px
            </p>

            <!-- heading text -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>"><?php esc_html_e( 'Heading Text:', 'soledad' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'heading' ) ); ?>"
                       value="<?php echo sanitize_text_field( $instance['heading'] ); ?>"/>
            </p>

            <!-- description -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'About me text: ( you can use HTML here )', 'soledad' ); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"
                          name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"
                          rows="6"><?php echo esc_textarea( $instance['description'] ); ?></textarea>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'fheading' ) ); ?>"><?php esc_html_e( 'Heading Text Font Size ( Default: 18 )', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'fheading' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'fheading' ) ); ?>"
                       value="<?php echo esc_attr( $instance['fheading'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'fdesc' ) ); ?>"><?php esc_html_e( 'About Me Text Font Size', 'soledad' ); ?></label>
                <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'fdesc' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'fdesc' ) ); ?>"
                       value="<?php echo esc_attr( $instance['fdesc'] ); ?>" size="3"/>
            </p>

			<?php
		}
	}
}
?>
