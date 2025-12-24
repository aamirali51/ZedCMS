<?php
if ( ! class_exists( 'Penci_Vc_Params_Helper' ) ):
	class Penci_Vc_Params_Helper {
		public static function params_bookmark_icon() {

			if ( ! defined( 'PENCI_BL_VERSION' ) ) {
				return [];
			}

			$group_name = 'Bookmark Icon';

			return array(
				[
					'type'       => 'penci_responsive_sizes',
					'param_name' => 'penci_bf_icon_sizes',
					'heading'    => __( 'Bookmark Size', 'soledad' ),
					'value'      => '',
					'std'        => '',
					'suffix'     => 'px',
					'min'        => 1,
					'group'      => $group_name,
				],
				[
					'type'       => 'penci_responsive_sizes',
					'param_name' => 'penci_bf_icon_fsizes',
					'heading'    => __( 'Icon Size', 'soledad' ),
					'value'      => '',
					'std'        => '',
					'suffix'     => 'px',
					'min'        => 1,
					'group'      => $group_name,
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Icon Color', 'soledad' ),
					'param_name'       => 'penci_bf_icon_icon_color',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-4',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Icon Border Color', 'soledad' ),
					'param_name'       => 'penci_bf_icon_icon_bcolor',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-4',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Icon Background Color', 'soledad' ),
					'param_name'       => 'penci_bf_icon_icon_bgcolor',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-4',
				],
				// hover
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Icon Hover Color', 'soledad' ),
					'param_name'       => 'penci_bf_icon_icon_hcolor',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-4',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Icon Hover Border Color', 'soledad' ),
					'param_name'       => 'penci_bf_icon_icon_hbcolor',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-4',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Icon Hover Background Color', 'soledad' ),
					'param_name'       => 'penci_bf_icon_icon_hbgcolor',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-4',
				],
				// activated
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Bookmarked Icon Color', 'soledad' ),
					'param_name'       => 'penci_bf_icon_icon_bmcolor',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-4',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Bookmarked Icon Border Color', 'soledad' ),
					'param_name'       => 'penci_bf_icon_icon_bmbcolor',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-4',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Bookmarked Icon Background Color', 'soledad' ),
					'param_name'       => 'penci_bf_icon_icon_bmbgcolor',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-4',
				],
			);
		}

		public static function params_heading() {

			$group_name = 'Heading';

			return array(
				array(
					'type'             => 'textfield',
					'param_name'       => 'block_heading_title',
					'heading'          => esc_html__( 'Block Heading', 'soledad' ),
					'value'            => '',
					'group'            => $group_name,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Show / Hide Block Heading', 'soledad' ),
					'param_name' => 'is_block_heading',
					'value'      => array(
						__( 'Show', 'soledad' ) => 'show',
						__( 'Hide', 'soledad' ) => 'hide',
					),
					'std'        => 'show',
					'group'      => $group_name,
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Heading Title Style', 'soledad' ),
					'param_name' => 'heading_title_style',
					'std'        => '',
					'value'      => array(
						esc_html__( 'Default ', 'soledad' ) => '',
						esc_html__( 'Style 1', 'soledad' )  => 'style-1',
						esc_html__( 'Style 2', 'soledad' )  => 'style-2',
						esc_html__( 'Style 3', 'soledad' )  => 'style-3',
						esc_html__( 'Style 4', 'soledad' )  => 'style-4',
						esc_html__( 'Style 5', 'soledad' )  => 'style-5',
						esc_html__( 'Style 6', 'soledad' )  => 'style-6',
						esc_html__( 'Style 7', 'soledad' )  => 'style-7',
						esc_html__( 'Style 8', 'soledad' )  => 'style-9',
						esc_html__( 'Style 9', 'soledad' )  => 'style-8',
						esc_html__( 'Style 10', 'soledad' ) => 'style-10',
						esc_html__( 'Style 11', 'soledad' ) => 'style-11',
						esc_html__( 'Style 12', 'soledad' ) => 'style-12',
						esc_html__( 'Style 13', 'soledad' ) => 'style-13',
						esc_html__( 'Style 14', 'soledad' ) => 'style-14',
						esc_html__( 'Style 15', 'soledad' ) => 'style-15',
						esc_html__( 'Style 16', 'soledad' ) => 'style-16',
						esc_html__( 'Style 17', 'soledad' ) => 'style-2 style-17',
						esc_html__( 'Style 18', 'soledad' ) => 'style-18',
						esc_html__( 'Style 19', 'soledad' ) => 'style-18 style-19',
						esc_html__( 'Style 20', 'soledad' ) => 'style-18 style-20',
						esc_html__( 'Style 21', 'soledad' ) => 'style-21',
						esc_html__( 'Style 22', 'soledad' ) => 'style-22',
						esc_html__( 'Style 23', 'soledad' ) => 'style-23',
						esc_html__( 'Style 24', 'soledad' ) => 'style-24',
						esc_html__( 'Style 25', 'soledad' ) => 'style-25',
						esc_html__( 'Style 26', 'soledad' ) => 'style-26',
					),
					'group'      => $group_name,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Heading Title', 'soledad' ),
					'param_name'  => 'heading',
					'value'       => 'Block title',
					'std'         => 'Block title',
					'admin_label' => true,
					'description' => esc_html__( 'A title for this block, if you leave it blank the block will not have a title', 'soledad' ),
					'group'       => $group_name,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title url', 'soledad' ),
					'param_name'  => 'heading_title_link',
					'std'         => '',
					'description' => esc_html__( 'A custom url when the block title is clicked', 'soledad' ),
					'group'       => $group_name,
				),
				array(
					'type'        => 'penci_switch',
					'true_state'  => 'yes',
					'false_state' => 'no',
					'default'     => 'no',
					'std'         => 'no',
					'heading'     => __( 'Add icon for title?', 'soledad' ),
					'param_name'  => 'add_title_icon',
					'group'       => $group_name,
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => esc_html__( 'Icon', 'soledad' ),
					'param_name' => 'block_title_icon',
					'std'        => 'block_title_icon',
					'settings'   => array(
						'emptyIcon'    => true,
						'type'         => 'fontawesome',
						'iconsPerPage' => 4000,
					),
					'dependency' => array( 'element' => 'add_title_icon', 'value' => 'true', ),
					'group'      => $group_name,
				),
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Icon Alignment', 'soledad' ),
					'description' => __( 'Select icon alignment.', 'soledad' ),
					'param_name'  => 'block_title_ialign',
					'value'       => array(
						__( 'Left', 'soledad' )  => 'left',
						__( 'Right', 'soledad' ) => 'right',
					),
					'dependency'  => array( 'element' => 'add_title_icon', 'value' => 'true', ),
					'group'       => $group_name,
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Heading Align', 'soledad' ),
					'param_name' => 'block_title_align',
					'std'        => '',
					'value'      => array(
						esc_html__( 'Default ( follow Customize )', 'soledad' ) => '',
						esc_html__( 'Left', 'soledad' )                         => 'pcalign-left',
						esc_html__( 'Center', 'soledad' )                       => 'pcalign-center',
						esc_html__( 'Right', 'soledad' )                        => 'pcalign-right',
					),
					'group'      => $group_name,
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'Align Icon on Style 15',
					'value'       => array(
						'Default( follow Customize )' => '',
						'Right'                       => 'pciconp-right',
						'Left'                        => 'pciconp-left',
					),
					'param_name'  => 'heading_icon_pos',
					'description' => '',
					'dependency'  => array( 'element' => 'heading_title_style', 'value' => array( 'style-15' ) ),
					'group'       => $group_name,
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'Custom Icon on Style 15',
					'value'       => array(
						'Default( follow Customize )' => '',
						'Arrow Right'                 => 'pcicon-right',
						'Arrow Left'                  => 'pcicon-left',
						'Arrow Down'                  => 'pcicon-down',
						'Arrow Up'                    => 'pcicon-up',
						'Star'                        => 'pcicon-star',
						'Bars'                        => 'pcicon-bars',
						'File'                        => 'pcicon-file',
						'Fire'                        => 'pcicon-fire',
						'Book'                        => 'pcicon-book',
					),
					'param_name'  => 'heading_icon',
					'description' => '',
					'dependency'  => array( 'element' => 'heading_title_style', 'value' => array( 'style-15' ) ),
					'group'       => $group_name,
				),
				array(
					'type'       => 'penci_switch',
					'heading'    => esc_html__( 'Turn off Uppercase Block Title', 'soledad' ),
					'param_name' => 'block_title_offupper',
					'value'      => 'no',
					'group'      => $group_name,
				),
				array(
					'type'       => 'penci_number',
					'param_name' => 'block_title_marginbt',
					'heading'    => __( 'Margin Bottom', 'soledad' ),
					'value'      => '',
					'std'        => '',
					'suffix'     => 'px',
					'min'        => 1,
					'group'      => $group_name,
				),
			);
		}

		public static function params_heading_filter_style() {
			$group_name = 'Typo & Color';

			return [
				[
					'type'             => 'textfield',
					'param_name'       => 'filter_heading_style',
					'heading'          => esc_html__( 'Heading Ajax Filter Style', 'soledad' ),
					'value'            => '',
					'group'            => $group_name,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				],
				[
					'type'       => 'penci_responsive_sizes',
					'param_name' => 'link_fsize',
					'heading'    => __( 'Spacing', 'soledad' ),
					'value'      => '',
					'std'        => '',
					'suffix'     => 'px',
					'min'        => 1,
					'group'      => $group_name,
				],
				[
					'type'       => 'penci_responsive_sizes',
					'param_name' => 'nexprev_fsize',
					'heading'    => __( 'Next/Prev Buttons Font Size', 'soledad' ),
					'value'      => '',
					'std'        => '',
					'suffix'     => 'px',
					'min'        => 1,
					'group'      => $group_name,
				],
				[
					'type'        => 'penci_switch',
					'heading'     => esc_html__( 'Use Custom Typography?', 'soledad' ),
					'group'       => $group_name,
					'param_name'  => 'use_custom_typo',
					'true_state'  => 'yes',
					'false_state' => 'no',
					'default'     => 'no',
					'std'         => 'no',
				],
				[
					'type'       => 'google_fonts',
					'heading'    => esc_html__( 'Typography', 'soledad' ),
					'group'      => $group_name,
					'param_name' => 'btitle_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'use_custom_typo', 'value' => array( 'yes' ) ),
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Color', 'soledad' ),
					'param_name'       => 'heading_filter_color',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-6',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Hover & Active Color', 'soledad' ),
					'param_name'       => 'heading_filter_hcolor',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-6',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Dropdown Background Color', 'soledad' ),
					'param_name'       => 'heading_filter_dropdown_bgcolor',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-6',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Dropdown Border Color', 'soledad' ),
					'param_name'       => 'heading_filter_dropdown_bdcolor',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-6',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Dropdown Link Color', 'soledad' ),
					'param_name'       => 'dropdown_l_color',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-6',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Dropdown Hover & Active Color', 'soledad' ),
					'param_name'       => 'dropdown_ha_color',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-6',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Loading Icon Color', 'soledad' ),
					'param_name'       => 'loading_icolor',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-6',
				],
				[
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Loading Overlay Background Color', 'soledad' ),
					'param_name'       => 'loadingo_bg_color',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-6',
				],
				[
					'type'             => 'penci_only_number',
					'heading'          => esc_html__( 'Loading Overlay Background Opacity', 'soledad' ),
					'param_name'       => 'loading_opacity_color',
					'group'            => $group_name,
					'edit_field_class' => 'vc_col-sm-6',
				],
			];
		}


		public static function params_custom_meta_fields() {
			$group_name = 'Custom Meta Field';

			return array_merge( array(
				array(
					'type'             => 'textfield',
					'param_name'       => 'filter_heading',
					'heading'          => esc_html__( 'Custom Post Meta', 'soledad' ),
					'value'            => '',
					'group'            => $group_name,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'        => 'penci_switch',
					'param_name'  => 'cspost_enable',
					'heading'     => esc_html__( 'Showing Custom Post Metas?', 'soledad' ),
					'true_state'  => 'yes',
					'false_state' => 'no',
					'default'     => 'no',
					'std'         => 'no',
					'group'       => $group_name,
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'cspost_cpost_meta',
					'heading'    => esc_html__( 'Custom Post Meta Keys', 'soledad' ),
					'group'      => $group_name,
				),
				array(
					'type'        => 'autocomplete',
					'param_name'  => 'cspost_cpost_acf_meta',
					'heading'     => esc_html__( 'Custom Post ACF Meta Keys', 'soledad' ),
					'description' => 'You can show your own custom fields easily by using the <a href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin.',
					'group'       => $group_name,
					'settings'    => array(
						'multiple'       => true,
						'min_length'     => 1,
						'groups'         => false,
						'unique_values'  => true,
						'display_inline' => true,
						'delay'          => 500,
						'auto_focus'     => true,
						'values'         => self::penci_get_meta_list()
					),
				),
				array(
					'type'        => 'penci_switch',
					'param_name'  => 'cspost_cpost_meta_label',
					'heading'     => esc_html__( 'Showing Custom Post Meta Label', 'soledad' ),
					'true_state'  => 'yes',
					'false_state' => 'no',
					'default'     => 'no',
					'std'         => 'no',
					'group'       => $group_name,
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'cspost_cpost_meta_divider',
					'heading'    => esc_html__( 'Custom Divider Between Meta Label & Meta Value', 'soledad' ),
					'group'      => $group_name,
				),
			) );
		}

		public static function params_heading_filter( $nav = false ) {
			$group_name = 'Heading';

			$nav_options = [];

			if ( $nav ) {
				$nav_options = [
					array(
						'type'        => 'penci_switch',
						'param_name'  => 'paging',
						'heading'     => esc_html__( 'Show Next Previous Navigation', 'soledad' ),
						'true_state'  => 'yes',
						'false_state' => 'no',
						'default'     => 'no',
						'std'         => 'no',
						'group'       => $group_name,
					),
				];
			}

			return array_merge( array(
				array(
					'type'             => 'textfield',
					'param_name'       => 'filter_heading',
					'heading'          => esc_html__( 'Heading Ajax Filter', 'soledad' ),
					'value'            => '',
					'group'            => $group_name,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'       => 'autocomplete',
					'heading'    => __( 'Categories', 'soledad' ),
					'param_name' => 'biggrid_ajaxfilter_cat',
					'settings'   => array(
						'multiple'       => true,
						'min_length'     => 1,
						'groups'         => false,
						'unique_values'  => true,
						'display_inline' => true,
						'delay'          => 500,
						'auto_focus'     => true,
						'values'         => self::penci_get_terms_list( 'category' )
					),
					'std'        => '',
					'group'      => $group_name,
				),
				array(
					'type'       => 'autocomplete',
					'heading'    => __( 'Tags', 'soledad' ),
					'param_name' => 'biggrid_ajaxfilter_tag',
					'settings'   => array(
						'multiple'       => true,
						'min_length'     => 1,
						'groups'         => false,
						'unique_values'  => true,
						'display_inline' => true,
						'delay'          => 500,
						'auto_focus'     => true,
						'values'         => self::penci_get_terms_list( 'post_tag' )
					),
					'std'        => '',
					'group'      => $group_name,
				),
				array(
					'type'       => 'autocomplete',
					'heading'    => __( 'Authors', 'soledad' ),
					'param_name' => 'biggrid_ajaxfilter_author',
					'settings'   => array(
						'multiple'       => true,
						'min_length'     => 1,
						'groups'         => false,
						'unique_values'  => true,
						'display_inline' => true,
						'delay'          => 500,
						'auto_focus'     => true,
						'values'         => self::get_users_list()
					),
					'std'        => '',
					'group'      => $group_name,
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Default Tab Text', 'soledad' ),
					'param_name' => 'group_more_defaultab_text',
					'group'      => $group_name,
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Add Text Before More Icon', 'soledad' ),
					'param_name' => 'group_more_link_text',
					'group'      => $group_name,
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'No Post Found Message', 'soledad' ),
					'param_name' => 'group_more_nopost',
					'group'      => $group_name,
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Loading Icon Style', 'soledad' ),
					'param_name' => 'biggrid_ajax_loading_style',
					'group'      => $group_name,
					'value'      => [
						'Follow Customize' => 'df',
						'Style 1'          => 's9',
						'Style 2'          => 's2',
						'Style 3'          => 's3',
						'Style 4'          => 's4',
						'Style 5'          => 's5',
						'Style 6'          => 's6',
						'Style 7'          => 's1',
					]
				),
			), $nav_options );
		}

		public static function get_users_list() {
			$users     = [];
			$blogusers = get_users();
			foreach ( $blogusers as $user ) {
				$users[] = [
					'value' => $user->ID,
					'label' => $user->display_name
				];
			}

			return $users;
		}

		public static function penci_get_meta_list() {


			$acf_fields_array = [];

			$acf_fields = get_posts( [
				'post_type'      => 'acf-field',
				'posts_per_page' => - 1
			] );

			$fields_support = apply_filters( 'penci_acf_fields', [
				'text',
				'textarea',
				'number',
				'range',
				'email',
				'url'
			] );

			if ( $acf_fields ) {
				foreach ( $acf_fields as $acf_field ) {
					$field_data = unserialize( $acf_field->post_content );
					if ( in_array( $field_data['type'], $fields_support ) ) {
						$acf_fields_array[] = [
							'value' => $acf_field->post_excerpt,
							'label' => $acf_field->post_title
						];
					}
				}
			}

			return $acf_fields_array;

		}

		public static function penci_get_terms_list( $tax ) {
			$post_cats_args = get_terms( [ 'taxonomy' => $tax, 'hide_empty' => true ] );
			$post_terms     = [];
			if ( ! empty( $post_cats_args ) ) {
				foreach ( $post_cats_args as $post_cat ) {
					$post_terms[] = [
						'value' => $post_cat->term_id,
						'label' => $post_cat->name
					];
				}
			}

			return $post_terms;
		}

		public static function params_heading_typo_color( $group_color = '' ) {
			if ( ! $group_color ) {
				$group_color = 'Typo & Color';
			}

			return array(
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_meta_settings',
					'heading'          => esc_html__( 'Block Heading Title', 'soledad' ),
					'value'            => '',
					'group'            => $group_color,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Title Color', 'soledad' ),
					'param_name'       => 'block_title_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Title Hover Color', 'soledad' ),
					'param_name'       => 'block_title_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Border Color', 'soledad' ),
					'param_name'       => 'btitle_bcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Border Outer Color', 'soledad' ),
					'param_name'       => 'btitle_outer_bcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Border Bottom for Heading Style 5, 10, 11, 12', 'soledad' ),
					'param_name'       => 'btitle_style5_bcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array(
						'element' => 'heading_title_style',
						'value'   => array( 'style-5', 'style-10', 'style-11', 'style-12' )
					),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Small Border Bottom for Heading Style 7 & Style 8', 'soledad' ),
					'param_name'       => 'btitle_style78_bcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array(
						'element' => 'heading_title_style',
						'value'   => array( 'style-7', 'style-9' )
					),
				),

				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Border Top for Heading Style 10', 'soledad' ),
					'param_name'       => 'btitle_style10_btopcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'heading_title_style', 'value' => array( 'style-10' ) ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Background Shapes for Heading Styles 11, 12, 13', 'soledad' ),
					'param_name'       => 'btitle_shapes_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array(
						'element' => 'heading_title_style',
						'value'   => array( 'style-13', 'style-11', 'style-12' )
					),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Background Color for Icon on Style 15', 'soledad' ),
					'param_name'       => 'bgstyle15_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'heading_title_style', 'value' => array( 'style-15' ) ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Icon Color on Style 15', 'soledad' ),
					'param_name'       => 'iconstyle15_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'heading_title_style', 'value' => array( 'style-15' ) ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Color for Lines on Styles 18, 19, 20', 'soledad' ),
					'param_name'       => 'cl_lines',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array(
						'element' => 'heading_title_style',
						'value'   => array(
							'style-18',
							'style-18 style-19',
							'style-18 style-20'
						)
					),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Background Color', 'soledad' ),
					'param_name'       => 'btitle_bgcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6'
				),

				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Background Outer Color', 'soledad' ),
					'param_name'       => 'btitle_outer_bgcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'       => 'attach_image',
					'heading'    => esc_html__( 'Custom Background Image for Style 9', 'soledad' ),
					'param_name' => 'btitle_style9_bgimg',
					'group'      => $group_color,
					'dependency' => array( 'element' => 'heading_title_style', 'value' => array( 'style-8' ) ),
				),
				array(
					'type'        => 'penci_switch',
					'heading'     => __( 'Custom Font Family for Block Title', 'soledad' ),
					'param_name'  => 'use_btitle_typo',
					'true_state'  => 'yes',
					'false_state' => 'no',
					'default'     => 'no',
					'std'         => 'no',
					'group'       => $group_color,
				),
				array(
					'type'       => 'google_fonts',
					'group'      => $group_color,
					'param_name' => 'btitle_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'use_btitle_typo', 'value' => 'yes' ),
				),
				array(
					'type'       => 'penci_responsive_sizes',
					'param_name' => 'btitle_fsize',
					'heading'    => __( 'Font Size for Block Title', 'soledad' ),
					'value'      => '',
					'std'        => '',
					'suffix'     => 'px',
					'min'        => 1,
					'group'      => $group_color,
				)
			);
		}

		public static function params_container_width( $default = 3 ) {
			return array(
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Element Columns', 'soledad' ),
					'param_name' => 'penci_block_width',
					'std'        => $default,
					'value'      => array(
						__( '1 Column ( Small Container Width)', 'soledad' )    => '1',
						__( '2 Columns ( Medium Container Width )', 'soledad' ) => '2',
						__( '3 Columns ( Large Container Width )', 'soledad' )  => '3',
					),
				)
			);
		}

		public static function extra_params() {
			return array(
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS box', 'soledad' ),
					'param_name' => 'css',
					'group'      => __( 'Design Options', 'soledad' ),
				),
				penci_get_vc_responsive_spacing_map(),
			);
		}

		public static function heading_block_params( $block_title_df = true ) {
			return array(
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_block_title_1',
					'heading'          => esc_html__( 'Heading Title', 'soledad' ),
					'value'            => '',
					'group'            => 'Heading',
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Heading Title Style', 'soledad' ),
					'param_name' => 'heading_title_style',
					'std'        => '',
					'value'      => array(
						esc_html__( 'Default ( follow Customize )', 'soledad' ) => '',
						esc_html__( 'Style 1', 'soledad' )                      => 'style-1',
						esc_html__( 'Style 2', 'soledad' )                      => 'style-2',
						esc_html__( 'Style 3', 'soledad' )                      => 'style-3',
						esc_html__( 'Style 4', 'soledad' )                      => 'style-4',
						esc_html__( 'Style 5', 'soledad' )                      => 'style-5',
						esc_html__( 'Style 6', 'soledad' )                      => 'style-6',
						esc_html__( 'Style 7', 'soledad' )                      => 'style-7',
						esc_html__( 'Style 8', 'soledad' )                      => 'style-9',
						esc_html__( 'Style 9', 'soledad' )                      => 'style-8',
						esc_html__( 'Style 10', 'soledad' )                     => 'style-10',
						esc_html__( 'Style 11', 'soledad' )                     => 'style-11',
						esc_html__( 'Style 12', 'soledad' )                     => 'style-12',
						esc_html__( 'Style 13', 'soledad' )                     => 'style-13',
						esc_html__( 'Style 14', 'soledad' )                     => 'style-14',
						esc_html__( 'Style 15', 'soledad' )                     => 'style-15',
						esc_html__( 'Style 16', 'soledad' )                     => 'style-16',
						esc_html__( 'Style 17', 'soledad' )                     => 'style-2 style-17',
						esc_html__( 'Style 18', 'soledad' )                     => 'style-18',
						esc_html__( 'Style 19', 'soledad' )                     => 'style-18 style-19',
						esc_html__( 'Style 20', 'soledad' )                     => 'style-18 style-20',
						esc_html__( 'Style 21', 'soledad' )                     => 'style-21',
						esc_html__( 'Style 22', 'soledad' )                     => 'style-22',
						esc_html__( 'Style 23', 'soledad' )                     => 'style-23',
						esc_html__( 'Style 24', 'soledad' )                     => 'style-24',
						esc_html__( 'Style 25', 'soledad' )                     => 'style-25',
						esc_html__( 'Style 26', 'soledad' )                     => 'style-26',
						esc_html__( 'Style 27', 'soledad' )                     => 'style-7',
					),
					'group'      => 'Heading',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Heading Title', 'soledad' ),
					'param_name'  => 'heading',
					'value'       => $block_title_df ? esc_html__( 'Block Title', 'soledad' ) : '',
					'std'         => $block_title_df ? esc_html__( 'Block Title', 'soledad' ) : '',
					'admin_label' => true,
					'description' => esc_html__( 'A title for this block, if you leave it blank the block will not have a title', 'soledad' ),
					'group'       => 'Heading',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title Url', 'soledad' ),
					'param_name'  => 'heading_title_link',
					'std'         => '',
					'description' => esc_html__( 'A custom url when the block title is clicked', 'soledad' ),
					'group'       => 'Heading',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Heading Align', 'soledad' ),
					'param_name' => 'heading_title_align',
					'std'        => '',
					'value'      => array(
						esc_html__( 'Default ( follow Customize )', 'soledad' ) => '',
						esc_html__( 'Left', 'soledad' )                         => 'pcalign-left',
						esc_html__( 'Center', 'soledad' )                       => 'pcalign-center',
						esc_html__( 'Right', 'soledad' )                        => 'pcalign-right',
					),
					'group'      => 'Heading',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'Align Icon on Style 15',
					'value'       => array(
						'Default( follow Customize )' => '',
						'Right'                       => 'pciconp-right',
						'Left'                        => 'pciconp-left',
					),
					'param_name'  => 'heading_icon_pos',
					'description' => '',
					'dependency'  => array( 'element' => 'heading_title_style', 'value' => array( 'style-15' ) ),
					'group'       => 'Heading',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'Custom Icon on Style 15',
					'value'       => array(
						'Default( follow Customize )' => '',
						'Arrow Right'                 => 'pcicon-right',
						'Arrow Left'                  => 'pcicon-left',
						'Arrow Down'                  => 'pcicon-down',
						'Arrow Up'                    => 'pcicon-up',
						'Star'                        => 'pcicon-star',
						'Bars'                        => 'pcicon-bars',
						'File'                        => 'pcicon-file',
						'Fire'                        => 'pcicon-fire',
						'Book'                        => 'pcicon-book',
					),
					'param_name'  => 'heading_icon',
					'description' => '',
					'dependency'  => array( 'element' => 'heading_title_style', 'value' => array( 'style-15' ) ),
					'group'       => 'Heading',
				),
			);
		}

		public static function params_latest_posts_typo_color() {
			$group_color = 'Typo & Color';

			$style_big_post = array(
				'mixed',
				'mixed-4',
				'mixed-2',
				'standard-grid',
				'standard-grid-2',
				'standard-list',
				'standard-boxed-1',
				'classic-grid',
				'classic-grid-2',
				'classic-list',
				'classic-boxed-1',
				'overlay-grid',
				'overlay-grid-2',
				'overlay-list',
				'overlay-boxed-1'
			);
			$color_big_post = array( 'mixed-2', 'overlay-grid', 'overlay-grid-2', 'overlay-list', 'overlay-boxed-1' );

			return array(
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_ptittle_settings',
					'heading'          => esc_html__( 'General Posts', 'soledad' ),
					'value'            => '',
					'group'            => $group_color,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),

				// Post title
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Border Color', 'soledad' ),
					'param_name'       => 'pborder_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array(
						'element' => 'style',
						'value'   => array(
							'boxed-1',
							'boxed-2',
							'mixed',
							'mixed-2',
							'standard-boxed-1'
						)
					),
				),

				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Title Color', 'soledad' ),
					'param_name'       => 'ptitle_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Title Hover Color', 'soledad' ),
					'param_name'       => 'ptitle_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'ptitle_fsize',
					'heading'          => __( 'Font Size for Post Title', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'checkbox',
					'heading'          => __( 'Custom Font Family for Post Title', 'soledad' ),
					'param_name'       => 'use_ptitle_typo',
					'value'            => array( __( 'Yes', 'soledad' ) => 'yes' ),
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'       => 'google_fonts',
					'group'      => $group_color,
					'param_name' => 'ptitle_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'use_ptitle_typo', 'value' => 'yes' ),
				),
				array(
					'type'       => 'penci_separator',
					'param_name' => 'penci_separator1',
					'group'      => $group_color,
				),
				// Post meta
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Meta Color', 'soledad' ),
					'param_name'       => 'pmeta_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Meta Hover Color', 'soledad' ),
					'param_name'       => 'pmeta_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Author Color', 'soledad' ),
					'param_name'       => 'pauthor_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Meta Border Color', 'soledad' ),
					'param_name'       => 'pmeta_border_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'pmeta_fsize',
					'heading'          => __( 'Font Size for Post Meta', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'checkbox',
					'heading'          => __( 'Custom Font Family for Post Meta', 'soledad' ),
					'param_name'       => 'use_pmeta_typo',
					'value'            => array( __( 'Yes', 'soledad' ) => 'yes' ),
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'       => 'google_fonts',
					'group'      => $group_color,
					'param_name' => 'pmeta_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'use_pmeta_typo', 'value' => 'yes' ),
				),

				array(
					'type'       => 'penci_separator',
					'param_name' => 'penci_separator2',
					'group'      => $group_color,
				),

				// Post Excrept
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Excrept Color', 'soledad' ),
					'param_name'       => 'pexcrept_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'pexcrept_fsize',
					'heading'          => __( 'Font Size for Post Excrept', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'checkbox',
					'heading'          => __( 'Custom Font Family for Post Excrept', 'soledad' ),
					'param_name'       => 'use_pexcrept_typo',
					'value'            => array( __( 'Yes', 'soledad' ) => 'yes' ),
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'       => 'google_fonts',
					'group'      => $group_color,
					'param_name' => 'pexcrept_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'use_pexcrept_typo', 'value' => 'yes' ),
				),
				array(
					'type'       => 'penci_separator',
					'param_name' => 'penci_separator2',
					'group'      => $group_color,
				),
				// Category
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Categories Color', 'soledad' ),
					'param_name'       => 'pcat_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Categories Hover Color', 'soledad' ),
					'param_name'       => 'pcat_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'pcat_fsize',
					'heading'          => __( 'Font Size for Post Categories', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'checkbox',
					'heading'          => __( 'Custom Font Family for Categories', 'soledad' ),
					'param_name'       => 'use_pcat_typo',
					'value'            => array( __( 'Yes', 'soledad' ) => 'yes' ),
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'       => 'google_fonts',
					'group'      => $group_color,
					'param_name' => 'pcat_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'use_pcat_typo', 'value' => 'yes' ),
				),
				array(
					'type'       => 'penci_separator',
					'param_name' => 'penci_separator2',
					'group'      => $group_color,
				),
				// Continue reading
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Continue reading Color', 'soledad' ),
					'param_name'       => 'prmore_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Continue reading Hover Color', 'soledad' ),
					'param_name'       => 'prmore_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'prmore_fsize',
					'heading'          => __( 'Font Size for Continue reading', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'checkbox',
					'heading'          => __( 'Custom Font Family for Continue reading', 'soledad' ),
					'param_name'       => 'use_prmore_typo',
					'value'            => array( __( 'Yes', 'soledad' ) => 'yes' ),
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'       => 'google_fonts',
					'group'      => $group_color,
					'param_name' => 'prmore_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'use_prmore_typo', 'value' => 'yes' ),
				),
				array(
					'type'       => 'penci_separator',
					'param_name' => 'penci_separator2',
					'group'      => $group_color,
				),

				// Share
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Social Share Color', 'soledad' ),
					'param_name'       => 'pshare_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Social Share Hover Color', 'soledad' ),
					'param_name'       => 'pshare_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Social Share Border Color', 'soledad' ),
					'param_name'       => 'pshare_border_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'pshare_fsize',
					'heading'          => __( 'Font Size for Social Share Icons', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),

				// Big Post
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_bptittle_settings',
					'heading'          => esc_html__( 'Big Posts', 'soledad' ),
					'value'            => '',
					'group'            => $group_color,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
					'dependency'       => array( 'element' => 'style', 'value' => $style_big_post ),
				),

				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Title Color', 'soledad' ),
					'param_name'       => 'bptitle_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => $color_big_post ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Title Hover Color', 'soledad' ),
					'param_name'       => 'bptitle_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => $color_big_post ),
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'bptitle_fsize',
					'heading'          => __( 'Font Size for Post Title', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => $style_big_post ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Author Color', 'soledad' ),
					'param_name'       => 'bpauthor_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => $color_big_post ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Meta Border Color', 'soledad' ),
					'param_name'       => 'bpmeta_border_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => $color_big_post ),
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'bpmeta_fsize',
					'heading'          => __( 'Font Size for Post Meta', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => $style_big_post ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Categories Color', 'soledad' ),
					'param_name'       => 'bpcat_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => $color_big_post ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Categories Hover Color', 'soledad' ),
					'param_name'       => 'bpcat_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => $color_big_post ),
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'bpcat_fsize',
					'heading'          => __( 'Font Size for Post Categories', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => $style_big_post ),
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'bpexcerpt_size',
					'heading'          => __( 'Font Size for Post Excerpt', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array(
						'element' => 'style',
						'value'   => array(
							'mixed',
							'mixed-4',
							'standard-grid',
							'standard-grid-2',
							'standard-list',
							'standard-boxed-1',
							'classic-grid',
							'classic-grid-2',
							'classic-list'
						)
					),
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'bsocialshare_size',
					'heading'          => __( 'Font Size for Post Social Share', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => $style_big_post ),
				),

				// Pagination
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_pag_settings',
					'heading'          => esc_html__( 'Pagination', 'soledad' ),
					'value'            => '',
					'group'            => $group_color,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'pagination_icon',
					'heading'          => __( 'Font size for Load More Icon', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'pagination_size',
					'heading'          => __( 'Font Size for Pagination', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Pagination Text Color', 'soledad' ),
					'param_name'       => 'pagination_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Pagination Border Color', 'soledad' ),
					'param_name'       => 'pagination_bordercolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Pagination Background Color', 'soledad' ),
					'param_name'       => 'pagination_bgcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Pagination Hover Text Color', 'soledad' ),
					'param_name'       => 'pagination_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Pagination Hover Border Color', 'soledad' ),
					'param_name'       => 'pagination_hbordercolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Pagination Hover Background Color', 'soledad' ),
					'param_name'       => 'pagination_hbgcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
			);
		}

		public static function params_featured_cat_typo_color() {
			$group_color = 'Typo & Color';

			return array(
				// Post title
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_ptittle_settings',
					'heading'          => esc_html__( 'Posts General Options', 'soledad' ),
					'value'            => '',
					'group'            => $group_color,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Border Color', 'soledad' ),
					'param_name'       => 'pborder_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				// Post title
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_ptittle_settings',
					'heading'          => esc_html__( 'Posts Title', 'soledad' ),
					'value'            => '',
					'group'            => $group_color,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Title Color', 'soledad' ),
					'param_name'       => 'ptitle_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Title Hover Color', 'soledad' ),
					'param_name'       => 'ptitle_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Title Color of Big Post', 'soledad' ),
					'param_name'       => 'bptitle_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => array( 'style-14', 'style-15' ) ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Title Hover Color of Big Post', 'soledad' ),
					'param_name'       => 'bptitle_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => array( 'style-14', 'style-15' ) ),
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'ptitle_fsize',
					'heading'          => __( 'Font Size for Post Title', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'bptitle_fsize',
					'heading'          => __( 'Font Size for Title of Big Post', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array(
						'element' => 'style',
						'value'   => array(
							'style-1',
							'style-2',
							'style-6',
							'style-10',
							'style-14',
							'style-15'
						)
					),
				),
				array(
					'type'             => 'checkbox',
					'heading'          => __( 'Custom Font Family for Post Title', 'soledad' ),
					'param_name'       => 'use_ptitle_typo',
					'value'            => array( __( 'Yes', 'soledad' ) => 'yes' ),
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'       => 'google_fonts',
					'group'      => $group_color,
					'param_name' => 'ptitle_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'use_ptitle_typo', 'value' => 'yes' ),
				),

				// Post meta
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_pmeta_settings',
					'heading'          => esc_html__( 'Posts Meta', 'soledad' ),
					'value'            => '',
					'group'            => $group_color,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Meta Color', 'soledad' ),
					'param_name'       => 'pmeta_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Meta Hover Color', 'soledad' ),
					'param_name'       => 'pmeta_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Meta Color for Big Posts', 'soledad' ),
					'param_name'       => 'bpmeta_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => array( 'style-14', 'style-15' ) ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Meta Hover Color for Big Posts', 'soledad' ),
					'param_name'       => 'bpmeta_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => array( 'style-14', 'style-15' ) ),
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'pmeta_fsize',
					'heading'          => __( 'Font Size for Post Meta', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'checkbox',
					'heading'          => __( 'Custom Font Family for Post Meta', 'soledad' ),
					'param_name'       => 'use_pmeta_typo',
					'value'            => array( __( 'Yes', 'soledad' ) => 'yes' ),
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'       => 'google_fonts',
					'group'      => $group_color,
					'param_name' => 'pmeta_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'use_pmeta_typo', 'value' => 'yes' ),
				),
				// Post excrept
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_pexcrept_settings',
					'heading'          => esc_html__( 'Posts Excerpt', 'soledad' ),
					'value'            => '',
					'group'            => $group_color,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Excerpt Color', 'soledad' ),
					'param_name'       => 'pexcerpt_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'pexcerpt_fsize',
					'heading'          => __( 'Font Size for Post Excerpt', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'checkbox',
					'heading'          => __( 'Custom Font Family for Post Excerpt', 'soledad' ),
					'param_name'       => 'use_pexcerpt_typo',
					'value'            => array( __( 'Yes', 'soledad' ) => 'yes' ),
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'       => 'google_fonts',
					'group'      => $group_color,
					'param_name' => 'pexcerpt_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'use_pexcerpt_typo', 'value' => 'yes' ),
				),

				// Category
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_pcat_settings',
					'heading'          => esc_html__( 'Categories', 'soledad' ),
					'value'            => '',
					'group'            => $group_color,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
					'dependency'       => array( 'element' => 'style', 'value' => 'style-8' ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Categories Color', 'soledad' ),
					'param_name'       => 'pcat_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => 'style-8' ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Categories Hover Color', 'soledad' ),
					'param_name'       => 'pcat_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => 'style-8' ),
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'pcat_fsize',
					'heading'          => __( 'Font Size for Post Categories', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'       => array( 'element' => 'style', 'value' => 'style-8' ),
				),
				array(
					'type'       => 'google_fonts',
					'group'      => $group_color,
					'param_name' => 'pcat_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'style', 'value' => 'style-8' ),
				),
			);
		}

		public static function params_popular_posts_typo_color() {
			$group_color = 'Typo & Color';

			return array(
				// Post title
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_ptittle_settings',
					'heading'          => esc_html__( 'Posts Title', 'soledad' ),
					'value'            => '',
					'group'            => $group_color,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Title Color', 'soledad' ),
					'param_name'       => 'ptitle_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Title Hover Color', 'soledad' ),
					'param_name'       => 'ptitle_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'ptitle_fsize',
					'heading'          => __( 'Font Size for Post Title', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'checkbox',
					'heading'          => __( 'Custom Font Family for Post Title', 'soledad' ),
					'param_name'       => 'use_ptitle_typo',
					'value'            => array( __( 'Yes', 'soledad' ) => 'yes' ),
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'       => 'google_fonts',
					'group'      => $group_color,
					'param_name' => 'ptitle_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'use_ptitle_typo', 'value' => 'yes' ),
				),

				// Post meta
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_pmeta_settings',
					'heading'          => esc_html__( 'Posts Meta', 'soledad' ),
					'value'            => '',
					'group'            => $group_color,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Post Meta Color', 'soledad' ),
					'param_name'       => 'pmeta_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'penci_number',
					'param_name'       => 'pmeta_fsize',
					'heading'          => __( 'Font Size for Post Meta', 'soledad' ),
					'value'            => '',
					'std'              => '',
					'suffix'           => 'px',
					'min'              => 1,
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'checkbox',
					'heading'          => __( 'Custom Font Family for Post Meta', 'soledad' ),
					'param_name'       => 'use_pmeta_typo',
					'value'            => array( __( 'Yes', 'soledad' ) => 'yes' ),
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'       => 'google_fonts',
					'group'      => $group_color,
					'param_name' => 'pmeta_typo',
					'value'      => '',
					'dependency' => array( 'element' => 'use_pmeta_typo', 'value' => 'yes' ),
				),

				// Dot style
				array(
					'type'             => 'textfield',
					'param_name'       => 'heading_pmeta_settings',
					'heading'          => esc_html__( 'Dots Slider', 'soledad' ),
					'value'            => '',
					'group'            => $group_color,
					'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Dot background color', 'soledad' ),
					'param_name'       => '_dot_color',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Dot border and background active color', 'soledad' ),
					'param_name'       => 'dot_hcolor',
					'group'            => $group_color,
					'edit_field_class' => 'vc_col-sm-6',
				),
			);
		}

		/**
		 * Get image sizes.
		 *
		 * Retrieve available image sizes after filtering `include` and `exclude` arguments.
		 */
		public static function get_list_image_sizes( $default = false ) {
			$wp_image_sizes = self::get_all_image_sizes();

			$image_sizes = array();

			if ( $default ) {
				$image_sizes[ esc_html__( 'Default', 'soledad' ) ] = '';
			}

			foreach ( $wp_image_sizes as $size_key => $size_attributes ) {
				$control_title = ucwords( str_replace( '_', ' ', $size_key ) );
				if ( is_array( $size_attributes ) ) {
					$control_title .= sprintf( ' - %d x %d', $size_attributes['width'], $size_attributes['height'] );
				}

				$image_sizes[ $control_title ] = $size_key;
			}

			$image_sizes[ esc_html__( 'Full', 'soledad' ) ] = 'full';

			return $image_sizes;
		}

		public static function get_all_image_sizes() {
			global $_wp_additional_image_sizes;

			$default_image_sizes = array( 'thumbnail', 'medium', 'medium_large', 'large' );

			$image_sizes = array();

			foreach ( $default_image_sizes as $size ) {
				$image_sizes[ $size ] = [
					'width'  => (int) get_option( $size . '_size_w' ),
					'height' => (int) get_option( $size . '_size_h' ),
					'crop'   => (bool) get_option( $size . '_crop' ),
				];
			}

			if ( $_wp_additional_image_sizes ) {
				$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
			}

			return $image_sizes;
		}
	}
endif;
