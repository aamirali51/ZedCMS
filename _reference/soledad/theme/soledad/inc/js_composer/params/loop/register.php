<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! function_exists( 'penci_soledad_vc_param_loop' ) ):
	function penci_soledad_vc_param_loop( $settings, $value ) {
		$query_builder = new PenciSoledadLoopSettings( $value );
		$params        = $query_builder->getContent();
		$loop_info     = '';
		if ( is_array( $params ) ) {
			foreach ( $params as $key => $param ) {
				$param_value = penci_soledad_loop_get_value( $param );
				if ( ! empty( $param_value ) ) {
					$loop_info .= ' <b>' . $query_builder->getLabel( $key ) . '</b>: ' . $param_value . ';';
				}
			}
		}

		return '<div class="vc_loop">' . '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value  ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="' . $value . '"/>' . '<a href="#" class="button vc_loop-build ' . $settings['param_name'] . '_button" data-settings="' . rawurlencode( json_encode( $settings['settings'] ) ) . '">' . __( 'Build query', 'soledad' ) . '</a>' . '<div class="vc_loop-info">' . $loop_info . '</div>' . '</div>';
	}
endif;

if ( ! function_exists( 'penci_soledad_loop_get_value' ) ):
	function penci_soledad_loop_get_value( $param ) {
		$value           = array();
		$selected_values = (array) $param['value'];
		if ( isset( $param['options'] ) && is_array( $param['options'] ) ) {
			foreach ( $param['options'] as $option ) {
				if ( is_array( $option ) && isset( $option['value'] ) ) {
					if ( in_array( ( ( '-' === $option['action'] ? '-' : '' ) . $option['value'] ), $selected_values ) ) {
						$value[] = $option['action'] . $option['name'];
					}
				} elseif ( is_array( $option ) && isset( $option[0] ) ) {
					if ( in_array( $option[0], $selected_values ) ) {
						$value[] = $option[1];
					}
				} elseif ( in_array( $option, $selected_values ) ) {
					$value[] = $option;
				}
			}
		} else {
			$value[] = $param['value'];
		}

		return implode( ', ', $value );
	}
endif;


if ( ! class_exists( 'PenciSoledadLoopQueryBuilder' ) ):
	class PenciSoledadLoopQueryBuilder {
		protected $args = array(
			'post_status' => 'publish',
		);

		function __construct( $data ) {
			foreach ( $data as $key => $value ) {
				$method = 'parse_' . $key;
				if ( method_exists( $this, $method ) ) {
					$this->$method( $value );
				}
			}
		}

		protected function parse_size( $value ) {
			$this->args['posts_per_page'] = 'All' === $value ? - 1 : (int) $value;
		}

		protected function parse_offset( $value ) {
			$this->args['offset'] = (int) $value;
		}

		protected function parse_order_by( $value ) {
			switch ( $value ) {
				case 'popular':
					$this->args['meta_key'] = penci_get_postviews_key();
					$this->args['orderby']  = 'meta_value_num';
					$this->args['order']    = 'DESC';
					break;
				case 'popular_day':
					$this->args['meta_key'] = 'penci_post_day_views_count';
					$this->args['orderby']  = 'meta_value_num';
					$this->args['order']    = 'DESC';
					break;
				case 'popular7':
					$this->args['meta_key'] = 'penci_post_week_views_count';
					$this->args['orderby']  = 'meta_value_num';
					$this->args['order']    = 'DESC';
					break;
				case 'popular_month':
					$this->args['meta_key'] = 'penci_post_month_views_count';
					$this->args['orderby']  = 'meta_value_num';
					$this->args['order']    = 'DESC';
					break;
				case 'jetpack':
					$this->args['meta_key'] = '_jetpack_post_view';
					$this->args['orderby']  = 'meta_value_num';
					$this->args['order']    = 'DESC';
					break;
				case 'alphabetical_order':
					$this->args['orderby'] = 'title';
					$this->args['order']   = 'ASC';
					break;
				case 'random_today':
					$this->args['orderby']  = 'rand';
					$this->args['year']     = date( 'Y' );
					$this->args['monthnum'] = date( 'n' );
					$this->args['day']      = date( 'j' );
					break;
				case 'random_7_day':
					$this->args['orderby']    = 'rand';
					$this->args['date_query'] = array(
						'column' => 'post_date_gmt',
						'after'  => '1 week ago'
					);
					break;
				case 'featured_product':
					$this->args['tax_query'][] = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
						'operator' => 'IN'
					);
					break;
				case 'modified':
					$this->args['orderby'] = 'modified';
					break;
				default:
					$this->args['orderby'] = $value;
			}


		}

		protected function parse_order( $value ) {
			$this->args['order'] = $value;
		}

		protected function parse_post_type( $value ) {
			$this->args['post_type'] = $this->stringToArray( $value );
		}

		protected function parse_authors( $value ) {
			$this->args['author'] = $value;
		}

		protected function parse_categories( $value ) {
			$this->args['cat'] = $value;
		}

		protected function parse_tax_query( $value ) {
			$terms = $this->stringToArray( $value );
			if ( empty( $this->args['tax_query'] ) ) {
				$this->args['tax_query'] = array( 'relation' => 'AND' );
			}
			$negative_term_list = array();
			foreach ( $terms as $term ) {
				if ( (int) $term < 0 ) {
					$negative_term_list[] = abs( $term );
				}
			}

			$not_in = array();
			$in     = array();

			$terms = get_terms( PenciSoledadLoopSettings::getTaxonomies(), array( 'include' => array_map( 'abs', $terms ) ) );
			foreach ( $terms as $t ) {
				if ( in_array( (int) $t->term_id, $negative_term_list ) ) {
					$not_in[ $t->taxonomy ][] = $t->term_id;
				} else {
					$in[ $t->taxonomy ][] = $t->term_id;
				}
			}

			foreach ( $in as $taxonomy => $terms ) {
				$this->args['tax_query'][] = array(
					'field'    => 'term_id',
					'taxonomy' => $taxonomy,
					'terms'    => $terms,
					'operator' => 'IN',
				);
			}
			foreach ( $not_in as $taxonomy => $terms ) {
				$this->args['tax_query'][] = array(
					'field'    => 'term_id',
					'taxonomy' => $taxonomy,
					'terms'    => $terms,
					'operator' => 'NOT IN',
				);
			}
		}

		protected function parse_tags( $value ) {
			$in       = $not_in = array();
			$tags_ids = $this->stringToArray( $value );
			foreach ( $tags_ids as $tag ) {
				$tag = (int) $tag;
				if ( $tag < 0 ) {
					$not_in[] = abs( $tag );
				} else {
					$in[] = $tag;
				}
			}
			$this->args['tag__in']     = $in;
			$this->args['tag__not_in'] = $not_in;
		}

		protected function parse_by_id( $value ) {
			$in  = $not_in = array();
			$ids = $this->stringToArray( $value );
			foreach ( $ids as $id ) {
				$id = (int) $id;
				if ( $id < 0 ) {
					$not_in[] = abs( $id );
				} else {
					$in[] = $id;
				}
			}
			$this->args['post__in']     = $in;
			$this->args['post__not_in'] = $not_in;
		}

		public function excludeId( $id ) {
			if ( ! isset( $this->args['post__not_in'] ) ) {
				$this->args['post__not_in'] = array();
			}
			if ( is_array( $id ) ) {
				$this->args['post__not_in'] = array_merge( $this->args['post__not_in'], $id );
			} else {
				$this->args['post__not_in'][] = $id;
			}
		}

		protected function stringToArray( $value ) {
			$valid_values = array();
			$list         = preg_split( '/\,[\s]*/', $value );
			foreach ( $list as $v ) {
				if ( strlen( $v ) > 0 ) {
					$valid_values[] = $v;
				}
			}

			return $valid_values;
		}

		public function build() {
			return array( $this->args, new WP_Query( $this->args ) );
		}

		public function build_args() {
			return $this->args;
		}
	}
endif;


if ( ! class_exists( 'PenciSoledadLoopSettings' ) ):
	class PenciSoledadLoopSettings {
		protected $content = array();

		protected $parts;

		public $settings;

		public $query_parts = array(
			'post_type',
			'size',
			'offset',
			'order_by',
			'order',
			'authors',
			'categories',
			'tags',
			'tax_query',
			'by_id',
		);

		function __construct( $value, $settings = array() ) {
			$this->parts = array(
				'size'       => __( 'Post count', 'soledad' ),
				'offset'     => __( 'Post offset', 'soledad' ),
				'order_by'   => __( 'Order by', 'soledad' ),
				'order'      => __( 'Sort order', 'soledad' ),
				'post_type'  => __( 'Post types', 'soledad' ),
				'authors'    => __( 'Author', 'soledad' ),
				'categories' => __( 'Categories', 'soledad' ),
				'tags'       => __( 'Tags', 'soledad' ),
				'tax_query'  => __( 'Taxonomies', 'soledad' ),
				'by_id'      => __( 'Individual posts/pages', 'soledad' ),
			);

			$this->settings = $settings;

			$data = $this->parseData( $value );
			foreach ( $this->query_parts as $part ) {
				$value  = isset( $data[ $part ] ) ? $data[ $part ] : '';
				$locked = 'true' === $this->getSettings( $part, 'locked' );

				if ( ! is_null( $this->getSettings( $part, 'value' ) ) && $this->replaceLockedValue( $part ) && ( true === $locked || 0 === strlen( (string) $value ) ) ) {
					$value = $this->settings[ $part ]['value'];
				} elseif ( ! is_null( $this->getSettings( $part, 'value' ) ) && ! $this->replaceLockedValue( $part ) && ( true === $locked || 0 === strlen( (string) $value ) ) ) {
					$value = implode( ',', array_unique( explode( ',', $value . ',' . $this->settings[ $part ]['value'] ) ) );
				}

				if ( method_exists( $this, 'parse_' . $part ) ) {
					$method                 = 'parse_' . $part;
					$this->content[ $part ] = $this->$method( $value );
				} else {
					$this->content[ $part ] = $this->parseString( $value );
				}

				if ( $locked ) {
					$this->content[ $part ]['locked'] = true;
				}

				if ( 'true' === $this->getSettings( $part, 'hidden' ) ) {
					$this->content[ $part ]['hidden'] = true;
				}
			}
		}

		protected function replaceLockedValue( $part ) {
			return in_array( $part, array( 'size', 'order_by', 'order' ) );
		}

		public function getLabel( $key ) {
			return isset( $this->parts[ $key ] ) ? $this->parts[ $key ] : $key;
		}

		public function getSettings( $part, $name ) {
			$settings_exists = isset( $this->settings[ $part ] ) && is_array( $this->settings[ $part ] );

			return $settings_exists && isset( $this->settings[ $part ][ $name ] ) ? $this->settings[ $part ][ $name ] : null;
		}

		public function parseString( $value ) {
			return array( 'value' => $value );
		}

		protected function parseDropDown( $value, $options = array() ) {
			return array( 'value' => $value, 'options' => $options );
		}

		protected function parseMultiSelect( $value, $options = array() ) {
			return array( 'value' => explode( ',', $value ), 'options' => $options );
		}

		public function parse_order_by( $value ) {
			return $this->parseDropDown( $value, array(
				array( 'date', __( 'Date', 'soledad' ) ),
				array( 'modified', __( 'Latest Modified', 'soledad' ) ),
				array( 'rand', __( 'Random', 'soledad' ) ),
				array( 'random_today', __( 'Random Today', 'soledad' ) ),
				array( 'random_7_day', __( 'Random from last 7 days', 'soledad' ) ),
				array( 'alphabetical_order', __( 'Alphabetical ( A - Z )', 'soledad' ) ),
				array( 'popular', __( 'Popular posts in all time', 'soledad' ) ),
				array( 'popular_month', __( 'Popular posts in once month', 'soledad' ) ),
				array( 'popular7', __( 'Popular posts in once week', 'soledad' ) ),
				array( 'ID', __( 'ID', 'soledad' ) ),
				array( 'author', __( 'Author', 'soledad' ) ),
				array( 'title', __( 'Title', 'soledad' ) ),
				array( 'comment_count', __( 'Comment count', 'soledad' ) ),
				array( 'featured_product', __( 'Featured ( Woo )', 'soledad' ) ),
			) );
		}

		public function parse_order( $value ) {
			return $this->parseDropDown( $value, array(
				array( 'ASC', __( 'Ascending', 'soledad' ) ),
				array( 'DESC', __( 'Descending', 'soledad' ) ),
			) );
		}

		public function parse_post_type( $value ) {
			$options    = array();
			$args       = array(
				'public' => true,
			);
			$post_types = get_post_types( $args );
			foreach ( $post_types as $post_type ) {
				if ( 'attachment' !== $post_type && 'penci_slider' !== $post_type ) {
					$options[] = $post_type;
				}
			}

			return $this->parseMultiSelect( $value, $options );
		}


		public function parse_authors( $value ) {
			$options = $not_in = array();
			if ( empty( $value ) ) {
				return $this->parseMultiSelect( $value, $options );
			}
			$list = explode( ',', $value );
			foreach ( $list as $id ) {
				if ( (int) $id < 0 ) {
					$not_in[] = abs( $id );
				}
			}
			$users = get_users( array( 'include' => array_map( 'abs', $list ) ) );
			foreach ( $users as $user ) {
				$options[] = array(
					'value'  => (string) $user->ID,
					'name'   => $user->data->user_nicename,
					'action' => in_array( (int) $user->ID, $not_in ) ? '-' : '+',
				);
			}

			return $this->parseMultiSelect( $value, $options );
		}

		public function parse_categories( $value ) {
			$options = $not_in = array();
			if ( empty( $value ) ) {
				return $this->parseMultiSelect( $value, $options );
			}
			$list = explode( ',', $value );
			foreach ( $list as $id ) {
				if ( (int) $id < 0 ) {
					$not_in[] = abs( $id );
				}
			}
			$list = get_categories( array( 'include' => array_map( 'abs', $list ) ) );
			foreach ( $list as $obj ) {
				$options[] = array(
					'value'  => (string) $obj->cat_ID,
					'name'   => $obj->cat_name,
					'action' => in_array( (int) $obj->cat_ID, $not_in ) ? '-' : '+',
				);
			}

			return $this->parseMultiSelect( $value, $options );
		}


		public function parse_tags( $value ) {
			$options = $not_in = array();
			if ( empty( $value ) ) {
				return $this->parseMultiSelect( $value, $options );
			}
			$list = explode( ',', $value );
			foreach ( $list as $id ) {
				if ( (int) $id < 0 ) {
					$not_in[] = abs( $id );
				}
			}
			$list = get_tags( array( 'include' => array_map( 'abs', $list ) ) );
			foreach ( $list as $obj ) {
				$options[] = array(
					'value'  => (string) $obj->term_id,
					'name'   => $obj->name,
					'action' => in_array( (int) $obj->term_id, $not_in ) ? '-' : '+',
				);
			}

			return $this->parseMultiSelect( $value, $options );
		}

		public function parse_tax_query( $value ) {
			$options = $not_in = array();
			if ( empty( $value ) ) {
				return $this->parseMultiSelect( $value, $options );
			}
			$list = explode( ',', $value );
			foreach ( $list as $id ) {
				if ( (int) $id < 0 ) {
					$not_in[] = abs( $id );
				}
			}
			$list = get_terms( self::getTaxonomies(), array( 'include' => array_map( 'abs', $list ) ) );
			foreach ( $list as $obj ) {
				$options[] = array(
					'value'  => (string) $obj->term_id,
					'name'   => $obj->name,
					'action' => in_array( (int) $obj->term_id, $not_in ) ? '-' : '+',
				);
			}

			return $this->parseMultiSelect( $value, $options );
		}

		public function parse_by_id( $value ) {
			$options = $not_in = array();
			if ( empty( $value ) ) {
				return $this->parseMultiSelect( $value, $options );
			}
			$list = explode( ',', $value );
			foreach ( $list as $id ) {
				if ( (int) $id < 0 ) {
					$not_in[] = abs( $id );
				}
			}

			$post_type_pre = array();
			$post_types    = get_post_types( array( 'public' => true ) );

			foreach ( $post_types as $post_type ) {
				if ( 'attachment' !== $post_type && 'penci_slider' !== $post_type ) {
					$post_type_pre[] = $post_type;
				}
			}

			$list = get_posts( array( 'post_type' => $post_type_pre, 'include' => array_map( 'abs', $list ) ) );

			foreach ( $list as $obj ) {
				$options[] = array(
					'value'  => (string) $obj->ID,
					'name'   => $obj->post_title,
					'action' => in_array( (int) $obj->ID, $not_in ) ? '-' : '+',
				);
			}

			return $this->parseMultiSelect( $value, $options );
		}

		public function render() {
			echo json_encode( $this->content );
		}

		public function getContent() {
			return $this->content;
		}

		public static function getTaxonomies() {
			$taxonomy_exclude   = (array) apply_filters( 'get_categories_taxonomy', 'category' );
			$taxonomy_exclude[] = 'post_tag';
			$taxonomies         = array();
			foreach ( get_taxonomies() as $taxonomy ) {
				if ( ! in_array( $taxonomy, $taxonomy_exclude ) ) {
					$taxonomies[] = $taxonomy;
				}
			}

			return $taxonomies;
		}

		public static function buildDefault( $settings ) {
			if ( ! isset( $settings['settings'] ) || ! is_array( $settings['settings'] ) ) {
				return '';
			}
			$value = '';
			foreach ( $settings['settings'] as $key => $val ) {
				if ( isset( $val['value'] ) ) {
					$value .= ( empty( $value ) ? '' : '|' ) . $key . ':' . $val['value'];
				}
			}

			return $value;
		}

		public function get_query_parts() {
			return $this->query_parts;
		}

		public static function buildWpQuery( $query, $exclude_id = false ) {
			$data          = self::parseData( $query );
			$query_builder = new PenciSoledadLoopQueryBuilder( $data );
			if ( $exclude_id ) {
				$query_builder->excludeId( $exclude_id );
			}

			return $query_builder->build();
		}


		public static function buildArgsQuery( $query, $exclude_id = false ) {
			$data          = self::parseData( $query );
			$query_builder = new PenciSoledadLoopQueryBuilder( $data );
			if ( $exclude_id ) {
				$query_builder->excludeId( $exclude_id );
			}

			return $query_builder->build_args();
		}

		public static function updateArgsQuerySize( $query, $size ) {
			$data         = self::parseData( $query );
			$data['size'] = $size;

			$data_pre = '';
			foreach ( $data as $key => $val ) {
				if ( $data_pre ) {
					$data_pre .= '|';
				}

				$data_pre .= $key . ':' . $val;
			}

			return $data_pre;
		}

		public static function parseData( $value ) {
			$data         = array();
			$values_pairs = preg_split( '/\|/', $value );
			foreach ( $values_pairs as $pair ) {
				if ( ! empty( $pair ) ) {
					list( $key, $value ) = preg_split( '/\:/', $pair );
					$data[ $key ] = $value;
				}
			}

			return $data;
		}
	}
endif;

if ( ! class_exists( 'PenciSoledadLoopSuggestions' ) ):
	class PenciSoledadLoopSuggestions {
		protected $content = array();
		protected $exclude = array();
		protected $field;

		function __construct( $field, $query, $exclude ) {
			$this->exclude = explode( ',', $exclude );
			$method_name   = 'get_' . preg_replace( '/_out$/', '', $field );
			if ( method_exists( $this, $method_name ) ) {
				$this->$method_name( $query );
			}
		}

		public function get_authors( $query ) {
			$args = ! empty( $query ) ? array(
				'search'         => '*' . $query . '*',
				'search_columns' => array( 'user_nicename' ),
			) : array();
			if ( ! empty( $this->exclude ) ) {
				$args['exclude'] = $this->exclude;
			}
			$users = get_users( $args );
			foreach ( $users as $user ) {
				$this->content[] = array(
					'value' => (string) $user->ID,
					'name'  => (string) $user->data->user_nicename
				);
			}
		}

		public function get_authorsnotin( $query ) {
			$args = ! empty( $query ) ? array(
				'search'         => '*' . $query . '*',
				'search_columns' => array( 'user_nicename' ),
			) : array();
			if ( ! empty( $this->exclude ) ) {
				$args['exclude'] = $this->exclude;
			}
			$users = get_users( $args );
			foreach ( $users as $user ) {
				$this->content[] = array(
					'value' => (string) $user->ID,
					'name'  => (string) $user->data->user_nicename
				);
			}
		}

		public function get_categories( $query ) {
			$args = ! empty( $query ) ? array( 'search' => $query ) : array();
			if ( ! empty( $this->exclude ) ) {
				$args['exclude'] = $this->exclude;
			}
			$categories = get_categories( $args );

			foreach ( $categories as $cat ) {
				$this->content[] = array( 'value' => (string) $cat->cat_ID, 'name' => $cat->cat_name );
			}
		}

		public function get_categorynotin( $query ) {
			$args = ! empty( $query ) ? array( 'search' => $query ) : array();
			if ( ! empty( $this->exclude ) ) {
				$args['exclude'] = $this->exclude;
			}
			$categories = get_categories( $args );

			foreach ( $categories as $cat ) {
				$this->content[] = array( 'value' => (string) $cat->cat_ID, 'name' => $cat->cat_name );
			}
		}

		public function get_tags( $query ) {
			$args = ! empty( $query ) ? array( 'search' => $query ) : array();
			if ( ! empty( $this->exclude ) ) {
				$args['exclude'] = $this->exclude;
			}
			$tags = get_tags( $args );
			foreach ( $tags as $tag ) {
				$this->content[] = array( 'value' => (string) $tag->term_id, 'name' => $tag->name );
			}
		}

		public function get_tagsnotin( $query ) {
			$args = ! empty( $query ) ? array( 'search' => $query ) : array();
			if ( ! empty( $this->exclude ) ) {
				$args['exclude'] = $this->exclude;
			}
			$tags = get_tags( $args );
			foreach ( $tags as $tag ) {
				$this->content[] = array( 'value' => (string) $tag->term_id, 'name' => $tag->name );
			}
		}

		public function get_tax_query( $query ) {
			$args = ! empty( $query ) ? array( 'search' => $query ) : array();
			if ( ! empty( $this->exclude ) ) {
				$args['exclude'] = $this->exclude;
			}
			$tags = get_terms( PenciSoledadLoopSettings::getTaxonomies(), $args );
			foreach ( $tags as $tag ) {
				$this->content[] = array(
					'value' => $tag->term_id,
					'name'  => $tag->name . ' (' . $tag->taxonomy . ')',
				);
			}
		}

		public function get_by_id( $query ) {
			$post_type_pre = array();

			$post_types = get_post_types( array( 'public' => true ) );

			foreach ( $post_types as $post_type ) {
				if ( 'attachment' !== $post_type ) {
					$post_type_pre[] = $post_type;
				}
			}

			$args = ! empty( $query ) ? array(
				's'         => $query,
				'post_type' => $post_type_pre
			) : array( 'post_type' => $post_type_pre );

			if ( ! empty( $this->exclude ) ) {
				$args['exclude'] = $this->exclude;
			}

			$postsqr = new WP_Query( $args );
			$posts   = $postsqr->posts;

			foreach ( $posts as $post ) {
				$this->content[] = array( 'value' => $post->ID, 'name' => $post->post_title );
			}
		}

		public function render() {
			echo json_encode( $this->content );
		}
	}
endif;

if ( ! function_exists( 'penci_build_loop_query' ) ) {
	function penci_build_loop_query( $query, $exclude_id = false ) {
		return PenciSoledadLoopSettings::buildWpQuery( $query, $exclude_id );
	}
}

if ( ! function_exists( 'penci_build_args_query' ) ) {
	function penci_build_args_query( $query, $exclude_id = false ) {
		$query = PenciSoledadLoopSettings::buildArgsQuery( $query, $exclude_id );
		if ( ! empty( $query['post_type'] ) && ( in_array( 'archive-template', (array) $query['post_type'] ) || in_array( 'custom-post-template', (array) $query['post_type'] ) ) ) {
			$query = $GLOBALS['wp_query']->query_vars;
		}

		return $query;
	}
}

if ( ! function_exists( 'penci_get_loop_suggestion' ) ) {
	function penci_get_loop_suggestion() {
		vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie();

		$loop_suggestions = new PenciSoledadLoopSuggestions( vc_post_param( 'field' ), vc_post_param( 'query' ), vc_post_param( 'exclude' ) );
		$loop_suggestions->render();
		die();
	}
}

if ( ! function_exists( 'penci_get_loop_settings_json' ) ) {
	function penci_get_loop_settings_json() {
		vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie();

		$loop_settings = new PenciSoledadLoopSettings( vc_post_param( 'value' ), vc_post_param( 'settings' ) );
		$loop_settings->render();
		die();
	}
}
add_action( 'wp_ajax_wpb_get_loop_suggestion', 'penci_get_loop_suggestion' );
add_action( 'wp_ajax_wpb_get_loop_settings', 'penci_get_loop_settings_json' );

if ( ! function_exists( 'penci_loop_include_templates' ) ) {
	function penci_loop_include_templates() {
		include( trailingslashit( PENCI_SOLEDAD_DIR ) . 'inc/js_composer/params/loop/templates.html' );
	}
}
add_action( 'admin_footer', 'penci_loop_include_templates' );

if ( ! function_exists( 'penci_set_loop_default_value' ) ) {
	function penci_set_loop_default_value( $param ) {
		if ( empty( $param['value'] ) && isset( $param['settings'] ) ) {
			$param['value'] = PenciSoledadLoopSettings::buildDefault( $param );
		}

		return $param;
	}
}
add_filter( 'vc_mapper_attribute_build_query', 'penci_set_loop_default_value' );
