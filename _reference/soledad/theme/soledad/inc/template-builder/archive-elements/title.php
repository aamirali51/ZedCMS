<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciArchiveTitle extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Archive - Title', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-post-title';
	}

	public function get_categories() {
		return [ 'penci-custom-archive-builder' ];
	}

	public function get_keywords() {
		return [ 'archive', 'title' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcab-atitle elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-archive-title';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading_markup', [
			'label'   => esc_html__( 'Heading Markup', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'h1',
			'options' => [
				'h1' => 'H1',
				'h2' => 'H2',
				'h3' => 'H3',
				'h4' => 'H4',
				'h5' => 'H5',
				'h6' => 'H6',
			]
		] );

		$this->add_control( 'heading_align', [
			'label'   => __( 'Heading Align', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::CHOOSE,
			'default' => 'left',
			'options' => array(
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
			'toggle'  => true,
		] );

		$this->add_control( 'heading_line', [
			'label'     => __( 'Remove Heading Line', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => [
				'{{WRAPPER}} .pcab-abox .title-bar:after' => 'display:none',
				'{{WRAPPER}} .pcab-abox .title-bar'       => 'padding:0;margin:0;',
			],
		] );

		$this->add_control( 'show_desc', [
			'label' => __( 'Show Archive Description', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'desc_position', [
			'label'     => esc_html__( 'Description Position', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'default'   => 'after',
			'condition' => [ 'show_desc' => 'yes' ],
			'options'   => [
				'after'  => 'After Title',
				'before' => 'Before Title',
			]
		] );

		$this->add_control( 'desc_align', [
			'label'     => __( 'Description Text Align', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::CHOOSE,
			'default'   => 'center',
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
			'selectors' => [ '{{WRAPPER}} .penci-category-description.post-entry' => 'text-align:{{VALUE}}' ],
			'condition' => [ 'show_desc' => 'yes' ],
		] );

		$this->add_responsive_control( 'desc_spacing', [
			'label'      => 'Description Spacing',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 200 ),
			),
			'selectors'  => [
				'{{WRAPPER}} .post-entry.pcdcp-after'  => 'margin-top:{{SIZE}}px !important;',
				'{{WRAPPER}} .post-entry.pcdcp-before' => 'margin-bottom:{{SIZE}}px !important;',
			],
			'condition'  => [ 'show_desc' => 'yes' ],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'color_style', [
			'label' => esc_html__( 'Color & Styles', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'heading_main_typo',
			'label'    => __( 'Typography for Archive Main Title', 'soledad' ),
			'selector' => '{{WRAPPER}} .archive-box .page-title, {{WRAPPER}} .archive-box span',
		) );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typo',
			'label'    => __( 'Typography for Prefix Text', 'soledad' ),
			'selector' => '{{WRAPPER}} .archive-box span',
		) );

		$this->add_control( 'main-text-color', [
			'label'     => 'Main Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .archive-box .page-title, {{WRAPPER}} .archive-box span' => 'color:{{VALUE}} !important' ],
		] );

		$this->add_control( 'text-color', [
			'label'     => 'Prefix Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .archive-box span' => 'color:{{VALUE}} !important' ],
		] );

		$this->add_control( 'heading-line-color', [
			'label'     => 'Heading Line Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcab-abox .title-bar:after' => 'background-color:{{VALUE}} !important' ],
		] );

		$this->add_control( 'heading-line-s', [
			'label'      => 'Heading Line Spacing with Title',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 500 ),
			),
			'selectors'  => [
				'{{WRAPPER}} .pcab-abox .title-bar' => 'padding-bottom:{{SIZE}}px;',
			],
		] );

		$this->add_control( 'heading-line-w', [
			'label'      => 'Heading Line Width',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 500 ),
			),
			'selectors'  => [
				'{{WRAPPER}} .pcab-abox .title-bar:after'              => 'width:{{SIZE}}px !important;',
				'{{WRAPPER}} .pcab-abox .title-bar.align-center:after' => 'margin-left:calc({{SIZE}}px / 2 * -1)',
			],
		] );

		$this->add_control( 'heading-line-h', [
			'label'      => 'Heading Line Height',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 50 ),
			),
			'selectors'  => [
				'{{WRAPPER}} .pcab-abox .title-bar:after' => 'height:{{SIZE}}px !important;',
			],
		] );

		// desc style

		$this->add_control( 'desc-heading', [
			'label'     => 'Archive Description',
			'type'      => \Elementor\Controls_Manager::HEADING,
			'condition' => [ 'show_desc' => 'yes' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'      => 'desc_typo',
			'label'     => __( 'Typography for Archive Description', 'soledad' ),
			'selector'  => '{{WRAPPER}} .penci-archive-description.post-entry, {{WRAPPER}} .penci-archive-description.post-entry p',
			'condition' => [ 'show_desc' => 'yes' ],
		) );

		$this->add_control( 'desc-color', [
			'label'     => 'Description Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-archive-description.post-entry' => 'color:{{VALUE}} !important' ],
			'condition' => [ 'show_desc' => 'yes' ],
		] );

		$this->add_control( 'main-dtext-color', [
			'label'     => 'Description Link Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-archive-description.post-entry a' => 'color:{{VALUE}} !important' ],
			'condition' => [ 'show_desc' => 'yes' ],
		] );

		$this->add_control( 'main-dtext-hcolor', [
			'label'     => 'Description Link Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-archive-description.post-entry a:hover' => 'color:{{VALUE}} !important' ],
			'condition' => [ 'show_desc' => 'yes' ],
		] );

		$this->add_control( 'readmore-heading', [
			'label'     => 'Read More',
			'type'      => \Elementor\Controls_Manager::HEADING,
			'condition' => [ 'show_desc' => 'yes' ],
		] );

		$this->add_control( 'readmore-bg-color', [
			'label'     => 'Read More Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => [ 'show_desc' => 'yes' ],
			'selectors' => [ '{{WRAPPER}} .penci-category-description-button:before' => 'background: linear-gradient(to bottom, transparent 0px, {{VALUE}} 40px);' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'      => 'readmore_btn_typo',
			'label'     => __( 'Typography for Read more', 'soledad' ),
			'condition' => [ 'show_desc' => 'yes' ],
			'selector'  => '{{WRAPPER}} .penci-category-description-button a',
		) );

		$this->add_control( 'readmore_btn_color', [
			'label'     => 'Read More Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => [ 'show_desc' => 'yes' ],
			'selectors' => [ '{{WRAPPER}} .penci-category-description-button a' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'readmore_btn_hcolor', [
			'label'     => 'Read More Text Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'condition' => [ 'show_desc' => 'yes' ],
			'selectors' => [ '{{WRAPPER}} .penci-category-description-button a:hover' => 'color:{{VALUE}}' ],
		] );

		$this->add_responsive_control( 'desc_maxheight', [
			'label'     => 'Max Height for Description Text',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'condition' => [ 'show_desc' => 'yes' ],
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 1000, ) ),
			'selectors' => [ 'body:not(.penci-disable-desc-collapse) {{WRAPPER}} .penci-category-description' => 'max-height:{{SIZE}}px' ],
		] );

		$this->end_controls_section();

	}

	protected function render() {

		if ( penci_elementor_is_edit_mode() ) {
			$this->preview_content();
		} else {
			$this->builder_content();
		}

	}

	protected function preview_content() {
		$settings      = $this->get_settings();
		$heading_tag   = $settings['heading_markup'];
		$align         = $settings['heading_align'];
		$desc_position = $settings['desc_position'] ? $settings['desc_position'] : 'after';
		$desc_content  = '<div class="post-entry penci-category-description penci-archive-description pcdcp-' . $desc_position . '">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus consequuntur eius iste mollitia quo
                veritatis, voluptatum. Atque culpa <a href="#">deleniti eligendi</a> est explicabo modi officia optio porro reiciendis
                tempore, ut voluptas!</p>
        </div>';
		if ( $settings['desc_position'] == 'before' && $settings['show_desc'] ) {
			echo $desc_content;
		}
		?>
        <div class="archive-box pcab-abox">
            <div class="title-bar align-<?php echo $align; ?>">
                <span><?php _e( 'Prefix Text: ', 'soledad' ); ?></span>
                <<?php echo $heading_tag; ?> class="page-title">
				<?php _e( 'Archive Title', 'soledad' ); ?>
            </<?php echo $heading_tag; ?>>
        </div>
        </div>
		<?php
		if ( $settings['desc_position'] != 'before' && $settings['show_desc'] ) {
			echo $desc_content;
		}
	}

	protected function builder_content() {
		$settings      = $this->get_settings();
		$heading_tag   = $settings['heading_markup'];
		$align         = $settings['heading_align'];
		$desc_position = $settings['desc_position'] ? $settings['desc_position'] : 'after';

		$desc_content = $button_desc = '';

		if ( $settings['show_desc'] ) {

			if ( ! get_theme_mod( 'penci_archive_disable_desc_collapse', true ) ) {

				$button_desc = '<div class="penci-category-description-button"><a aria-label="' . penci_get_setting( 'penci_trans_read_more' ) . '" title="' . penci_get_setting( 'penci_trans_read_more' ) . '" href="#">' . penci_get_setting( 'penci_trans_read_more' ) . '</a></div>';
			}
			if ( is_tag() && tag_description() ) {
				$desc_content = '<div class="post-entry penci-archive-description penci-tag-description pcdcp-' . $desc_position . '"><div class="penci-category-description-inner">' . do_shortcode( tag_description() ) . '</div>' . $button_desc . '</div>';
			} elseif ( is_category() && category_description() ) {
				$desc_content = '<div class="post-entry penci-archive-description penci-category-description pcdcp-' . $desc_position . '"><div class="penci-category-description-inner">' . do_shortcode( category_description() ) . '</div>' . $button_desc . '</div>';
			} elseif ( is_archive() && get_the_archive_description() ) {
				$desc_content = '<div class="post-entry penci-category-description penci-archive-description penci-acdes-below pcdcp-' . $desc_position . '"><div class="penci-category-description-inner">' . do_shortcode( get_the_archive_description() ) . '</div>' . $button_desc . '</div>';
			}
		}

		if ( $desc_position == 'before' && $desc_content ) {
			echo $desc_content;
		}

		?>
        <div class="archive-box pcab-abox">
            <div class="title-bar align-<?php echo $align; ?>">
				<?php
				if ( is_day() ) :
					if ( penci_get_setting( 'penci_trans_daily_archives' ) ):
						echo '<span>';
						echo penci_get_setting( 'penci_trans_daily_archives' );
						echo ' </span>';
					endif;
					printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">%s</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), get_the_date() );
                elseif ( is_month() ) :
					if ( penci_get_setting( 'penci_trans_monthly_archives' ) ):
						echo '<span>';
						echo penci_get_setting( 'penci_trans_monthly_archives' );
						echo ' </span>';
					endif;
					printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">%s</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), get_the_date( _x( 'F Y', 'monthly archives date format', 'soledad' ) ) );
                elseif ( is_year() ) :
					if ( penci_get_setting( 'penci_trans_yearly_archives' ) ):
						echo '<span>';
						echo penci_get_setting( 'penci_trans_yearly_archives' );
						echo ' </span>';
					endif;
					printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">%s</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), get_the_date( _x( 'Y', 'yearly archives date format', 'soledad' ) ) );
                elseif ( is_author() ) :
					echo '<span>';
					echo penci_get_setting( 'penci_trans_author' );
					echo ' </span>';
					printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">%s</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), get_userdata( get_query_var( 'author' ) )->display_name );
                elseif ( is_category() ):
					if ( ! get_theme_mod( 'penci_remove_cat_words' ) ): ?>
                        <span><?php echo penci_get_setting( 'penci_trans_category' ); ?></span>
					<?php endif;
					printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">%s</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), single_cat_title( '', false ) );
					do_action( 'penci_archive_follow_button' );
				elseif ( is_tag() ):
					if ( ! get_theme_mod( 'penci_remove_tag_words' ) ): ?>
                        <span><?php echo penci_get_setting( 'penci_trans_tag' ); ?></span>
					<?php endif;
					printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">%s</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), single_tag_title( '', false ) );
                elseif ( is_search() ):
					echo '<span>' . penci_get_setting( 'penci_trans_search_results_for' ) . '</span> ';
					printf( wp_kses( __( '<' . $heading_tag . ' class="page-title">"%s"</' . $heading_tag . '>', 'soledad' ), penci_allow_html() ), get_search_query() );
                elseif ( is_tax() ) :
					the_archive_title( '<' . $heading_tag . ' class="page-title">', '</' . $heading_tag . '>' );
				else :
					echo '<' . $heading_tag . ' class="page-title">';
					echo penci_get_setting( 'penci_trans_archives' );
					echo '</' . $heading_tag . '>';
				endif;
				?>
            </div>
        </div>
		<?php
		if ( $desc_position != 'before' && $desc_content ) {
			echo $desc_content;
		}
	}
}
