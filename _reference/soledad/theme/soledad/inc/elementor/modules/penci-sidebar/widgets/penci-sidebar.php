<?php
namespace PenciSoledadElementor\Modules\PenciSidebar\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciSidebar extends Base_Widget {

	public function get_name() {
		return 'penci-sidebar';
	}

	public function get_title() {
		return penci_get_theme_name('Penci').' '.esc_html__( ' Sidebar', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-sidebar';
	}
	
	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'Sidebar' );
	}

	protected function register_controls() {
		

		// Section layout
		$this->start_controls_section(
			'section_page', array(
				'label' => esc_html__( 'General', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'penci_sidebar', array(
				'label'   => __( 'Sidebar to Display', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'main-sidebar',
				'options' => \Penci_Custom_Sidebar::get_list_sidebar_el()
			)
		);
		$this->add_control(
			'penci_htitle_layout', array(
				'label'   => __( 'Select Sidebar Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''                  => esc_html__( 'Default( follow Customize )', 'soledad' ),
					'pcsb-boxed-none'   => esc_html__( 'No Boxed', 'soledad' ),
					'pcsb-boxed-whole'  => esc_html__( 'Boxed Whole Sidebar', 'soledad' ),
					'pcsb-boxed-widget' => esc_html__( 'Boxed Widgets on Sidebar', 'soledad' )
				)
			)
		);
		$this->add_control(
			'penci_htitle_style', array(
				'label'   => __( 'Sidebar Widget Heading Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''                  => esc_html__( 'Default( follow Customize )', 'soledad' ),
					'style-1'           => esc_html__( 'Style 1', 'soledad' ),
					'style-2'           => esc_html__( 'Style 2', 'soledad' ),
					'style-3'           => esc_html__( 'Style 3', 'soledad' ),
					'style-4'           => esc_html__( 'Style 4', 'soledad' ),
					'style-5'           => esc_html__( 'Style 5', 'soledad' ),
					'style-6'           => esc_html__( 'Style 6 - Only Text', 'soledad' ),
					'style-7'           => esc_html__( 'Style 7', 'soledad' ),
					'style-9'           => esc_html__( 'Style 8', 'soledad' ),
					'style-8'           => esc_html__( 'Style 9', 'soledad' ),
					'style-10'          => esc_html__( 'Style 10', 'soledad' ),
					'style-11'          => esc_html__( 'Style 11', 'soledad' ),
					'style-12'          => esc_html__( 'Style 12', 'soledad' ),
					'style-13'          => esc_html__( 'Style 13', 'soledad' ),
					'style-14'          => esc_html__( 'Style 14', 'soledad' ),
					'style-15'          => esc_html__( 'Style 15', 'soledad' ),
					'style-16'          => esc_html__( 'Style 16', 'soledad' ),
					'style-2 style-17'  => esc_html__( 'Style 17', 'soledad' ),
					'style-18'          => esc_html__( 'Style 18', 'soledad' ),
					'style-18 style-19' => esc_html__( 'Style 19', 'soledad' ),
					'style-18 style-20' => esc_html__( 'Style 20', 'soledad' ),
					'style-21'          => __( 'Style 21', 'soledad' ),
					'style-22'          => __( 'Style 22', 'soledad' ),
					'style-23'          => __( 'Style 23', 'soledad' ),
					'style-24'          => __( 'Style 24', 'soledad' ),
					'style-25'          => __( 'Style 25', 'soledad' ),
					'style-26'          => __( 'Style 26', 'soledad' ),
					'style-27'          => __( 'Style 27', 'soledad' ),
					'style-28'          => __( 'Style 28', 'soledad' ),
					'style-29'          => __( 'Style 29', 'soledad' ),
					'style-30'          => __( 'Style 30', 'soledad' ),
				)
			)
		);
		$this->add_control(
			'penci_htitle_align', array(
				'label'   => __( 'Sidebar Widget Heading Align', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''               => esc_html__( 'Default( follow Customize )', 'soledad' ),
					'pcalign-left'   => esc_html__( 'Left', 'soledad' ),
					'pcalign-center' => esc_html__( 'Center', 'soledad' ),
					'pcalign-right'  => esc_html__( 'Right', 'soledad' )
				)
			)
		);

		$this->add_control(
			'penci_htitle_iconpo', array(
				'label'     => __( 'Align Icon on Style 15', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''              => esc_html__( 'Default( follow Customize )', 'soledad' ),
					'pciconp-right' => esc_html__( 'Right', 'soledad' ),
					'pciconp-left'  => esc_html__( 'Left', 'soledad' ),
				),
				'condition' => array( 'penci_htitle_style' => 'style-15' ),
			)
		);

		$this->add_control(
			'penci_htitle_icon', array(
				'label'     => __( 'Custom Icon on Style 15', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''             => esc_html__( 'Default( follow Customize )', 'soledad' ),
					'pcicon-right' => esc_html__( 'Arrow Right', 'soledad' ),
					'pcicon-left'  => esc_html__( 'Arrow Left', 'soledad' ),
					'pcicon-down'  => esc_html__( 'Arrow Down', 'soledad' ),
					'pcicon-up'    => esc_html__( 'Arrow Up', 'soledad' ),
					'pcicon-star'  => esc_html__( 'Star', 'soledad' ),
					'pcicon-bars'  => esc_html__( 'Bars', 'soledad' ),
					'pcicon-file'  => esc_html__( 'File', 'soledad' ),
					'pcicon-fire'  => esc_html__( 'Fire', 'soledad' ),
					'pcicon-book'  => esc_html__( 'Book', 'soledad' ),
				),
				'condition' => array( 'penci_htitle_style' => 'style-15' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		$heading_title       = get_theme_mod( 'penci_sidebar_heading_style' ) ? get_theme_mod( 'penci_sidebar_heading_style' ) : 'style-1';
		$heading_align       = get_theme_mod( 'penci_sidebar_heading_align' ) ? get_theme_mod( 'penci_sidebar_heading_align' ) : 'pcalign-center';
		$sidebar_style       = get_theme_mod( 'penci_sidebar_style' ) ? get_theme_mod( 'penci_sidebar_style' ) : '';
		$sidebar_icon_pos    = get_theme_mod( 'penci_sidebar_icon_align' ) ? get_theme_mod( 'penci_sidebar_icon_align' ) : 'pciconp-right';
		$sidebar_icon_design = get_theme_mod( 'penci_sidebar_icon_design' ) ? get_theme_mod( 'penci_sidebar_icon_design' ) : 'pcicon-right';

		$sidebar  = $settings['penci_sidebar'] ? $settings['penci_sidebar'] : 'main-sidebar';
		$style    = $settings['penci_htitle_style'] ? $settings['penci_htitle_style'] : $heading_title;
		$align    = $settings['penci_htitle_align'] ? $settings['penci_htitle_align'] : $heading_align;
		$layout   = $settings['penci_htitle_layout'] ? $settings['penci_htitle_layout'] : $sidebar_style;
		$icon_pos = $settings['penci_htitle_iconpo'] ? $settings['penci_htitle_iconpo'] : $sidebar_icon_pos;
		$icon     = $settings['penci_htitle_icon'] ? $settings['penci_htitle_icon'] : $sidebar_icon_design;

		if ( ! isset( $sidebar ) ): $sidebar = 'main-sidebar'; endif;
		if ( ! isset( $style ) ): $style = 'style-1'; endif;
		if ( ! in_array( $align, array(
			'pcalign-center',
			'pcalign-left',
			'pcalign-right'
		) ) ): $align = 'pcalign-center'; endif;
		?>

        <div id="sidebar"
             class="penci-sidebar-content penci-sidebar-content-vc <?php echo esc_attr( $style . ' ' . $align . ' ' . $layout . ' ' . $icon_pos . ' ' . $icon ); ?>">
            <div class="theiaStickySidebar">
				<?php
				if ( is_active_sidebar( $sidebar ) ) {
					dynamic_sidebar( $sidebar );
				} else {
					dynamic_sidebar( 'main-sidebar' );
				}
				?>
            </div>
        </div>

		<?php
	}
}
