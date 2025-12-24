<?php
$builder_id      = penci_get_header_builder_id();
$header_data     = get_page_by_path( $builder_id, OBJECT, 'penci_builder' );
$header_id       = isset( $header_data->ID ) && $header_data->ID ? $header_data->ID : $builder_id;
$customizer_data = get_post_meta( $header_id, 'settings_content', true );
?>
<div data-builder-slug="<?php echo esc_attr( $builder_id ); ?>" id="pcbdhd_<?php echo esc_attr( $builder_id ); ?>"
     class="pc-wrapbuilder-header-inner penci-builder-id-<?php echo esc_attr( $builder_id ); ?>">
	<?php
	if ( is_customize_preview() && $header_id ) {
		$query['post']   = $header_id;
		$query['action'] = 'edit';
		$link            = add_query_arg( $query, admin_url( 'post.php' ) );
		echo '<a target="_blank" href="#" data-href="' . esc_url( $link ) . '" class="soledad-customizer-edit-link custom-link header-custom-link"><button><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"/></svg></button></a>';
	}

	if ( ( is_home() || is_front_page() ) && get_theme_mod( 'penci_home_h1content' ) ) {
		echo '<h1 class="penci-hide-tagupdated">' . get_theme_mod( 'penci_home_h1content' ) . '</h1>';
	}

	load_template( PENCI_BUILDER_PATH . 'template/desktop-sticky-wrapper.php', true, [ 'header_data' => $customizer_data ] );
	$overlap           = penci_builder_validate_mod( $customizer_data, 'penci_header_overlap_setting' );
	$header_width      = penci_builder_validate_mod( $customizer_data, 'penci_header_wrap_all', 'normal-width' );
	$header_shadow     = penci_builder_validate_mod( $customizer_data, 'penci_header_shadow' );
	$header_wrap_width = penci_builder_validate_mod( $customizer_data, 'penci_header_wrap_width' );
	$classes           = [];
	$classes[]         = 'penci_header penci-header-builder main-builder-header';
	$classes[]         = 'enable' == $overlap ? 'penci_header_overlap' : '';
	$classes[]         = 'enable' == $header_width ? 'container' : 'normal';
	$classes[]         = 'enable' == $header_shadow ? 'shadow-enable' : 'no-shadow';
	$classes[]         = 'enable' == $header_width && $header_wrap_width ? $header_wrap_width : '';
	$classes[]         = 'enable' == $header_width ? 'pchb-boxed-layout' : '';
	?>
    <div class="<?php echo implode( ' ', $classes ); ?>">
		<?php
		$rows = penci_builder_validate_mod( $customizer_data, 'penci_hb_arrange_bar', 'topblock,top,mid,bottom,bottomblock' );

		$rows = explode( ',', $rows );

		foreach ( $rows as $row ) {
			if ( ( ! empty( $row ) && penci_can_render_header( 'desktop', $row ) ) || is_customize_preview() ) {
				load_template( PENCI_BUILDER_PATH . 'template/desktop-' . $row . '.php', true, [ 'header_data' => $customizer_data ] );
			}
		}
		?>
    </div>
	<?php
	load_template( PENCI_BUILDER_PATH . 'template/mobile-builder.php', true, [ 'header_data' => $customizer_data ] );
	load_template( PENCI_BUILDER_PATH . 'template/mobile-menu.php', true, [ 'header_data' => $customizer_data ] );
	?>
</div>