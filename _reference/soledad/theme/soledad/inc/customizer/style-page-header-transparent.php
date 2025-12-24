<?php
if ( ! function_exists( 'pencidesign_customizer_css_page_header_transparent' ) ):
	function pencidesign_customizer_css_page_header_transparent() {
		$post_meta_df   = array(
			'tran_slogan_color'            => '',
			'tran_slogan_line_color'       => '',
			'tran_social_color'            => '',
			'tran_social_color_hover'      => '',
			'tran_main_bar_nav_color'      => '',
			'tran_bar_color_active'        => '',
			'tran_main_bar_padding_color'  => '',
			'tran_main_bar_search_magnify' => '',
			'tran_main_bar_close_color'    => '',
		);
		$header_options = get_post_meta( get_the_ID(), 'penci_pmeta_page_header', true );
		$header_options = wp_parse_args( $header_options, $post_meta_df );

		$slogan_color            = $header_options['tran_slogan_color'] ? $header_options['tran_slogan_color'] : get_theme_mod( 'penci_header_tran_slogan_color' );
		$slogan_line_color       = $header_options['tran_slogan_line_color'] ? $header_options['tran_slogan_line_color'] : get_theme_mod( 'penci_header_tran_slogan_line_color' );
		$social_color            = $header_options['tran_social_color'] ? $header_options['tran_social_color'] : get_theme_mod( 'penci_header_tran_social_color' );
		$social_color_hover      = $header_options['tran_social_color_hover'] ? $header_options['tran_social_color_hover'] : get_theme_mod( 'penci_header_tran_social_color_hover' );
		$main_bar_nav_color      = $header_options['tran_main_bar_nav_color'] ? $header_options['tran_main_bar_nav_color'] : get_theme_mod( 'penci_tran_main_bar_nav_color' );
		$bar_color_active        = $header_options['tran_bar_color_active'] ? $header_options['tran_bar_color_active'] : get_theme_mod( 'penci_tran_main_bar_color_active' );
		$bar_padding_color       = $header_options['tran_main_bar_padding_color'] ? $header_options['tran_main_bar_padding_color'] : get_theme_mod( 'penci_tran_main_bar_padding_color' );
		$main_bar_search_magnify = $header_options['tran_main_bar_search_magnify'] ? $header_options['tran_main_bar_search_magnify'] : get_theme_mod( 'penci_tran_main_bar_search_magnify' );
		$main_bar_close_color    = $header_options['tran_main_bar_close_color'] ? $header_options['tran_main_bar_close_color'] : get_theme_mod( 'penci_tran_main_bar_close_color' );

		?>

		@media only screen and (min-width: 961px){
		<?php if ( $slogan_color ): ?>
			.penci-header-trans .header-slogan .header-slogan-text{ color:  <?php echo esc_attr( $slogan_color ); ?> !important; }
		<?php endif; ?>
		<?php if ( $slogan_line_color ): ?>
			.penci-header-trans .header-slogan .header-slogan-text:before, .penci-header-trans .header-slogan .header-slogan-text:after { background:  <?php echo esc_attr( $slogan_line_color ); ?>; }
		<?php endif; ?>

		<?php if ( $social_color ): ?>
			.penci-header-trans #navigation.sticky:not(.sticky-active) .header-social a i,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .header-social a i,
			.penci-header-trans #navigation.sticky:not(.sticky-active) .main-nav-social a,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .main-nav-social a {   color: <?php echo esc_attr( $social_color ); ?>; }
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .penci-menuhbg-toggle .lines-button:after,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .penci-menuhbg-toggle .penci-lines:before,
			.penci-header-trans #navigation.sticky:not(.sticky-active) .penci-menuhbg-toggle .lines-button:after,
			.penci-header-trans #navigation.sticky:not(.sticky-active) .penci-menuhbg-toggle .penci-lines:before,
			.penci-header-trans #navigation.sticky:not(.sticky-active) .penci-menuhbg-toggle .penci-lines:after,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .penci-menuhbg-toggle .penci-lines:after {   background-color: <?php echo esc_attr( $social_color ); ?>; }
		<?php endif; ?>
		<?php if ( $social_color_hover ): ?>
			.penci-header-trans .header-social a:hover i, .penci-header-trans .header-social a:hover i, .penci-header-trans #navigation.sticky:not(.sticky-active) .main-nav-social a:hover,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .main-nav-social a:hover {   color: <?php echo esc_attr( $social_color_hover ); ?>; }
			.penci-header-trans #navigation.sticky:not(.sticky-active) .penci-menuhbg-toggle:hover .lines-button:after,
			.penci-header-trans #navigation.sticky:not(.sticky-active) .penci-menuhbg-toggle:hover .penci-lines:before,
			.penci-header-trans #navigation.sticky:not(.sticky-active) .penci-menuhbg-toggle:hover .penci-lines:after,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .penci-menuhbg-toggle:hover .lines-button:after,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .penci-menuhbg-toggle:hover .penci-lines:before,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .penci-menuhbg-toggle:hover .penci-lines:after {   background-color: <?php echo esc_attr( $social_color_hover ); ?>; }
		<?php endif; ?>
		<?php if ( $main_bar_nav_color ): ?>
			.penci-header-trans #navigation.sticky:not(.sticky-active) .menu > li > a, .penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation .menu > li > a { color:  <?php echo esc_attr( $main_bar_nav_color ); ?>; }
		<?php endif; ?>
		<?php if ( $bar_color_active ): ?>
			.penci-header-trans #navigation.sticky:not(.sticky-active) .menu > li > a:hover,
			.penci-header-trans #navigation.sticky:not(.sticky-active) .menu > li.current-menu-item > a,
			.penci-header-trans #navigation.sticky:not(.sticky-active) .menu > li.current_page_item > a,
			.penci-header-trans #navigation.sticky:not(.sticky-active) .menu > li:hover > a,
			.penci-header-trans #navigation.sticky:not(.sticky-active) .menu > li.current-menu-ancestor > a,
			.penci-header-trans #navigation.sticky:not(.sticky-active) .menu > li.current-menu-item > a,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation .menu > li > a:hover,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation .menu > li.current-menu-item > a,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation .menu > li.current_page_item > a,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation .menu > li:hover > a,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation .menu li.current-menu-ancestor > a,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation .menu > li.current-menu-item > a { color:  <?php echo esc_attr( $bar_color_active ); ?>; }
			.penci-header-trans #navigation.sticky:not(.sticky-active) ul.menu > li > a:before,
			.penci-header-trans #navigation.sticky:not(.sticky-active) .menu > ul > li > a:before,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation ul.menu > li > a:before,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation .menu > ul > li > a:before { background: <?php echo esc_attr( $bar_color_active ); ?>; }
		<?php endif; ?>
		<?php if ( $bar_padding_color ): ?>
			.penci-header-trans #navigation.menu-item-padding:not(.sticky-active) .menu > li > a:hover,
			.penci-header-trans #navigation.menu-item-padding:not(.sticky-active) .menu > li:hover > a,
			.penci-header-trans #navigation.menu-item-padding:not(.sticky-active) .menu > li.current-menu-item > a,
			.penci-header-trans #navigation.menu-item-padding:not(.sticky-active) .menu > li.current_page_item > a,
			.penci-header-trans #navigation.menu-item-padding:not(.sticky-active) .menu > li.current-menu-ancestor > a,
			.penci-header-trans #navigation.menu-item-padding:not(.sticky-active) .menu > li.current-menu-item > a,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation.menu-item-padding .menu > li > a:hover,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation.menu-item-padding .menu > li:hover > a,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation.menu-item-padding .menu > li.current-menu-item > a,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation.menu-item-padding .menu > li.current_page_item > a,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation.menu-item-padding .menu > li.current-menu-ancestor > a,
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation.menu-item-padding .menu > li.current-menu-item > a { background-color:  <?php echo esc_attr( $bar_padding_color ); ?>; }
		<?php endif; ?>
		<?php if ( $main_bar_search_magnify ): ?>
            .penci-header-trans #navigation.sticky:not(.sticky-active) .top-search-classes a.cart-contents,
            .penci-header-trans #navigation.sticky:not(.sticky-active) .top-search-classes > a,
            .penci-header-trans #navigation.sticky:not(.sticky-active) .button-menu-mobile,
            .penci-header-trans .sticky-wrapper:not( .is-sticky ) .top-search-classes > a,
            .penci-header-trans .sticky-wrapper:not( .is-sticky ) .top-search-classes a.cart-contents,
            .penci-header-trans .sticky-wrapper:not( .is-sticky ) #navigation .button-menu-mobile { color: <?php echo esc_attr( $main_bar_search_magnify ); ?>; }
		<?php endif; ?>
		<?php if ( $main_bar_close_color ): ?>
			.penci-header-trans #navigation.sticky:not(.sticky-active) .show-search a.close-search, .penci-header-trans .sticky-wrapper:not( .is-sticky ) .show-search a.close-search{ color: <?php echo esc_attr( $main_bar_close_color ); ?>; }
			.penci-header-trans #navigation.sticky:not(.sticky-active) .show-search form.pc-searchform input.search-input::-webkit-input-placeholder{ color: <?php echo esc_attr( $main_bar_close_color ); ?>; }
			.penci-header-trans #navigation.sticky:not(.sticky-active) .show-search form.pc-searchform input.search-input:-moz-placeholder{ color: <?php echo esc_attr( $main_bar_close_color ); ?>; opacity: 1;}
			.penci-header-trans #navigation.sticky:not(.sticky-active) .show-search form.pc-searchform input.search-input::-moz-placeholder{ color: <?php echo esc_attr( $main_bar_close_color ); ?>; opacity: 1;}
			.penci-header-trans #navigation.sticky:not(.sticky-active) .show-search form.pc-searchform input.search-input:-ms-input-placeholder{ color: <?php echo esc_attr( $main_bar_close_color ); ?>; }
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .show-search form.pc-searchform input.search-input::-webkit-input-placeholder{ color: <?php echo esc_attr( $main_bar_close_color ); ?>; }
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .show-search form.pc-searchform input.search-input:-moz-placeholder { color: <?php echo esc_attr( $main_bar_close_color ); ?>; opacity: 1;}
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .show-search form.pc-searchform input.search-input::-moz-placeholder {color: <?php echo esc_attr( $main_bar_close_color ); ?>; opacity: 1; }
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .show-search form.pc-searchform input.search-input:-ms-input-placeholder { color: <?php echo esc_attr( $main_bar_close_color ); ?>; }
			.penci-header-trans #navigation.sticky:not(.sticky-active) .show-search form.pc-searchform input.search-input{ color: <?php echo esc_attr( $main_bar_close_color ); ?>; }
			.penci-header-trans .sticky-wrapper:not( .is-sticky ) .show-search form.pc-searchform input.search-input{ color: <?php echo esc_attr( $main_bar_close_color ); ?>; }
		<?php endif; ?>
		}
		<?php
	}
endif;
