<?php

namespace PenciSoledadElementor\Modules\PenciCommentsList\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Base\Base_Color;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciCommentsList extends Base_Widget {

	public function get_name() {
		return 'penci-comments-list';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Comments Listing', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-comments';
	}

	public function get_categories() {
		return array( 'penci-elements' );
	}

	public function get_keywords() {
		return array( 'category' );
	}

	protected function register_controls() {

		// Section layout
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'General', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'number',
			array(
				'label'   => __( 'Number of Comments to Show:', 'soledad' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5,
			)
		);

		$this->add_control(
			'length_words',
			array(
				'label'   => __( 'Custom Comment Content Words Length:', 'soledad' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 12,
			)
		);

		$this->add_control(
			'avatar_size',
			array(
				'label'   => __( 'Avatar Size:', 'soledad' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 70,
			)
		);

		$this->add_control(
			'avatar_bradius',
			array(
				'label'      => __( 'Avatar Border Radius:', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .penci_comments_widget.el .author-avatar img' => 'border-radius: {{SIZE}}%;',
				),
			)
		);

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => __( 'Color & Style', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'comment_spacing',
			array(
				'label'          => __( 'Spacing Between Comments', 'soledad' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array( 'max' => 200 ),
				),
				'default'        => array(
					'size' => '15',
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .penci_comments_widget.el ul li:not(:last-child)' => 'margin-bottom: calc({{SIZE}}px / 2);padding-bottom: calc({{SIZE}}px / 2);',
				),
			)
		);

		$this->add_control(
			'author_bcolor',
			array(
				'label'     => __( 'Divider Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .penci_comments_widget.el ul li:not(:last-child)' => 'border-bottom:1px solid {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'author_name_typo',
				'label'    => __( 'Author Name Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci_comments_widget.el .comment-author',
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => __( 'Name Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci_comments_widget.el .comment-author' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'name_hcolor',
			array(
				'label'     => __( 'Name Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci_comments_widget.el .comment-author:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'comment_text_typo',
				'label'    => __( 'Comment Text Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci_comments_widget.el .comment-body p',
			)
		);

		$this->add_control(
			'comment_text_color',
			array(
				'label'     => __( 'Comment Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci_comments_widget.el .comment-body p' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'comment_text_lcolor',
			array(
				'label'     => __( 'Comment Text Links Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci_comments_widget.el .comment-body p a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'comment_text_lhcolor',
			array(
				'label'     => __( 'Comment Text Links Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci_comments_widget.el .comment-body p a:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
		$this->register_block_title_style_section_controls();
	}

	protected function render() {
		$settings    = $this->get_settings();
		$comments    = get_comments( 'status=approve&number=' . $settings['number'] );
		$avatar_size = $settings['avatar_size'] ? $settings['avatar_size'] : 70;
		$length      = $settings['length_words'] ? $settings['length_words'] : 12;
		$this->markup_block_title( $settings, $this );
		if ( $comments ) {
			echo '<div class="penci_comments_widget el"><ul>';
			foreach ( $comments as $comment ) { ?>
				<li>
					<?php

					$no_thumb = 'no-small-thumbs';

					// Show the avatar if it is active only
					if ( get_option( 'show_avatars' ) ) {
						if ( isset( $comment->comment_author_email ) && $comment->comment_author_email ) {
							$usergravatar = get_avatar( $comment->comment_author_email );
						} else {
							$usergravatar = get_avatar( $comment->user_id );
						}
						$no_thumb = '';
						?>
						<div class="post-widget-thumbnail"
							style="flex: 0 0 <?php echo esc_attr( $avatar_size ); ?>px">
							<a class="author-avatar"
								href="<?php echo get_permalink( $comment->comment_post_ID ); ?>#comment-<?php echo esc_attr( $comment->comment_ID ); ?>">
								<?php echo $usergravatar; ?>
							</a>
						</div>
						<?php
					}

					?>

					<div class="comment-body <?php echo esc_attr( $no_thumb ); ?>">
						<a class="comment-author"
							href="<?php echo get_permalink( $comment->comment_post_ID ); ?>#comment-<?php echo esc_attr( $comment->comment_ID ); ?>">
							<?php echo strip_tags( $comment->comment_author ); ?>
						</a>
						<p>
						<?php
							$comment_content = wp_strip_all_tags( $comment->comment_content );
							echo wp_trim_words( $comment_content, $length );
						?>
							</p>
					</div>

				</li>

				<?php
			}
			echo '</ul></div>';
		} else {
			echo penci_get_setting( 'penci_trans_no_comments' );
		}
	}
}
