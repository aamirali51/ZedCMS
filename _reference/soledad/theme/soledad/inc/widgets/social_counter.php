<?php

class Soledad_Social_Counter extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'penci_social_counter',
			'description' => 'Show social counter data.',
		);
		parent::__construct( 'penci_social_counter', penci_get_theme_name( '.Soledad', true ) . 'Social Counter', $widget_ops );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_footer-widgets.php', array( $this, 'print_scripts' ), 9999 );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$title                  = isset( $instance['title'] ) && $instance['title'] ? $instance['title'] : '';
		$title                  = apply_filters( 'widget_title', $title );
		$social_style           = isset( $instance['style'] ) && $instance['style'] ? $instance['style'] : 's1';
		$column                 = isset( $instance['columns'] ) && $instance['columns'] ? $instance['columns'] : '1';
		$fill                   = ! empty( $instance['fill'] ) ? $instance['fill'] : 'fill';
		$shape                  = ! empty( $instance['shape'] ) ? $instance['shape'] : 'rectangle';
		$tab_column             = ! empty( $instance['tab_column'] ) ? $instance['tab_column'] : '1';
		$mobile_column          = ! empty( $instance['mobile_column'] ) ? $instance['mobile_column'] : '1';
		$color_style            = ! empty( $instance['color_style'] ) ? $instance['color_style'] : 'custom';
		$hide_count             = ! empty( $instance['hide_count'] ) ? $instance['hide_count'] : '';
		$counter_item_icon_size = ! empty( $instance['counter_item_icon_size'] ) ? $instance['counter_item_icon_size'] : '';
		$bgcl                   = ! empty( $instance['bgcl'] ) ? $instance['bgcl'] : '';
		$hbgcl                  = ! empty( $instance['hbgcl'] ) ? $instance['hbgcl'] : '';
		$bordercl               = ! empty( $instance['hbgcl'] ) ? $instance['bordercl'] : '';
		$borderhcl              = ! empty( $instance['borderhcl'] ) ? $instance['borderhcl'] : '';
		$textcl                 = ! empty( $instance['textcl'] ) ? $instance['textcl'] : '';
		$texthcl                = ! empty( $instance['texthcl'] ) ? $instance['texthcl'] : '';
		$countcl                = ! empty( $instance['countcl'] ) ? $instance['countcl'] : '';
		$counthcl               = ! empty( $instance['counthcl'] ) ? $instance['counthcl'] : '';
		$fanscl                 = ! empty( $instance['fanscl'] ) ? $instance['fanscl'] : '';
		$fanshcl                = ! empty( $instance['fanshcl'] ) ? $instance['fanshcl'] : '';
		$followcl               = ! empty( $instance['followcl'] ) ? $instance['followcl'] : '';
		$followhcl              = ! empty( $instance['followhcl'] ) ? $instance['followhcl'] : '';
		$hospace                = ! empty( $instance['hospace'] ) ? $instance['hospace'] : '';
		$verspace               = ! empty( $instance['verspace'] ) ? $instance['verspace'] : '';
		$use_shadow             = ! empty( $instance['use_shadow'] ) ? $instance['use_shadow'] : 'no';
		$countersize            = ! empty( $instance['countersize'] ) ? $instance['countersize'] : '';
		$fansize                = ! empty( $instance['fansize'] ) ? $instance['fansize'] : '';
		$source                 = ! empty( $instance['source'] ) ? $instance['source'] : 'counter';
		$id                     = wp_rand( 0, 9999999 );

		echo '<style>';
		$id_social_counter = '.widget.penci_social_counter #penci-sct-' . $id;
		if ( $counter_item_icon_size ) {
			echo $id_social_counter . ' .pcsoc-icon i{font-size:' . $counter_item_icon_size . 'px}';
			echo $id_social_counter . ' .pcsoc-icon pcsocs-s3 i{line-height:' . $counter_item_icon_size . 'px}';
		}
		if ( $bgcl ) {
			echo $id_social_counter . ' .pcsocs-s1 .pcsoc-item,' . $id_social_counter . ' .pcsocs-s2 .pcsoc-icon,.pcsocs-s3 .pcsoc-item,' . $id_social_counter . ' .pcsocs-s4 .pcsoc-icon{background-color:' . $bgcl . '}';
		}
		if ( $hbgcl ) {
			echo $id_social_counter . ' .pcsocs-s1 .pcsoc-item:hover,' . $id_social_counter . ' .pcsocs-s2 .pcsoc-item:hover .pcsoc-icon,' . $id_social_counter . ' .pcsocs-s3 .pcsoc-item:hover,' . $id_social_counter . ' .pcsocs-s4 .pcsoc-item:hover .pcsoc-icon{background-color:' . $hbgcl . '}';
		}
		if ( $bordercl ) {
			echo $id_social_counter . ' .pcsocs-s1 .pcsoc-item,' . $id_social_counter . ' .pcsocs-s2 .pcsoc-icon,' . $id_social_counter . ' .pcsocs-s3 .pcsoc-item,' . $id_social_counter . ' .pcsocs-s4 .pcsoc-icon{border-color:' . $bordercl . '}';
		}
		if ( $borderhcl ) {
			echo $id_social_counter . ' .pcsocs-s1 .pcsoc-item:hover,' . $id_social_counter . ' .pcsocs-s2 .pcsoc-item:hover .pcsoc-icon,' . $id_social_counter . ' .pcsocs-s3 .pcsoc-item:hover,' . $id_social_counter . ' .pcsocs-s4 .pcsoc-item:hover .pcsoc-icon{border-color:' . $borderhcl . '}';
		}
		if ( $textcl ) {
			echo $id_social_counter . ' .pcsoc-item i{color:' . $textcl . '}';
		}
		if ( $texthcl ) {
			echo $id_social_counter . ' .pcsoc-item:hover i{color:' . $texthcl . '}';
		}
		if ( $countcl ) {
			echo $id_social_counter . ' .pcsoc-counter{color:' . $countcl . '}';
		}
		if ( $counthcl ) {
			echo $id_social_counter . ' .pcsoc-item:hover .pcsoc-counter{color:' . $counthcl . '}';
		}
		if ( $fanscl ) {
			echo $id_social_counter . ' .pcsoc-item .pcsoc-fan{color:' . $fanscl . '}';
		}
		if ( $fanshcl ) {
			echo $id_social_counter . ' .pcsoc-item:hover .pcsoc-fan{color:' . $fanshcl . '}';
		}
		if ( $followcl ) {
			echo $id_social_counter . ' .pcsoc-item .pcsoc-like{color:' . $followcl . '}';
		}
		if ( $followhcl ) {
			echo $id_social_counter . ' .pcsoc-item:hover .pcsoc-like{color:' . $followhcl . '}';
		}
		if ( $hospace ) {
			echo $id_social_counter . ' .pcsoc-wrapper{--pcsoc-space:' . $hospace . 'px}';
		}
		if ( $verspace ) {
			echo $id_social_counter . ' .pcsoc-wrapper{--pcsoc-bspace:' . $verspace . 'px}';
		}
		if ( $countersize ) {
			echo $id_social_counter . ' .pcsoc-counter{font-size:' . $countersize . 'px}';
		}
		if ( $fansize ) {
			echo $id_social_counter . ' .pcsoc-item .pcsoc-fan,' . $id_social_counter . ' .pcsoc-item .pcsoc-like{font-size:' . $fansize . 'px}';
		}
		echo '</style>';

		$wrapper_class = 'pcsoc-wrapper';
		$social_style  = isset( $social_style ) && $social_style && in_array(
			$social_style,
			array(
				's1',
				's2',
				's3',
				's4',
			)
		) ? $social_style : 's1';
		$fill          = $fill ? $fill : 'border';
		$shape         = $shape ? $shape : 'rectangle';
		$color_style   = $color_style ? $color_style : 'custom';
		$brand_class   = $brand_class_icon = '';
		if ( in_array( $social_style, array( 's2', 's4' ) ) ) {
			$brand_class_icon = ' pcsc-brandflag';
		} elseif ( in_array( $social_style, array( 's1', 's3' ) ) ) {
			$brand_class = ' pcsc-brandflag';
		}

		$wrapper_class .= ' pcsocs-' . $social_style;
		$wrapper_class .= ' pcsocf-' . $fill;
		$wrapper_class .= ' pcsocs-' . $shape;
		$wrapper_class .= ' pcsoccl-' . $color_style;

		if ( 'yes' == $use_shadow ) {
			$wrapper_class .= ' pcsocshadow';
		}

		if ( 's4' != $social_style ) {
			$column_default        = $social_style == 's3' ? '3' : '1';
			$column                = $column ? $column : $column_default;
			$tab_column_default    = $social_style == 's3' ? 'default' : '1';
			$mobile_column_default = $social_style == 's3' ? '2' : '1';
			$tab_column            = $tab_column ? $tab_column : $tab_column_default;
			$mobile_column         = $mobile_column ? $mobile_column : $mobile_column_default;
			$wrapper_class        .= ' pcsocc-' . $column;
			$wrapper_class        .= ' pcsocc-tabcol-' . $tab_column;
			$wrapper_class        .= ' pcsocc-mocol-' . $mobile_column;
		}

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		?>
		<div class="pcsoc-wrapper-outside source-<?php echo esc_attr( $source ); ?>"
			id="<?php echo 'penci-sct-' . $id; ?>">
			<div class="<?php echo $wrapper_class; ?>">
				<?php

				if ( 'counter' == $source ) {
					$socials = array(
						'facebook',
						'twitter',
						'youtube',
						'instagram',
						'pinterest',
						'flickr',
						'vimeo',
						'soundcloud',
						'behance ',
						'vk',
						'tiktok',
						'twitch',
						'rss',
					);
					foreach ( $socials as $social ) {

						if ( empty( $instance[ $social ] ) ) {
							continue;
						}

						$social_info = \PENCI_FW_Social_Counter::get_social_counter( $social );

						$social_info_name = isset( $social_info['name'] ) && $social_info['name'] ? $social_info['name'] : '';

						if ( ! $social_info || ! $social_info_name ) {
							continue;
						}

						if ( strpos( $social_info['count'], '.' ) !== false ) {
							$count = $social_info['count'];
						} else {
							$count = \PENCI_FW_Social_Counter::format_followers( $social_info['count'] );
						}

						$count = $count ? $count : '';

						$social_icon     = $social_info['icon'];
						$social_follower = isset( $social_info['text_below'] ) && $social_info['text_below'] ? $social_info['text_below'] : '';
						$social_follow   = isset( $social_info['text_btn'] ) && $social_info['text_btn'] ? $social_info['text_btn'] : '';
						$social_url      = isset( $social_info['url'] ) && $social_info['url'] ? $social_info['url'] : '';
						?>
						<div class="pcsoc-item-wrap">
							<a class="pcsoc-item pcsoci-<?php echo $social . $brand_class; ?>
																	<?php
																	if ( ! $count ) {
																		echo ' empty-count';
																	}
																	?>
							" href="<?php echo esc_url( $social_url ); ?>"
								target="_blank" <?php echo penci_reltag_social_icons(); ?>>
								<span class="pcsoc-icon pcsoci-<?php echo $social . $brand_class_icon; ?>"><?php echo $social_icon; ?></span>
								<?php if ( $count && 'yes' != $hide_count ) { ?>
									<span class="pcsoc-counter"><?php echo $count; ?></span>
									<span class="pcsoc-fan"><?php echo $social_follower; ?></span>
								<?php } else { ?>
									<span class="pcsoc-counter pcsoc-socname"><?php echo $social; ?></span>
								<?php } ?>
								<?php if ( in_array( $social_style, array( 's1', 's2' ) ) ) { ?>
									<span class="pcsoc-like"><?php echo $social_follow; ?></span>
								<?php } ?>
							</a>
						</div>
						<?php
					}
				} else {
					$socials = penci_social_media_array();
					foreach ( $socials as $social => $sdata ) {

						if ( empty( $instance[ 'profile_' . $social ] ) ) {
							continue;
						}

						$social_icon   = penci_icon_by_ver( $sdata[1] );
						$social_follow = penci_get_setting( 'follow' );
						$social_url    = esc_url( do_shortcode( $sdata[0] ) );

						$custom_social_icons = get_option( 'penci_custom_socials', array() );
						$name                = isset( $custom_social_icons[ $social ]['name'] ) ? $custom_social_icons[ $social ]['name'] : $social;
						?>
						<div class="pcsoc-item-wrap">
							<a class="pcsoc-item pcsoci-<?php echo $social . $brand_class; ?> empty-count"
								href="<?php echo esc_url( $social_url ); ?>"
								target="_blank" <?php echo penci_reltag_social_icons(); ?>>
								<span class="pcsoc-icon pcsoci-<?php echo $social . $brand_class_icon; ?>"><?php echo $social_icon; ?></span>
								<span class="pcsoc-counter pcsoc-socname"><?php echo $name; ?></span>
								<?php if ( in_array( $social_style, array( 's1', 's2' ) ) ) { ?>
									<span class="pcsoc-like"><?php echo $social_follow; ?></span>
								<?php } ?>
							</a>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$title                  = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Social Counter', 'soledad' );
		$social_lists           = $this->social_lists();
		$style                  = ! empty( $instance['style'] ) ? $instance['style'] : 's1';
		$fill                   = ! empty( $instance['fill'] ) ? $instance['fill'] : 'fill';
		$shape                  = ! empty( $instance['shape'] ) ? $instance['shape'] : 'rectangle';
		$columns                = ! empty( $instance['columns'] ) ? $instance['columns'] : '1';
		$tab_column             = ! empty( $instance['tab_column'] ) ? $instance['tab_column'] : '';
		$mobile_column          = ! empty( $instance['mobile_column'] ) ? $instance['mobile_column'] : '';
		$color_style            = ! empty( $instance['color_style'] ) ? $instance['color_style'] : 'custom';
		$hospace                = ! empty( $instance['hospace'] ) ? $instance['hospace'] : '';
		$verspace               = ! empty( $instance['verspace'] ) ? $instance['verspace'] : '';
		$counter_item_icon_size = ! empty( $instance['counter_item_icon_size'] ) ? $instance['counter_item_icon_size'] : '';
		$bgcl                   = ! empty( $instance['bgcl'] ) ? $instance['bgcl'] : '';
		$hbgcl                  = ! empty( $instance['hbgcl'] ) ? $instance['hbgcl'] : '';
		$bordercl               = ! empty( $instance['hbgcl'] ) ? $instance['bordercl'] : '';
		$borderhcl              = ! empty( $instance['borderhcl'] ) ? $instance['borderhcl'] : '';
		$textcl                 = ! empty( $instance['textcl'] ) ? $instance['textcl'] : '';
		$texthcl                = ! empty( $instance['texthcl'] ) ? $instance['texthcl'] : '';
		$countcl                = ! empty( $instance['countcl'] ) ? $instance['countcl'] : '';
		$counthcl               = ! empty( $instance['counthcl'] ) ? $instance['counthcl'] : '';
		$fanscl                 = ! empty( $instance['fanscl'] ) ? $instance['fanscl'] : '';
		$fanshcl                = ! empty( $instance['fanshcl'] ) ? $instance['fanshcl'] : '';
		$followcl               = ! empty( $instance['followcl'] ) ? $instance['followcl'] : '';
		$followhcl              = ! empty( $instance['followhcl'] ) ? $instance['followhcl'] : '';
		$use_shadow             = ! empty( $instance['use_shadow'] ) ? $instance['use_shadow'] : '';
		$hide_count             = ! empty( $instance['hide_count'] ) ? $instance['hide_count'] : '';
		$countersize            = ! empty( $instance['countersize'] ) ? $instance['countersize'] : '';
		$fansize                = ! empty( $instance['fansize'] ) ? $instance['fansize'] : '';
		$source                 = ! empty( $instance['source'] ) ? $instance['source'] : 'counter';
		?>

		<div class="penci-social-widget-tabs">
			<div class="tabs-stage">
				<div id="pc_general_social" class="penci-social-tab tab-active">
					<p class="widget-title-settings">
						<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'soledad' ); ?></label>
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
								value="<?php echo esc_attr( $title ); ?>">
					</p>

					<p class="widget-title-settings">
						<label for="<?php echo esc_attr( $this->get_field_id( 'source' ) ); ?>">Select Data
							Source:</label>
						<select class="widefat source-select"
								id="<?php echo esc_attr( $this->get_field_id( 'source' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'source' ) ); ?>">
							<option value="counter" <?php selected( $source, 'counter' ); ?>><?php echo esc_html__( 'Social Counter', 'soledad' ); ?></option>
							<option value="customizer" <?php selected( $source, 'customizer' ); ?>><?php echo esc_html__( 'Social Media URLs from Customizer ( no Counter )', 'soledad' ); ?></option>
						</select>
					</p>

					<p class="widget-title-settings notes counter"><strong>Note Important</strong>: You need to setup
						data for socials sharing
						on <a
								href="<?php echo esc_url( admin_url( 'admin.php?page=penci_social_counter_settings' ) ); ?>"
								target="_blank">this page</a> to get the counter number work.</p>

					<p style="display: none;" class="widget-title-settings notes customizer"><strong>Note
							Important</strong>: You need to setup
						data for socials profile on Appearance > Customize > Social Media.</p>

					<p class="widget-title-settings">
						<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>">Select Pre-Build
							Design:</label>
						<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
							<option value="s1" <?php selected( $style, 's1' ); ?>><?php echo esc_html__( 'Style 1', 'soledad' ); ?></option>
							<option value="s2" <?php selected( $style, 's2' ); ?>><?php echo esc_html__( 'Style 2', 'soledad' ); ?></option>
							<option value="s3" <?php selected( $style, 's3' ); ?>><?php echo esc_html__( 'Style 3', 'soledad' ); ?></option>
							<option value="s4" <?php selected( $style, 's4' ); ?>><?php echo esc_html__( 'Style 4', 'soledad' ); ?></option>
						</select>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'fill' ) ); ?>">Filled or Bordered
							Style?</label>
						<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'fill' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'fill' ) ); ?>">
							<option value="fill" <?php selected( $fill, 'fill' ); ?>><?php echo esc_html__( 'Filled', 'soledad' ); ?></option>
							<option value="border" <?php selected( $fill, 'border' ); ?>><?php echo esc_html__( 'Bordered', 'soledad' ); ?></option>
						</select>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'shape' ) ); ?>">Shape</label>
						<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'shape' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'shape' ) ); ?>">
							<option value="rectangle" <?php selected( $shape, 'rectangle' ); ?>><?php echo esc_html__( 'Rectangle', 'soledad' ); ?></option>
							<option value="round" <?php selected( $shape, 'round' ); ?>><?php echo esc_html__( 'Round', 'soledad' ); ?></option>
							<option value="circle" <?php selected( $shape, 'circle' ); ?>><?php echo esc_html__( 'Circle', 'soledad' ); ?></option>
						</select>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'color_style' ) ); ?>">Colors
							Style</label>
						<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'color_style' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'color_style' ) ); ?>">
							<option value="custom" <?php selected( $color_style, 'custom' ); ?>><?php echo esc_html__( 'Custom Color', 'soledad' ); ?></option>
							<option value="brandbg" <?php selected( $color_style, 'brandbg' ); ?>><?php echo esc_html__( 'Brand Background', 'soledad' ); ?></option>
							<option value="brandtext" <?php selected( $color_style, 'brandtext' ); ?>><?php echo esc_html__( 'Brand Text', 'soledad' ); ?></option>
						</select>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>">Select Columns:</label>
						<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>">
							<option value="1" <?php selected( $columns, '1' ); ?>><?php echo esc_html__( '1 Column', 'soledad' ); ?></option>
							<option value="2" <?php selected( $columns, '2' ); ?>><?php echo esc_html__( '2 Columns', 'soledad' ); ?></option>
							<option value="3" <?php selected( $columns, '3' ); ?>><?php echo esc_html__( '3 Columns', 'soledad' ); ?></option>
							<option value="4" <?php selected( $columns, '4' ); ?>><?php echo esc_html__( '4 Columns', 'soledad' ); ?></option>
						</select>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'tab_column' ) ); ?>">Select Columns for
							Tablet:</label>
						<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tab_column' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'tab_column' ) ); ?>">
							<option value="" <?php selected( $tab_column, '' ); ?>><?php echo esc_html__( 'Default', 'soledad' ); ?></option>
							<option value="1" <?php selected( $tab_column, '1' ); ?>><?php echo esc_html__( '1 Column', 'soledad' ); ?></option>
							<option value="2" <?php selected( $tab_column, '2' ); ?>><?php echo esc_html__( '2 Columns', 'soledad' ); ?></option>
							<option value="3" <?php selected( $tab_column, '3' ); ?>><?php echo esc_html__( '3 Columns', 'soledad' ); ?></option>
							<option value="4" <?php selected( $tab_column, '4' ); ?>><?php echo esc_html__( '4 Columns', 'soledad' ); ?></option>
						</select>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'mobile_column' ) ); ?>">Select Columns
							for
							Mobile</label>
						<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'mobile_column' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'mobile_column' ) ); ?>">
							<option value="" <?php selected( $mobile_column, '' ); ?>><?php echo esc_html__( 'Default', 'soledad' ); ?></option>
							<option value="1" <?php selected( $mobile_column, '1' ); ?>><?php echo esc_html__( '1 Column', 'soledad' ); ?></option>
							<option value="2" <?php selected( $mobile_column, '2' ); ?>><?php echo esc_html__( '2 Columns', 'soledad' ); ?></option>
							<option value="3" <?php selected( $mobile_column, '3' ); ?>><?php echo esc_html__( '3 Columns', 'soledad' ); ?></option>
						</select>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'use_shadow' ) ); ?>">Use Shadow?</label>
						<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'use_shadow' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'use_shadow' ) ); ?>">
							<option value="no" <?php selected( $use_shadow, 'no' ); ?>><?php echo esc_html__( 'No', 'soledad' ); ?></option>
							<option value="yes" <?php selected( $use_shadow, 'yes' ); ?>><?php echo esc_html__( 'Yes', 'soledad' ); ?></option>
						</select>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'hide_count' ) ); ?>">Hide Counter Data &
							Show Social Name?</label>
						<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'hide_count' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'hide_count' ) ); ?>">
							<option value="no" <?php selected( $hide_count, 'no' ); ?>><?php echo esc_html__( 'No', 'soledad' ); ?></option>
							<option value="yes" <?php selected( $hide_count, 'yes' ); ?>><?php echo esc_html__( 'Yes', 'soledad' ); ?></option>
						</select>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'hospace' ) ); ?>">Horizontal Spacing
							Between Social Icons</label>
						<input type="number" class="widefat"
								id="<?php echo esc_attr( $this->get_field_id( 'hospace' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'hospace' ) ); ?>"
								value="<?php echo $hospace; ?>">
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'verspace' ) ); ?>">Vertical Spacing
							Between Social Icons</label>
						<input type="number" class="widefat"
								id="<?php echo esc_attr( $this->get_field_id( 'verspace' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'verspace' ) ); ?>"
								value="<?php echo $verspace; ?>">
					</p>
				</div>
				<div id="pc_profile_social" class="data-source-settings counter penci-social-tab">
					<?php
					$custom_social_icons = get_option( 'penci_custom_socials', array() );
					foreach ( $social_lists as $social => $social_info ) {
						$checked = isset( $instance[ $social ] ) ? (bool) $instance[ $social ] : false;
						$name    = $social_info['label'];
						$name    = isset( $custom_social_icons[ $name ]['name'] ) ? $custom_social_icons[ $name ]['name'] : $name;
						?>

						<p>
							<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( $social ) ); ?>"
									name="<?php echo esc_attr( $this->get_field_name( $social ) ); ?>"
									type="checkbox" <?php checked( $checked ); ?> />
							<label for="<?php echo esc_attr( $this->get_field_id( $social ) ); ?>"><?php echo esc_attr( $name ); ?></label>
						</p>

						<?php

					}
					?>
				</div>
				<div class="social-flex penci-social-tab data-source-settings customizer">
					<?php
					$custom_social_icons = get_option( 'penci_custom_socials', array() );
					$socials_profile     = penci_social_media_array();
					foreach ( $socials_profile as $name => $data ) {
						$settings_name = 'profile_' . $name;
						$checked       = isset( $instance[ $settings_name ] ) ? (bool) $instance[ $settings_name ] : false;
						$name          = isset( $custom_social_icons[ $name ]['name'] ) ? $custom_social_icons[ $name ]['name'] : $name;
						?>
						<p>
							<label for="<?php echo esc_attr( $this->get_field_id( $settings_name ) ); ?>"><?php esc_html_e( 'Show ' . ucwords( $name ) . ':', 'soledad' ); ?></label>
							<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( $settings_name ) ); ?>"
									name="<?php echo esc_attr( $this->get_field_name( $settings_name ) ); ?>" <?php checked( $checked ); ?> />
						</p>
					<?php } ?>
				</div>
				<div id="pc_color_social" class="penci-social-tab">

					<p class="widget-title-settings">
						<label for="<?php echo $this->get_field_id( 'counter_item_icon_size' ); ?>"
								style="display:block;"><?php _e( 'Icon Size: (Number only)' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id( 'counter_item_icon_size' ); ?>"
								name="<?php echo $this->get_field_name( 'counter_item_icon_size' ); ?>" type="number"
								value="<?php echo $counter_item_icon_size; ?>"/>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'countersize' ) ); ?>">Font Size for
							Counter Number</label>
						<input type="number" class="widefat"
								id="<?php echo esc_attr( $this->get_field_id( 'countersize' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'countersize' ) ); ?>"
								value="<?php echo $countersize; ?>">
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'fansize' ) ); ?>">Font Size for Fans/Like
							text</label>
						<input type="number" class="widefat"
								id="<?php echo esc_attr( $this->get_field_id( 'fansize' ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'fansize' ) ); ?>"
								value="<?php echo $fansize; ?>">
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'bgcl' ); ?>"
								style="display:block;"><?php _e( 'Background Color:' ); ?></label>
						<input class="widefat pcwoo-color-picker color-picker"
								id="<?php echo $this->get_field_id( 'bgcl' ); ?>"
								name="<?php echo $this->get_field_name( 'bgcl' ); ?>" type="text"
								value="<?php echo $bgcl; ?>"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'hbgcl' ); ?>"
								style="display:block;"><?php _e( 'Background Hover Color:' ); ?></label>
						<input class="widefat pcwoo-color-picker color-picker"
								id="<?php echo $this->get_field_id( 'hbgcl' ); ?>"
								name="<?php echo $this->get_field_name( 'hbgcl' ); ?>" type="text"
								value="<?php echo $hbgcl; ?>"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'bordercl' ); ?>"
								style="display:block;"><?php _e( 'Border Color:' ); ?></label>
						<input class="widefat pcwoo-color-picker color-picker"
								id="<?php echo $this->get_field_id( 'bordercl' ); ?>"
								name="<?php echo $this->get_field_name( 'bordercl' ); ?>" type="text"
								value="<?php echo $bordercl; ?>"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'borderhcl' ); ?>"
								style="display:block;"><?php _e( 'Border Hover Color:' ); ?></label>
						<input class="widefat pcwoo-color-picker color-picker"
								id="<?php echo $this->get_field_id( 'bordercl' ); ?>"
								name="<?php echo $this->get_field_name( 'borderhcl' ); ?>" type="text"
								value="<?php echo $borderhcl; ?>"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'textcl' ); ?>"
								style="display:block;"><?php _e( 'Icon Color:' ); ?></label>
						<input class="widefat pcwoo-color-picker color-picker"
								id="<?php echo $this->get_field_id( 'textcl' ); ?>"
								name="<?php echo $this->get_field_name( 'textcl' ); ?>" type="text"
								value="<?php echo $textcl; ?>"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'texthcl' ); ?>"
								style="display:block;"><?php _e( 'Icon Hover Color:' ); ?></label>
						<input class="widefat pcwoo-color-picker color-picker"
								id="<?php echo $this->get_field_id( 'texthcl' ); ?>"
								name="<?php echo $this->get_field_name( 'texthcl' ); ?>" type="text"
								value="<?php echo $texthcl; ?>"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'countcl' ); ?>"
								style="display:block;"><?php _e( 'Counter Text Color:' ); ?></label>
						<input class="widefat pcwoo-color-picker color-picker"
								id="<?php echo $this->get_field_id( 'countcl' ); ?>"
								name="<?php echo $this->get_field_name( 'countcl' ); ?>" type="text"
								value="<?php echo $countcl; ?>"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'counthcl' ); ?>"
								style="display:block;"><?php _e( 'Counter Text Hover Color:' ); ?></label>
						<input class="widefat pcwoo-color-picker color-picker"
								id="<?php echo $this->get_field_id( 'counthcl' ); ?>"
								name="<?php echo $this->get_field_name( 'counthcl' ); ?>" type="text"
								value="<?php echo $counthcl; ?>"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'fanscl' ); ?>"
								style="display:block;"><?php _e( 'Fans Text Color:' ); ?></label>
						<input class="widefat pcwoo-color-picker color-picker"
								id="<?php echo $this->get_field_id( 'fanscl' ); ?>"
								name="<?php echo $this->get_field_name( 'fanscl' ); ?>" type="text"
								value="<?php echo $fanscl; ?>"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'fanshcl' ); ?>"
								style="display:block;"><?php _e( 'Fans Text Hover Color:' ); ?></label>
						<input class="widefat pcwoo-color-picker color-picker"
								id="<?php echo $this->get_field_id( 'fanshcl' ); ?>"
								name="<?php echo $this->get_field_name( 'fanshcl' ); ?>" type="text"
								value="<?php echo $fanshcl; ?>"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'followcl' ); ?>"
								style="display:block;"><?php _e( 'Follow Text Color:' ); ?></label>
						<input class="widefat pcwoo-color-picker color-picker"
								id="<?php echo $this->get_field_id( 'followcl' ); ?>"
								name="<?php echo $this->get_field_name( 'followcl' ); ?>" type="text"
								value="<?php echo $followcl; ?>"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'followhcl' ); ?>"
								style="display:block;"><?php _e( 'Follow Text Hover Color:' ); ?></label>
						<input class="widefat pcwoo-color-picker color-picker"
								id="<?php echo $this->get_field_id( 'followhcl' ); ?>"
								name="<?php echo $this->get_field_name( 'followhcl' ); ?>" type="text"
								value="<?php echo $followhcl; ?>"/>
					</p>

				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs the social array listing
	 */
	public function social_lists() {
		return array(
			'facebook'   => array(
				'label'   => __( 'Facebook', 'soledad' ),
				'default' => 'yes',
			),
			'twitter'    => array(
				'label'   => __( 'Twitter', 'soledad' ),
				'default' => 'yes',
			),
			'youtube'    => array(
				'label'   => __( 'Youtube', 'soledad' ),
				'default' => 'yes',
			),
			'instagram'  => array(
				'label'   => __( 'Instagram', 'soledad' ),
				'default' => 'yes',
			),
			'pinterest'  => array(
				'label'   => __( 'Pinterest', 'soledad' ),
				'default' => '',
			),
			'flickr'     => array(
				'label'   => __( 'Flickr', 'soledad' ),
				'default' => '',
			),
			'vimeo'      => array(
				'label'   => __( 'Vimeo', 'soledad' ),
				'default' => '',
			),
			'soundcloud' => array(
				'label'   => __( 'SoundCloud', 'soledad' ),
				'default' => '',
			),
			'behance '   => array(
				'label'   => __( 'Behance', 'soledad' ),
				'default' => '',
			),
			'vk'         => array(
				'label'   => __( 'VK', 'soledad' ),
				'default' => '',
			),
			'tiktok'     => array(
				'label'   => __( 'Tiktok', 'soledad' ),
				'default' => '',
			),
			'twitch'     => array(
				'label'   => __( 'Twitch', 'soledad' ),
				'default' => '',
			),
			'rss'        => array(
				'label'   => __( 'RSS', 'soledad' ),
				'default' => '',
			),
		);
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                           = $old_instance;
		$instance['title']                  = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['style']                  = ( ! empty( $new_instance['style'] ) ) ? sanitize_text_field( $new_instance['style'] ) : 's1';
		$instance['columns']                = ( ! empty( $new_instance['columns'] ) ) ? sanitize_text_field( $new_instance['columns'] ) : '1';
		$instance['fill']                   = ! empty( $new_instance['fill'] ) ? $new_instance['fill'] : 'fill';
		$instance['shape']                  = ! empty( $new_instance['shape'] ) ? $new_instance['shape'] : 'rectangle';
		$instance['tab_column']             = ! empty( $new_instance['tab_column'] ) ? $new_instance['tab_column'] : '1';
		$instance['mobile_column']          = ! empty( $new_instance['mobile_column'] ) ? $new_instance['mobile_column'] : '1';
		$instance['color_style']            = ! empty( $new_instance['color_style'] ) ? $new_instance['color_style'] : 'custom';
		$instance['hide_count']             = ! empty( $new_instance['hide_count'] ) ? $new_instance['hide_count'] : '';
		$instance['counter_item_icon_size'] = ! empty( $new_instance['counter_item_icon_size'] ) ? $new_instance['counter_item_icon_size'] : '';
		$instance['bgcl']                   = ! empty( $new_instance['bgcl'] ) ? $new_instance['bgcl'] : '';
		$instance['hbgcl']                  = ! empty( $new_instance['hbgcl'] ) ? $new_instance['hbgcl'] : '';
		$instance['bordercl']               = ! empty( $new_instance['hbgcl'] ) ? $new_instance['bordercl'] : '';
		$instance['borderhcl']              = ! empty( $new_instance['borderhcl'] ) ? $new_instance['borderhcl'] : '';
		$instance['textcl']                 = ! empty( $new_instance['textcl'] ) ? $new_instance['textcl'] : '';
		$instance['texthcl']                = ! empty( $new_instance['texthcl'] ) ? $new_instance['texthcl'] : '';
		$instance['countcl']                = ! empty( $new_instance['countcl'] ) ? $new_instance['countcl'] : '';
		$instance['counthcl']               = ! empty( $new_instance['counthcl'] ) ? $new_instance['counthcl'] : '';
		$instance['fanscl']                 = ! empty( $new_instance['fanscl'] ) ? $new_instance['fanscl'] : '';
		$instance['fanshcl']                = ! empty( $new_instance['fanshcl'] ) ? $new_instance['fanshcl'] : '';
		$instance['followcl']               = ! empty( $new_instance['followcl'] ) ? $new_instance['followcl'] : '';
		$instance['followhcl']              = ! empty( $new_instance['followhcl'] ) ? $new_instance['followhcl'] : '';
		$instance['hospace']                = ! empty( $new_instance['hospace'] ) ? $new_instance['hospace'] : '';
		$instance['verspace']               = ! empty( $new_instance['verspace'] ) ? $new_instance['verspace'] : '';
		$instance['use_shadow']             = ! empty( $new_instance['use_shadow'] ) ? $new_instance['use_shadow'] : '';
		$instance['countersize']            = ! empty( $new_instance['countersize'] ) ? $new_instance['countersize'] : '';
		$instance['fansize']                = ! empty( $new_instance['fansize'] ) ? $new_instance['fansize'] : '';
		$instance['source']                 = ! empty( $new_instance['source'] ) ? $new_instance['source'] : 'counter';

		foreach ( $this->social_lists() as $social => $social_info ) {
			$instance[ $social ] = ( ! empty( $new_instance[ $social ] ) ) ? 1 : 0;
		}

		$socials_profile = penci_social_media_array();
		foreach ( $socials_profile as $name => $data ) {
			$instance[ 'profile_' . $name ] = ( ! empty( $new_instance[ 'profile_' . $name ] ) ) ? 1 : 0;
		}

		return $instance;
	}

	public function enqueue_scripts( $hook_suffix ) {
		if ( 'widgets.php' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'underscore' );
	}


	public function print_scripts() {
		?>
		<script>
			(function ($) {
				function initColorPicker(widget) {
					widget.find('.color-picker').wpColorPicker({
						change: _.throttle(function () { // For Customizer
							$(this).trigger('change');
						}, 3000)
					});
				}

				function onFormUpdate(event, widget) {
					initColorPicker(widget);
				}

				$(document).on('widget-added widget-updated', onFormUpdate);

				$(document).ready(function () {
					$('#widgets-right .widget:has(.color-picker)').each(function () {
						initColorPicker($(this));
					});
				});
			}(jQuery));
		</script>
		<?php
	}
}

// register widget
function penci_register_social_counter_widget() {
	register_widget( 'Soledad_Social_Counter' );
}

add_action( 'widgets_init', 'penci_register_social_counter_widget' );
