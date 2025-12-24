<?php

namespace PenciSoledadElementor\Modules\QueryControl\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;
use PenciSoledadElementor\Classes\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Penci_Group_Control_Posts extends Group_Control_Base {

	const INLINE_MAX_RESULTS = 100;

	protected static $fields;

	public static function get_type() {
		return 'posts';
	}

	public static function on_export_remove_setting_from_element( $element, $control_id ) {
		unset( $element['settings'][ $control_id . '_posts_ids' ] );
		unset( $element['settings'][ $control_id . '_authors' ] );

		foreach ( Utils::get_public_post_types() as $post_type => $label ) {
			$taxonomy_filter_args = array(
				'show_in_nav_menus' => true,
				'object_type'       => array( $post_type ),
			);

			$taxonomies = get_taxonomies( $taxonomy_filter_args, 'objects' );

			foreach ( $taxonomies as $taxonomy => $object ) {
				unset( $element['settings'][ $control_id . '_' . $taxonomy . '_ids' ] );
			}
		}

		return $element;
	}

	final protected function init_fields() {
		$fields = array();

		$fields['post_type'] = array(
			'label' => __( 'Source', 'soledad' ),
			'type'  => Controls_Manager::SELECT,
		);

		$fields['related_post_by'] = array(
			'label'       => __( 'Display Related Posts By', 'soledad' ),
			'label_block' => true,
			'type'        => Controls_Manager::SELECT,
			'multiple'    => false,
			'default'     => 'categories',
			'options'     => array(
				'categories'  => __('Categories','soledad' ),
				'tags'        => __('Tags','soledad' ),
				'primary_cat' => __('Primary Category from "Yoast SEO" or "Rank Math" plugin','soledad' ),
			),
			'condition'   => array( 'post_type' => array( 'related_posts' ) ),
		);

		$fields['posts_ids'] = array(
			'label'       => __( 'Search by Title & Select', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_posts_by_query',
			'render'      => 'penci_get_posts_title_by_id',
			'post_type'   => 'all',
			'multiple'    => true,
			'label_block' => true,
			'condition'   => array( 'post_type' => 'by_id' ),
		);

		$fields['authors'] = array(
			'label'       => __( 'Author', 'soledad' ),
			'label_block' => true,
			'multiple'    => true,
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_user_by_query',
			'render'      => 'penci_get_user_title_by_id',
			'condition'   => array( 'post_type!' => array( 'by_id', 'current_query' ) ),
		);

		$fields['multi_types_option'] = array(
			'label'       => __( 'Select Post Types', 'soledad' ),
			'label_block' => true,
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'default'     => ['post'],
			'condition'   => array( 'post_type' => 'multi_types' ),
			'options'     => Utils::get_public_post_types(),
		);
		
		foreach ( Utils::get_public_post_types() as $post_type => $label ) {

			$taxonomies = get_taxonomies( array(
				'show_in_nav_menus' => true,
				'object_type'       => array( $post_type ),
			), 'objects' );

			foreach ( $taxonomies as $taxonomy => $object ) {
				$taxonomy_args = array(
					'label'       => 'Include ' . $label . ' - ' . $object->label,
					'label_block' => true,
					'multiple'    => true,
					'object_type' => $taxonomy,
					'source_name' => 'taxonomy',
					'source_type' => $taxonomy,
					'options'     => array(),
					'conditions' => [
						'terms' => [
							[
								'name' => 'post_type',
								'operator' => '==',
								'value' => 'multi_types',
							],
							[
								'name' => 'multi_types_option',
								'operator' => 'contains',
								'value' => $post_type,
							],
						],
					],
				);

				$count = wp_count_terms( $taxonomy );

				$options = array();

				if ( $count > self::INLINE_MAX_RESULTS ) {
					$taxonomy_args['type'] = 'penci-select2';
					$taxonomy_args['filter_type'] = 'taxonomy';
				} else {
					$taxonomy_args['type'] = Controls_Manager::SELECT2;

					$terms = get_terms(
						array(
							'taxonomy'   => $taxonomy,
							'hide_empty' => false,
						)
					);

					foreach ( $terms as $term ) {
						$options[ $term->term_id ] = $term->name;
					}

					$taxonomy_args['options'] = $options;
				}

				$fields[ $object->name . '_multi_ids' ] = $taxonomy_args;

				$taxonomy_args = array(
					'label'       => 'Exclude ' . $label . ' - ' . $object->label,
					'label_block' => true,
					'multiple'    => true,
					'object_type' => $taxonomy,
					'source_name' => 'taxonomy',
					'source_type' => $taxonomy,
					'options'     => array(),
					'conditions' => [
						'terms' => [
							[
								'name' => 'post_type',
								'operator' => '==',
								'value' => 'multi_types',
							],
							[
								'name' => 'multi_types_option',
								'operator' => 'contains',
								'value' => $post_type,
							],
						],
					],
				);

				if ( $count > self::INLINE_MAX_RESULTS ) {
					$taxonomy_args['type'] = 'penci-select2';
					$taxonomy_args['filter_type'] = 'taxonomy';
				} else {
					$taxonomy_args['type'] = Controls_Manager::SELECT2;

					$terms = get_terms(
						array(
							'taxonomy'   => $taxonomy,
							'hide_empty' => false,
						)
					);

					foreach ( $terms as $term ) {
						$options[ $term->term_id ] = $term->name;
					}

					$taxonomy_args['options'] = $options;
				}

				$fields[ $object->name . '_multi_exids' ] = $taxonomy_args;
			}
		}

		return $fields;
	}

	final protected function prepare_fields( $fields ) {
		$args = $this->get_args();

		$post_types = Utils::get_public_post_types( $args );

		$post_types_options = $post_types;

		$post_types_options['by_id']         = __( 'Manual Selection', 'soledad' );
		$post_types_options['current_query'] = __( 'Archive Builder / Current Query', 'soledad' );
		$post_types_options['related_posts'] = __( 'Post Builder - Related Posts', 'soledad' );
		$post_types_options['multi_types'] 	 = __( 'Multi Post Types', 'soledad' );

		$fields['post_type']['options'] = $post_types_options;

		$fields['post_type']['default'] = key( $post_types );

		$fields['posts_ids']['object_type'] = array_keys( $post_types );

		$taxonomy_filter_args = array( 'show_in_nav_menus' => true );

		if ( ! empty( $args['post_type'] ) ) {
			$taxonomy_filter_args['object_type'] = array( $args['post_type'] );
		}

		$taxonomies = get_taxonomies( $taxonomy_filter_args, 'objects' );

		foreach ( $taxonomies as $taxonomy => $object ) {
			$taxonomy_args = array(
				'label'       => 'Include ' . $object->label,
				'label_block' => true,
				'multiple'    => true,
				'object_type' => $taxonomy,
				'source_name' => 'taxonomy',
				'source_type' => $taxonomy,
				'options'     => array(),
				'condition'   => array( 'post_type' => $object->object_type ),
				'dynamic' => [
					'active' => true,
				],
			);

			$count = wp_count_terms( $taxonomy );

			$options = array();

			if ( $count > self::INLINE_MAX_RESULTS ) {
				$taxonomy_args['type'] = 'penci-select2';

				$taxonomy_args['filter_type'] = 'taxonomy';
			} else {
				$taxonomy_args['type'] = Controls_Manager::SELECT2;

				$terms = get_terms(
					array(
						'taxonomy'   => $taxonomy,
						'hide_empty' => false,
					)
				);

				foreach ( $terms as $term ) {
					$options[ $term->term_id ] = $term->name;
				}

				$taxonomy_args['options'] = $options;
			}

			$fields[ $taxonomy . '_ids' ] = $taxonomy_args;
		}

		foreach ( $taxonomies as $taxonomy => $object ) {
			$taxonomy_args = array(
				'label'       => 'Exclude ' . $object->label,
				'label_block' => true,
				'multiple'    => true,
				'object_type' => $taxonomy,
				'source_name' => 'taxonomy',
				'source_type' => $taxonomy,
				'options'     => array(),
				'condition'   => array( 'post_type' => $object->object_type ),
			);

			$count = wp_count_terms( $taxonomy );

			$options = array();

			if ( $count > self::INLINE_MAX_RESULTS ) {
				$taxonomy_args['type'] = 'penci-select2';

				$taxonomy_args['filter_type'] = 'taxonomy';
			} else {
				$taxonomy_args['type'] = Controls_Manager::SELECT2;

				$terms = get_terms(
					array(
						'taxonomy'   => $taxonomy,
						'hide_empty' => false,
					)
				);

				foreach ( $terms as $term ) {
					$options[ $term->term_id ] = $term->name;
				}

				$taxonomy_args['options'] = $options;
			}

			$fields[ $taxonomy . '_exids' ] = $taxonomy_args;
		}

		$fields['ignore_sticky_posts'] = array(
			'label'       => __( 'Ignore Sticky Posts', 'soledad' ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => 'yes',
			'condition'   => array( 'post_type' => 'post' ),
			'description' => __( 'Sticky-posts ordering is visible on frontend only. And includes sticky posts doesn\'t work if you filter your posts by a taxonomy or multiple taxonomies (categories, tags... ) - because it doesn\'t support by WordPress itself.', 'soledad' ),
			'separator'   => 'before',
		);

		return parent::prepare_fields( $fields );
	}

	protected function get_default_options() {
		return array( 'popover' => false );
	}
}
