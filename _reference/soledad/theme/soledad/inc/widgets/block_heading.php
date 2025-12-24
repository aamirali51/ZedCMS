<?php
/**
 * Block widget heading title
 * Display the widget heading title follow the styles from the theme
 *
 * @package Wordpress
 * @since   7.9
 */

add_action( 'widgets_init', 'penci_block_heading_load_widget' );

function penci_block_heading_load_widget() {
	register_widget( 'penci_block_heading_widget' );
}

if ( ! class_exists( 'penci_block_heading_widget' ) ) {
	class penci_block_heading_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_block_heading_widget',
				'description' => esc_html__( 'A widget that displays the widget heading follow the styles from the theme. It helps if you\'re using Widget Blocks Editor from WordPress version 5.8', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'id_base' => 'penci_block_heading_widget' );

			/* Create the widget. */
			global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_block_heading_widget', penci_get_theme_name('.Soledad',true).esc_html__( 'Block Heading', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_block_heading_widget', penci_get_theme_name('.Soledad',true).esc_html__( 'Block Heading', 'soledad' ), $widget_ops, $control_ops );
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

			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo '<h3 class="widget-title penci-border-arrow"><span class="inner-arrow">' . $title . '</span></h3>';
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
			$defaults = array( 'title' => 'Example Title' );

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

			<?php
		}
	}
}
?>
