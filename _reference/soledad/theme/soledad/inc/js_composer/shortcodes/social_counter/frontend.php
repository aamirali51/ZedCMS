<?php
$output = $penci_block_width = $el_class = $css_animation = $css = $source = '';

$title_page          = $page_url = $page_height = $hide_faces = $hide_stream = '';
$heading_title_style = $heading = $heading_title_link = $heading_title_align = $hide_count = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class  = 'penci-block-vc penci-social-counter';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id   = Penci_Vc_Helper::get_unique_id_block( 'social_counter' );
?>
	<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
		<div class="penci-block_content">
			<?php
			$css_class     = 'penci-block-vc penci-social-counter';
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

			if ( 's4' != $social_style ) {
				$column_default        = in_array( $social_style, array( 's3' ) ) ? '3' : '1';
				$column                = $column ? $column : $column_default;
				$tab_column_default    = in_array( $social_style, array( 's3' ) ) ? 'default' : '1';
				$mobile_column_default = in_array( $social_style, array( 's3' ) ) ? '2' : '1';
				$tab_column            = $tab_column ? $tab_column : $tab_column_default;
				$mobile_column         = $mobile_column ? $mobile_column : $mobile_column_default;
				$wrapper_class        .= ' pcsocc-' . $column;
				$wrapper_class        .= ' pcsocc-tabcol-' . $tab_column;
				$wrapper_class        .= ' pcsocc-mocol-' . $mobile_column;
			}

			if ( 'yes' == $use_shadow ) {
				$wrapper_class .= ' pcsocshadow';
			}
			?>
			<div class="pcsoc-wrapper-outside source-<?php echo esc_attr( $source ); ?>">
				<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
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

						$error = array();

						$has_data = false;

						foreach ( $socials as $social ) {


							if ( empty( $atts[ 'social_' . $social ] ) && 'yes' == $atts[ 'social_' . $social ] ) {
								continue;
							}

							$social_info = \PENCI_FW_Social_Counter::get_social_counter( $social );

							$social_info_name = $social_info['name'] ?? '';

							if ( ! $social_info || ! $social_info_name ) {
								continue;
							}

							$has_data = true;
							if ( 'tiktok' === $social ) {
								$count = $social_info['count'];
							} else {
								$count = \PENCI_FW_Social_Counter::format_followers( $social_info['count'] );
							}

							$count = $count ? $count : '';

							$social_icon     = $social_info['icon'];
							$social_follower = isset( $social_info['text_below'] ) && $social_info['text_below'] ? $social_info['text_below'] : '';
							$social_follow   = isset( $social_info['text_btn'] ) && $social_info['text_btn'] ? $social_info['text_btn'] : '';
							$social_url      = isset( $social_info['url'] ) && $social_info['url'] ? $social_info['url'] : '';
							// $count = '10000';
							// $social_follower = 'Fans';
							// $social_icon = '<i class="penci-faicon fa fa-facebook"></i>';
							// $social_url = '#';
							// $social_follow = 'Like';
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
						$custom_social_icons = get_option( 'penci_custom_socials', array() );
						$socials             = penci_social_media_array();
						foreach ( $socials as $social => $sdata ) {

							if ( empty( $atts[ 'social_profile_' . $social ] ) ) {
								continue;
							}

							$social_icon   = penci_icon_by_ver( $sdata[1] );
							$social_follow = penci_get_setting( 'penci_trans_follow' );
							$social_url    = esc_url( do_shortcode( $sdata[0] ) );
							$name          = isset( $custom_social_icons[ $social ]['name'] ) ? $custom_social_icons[ $social ]['name'] : $social;
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
		</div>
	</div>
<?php

$id_social_counter  = '#' . $block_id;
$id_social_counter2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom         = Penci_Vc_Helper::get_heading_block_css( $id_social_counter, $atts );

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	if ( $counter_item_icon_size ) {
		echo penci_extract_md_responsive_fsize( $id_social_counter . ' .pcsoc-icon i{font-size:{{VALUE}}px}', $counter_item_icon_size );
		echo penci_extract_md_responsive_fsize( $id_social_counter . ' .pcsoc-icon pcsocs-s3 i{line-height:{{VALUE}}px}', $counter_item_icon_size );
	}
	if ( $bgcl ) {
		echo $id_social_counter2 . ' .pcsocs-s1 .pcsoc-item,' . $id_social_counter . ' .pcsocs-s2 .pcsoc-icon,.pcsocs-s3 .pcsoc-item,' . $id_social_counter . ' .pcsocs-s4 .pcsoc-icon{background-color:' . $bgcl . '}';
	}
	if ( $hbgcl ) {
		echo $id_social_counter2 . ' .pcsocs-s1 .pcsoc-item:hover,' . $id_social_counter . ' .pcsocs-s2 .pcsoc-item:hover .pcsoc-icon,' . $id_social_counter . ' .pcsocs-s3 .pcsoc-item:hover,' . $id_social_counter . ' .pcsocs-s4 .pcsoc-item:hover .pcsoc-icon{background-color:' . $hbgcl . '}';
	}
	if ( $bordercl ) {
		echo $id_social_counter2 . ' .pcsocs-s1 .pcsoc-item,' . $id_social_counter . ' .pcsocs-s2 .pcsoc-icon,' . $id_social_counter . ' .pcsocs-s3 .pcsoc-item,' . $id_social_counter . ' .pcsocs-s4 .pcsoc-icon{border-color:' . $bordercl . '}';
	}
	if ( $borderhcl ) {
		echo $id_social_counter2 . ' .pcsocs-s1 .pcsoc-item:hover,' . $id_social_counter . ' .pcsocs-s2 .pcsoc-item:hover .pcsoc-icon,' . $id_social_counter . ' .pcsocs-s3 .pcsoc-item:hover,' . $id_social_counter . ' .pcsocs-s4 .pcsoc-item:hover .pcsoc-icon{border-color:' . $borderhcl . '}';
	}
	if ( $textcl ) {
		echo $id_social_counter2 . ' .pcsoc-item i{color:' . $textcl . '}';
	}
	if ( $texthcl ) {
		echo $id_social_counter2 . ' .pcsoc-item:hover i{color:' . $texthcl . '}';
	}
	if ( $countcl ) {
		echo $id_social_counter2 . ' .pcsoc-counter{color:' . $countcl . '}';
	}
	if ( $counthcl ) {
		echo $id_social_counter2 . ' .pcsoc-item:hover .pcsoc-counter{color:' . $counthcl . '}';
	}
	if ( $fanscl ) {
		echo $id_social_counter2 . ' .pcsoc-item .pcsoc-fan{color:' . $fanscl . '}';
	}
	if ( $fanshcl ) {
		echo $id_social_counter2 . ' .pcsoc-item:hover .pcsoc-fan{color:' . $fanshcl . '}';
	}
	if ( $followcl ) {
		echo $id_social_counter2 . ' .pcsoc-item .pcsoc-like{color:' . $followcl . '}';
	}
	if ( $followhcl ) {
		echo $id_social_counter2 . ' .pcsoc-item:hover .pcsoc-like{color:' . $followhcl . '}';
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
		echo $id_social_counter . ' .pcsoc-item .pcsoc-fan{font-size:' . $fansize . 'px}';
	}
	echo '</style>';
}
