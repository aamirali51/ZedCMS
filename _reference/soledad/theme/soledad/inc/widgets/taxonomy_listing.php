<?php

add_action( 'widgets_init', 'penci_taxonomy_listing_widget' );

function penci_taxonomy_listing_widget() {
	register_widget( 'penci_taxonomy_listing_widget' );
}

if ( ! class_exists( 'penci_taxonomy_listing_widget' ) ) {
	class penci_taxonomy_listing_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array(
				'classname'   => 'penci_taxonomy_listing_widget',
				'description' => esc_html__( 'A widget that displays the listing of the taxonomy terms.', 'soledad' )
			);

			/* Widget control settings. */
			$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'penci_taxonomy_listing_widget' );

			parent::__construct( 'penci_taxonomy_listing_widget', penci_get_theme_name( '.Soledad', true ) . esc_html__( 'Taxonomy Listing', 'soledad' ), $widget_ops, $control_ops );

		}

		/**
		 * How to display the widget on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );

			/* Our variables from the widget settings. */
			$title = isset( $instance['title'] ) && $instance['title'] ? $instance['title'] : __( 'Taxonomy Listing', 'soledad' );
			$title = apply_filters( 'widget_title', $title );

			/* Before widget (defined by themes). */
			echo ent2ncr( $before_widget );

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
				echo ent2ncr( $before_title ) . $title . ent2ncr( $after_title );
			}

			$settings         = $instance;
			$biggid_style     = isset( $settings['style'] ) ? $settings['style'] : 'style-1';
			$overlay_type     = isset( $settings['overlay_type'] ) ? $settings['overlay_type'] : 'whole';
			$bgcontent_pos    = isset( $settings['bgcontent_pos'] ) ? $settings['bgcontent_pos'] : 'on';
			$content_display  = isset( $settings['content_display'] ) ? $settings['content_display'] : 'block';
			$disable_lazy     = get_theme_mod( 'penci_disable_lazyload_layout' );
			$image_hover      = isset( $settings['image_hover'] ) ? $settings['image_hover'] : 'zoom-in';
			$text_overlay     = isset( $settings['text_overlay'] ) ? $settings['text_overlay'] : 'none';
			$text_overlay_ani = isset( $settings['text_overlay_ani'] ) ? $settings['text_overlay_ani'] : 'movetop';
			$onecol_mobile    = isset( $settings['onecol_mobile'] ) ? $settings['onecol_mobile'] : '';
			$sameh_mobile     = isset( $settings['sameh_mobile'] ) ? $settings['sameh_mobile'] : '';
			$thumb_size       = isset( $settings['thumb_size'] ) ? $settings['thumb_size'] : 'penci-masonry-thumb';
			$bthumb_size      = isset( $settings['bthumb_size'] ) ? $settings['bthumb_size'] : 'penci-full-thumb';
			$mthumb_size      = isset( $settings['mthumb_size'] ) ? $settings['mthumb_size'] : 'penci-masonry-thumb';
			$title_length     = isset( $settings['title_length'] ) ? $settings['title_length'] : 10;
			$excerpt_length   = isset( $settings['excerpt_length'] ) ? $settings['excerpt_length'] : 10;
			$text_overlay     = isset( $settings['text_overlay'] ) ? $settings['text_overlay'] : 'none';
			$text_overlay_ani = isset( $settings['text_overlay_ani'] ) ? $settings['text_overlay_ani'] : 'movetop';
			$image_hover      = isset( $settings['image_hover'] ) ? $settings['image_hover'] : 'zoom-in';

			$wrapper_class   = $data_class = '';
			$flag_style      = false;
			$clear_fix_class = 'penci-clearfix ';
			if ( $biggid_style == 'style-1' ) {
				$data_class .= ' penci-dflex';
			} else {
				$data_class .= ' penci-dblock';
			}

			if ( ! in_array( $biggid_style, array( 'style-1', 'style-2' ) ) ) {
				$flag_style    = true;
				$data_class    .= ' penci-fixh';
				$bgcontent_pos = 'on';
			}

			$block_id = $this->id;

			$wrapper_class .= ' ' . $block_id;

			if ( 'style-1' == $biggid_style || 'style-2' == $biggid_style ) {
				$bg_columns        = isset( $settings['bg_columns'] ) ? $settings['bg_columns'] : '3';
				$bg_columns_tablet = isset( $settings['bg_columns_tablet'] ) ? $settings['bg_columns_tablet'] : '';
				$bg_columns_mobile = isset( $settings['bg_columns_mobile'] ) ? $settings['bg_columns_mobile'] : '1';
				$wrapper_class     .= ' penci-grid-col-' . $bg_columns;
				if ( $bg_columns_tablet ) {
					$wrapper_class .= ' penci-grid-tcol-' . $bg_columns_tablet;
				}
				$wrapper_class .= ' penci-grid-mcol-' . $bg_columns_mobile;
			}

			$wrapper_class .= ' penci-bgrid-based-custom penci-bgrid-custom penci-bgrid-content-' . $bgcontent_pos . ' pencibg-imageh-' . $image_hover . ' pencibg-texth-' . $text_overlay . ' pencibg-textani-' . $text_overlay_ani;
			if ( $flag_style && $onecol_mobile ) {
				$wrapper_class .= ' penci-bgrid-monecol';
			}
			if ( $flag_style && $sameh_mobile ) {
				$wrapper_class .= ' penci-bgrid-msameh';
			}

			if ( isset( $settings['title_anivisi'] ) && $settings['title_anivisi'] ) {
				$wrapper_class .= ' pcbg-titles-visible';
			}

			if ( isset( $settings['apply_spe_bg_title'] ) && $settings['apply_spe_bg_title'] ) {
				$wrapper_class .= ' pcbg-mask-title';
			}

			if ( isset( $settings['apply_spe_bg_meta'] ) && $settings['apply_spe_bg_meta'] ) {
				$wrapper_class .= ' pcbg-mask-meta';
			}

			if ( in_array( $text_overlay_ani, array( 'movetop', 'movebottom', 'moveleft', 'moveright' ) ) ) {
				$wrapper_class .= ' textop';
			} else {
				$wrapper_class .= ' notextop';
			}

			if ( isset( $settings['hide_subtitle_mobile'] ) && $settings['hide_subtitle_mobile'] ) {
				$wrapper_class .= ' hide-msubtitle';
			}
			if ( isset( $settings['hide_readmore_mobile'] ) && $settings['hide_readmore_mobile'] ) {
				$wrapper_class .= ' hide-mreadmorebt';
			}
			if ( isset( $settings['ver_border'] ) && $settings['ver_border'] && 'style-1' == $biggid_style ) {
				$wrapper_class .= ' pcbg-verbd';
			}

			if ( empty( $settings['taxonomies'] ) ) {
				if ( current_user_can( 'manage_options' ) ) {
					echo '<p>' . __( 'Please select the taxonomy term in the widget settings.', 'soledad' ) . '</p>';
				}

				return;
			}


			$ars_terms = array(
				'taxonomy'     => isset( $settings['taxonomies'] ) ? $settings['taxonomies'] : '',
				'hide_empty'   => isset( $settings['hide_empty'] ) && $settings['hide_empty'] ? true : false,
				'orderby'      => isset( $settings['orderby'] ) && $settings['orderby'] ? $settings['orderby'] : 'name',
				'order'        => isset( $settings['order'] ) && $settings['order'] ? $settings['order'] : 'ASC',
				'hierarchical' => isset( $settings['hierarchical'] ) && $settings['hierarchical'] ? true : false,
				'number'       => 6,
			);

			$settings['term_name'] = $settings['taxonomies'];

			if ( isset ( $settings['taxonomies_ex'] ) && $settings['taxonomies_ex'] ) {
				$ars_terms['exclude'] = explode( ',', $settings['taxonomies_ex'] );
			}

			if ( isset ( $settings['number'] ) && $settings['number'] ) {
				$ars_terms['number'] = $settings['number'];
			}
			$big_items = penci_big_grid_is_big_items( $biggid_style );
			?>
            <div class="penci-clearfix penci-biggrid-terms-wrapper penci-biggrid-wrapper<?php echo $wrapper_class; ?>">

                <div class="penci-clearfix penci-biggrid penci-bg<?php echo $biggid_style; ?> penci-bgel">
                    <div class="penci-biggrid-inner default">
						<?php
						$bg = 1;

						$biggrid_items      = get_terms( $ars_terms );
						$num_posts          = count( $biggrid_items );
						$post_meta          = isset( $settings['bg_postmeta'] ) && $settings['bg_postmeta'] ? $settings['bg_postmeta'] : [];
						$hide_meta_small    = isset( $settings['hide_meta_small'] ) ? $settings['hide_meta_small'] : '';
						$hide_excerpt_small = isset( $settings['hide_excerpt_small'] ) ? $settings['hide_excerpt_small'] : '';
						$show_readmore      = isset( $settings['show_readmore'] ) ? $settings['show_readmore'] : '';
						if ( ! empty( $biggrid_items ) ) {
							echo '<div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
							foreach ( $biggrid_items as $setting ) {
								$is_big_item         = '';
								$hide_cat_small_flag = $hide_meta_small_flag = $hide_rm_small_flag = $hide_excerpt_small_flag = false;
								$surplus             = penci_big_grid_count_classes( $bg, $biggid_style, true );
								$thumbnail           = $thumb_size;
								$post_count          = $setting->count;
								if ( ! empty( $big_items ) && in_array( $surplus, $big_items ) ) {
									$thumbnail   = $bthumb_size;
									$is_big_item = ' pcbg-big-item';
								}
								if ( penci_is_mobile() ) {
									$thumbnail = $mthumb_size;
								}

								if ( ! $is_big_item ) {
									if ( 'yes' == $hide_meta_small ) {
										$hide_meta_small_flag = true;
									}
									if ( 'yes' == $hide_excerpt_small ) {
										$hide_excerpt_small_flag = true;
									}
								}

								$image_url = get_default_term_thumb_url( $setting->term_id, $thumbnail );

								/* Get Custom Items Data */
								$item_id     = ' elementor-repeater-item-' . $setting->term_id;
								$image_ratio = penci_get_ratio_size_based_url( $image_url );

								$title      = $setting->name;
								$title_link = get_term_link( $setting->term_id );
								$title_attr = '';

								$desc = $setting->description;

								include dirname( __FILE__ ) . "/category.php";

								if ( $flag_style && $surplus == 0 && $bg < $num_posts ) {
									echo '</div><div class="penci-clearfix penci-biggrid-data' . $data_class . '">';
								}

								$bg ++;
							}
							echo '</div>';


						}
						?>
                    </div>
                </div>
            </div>
			<?php

			/* After widget (defined by themes). */
			echo ent2ncr( $after_widget );

			$css = '';

			$css_rules = [
				'bg_gap'              => array(
					'{{WRAPPER}} .penci-bgstyle-1 .penci-dflex'                                              => 'margin-left: calc(-{{SIZE}}px/2); margin-right: calc(-{{SIZE}}px/2); width: calc(100% + {{SIZE}}px);',
					'{{WRAPPER}} .penci-bgstyle-2 .item-masonry, {{WRAPPER}} .penci-bgstyle-1 .penci-bgitem' => 'padding-left: calc({{SIZE}}px/2); padding-right: calc({{SIZE}}px/2); margin-bottom: {{SIZE}}px',
					'{{WRAPPER}} .penci-bgstyle-2 .penci-biggrid-data'                                       => 'margin-left: calc(-{{SIZE}}px/2); margin-right: calc(-{{SIZE}}px/2);',
				),
				'bg_othergap'         => array(
					'{{WRAPPER}} .penci-biggrid' => '--pcgap: {{SIZE}}px;',
				),
				'penci_img_ratio'     => array(
					'{{WRAPPER}} .penci-bgitem .penci-image-holder:before' => 'padding-top: {{SIZE}}%;',
				),
				'imgradius'           => array(
					'{{WRAPPER}} .pcbg-thumb, {{WRAPPER}} .pcbg-bgoverlay, {{WRAPPER}} .penci-image-holder' => 'border-radius: {{SIZE}}px; -webkit-border-radius: {{SIZE}}px;',
				),
				'bg_height'           => array(
					'{{WRAPPER}} .penci-biggrid .penci-fixh' => '--bgh: {{SIZE}}px;',
				),
				'title_fsize'         => array( '{{WRAPPER}} .pcbg-content-inner .pcbg-title a,{{WRAPPER}} .pcbg-content-inner .pcbg-title' => 'font-size: {{SIZE}}px;' ),
				'bgtitle_color'       => array( '{{WRAPPER}} .pcbg-content-inner .pcbg-title a,{{WRAPPER}} .pcbg-content-inner .pcbg-title' => 'color: {{VALUE}};' ),
				'bgtitle_color_hover' => array( '{{WRAPPER}} .penci-bgmain:hover .pcbg-content-inner .pcbg-title a' => 'color: {{VALUE}};' ),
				'meta_fsize'          => array(
					'{{WRAPPER}} .pcbg-content-inner .pcbg-meta, {{WRAPPER}} .pcbg-content-inner .pcbg-meta span' => 'font-size: {{SIZE}}px;',
				),
				'bgdesc_color'        => array(
					'{{WRAPPER}} .pcbg-content-inner .pcbg-meta'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .pcbg-content-inner .pcbg-meta span' => 'color: {{VALUE}};',
				),
				'bgdesc_link_color'   => array(
					'{{WRAPPER}} .pcbg-content-inner .pcbg-meta a'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .pcbg-content-inner .pcbg-meta span a' => 'color: {{VALUE}};',
				),
				'bgdesc_link_hcolor'  => array(
					'{{WRAPPER}} .pcbg-content-inner .pcbg-meta a:hover'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .pcbg-content-inner .pcbg-meta span a:hover' => 'color: {{VALUE}};',
				),
				'desc_fsize'          => array(
					'{{WRAPPER}} .pcbg-pexcerpt, {{WRAPPER}} .pcbg-pexcerpt a, {{WRAPPER}} .pcbg-pexcerpt p' => 'font-size: {{SIZE}}px;',
				),
				'excerpt_tcolor'      => array(
					'{{WRAPPER}} .pcbg-pexcerpt, {{WRAPPER}} .pcbg-pexcerpt a, {{WRAPPER}} .pcbg-pexcerpt p' => 'color: {{VALUE}};',
				)
			];

			foreach ( $css_rules as $op => $selectors ) {

				if ( isset( $instance[ $op ] ) && $instance[ $op ] ) {
					$value = $instance[ $op ];
					foreach ( $selectors as $selector => $prop ) {
						$selector = str_replace( '{{WRAPPER}}', '.' . $this->id, $selector );
						$prop     = str_replace( '{{SIZE}}', $value, $prop );
						$prop     = str_replace( '{{VALUE}}', $value, $prop );
						$css      .= $selector . '{ ' . $prop . ' }';
					}
				}

			}

			if ( $css ) {
				echo '<style id="' . $this->id . '-css">' . $css . '</style>';
			}
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
				'title'                       => esc_html__( 'Taxonomy Listing', 'soledad' ),
				'taxonomies'                  => '',
				'taxonomies_ex'               => '',
				'orderby'                     => 'name',
				'order'                       => 'ASC',
				'number'                      => 6,
				'hide_empty'                  => '',
				'hierarchical'                => '',
				'style'                       => '',
				'bg_columns'                  => '',
				'bg_columns_tablet'           => '',
				'bg_columns_mobile'           => '',
				'bg_postmeta'                 => [],
				'hide_meta_small'             => '',
				'hide_excerpt_small'          => '',
				'hide_subtitle_mobile'        => '',
				'show_readmore'               => '',
				'hide_readmore_mobile'        => '',
				'onecol_mobile'               => '',
				'sameh_mobile'                => '',
				'title_length'                => '',
				'bgcontent_pos'               => 'on',
				'bg_gap'                      => '',
				'bg_othergap'                 => '',
				'penci_img_ratio'             => '',
				'imgradius'                   => '',
				'bg_height'                   => '',
				'content_horizontal_position' => '',
				'content_vertical_position'   => '',
				'content_text_align'          => '',
				'bgtitle_color'               => '',
				'bgtitle_color_hover'         => '',
				'bgdesc_color'                => '',
				'bgdesc_link_color'           => '',
				'bgdesc_link_hcolor'          => '',
				'excerpt_tcolor'              => '',
				'text_overlay'                => 'none',
				'text_overlay_ani'            => 'movetop',
				'image_hover'                 => 'zoom-in',
				'title_fsize'                 => '',
				'meta_fsize'                  => '',
				'desc_fsize'                  => '',
			);
		}

		function prs_select_field( $options, $current ) {
			foreach ( $options as $name => $label ) {
				echo '<option ' . selected( $name, $current ) . ' value="' . esc_attr( $name ) . '">' . esc_html( $label ) . '</option>';
			}
		}

		function color_field( $id, $label, $instance ) {
			?>
            <p>
                <label for="<?php echo $this->get_field_id( $id ); ?>"
                       style="display:block;"><?php echo $label; ?></label>
                <input class="widefat pcwoo-color-picker color-picker"
                       id="<?php echo $this->get_field_id( $id ); ?>"
                       name="<?php echo $this->get_field_name( $id ); ?>" type="text"
                       value="<?php echo $instance[ $id ]; ?>"/>
            </p>
			<?php
		}

		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults                    = $this->soledad_widget_defaults();
			$instance                    = wp_parse_args( (array) $instance, $defaults );
			$title                       = $instance['title'] ? str_replace( '"', '&quot;', $instance['title'] ) : '';
			$taxonomies                  = isset( $instance['taxonomies'] ) ? $instance['taxonomies'] : '';
			$orderby                     = isset( $instance['orderby'] ) ? $instance['orderby'] : '';
			$order                       = isset( $instance['order'] ) ? $instance['order'] : '';
			$style                       = isset( $instance['style'] ) ? $instance['style'] : '';
			$bg_columns                  = isset( $instance['bg_columns'] ) ? $instance['bg_columns'] : '';
			$bg_columns_tablet           = isset( $instance['bg_columns_tablet'] ) ? $instance['bg_columns_tablet'] : '';
			$bg_columns_mobile           = isset( $instance['bg_columns_mobile'] ) ? $instance['bg_columns_mobile'] : '';
			$bg_postmeta                 = isset( $instance['bg_postmeta'] ) ? (array) $instance['bg_postmeta'] : [];
			$bgcontent_pos               = isset( $instance['bgcontent_pos'] ) ? $instance['bgcontent_pos'] : 'on';
			$content_horizontal_position = isset( $instance['content_horizontal_position'] ) ? $instance['content_horizontal_position'] : '';
			$content_vertical_position   = isset( $instance['content_vertical_position'] ) ? $instance['content_vertical_position'] : '';
			$content_text_align          = isset( $instance['content_text_align'] ) ? $instance['content_text_align'] : '';
			$text_overlay                = isset( $instance['text_overlay'] ) ? $instance['text_overlay'] : 'none';
			$text_overlay_ani            = isset( $instance['text_overlay_ani'] ) ? $instance['text_overlay_ani'] : 'movetop';
			$image_hover                 = isset( $instance['image_hover'] ) ? $instance['image_hover'] : 'zoom-in';

			$post_taxonomies = get_object_taxonomies( 'post' );
			$post_tax        = [];
			$post_tax['']    = 'Select';
			foreach ( $post_taxonomies as $tname ) {
				$labels             = get_taxonomy( $tname );
				$post_tax[ $tname ] = $labels->label;
			}

			foreach ( penci_get_published_posttypes() as $type ) {

				$type_data = get_object_taxonomies( $type );
				if ( is_array( $type_data ) ) {
					foreach ( $type_data as $type_name ) {
						$labels                 = get_taxonomy( $type_name );
						$post_tax[ $type_name ] = $labels->label;
					}
				}
			}

			$post_order_by = [
				'name'       => 'Name',
				'slug'       => 'Slug',
				'term_id'    => 'ID',
				'term_order' => 'Term Order',
				'count'      => 'Posts Count',
			];

			$post_order = [
				'ASC'  => 'ASC',
				'DESC' => 'DESC',
			];

			$post_style = array(
				'style-1'  => esc_html__( 'Grid ( Default )', 'soledad' ),
				'style-2'  => esc_html__( 'Masonry', 'soledad' ),
				'style-3'  => esc_html__( 'Style 3', 'soledad' ),
				'style-4'  => esc_html__( 'Style 4', 'soledad' ),
				'style-5'  => esc_html__( 'Style 5', 'soledad' ),
				'style-6'  => esc_html__( 'Style 6', 'soledad' ),
				'style-7'  => esc_html__( 'Style 7', 'soledad' ),
				'style-8'  => esc_html__( 'Style 8', 'soledad' ),
				'style-9'  => esc_html__( 'Style 9', 'soledad' ),
				'style-10' => esc_html__( 'Style 10', 'soledad' ),
				'style-11' => esc_html__( 'Style 11', 'soledad' ),
				'style-12' => esc_html__( 'Style 12', 'soledad' ),
				'style-13' => esc_html__( 'Style 13', 'soledad' ),
				'style-14' => esc_html__( 'Style 14', 'soledad' ),
				'style-15' => esc_html__( 'Style 15', 'soledad' ),
				'style-16' => esc_html__( 'Style 16', 'soledad' ),
				'style-17' => esc_html__( 'Style 17', 'soledad' ),
				'style-18' => esc_html__( 'Style 18', 'soledad' ),
				'style-19' => esc_html__( 'Style 19', 'soledad' ),
				'style-20' => esc_html__( 'Style 20', 'soledad' ),
				'style-21' => esc_html__( 'Style 21', 'soledad' ),
				'style-22' => esc_html__( 'Style 22', 'soledad' ),
			);
			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'soledad' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo $title; ?>"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'taxonomies' ) ); ?>"><?php esc_html_e( 'Taxonomies', 'soledad' ) ?></label>
                <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'taxonomies' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'taxonomies' ) ); ?>">
					<?php foreach ( $post_tax as $name => $label ) {
						echo '<option ' . selected( $name, $taxonomies ) . ' value="' . esc_attr( $name ) . '">' . esc_html( $label ) . '</option>';
					} ?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'taxonomies_ex' ) ); ?>"><?php esc_html_e( 'Excluded Taxonomies Terms IDs:', 'soledad' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'taxonomies_ex' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'taxonomies_ex' ) ); ?>"
                       value="<?php echo sanitize_text_field( $instance['taxonomies_ex'] ); ?>"/>
            <p><?php _e( 'Enter the list of term IDs you need to exclude, separated by commas.', 'soledad' ); ?></p>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By', 'soledad' ) ?></label>
                <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
					<?php foreach ( $post_order_by as $name => $label ) {
						echo '<option ' . selected( $name, $orderby ) . ' value="' . esc_attr( $name ) . '">' . esc_html( $label ) . '</option>';
					} ?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order', 'soledad' ) ?></label>
                <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
					<?php $this->prs_select_field( $post_order, $order ); ?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Limit Terms to Show:', 'soledad' ); ?></label>
                <input type="number" style="width: 65px; display: inline-block;"
                       id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>"
                       value="<?php echo esc_attr( $instance['number'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>"><?php esc_html_e( 'Hide Empty Items:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'hide_empty' ) ); ?>" <?php checked( (bool) $instance['hide_empty'], true ); ?> /><br/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>"><?php esc_html_e( 'Hierarchical:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'hierarchical' ) ); ?>" <?php checked( (bool) $instance['hierarchical'], true ); ?> /><br/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Display Style', 'soledad' ) ?></label>
                <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
					<?php $this->prs_select_field( $post_style, $style ); ?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'bg_columns' ) ); ?>"><?php esc_html_e( 'Grid/Masonry Style Columns', 'soledad' ) ?></label>
                <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'bg_columns' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'bg_columns' ) ); ?>">
					<?php
					$post_col = array(
						'1' => esc_html__( '1 Column', 'soledad' ),
						'2' => esc_html__( '2 Columns', 'soledad' ),
						'3' => esc_html__( '3 Columns', 'soledad' ),
						'4' => esc_html__( '4 Columns', 'soledad' ),
						'5' => esc_html__( '5 Columns', 'soledad' ),
						'6' => esc_html__( '6 Columns', 'soledad' )
					);
					$this->prs_select_field( $post_col, $bg_columns );
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'bg_columns_tablet' ) ); ?>"><?php esc_html_e( 'Grid/Masonry Style Columns on Tablet', 'soledad' ) ?></label>
                <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'bg_columns_tablet' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'bg_columns_tablet' ) ); ?>">
					<?php
					$post_col_m = array(
						''  => esc_html__( 'Default', 'soledad' ),
						'1' => esc_html__( '1 Column', 'soledad' ),
						'2' => esc_html__( '2 Columns', 'soledad' ),
						'3' => esc_html__( '3 Columns', 'soledad' ),
						'4' => esc_html__( '4 Columns', 'soledad' ),
					);
					$this->prs_select_field( $post_col_m, $bg_columns_tablet );
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'bg_columns_mobile' ) ); ?>"><?php esc_html_e( 'Grid/Masonry Style Columns on Mobile', 'soledad' ) ?></label>
                <select style="width:100%;" id="<?php echo esc_attr( $this->get_field_id( 'bg_columns_mobile' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'bg_columns_mobile' ) ); ?>">
					<?php
					$post_col_mb = array(
						'1' => esc_html__( '1 Column', 'soledad' ),
						'2' => esc_html__( '2 Columns', 'soledad' ),
						'3' => esc_html__( '3 Columns', 'soledad' ),
					);
					$this->prs_select_field( $post_col_mb, $bg_columns_mobile );
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'bg_postmeta' ) ); ?>"><?php esc_html_e( 'Showing Term Data', 'soledad' ) ?></label>
                <select multiple style="width:100%;"
                        id="<?php echo esc_attr( $this->get_field_id( 'bg_postmeta' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'bg_postmeta' ) ); ?>[]">
					<?php
					$post_meta = array(
						'name'  => esc_html__( 'Name', 'soledad' ),
						'desc'  => esc_html__( 'Description', 'soledad' ),
						'count' => esc_html__( 'Posts Count', 'soledad' ),
					);
					foreach ( $post_meta as $name => $label ) {
						$selected = in_array( $name, $bg_postmeta ) ? 'selected' : '';
						echo '<option ' . $selected . ' value="' . esc_attr( $name ) . '">' . esc_html( $label ) . '</option>';
					} ?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'hide_meta_small' ) ); ?>"><?php esc_html_e( 'Hide Term Meta on Small Grid Items:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_meta_small' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'hide_meta_small' ) ); ?>" <?php checked( (bool) $instance['hide_meta_small'], true ); ?> /><br/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'hide_excerpt_small' ) ); ?>"><?php esc_html_e( 'Hide Only Post Excerpt on Small Grid Items:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_excerpt_small' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'hide_excerpt_small' ) ); ?>" <?php checked( (bool) $instance['hide_excerpt_small'], true ); ?> /><br/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'hide_subtitle_mobile' ) ); ?>"><?php esc_html_e( 'Hide Terms Description on Mobiles:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_subtitle_mobile' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'hide_subtitle_mobile' ) ); ?>" <?php checked( (bool) $instance['hide_subtitle_mobile'], true ); ?> /><br/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_readmore' ) ); ?>"><?php esc_html_e( 'Show View All Posts Button:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_readmore' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'show_readmore' ) ); ?>" <?php checked( (bool) $instance['show_readmore'], true ); ?> /><br/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'hide_readmore_mobile' ) ); ?>"><?php esc_html_e( 'Hide View All Posts on Mobile:', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_readmore_mobile' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'hide_readmore_mobile' ) ); ?>" <?php checked( (bool) $instance['hide_readmore_mobile'], true ); ?> /><br/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'onecol_mobile' ) ); ?>"><?php esc_html_e( 'Display One Column on Mobile?', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'onecol_mobile' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'onecol_mobile' ) ); ?>" <?php checked( (bool) $instance['onecol_mobile'], true ); ?> /><br/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'sameh_mobile' ) ); ?>"><?php esc_html_e( 'Display Grid Items Same Height on Mobile?', 'soledad' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'sameh_mobile' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'sameh_mobile' ) ); ?>" <?php checked( (bool) $instance['sameh_mobile'], true ); ?> /><br/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title_length' ) ); ?>"><?php esc_html_e( 'Custom Words Length for Term Name:', 'soledad' ); ?></label>
                <input type="number" style="width: 65px; display: inline-block;"
                       id="<?php echo esc_attr( $this->get_field_id( 'title_length' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title_length' ) ); ?>"
                       value="<?php echo esc_attr( $instance['title_length'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'bgcontent_pos' ) ); ?>"><?php esc_html_e( 'Content Position', 'soledad' ) ?></label>
                <select style="width:100%;"
                        id="<?php echo esc_attr( $this->get_field_id( 'bgcontent_pos' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'bgcontent_pos' ) ); ?>">
					<?php
					$bgcontent_pos_option = array(
						'on'    => esc_html__( 'On Image', 'soledad' ),
						'below' => esc_html__( 'Below Image', 'soledad' ),
						'above' => esc_html__( 'Above Image', 'soledad' ),
					);
					$this->prs_select_field( $bgcontent_pos_option, $bgcontent_pos );
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'bg_gap' ) ); ?>"><?php esc_html_e( 'Gap Between Grid & Mansonry Items:', 'soledad' ); ?></label>
                <input type="number" style="width: 65px; display: inline-block;"
                       id="<?php echo esc_attr( $this->get_field_id( 'bg_gap' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'bg_gap' ) ); ?>"
                       value="<?php echo esc_attr( $instance['bg_gap'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'bg_othergap' ) ); ?>"><?php esc_html_e( 'Gap Between Items:', 'soledad' ); ?></label>
                <input type="number" style="width: 65px; display: inline-block;"
                       id="<?php echo esc_attr( $this->get_field_id( 'bg_othergap' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'bg_othergap' ) ); ?>"
                       value="<?php echo esc_attr( $instance['bg_othergap'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'penci_img_ratio' ) ); ?>"><?php esc_html_e( 'Adjust Ratio of Images( Unit % ):', 'soledad' ); ?></label>
                <input type="number" style="width: 65px; display: inline-block;"
                       id="<?php echo esc_attr( $this->get_field_id( 'penci_img_ratio' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'penci_img_ratio' ) ); ?>"
                       value="<?php echo esc_attr( $instance['penci_img_ratio'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'imgradius' ) ); ?>"><?php esc_html_e( 'Custom Border Radius for Images', 'soledad' ); ?></label>
                <input type="number" style="width: 65px; display: inline-block;"
                       id="<?php echo esc_attr( $this->get_field_id( 'imgradius' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'imgradius' ) ); ?>"
                       value="<?php echo esc_attr( $instance['imgradius'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'bg_height' ) ); ?>"><?php esc_html_e( 'Custom Item Height (Unit is px)', 'soledad' ); ?></label>
                <input type="number" style="width: 65px; display: inline-block;"
                       id="<?php echo esc_attr( $this->get_field_id( 'bg_height' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'bg_height' ) ); ?>"
                       value="<?php echo esc_attr( $instance['bg_height'] ); ?>" size="3"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'content_horizontal_position' ) ); ?>"><?php esc_html_e( 'Content Position', 'soledad' ) ?></label>
                <select style="width:100%;"
                        id="<?php echo esc_attr( $this->get_field_id( 'content_horizontal_position' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'content_horizontal_position' ) ); ?>">
					<?php
					$content_horizontal_position_o = array(
						'left'   => esc_html__( 'Left', 'soledad' ),
						'right'  => esc_html__( 'Right', 'soledad' ),
						'center' => esc_html__( 'Center', 'soledad' ),
					);
					$this->prs_select_field( $content_horizontal_position_o, $content_horizontal_position );
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'content_vertical_position' ) ); ?>"><?php esc_html_e( 'Content Text Vertical Position', 'soledad' ) ?></label>
                <select style="width:100%;"
                        id="<?php echo esc_attr( $this->get_field_id( 'content_vertical_position' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'content_vertical_position' ) ); ?>">
					<?php
					$content_vertical_position_o = array(
						'top'    => esc_html__( 'Top', 'soledad' ),
						'middle' => esc_html__( 'Middle', 'soledad' ),
						'bottom' => esc_html__( 'Bottom', 'soledad' ),
					);
					$this->prs_select_field( $content_vertical_position_o, $content_vertical_position );
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'content_text_align' ) ); ?>"><?php esc_html_e( 'Content Text Align', 'soledad' ) ?></label>
                <select style="width:100%;"
                        id="<?php echo esc_attr( $this->get_field_id( 'content_text_align' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'content_text_align' ) ); ?>">
					<?php
					$content_text_align_o = array(
						'left'   => esc_html__( 'Left', 'soledad' ),
						'right'  => esc_html__( 'Right', 'soledad' ),
						'center' => esc_html__( 'Center', 'soledad' ),
					);
					$this->prs_select_field( $content_text_align_o, $content_text_align );
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'image_hover' ) ); ?>"><?php esc_html_e( 'Image Hover Effect', 'soledad' ) ?></label>
                <select style="width:100%;"
                        id="<?php echo esc_attr( $this->get_field_id( 'image_hover' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'image_hover' ) ); ?>">
					<?php
					$image_hover_o = array(
						'zoom-in'     => 'Zoom-In',
						'zoom-out'    => 'Zoom-out',
						'move-left'   => 'Move to Left',
						'move-right'  => 'Move to Right',
						'move-bottom' => 'Move to Bottom',
						'move-top'    => 'Move to Top',
						'none'        => 'None',
					);
					$this->prs_select_field( $image_hover_o, $image_hover );
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'text_overlay' ) ); ?>"><?php esc_html_e( 'Content Text Hover Type', 'soledad' ) ?></label>
                <select style="width:100%;"
                        id="<?php echo esc_attr( $this->get_field_id( 'text_overlay' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'text_overlay' ) ); ?>">
					<?php
					$text_overlay_o = array(
						'none'    => 'None',
						'show-in' => 'Show on Hover',
						'hide-in' => 'Hide on Hover',
					);
					$this->prs_select_field( $text_overlay_o, $text_overlay );
					?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'text_overlay_ani' ) ); ?>"><?php esc_html_e( 'Content Text Hover Type', 'soledad' ) ?></label>
                <select style="width:100%;"
                        id="<?php echo esc_attr( $this->get_field_id( 'text_overlay_ani' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'text_overlay_ani' ) ); ?>">
					<?php
					$text_overlay_ani_o = array(
						'movetop'    => 'Move to Top',
						'movebottom' => 'Move to Bottom',
						'moveleft'   => 'Move to Left',
						'moveright'  => 'Move to Right',
						'zoomin'     => 'Zoom In',
						'zoomout'    => 'Zoom Out',
						'fade'       => 'Fade',
					);
					$this->prs_select_field( $text_overlay_ani_o, $text_overlay_ani );
					?>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title_fsize' ) ); ?>"><?php esc_html_e( 'Font Size for Title (Unit is px)', 'soledad' ); ?></label>
                <input type="number" style="width: 65px; display: inline-block;"
                       id="<?php echo esc_attr( $this->get_field_id( 'title_fsize' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title_fsize' ) ); ?>"
                       value="<?php echo esc_attr( $instance['title_fsize'] ); ?>" size="3"/>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'meta_fsize' ) ); ?>"><?php esc_html_e( 'Font Size for Meta (Unit is px)', 'soledad' ); ?></label>
                <input type="number" style="width: 65px; display: inline-block;"
                       id="<?php echo esc_attr( $this->get_field_id( 'meta_fsize' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'meta_fsize' ) ); ?>"
                       value="<?php echo esc_attr( $instance['meta_fsize'] ); ?>" size="3"/>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'desc_fsize' ) ); ?>"><?php esc_html_e( 'Font Size for Description (Unit is px)', 'soledad' ); ?></label>
                <input type="number" style="width: 65px; display: inline-block;"
                       id="<?php echo esc_attr( $this->get_field_id( 'desc_fsize' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'desc_fsize' ) ); ?>"
                       value="<?php echo esc_attr( $instance['desc_fsize'] ); ?>" size="3"/>
            </p>
			<?php
			$color_fields = [
				'bgtitle_color'       => __( 'Title Color', 'soledad' ),
				'bgtitle_color_hover' => __( 'Title Hover Color', 'soledad' ),
				'bgdesc_color'        => __( 'Term Meta Color', 'soledad' ),
				'bgdesc_link_color'   => __( 'Term Meta Link Color', 'soledad' ),
				'bgdesc_link_hcolor'  => __( 'Term Meta Link Hover Color', 'soledad' ),
				'excerpt_tcolor'      => __( 'Term Description Text Color', 'soledad' ),
			];
			foreach ( $color_fields as $id => $label ) {
				$this->color_field( $id, $label, $instance );
			}
		}
	}
}
?>
