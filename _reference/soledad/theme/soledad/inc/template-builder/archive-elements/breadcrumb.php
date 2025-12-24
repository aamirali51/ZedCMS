<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciArchiveBreadcrumb extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Archive - Breadcrumb', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	public function get_categories() {
		return [ 'penci-custom-archive-builder' ];
	}

	public function get_keywords() {
		return [ 'archive', 'breadcrumb' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcab-abrcrb elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-archive-breadcrumb';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'breadcrumb_align', [
			'label'     => __( 'Breadcrumb Align', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::CHOOSE,
			'default'   => 'left',
			'options'   => array(
				'left'   => array(
					'title' => __( 'Left', 'soledad' ),
					'icon'  => 'eicon-text-align-left',
				),
				'center' => array(
					'title' => __( 'Center', 'soledad' ),
					'icon'  => 'eicon-text-align-center',
				),
				'right'  => array(
					'title' => __( 'Right', 'soledad' ),
					'icon'  => 'eicon-text-align-right',
				),
			),
			'toggle'    => true,
			'selectors' => [ '{{WRAPPER}} .penci-breadcrumb' => 'text-align:{{VALUE}}' ],
		] );

		$this->end_controls_section();


		$this->start_controls_section( 'color_style', [
			'label' => esc_html__( 'Color & Styles', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'breadcrumb-typo',
			'label'    => __( 'Typography for BreadCrumb', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-breadcrumb,{{WRAPPER}} .penci-breadcrumb *',
		) );

		$this->add_control( 'breadcrumb-t-color', [
			'label'     => 'BreadCrumb Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-breadcrumb,{{WRAPPER}} .penci-breadcrumb span,{{WRAPPER}} .penci-breadcrumb i,{{WRAPPER}} .penci-breadcrumb a' => 'color:{{VALUE}} !important' ],
		] );

		$this->add_control( 'breadcrumb-l-hcolor', [
			'label'     => 'BreadCrumb Text Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-breadcrumb a:hover' => 'color:{{VALUE}} !important' ],
		] );

		$this->add_control( 'breadcrumb-spacing', [
			'label'     => 'BreadCrumb Spacing',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 300, 'step' => 0.5 ) ),
			'selectors' => [ '{{WRAPPER}} .container.penci-breadcrumb i,{{WRAPPER}} .container.penci-breadcrumb span.separator' => 'margin-left:{{SIZE}}px;margin-right:calc({{SIZE}}px - 4px)' ],
		] );

		$this->end_controls_section();

	}

	protected function render() {

		if ( is_category() ) {
			$this->builder_category_content();
		} elseif ( is_tag() ) {
			$this->builder_tag_content();
		} else {
			$this->builder_archive_content();
		}

	}

	protected function builder_category_content() {
		$settings          = $this->get_settings();
		$sidebar_position  = penci_get_sidebar_position_archive();
		$two_sidebar_class = '';
		if ( 'two-sidebar' == $sidebar_position ): $two_sidebar_class = ' two-sidebar'; endif;
		$yoast_breadcrumb = $rm_breadcrumb = '';

		$category_oj = get_queried_object();
		$fea_cat_id  = $category_oj->term_id;

		if ( function_exists( 'yoast_breadcrumb' ) ) {
			$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-crumb-inside penci-breadcrumb' . $two_sidebar_class . '">', '</div>', false );
		}

		if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
			$rm_breadcrumb = rank_math_get_breadcrumbs( [
				'wrap_before' => '<div class="container penci-crumb-inside penci-breadcrumb' . $two_sidebar_class . '"><nav aria-label="breadcrumbs" class="rank-math-breadcrumb">',
				'wrap_after'  => '</nav></div>',
			] );
		}

		if ( $rm_breadcrumb ) {
			echo $rm_breadcrumb;
		} elseif ( $yoast_breadcrumb ) {
			echo $yoast_breadcrumb;
		} else { ?>
            <div class="container penci-crumb-inside penci-breadcrumb<?php echo $two_sidebar_class; ?>">
            <span><a class="crumb"
                     href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
				<?php
				$parent_ID = penci_get_category_parent_id( $fea_cat_id );
				if ( $parent_ID ):
					echo penci_get_category_parents( $parent_ID );
				endif;
				?>
                <span><?php single_cat_title( '', true ); ?></span>
            </div>
		<?php }
	}

	protected function builder_tag_content() {
		$sidebar_position  = penci_get_sidebar_position_archive();
		$two_sidebar_class = '';
		if ( 'two-sidebar' == $sidebar_position ): $two_sidebar_class = ' two-sidebar'; endif;
		$yoast_breadcrumb = $rm_breadcrumb = '';
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-breadcrumb penci-crumb-inside' . $two_sidebar_class . '">', '</div>', false );
		}

		if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
			$rm_breadcrumb = rank_math_get_breadcrumbs( [
				'wrap_before' => '<div class="container penci-breadcrumb penci-crumb-inside' . $two_sidebar_class . '"><nav aria-label="breadcrumbs" class="rank-math-breadcrumb">',
				'wrap_after'  => '</nav></div>',
			] );
		}

		if ( $rm_breadcrumb ) {
			echo $rm_breadcrumb;
		} elseif ( $yoast_breadcrumb ) {
			echo $yoast_breadcrumb;
		} else { ?>
            <div class="container penci-breadcrumb penci-crumb-inside<?php echo $two_sidebar_class; ?>">
                            <span><a class="crumb"
                                     href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
                <span><?php echo penci_get_setting( 'penci_trans_tags' ); ?></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
                <span><?php printf( penci_get_setting( 'penci_trans_posts_tagged' ) . ' "%s"', single_tag_title( '', false ) ); ?></span>
            </div>
		<?php }
	}

	protected function builder_archive_content() {
		$sidebar_position  = penci_get_sidebar_position_archive();
		$two_sidebar_class = '';
		if ( 'two-sidebar' == $sidebar_position ): $two_sidebar_class = ' two-sidebar'; endif;
		$yoast_breadcrumb = $rm_breadcrumb = '';
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-breadcrumb penci-crumb-inside' . $two_sidebar_class . '">', '</div>', false );
		}
		if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
			$rm_breadcrumb = rank_math_get_breadcrumbs( [
				'wrap_before' => '<div class="container penci-breadcrumb penci-crumb-inside' . $two_sidebar_class . '"><nav aria-label="breadcrumbs" class="rank-math-breadcrumb">',
				'wrap_after'  => '</nav></div>',
			] );
		}
		if ( $rm_breadcrumb ) {
			echo $rm_breadcrumb;
		} elseif ( $yoast_breadcrumb ) {
			echo $yoast_breadcrumb;
		} else { ?>
            <div class="container penci-breadcrumb penci-crumb-inside">
                            <span><a class="crumb"
                                     href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
				<?php
				echo '<span>';
				echo penci_get_setting( 'penci_trans_archives' );
				echo '</span>';
				?>
            </div>
		<?php }
	}

	protected function preview_content() {
		$settings    = $this->get_settings();
		$heading_tag = $settings['heading_markup'];
		?>
        <div class="archive-box">
            <div class="title-bar">
                <<?php echo $heading_tag; ?> class="page-title">
				<?php _e( 'General Archive Title', 'soledad' ); ?>
            </<?php echo $heading_tag; ?>>
        </div>
        </div>
		<?php
	}
}
