<?php
$options = [];

$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Posts Page ( Blog ) Template', 'soledad' ),
	'id'       => 'penci_blogpage_layout',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''                 => __( 'Follow Homepage Setting', 'soledad' ),
		'standard'         => __( 'Standard Posts', 'soledad' ),
		'classic'          => __( 'Classic Posts', 'soledad' ),
		'overlay'          => __( 'Overlay Posts', 'soledad' ),
		'featured'         => __( 'Featured Boxed Posts', 'soledad' ),
		'grid'             => __( 'Grid Posts', 'soledad' ),
		'grid-2'           => __( 'Grid 2 Columns Posts', 'soledad' ),
		'masonry'          => __( 'Grid Masonry Posts', 'soledad' ),
		'masonry-2'        => __( 'Grid Masonry 2 Columns Posts', 'soledad' ),
		'list'             => __( 'List Posts', 'soledad' ),
		'small-list'       => __( 'Small List Posts', 'soledad' ),
		'boxed-1'          => __( 'Boxed Posts Style 1', 'soledad' ),
		'boxed-2'          => __( 'Boxed Posts Style 2', 'soledad' ),
		'mixed'            => __( 'Mixed Posts', 'soledad' ),
		'mixed-2'          => __( 'Mixed Posts Style 2', 'soledad' ),
		'mixed-3'          => __( 'Mixed Posts Style 3', 'soledad' ),
		'mixed-4'          => __( 'Mixed Posts Style 4', 'soledad' ),
		'photography'      => __( 'Photography Posts', 'soledad' ),
		'magazine-1'       => __( 'Magazine Style 1', 'soledad' ),
		'magazine-2'       => __( 'Magazine Style 2', 'soledad' ),
		'standard-grid'    => __( '1st Standard Then Grid', 'soledad' ),
		'standard-grid-2'  => __( '1st Standard Then Grid 2 Columns', 'soledad' ),
		'standard-list'    => __( '1st Standard Then List', 'soledad' ),
		'standard-boxed-1' => __( '1st Standard Then Boxed', 'soledad' ),
		'classic-grid'     => __( '1st Classic Then Grid', 'soledad' ),
		'classic-grid-2'   => __( '1st Classic Then Grid 2 Columns', 'soledad' ),
		'classic-list'     => __( '1st Classic Then List', 'soledad' ),
		'classic-boxed-1'  => __( '1st Classic Then Boxed', 'soledad' ),
		'overlay-grid'     => __( '1st Overlay Then Grid', 'soledad' ),
		'overlay-list'     => __( '1st Overlay Then List', 'soledad' )
	)
);

$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Stop Overwrite Post Page Template', 'soledad' ),
	'description' => __( 'keep the same content as the page assigned is Posts Page', 'soledad' ),
	'id'          => 'penci_overwrite_post_page',
	'type'        => 'soledad-fw-toggle',
);

return $options;