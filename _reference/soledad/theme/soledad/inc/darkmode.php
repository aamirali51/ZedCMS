<?php
if ( ! function_exists( 'penci_dm_button' ) ) {
	function penci_dm_button( $style = 1, $customizer = true ) {

		if ( ! get_theme_mod( 'penci_dms_enable' ) ) {
			return false;
		}

		if ( $customizer ) {
			$style = get_theme_mod( 'penci_dms_style', '3' );
		}

		return '<div class="pc_dm_mode style_' . $style . '">
						<label class="pc_dm_switch">
							<input type="checkbox" class="pc_dark_mode_toggle" aria-label="Darkmode Switcher">
							<span class="slider round"></span>
						</label>
					</div>';
	}
}

if ( ! function_exists( 'penci_render_dark_style' ) ) {
	add_action( 'wp_enqueue_scripts', 'penci_render_dark_style' );
	function penci_render_dark_style() {
		wp_register_style( 'penci-dark-style', PENCI_SOLEDAD_URL . '/dark.min.css', array(), PENCI_SOLEDAD_VERSION );
		wp_register_script( 'penci-dark', PENCI_SOLEDAD_URL . '/js/darkmode.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION );
		if ( get_theme_mod( 'penci_dms_enable' ) ) {
			wp_enqueue_style( 'penci-dark-style' );
			wp_enqueue_script( 'penci-dark' );
			wp_localize_script(
				'penci-dark',
				'penci_dark',
				array(
					'auto_by'   => (bool) get_theme_mod( 'penci_dms_auto_by' ),
					'darktheme' => (bool) get_theme_mod( 'penci_enable_dark_layout' ),
					'darkmode'  => (bool) get_theme_mod( 'penci_dms_dark_default' ),
				)
			);
		}
	}
}

if ( ! function_exists( 'penci_render_dmcheck' ) ) {
	add_action( 'wp_footer', 'penci_render_dmcheck', 10 );
	function penci_render_dmcheck() {
		if ( get_theme_mod( 'penci_dms_enable' ) ) {
			echo '<script id="penci-dm-checking" type="text/javascript">';
			echo file_get_contents( PENCI_SOLEDAD_DIR . '/js/darkmode-loading.js' );
			echo '</script>';
		}
	}
}

if ( ! function_exists( 'penci_dm_header_content' ) ) {

	add_action( 'penci_top_search', 'penci_dm_header_content' );

	function penci_dm_header_content() {
		echo penci_dm_button();
	}
}

if ( ! function_exists( 'penci_adjust_hexcolor' ) ) {
	function penci_adjust_hexcolor( $hexCode, $adjustPercent ) {
		$hexCode = ltrim( $hexCode, '#' );

		if ( strlen( $hexCode ) == 3 ) {
			$hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
		}

		$hexCode = array_map( 'hexdec', str_split( $hexCode, 2 ) );

		foreach ( $hexCode as & $color ) {
			$adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
			$adjustAmount    = ceil( $adjustableLimit * $adjustPercent );

			$color = str_pad( dechex( $color + $adjustAmount ), 2, '0', STR_PAD_LEFT );
		}

		return '#' . implode( $hexCode );
	}
}
if ( ! function_exists( 'penci_darkmode_css' ) ) {
	add_action( 'soledad_theme/custom_dark_css', 'penci_darkmode_css' );
	function penci_darkmode_css() {
		$bgdark         = get_theme_mod( 'penci_dm_bg_color_dark' ) ? get_theme_mod( 'penci_dm_bg_color_dark' ) : '#000000';
		$bgdark_lighten = penci_adjust_hexcolor( $bgdark, 0.1 );
		$bgdark_darker  = penci_adjust_hexcolor( $bgdark, - 0.1 );
		$accent_dark    = get_theme_mod( 'penci_accent_color_dark', get_theme_mod( 'penci_color_accent', '#6eb48c' ) );
		$link           = get_theme_mod( 'penci_link_color_dark', '#fff' );
		$linkh          = get_theme_mod( 'penci_link_hcolor_dark', $accent_dark );
		$text           = get_theme_mod( 'penci_dm_text_color_dark', '#fff' );
		$meta           = get_theme_mod( 'penci_dm_meta_color_dark', '#999999' );
		$border         = get_theme_mod( 'penci_dm_border_color_dark', '#313131' );
		$heading        = get_theme_mod( 'penci_heading_color_dark', 'rgba(255,255,255,0.9)' );
		$smborder       = penci_adjust_hexcolor( $border, 0.05 );

		// customizer style button
		$btn_bg_color  = get_theme_mod( 'penci_dm_bg_color', 'rgba(0, 0, 0, .1)' );
		$btn_d_color   = get_theme_mod( 'penci_dm_d_color', '#666' );
		$btn_dbg_color = get_theme_mod( 'penci_dm_d_bgcolor', '#fff' );
		$btn_n_color   = get_theme_mod( 'penci_dm_n_color', 'var(--pctext-cl)' );
		$btn_nbg_color = get_theme_mod( 'penci_dm_n_bgcolor', 'var(--pcbg-cl)' );
		?>
		body {
		--pcdm_btnbg: <?php echo $btn_bg_color; ?>;
		--pcdm_btnd: <?php echo $btn_d_color; ?>;
		--pcdm_btndbg: <?php echo $btn_dbg_color; ?>;
		--pcdm_btnn: <?php echo $btn_n_color; ?>;
		--pcdm_btnnbg: <?php echo $btn_nbg_color; ?>;
		}
		body.pcdm-enable {
		--pcbg-cl: <?php echo $bgdark; ?>;
		--pcbg-l-cl: <?php echo $bgdark_lighten; ?>;
		--pcbg-d-cl: <?php echo $bgdark_darker; ?>;
		--pctext-cl: <?php echo $text; ?>;
		--pcborder-cl: <?php echo $border; ?>;
		--pcborders-cl: <?php echo $smborder; ?>;
		--pcheading-cl: <?php echo $heading; ?>;
		--pcmeta-cl: <?php echo $meta; ?>;
		--pcl-cl: <?php echo $link; ?>;
		--pclh-cl: <?php echo $linkh; ?>;
		--pcaccent-cl: <?php echo $accent_dark; ?>;
		background-color: var(--pcbg-cl);
		color: var(--pctext-cl);
		}
		body.pcdark-df.pcdm-enable.pclight-mode {
		--pcbg-cl: #fff;
		--pctext-cl: #313131;
		--pcborder-cl: #dedede;
		--pcheading-cl: #313131;
		--pcmeta-cl: #888888;
		--pcaccent-cl: <?php echo $accent_dark; ?>;
		}
		<?php
		// Color single
		$single_style = penci_get_single_style();
		if ( ! get_theme_mod( 'penci_move_title_bellow' ) ) {
			$single_color_title    = get_theme_mod( 'penci_single_color_title_s568' );
			$single_color_subtitle = get_theme_mod( 'penci_single_color_subtitle_s568' );
			$single_color_cat      = get_theme_mod( 'penci_single_color_cat_s568' );
			$single_color_meta     = get_theme_mod( 'penci_single_color_meta_s568' );
			if ( $single_color_title && in_array( $single_style, array( 'style-5', 'style-6', 'style-8' ) ) ) {
				echo '@media only screen and (min-width: 768px){ body.pcdm-enable .container-single.penci-single-' . $single_style . ' .single-header .post-title { color: var(--pctext-cl); } }';
			}
			if ( $single_color_subtitle && in_array( $single_style, array( 'style-5', 'style-6', 'style-8' ) ) ) {
				echo '@media only screen and (min-width: 768px){ body.pcdm-enable .container-single.penci-single-' . $single_style . ' .single-header .penci-psub-title{ color: var(--pctext-cl); } }';
			}
			if ( $single_color_cat && in_array( $single_style, array( 'style-5', 'style-6', 'style-8' ) ) ) {
				echo '@media only screen and (min-width: 768px){ body.pcdm-enable .container-single.penci-single-' . $single_style . ' .penci-single-cat .cat > a.penci-cat-name { color: var(--pctext-cl); } }';
			}
			if ( $single_color_meta && in_array( $single_style, array( 'style-5', 'style-6', 'style-8' ) ) ) {
				echo '@media only screen and (min-width: 768px){';
				echo 'body.pcdm-enable .penci-single-' . $single_style . '.penci-header-text-white .post-box-meta-single span,';
				echo 'body.pcdm-enable .penci-single-' . $single_style . '.penci-header-text-white .header-standard .author-post span a{ color: var(--pctext-cl); }';
				echo '}';
				if ( get_theme_mod( 'penci_single_accent_color' ) ) {
					echo 'body.pcdm-enable .penci-single-' . $single_style . '.penci-header-text-white .header-standard .author-post span a:hover{ color: var(--pctext-cl); }';
				}
			}
		}
		if ( 'style-10' == $single_style ) {
			if ( get_theme_mod( 'penci_single_color_bread_s10' ) ) {
				echo 'body.pcdm-enable .penci-single-style-10 .penci-container-inside.penci-breadcrumb i,body.pcdm-enable .penci-single-style-10  .container.penci-breadcrumb i,
				body.pcdm-enable .penci-single-style-10 .penci-container-inside.penci-breadcrumb a,';
				echo 'body.pcdm-enable .penci-single-style-10 .penci-container-inside.penci-breadcrumb span{ color: var(--pctext-cl) }';
			}
			if ( get_theme_mod( 'penci_single_color_title_s10' ) ) {
				echo 'body.pcdm-enable .penci-single-style-10.penci-header-text-white .header-standard .post-title,';
				echo 'body.pcdm-enable .penci-single-style-10.penci-header-text-white .header-standard .pc_titlebig_standard,';
				echo 'body.pcdm-enable .penci-single-style-10.penci-header-text-white .header-standard h2 a';
				echo 'body.pcdm-enable .penci-single-style-10.penci-header-text-white .pc_titlebig_standard a';
				echo '{ color: ' . get_theme_mod( 'penci_single_color_title_s10' ) . ' }';
			}
			if ( get_theme_mod( 'penci_single_color_subtitle_s10' ) ) {
				echo 'body.pcdm-enable .penci-single-style-10.penci-header-text-white .header-standard h2.penci-psub-title{ color: var(--pctext-cl) }';
			}
			if ( get_theme_mod( 'penci_single_color_cat_s10' ) ) {
				echo 'body.pcdm-enable .penci-single-style-10.penci-header-text-white .penci-standard-cat  .cat > a.penci-cat-name { color: var(--pctext-cl); }';
			}
			if ( get_theme_mod( 'penci_single_color_meta_s10' ) ) {
				echo 'body.pcdm-enable .penci-single-style-10.penci-header-text-white .post-box-meta-single span,';
				echo 'body.pcdm-enable .penci-single-style-10.penci-header-text-white .header-standard .author-post span a';
				echo '{ color: ' . get_theme_mod( 'penci_single_color_meta_s10' ) . ' }';
				if ( get_theme_mod( 'penci_single_accent_color' ) ) {
					echo 'body.pcdm-enable .penci-single-style-10.penci-header-text-white .header-standard .author-post span a:hover{ color: var(--pctext-cl); }';
				}
			}
		}
		$bquote_text_color   = get_theme_mod( 'penci_bquote_text_color' );
		$bquote_author_color = get_theme_mod( 'penci_bquote_author_color' );
		$bquote_bgcolor      = get_theme_mod( 'penci_bquote_bgcolor' );
		$bquote_border_color = get_theme_mod( 'penci_bquote_border_color' );
		if ( $bquote_text_color ) {
			echo 'body.pcdm-enable .post-entry blockquote, body.pcdm-enable .post-entry blockquote p, body.pcdm-enable .wpb_text_column blockquote, body.pcdm-enable .wpb_text_column blockquote p{ color: var(--pctext-cl) }';
		}
		if ( $bquote_author_color ) {
			echo 'body.pcdm-enable .post-entry blockquote cite, body.pcdm-enable .post-entry blockquote .author, body.pcdm-enable .wpb_text_column blockquote cite, body.pcdm-enable .wpb_text_column blockquote .author, body.pcdm-enable.woocommerce .page-description blockquote cite, body.pcdm-enable.woocommerce .page-description blockquote .author{ color: var(--pctext-cl) }';
			echo 'body.pcdm-enable .post-entry blockquote .author span:after, body.pcdm-enable .wpb_text_column blockquote .author span:after, body.pcdm-enable.woocommerce .page-description blockquote .author span:after{ background-color:var(--pcbg-cl) }';
		}
		if ( $bquote_bgcolor ) {
			echo 'body.pcdm-enable .post-entry.blockquote-style-2 blockquote{ background-color: var(--pcbg-cl) }';
		}
		if ( $bquote_border_color ) {
			echo 'body.pcdm-enable .post-entry.blockquote-style-2 blockquote:before{ background-color: var(--pcbg-cl) }';
			echo 'body.pcdm-enable .post-entry blockquote::before, body.pcdm-enable .wpb_text_column blockquote::before, body.pcdm-enable.woocommerce .page-description blockquote:before{ color: var(--pctext-cl) }';
		}
		if ( get_theme_mod( 'penci_menu_hbg_show' ) || get_theme_mod( 'penci_vertical_nav_show' ) || get_theme_mod( 'pchdbd_all' ) || get_theme_mod( 'pchdbd_homepage' ) || get_theme_mod( 'pchdbd_archive' ) || get_theme_mod( 'pchdbd_post' ) || get_theme_mod( 'pchdbd_page' ) || get_theme_mod( 'pchdbd_woocommerce' ) ) :
			$mhbg_icon_toggle_color  = get_theme_mod( 'penci_mhbg_icon_toggle_color' );
			$mhbg_icon_toggle_hcolor = get_theme_mod( 'penci_mhbg_icon_toggle_hcolor' );
			$penci_mhbg_mobilecl     = get_theme_mod( 'penci_mhbg_mobilecl' );
			$penci_mhbg_mobilebgcl   = get_theme_mod( 'penci_mhbg_mobilebgcl' );
			if ( $penci_mhbg_mobilebgcl ) {
				echo 'body.pcdm-enable .penci-vernav-toggle:before{border-top-color:var(--pcborder-cl);}';
			}
			if ( $penci_mhbg_mobilecl ) {
				echo 'body.pcdm-enable .penci-vernav-toggle svg{fill:var(--pctext-cl)}';
			}
			if ( $mhbg_icon_toggle_color ) {
				echo 'body.pcdm-enable .penci-menuhbg-toggle .lines-button:after,body.pcdm-enable .penci-menuhbg-toggle .penci-lines:before, body.pcdm-enable .penci-menuhbg-toggle .penci-lines:after{ background-color: var(--pcbg-cl) }';
			}
			if ( $mhbg_icon_toggle_hcolor ) {
				echo 'body.pcdm-enable .penci-menuhbg-toggle:hover .lines-button:after, body.pcdm-enable .penci-menuhbg-toggle:hover .penci-lines:before, body.pcdm-enable .penci-menuhbg-toggle:hover .penci-lines:after{ background-color: var(--pcbg-cl) }';
			}
			$mhbg_bgcolor            = get_theme_mod( 'penci_mhbg_bgcolor' );
			$mhbg_textcolor          = get_theme_mod( 'penci_mhbg_textcolor' );
			$mhbg_closecolor         = get_theme_mod( 'penci_mhbg_closecolor' );
			$mhbg_closehover         = get_theme_mod( 'penci_mhbg_closehover' );
			$mhbg_bordercolor        = get_theme_mod( 'penci_mhbg_bordercolor' );
			$mhbgtitle_color         = get_theme_mod( 'penci_mhbgtitle_color' );
			$mhbgdesc_hcolor         = get_theme_mod( 'penci_mhbgdesc_hcolor' );
			$mhbgsearch_border       = get_theme_mod( 'penci_mhbg_search_border' );
			$mhbgsearch_border_hover = get_theme_mod( 'penci_mhbg_search_border_hover' );
			$mhbgsearch_color        = get_theme_mod( 'penci_mhbg_search_color' );
			$mhbgsearch_icon         = get_theme_mod( 'penci_mhbg_search_icon' );
			$mhbgaccent_color        = get_theme_mod( 'penci_mhbgaccent_color' );
			$mhbgaccent_hover_color  = get_theme_mod( 'penci_mhbgaccent_hover_color' );
			$mhbgfooter_color        = get_theme_mod( 'penci_mhbgfooter_color' );
			$mhbgicon_color          = get_theme_mod( 'penci_mhbgicon_color' );
			$mhbgicon_hover_color    = get_theme_mod( 'penci_mhbgicon_hover_color' );
			$mhbgicon_border         = get_theme_mod( 'penci_mhbgicon_border' );
			$mhbgicon_border_hover   = get_theme_mod( 'penci_mhbgicon_border_hover' );
			$mhbgicon_bg             = get_theme_mod( 'penci_mhbgicon_bg' );
			$mhbgicon_bg_hover       = get_theme_mod( 'penci_mhbgicon_bg_hover' );
			if ( $mhbg_bgcolor ) {
				echo 'body.pcdm-enable .penci-menu-hbg,body.pcdm-enable .penci-menu-hbg .penci-sidebar-content .widget-title{background-color:var(--pcbg-cl);}';
			}
			if ( $mhbg_closecolor ) {
				echo 'body.pcdm-enable .penci-menu-hbg-inner #penci-close-hbg:before, body.pcdm-enable .penci-menu-hbg-inner #penci-close-hbg:after{background-color:var(--pcbg-cl);}';
			}
			if ( $mhbg_closehover ) {
				echo 'body.pcdm-enable .penci-menu-hbg-inner #penci-close-hbg:hover:before, body.pcdm-enable .penci-menu-hbg-inner #penci-close-hbg:hover:after{background-color:var(--pcbg-cl);}';
			}
			if ( $mhbg_textcolor ) {
				echo 'body.pcdm-enable .penci-menu-hbg,body.pcdm-enable .penci-menu-hbg .about-widget .about-me-heading,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget select,body.pcdm-enable .penci-menu-hbg .widget select option,';
				echo 'body.pcdm-enable .penci-menu-hbg form.pc-searchform input.search-input{ color: var(--pctext-cl) }';
			}
			if ( $mhbg_bordercolor ) {
				echo 'body.pcdm-enable .penci-menu-hbg .widget ul li,body.pcdm-enable .penci-menu-hbg .menu li,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget-social a i,';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-home-popular-posts,';
				echo 'body.pcdm-enable .penci-menu-hbg #respond textarea,';
				echo 'body.pcdm-enable .penci-menu-hbg .wpcf7 textarea,';
				echo 'body.pcdm-enable .penci-menu-hbg #respond input,';
				echo 'body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=date], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=datetime], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=datetime-local], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=email], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=month], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=number], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=password], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=range], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=search], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=tel], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=text], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=time], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=url], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form input[type=week], body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form select, body.pcdm-enable .penci-menu-hbg div.wpforms-container .wpforms-form.wpforms-form textarea,';
				echo 'body.pcdm-enable .penci-menu-hbg .wpcf7 input,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget_wysija input,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget select,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget ul ul,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget .tagcloud a,';
				echo 'body.pcdm-enable .penci-menu-hbg #wp-calendar tbody td,';
				echo 'body.pcdm-enable .penci-menu-hbg #wp-calendar thead th,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget input[type="text"],';
				echo 'body.pcdm-enable .penci-menu-hbg .widget input[type="email"],';
				echo 'body.pcdm-enable .penci-menu-hbg .widget input[type="date"],';
				echo 'body.pcdm-enable .penci-menu-hbg .widget input[type="number"],';
				echo 'body.pcdm-enable .penci-menu-hbg .widget input[type="search"], body.pcdm-enable .widget input[type="password"], body.pcdm-enable .penci-menu-hbg form.pc-searchform input.search-input,';
				echo 'body.pcdm-enable .penci-vernav-enable.penci-vernav-poleft .penci-menu-hbg, body.pcdm-enable .penci-vernav-enable.penci-vernav-poright .penci-menu-hbg, body.pcdm-enable .penci-menu-hbg ul.sub-menu{border-color:var(--pcborder-cl);}';
			}
			if ( $mhbgtitle_color ) {
				echo 'body.pcdm-enable .penci-menu-hbg-inner .penci-hbg_sitetitle{ color:var(--pctext-cl);}';
			}
			if ( $mhbgdesc_hcolor ) {
				echo 'body.pcdm-enable .penci-menu-hbg-inner .penci-hbg_desc{ color:var(--pctext-cl);}';
			}
			if ( $mhbgsearch_border ) {
				echo 'body.pcdm-enable .penci-menu-hbg form.pc-searchform.penci-hbg-search-form input.search-input{ border-color:var(--pcborder-cl);}';
			}
			if ( $mhbgsearch_border_hover ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-hbg-search-form input.search-input:hover, body.pcdm-enable form.pc-searchform.penci-hbg-search-form input.search-input:hover, body.pcdm-enable form.pc-searchform.penci-hbg-search-form input.search-input:focus{ border-color:var(--pcborder-cl);}';
			}
			if ( $mhbgsearch_color ) {
				echo 'body.pcdm-enable form.pc-searchform.penci-hbg-search-form input.search-input{ color:var(--pctext-cl);}';
				echo 'body.pcdm-enable form.pc-searchform.penci-hbg-search-form input.search-input::-webkit-input-placeholder { color: var(--pctext-cl); }';
				echo 'body.pcdm-enable form.pc-searchform.penci-hbg-search-form input.search-input::-moz-placeholder { color:var(--pctext-cl); opacity: 1; }';
				echo 'body.pcdm-enable form.pc-searchform.penci-hbg-search-form input.search-input:-ms-input-placeholder { color: var(--pctext-cl); }';
				echo 'body.pcdm-enable form.pc-searchform.penci-hbg-search-form input.search-input:-moz-placeholder { color:var(--pctext-cl); opacity: 1; }';
			}
			if ( $mhbgsearch_icon ) {
				echo 'body.pcdm-enable form.pc-searchform.penci-hbg-search-form i{ color:var(--pctext-cl);}';
			}
			if ( $mhbgaccent_color ) {
				echo 'body.pcdm-enable body.pcdm-enable .penci-menu-hbg .menu li a,';
				echo 'body.pcdm-enable body.pcdm-enable .penci-menu-hbg .widget ul.side-newsfeed li .side-item .side-item-text h4 a,';
				echo 'body.pcdm-enable body.pcdm-enable .penci-menu-hbg #wp-calendar tbody td a,';
				echo 'body.pcdm-enable body.pcdm-enable .penci-menu-hbg .widget.widget_categories ul li,';
				echo 'body.pcdm-enable body.pcdm-enable .penci-menu-hbg .widget.widget_archive ul li, body.pcdm-enable .penci-menu-hbg .widget-social a i,';
				echo 'body.pcdm-enable body.pcdm-enable .penci-menu-hbg .widget-social a span,';
				echo 'body.pcdm-enable body.pcdm-enable .penci-menu-hbg .widget-social.show-text a span,';
				echo 'body.pcdm-enable body.pcdm-enable .penci-menu-hbg .widget a{ color:var(--pctext-cl);}';
			}
			if ( $mhbgaccent_hover_color ) {
				echo 'body.pcdm-enable .penci-menu-hbg .menu li a:hover,body.pcdm-enable .penci-menu-hbg .menu li a .indicator:hover';
				echo 'body.pcdm-enable .penci-menu-hbg .widget ul.side-newsfeed li .side-item .side-item-text h4 a:hover,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget a:hover,';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content .widget-social a:hover span,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget-social a:hover span,';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-tweets-widget-content .icon-tweets,';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-tweets-widget-content .tweet-intents a,';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-tweets-widget-content.tweet-intents span:after,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget-social.remove-circle a:hover i,';
				echo 'body.pcdm-enable .penci-menu-hbg #wp-calendar tbody td a:hover,';
				echo 'body.pcdm-enable .penci-menu-hbg a:hover {color:var(--pctext-cl);}';
				echo 'body.pcdm-enable .penci-menu-hbg .widget .tagcloud a:hover,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget-social a:hover i,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget .penci-user-logged-in .penci-user-action-links a:hover,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget input[type="submit"]:hover,';
				echo 'body.pcdm-enable .penci-menu-hbg .widget button[type="submit"]:hover{ color: #fff; background-color: var(--pcbg-cl); border-color: var(--pcborder-cl); }';
				echo 'body.pcdm-enable .penci-menu-hbg .about-widget .about-me-heading:before { border-color: var(--pcborder-cl); }';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-tweets-widget-content .tweet-intents-inner:before,';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-tweets-widget-content .tweet-intents-inner:after { background-color:var(--pcbg-cl); }';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-owl-carousel.penci-tweets-slider .penci-owl-dots .penci-owl-dot.active span,';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-owl-carousel.penci-tweets-slider .penci-owl-dots .penci-owl-dot:hover span { border-color:var(--pcborder-cl); background-color:var(--pcbg-cl); }';
			}
			if ( $mhbgfooter_color ) {
				echo 'body.pcdm-enable .penci-menu-hbg-inner .penci_menu_hbg_ftext{ color:var(--pctext-cl);}';
			}
			if ( $mhbgicon_color ) {
				echo 'body.pcdm-enable .penci-menu-hbg .header-social.sidebar-nav-social a i, body.pcdm-enable .penci-menu-hbg .header-social.penci-hbg-social-style-2 a i{ color:var(--pctext-cl);}';
			}
			if ( $mhbgicon_hover_color ) {
				echo 'body.pcdm-enable .penci-menu-hbg .header-social.sidebar-nav-social a:hover i, body.pcdm-enable .penci-menu-hbg .header-social.penci-hbg-social-style-2 a:hover i{ color:var(--pctext-cl);}';
			}
			if ( $mhbgicon_border ) {
				echo 'body.pcdm-enable .penci-menu-hbg .header-social.penci-hbg-social-style-2 a i{ border-color:var(--pcborder-cl);}';
			}
			if ( $mhbgicon_border_hover ) {
				echo 'body.pcdm-enable .penci-menu-hbg .header-social.penci-hbg-social-style-2 a:hover i{ border-color:var(--pcborder-cl);}';
			}
			if ( $mhbgicon_bg ) {
				echo 'body.pcdm-enable .penci-menu-hbg .header-social.penci-hbg-social-style-2 a i{ background-color:var(--pcbg-cl);}';
			}
			if ( $mhbgicon_bg_hover ) {
				echo 'body.pcdm-enable .penci-menu-hbg .header-social.penci-hbg-social-style-2 a:hover i{ background-color:var(--pcbg-cl);}';
			}
			// Widget
			$mhwidget_heading_bg           = get_theme_mod( 'penci_mhwidget_heading_bg' );
			$mhwidget_heading_outer_bg     = get_theme_mod( 'penci_mhwidget_heading_outer_bg' );
			$mhwidget_heading_bcolor       = get_theme_mod( 'penci_mhwidget_heading_bcolor' );
			$mhwidget_heading_binner_color = get_theme_mod( 'penci_mhwidget_heading_binner_color' );
			$mhwidget_heading_bcolor5      = get_theme_mod( 'penci_mhwidget_heading_bcolor5' );
			$mhwidget_heading_bcolor7      = get_theme_mod( 'penci_mhwidget_heading_bcolor7' );
			$mhwidget_bordertop_color10    = get_theme_mod( 'penci_mhwidget_bordertop_color10' );
			$mhwidget_shapes_color         = get_theme_mod( 'penci_mhwidget_shapes_color' );
			$mhwidget_bgstyle15            = get_theme_mod( 'penci_mhwidget_bgstyle15' );
			$mhwidget_iconstyle15          = get_theme_mod( 'penci_mhwidget_iconstyle15' );
			$mhwidget_cllines              = get_theme_mod( 'penci_mhwidget_cllines' );
			$mhwidget_heading_color        = get_theme_mod( 'penci_mhwidget_heading_color' );
			if ( $mhwidget_heading_bg ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-11 .penci-border-arrow .inner-arrow, body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-12 .penci-border-arrow .inner-arrow, body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-14 .penci-border-arrow .inner-arrow:before, body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-13 .penci-border-arrow .inner-arrow, body.pcdm-enable .penci-menu-hbg .penci-sidebar-content .penci-border-arrow .inner-arrow, body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-15 .penci-border-arrow .inner-arrow{ background-color: ' . $mhwidget_heading_bg . '; }';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-2 .penci-border-arrow:after{ border-top-color: var(--pcborder-cl); }';
			}
			if ( $mhwidget_heading_outer_bg ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content .penci-border-arrow:after { background-color: var(--pcbg-cl); }';
			}
			if ( $mhwidget_heading_bcolor ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content .penci-border-arrow .inner-arrow,';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-4 .penci-border-arrow .inner-arrow:before,';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-4 .penci-border-arrow .inner-arrow:after,';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-5 .penci-border-arrow,';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-7 .penci-border-arrow,';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-9 .penci-border-arrow { border-color:var(--pcborder-cl); }';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content .penci-border-arrow:before { border-top-color: var(--pcborder-cl); }';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-16 .penci-border-arrow:after{ background-color: var(--pcbg-cl); }';
			}
			if ( $mhwidget_heading_binner_color ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content .penci-border-arrow:after { border-color:var(--pcborder-cl); }';
			}
			if ( $mhwidget_heading_bcolor5 ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-5 .penci-border-arrow{ border-color: var(--pcborder-cl); }';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-12 .penci-border-arrow, body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-10 .penci-border-arrow, body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-5 .penci-border-arrow .inner-arrow{ border-bottom-color: var(--pcborder-cl); }';
			}
			if ( $mhwidget_heading_bcolor7 ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-7 .penci-border-arrow .inner-arrow:before,body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-9 .penci-border-arrow .inner-arrow:before{ background-color: var(--pcbg-cl); }';
			}
			if ( $mhwidget_bordertop_color10 ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-10 .penci-border-arrow{ border-top-color: var(--pcborder-cl); }';
			}
			if ( $mhwidget_shapes_color ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-11 .penci-border-arrow .inner-arrow:after,body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-11 .penci-border-arrow .inner-arrow:before{ border-top-color: var(--pcborder-cl); }';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-12 .penci-border-arrow .inner-arrow:before,body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-12.pcalign-center .penci-border-arrow .inner-arrow:after, body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-12.pcalign-right .penci-border-arrow .inner-arrow:after{ border-bottom-color: var(--pcborder-cl); }';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-13.pcalign-center .penci-border-arrow .inner-arrow:after, body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-13.pcalign-left .penci-border-arrow .inner-arrow:after{ border-right-color: var(--pcborder-cl); }';
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-13.pcalign-center .penci-border-arrow .inner-arrow:before, body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-13.pcalign-right .penci-border-arrow .inner-arrow:before{ border-left-color: var(--pcborder-cl); }';
			}
			if ( $mhwidget_bgstyle15 ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-15 .penci-border-arrow:before{ background-color:var(--pcbg-cl); }';
			}
			if ( $mhwidget_iconstyle15 ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-15 .penci-border-arrow:after{ color: var(--pctext-cl); }';
			}
			if ( $mhwidget_cllines ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content.style-18 .penci-border-arrow:after{ color: var(--pctext-cl); }';
			}
			if ( $mhwidget_heading_color ) {
				echo 'body.pcdm-enable .penci-menu-hbg .penci-sidebar-content .penci-border-arrow .inner-arrow { color: var(--pctext-cl); }';
			}
		endif; /* End check if enable HBG menu or Vertical Nav */
	}
}
