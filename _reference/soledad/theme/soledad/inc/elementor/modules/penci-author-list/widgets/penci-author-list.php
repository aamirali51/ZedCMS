<?php

namespace PenciSoledadElementor\Modules\PenciAuthorList\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Base\Base_Color;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciAuthorList extends Base_Widget {

	public function get_name() {
		return 'penci-author-list';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Author List', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-user-circle-o';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'category' );
	}

	protected function register_controls() {


		// Section layout
		$this->start_controls_section( 'section_settings', array(
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$roles = [];

		$wp_roles = new \WP_Roles();
		if ( ! empty( $wp_roles ) ) {
			foreach ( $wp_roles->roles as $role_name => $role_info ) {
				$roles[ $role_name ] = $role_info['name'];
			}
		}

		$this->add_control( 'roles', array(
			'label'    => __( 'Select User Roles', 'soledad' ),
			'type'     => Controls_Manager::SELECT2,
			'multiple' => true,
			'default'  => [ 'administrator' ],
			'options'  => $roles,
		) );

		$this->add_control( 'excluded', array(
			'label' => __( 'Exclude User IDs:', 'soledad' ),
			'type'  => Controls_Manager::TEXT,
		) );

		$this->add_control( 'avatar', array(
			'label' => __( 'Hide user avatar?', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_control( 'avatar_size', array(
			'label'   => __( 'Avatar Image Size', 'soledad' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 70
		) );

		$this->add_control( 'avatar_bdradius', array(
			'label'     => __( 'Avatar Image Border Radius', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'selectors' => array(
				'{{WRAPPER}} .pc-widget-user-lists.el img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_control( 'post_count', array(
			'label' => __( 'Hide user posts count?', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		$this->start_controls_section( 'section_style_image', array(
			'label' => __( 'Color & Style', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_responsive_control( 'author_spacing', array(
			'label'      => __( 'Spacing Between Authors', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 200 ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .pc-widget-user-lists.el ul li:not(:last-child)' => 'margin-bottom: calc({{SIZE}}px / 2);padding-bottom: calc({{SIZE}}px / 2);'
			),
		) );

		$this->add_control( 'author_bcolor', array(
			'label'     => __( 'Divider Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .pc-widget-user-lists.el ul li:not(:last-child)' => 'border-bottom-color: {{VALUE}};'
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'author_name_typo',
			'label'    => __( 'Author Name Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .pc-widget-user-lists.el .name',
		) );

		$this->add_control( 'name_color', array(
			'label'     => __( 'Name Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pc-widget-user-lists.el .name a' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'name_hcolor', array(
			'label'     => __( 'Name Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pc-widget-user-lists.el .name a:hover' => 'color: {{VALUE}};' ),
		) );

		$this->add_responsive_control( 'name_spacing', array(
			'label'      => __( 'Name Spacing', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 200 ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .pc-widget-user-lists.el .name' => 'margin-bottom: {{SIZE}}px;'
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'post_count_typo',
			'label'    => __( 'Post Count Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .pc-widget-user-lists.el .count',
		) );

		$this->add_control( 'count_color', array(
			'label'     => __( 'Post Count Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pc-widget-user-lists.el .count' => 'color: {{VALUE}};' ),
		) );

		$this->end_controls_section();
		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings    = $this->get_settings();
		$role__in    = $settings['roles'];
		$exclude     = $settings['excluded'];
		$avatar      = $settings['avatar'];
		$total_posts = $settings['post_count'];
		$ava_size    = $settings['avatar_size'];
		$users_args  = [];

		if ( $role__in !== 'all' ) {
			$users_args['role__in'] = $role__in;
		}
		if ( $exclude ) {
			$users_args['exclude'] = explode( ',', $exclude );
		}
		$users = get_users( $users_args );
		if ( $users ) {
			?>
            <div class="pc-widget-user-lists el">
				<?php $this->markup_block_title( $settings, $this ); ?>
                <ul>
					<?php foreach ( $users as $user ):
						$total_post = count_user_posts( $user->ID );
						$author_link = get_author_posts_url( $user->ID );
						$text = $total_post > 1 ? penci_get_setting( 'penci_trans_posts' ) : penci_get_setting( 'penci_trans_post' );
						?>
                        <li>
							<?php if ( ! $avatar ): ?>
                                <a class="pc-uw-ava"
                                   href="<?php echo esc_url( $author_link ); ?>"><?php echo get_avatar( $user->ID, $ava_size ); ?></a>
							<?php endif; ?>
                            <div class="pc-uw-userinfo">
                                <h4 class="name"><a
                                            href="<?php echo esc_url( $author_link ); ?>"><?php echo esc_attr( $user->display_name ); ?></a>
                                </h4>
								<?php if ( ! $total_posts ): ?>
                                    <span class="count"><?php echo $total_post . ' ' . $text; ?></span>
								<?php endif; ?>
                            </div>
                        </li>
					<?php endforeach; ?>
                </ul>
            </div>
			<?php
		}
	}
}
