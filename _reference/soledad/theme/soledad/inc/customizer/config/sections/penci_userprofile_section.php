<?php
$options = array();
$options[] = array(
	'label'       => esc_html__( 'Permantly Disable User Profile Features', 'soledad' ),
	'id'          => 'penci_disable_all_user_profiles',
	'type'        => 'soledad-fw-toggle',
	'sanitize'    => 'sanitize_text_field'
);
$options[] = array(
	'label'       => esc_html__( 'Disable Default User Profile Pages', 'soledad' ),
	'description' => esc_html__( 'Turn on this option if you want to use user profile pages with third-party plugins (such as WooCommerce, ProfilePress, EDD, ...).', 'soledad' ),
	'id'          => 'penci_disable_user_profiles',
	'type'        => 'soledad-fw-toggle',
	'sanitize'    => 'sanitize_text_field'
);
$options[] = array(
	'id'    => 'penci_frontend_profile_header',
	'type'  => 'soledad-fw-header',
	'label' => esc_html__( 'Page URL Settings', 'soledad' ),
);

$options[] = array(
	'id'          => 'penci_frontend_submit_account_slug',
	'transport'   => 'postMessage',
	'default'     => 'account',
	'type'        => 'soledad-fw-text',
	'label'       => esc_html__( 'Account Page Slug', 'soledad' ),
	'description' => esc_html__( 'Default: ' . home_url( '/' ) . 'account/', 'soledad' ),
);

$options[] = array(
	'id'          => 'penci_frontend_submit_edit_account_slug',
	'transport'   => 'postMessage',
	'default'     => 'edit-account',
	'type'        => 'soledad-fw-text',
	'label'       => esc_html__( 'Edit Account URL Slug', 'soledad' ),
	'description' => esc_html__( 'Default: ' . home_url( '/' ) . 'account/edit-account/', 'soledad' ),
);

$options[] = array(
	'id'          => 'penci_frontend_submit_change_password_slug',
	'transport'   => 'postMessage',
	'default'     => 'change-password',
	'type'        => 'soledad-fw-text',
	'label'       => esc_html__( 'Change Password URL Slug', 'soledad' ),
	'description' => esc_html__( 'Default: ' . home_url( '/' ) . 'account/change-password/', 'soledad' ),
);

$options[] = array(
	'id'          => 'penci_frontend_submit_like_posts_slug',
	'transport'   => 'postMessage',
	'default'     => 'change-password',
	'type'        => 'soledad-fw-text',
	'label'       => esc_html__( 'Change Post Likes URL Slug', 'soledad' ),
	'description' => esc_html__( 'Default: ' . home_url( '/' ) . 'account/like-posts/', 'soledad' ),
);

return $options;