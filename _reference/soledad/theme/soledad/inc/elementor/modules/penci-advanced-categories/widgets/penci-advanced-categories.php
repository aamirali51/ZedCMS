<?php

namespace PenciSoledadElementor\Modules\PenciAdvancedCategories\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Base\Base_Color;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciAdvancedCategories extends Base_Widget {

	public function get_name() {
		return 'penci-advanced-categories';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Advanced Categories', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-document-file';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'category' );
	}

	protected function register_controls() {


		// Section layout
		$this->start_controls_section( 'section_aboutme', array(
			'label' => esc_html__( 'Taxonomy', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'style', array(
			'label'   => __( 'Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'style-1',
			'options' => [
				'style-1' => __('List', 'soledad' ),
				'style-2' => __('Boxed', 'soledad' ),
				'style-3' => __('Dropdown', 'soledad' ),
			],
		) );

		$post_types  = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ), 'object' );
		$tax_options = [];
		foreach ( $post_types as $post_type => $type ) {
			foreach ( get_object_taxonomies( $type->name, 'object' ) as $tax_name => $tax_info ) {
				if ( ! in_array( $tax_name, [ 'post_format', 'elementor_library_type', 'penci_block_category' ] ) ) {
					$tax_options[ $tax_name ] = $type->label . ' - ' . $tax_info->label;
				}
			}
		}

		$this->add_control( 'tax', array(
			'label'   => __( 'Taxonomy', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $tax_options,
			'default' => 'category',
		) );

		$this->add_control( 'orderby', array(
			'label'   => __( 'Order By', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'term_id',
			'options' => [
				'term_id' => __('ID', 'soledad' ),
				'name'    => __('Name', 'soledad' ),
				'slug'    => __('Slug', 'soledad' ),
				'count'   => __('Count', 'soledad' ),
			],
		) );

		$this->add_control( 'order', array(
			'label'   => __( 'Order', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'DESC',
			'options' => [
				'ASC'  => __('ASC', 'soledad' ),
				'DESC' => __('DESC', 'soledad' ),
			],
		) );

		$this->add_control( 'hide_empty', array(
			'label' => __( 'Show Empty Categories?', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_control( 'hierarchical', array(
			'label' => __( 'Show in hierarchical?', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_control( 'count', array(
			'label' => __( 'Show posts count?', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_control( 'toggle', array(
			'label' => __( 'Show Toggle Button?', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_control( 'mark_count', array(
			'label' => __( 'Highlight posts count?', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_control( 'maxitems', array(
			'label'   => __( 'Limit Category to Show', 'soledad' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 10,
		) );

		$this->add_control( 'excluded', array(
			'label'       => __( 'Exclude Term IDs:', 'soledad' ),
			'description' => __( 'E.g:  1, 2, 4', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
		) );

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		$this->start_controls_section( 'section_style_image', array(
			'label' => __( 'Color & Style', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'term_name_typo',
			'label'    => __( 'Term Name Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .pc-widget-advanced-tax.tax-style-1 ul li a,{{WRAPPER}} .pc-widget-advanced-tax.tax-style-2 a,{{WRAPPER}} .pc-widget-advanced-tax.tax-style-3 select',
		) );

		$this->add_control( 'term_name_color', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => __( 'Color', 'soledad' ),
			'selectors' => [ '{{WRAPPER}} .pc-widget-advanced-tax.tax-style-1 ul li a,{{WRAPPER}} .pc-widget-advanced-tax.tax-style-2 a,{{WRAPPER}} .pc-widget-advanced-tax.tax-style-3 select' => 'color:{{VALUE}}' ],
		) );

		$this->add_control( 'term_name_hcolor', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => __( 'Hover Color', 'soledad' ),
			'condition' => [ 'style!' => 'style-3' ],
			'selectors' => [ '{{WRAPPER}} .pc-widget-advanced-tax.tax-style-1 ul li a:hover,{{WRAPPER}} .pc-widget-advanced-tax.tax-style-2 a:hover' => 'color:{{VALUE}}' ],
		) );

		$this->add_control( 'term_name_mcolor', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => __( 'Post Count Color', 'soledad' ),
			'condition' => [
				'style!' => 'style-3',
				'count'  => 'yes'
			],
			'selectors' => [ '{{WRAPPER}} .pc-widget-advanced-tax .category-item-count,{{WRAPPER}} .pc-widget-advanced-tax .tag-link-count' => 'color:{{VALUE}} !important' ],
		) );

		$this->add_control( 'term_name_mhcolor', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => __( 'Post Count Hover Color', 'soledad' ),
			'condition' => [ 'style!' => 'style-3', 'count' => 'yes' ],
			'selectors' => [ '{{WRAPPER}} .pc-widget-advanced-tax a:hover .category-item-count,{{WRAPPER}} .pc-widget-advanced-tax a:hover .tag-link-count' => 'color:{{VALUE}} !important' ],
		) );

		$this->add_control( 'term_name_nbgcolor', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => __( 'Post Count BackgroundColor', 'soledad' ),
			'condition' => [
				'style'      => 'style-1',
				'mark_count' => 'yes',
				'count'      => 'yes',
			],
			'selectors' => [ '{{WRAPPER}} .pc-widget-advanced-tax .category-item-count,{{WRAPPER}} .pc-widget-advanced-tax .tag-link-count' => 'background-color:{{VALUE}} !important' ],
		) );

		$this->add_control( 'term_name_nbghcolor', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => __( 'Post Count Hover Background Color', 'soledad' ),
			'condition' => [
				'style'      => 'style-1',
				'mark_count' => 'yes',
				'count'      => 'yes',
			],
			'selectors' => [ '{{WRAPPER}} .pc-widget-advanced-tax a:hover .category-item-count,{{WRAPPER}} .pc-widget-advanced-tax a:hover .tag-link-count' => 'background-color:{{VALUE}} !important' ],
		) );

		$this->add_control( 'term_name_bdcolor', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => __( 'Border Color', 'soledad' ),
			'selectors' => [ '{{WRAPPER}} .pc-widget-advanced-tax.tax-style-1 ul ul,{{WRAPPER}} .pc-widget-advanced-tax.tax-style-1 ul li,{{WRAPPER}} .pc-widget-advanced-tax.tax-style-2 a,{{WRAPPER}} .pc-widget-advanced-tax.tax-style-3 select' => 'border-color:{{VALUE}}' ],
		) );

		$this->add_control( 'term_name_bdhcolor', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => __( 'Border Hover Color', 'soledad' ),
			'condition' => [ 'style' => 'style-2' ],
			'selectors' => [ '{{WRAPPER}} .pc-widget-advanced-tax.tax-style-2 a:hover' => 'border-color:{{VALUE}}' ],
		) );

		$this->add_control( 'term_name_bgcolor', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => __( 'Background Color', 'soledad' ),
			'condition' => [ 'style!' => 'style-1' ],
			'selectors' => [ '{{WRAPPER}} .pc-widget-advanced-tax.tax-style-2 a,{{WRAPPER}} .pc-widget-advanced-tax.tax-style-3 select' => 'background-color:{{VALUE}}' ],
		) );

		$this->add_control( 'term_name_bghcolor', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => __( 'Background Hover Color', 'soledad' ),
			'condition' => [ 'style' => 'style-2' ],
			'selectors' => [ '{{WRAPPER}} .pc-widget-advanced-tax.tax-style-2 a:hover' => 'background-color:{{VALUE}}' ],
		) );

		$this->add_control( 'term_name_padding', array(
			'type'       => Controls_Manager::DIMENSIONS,
			'label'      => __( 'Term Name Padding', 'soledad' ),
			'size_units' => array( 'px' ),
			'condition'  => [ 'style' => 'style-2' ],
			'selectors'  => array(
				'{{WRAPPER}} .pc-widget-advanced-tax.tax-style-2 a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		) );

		$this->add_responsive_control( 'term_name_spacing', array(
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 300, ) ),
			'label'     => __( 'Term Spacing', 'soledad' ),
			'condition' => [ 'style' => 'style-1' ],
			'selectors' => [
				'{{WRAPPER}} .pc-widget-advanced-tax.tax-style-1 ul li:not(:last-child)' => 'margin-bottom:calc({{SIZE}}px / 2);padding-bottom:calc({{SIZE}}px / 2)',
				'{{WRAPPER}} .pc-widget-advanced-tax.tax-style-1 ul ul'                  => 'padding-top:calc({{SIZE}}px / 2);margin-top:calc({{SIZE}}px / 2)',
			],
		) );

		$this->end_controls_section();
		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();

		$tax          = $settings['tax'];
		$hide_empty   = $settings['hide_empty'];
		$order        = $settings['order'];
		$orderby      = $settings['orderby'];
		$hierarchical = $settings['hierarchical'] == 'yes';
		$count        = $settings['count'] == 'yes';
		$maxitems     = $settings['maxitems'];
		$exclude      = $settings['excluded'];
		$mark_count   = $settings['mark_count'] == 'yes';
		$rstyle       = $settings['style'];
		$style        = $settings['style'];

		$term_args = [
			'taxonomy'     => $tax,
			'hide_empty'   => ! $hide_empty,
			'order'        => $order,
			'orderby'      => $orderby,
			'hierarchical' => $hierarchical,
			'show_count'   => $count,
			'number'       => $maxitems,
			'title_li'     => '',
		];
		if ( $exclude ) {
			$term_args['exclude'] = explode( ',', $exclude );
		}
		$style = $mark_count ? $style . ' hlmark' : $style;
		add_filter( 'wp_list_categories', function ( $links ) use ( $mark_count ) {
			if ( $mark_count ) {
				$links = preg_replace( '/<\/a> \(([0-9.,]+)\)/', ' <span class="category-item-count">\\1</span></a>', $links );
			}

			return $links;

		}, 0, 1 );

		$this->markup_block_title( $settings, $this );

		?>
        <div class="widget widget_categories pcel-acat">
            <div class="pc-widget-advanced-tax tax-<?php echo $style; ?>">
				<?php if ( 'style-3' == $rstyle ):
					$name = 'category' == $tax ? 'cat' : ( 'post_tag' == $tax ? 'tag' : $tax );
					$term_args['name'] = $name;
					$term_args['value_field'] = 'category' == $tax ? 'term_id' : 'slug';
					wp_dropdown_categories( $term_args );
					?>
                    <form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php
						wp_dropdown_categories( $term_args );
						?>
                        <script type="text/javascript">
                            /* <![CDATA[ */
                            (function () {
                                var dropdown = document.getElementById("<?php echo esc_attr( $name );?>");

                                function onCatChange() {
                                    if (dropdown.options[dropdown.selectedIndex].value !== '') {
                                        dropdown.parentNode.submit();
                                    }
                                }

                                dropdown.onchange = onCatChange;
                            })();
                            /* ]]> */
                        </script>
                    </form>
				<?php
                elseif ( 'style-2' == $rstyle ):

					wp_tag_cloud( $term_args );
				else:
					$extra_class = $settings['toggle'] == 'yes' ? ' toggle-enable' : '';
					echo '<ul class="pc-advanced-cat '.esc_attr( $extra_class ).'">';
					wp_list_categories( $term_args );
					echo '</ul>';
				endif; ?>
            </div>
        </div>
		<?php
	}
}
