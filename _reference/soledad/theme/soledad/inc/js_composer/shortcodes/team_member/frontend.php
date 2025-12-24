<?php
$output = $penci_block_width = $el_class = $css_animation = $css = $responsive_spacing = '';


$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$team_members = (array) vc_param_group_parse_atts( $atts['teammembers'] );

if ( ! $team_members ) {
	return;
}

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class     = 'penci-block-vc penci-teammb-bsc';
$css_class     .= ' penci-teammb-' . $atts['style'];
$css_class     .= ' pencisc-grid-' . $atts['columns'];
$column_tablet = isset( $atts['columns_tablet'] ) ? $atts['columns_tablet'] : '2';
$column_mobile = isset( $atts['columns_mobile'] ) ? $atts['columns_mobile'] : '1';
$css_class     .= ' pencisc-grid-tablet-' . $column_tablet;
$css_class     .= ' pencisc-grid-mobile-' . $column_mobile;

$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'team_member' );
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
        <div class="penci-block_content pencisc-grid">
			<?php
			$link_target     = 'target="_blank"';
			$social_colorcss = '';
			$titpos          = isset( $atts['titpos'] ) ? $atts['titpos'] : 'default';
			$social_shape    = isset( $atts['social_shape'] ) ? $atts['social_shape'] : 'default';
			$social_style    = isset( $atts['social_style'] ) ? $atts['social_style'] : 'default';
			$social_colors   = isset( $atts['social_colors'] ) ? $atts['social_colors'] : 'default';
			if ( 'default' != $social_shape && 'default' == $social_style ) {
				$social_style = 'filled';
			}

			if ( 'brandbg' == $social_colors ) {
				$social_colorcss = ' penci-social-colored';
			} else if ( 'brandtext' == $social_colors ) {
				$social_colorcss = ' penci-social-textcolored';
			}

			$css_class .= ' pcsc-shape-' . $social_shape;
			$css_class .= ' pcsc-st-' . $social_style;

			if ( ! get_theme_mod( 'penci_dis_noopener' ) ) {
				$link_target .= ' rel="noopener"';
			}

			$title_pos = 'below';
			if ( 's1' == $atts['style'] ) {
				$title_pos = 'above';
			}
			if ( 'above' == $titpos || 'below' == $titpos ) {
				$title_pos = $titpos;
			}

			foreach ( (array) $team_members as $item ) {

				$name_item     = isset( $item['name'] ) ? $item['name'] : '';
				$desc_item     = isset( $item['desc'] ) ? $item['desc'] : '';
				$position_item = isset( $item['position'] ) ? $item['position'] : '';

				$link_website_item   = isset( $item['link_website'] ) ? $item['link_website'] : '';
				$link_twitter_item   = isset( $item['link_twitter'] ) ? $item['link_twitter'] : '';
				$link_linkedin_item  = isset( $item['link_linkedin'] ) ? $item['link_linkedin'] : '';
				$link_instagram_item = isset( $item['link_instagram'] ) ? $item['link_instagram'] : '';
				$link_dribbble_item  = isset( $item['link_dribbble'] ) ? $item['link_dribbble'] : '';
				$link_facebook_item  = isset( $item['link_facebook'] ) ? $item['link_facebook'] : '';

				$link_youtube_item   = isset( $item['link_youtube'] ) ? $item['link_youtube'] : '';
				$link_vimeo_item     = isset( $item['link_vimeo'] ) ? $item['link_vimeo'] : '';
				$link_pinterest_item = isset( $item['link_pinterest'] ) ? $item['link_pinterest'] : '';


				$url_img_item = penci_get_default_thumbnail_url();
				if ( isset( $item['image'] ) && $item['image'] ) {
					$url_img_item = wp_get_attachment_url( $item['image'] );
				}

				?>
                <div class="penci-teammb-item pencisc-grid-item">
                    <div class="penci-teammb-inner">
						<?php
						if ( $url_img_item ) {
							$dis_lazy = get_theme_mod( 'penci_disable_lazyload_layout' );
							if ( $dis_lazy ) {
								echo '<span class="penci-image-holder penci-teammb-img  penci-disable-lazy style="background-image: url(' . esc_url( $url_img_item ) . ');"></span>';
							} else {
								echo '<span class="penci-image-holder penci-teammb-img penci-lazy" data-bgset="' . esc_url( $url_img_item ) . '"></span>';
							}
						}
						?>
                        <div class="penci-team_item__info">
							<?php if ( $position_item && 'below' == $title_pos ): ?>
                                <div class="penci-team_member_pos"><?php echo $position_item; ?></div>
							<?php endif; ?>
							<?php if ( $name_item ): ?>
                                <h5 class="penci-team_member_name"><?php echo $name_item; ?></h5>
							<?php endif; ?>
							<?php if ( $position_item && 'above' == $title_pos ): ?>
                                <div class="penci-team_member_pos"><?php echo $position_item; ?></div>
							<?php endif; ?>
							<?php if ( $desc_item ): ?>
                                <div class="penci-team_member_desc"><?php echo $desc_item; ?></div>
							<?php endif; ?>
                            <div class="penci-team_member_socails penci-social-wrap <?php echo $social_colorcss; ?>">
								<?php if ( $link_website_item ): ?>
                                    <a <?php echo $link_target ?> class="penci-social-item penci-social-item website"
                                                                  href="<?php echo esc_url( $link_website_item ); ?>"><?php penci_fawesome_icon( 'fas fa-globe' ); ?></a>
								<?php endif; ?>
								<?php if ( $link_facebook_item ): ?>
                                    <a <?php echo $link_target ?> class="penci-social-item penci-social-item facebook-f"
                                                                  href="<?php echo esc_url( $link_facebook_item ); ?>"><?php penci_fawesome_icon( 'fab fa-facebook-f' ); ?></a>
								<?php endif; ?>
								<?php if ( $link_twitter_item ): ?>
                                    <a <?php echo $link_target ?> class="penci-social-item penci-social-item twitter"
                                                                  href="<?php echo esc_url( $link_twitter_item ); ?>"><?php penci_fawesome_icon( 'penciicon-x-twitter' ); ?></a>
								<?php endif; ?>

								<?php if ( $link_linkedin_item ): ?>
                                    <a <?php echo $link_target ?> class="penci-social-item penci-social-item linkedin"
                                                                  href="<?php echo esc_url( $link_linkedin_item ); ?>"><?php penci_fawesome_icon( 'fab fa-linkedin-in' ); ?></a>
								<?php endif; ?>
								<?php if ( $link_instagram_item ): ?>
                                    <a <?php echo $link_target ?> class="penci-social-item penci-social-item instagram"
                                                                  href="<?php echo esc_url( $link_instagram_item ); ?>"><?php penci_fawesome_icon( 'fab fa-instagram' ); ?></a>
								<?php endif; ?>
								<?php if ( $link_youtube_item ): ?>
                                    <a <?php echo $link_target ?> class="penci-social-item penci-social-item youtube"
                                                                  href="<?php echo esc_url( $link_youtube_item ); ?>"><?php penci_fawesome_icon( 'fab fa-youtube' ); ?></a>
								<?php endif; ?>
								<?php if ( $link_vimeo_item ): ?>
                                    <a <?php echo $link_target ?> class="penci-social-item penci-social-item vimeo"
                                                                  href="<?php echo esc_url( $link_vimeo_item ); ?>"><?php penci_fawesome_icon( 'fab fa-vimeo-v' ); ?></a>
								<?php endif; ?>
								<?php if ( $link_pinterest_item ): ?>
                                    <a <?php echo $link_target ?> class="penci-social-item penci-social-item pinterest"
                                                                  href="<?php echo esc_url( $link_pinterest_item ); ?>"><?php penci_fawesome_icon( 'fab fa-pinterest' ); ?></a>
								<?php endif; ?>
								<?php if ( $link_dribbble_item ): ?>
                                    <a <?php echo $link_target ?> class="penci-social-item penci-social-item dribbble"
                                                                  href="<?php echo esc_url( $link_dribbble_item ); ?>"><?php penci_fawesome_icon( 'fab fa-dribbble' ); ?></a>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}

			?>
        </div>
    </div>
<?php

$id_team_members = '#' . $block_id;
$id_team_members2 = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom      = Penci_Vc_Helper::get_heading_block_css( $id_team_members, $atts );

$css_height_border = '';
if ( $atts['height_team'] ) {
	$css_height_border .= 'height:' . esc_attr( $atts['height_team'] ) . ';';
}
if ( $atts['border_width_team'] ) {
	$css_height_border .= 'border-width:' . esc_attr( $atts['border_width_team'] ) . ';';
}
if ( $atts['team_bghcolor'] ) {
	$css_height_border .= 'background:' . esc_attr( $atts['team_bghcolor'] ) . ';';
}
if ( $atts['team_borderhcolor'] ) {
	$css_height_border .= 'border-color:' . esc_attr( $atts['team_borderhcolor'] ) . ';';
}
if ( $atts['border_width_team'] ) {
	$css_custom .= $id_team_members . ' .penci-teammb-item .penci-teammb-inner{ ' . $css_height_border . ' }';
}

$css_width_height = '';
if ( $atts['width_img'] ) {
	$css_width_height .= 'width:' . esc_attr( $atts['width_img'] ) . ';';
}
if ( $atts['height_img'] ) {
	$css_width_height .= 'height:' . esc_attr( $atts['height_img'] ) . ';';
}
if ( $css_width_height ) {
	$css_custom .= $id_team_members . '.penci-teammb-s2 .penci-teammb-img,';
	$css_custom .= $id_team_members . '.penci-teammb-s1 .penci-teammb-img{ ' . $css_width_height . ' }';
}

$css_row_gap = '';
if ( $atts['row_gap'] ) {
	$css_row_gap .= 'grid-row-gap:' . esc_attr( $atts['row_gap'] ) . ';';
}
if ( $atts['col_gap'] ) {
	$css_row_gap .= 'grid-column-gap:' . esc_attr( $atts['col_gap'] ) . ';';
}
if ( $css_row_gap ) {
	$css_custom .= $id_team_members . '.penci-teammb-bsc .pencisc-grid{ ' . $css_row_gap . ' }';
}

// Name
if ( $atts['team_name_color'] ) {
	$css_custom .= $id_team_members . '.penci-teammb-bsc .penci-team_member_name{ color:' . esc_attr( $atts['team_name_color'] ) . '; }';
}
if ( $atts['team_name_martop'] ) {
	$css_custom .= $id_team_members . '.penci-teammb-bsc .penci-team_member_name{ margin-top:' . esc_attr( $atts['team_name_martop'] ) . '; }';
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $atts['team_name_size'],
	'font_style' => $atts['team_name_typo'],
	'template'   => $id_team_members . '.penci-teammb-bsc .penci-team_member_name{ %s }',
) );

// Position
if ( $atts['team_pos_color'] ) {
	$css_custom .= $id_team_members2 . '.penci-teammb-bsc .penci-team_member_pos{ color:' . esc_attr( $atts['team_pos_color'] ) . '; }';
}
if ( $atts['team_pos_martop'] ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_team_members . '.penci-teammb-bsc .penci-team_member_pos{ margin-top:{{VALUE}}px; }', $atts['team_pos_martop'] );
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $atts['team_pos_size'],
	'font_style' => $atts['team_pos_typo'],
	'template'   => $id_team_members . '.penci-teammb-bsc .penci-team_member_pos{ %s }',
) );

// Description
if ( $atts['team_des_color'] ) {
	$css_custom .= $id_team_members2 . '.penci-teammb-bsc .penci-team_member_desc{ color:' . esc_attr( $atts['team_des_color'] ) . '; }';
}
if ( $atts['team_des_martop'] ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_team_members . '.penci-teammb-bsc .penci-team_member_desc{ margin-top:{{VALUE}}px; }', $atts['team_des_martop'] );
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $atts['team_des_size'],
	'font_style' => $atts['team_des_typo'],
	'template'   => $id_team_members . '.penci-teammb-bsc .penci-team_member_desc{ %s }',
) );
// Social
if ( $atts['team_social_color'] ) {
	$css_custom .= $id_team_members2 . '.penci-teammb-bsc .penci-social-item{ color:' . esc_attr( $atts['team_social_color'] ) . '; }';
}
if ( $atts['team_social_bgcolor'] ) {
	$css_custom .= $id_team_members2 . '.penci-teammb-bsc .penci-social-item{ background-color:' . esc_attr( $atts['team_social_bgcolor'] ) . '; }';
}
if ( $atts['team_social_hcolor'] ) {
	$css_custom .= $id_team_members2 . '.penci-teammb-bsc .penci-penci-social-item:hover{ color:' . esc_attr( $atts['team_social_hcolor'] ) . '; }';
}
if ( $atts['team_social_bghcolor'] ) {
	$css_custom .= $id_team_members2 . '.penci-teammb-bsc .penci-social-item:hover{ background-color:' . esc_attr( $atts['team_social_bghcolor'] ) . '; }';
}
if ( $atts['team_social_martop'] ) {
	$css_custom .= penci_extract_md_responsive_fsize( $id_team_members . '.penci-teammb-bsc .penci-social-item{ margin-top:{{VALUE}}px; }', $atts['team_social_martop'] );
}

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_team_members, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
