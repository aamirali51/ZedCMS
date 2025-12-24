<?php

class Penci_Maintenance_Mode {
	public function __construct() {

		$is_enabled = (bool) get_theme_mod( 'penci_maintenance_mode' ) && (bool) get_theme_mod( 'penci_maintenance_mode_template' );


		if ( ! $is_enabled ) {
			return;
		}

		add_action( 'admin_bar_menu', [ $this, 'add_menu_in_admin_bar' ], 300 );
		add_action( 'admin_head', [ $this, 'print_style' ] );
		add_action( 'wp_head', [ $this, 'print_style' ] );

		// Priority = 11 that is *after* WP default filter `redirect_canonical` in order to avoid redirection loop.
		add_action( 'template_redirect', [ $this, 'template_redirect' ], 11 );
	}

	public function print_style() {
		?>
        <style>#wp-admin-bar-penci-maintenance-on > a {
                background-color: #0639ff;
            }
            #wp-admin-bar-penci-maintenance-on > .ab-item:before {
                content: "\f160";
                top: 2px;
            }</style>
		<?php
	}

	public function add_menu_in_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {
		$block               = get_theme_mod( 'penci_maintenance_mode_template' );
		$customizer_edit_url = admin_url( 'customize.php?autofocus[section]=penci_maintenance_mode_section' );
		$block_edit_url = $block ? get_edit_post_link( $block ) : '';

		$wp_admin_bar->add_node( [
			'id'    => 'penci-maintenance-on',
			'title' => esc_html__( 'Maintenance Mode ON', 'soledad' ),
			'href'  => $customizer_edit_url,
		] );

		if ( $block_edit_url ) {
			$wp_admin_bar->add_node( [
				'id'     => 'penci-maintenance-edit',
				'parent' => 'penci-maintenance-on',
				'title'  => esc_html__( 'Edit Template', 'soledad' ),
				'href'   => $block_edit_url,
			] );
		}
	}

	public function body_class( $classes ) {
		$classes[] = 'penci-maintenance-mode';

		return $classes;
	}

	public function template_redirect() {
		if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return;
		}

		$user = wp_get_current_user();

		$exclude_mode = get_theme_mod( 'penci_maintenance_mode_access', 'logged_in' );

		$is_login_page = false;

		/**
		 * Is login page
		 *
		 * Filters whether the maintenance mode displaying the login page or a regular page.
		 *
		 * @param bool $is_login_page Whether its a login page.
		 *
		 * @since 1.0.4
		 *
		 */
		$is_login_page = is_login();

		if ( $is_login_page ) {
			return;
		}

		if ( 'logged_in' === $exclude_mode && is_user_logged_in() ) {
			return;
		}

		if ( 'custom' === $exclude_mode ) {
			$exclude_roles = get_theme_mod( 'penci_maintenance_mode_custom_roles', [] );
			$user_roles    = $user->roles;

			if ( is_multisite() && is_super_admin() ) {
				$user_roles[] = 'super_admin';
			}

			$compare_roles = array_intersect( $user_roles, $exclude_roles );

			if ( ! empty( $compare_roles ) ) {
				return;
			}
		}

		add_filter( 'body_class', [ $this, 'body_class' ] );

		if ( 'maintenance' === get_theme_mod( 'penci_maintenance_mode' ) ) {
			$protocol = wp_get_server_protocol();
			header( "$protocol 503 Service Unavailable", true, 503 );
			header( 'Content-Type: text/html; charset=utf-8' );
			header( 'Retry-After: 600' );
		}

		// Setup global post for Elementor\frontend so `_has_elementor_in_page = true`.
		$GLOBALS['post'] = get_post( get_theme_mod( 'penci_maintenance_mode_template' ) ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		// Set the template as `$wp_query->current_object` for `wp_title` and etc.
		query_posts( [
			'p'         => get_theme_mod( 'penci_maintenance_mode_template' ),
			'post_type' => 'penci-block',
		] );
	}
}

new Penci_Maintenance_Mode();