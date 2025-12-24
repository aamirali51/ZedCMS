<?php

if ( ! class_exists( 'penci_categories_stylist' ) ) {

	/**
	 * Register the widget.
	 */
	add_action( 'widgets_init', 'penci_categories_stylist_widget_register' );
	function penci_categories_stylist_widget_register() {
		register_widget( 'penci_categories_stylist' );
	}

	/**
	 * Widget API: penci_categories_stylist class
	 */
	class penci_categories_stylist extends WP_Widget {


		public function __construct() {
			$widget_ops = array( 'classname' => 'penci-categories-stylist' );
			parent::__construct( 'penci-categories-stylist', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Categories Stylist', 'soledad' ), $widget_ops );
		}

		/**
		 * Outputs the content for the widget instance.
		 */
		public function widget( $args, $instance ) {

			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo( $args['before_widget'] );

			if ( ! empty( $instance['title'] ) ) {
				echo( $args['before_title'] . $instance['title'] . $args['after_title'] );
			}

			$style  = ! empty( $instance['style'] ) ? $instance['style'] : '';
			$layout = ! empty( $instance['layout'] ) ? $instance['layout'] : '';

			$show_bgs   = ! empty( $instance['show_bgs'] ) ? 'true' : false;
			$show_count = ! empty( $instance['show_count'] ) ? 'true' : false;
			$hide_empty = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : '';
			$maxitems   = isset( $instance['maxitems'] ) ? $instance['maxitems'] : '';

			$ratio        = isset( $instance['ratio'] ) ? $instance['ratio'] : '';
			$number_fsize = isset( $instance['number_fsize'] ) ? $instance['number_fsize'] : '';
			$title_fsize  = isset( $instance['title_fsize'] ) ? $instance['title_fsize'] : '';


			$categories_exclude = ! empty( $instance['categories_exclude'] ) ? explode( ',', $instance['categories_exclude'] ) : array();
			$categories_include = ! empty( $instance['categories_include'] ) ? explode( ',', $instance['categories_include'] ) : array();

			$this->penci_category_brand_block( array(
				'style'        => $style,
				'layout'       => $layout,
				'count'        => $show_count,
				'bgs'          => $show_bgs,
				'hide_empty'   => $hide_empty,
				'maxitems'     => $maxitems,
				'exclude'      => $categories_exclude,
				'include'      => $categories_include,
				'ratio'        => $ratio,
				'title_fsize'  => $title_fsize,
				'number_fsize' => $number_fsize,
			) );

			echo( $args['after_widget'] );
		}

		public function penci_category_brand_block( $args = array() ) {

			// Default Args
			$args = wp_parse_args( $args, array(
				'title'        => false,
				'style'        => false,
				'layout'       => false,
				'count'        => false,
				'bgs'          => false,
				'parent'       => false,
				'before'       => false,
				'after'        => false,
				'hide_empty'   => true,
				'maxitems'     => '',
				'exclude'      => '',
				'include'      => '',
				'ratio'        => '',
				'title_fsize'  => '',
				'number_fsize' => '',
			) );

			extract( $args );

			// Block Style
			$style = ! empty( $style ) ? 'categories-block-' . $style : 'categories-block-vertical';

			// Block Layout
			if ( ! empty( $layout ) ) {
				$columns = explode( '-', $layout );
				$layout  = 'categories-layout-wrap';
				$layout  .= ! empty( $columns[1] ) ? ' categories-wrap-' . $columns[1] : '';
			} else {
				$layout = 'categories-layout-scroll';
			}

			$get_args = array(
				'hide_empty' => $hide_empty,
				'parent'     => $parent,
				'number'     => $maxitems,
			);

			if ( $exclude ) {
				$get_args['exclude'] = is_array( $exclude ) ? $exclude : explode( ',', $exclude );
			}

			if ( $include ) {
				$get_args['include'] = is_array( $include ) ? $include : explode( ',', $include );
			}

			// Get the categories List
			$cats = get_categories( $get_args );

			$id = 'penci-categories-stylist-' . rand();


			if ( ! empty( $cats ) ) {

				// Get the categories custom Settings
				$penci_cats_options = get_option( 'penci_cats_options' );


				echo( $before ); ?>

                <div id="<?php echo esc_attr( $id ); ?>" class="categories-block <?php echo $style ?>">

                    <ul class="<?php echo $layout ?>">
						<?php

						foreach ( $cats as $cat ) {

							$cat_class    = array( 'cat-block-' . $cat->term_id );
							$penci_cat_bg = get_default_term_thumb_url( $cat->term_id, 'penci-masonry-thumb' );

							$brand_bg = false;
							if ( $penci_cat_bg && $bgs ) {
								$cat_class[] = 'has-bg';
							}
							?>

                            <li class="<?php echo join( ' ', $cat_class ) ?>">
								<?php if ( $penci_cat_bg && $bgs ) { ?>
                                    <div <?php echo penci_layout_bg( $penci_cat_bg ); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder">
	                                    <?php echo penci_layout_img( $penci_cat_bg ); ?>
                                    </div>
								<?php } ?>
                                <a href="<?php echo get_category_link( $cat->term_id ) ?>">
                                    <div class="category-title"><?php echo esc_html( $cat->name ) ?></div>
									<?php if ( $count ) { ?>
                                        <span class="category-count"><?php echo esc_html( $cat->count ) ?></span>
									<?php } ?>
                                </a>
                            </li>

							<?php
						}
						?>
                    </ul>
                </div>
				<?php

				echo '<style>';

				if ( $ratio ) {
					echo '#' . $id . ' li{padding-top:' . esc_attr( $ratio ) . 'px;padding-bottom:' . esc_attr( $ratio ) . 'px}';
				}

				if ( $title_fsize ) {
					echo '#' . $id . ' .category-title{font-size:' . esc_attr( $title_fsize ) . ';}';
				}

				if ( $number_fsize ) {
					echo '#' . $id . ' .category-count{font-size:' . esc_attr( $number_fsize ) . ';}';
				}

				echo '</style>';

				echo( $after );
			}
		}

		/**
		 * Handles updating settings for widget instance.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance          = $old_instance;
			$instance['title'] = sanitize_text_field( $new_instance['title'] );

			$instance['style']  = $new_instance['style'];
			$instance['layout'] = $new_instance['layout'];

			$instance['show_bgs']   = ! empty( $new_instance['show_bgs'] ) ? 'true' : false;
			$instance['show_icon']  = ! empty( $new_instance['show_icon'] ) ? 'true' : false;
			$instance['show_count'] = ! empty( $new_instance['show_count'] ) ? 'true' : false;

			$instance['categories_exclude'] = $new_instance['categories_exclude'];
			$instance['categories_include'] = $new_instance['categories_include'];
			$instance['hide_empty']         = $new_instance['hide_empty'];
			$instance['maxitems']           = $new_instance['maxitems'];
			$instance['ratio']              = $new_instance['ratio'];
			$instance['title_fsize']        = $new_instance['title_fsize'];
			$instance['number_fsize']       = $new_instance['number_fsize'];

			return $instance;
		}

		/**
		 * Outputs the settings form for the widget.
		 */
		public function form( $instance ) {
			$defaults = array( 'title' => esc_html__( 'Categories', 'soledad' ) );
			$instance = wp_parse_args( (array) $instance, $defaults );

			$title  = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$style  = ! empty( $instance['style'] ) ? $instance['style'] : '';
			$layout = ! empty( $instance['layout'] ) ? $instance['layout'] : '';

			$show_bgs   = ! empty( $instance['show_bgs'] ) ? 'true' : false;
			$show_count = ! empty( $instance['show_count'] ) ? 'true' : false;

			$categories_exclude = ! empty( $instance['categories_exclude'] ) ? $instance['categories_exclude'] : '';
			$categories_include = ! empty( $instance['categories_include'] ) ? $instance['categories_include'] : '';

			// Layout Options
			$layouts_list = array(
				''           => esc_html__( 'Horizontal', 'soledad' ),
				'vertical-1' => esc_html__( 'Vertical', 'soledad' ),
			);

			$style_list = array(
				'horizontal' => esc_html__( 'Style 1', 'soledad' ),
				'vertical'   => esc_html__( 'Style 2', 'soledad' ),
			);


			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo esc_attr( $title ); ?>" class="widefat" type="text"/>
            </p>


            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style:', 'soledad' ) ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" class="widefat">
					<?php
					foreach ( $style_list as $style_id => $style_text ) { ?>
                        <option value="<?php echo esc_attr( $style_id ) ?>" <?php selected( $style, $style_id ); ?>><?php echo esc_attr( $style_text ) ?></option>
						<?php
					}
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e( 'Layout:', 'soledad' ) ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>" class="widefat">
					<?php
					foreach ( $layouts_list as $layout_id => $layout_text ) { ?>
                        <option value="<?php echo esc_attr( $layout_id ) ?>" <?php selected( $layout, $layout_id ); ?>><?php echo esc_attr( $layout_text ) ?></option>
						<?php
					}
					?>
                </select>
            </p>

            <p>
                <input id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>"
                       value="true" <?php checked( $show_count, 'true' ); ?> type="checkbox"/>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php esc_html_e( 'Show Number of Posts', 'soledad' ) ?></label>
            </p>

            <p>
                <input id="<?php echo esc_attr( $this->get_field_id( 'show_bgs' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'show_bgs' ) ); ?>"
                       value="true" <?php checked( $show_bgs, 'true' ); ?> type="checkbox"/>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_bgs' ) ); ?>"><?php esc_html_e( 'Show Backgrounds', 'soledad' ) ?></label>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'ratio' ) ); ?>">Custom Image Padding Top &
                    Bottom</label>
                <input type="number" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'ratio' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'ratio' ) ); ?>"
                       value="<?php echo isset( $instance['ratio'] ) ? $instance['ratio'] : ''; ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title_fsize' ) ); ?>">Custom Font Size for
                    Category Titles</label>
                <input type="text" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'title_fsize' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title_fsize' ) ); ?>"
                       value="<?php echo isset( $instance['title_fsize'] ) ? $instance['title_fsize'] : ''; ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number_fsize' ) ); ?>">Custom Font Size for
                    Category Counts</label>
                <input type="text" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'number_fsize' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'number_fsize' ) ); ?>"
                       value="<?php echo isset( $instance['number_fsize'] ) ? $instance['number_fsize'] : ''; ?>">
            </p>

            <p>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'hide_empty' ) ); ?>" <?php isset( $instance['hide_empty'] ) ? checked( (bool) $instance['hide_empty'], true ) : ''; ?> />
                <label for="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>"><?php esc_html_e( 'Show empty term?', 'soledad' ); ?></label>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'maxitems' ) ); ?>">Limit Number of Categories to
                    Show</label>
                <input type="number" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'maxitems' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'maxitems' ) ); ?>"
                       value="<?php echo isset( $instance['maxitems'] ) ? $instance['maxitems'] : ''; ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'categories_include' ) ); ?>"><?php esc_html_e( 'Include Categories', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'categories_include' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'categories_include' ) ); ?>"
                       value="<?php echo esc_attr( $categories_include ); ?>" class="widefat" type="text"/>
                <br/>
				<?php esc_html_e( 'Enter a category ID, or IDs separated by comma.', 'soledad' ); ?>
            </p>


            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'categories_exclude' ) ); ?>"><?php esc_html_e( 'Exclude Categories', 'soledad' ) ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'categories_exclude' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'categories_exclude' ) ); ?>"
                       value="<?php echo esc_attr( $categories_exclude ); ?>" class="widefat" type="text"/>
                <br/>
				<?php esc_html_e( 'Enter a category ID, or IDs separated by comma.', 'soledad' ); ?>
            </p>


			<?php
		}
	}

}
