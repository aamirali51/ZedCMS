<?php

add_action( 'widgets_init', 'penci_advanced_categories_load_widget' );

function penci_advanced_categories_load_widget() {
	register_widget( 'penci_advanced_categories_widget' );
}

if ( ! class_exists( 'penci_advanced_categories_widget' ) ) {
	class penci_advanced_categories_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */

		private $classname = [];

		function __construct() {
			/* Widget settings. */
			$this->classname[] = 'penci_advanced_categories_widget';
			$widget_ops        = array(
				'classname'   => implode( ',', $this->classname ),
				'description' => esc_html__( 'A widget that displays a list of category', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'penci_advanced_categories_widget' );

			/* Create the widget. */ global $wp_version;
			if ( 4.3 > $wp_version ) {
				$this->WP_Widget( 'penci_advanced_categories_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Advanced Categories', 'soledad' ), $widget_ops, $control_ops );
			} else {
				parent::__construct( 'penci_advanced_categories_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Advanced Categories', 'soledad' ), $widget_ops, $control_ops );
			}
		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );

			/* Our variables from the widget settings. */
			$title        = isset( $instance['title'] ) ? $instance['title'] : '';
			$title        = apply_filters( 'widget_title', $title );
			$style        = isset( $instance['style'] ) ? $instance['style'] : 'style-1';
			$rstyle       = isset( $instance['style'] ) ? $instance['style'] : 'style-1';
			$tax          = isset( $instance['tax'] ) ? $instance['tax'] : 'category';
			$hide_empty   = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : '';
			$order        = isset( $instance['order'] ) ? $instance['order'] : '';
			$orderby      = isset( $instance['orderby'] ) ? $instance['orderby'] : '';
			$hierarchical = isset( $instance['hierarchical'] ) && $instance['hierarchical'];
			$count        = isset( $instance['count'] ) && $instance['count'];
			$mark_count   = isset( $instance['mark_count'] ) && $instance['mark_count'];
			$maxitems     = isset( $instance['maxitems'] ) ? $instance['maxitems'] : '';
			$exclude      = isset( $instance['exclude'] ) ? $instance['exclude'] : '';
			$toggle       = isset( $instance['toggle'] ) ? $instance['toggle'] : '';

			$this->classname[] = 'widget_categories';
			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
			}
			$term_args = [
				'taxonomy'     => $tax,
				'hide_empty'   => ! $hide_empty,
				'order'        => $order,
				'orderby'      => $orderby,
				'hierarchical' => $hierarchical,
				'show_count'   => $count,
				'number'       => $maxitems,
				'title_li'     => '',
			];
			if ( $exclude ) {
				$term_args['exclude'] = explode( ',', $exclude );
			}
			$style = $mark_count ? $style . ' hlmark' : $style;
			add_filter( 'wp_list_categories', function ( $links ) use ( $mark_count ) {
				if ( $mark_count ) {
					$links = preg_replace( '/<\/a> \(([0-9.,]+)\)/', ' <span class="category-item-count">\\1</span></a>', $links );
				}

				return $links;

			}, 0, 1 );
			?>
            <div class="pc-widget-advanced-tax tax-<?php echo $style; ?>">
				<?php if ( 'style-3' == $rstyle ):
					$name = 'category' == $tax ? 'cat' : ( 'post_tag' == $tax ? 'tag' : $tax );
					$term_args['name'] = $name;
					$term_args['value_field'] = 'category' == $tax ? 'term_id' : 'slug';
					?>
                    <form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php
						wp_dropdown_categories( $term_args );
						?>
                        <script type="text/javascript">
                            /* <![CDATA[ */
                            (function () {
                                var dropdown = document.getElementById("<?php echo esc_attr( $name );?>");

                                function onCatChange() {
                                    if (dropdown.options[dropdown.selectedIndex].value !== '') {
                                        dropdown.parentNode.submit();
                                    }
                                }

                                dropdown.onchange = onCatChange;
                            })();
                            /* ]]> */
                        </script>
                    </form>
				<?php
                elseif ( 'style-2' == $rstyle ):

					if ( $instance['fsize'] ) {
						$term_args['smallest'] = $instance['fsize'];
						$term_args['largest'] = $instance['fsize'];
						$term_args['unit'] = 'px';
					}
					
					wp_tag_cloud( $term_args );
				else:
					$extra_class = $toggle ? ' toggle-enable' : '';
					echo '<ul class="pc-advanced-cat '.esc_attr( $extra_class ).'">';
					wp_list_categories( $term_args );
					echo '</ul>';
				endif; ?>
            </div>
			<?php

			$styles = [
				'color'    => [
					'color' => '#' . $this->id . '.penci_advanced_categories_widget span.category-item-count, #' . $this->id . '.penci_advanced_categories_widget a.tag-cloud-link,#' . $this->id . ' .pc-widget-advanced-tax.tax-style-1 ul li a'
				],
				'hcolor'   => [
					'color' => '#' . $this->id . '.penci_advanced_categories_widget a:hover span.category-item-count,#' . $this->id . '.penci_advanced_categories_widget a.tag-cloud-link:hover,#' . $this->id . ' .pc-widget-advanced-tax.tax-style-1 ul li a:hover'
				],
				'bgcolor'  => [
					'background-color' => '#' . $this->id . '.penci_advanced_categories_widget a.tag-cloud-link,#' . $this->id . ' .pc-widget-advanced-tax.tax-style-1 ul li a'
				],
				'bghcolor' => [
					'background-color' => '#' . $this->id . '.penci_advanced_categories_widget a.tag-cloud-link:hover,#' . $this->id . ' .pc-widget-advanced-tax.tax-style-1 ul li a:hover'
				],
				'bdcolor'  => [
					'border-color' => '#' . $this->id . '.penci_advanced_categories_widget ul li,#' . $this->id . '.penci_advanced_categories_widget ul ul, #' . $this->id . '.penci_advanced_categories_widget a.tag-cloud-link,#' . $this->id . ' .pc-widget-advanced-tax.tax-style-1 ul li a'
				],
				'bdhcolor' => [
					'border-color' => '#' . $this->id . '.penci_advanced_categories_widget a.tag-cloud-link:hover,#' . $this->id . ' .pc-widget-advanced-tax.tax-style-1 ul li a:hover'
				],
				'fsize'    => [
					'font-size' => '#' . $this->id . '.penci_advanced_categories_widget a.tag-cloud-link,#' . $this->id . ' .pc-widget-advanced-tax.tax-style-1 ul li a, #' . $this->id . ' .pc-widget-advanced-tax.tax-style-3 select'
				],
				'fsizec'   => [
					'font-size' => '#' . $this->id . '.penci_advanced_categories_widget a.tag-cloud-link .tag-link-count,#' . $this->id . ' .pc-widget-advanced-tax.tax-style-1 ul li .category-item-count'
				],
			];

			$out = '';

			foreach ( $styles as $option => $selectors ) {
				$value = isset( $instance[ $option ] ) ? $instance[ $option ] : '';
				if ( $value ) {
					foreach ( $selectors as $prop => $selector ) {
						$prefix = 'font-size' == $prop ? 'px' : '';
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
			$instance      = $old_instance;
			$data_instance = $this->soledad_widget_defaults();
			foreach ( $data_instance as $data => $value ) {
				$instance[ $data ] = ! empty( $new_instance[ $data ] ) ? $new_instance[ $data ] : '';
			}

			return $instance;
		}

		public function soledad_widget_defaults() {
			return array(
				'title'        => esc_html__( 'Categories', 'soledad' ),
				'style'        => '',
				'tax'          => 'category',
				'order'        => '',
				'orderby'      => '',
				'hide_empty'   => '',
				'hierarchical' => '',
				'count'        => '',
				'mark_count'   => '',
				'toggle'   	   => '',
				'maxitems'     => '',
				'exclude'      => '',
				'color'        => '',
				'hcolor'       => '',
				'bgcolor'      => '',
				'bghcolor'     => '',
				'bdcolor'      => '',
				'bdhcolor'     => '',
				'fsize'        => '',
				'fsizec'       => '',
			);
		}


		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults = $this->soledad_widget_defaults();
			$instance = wp_parse_args( (array) $instance, $defaults );

			$instance_title = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			$tax            = isset( $instance['tax'] ) ? $instance['tax'] : 'category';
			$style          = isset( $instance['style'] ) ? $instance['style'] : '';
			$order          = isset( $instance['order'] ) ? $instance['order'] : '';
			?>

            <!-- Widget Title: Text Input -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $instance_title; ?>"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>"
                        class="widefat categories" style="width:100%;">
					<?php
					$styles = [
						'style-1' => 'List',
						'style-2' => 'Boxed',
						'style-3' => 'Dropdown',
					];
					foreach ( $styles as $name => $label ) {
						?>
                        <option value='<?php echo esc_attr( $name ); ?>' <?php if ( $name == $style ) {
							echo 'selected="selected"';
						} ?>><?php echo $label; ?></option>
					<?php } ?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'tax' ) ); ?>"><?php esc_html_e( 'Select Taxnonomy:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'tax' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'tax' ) ); ?>"
                        class="widefat categories" style="width:100%;">
					<?php
					$post_types = get_post_types( [
						'public' => true,
					], 'objects' );
					foreach ( $post_types as $post_type => $type ) {
						foreach ( get_object_taxonomies( $type->name, 'object' ) as $tax_name => $tax_info ) {
							?>
                            <option value='<?php echo esc_attr( $tax_name ); ?>' <?php if ( $tax_name == $tax ) {
								echo 'selected="selected"';
							} ?>><?php echo sanitize_text_field( $type->label . ' - ' . $tax_info->label ); ?></option>
						<?php }
					} ?>
                </select>
            </p>

			<?php
			$order_by_lists = array( 'term_id', 'name', 'slug', 'count' )
			?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order by:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>"
                        class="widefat categories" style="width:100%;">
					<?php foreach ( $order_by_lists as $orderl ): ?>
                        <option value='<?php echo $orderl; ?>' <?php if ( $orderl == $order ) {
							echo 'selected="selected"';
						} ?>><?php echo $orderl; ?>
                        </option>
					<?php endforeach; ?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order:', 'soledad' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>"
                        class="widefat categories" style="width:100%;">
                    <option value='ASC' <?php if ( 'ASC' == $order ) {
						echo 'selected="selected"';
					} ?>>ASC
                    </option>
                    <option value='DESC' <?php if ( 'DESC' == $order ) {
						echo 'selected="selected"';
					} ?>>DESC
                    </option>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'toggle' ) ); ?>"><?php esc_html_e( 'Enable toggle to view child terms?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'toggle' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'toggle' ) ); ?>" <?php checked( (bool) $instance['toggle'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>"><?php esc_html_e( 'Show empty term?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'hide_empty' ) ); ?>" <?php checked( (bool) $instance['hide_empty'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>"><?php esc_html_e( 'Show in hierarchical?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'hierarchical' ) ); ?>" <?php checked( (bool) $instance['hierarchical'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Show posts count?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" <?php checked( (bool) $instance['count'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'mark_count' ) ); ?>"><?php esc_html_e( 'Highlight posts count?:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'mark_count' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'mark_count' ) ); ?>" <?php checked( (bool) $instance['mark_count'], true ); ?> />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'maxitems' ) ); ?>">Limit Number of Categories to
                    Show</label>
                <input type="number" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'maxitems' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'maxitems' ) ); ?>"
                       value="<?php echo $instance['maxitems']; ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'exclude' ) ); ?>"><?php esc_html_e( 'Exclude Term IDs:', 'soledad' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'exclude' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'exclude' ) ); ?>"
                       value="<?php echo $instance['exclude']; ?>"/>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'color' ); ?>"
                       style="display:block;"><?php _e( 'Color:' ); ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( 'color' ); ?>"
                       name="<?php echo $this->get_field_name( 'color' ); ?>" type="text"
                       value="<?php echo $instance['color']; ?>"/>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'hcolor' ); ?>"
                       style="display:block;"><?php _e( 'Hover Color:' ); ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( 'hcolor' ); ?>"
                       name="<?php echo $this->get_field_name( 'hcolor' ); ?>" type="text"
                       value="<?php echo $instance['hcolor']; ?>"/>
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
                <label for="<?php echo $this->get_field_id( 'bghcolor' ); ?>"
                       style="display:block;"><?php _e( 'Background Hover Color:' ); ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( 'bghcolor' ); ?>"
                       name="<?php echo $this->get_field_name( 'bghcolor' ); ?>" type="text"
                       value="<?php echo $instance['bghcolor']; ?>"/>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'bdcolor' ); ?>"
                       style="display:block;"><?php _e( 'Borders Color:' ); ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( 'bdcolor' ); ?>"
                       name="<?php echo $this->get_field_name( 'bdcolor' ); ?>" type="text"
                       value="<?php echo $instance['bdcolor']; ?>"/>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'bdhcolor' ); ?>"
                       style="display:block;"><?php _e( 'Borders Hover Color (for Boxed Style):' ); ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( 'bdhcolor' ); ?>"
                       name="<?php echo $this->get_field_name( 'bdhcolor' ); ?>" type="text"
                       value="<?php echo $instance['bdhcolor']; ?>"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'fsize' ) ); ?>">Font Size for Term Name</label>
                <input type="number" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'fsize' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'fsize' ) ); ?>"
                       value="<?php echo $instance['fsize']; ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'fsizec' ) ); ?>">Font Size for Counter</label>
                <input type="number" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'fsizec' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'fsizec' ) ); ?>"
                       value="<?php echo $instance['fsizec']; ?>">
            </p>

			<?php
		}
	}
}
?>
