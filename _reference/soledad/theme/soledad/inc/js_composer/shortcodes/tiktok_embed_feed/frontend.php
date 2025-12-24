<?php
$settings = vc_map_get_attributes( $this->getShortcode(), $atts );
$username = $settings['username'] ? $settings['username'] : '';
wp_enqueue_script( 'penci_tiktok_embed' );
$id_tiktok = Penci_Vc_Helper::get_unique_id_block( 'penci_tiktok_feed' );
?>
    <div id="<?php echo $id_tiktok; ?>" class="pc-tiktok-embed-feed-el">
		<?php
		Penci_Vc_Helper::markup_block_title( $atts );
		if ( $username ) {
			wp_enqueue_script( 'penci_tiktok_embed' );
			?>
            <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@<?php echo esc_attr( $username ); ?>"
                        data-unique-id="<?php echo esc_attr( $username ); ?>"
                        data-embed-type="creator"
                        style="width: 100%;">
                <section><a target="_blank"
                            href="https://www.tiktok.com/@<?php echo esc_attr( $username ); ?>">@<?php echo esc_attr( $username ); ?></a>
                </section>
            </blockquote>
			<?php
		} else {
			_e( 'Please enter your Tiktok username', 'soledad' );
		}
		?>
    </div>
<?php
$css_custom = Penci_Vc_Helper::get_heading_block_css( $id_tiktok, $atts );

if ( $atts['width'] ) {
	$css_custom = penci_extract_md_responsive_fsize( $id_tiktok . ' .tiktok-embed{max-width:{{VALUE}}px}', $atts['width'] );
}

if ( $atts['content_horizontal_position'] ) {
	$css_custom = $id_tiktok . ' .tiktok-embed{' . $atts['content_horizontal_position'] . '}';
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
