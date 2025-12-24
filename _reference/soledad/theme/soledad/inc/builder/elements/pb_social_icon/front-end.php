<?php 
$header_data    = $args['header_data'];
$style     = penci_builder_validate_mod( $header_data, 'penci_header_pb_social_icon_section_icon_style', 'simple' );
$classes   = [];
$classes[] = 'social-icon-style penci-social-' . $style;
$classes[] = 'penci-social-' . penci_builder_validate_mod( $header_data, 'penci_header_pb_social_icon_section_icon_color', 'textaccent' );
?>
<div class="header-social desktop-social penci-builder-element">
    <div class="inner-header-social <?php echo implode( ' ', $classes ); ?>">
		<?php
		$dataref     = penci_builder_validate_mod( $header_data, 'penci_header_pb_social_icon_section_penci_rel_type_social' ) ? penci_builder_validate_mod( $header_data, 'penci_header_pb_social_icon_section_penci_rel_type_social' ) : 'noreferrer';
		$data_return = str_replace( '_', ' ', $dataref );
		if ( 'none' != $data_return ) {
			$rel = ' rel="' . $data_return . '"';
		} else {
			$rel = '';
		}


		$social_data = penci_social_media_array();
		foreach ( $social_data as $name => $sdata ) {
			if ( $sdata[0] ) {
				$icon_html = penci_icon_by_ver( $sdata[1] );
				?>
                <a href="<?php echo esc_url( do_shortcode( $sdata[0] ) ); ?>"
                   aria-label="<?php echo ucwords( $name ); ?>" <?php echo $rel; ?>
                   target="_blank"><?php echo $icon_html; ?></a>
				<?php
			}
		}
		?>
    </div>
</div>
