<?php
$settings    = vc_map_get_attributes( $this->getShortcode(), $atts );
$heading_tag = $settings['heading_markup'];
$align       = $settings['heading_align'];
$block_id    = Penci_Vc_Helper::get_unique_id_block( 'pc_archive_taxonomy' );
$css_class   = vc_shortcode_custom_css_class( $settings['css'] );
?>
    <div id="<?php echo $block_id; ?>" class="archive-box pcab-abox <?php echo $css_class; ?>">
        <div class="title-bar align-<?php echo $align; ?>">
			<?php
			if ( is_day() ) :
				if ( penci_get_setting( 'penci_trans_daily_archives' ) ):
					echo '<span>';
					echo penci_get_setting( 'penci_trans_daily_archives' );
					echo ' </span>';
				endif;
				printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">%s</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), get_the_date() );
            elseif ( is_month() ) :
				if ( penci_get_setting( 'penci_trans_monthly_archives' ) ):
					echo '<span>';
					echo penci_get_setting( 'penci_trans_monthly_archives' );
					echo ' </span>';
				endif;
				printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">%s</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), get_the_date( _x( 'F Y', 'monthly archives date format', 'soledad' ) ) );
            elseif ( is_year() ) :
				if ( penci_get_setting( 'penci_trans_yearly_archives' ) ):
					echo '<span>';
					echo penci_get_setting( 'penci_trans_yearly_archives' );
					echo ' </span>';
				endif;
				printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">%s</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), get_the_date( _x( 'Y', 'yearly archives date format', 'soledad' ) ) );
            elseif ( is_author() ) :
				echo '<span>';
				echo penci_get_setting( 'penci_trans_author' );
				echo ' </span>';
				printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">%s</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), get_userdata( get_query_var( 'author' ) )->display_name );
            elseif ( is_category() ):
				if ( ! get_theme_mod( 'penci_remove_cat_words' ) ): ?>
                    <span><?php echo penci_get_setting( 'penci_trans_category' ); ?></span>
				<?php endif;
				printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">%s</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), single_cat_title( '', false ) );
            elseif ( is_tag() ):
				if ( ! get_theme_mod( 'penci_remove_tag_words' ) ): ?>
                    <span><?php echo penci_get_setting( 'penci_trans_tag' ); ?></span>
				<?php endif;
				printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">%s</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), single_tag_title( '', false ) );
            elseif ( is_search() ):
				echo '<span>' . penci_get_setting( 'penci_trans_search_results_for' ) . '</span> ';
				printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">"%s"</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), get_search_query() );
            elseif ( is_tax() ) :
				the_archive_title( '<' . $heading_tag . ' class="page-title">', '</' . $heading_tag . '>' );
			else :
				echo '<' . $heading_tag . ' class="page-title">';
				echo penci_get_setting( 'penci_trans_archives' );
				echo '</' . $heading_tag . '>';
			endif;
			?>
        </div>
    </div>
<?php
$css_custom = '';
$block_id   = '#' . $block_id;
if ( $settings['heading_line'] ) {
	$css_custom .= $block_id . ' .pcab-abox .title-bar:after{display:none}';
	$css_custom .= $block_id . ' .pcab-abox .title-bar{padding:0;margin:0;}';
}

if ( $settings['main_text_color'] ) {
	$css_custom .= $block_id . ' .archive-box .page-title,'. $block_id . ' .archive-box span{color:' . $settings['text_color'] . '!important;}';
}
if ( $settings['text_color'] ) {
	$css_custom .= $block_id . ' .archive-box span{color:' . $settings['text_color'] . '!important;}';
}
if ( $settings['heading-line-color'] ) {
	$css_custom .= $block_id . ' .pcab-abox .title-bar:after{background-color:' . $settings['heading-line-color'] . ';}';
}
if ( $settings['heading-line-spacing'] ) {
	$css_custom .= $block_id . ' .pcab-abox .title-bar{padding-bottom:' . $settings['heading-line-spacing'] . 'px;}';
}
if ( $settings['heading-line-w'] ) {
	$css_custom .= $block_id . ' .pcab-abox .title-bar:after{width:' . $settings['heading-line-w'] . 'px!important;}';
	$css_custom .= $block_id . ' .pcab-abox .title-bar.align-center:after{margin-left:calc(' . $settings['heading-line-w'] . 'px / 2 * -1)}';
}
if ( $settings['heading-line-h'] ) {
	$css_custom .= $block_id . ' .pcab-abox .title-bar:after{height:' . $settings['heading-line-h'] . 'px!important;}';
}
if ( $settings['responsive_spacing'] ) {
	$css_custom .= penci_extract_spacing_style( $block_id, $settings['responsive_spacing'] );
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $settings['main_text_size'],
	'font_style' => $settings['main_text_font'],
	'template'   => $block_id . '  .archive-box .page-title{ %s }',
) );
if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
