<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPBakeryShortCode_Penci_Count_Down extends WPBakeryShortCode {
	public function __construct( $settings ) {
		parent::__construct( $settings );
		$this->jsCssScripts();
	}

	public function jsCssScripts() {
		wp_enqueue_script( 'jquery.plugin', PENCI_SOLEDAD_URL . '/js/jquery.plugin.min.js', array( 'jquery' ), '2.0.2', true );
		wp_enqueue_script( 'countdown', PENCI_SOLEDAD_URL . '/js/jquery.countdown.min.js', array( 'jquery' ), '2.0.2', true );

	}
}

class WPBakeryShortCode_Penci_Counter_Up extends WPBakeryShortCode {
	public function __construct( $settings ) {
		parent::__construct( $settings );
		$this->jsCssScripts();
	}

	public function jsCssScripts() {
		wp_enqueue_script( 'waypoints', PENCI_SOLEDAD_URL . '/js/waypoints.min.js', array( 'jquery' ), '2.0.3', true );
		wp_enqueue_script( 'jquery-counterup', PENCI_SOLEDAD_URL . '/js/jquery.counterup.min.js', array(
			'jquery',
			'waypoints'
		), '1.0', true );
	}
}

class WPBakeryShortCode_Penci_Media_Carousel extends WPBakeryShortCodesContainer {
}

class WPBakeryShortCode_Penci_Custom_Slider extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Facebook_Page extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Fancy_Heading extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Google_Map extends WPBakeryShortCode {
	public function __construct( $settings ) {
		parent::__construct( $settings );
		$this->jsCssScripts();
	}

	public function jsCssScripts() {
		$api = get_theme_mod( 'penci_map_api_key' );

		if ( ! $api ) {
			$api = 'AIzaSyBzbXkmI1iibQGKhyS_YbIDEyDEfBK5_bI';
		}
		$http = is_ssl() ? 'https://' : 'http://';

		wp_enqueue_script( 'google-map', esc_url( $http . 'maps.google.com/maps/api/js?key=' . esc_attr( $api ) ), array(), '', true );
	}
}

class WPBakeryShortCode_Penci_Image_Gallery extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Info_Box extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Instagram extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Latest_Tweets extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Login_Form extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Mailchimp extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Open_Hours extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Pinterest extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Pricing_Table extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Progress_Bar extends WPBakeryShortCode {
	public static function convertAttributesToNewProgressBar( $atts ) {
		if ( isset( $atts['values'] ) && strlen( $atts['values'] ) > 0 ) {
			$values = vc_param_group_parse_atts( $atts['values'] );
			if ( ! is_array( $values ) ) {
				$temp        = explode( ',', $atts['values'] );
				$paramValues = array();
				foreach ( $temp as $value ) {
					$data             = explode( '|', $value );
					$colorIndex       = 2;
					$newLine          = array();
					$newLine['value'] = isset( $data[0] ) ? $data[0] : 0;
					$newLine['label'] = isset( $data[1] ) ? $data[1] : '';
					if ( isset( $data[1] ) && preg_match( '/^\d{1,3}\%$/', $data[1] ) ) {
						$colorIndex       += 1;
						$newLine['value'] = (float) str_replace( '%', '', $data[1] );
						$newLine['label'] = isset( $data[2] ) ? $data[2] : '';
					}
					if ( isset( $data[ $colorIndex ] ) ) {
						$newLine['customcolor'] = $data[ $colorIndex ];
					}
					$paramValues[] = $newLine;
				}
				$atts['values'] = urlencode( json_encode( $paramValues ) );
			}
		}

		return $atts;
	}
}

class WPBakeryShortCode_Penci_Single_Video extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Social_Counter extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Social_Media extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Team_Member extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Testimonial_Slider extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Testimonial extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Text_Block extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Video_Playlist extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Weather extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Popular_Cat extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Container extends WPBakeryShortCode {
	public $nonDraggableClass = 'vc-non-draggable-row';
	protected $predefined_atts = array(
		'el_class' => '',
	);

	/**
	 * @param $settings
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );
		$this->shortcodeScripts();
	}

	protected function shortcodeScripts() {
		wp_register_script( 'vc_jquery_skrollr_js', vc_asset_url( 'lib/bower/skrollr/dist/skrollr.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		wp_register_script( 'vc_youtube_iframe_api_js', '//www.youtube.com/iframe_api', array(), WPB_VC_VERSION, true );
	}

	public function template( $content = '' ) {
		return $this->contentAdmin( $this->atts );
	}

	public function contentAdmin( $atts, $content = null ) {
		$width = $el_class = '';
		$atts  = shortcode_atts( $this->predefined_atts, $atts );

		extract( $atts );

		$output          = '';
		$count_width     = $width && is_array( $width ) ? count( $width ) : 1;
		$column_controls = $this->getColumnControls( $this->settings( 'controls' ) );

		for ( $i = 0; $i < $count_width; $i ++ ) {
			$output .= '<div data-manh="" data-element_type="' . $this->settings['base'] . '" class="' . $this->cssAdminClass() . '">';
			$output .= str_replace( '%column_size%', 1, $column_controls );
			$output .= '<div class="wpb_element_wrapper">';
			$output .= '<div class="vc_row vc_row-fluid wpb_row_container vc_container_for_children">';
			if ( '' === $content && ! empty( $this->settings['default_content_in_template'] ) ) {
				$output .= do_shortcode( shortcode_unautop( $this->settings['default_content_in_template'] ) );
			} else {
				$output .= do_shortcode( shortcode_unautop( $content ) );

			}
			$output .= '</div>';
			if ( isset( $this->settings['params'] ) ) {
				$inner = '';
				foreach ( $this->settings['params'] as $param ) {
					if ( ! isset( $param['param_name'] ) ) {
						continue;
					}
					$param_value = isset( $atts[ $param['param_name'] ] ) ? $atts[ $param['param_name'] ] : '';
					if ( is_array( $param_value ) ) {
						// Get first element from the array
						reset( $param_value );
						$first_key   = key( $param_value );
						$param_value = $param_value[ $first_key ];
					}
					$inner .= $this->singleParamHtmlHolder( $param, $param_value );
				}
				$output .= $inner;
			}
			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}

	public function getColumnControls( $controls, $extended_css = '' ) {
		$output       = '<div class="penci_container_controls vc_controls vc_controls-row controls_row vc_clearfix">';
		$controls_end = '</div>';

		$icon     = $this->settings( 'icon' );
		$img_icon = $icon ? '<img  src="' . $icon . '" alt="icon"/>' : '';

		$controls_layout = $this->getLayoutsControl();
		$controls_add    = '';
		$controls_title  = '<span class="wpb_element_title"> ' . $img_icon . '</span>';
		$controls_move   = '<a class="vc_control column_move vc_column-move" href="#" title="' . __( 'Drag row to reorder', 'soledad' ) . '" data-vc-control="move"><i class="vc-composer-icon vc-c-icon-dragndrop"></i>' . $controls_title . '</a>';
		$controls_delete = '<a class="vc_control column_delete vc_column-delete" href="#" title="' . __( 'Delete this row', 'soledad' ) . '" data-vc-control="delete"><i class="vc-composer-icon vc-c-icon-delete_empty"></i></a>';
		$controls_edit   = ' <a class="vc_control column_edit vc_column-edit" href="#" title="' . __( 'Edit this row', 'soledad' ) . '" data-vc-control="edit"><i class="vc-composer-icon vc-c-icon-mode_edit"></i></a>';
		$controls_clone  = ' <a class="vc_control column_clone vc_column-clone" href="#" title="' . __( 'Clone this row', 'soledad' ) . '" data-vc-control="clone"><i class="vc-composer-icon vc-c-icon-content_copy"></i></a>';
		$controls_toggle = ' <a class="vc_control column_toggle vc_column-toggle" href="#" title="' . __( 'Toggle row', 'soledad' ) . '" data-vc-control="toggle"><i class="vc-composer-icon vc-c-icon-arrow_drop_down"></i></a>';
		$editAccess      = vc_user_access_check_shortcode_edit( $this->shortcode );
		$allAccess       = vc_user_access_check_shortcode_all( $this->shortcode );


		if ( is_array( $controls ) && ! empty( $controls ) ) {

			foreach ( $controls as $control ) {
				$control_var = 'controls_' . $control;
				if ( ( $editAccess && 'edit' == $control ) || $allAccess ) {
					if ( isset( ${$control_var} ) ) {
						$output .= ${$control_var};
					}
				}
			}
			$output .= $controls_end;
		} elseif ( is_string( $controls ) ) {
			$control_var = 'controls_' . $controls;
			if ( ( $editAccess && 'edit' === $controls ) || $allAccess ) {
				if ( isset( ${$control_var} ) ) {
					$output .= ${$control_var} . $controls_end;
				}
			}
		} else {
			$row_edit_clone_delete = '<span class="vc_row_edit_clone_delete">';
			if ( $allAccess ) {
				$row_edit_clone_delete .= $controls_delete . $controls_clone . $controls_edit;
			} elseif ( $editAccess ) {
				$row_edit_clone_delete .= $controls_edit;
			}
			$row_edit_clone_delete .= $controls_toggle;
			$row_edit_clone_delete .= '</span>';

			if ( $allAccess ) {
				$output .= $controls_move . $controls_layout . $controls_add . $row_edit_clone_delete . $controls_end;
			} elseif ( $editAccess ) {
				$output .= $row_edit_clone_delete . $controls_end;
			} else {
				$output .= $row_edit_clone_delete . $controls_end;
			}
		}

		return $output;
	}

	/**
	 * This returs block controls
	 */
	public function getLayoutsControl() {
		$vc_row_layouts  = array(
			array(
				'cells'      => '11',
				'mask'       => '12',
				'title'      => 'Full width',
				'icon_class' => '1-1',
			),
			array(
				'cells'      => '14_12_14',
				'mask'       => '313',
				'title'      => 'Sidebar + Content + Sidebar',
				'icon_class' => '1-4_1-2_1-4',
			),
			array(
				'cells'      => '23_13',
				'mask'       => '29',
				'title'      => 'Content + Sidebar',
				'icon_class' => '2-3_1-3',
			),
			array(
				'cells'      => '13_23',
				'mask'       => '29',
				'title'      => 'Sidebar + Content',
				'icon_class' => '2-3_1-3',
			),
			array(
				'cells'      => '12_12',
				'mask'       => '26',
				'title'      => '1/2 + 1/2',
				'icon_class' => '1-2_1-2',
			),
			array(
				'cells'      => '13_13_13',
				'mask'       => '312',
				'title'      => '1/3 + 1/3 + 1/3',
				'icon_class' => '1-3_1-3_1-3',
			),
			array(
				'cells'      => '12_14_14',
				'mask'       => '313',
				'title'      => 'Content + Sidebar + Sidebar',
				'icon_class' => '1-2_1-4_1-4',
			),
			array(
				'cells'      => '14_14_12',
				'mask'       => '313',
				'title'      => 'Sidebar + Sidebar + Content',
				'icon_class' => '1-4_1-4_1-2',
			),
			array(
				'cells'      => '14_14_14_14',
				'mask'       => '420',
				'title'      => '1/4 + 1/4 + 1/4 + 1/4',
				'icon_class' => '1-4_1-4_1-4_1-4',
			),
		);
		$controls_layout = '<span class="vc_row_layouts vc_control">';
		foreach ( $vc_row_layouts as $layout ) {
			$controls_layout .= '<a class="vc_control-set-column penci_set_layout cell-' . $layout['cells'] . ' " data-cells="' . $layout['cells'] . '" data-cells-mask="' . $layout['mask'] . '" data-hint="' . $layout['title'] . '"><i class="vc-composer-icon vc-c-icon-' . $layout['icon_class'] . '"></i></a> ';
		}
		$controls_layout .= '</span>';

		return $controls_layout;
	}

	public function cssAdminClass() {
		$sortable = ( vc_user_access_check_shortcode_all( $this->shortcode ) ? ' wpb_sortable' : ' ' . $this->nonDraggableClass );

		return 'wpb_' . $this->settings['base'] . $sortable . '' . ( ! empty( $this->settings['class'] ) ? ' ' . $this->settings['class'] : '' );
	}

	/**
	 * @return string
	 * @deprecated - due to it is not used anywhere? 4.5
	 * @typo Bock - Block
	 */
	public function customAdminBockParams() {
		// _deprecated_function( 'WPBakeryShortCode_VC_Row::customAdminBockParams', '4.5 (will be removed in 4.10)' );

		return '';
	}

	/**
	 * @param string $bg_image
	 * @param string $bg_color
	 * @param string $bg_image_repeat
	 * @param string $font_color
	 * @param string $padding
	 * @param string $margin_bottom
	 *
	 * @return string
	 * @deprecated 4.5
	 *
	 */
	public function buildStyle( $bg_image = '', $bg_color = '', $bg_image_repeat = '', $font_color = '', $padding = '', $margin_bottom = '' ) {
		// _deprecated_function( 'WPBakeryShortCode_VC_Row::buildStyle', '4.5 (will be removed in 4.10)' );

		$has_image = false;
		$style     = '';
		if ( (int) $bg_image > 0 && false !== ( $image_url = wp_get_attachment_url( $bg_image ) ) ) {
			$has_image = true;
			$style     .= 'background-image: url(' . $image_url . ');';
		}
		if ( ! empty( $bg_color ) ) {
			$style .= vc_get_css_color( 'background-color', $bg_color );
		}
		if ( ! empty( $bg_image_repeat ) && $has_image ) {
			if ( 'cover' === $bg_image_repeat ) {
				$style .= 'background-repeat:no-repeat;background-size: cover;';
			} elseif ( 'contain' === $bg_image_repeat ) {
				$style .= 'background-repeat:no-repeat;background-size: contain;';
			} elseif ( 'no-repeat' === $bg_image_repeat ) {
				$style .= 'background-repeat: no-repeat;';
			}
		}
		if ( ! empty( $font_color ) ) {
			$style .= vc_get_css_color( 'color', $font_color );
		}
		if ( '' !== $padding ) {
			$style .= 'padding: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $padding ) ? $padding : $padding . 'px' ) . ';';
		}
		if ( '' !== $margin_bottom ) {
			$style .= 'margin-bottom: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $margin_bottom ) ? $margin_bottom : $margin_bottom . 'px' ) . ';';
		}

		return empty( $style ) ? '' : ' style="' . esc_attr( $style ) . '"';
	}
}

class WPBakeryShortCode_Penci_Column extends WPBakeryShortCode {
	public $nonDraggableClass = 'vc-non-draggable-column';
	/**
	 * @var array
	 */
	protected $predefined_atts = array(
		'font_color'  => '',
		'el_class'    => '',
		'el_position' => '',
		'width'       => '1/2',
	);

	/**
	 * @param string $content
	 *
	 * @return string
	 */
	public function template( $content = '' ) {
		return $this->contentAdmin( $this->atts );
	}

	/**
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	public function contentAdmin( $atts, $content = null ) {
		$width = $el_class = '';

		extract( shortcode_atts( $this->predefined_atts, $atts ) );
		$output = '';

		$column_controls        = $this->getColumnControls( $this->settings( 'controls' ) );
		$column_controls_bottom = $this->getColumnControls( 'add', 'bottom-controls' );

		if ( ' column_14' === $width || ' 1/4' === $width ) {
			$width = array( 'vc_col-sm-3' );
		} elseif ( ' column_14===$width-14-14-14' ) {
			$width = array(
				'vc_col-sm-3',
				'vc_col-sm-3',
				'vc_col-sm-3',
				'vc_col-sm-3',
			);
		} elseif ( ' column_13' === $width || ' 1/3' === $width ) {
			$width = array( 'vc_col-sm-4' );
		} elseif ( ' column_13===$width-23' ) {
			$width = array(
				'vc_col-sm-4',
				'vc_col-sm-8',
			);
		} elseif ( ' column_13===$width-13-13' ) {
			$width = array(
				'vc_col-sm-4',
				'vc_col-sm-4',
				'vc_col-sm-4',
			);
		} elseif ( ' column_12' === $width || ' 1/2' === $width ) {
			$width = array( 'vc_col-sm-6' );
		} elseif ( ' column_12===$width-12' ) {
			$width = array(
				'vc_col-sm-6',
				'vc_col-sm-6',
			);
		} elseif ( ' column_23' === $width || ' 2/3' === $width ) {
			$width = array( 'vc_col-sm-8' );
		} elseif ( ' column_34' === $width || ' 3/4' === $width ) {
			$width = array( 'vc_col-sm-9' );
		} elseif ( ' column_16' === $width || ' 1/6' === $width ) {
			$width = array( 'vc_col-sm-2' );
		} elseif ( ' column_56' === $width || ' 5/6' === $width ) {
			$width = array( 'vc_col-sm-10' );
		} else {
			$width = array( '' );
		}
		$_count_width = count( (array) $width );
		for ( $i = 0; $i < $_count_width; $i ++ ) {
			$output .= '<div ' . $this->mainHtmlBlockParams( $width, $i ) . '>';
			$output .= str_replace( '%column_size%', wpb_translateColumnWidthToFractional( $width[ $i ] ), $column_controls );
			$output .= '<div class="wpb_element_wrapper">';
			$output .= '<div ' . $this->containerHtmlBlockParams( $width, $i ) . '>';
			$output .= do_shortcode( shortcode_unautop( $content ) );
			$output .= '</div>';
			if ( isset( $this->settings['params'] ) ) {
				$inner = '';
				foreach ( $this->settings['params'] as $param ) {
					$param_value = isset( ${$param['param_name']} ) ? ${$param['param_name']} : '';
					if ( is_array( $param_value ) ) {
						// Get first element from the array
						reset( $param_value );
						$first_key   = key( $param_value );
						$param_value = $param_value[ $first_key ];
					}
					$inner .= $this->singleParamHtmlHolder( $param, $param_value );
				}
				$output .= $inner;
			}
			$output .= '</div>';
			$output .= str_replace( '%column_size%', wpb_translateColumnWidthToFractional( $width[ $i ] ), $column_controls_bottom );
			$output .= '</div>';
		}

		return $output;
	}

	/**
	 * @param $controls
	 * @param string $extended_css
	 *
	 * @return string
	 */
	public function getColumnControls( $controls, $extended_css = '' ) {
		$output       = '<div class="vc_controls vc_control-column vc_controls-visible' . ( ! empty( $extended_css ) ? " {$extended_css}" : '' ) . '">';
		$controls_end = '</div>';

		if ( ' bottom-controls' === $extended_css ) {
			$control_title = __( 'Append to this column', 'soledad' );
		} else {
			$control_title = __( 'Prepend to this column', 'soledad' );
		}
		if ( vc_user_access()->part( 'shortcodes' )->checkStateAny( true, 'custom', null )->get() ) {
			$controls_add = '<a class="vc_control column_add vc_column-add" data-vc-control="add" href="#" title="' . $control_title . '"><i class="vc-composer-icon vc-c-icon-add"></i></a>';
		} else {
			$controls_add = '';
		}
		$controls_edit = '<a class="vc_control column_edit vc_column-edit"  data-vc-control="edit" href="#" title="' . __( 'Edit this column', 'soledad' ) . '"><i class="vc-composer-icon vc-c-icon-mode_edit"></i></a>';
		//$controls_delete = '<a class="vc_control column_delete vc_column-delete" data-vc-control="delete"  href="#" title="' . __( 'Delete this column', 'soledad' ) . '"><i class="vc-composer-icon vc-c-icon-delete_empty"></i></a>';
		$controls_delete = '';
		$editAccess      = vc_user_access_check_shortcode_edit( $this->shortcode );
		$allAccess       = vc_user_access_check_shortcode_all( $this->shortcode );
		if ( is_array( $controls ) && ! empty( $controls ) ) {
			foreach ( $controls as $control ) {
				if ( 'add' === $control || ( $editAccess && 'edit' === $control ) || $allAccess ) {
					$method_name = vc_camel_case( 'output-editor-control-' . $control );
					if ( method_exists( $this, $method_name ) ) {
						$output .= $this->$method_name();
					} else {
						$control_var = 'controls_' . $control;
						if ( isset( ${$control_var} ) ) {
							$output .= ${$control_var};
						}
					}
				}
			}

			return $output . $controls_end;
		} elseif ( is_string( $controls ) && 'full' === $controls ) {
			if ( $allAccess ) {
				return $output . $controls_add . $controls_edit . $controls_delete . $controls_end;
			} elseif ( $editAccess ) {
				return $output . $controls_add . $controls_edit . $controls_end;
			}

			return $output . $controls_add . $controls_end;
		} elseif ( is_string( $controls ) ) {
			$control_var = 'controls_' . $controls;
			if ( 'add' === $controls || ( $editAccess && 'edit' == $controls || $allAccess ) && isset( ${$control_var} ) ) {
				return $output . ${$control_var} . $controls_end;
			}

			return $output . $controls_end;
		}
		if ( $allAccess ) {
			return $output . $controls_add . $controls_edit . $controls_delete . $controls_end;
		} elseif ( $editAccess ) {
			return $output . $controls_add . $controls_edit . $controls_end;
		}

		return $output . $controls_add . $controls_end;
	}

	/**
	 * @param $width
	 * @param $i
	 *
	 * @return string
	 */
	public function mainHtmlBlockParams( $width, $i ) {
		$sortable = ( vc_user_access_check_shortcode_all( $this->shortcode ) ? 'wpb_sortable' : $this->nonDraggableClass );

		return 'data-element_type_sdsds="' . $this->settings['base'] . '" data-vc-column-width="' . wpb_vc_get_column_width_indent( $width[ $i ] ) . '" class="wpb_' . $this->settings['base'] . ' ' . $sortable . '' . ( ! empty( $this->settings['class'] ) ? ' ' . $this->settings['class'] : '' ) . ' ' . $this->templateWidth() . ' wpb_content_holder"' . $this->customAdminBlockParams();
	}

	/**
	 * @return string
	 */
	protected function templateWidth() {
		return '<%= window.vc_convert_column_size(params.width) %>';
	}

	/**
	 * @return string
	 */
	public function customAdminBlockParams() {
		return '';
	}

	/**
	 * @param $width
	 * @param $i
	 *
	 * @return string
	 */
	public function containerHtmlBlockParams( $width, $i ) {
		return 'class="wpb_column_container vc_container_for_children"';
	}

	/**
	 * @param $param
	 * @param $value
	 *
	 * @return string
	 */
	public function singleParamHtmlHolder( $param, $value ) {
		$output = '';
		// Compatibility fixes.
		$old_names  = array(
			'yellow_message',
			'blue_message',
			'green_message',
			'button_green',
			'button_grey',
			'button_yellow',
			'button_blue',
			'button_red',
			'button_orange',
		);
		$new_names  = array(
			'alert-block',
			'alert-info',
			'alert-success',
			'btn-success',
			'btn',
			'btn-info',
			'btn-primary',
			'btn-danger',
			'btn-warning',
		);
		$value      = str_ireplace( $old_names, $new_names, $value );
		$param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
		$type       = isset( $param['type'] ) ? $param['type'] : '';
		$class      = isset( $param['class'] ) ? $param['class'] : '';

		if ( isset( $param['holder'] ) && 'hidden' !== $param['holder'] ) {
			$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
		}

		return $output;
	}

	/**
	 * @param string $font_color
	 *
	 * @return string
	 */
	public function buildStyle( $font_color = '' ) {
		$style = '';
		if ( ! empty( $font_color ) ) {
			$style .= vc_get_css_color( 'color', $font_color );
		}

		return empty( $style ) ? $style : ' style="' . esc_attr( $style ) . '"';
	}
}

class WPBakeryShortCode_Penci_Container_Inner extends WPBakeryShortCode {
	public $nonDraggableClass = 'vc-non-draggable-row';
	protected $predefined_atts = array(
		'el_class' => '',
	);

	/**
	 * @param $settings
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );
		$this->shortcodeScripts();
	}

	protected function shortcodeScripts() {
		wp_register_script( 'vc_jquery_skrollr_js', vc_asset_url( 'lib/bower/skrollr/dist/skrollr.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		wp_register_script( 'vc_youtube_iframe_api_js', '//www.youtube.com/iframe_api', array(), WPB_VC_VERSION, true );
	}

	public function template( $content = '' ) {
		return $this->contentAdmin( $this->atts );
	}

	public function contentAdmin( $atts, $content = null ) {
		$width = $el_class = '';
		$atts  = shortcode_atts( $this->predefined_atts, $atts );

		extract( $atts );

		$output          = '';
		$count_width     = $width && is_array( $width ) ? count( $width ) : 1;
		$column_controls = $this->getColumnControls( $this->settings( 'controls' ) );

		for ( $i = 0; $i < $count_width; $i ++ ) {
			$output .= '<div data-manh="" data-element_type="' . $this->settings['base'] . '" class="' . $this->cssAdminClass() . '">';
			$output .= str_replace( '%column_size%', 1, $column_controls );
			$output .= '<div class="wpb_element_wrapper">';
			$output .= '<div class="vc_row vc_row-fluid wpb_row_container vc_container_for_children">';
			if ( '' === $content && ! empty( $this->settings['default_content_in_template'] ) ) {
				$output .= do_shortcode( shortcode_unautop( $this->settings['default_content_in_template'] ) );
			} else {
				$output .= do_shortcode( shortcode_unautop( $content ) );

			}
			$output .= '</div>';
			if ( isset( $this->settings['params'] ) ) {
				$inner = '';
				foreach ( $this->settings['params'] as $param ) {
					if ( ! isset( $param['param_name'] ) ) {
						continue;
					}
					$param_value = isset( $atts[ $param['param_name'] ] ) ? $atts[ $param['param_name'] ] : '';
					if ( is_array( $param_value ) ) {
						// Get first element from the array
						reset( $param_value );
						$first_key   = key( $param_value );
						$param_value = $param_value[ $first_key ];
					}
					$inner .= $this->singleParamHtmlHolder( $param, $param_value );
				}
				$output .= $inner;
			}
			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}

	public function getColumnControls( $controls, $extended_css = '' ) {
		$output       = '<div class="penci_container_controls vc_controls vc_controls-row controls_row vc_clearfix">';
		$controls_end = '</div>';

		$icon     = $this->settings( 'icon' );
		$img_icon = $icon ? '<img src="' . $icon . '" alt="icon"/>' : '';

		$controls_layout = $this->getLayoutsControl();
		$controls_add    = '';
		$controls_title  = '<span class="wpb_element_title"> ' . $img_icon . '</span>';
		$controls_move   = '<a class="vc_control column_move vc_column-move" href="#" title="' . __( 'Drag row to reorder', 'soledad' ) . '" data-vc-control="move"><i class="vc-composer-icon vc-c-icon-dragndrop"></i>' . $controls_title . '</a>';
		$controls_delete = '<a class="vc_control column_delete vc_column-delete" href="#" title="' . __( 'Delete this row', 'soledad' ) . '" data-vc-control="delete"><i class="vc-composer-icon vc-c-icon-delete_empty"></i></a>';
		$controls_edit   = ' <a class="vc_control column_edit vc_column-edit" href="#" title="' . __( 'Edit this row', 'soledad' ) . '" data-vc-control="edit"><i class="vc-composer-icon vc-c-icon-mode_edit"></i></a>';
		$controls_clone  = ' <a class="vc_control column_clone vc_column-clone" href="#" title="' . __( 'Clone this row', 'soledad' ) . '" data-vc-control="clone"><i class="vc-composer-icon vc-c-icon-content_copy"></i></a>';
		$controls_toggle = ' <a class="vc_control column_toggle vc_column-toggle" href="#" title="' . __( 'Toggle row', 'soledad' ) . '" data-vc-control="toggle"><i class="vc-composer-icon vc-c-icon-arrow_drop_down"></i></a>';
		$editAccess      = vc_user_access_check_shortcode_edit( $this->shortcode );
		$allAccess       = vc_user_access_check_shortcode_all( $this->shortcode );


		if ( is_array( $controls ) && ! empty( $controls ) ) {

			foreach ( $controls as $control ) {
				$control_var = 'controls_' . $control;
				if ( ( $editAccess && 'edit' == $control ) || $allAccess ) {
					if ( isset( ${$control_var} ) ) {
						$output .= ${$control_var};
					}
				}
			}
			$output .= $controls_end;
		} elseif ( is_string( $controls ) ) {
			$control_var = 'controls_' . $controls;
			if ( ( $editAccess && 'edit' === $controls ) || $allAccess ) {
				if ( isset( ${$control_var} ) ) {
					$output .= ${$control_var} . $controls_end;
				}
			}
		} else {
			$row_edit_clone_delete = '<span class="vc_row_edit_clone_delete">';
			if ( $allAccess ) {
				$row_edit_clone_delete .= $controls_delete . $controls_clone . $controls_edit;
			} elseif ( $editAccess ) {
				$row_edit_clone_delete .= $controls_edit;
			}
			$row_edit_clone_delete .= $controls_toggle;
			$row_edit_clone_delete .= '</span>';

			if ( $allAccess ) {
				$output .= $controls_move . $controls_layout . $controls_add . $row_edit_clone_delete . $controls_end;
			} elseif ( $editAccess ) {
				$output .= $row_edit_clone_delete . $controls_end;
			} else {
				$output .= $row_edit_clone_delete . $controls_end;
			}
		}

		return $output;
	}

	/**
	 * This returs block controls
	 */
	public function getLayoutsControl() {
		$vc_row_layouts  = array(
			array(
				'cells'      => '12_12',
				'mask'       => '26',
				'title'      => '1/2 + 1/2',
				'icon_class' => '1-2_1-2',
			),
			array(
				'cells'      => '23_13',
				'mask'       => '29',
				'title'      => 'Content + Sidebar',
				'icon_class' => '2-3_1-3',
			),
			array(
				'cells'      => '13_23',
				'mask'       => '29',
				'title'      => 'Sidebar + Content',
				'icon_class' => '2-3_1-3',
			),
		);
		$controls_layout = '<span class="vc_row_layouts vc_control">';
		foreach ( $vc_row_layouts as $layout ) {
			$controls_layout .= '<a class="vc_control-set-column penci_set_layout cell-' . $layout['cells'] . ' " data-cells="' . $layout['cells'] . '" data-cells-mask="' . $layout['mask'] . '" data-hint="' . $layout['title'] . '"><i class="vc-composer-icon vc-c-icon-' . $layout['icon_class'] . '"></i></a> ';
		}
		$controls_layout .= '</span>';

		return $controls_layout;
	}

	public function cssAdminClass() {
		$sortable = ( vc_user_access_check_shortcode_all( $this->shortcode ) ? ' wpb_sortable' : ' ' . $this->nonDraggableClass );

		return 'wpb_' . $this->settings['base'] . $sortable . '' . ( ! empty( $this->settings['class'] ) ? ' ' . $this->settings['class'] : '' );
	}

	/**
	 * @return string
	 * @deprecated - due to it is not used anywhere? 4.5
	 * @typo Bock - Block
	 */
	public function customAdminBockParams() {
		// _deprecated_function( 'WPBakeryShortCode_VC_Row::customAdminBockParams', '4.5 (will be removed in 4.10)' );

		return '';
	}

	/**
	 * @param string $bg_image
	 * @param string $bg_color
	 * @param string $bg_image_repeat
	 * @param string $font_color
	 * @param string $padding
	 * @param string $margin_bottom
	 *
	 * @return string
	 * @deprecated 4.5
	 *
	 */
	public function buildStyle( $bg_image = '', $bg_color = '', $bg_image_repeat = '', $font_color = '', $padding = '', $margin_bottom = '' ) {
		// _deprecated_function( 'WPBakeryShortCode_VC_Row::buildStyle', '4.5 (will be removed in 4.10)' );

		$has_image = false;
		$style     = '';
		if ( (int) $bg_image > 0 && false !== ( $image_url = wp_get_attachment_url( $bg_image ) ) ) {
			$has_image = true;
			$style     .= 'background-image: url(' . $image_url . ');';
		}
		if ( ! empty( $bg_color ) ) {
			$style .= vc_get_css_color( 'background-color', $bg_color );
		}
		if ( ! empty( $bg_image_repeat ) && $has_image ) {
			if ( 'cover' === $bg_image_repeat ) {
				$style .= 'background-repeat:no-repeat;background-size: cover;';
			} elseif ( 'contain' === $bg_image_repeat ) {
				$style .= 'background-repeat:no-repeat;background-size: contain;';
			} elseif ( 'no-repeat' === $bg_image_repeat ) {
				$style .= 'background-repeat: no-repeat;';
			}
		}
		if ( ! empty( $font_color ) ) {
			$style .= vc_get_css_color( 'color', $font_color );
		}
		if ( '' !== $padding ) {
			$style .= 'padding: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $padding ) ? $padding : $padding . 'px' ) . ';';
		}
		if ( '' !== $margin_bottom ) {
			$style .= 'margin-bottom: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $margin_bottom ) ? $margin_bottom : $margin_bottom . 'px' ) . ';';
		}

		return empty( $style ) ? '' : ' style="' . esc_attr( $style ) . '"';
	}
}

class WPBakeryShortCode_Penci_Column_Inner extends WPBakeryShortCode {
	public $nonDraggableClass = 'vc-non-draggable-column';
	/**
	 * @var array
	 */
	protected $predefined_atts = array(
		'font_color'  => '',
		'el_class'    => '',
		'el_position' => '',
		'width'       => '1/2',
	);

	/**
	 * @param string $content
	 *
	 * @return string
	 */
	public function template( $content = '' ) {
		return $this->contentAdmin( $this->atts );
	}

	/**
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	public function contentAdmin( $atts, $content = null ) {
		$width = $el_class = '';

		extract( shortcode_atts( $this->predefined_atts, $atts ) );
		$output = '';

		$column_controls        = $this->getColumnControls( $this->settings( 'controls' ) );
		$column_controls_bottom = $this->getColumnControls( 'add', 'bottom-controls' );

		if ( ' column_14' === $width || ' 1/4' === $width ) {
			$width = array( 'vc_col-sm-3' );
		} elseif ( ' column_14===$width-14-14-14' ) {
			$width = array(
				'vc_col-sm-3',
				'vc_col-sm-3',
				'vc_col-sm-3',
				'vc_col-sm-3',
			);
		} elseif ( ' column_13' === $width || ' 1/3' === $width ) {
			$width = array( 'vc_col-sm-4' );
		} elseif ( ' column_13===$width-23' ) {
			$width = array(
				'vc_col-sm-4',
				'vc_col-sm-8',
			);
		} elseif ( ' column_13===$width-13-13' ) {
			$width = array(
				'vc_col-sm-4',
				'vc_col-sm-4',
				'vc_col-sm-4',
			);
		} elseif ( ' column_12' === $width || ' 1/2' === $width ) {
			$width = array( 'vc_col-sm-6' );
		} elseif ( ' column_12===$width-12' ) {
			$width = array(
				'vc_col-sm-6',
				'vc_col-sm-6',
			);
		} elseif ( ' column_23' === $width || ' 2/3' === $width ) {
			$width = array( 'vc_col-sm-8' );
		} elseif ( ' column_34' === $width || ' 3/4' === $width ) {
			$width = array( 'vc_col-sm-9' );
		} elseif ( ' column_16' === $width || ' 1/6' === $width ) {
			$width = array( 'vc_col-sm-2' );
		} elseif ( ' column_56' === $width || ' 5/6' === $width ) {
			$width = array( 'vc_col-sm-10' );
		} else {
			$width = array( '' );
		}
		$__count_width = count( (array) $width );
		for ( $i = 0; $i < $__count_width; $i ++ ) {
			$output .= '<div ' . $this->mainHtmlBlockParams( $width, $i ) . '>';
			$output .= str_replace( '%column_size%', wpb_translateColumnWidthToFractional( $width[ $i ] ), $column_controls );
			$output .= '<div class="wpb_element_wrapper">';
			$output .= '<div ' . $this->containerHtmlBlockParams( $width, $i ) . '>';
			$output .= do_shortcode( shortcode_unautop( $content ) );
			$output .= '</div>';
			if ( isset( $this->settings['params'] ) ) {
				$inner = '';
				foreach ( $this->settings['params'] as $param ) {
					$param_value = isset( ${$param['param_name']} ) ? ${$param['param_name']} : '';
					if ( is_array( $param_value ) ) {
						// Get first element from the array
						reset( $param_value );
						$first_key   = key( $param_value );
						$param_value = $param_value[ $first_key ];
					}
					$inner .= $this->singleParamHtmlHolder( $param, $param_value );
				}
				$output .= $inner;
			}
			$output .= '</div>';
			$output .= str_replace( '%column_size%', wpb_translateColumnWidthToFractional( $width[ $i ] ), $column_controls_bottom );
			$output .= '</div>';
		}

		return $output;
	}

	/**
	 * @param $controls
	 * @param string $extended_css
	 *
	 * @return string
	 */
	public function getColumnControls( $controls, $extended_css = '' ) {
		$output       = '<div class="vc_controls vc_control-column vc_controls-visible' . ( ! empty( $extended_css ) ? " {$extended_css}" : '' ) . '">';
		$controls_end = '</div>';

		if ( ' bottom-controls' === $extended_css ) {
			$control_title = __( 'Append to this column', 'soledad' );
		} else {
			$control_title = __( 'Prepend to this column', 'soledad' );
		}
		if ( vc_user_access()->part( 'shortcodes' )->checkStateAny( true, 'custom', null )->get() ) {
			$controls_add = '<a class="vc_control column_add vc_column-add" data-vc-control="add" href="#" title="' . $control_title . '"><i class="vc-composer-icon vc-c-icon-add"></i></a>';
		} else {
			$controls_add = '';
		}
		$controls_edit = '<a class="vc_control column_edit vc_column-edit"  data-vc-control="edit" href="#" title="' . __( 'Edit this column', 'soledad' ) . '"><i class="vc-composer-icon vc-c-icon-mode_edit"></i></a>';
		//$controls_delete = '<a class="vc_control column_delete vc_column-delete" data-vc-control="delete"  href="#" title="' . __( 'Delete this column', 'soledad' ) . '"><i class="vc-composer-icon vc-c-icon-delete_empty"></i></a>';
		$controls_delete = '';
		$editAccess      = vc_user_access_check_shortcode_edit( $this->shortcode );
		$allAccess       = vc_user_access_check_shortcode_all( $this->shortcode );
		if ( is_array( $controls ) && ! empty( $controls ) ) {
			foreach ( $controls as $control ) {
				if ( 'add' === $control || ( $editAccess && 'edit' === $control ) || $allAccess ) {
					$method_name = vc_camel_case( 'output-editor-control-' . $control );
					if ( method_exists( $this, $method_name ) ) {
						$output .= $this->$method_name();
					} else {
						$control_var = 'controls_' . $control;
						if ( isset( ${$control_var} ) ) {
							$output .= ${$control_var};
						}
					}
				}
			}

			return $output . $controls_end;
		} elseif ( is_string( $controls ) && 'full' === $controls ) {
			if ( $allAccess ) {
				return $output . $controls_add . $controls_edit . $controls_delete . $controls_end;
			} elseif ( $editAccess ) {
				return $output . $controls_add . $controls_edit . $controls_end;
			}

			return $output . $controls_add . $controls_end;
		} elseif ( is_string( $controls ) ) {
			$control_var = 'controls_' . $controls;
			if ( 'add' === $controls || ( $editAccess && 'edit' == $controls || $allAccess ) && isset( ${$control_var} ) ) {
				return $output . ${$control_var} . $controls_end;
			}

			return $output . $controls_end;
		}
		if ( $allAccess ) {
			return $output . $controls_add . $controls_edit . $controls_delete . $controls_end;
		} elseif ( $editAccess ) {
			return $output . $controls_add . $controls_edit . $controls_end;
		}

		return $output . $controls_add . $controls_end;
	}

	/**
	 * @param $width
	 * @param $i
	 *
	 * @return string
	 */
	public function mainHtmlBlockParams( $width, $i ) {
		$sortable = ( vc_user_access_check_shortcode_all( $this->shortcode ) ? 'wpb_sortable' : $this->nonDraggableClass );

		return 'data-element_type_sdsds="' . $this->settings['base'] . '" data-vc-column-width="' . wpb_vc_get_column_width_indent( $width[ $i ] ) . '" class="wpb_' . $this->settings['base'] . ' ' . $sortable . '' . ( ! empty( $this->settings['class'] ) ? ' ' . $this->settings['class'] : '' ) . ' ' . $this->templateWidth() . ' wpb_content_holder"' . $this->customAdminBlockParams();
	}

	/**
	 * @return string
	 */
	protected function templateWidth() {
		return '<%= window.vc_convert_column_size(params.width) %>';
	}

	/**
	 * @return string
	 */
	public function customAdminBlockParams() {
		return '';
	}

	/**
	 * @param $width
	 * @param $i
	 *
	 * @return string
	 */
	public function containerHtmlBlockParams( $width, $i ) {
		return 'class="wpb_column_container vc_container_for_children"';
	}

	/**
	 * @param $param
	 * @param $value
	 *
	 * @return string
	 */
	public function singleParamHtmlHolder( $param, $value ) {
		$output = '';
		// Compatibility fixes.
		$old_names  = array(
			'yellow_message',
			'blue_message',
			'green_message',
			'button_green',
			'button_grey',
			'button_yellow',
			'button_blue',
			'button_red',
			'button_orange',
		);
		$new_names  = array(
			'alert-block',
			'alert-info',
			'alert-success',
			'btn-success',
			'btn',
			'btn-info',
			'btn-primary',
			'btn-danger',
			'btn-warning',
		);
		$value      = str_ireplace( $old_names, $new_names, $value );
		$param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
		$type       = isset( $param['type'] ) ? $param['type'] : '';
		$class      = isset( $param['class'] ) ? $param['class'] : '';

		if ( isset( $param['holder'] ) && 'hidden' !== $param['holder'] ) {
			$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
		}

		return $output;
	}

	/**
	 * @param string $font_color
	 *
	 * @return string
	 */
	public function buildStyle( $font_color = '' ) {
		$style = '';
		if ( ! empty( $font_color ) ) {
			$style .= vc_get_css_color( 'color', $font_color );
		}

		return empty( $style ) ? $style : ' style="' . esc_attr( $style ) . '"';
	}
}

class WPBakeryShortCode_Penci_Advanced_List extends WPBakeryShortCodesContainer {
}

class WPBakeryShortCode_Penci_Advanced_List_Item extends WPBakeryShortCode {
}

class WPBakeryShortCode_Penci_Product_Tabs extends WPBakeryShortCodesContainer {
}

class WPBakeryShortCode_Penci_Product_Tab extends WPBakeryShortCode {
}
