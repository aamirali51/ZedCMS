<?php

add_action( 'widgets_init', 'penci_search_box_load_widget' );

function penci_search_box_load_widget() {
	register_widget( 'penci_search_box_widget' );
}

if ( ! class_exists( 'penci_search_box_widget' ) ) {
	class penci_search_box_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_search_box_widget',
				'description' => esc_html__( 'A widget that displays the search form', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'penci_search_box_widget' );

			/* Create the widget. */ global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_search_box_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Search Box', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_search_box_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Search Box', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );

			/* Our variables from the widget settings. */
			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			$title = apply_filters( 'widget_title', $title );
			$style = isset( $instance['style'] ) && $instance['style'] ? $instance['style'] : 'default';


			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
			}

			?>
            <div class="pcwg-widget pc-widget-searchform penci-builder-element pc-search-form search-style-<?php echo $style; ?>">
                <form role="search" method="get" class="pc-searchform"
                      action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="pc-searchform-inner">
                        <input type="text" class="search-input"
                               placeholder="<?php echo penci_get_setting( 'penci_trans_type_and_hit' ); ?>" name="s"/>
                        <i class="penciicon-magnifiying-glass"></i>
                        <button type="submit"
                                class="searchsubmit penci-ele-btn"><?php echo penci_get_setting( 'penci_trans_search' ); ?></button>
                    </div>
                </form>
            </div>
			<?php
			$styles = [
				'bgcolor'     => [
					'background-color' => '#' . $this->id . ' .pc-widget-searchform form.pc-searchform input.search-input'
				],
				'bdcolor'     => [
					'border-color' => '#' . $this->id . ' .pc-widget-searchform form.pc-searchform input.search-input'
				],
				'txtcolor'    => [
					'color' => '#' . $this->id . ' form.pc-searchform input.search-input',
				],
				'btncolor'    => [
					'color' => '#' . $this->id . ' .pc-widget-searchform.search-style-default i,#' . $this->id . ' .pc-widget-searchform.search-style-icon-button .searchsubmit,#' . $this->id . ' .pc-widget-searchform.pc-search-form.search-style-text-button .searchsubmit'
				],
				'btnhcolor'   => [
					'color' => '#' . $this->id . ' .pc-widget-searchform.search-style-icon-button .searchsubmit:hover,#' . $this->id . ' .pc-widget-searchform.pc-search-form.search-style-text-button .searchsubmit:hover'
				],
				'btnbgcolor'  => [
					'background-color' => '#' . $this->id . ' .pc-widget-searchform.search-style-icon-button .searchsubmit,#' . $this->id . ' .pc-widget-searchform.pc-search-form.search-style-text-button .searchsubmit'
				],
				'btnbghcolor' => [
					'background-color' => '#' . $this->id . ' .pc-widget-searchform.search-style-icon-button .searchsubmit:hover,#' . $this->id . ' .pc-widget-searchform.pc-search-form.search-style-text-button .searchsubmit:hover'
				],
				'ch'          => [
					'line-height' => '#' . $this->id . ' .pc-widget-searchform form.pc-searchform input.search-input,#' . $this->id . ' .pc-widget-searchform.search-style-icon-button .searchsubmit:before,#' . $this->id . ' .pc-widget-searchform.search-style-text-button .searchsubmit ',
				],
				'fzinput'     => [
					'font-size' => '#' . $this->id . ' .pc-widget-searchform form.pc-searchform input.search-input',
				],
				'fzbtn'       => [
					'font-size' => '#' . $this->id . ' .pc-widget-searchform.search-style-default i,
							#' . $this->id . ' .pc-widget-searchform.search-style-icon-button .searchsubmit:before,
							#' . $this->id . ' .pc-widget-searchform.search-style-text-button .searchsubmit',
				],
			];

			$out = '';

			foreach ( $styles as $option => $selectors ) {
				$value = isset( $instance[ $option ] ) ? $instance[ $option ] : '';
				if ( $value ) {
					foreach ( $selectors as $prop => $selector ) {
						$prefix = in_array( $prop, [ 'font-size', 'line-height' ] ) ? 'px' : '';
						$out    .= $selector . '{' . $prop . ':' . $value . $prefix . '}';
					}
				}
			}

			if ( $out ) {
				echo '<style>' . $out . '</style>';
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
			return array(
				'title'       => esc_html__( 'Search', 'soledad' ),
				'style'       => 'default',
				'bgcolor'     => '',
				'bdcolor'     => '',
				'txtcolor'    => '',
				'txthcolor'   => '',
				'btncolor'    => '',
				'btnhcolor'   => '',
				'btnbgcolor'  => '',
				'btnbghcolor' => '',
				'ch'          => '',
				'fzinput'     => '',
				'fzbtn'       => '',
			);
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
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>">Search Form Style:</label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" class="widefat categories"
                        style="width:100%;">
                    <option value=''>Default
                    </option>
                    <option value='text-button' <?php selected( $instance['style'], 'text-button' ); ?>>Text Button
                    </option>
                    <option value='icon-button' <?php selected( $instance['style'], 'icon-button' ); ?>>Icon Button
                    </option>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'bgcolor' ); ?>"
                       style="display:block;"><?php _e( 'Background Color:' ); ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( 'bgcolor' ); ?>"
                       name="<?php echo $this->get_field_name( 'bgcolor' ); ?>" type="text"
                       value="<?php echo $instance['bgcolor']; ?>"/>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'bdcolor' ); ?>"
                       style="display:block;"><?php _e( 'Border Color:' ); ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( 'bdcolor' ); ?>"
                       name="<?php echo $this->get_field_name( 'bdcolor' ); ?>" type="text"
                       value="<?php echo $instance['bdcolor']; ?>"/>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'txtcolor' ); ?>"
                       style="display:block;"><?php _e( 'Search Input Text Color:' ); ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( 'txtcolor' ); ?>"
                       name="<?php echo $this->get_field_name( 'txtcolor' ); ?>" type="text"
                       value="<?php echo $instance['txtcolor']; ?>"/>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'btncolor' ); ?>"
                       style="display:block;"><?php _e( 'Button/Icon Text Color:' ); ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( 'btncolor' ); ?>"
                       name="<?php echo $this->get_field_name( 'btncolor' ); ?>" type="text"
                       value="<?php echo $instance['btncolor']; ?>"/>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'btnhcolor' ); ?>"
                       style="display:block;"><?php _e( 'Button/Icon Hover Text Color:' ); ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( 'btnhcolor' ); ?>"
                       name="<?php echo $this->get_field_name( 'btnhcolor' ); ?>" type="text"
                       value="<?php echo $instance['btnhcolor']; ?>"/>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'btnbgcolor' ); ?>"
                       style="display:block;"><?php _e( 'Button/Icon Background Color:' ); ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( 'btnbgcolor' ); ?>"
                       name="<?php echo $this->get_field_name( 'btnbgcolor' ); ?>" type="text"
                       value="<?php echo $instance['btnbgcolor']; ?>"/>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'btnbghcolor' ); ?>"
                       style="display:block;"><?php _e( 'Button/Icon Hover Background Color:' ); ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( 'btnbghcolor' ); ?>"
                       name="<?php echo $this->get_field_name( 'btnbghcolor' ); ?>" type="text"
                       value="<?php echo $instance['btnbghcolor']; ?>"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ch' ) ); ?>">Custom Height</label>
                <input type="number" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'ch' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'ch' ) ); ?>"
                       value="<?php echo $instance['ch']; ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'fzinput' ) ); ?>">Font Size for Input
                    Text</label>
                <input type="number" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'fzinput' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'fzinput' ) ); ?>"
                       value="<?php echo $instance['fzinput']; ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'fzbtn' ) ); ?>">Font Size for Button/Icon</label>
                <input type="number" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'fzbtn' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'fzbtn' ) ); ?>"
                       value="<?php echo $instance['fzbtn']; ?>">
            </p>

			<?php
		}
	}
}
?>
