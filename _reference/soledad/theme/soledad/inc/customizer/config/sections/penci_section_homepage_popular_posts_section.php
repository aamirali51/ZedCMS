<?php
$options   = [];
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Popular Posts on HomePage', 'soledad' ),
	'id'       => 'penci_enable_home_popular_posts',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '10',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts on Popular Posts', 'soledad' ),
	'id'       => 'penci_home_popular_post_numberposts',
	'ids'      => array(
		'desktop' => 'penci_home_popular_post_numberposts',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '10',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Display Home Popular Posts on', 'soledad' ),
	'id'       => 'penci_home_popular_type',
	'type'     => 'soledad-fw-select',
	'choices'  => array_merge( penci_jetpack_option(), array(
		''      => __('All Time','soledad' ),
		'week'  => __('Once Weekly','soledad' ),
		'month' => __('Once a Month','soledad' ),
	) )
);
$options[] = array(
	'default'  => 'Popular Posts',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Custom Title for Home Popular Posts Box', 'soledad' ),
	'id'       => 'penci_home_popular_title',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'default'  => '0',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Filter Home Popular Posts by A Category', 'soledad' ),
	'id'       => 'penci_home_popular_cat',
	'type'     => 'soledad-fw-ajax-select',
	'choices'  => call_user_func( function () {
		$category = [ '' ];
		$count    = wp_count_terms( 'category' );
		$limit    = 99;
		if ( (int) $count <= $limit ) {
			$categories = get_categories( [
				'hide_empty'   => false,
				'hierarchical' => true,
			] );
			foreach ( $categories as $value ) {
				$category[ $value->term_id ] = $value->name;
			}
		} else {
			$selected = get_theme_mod( 'penci_top_bar_category' );
			if ( ! empty( $selected ) ) {
				$categories = get_categories( [
					'hide_empty'   => false,
					'hierarchical' => true,
					'include'      => $selected,
				] );

				foreach ( $categories as $value ) {
					$category[ $value->term_id ] = $value->name;
				}
			}
		}

		return $category;
	} ),
);
$options[] = array(
	'default'  => '8',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Words Length for Post Titles on Popular Posts', 'soledad' ),
	'id'       => 'penci_home_polular_title_length',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_home_polular_title_length',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '8',
		),
	),
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Turn Off Uppercase Post Titles for Popular Posts on HomePage', 'soledad' ),
	'id'       => 'penci_lowcase_popular_posts',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Date on Home Popular Posts', 'soledad' ),
	'id'       => 'penci_hide_date_home_popular',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Post Format Icons on Home Popular Posts', 'soledad' ),
	'id'       => 'penci_enable_home_popular_icons',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Prev/Next Buttons on Home Popular Posts', 'soledad' ),
	'id'       => 'penci_home_popular_shownav',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Dots on Home Popular Posts', 'soledad' ),
	'id'       => 'penci_home_popular_hidedots',
	'type'     => 'soledad-fw-toggle',
);

return $options;
