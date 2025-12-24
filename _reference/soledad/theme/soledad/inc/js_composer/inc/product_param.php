<?php
/**
 * ------------------------------------------------------------------------------------------------
 * AJAX Products tabs element map
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'penci_get_products_shortcode_params' ) ) {
	function penci_get_products_shortcode_params() {
		return array(
			array(
				'type'             => 'dropdown',
				'heading'          => __( 'Date Source', 'soledad' ),
				'value'            => array(
					esc_html__( 'All Products', 'soledad' )            => 'product',
					esc_html__( 'Featured Products', 'soledad' )       => 'featured',
					esc_html__( 'Sale Products', 'soledad' )           => 'sale',
					esc_html__( 'Products with NEW label', 'soledad' ) => 'new',
					esc_html__( 'Bestsellers', 'soledad' )             => 'bestselling',
					esc_html__( 'List of IDs', 'soledad' )             => 'ids',
					esc_html__( 'Top Rated Products', 'soledad' )      => 'top_rated_products',
				),
				'std'              => 'product',
				'param_name'       => 'post_type',
				'edit_field_class' => 'vc_col-sm-12',
			),
			array(
				'type'             => 'autocomplete',
				'heading'          => __( 'Included Only', 'soledad' ),
				'value'            => '',
				'param_name'       => 'include',
				'edit_field_class' => 'vc_col-sm-4',
				'hint'             => esc_html__( 'Add products by title.', 'soledad' ),
				'settings'         => array(
					'multiple' => true,
					'sortable' => true,
					'groups'   => true
				),
			),
			array(
				'type'             => 'autocomplete',
				'heading'          => __( 'Excluded', 'soledad' ),
				'value'            => '',
				'param_name'       => 'exclude',
				'edit_field_class' => 'vc_col-sm-4',
			),
			array(
				'type'             => 'autocomplete',
				'heading'          => __( 'Categories OR Tags', 'soledad' ),
				'value'            => '',
				'param_name'       => 'taxonomies',
				'edit_field_class' => 'vc_col-sm-4',
				'settings'         => array(
					'multiple'       => true,
					// is multiple values allowed? default false
					// 'sortable' => true, // is values are sortable? default false
					'min_length'     => 1,
					// min length to start search -> default 2
					// 'no_hide' => true, // In UI after select doesn't hide an select list, default false
					'groups'         => true,
					// In UI show results grouped by groups, default false
					'unique_values'  => true,
					// In UI show results except selected. NB! You should manually check values in backend, default false
					'display_inline' => true,
					// In UI show results inline view, default false (each value in own line)
					'delay'          => 500,
					// delay for search. default 500
					'auto_focus'     => true,
					// auto focus input, default true
				),
			),
			array(
				'type'             => 'dropdown',
				'heading'          => __( 'Order by', 'soledad' ),
				'value'            => array(
					''                                            => '',
					esc_html__( 'Date', 'soledad' )               => 'date',
					esc_html__( 'ID', 'soledad' )                 => 'id',
					esc_html__( 'Author', 'soledad' )             => 'author',
					esc_html__( 'Title', 'soledad' )              => 'title',
					esc_html__( 'Last modified date', 'soledad' ) => 'modified',
					esc_html__( 'Number of comments', 'soledad' ) => 'comment_count',
					esc_html__( 'Menu order', 'soledad' )         => 'menu_order',
					esc_html__( 'Meta value', 'soledad' )         => 'meta_value',
					esc_html__( 'Meta value number', 'soledad' )  => 'meta_value_num',
					esc_html__( 'Random order', 'soledad' )       => 'rand',
					esc_html__( 'Price', 'soledad' )              => 'price',
				),
				'std'              => '',
				'edit_field_class' => 'vc_col-sm-6',
				'param_name'       => 'orderby',
			),
			array(
				'type'             => 'textfield',
				'heading'          => __( 'Offset', 'soledad' ),
				'value'            => '',
				'param_name'       => 'offset',
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'dropdown',
				'heading'          => __( 'Query Type', 'soledad' ),
				'value'            => array(
					esc_html__( 'OR', 'soledad' )  => 'or',
					esc_html__( 'AND', 'soledad' ) => 'and',
				),
				'std'              => 'or',
				'param_name'       => 'query_type',
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'dropdown',
				'heading'          => __( 'Sort order', 'soledad' ),
				'value'            => array(
					esc_html__( 'Inherit', 'soledad' )    => '',
					esc_html__( 'Descending', 'soledad' ) => 'DESC',
					esc_html__( 'Ascending', 'soledad' )  => 'ASC',
				),
				'std'              => 'DESC',
				'param_name'       => 'order',
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Meta Key', 'soledad' ),
				'description' => esc_html__( 'Input meta key for grid ordering.', 'soledad' ),
				'value'       => '',
				'std'         => '',
				'param_name'  => 'meta_key',
			),
		);
	}
}

// Necessary hooks for blog autocomplete fields
add_filter( 'vc_autocomplete_penci_product_include_callback', 'penci_productIdAutocompleteSuggester_new', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_penci_product_include_render', 'penci_productIdAutocompleteRender', 10, 1 ); // Render exact product. Must return an array (label,value)

add_filter( 'vc_autocomplete_penci_product_tab_include_callback', 'penci_productIdAutocompleteSuggester_new', 10, 1 ); // Render exact product. Must return an array (label,value)
add_filter( 'vc_autocomplete_penci_product_tab_include_render', 'penci_productIdAutocompleteRender', 10, 1 ); // Render exact product. Must return an array (label,value)

// Narrow data taxonomies
add_filter( 'vc_autocomplete_penci_product_taxonomies_callback', 'penci_vc_autocomplete_taxonomies_field_search', 10, 1 );
add_filter( 'vc_autocomplete_penci_product_tab_taxonomies_callback', 'penci_vc_autocomplete_taxonomies_field_search', 10, 1 );
add_filter( 'vc_autocomplete_penci_product_taxonomies_render', 'penci_vc_autocomplete_taxonomies_field_render', 10, 1 );
add_filter( 'vc_autocomplete_penci_product_tab_taxonomies_render', 'penci_vc_autocomplete_taxonomies_field_render', 10, 1 );

// Narrow data taxonomies for exclude_filter
add_filter( 'vc_autocomplete_penci_product_exclude_filter_callback', 'penci_vc_autocomplete_taxonomies_field_render', 10, 1 );
add_filter( 'vc_autocomplete_penci_product_tab_exclude_filter_callback', 'penci_vc_autocomplete_taxonomies_field_render', 10, 1 );
add_filter( 'vc_autocomplete_penci_product_exclude_filter_render', 'penci_vc_autocomplete_taxonomies_field_render', 10, 1 );
add_filter( 'vc_autocomplete_penci_product_tab_exclude_filter_render', 'penci_vc_autocomplete_taxonomies_field_render', 10, 1 );

add_filter( 'vc_autocomplete_penci_product_exclude_callback', 'vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_penci_product_tab_exclude_callback', 'vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_penci_product_exclude_render', 'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
add_filter( 'vc_autocomplete_penci_product_tab_exclude_render', 'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

if ( ! function_exists( 'penci_vc_autocomplete_taxonomies_field_render' ) ) {
	function penci_vc_autocomplete_taxonomies_field_render( $term ) {
		$vc_taxonomies_types = vc_taxonomies_types();

		$brands_attribute = get_theme_mod( 'penci_woocommerce_brand_attr' );

		if ( ! empty( $brands_attribute ) && taxonomy_exists( $brands_attribute ) ) {
			$vc_taxonomies_types[ $brands_attribute ] = $brands_attribute;
		}

		$terms = get_terms( array_keys( $vc_taxonomies_types ), array(
			'include'    => array( $term['value'] ),
			'hide_empty' => false,
		) );
		$data  = false;
		if ( is_array( $terms ) && 1 === count( $terms ) ) {
			$term = $terms[0];
			$data = vc_get_term_object( $term );
		}

		return $data;
	}
}

if ( ! function_exists( 'penci_productIdAutocompleteSuggester_new' ) ) {
	function penci_productIdAutocompleteSuggester_new( $query ) {
		global $wpdb;
		$product_id      = (int) $query;
		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title
							FROM {$wpdb->posts} AS a
							LEFT JOIN ( SELECT product_id, sku FROM {$wpdb->wc_product_meta_lookup} ) AS b ON b.product_id = a.ID
							WHERE a.post_type = 'product' AND ( a.ID = '%d' OR b.sku LIKE '%%%s%%' OR a.post_title LIKE '%%%s%%' )", $product_id > 0 ? $product_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

		$results = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data          = array();
				$data['value'] = $value['id'];
				$data['label'] = esc_html__( 'Id', 'soledad' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'soledad' ) . ': ' . $value['title'] : '' ) . ( ( strlen( $value['sku'] ) > 0 ) ? ' - ' . esc_html__( 'Sku', 'soledad' ) . ': ' . $value['sku'] : '' );
				$results[]     = $data;
			}
		}

		return $results;
	}
}

if ( ! function_exists( 'penci_productIdAutocompleteRender' ) ) {
	function penci_productIdAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get product
			$product_object = wc_get_product( (int) $query );
			if ( is_object( $product_object ) ) {
				$product_sku   = $product_object->get_sku();
				$product_title = $product_object->get_title();
				$product_id    = $product_object->get_id();

				$product_sku_display = '';
				if ( ! empty( $product_sku ) ) {
					$product_sku_display = ' - ' . esc_html__( 'Sku', 'soledad' ) . ': ' . $product_sku;
				}

				$product_title_display = '';
				if ( ! empty( $product_title ) ) {
					$product_title_display = ' - ' . esc_html__( 'Title', 'soledad' ) . ': ' . $product_title;
				}

				$product_id_display = esc_html__( 'Id', 'soledad' ) . ': ' . $product_id;

				$data          = array();
				$data['value'] = $product_id;
				$data['label'] = $product_id_display . $product_title_display . $product_sku_display;

				return ! empty( $data ) ? $data : false;
			}

			return false;
		}

		return false;
	}
}

// Add other product attributes
if ( ! function_exists( 'penci_vc_autocomplete_taxonomies_field_search' ) ) {
	function penci_vc_autocomplete_taxonomies_field_search( $search_string ) {
		$data                = array();
		$vc_filter_by        = vc_post_param( 'vc_filter_by', '' );
		$vc_taxonomies_types = strlen( $vc_filter_by ) > 0 ? array( $vc_filter_by ) : array_keys( vc_taxonomies_types() );

		$brands_attribute = get_theme_mod( 'penci_woocommerce_brand_attr' );

		if ( ! empty( $brands_attribute ) && taxonomy_exists( $brands_attribute ) ) {
			array_push( $vc_taxonomies_types, $brands_attribute );
		}

		$vc_taxonomies = get_terms( $vc_taxonomies_types, array(
			'hide_empty' => false,
			'search'     => $search_string,
		) );
		if ( is_array( $vc_taxonomies ) && ! empty( $vc_taxonomies ) ) {
			foreach ( $vc_taxonomies as $t ) {
				if ( is_object( $t ) ) {
					$data[] = vc_get_term_object( $t );
				}
			}
		}

		return $data;
	}
}
