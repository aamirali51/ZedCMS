<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciSingleComments extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Post - Comments', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-commenting-o';
	}

	public function get_categories() {
		return [ 'penci-single-builder' ];
	}

	public function get_keywords() {
		return [ 'single', 'comment' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcsb-cml pcsb-cmf elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-single-comments';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'penci_post_move_comment_box', [
			'label' => esc_html__( 'Move Comment Form to Above the List Comments ', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'hcomment_date', [
			'label'     => 'Hide Comment Date',
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => [ '{{WRAPPER}} .thecomment .comment-text span.date' => 'display:none' ],
		] );

		$this->add_control( 'comment_rheading_lcolor', [
			'label'     => 'Remove Lines on The Block Heading',
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => [ '{{WRAPPER}} .post-box-title:before,{{WRAPPER}} .post-box-title:after,{{WRAPPER}} #respond h3.comment-reply-title span:before,{{WRAPPER}} #respond h3.comment-reply-title span:after' => 'display:none' ],
		] );

		$this->add_control( 'penci_single_comments_remove_name', [
			'label'       => esc_html__( 'Hide "Name" field on Comment Form', 'soledad' ),
			'description' => 'Note that: If you want to hide this field - you need go to Dashboard > Settings > Discussion > and un-check to "Comment author must fill out name and email" - check <a href="https://imgresources.s3.amazonaws.com/discussion_settings.png" target="_blank">this image</a> for more.',
			'type'        => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'penci_single_comments_remove_email', [
			'label'       => esc_html__( 'Hide "Email" field on Comment Form', 'soledad' ),
			'description' => 'Note that: If you want to hide this field - you need go to Dashboard > Settings > Discussion > and un-check to "Comment author must fill out name and email" - check <a href="https://imgresources.s3.amazonaws.com/discussion_settings.png" target="_blank">this image</a> for more.',
			'type'        => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'penci_single_comments_remove_website', [
			'label' => esc_html__( 'Hide "Website" field on Comment Form', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'penci_single_hide_save_fields', [
			'label'       => esc_html__( 'Remove checkbox "Save my name, email, and website in this browser for the next time I comment."', 'soledad' ),
			'description' => 'Note that: This checkbox just appears when you use Wordpress from version 4.9.6',
			'type'        => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'penci_single_gdpr', [
			'label' => esc_html__( 'Enable GDPR message on Comment Form', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'penci_single_gdpr_text', [
			'label'   => esc_html__( 'Custom GDPR Message on Comment Form', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::TEXTAREA,
			'default' => esc_html__( '* By using this form you agree with the storage and handling of your data by this website.', 'soledad' ),
		] );

		$this->add_control( 'submit_btn_align', [
			'label'     => __( 'Submit Button Align', 'soledad' ),
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
			'selectors' => [ '{{WRAPPER}} #respond p.form-submit' => 'text-align:{{VALUE}}' ],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'content_spacing_section', [
			'label' => esc_html__( 'Elements Spacing', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_responsive_control( 'comment_lista_spacingt', [
			'label'      => 'Spacing Between Comments List & Comment Form',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 200 ),
			),
			'selectors'  => [
				'{{WRAPPER}} .pc-cscomments.move-top .post-title-box' => 'padding-top:{{SIZE}}px;margin-top:{{SIZE}}px',
				'{{WRAPPER}} .pc-cscomments.normal h3.comment-reply-title' => 'padding-top:{{SIZE}}px!important;',

			],
			'condition'  => [ 'penci_post_move_comment_box' => 'yes' ]
		] );

		$this->add_responsive_control( 'commentl_heading', [
			'label'     => 'Comments List',
			'type'      => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_responsive_control( 'comment_heading_spacing', [
			'label'     => 'Heading Title Spacing',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => [ '{{WRAPPER}} .post-title-box' => 'margin-bottom:{{SIZE}}px' ],
		] );

		$this->add_responsive_control( 'comment_name_spacing', [
			'label'      => 'Author Spacing',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 200 ),
			),
			'selectors'  => [ '{{WRAPPER}} .thecomment .comment-text span.author' => 'margin-bottom:{{SIZE}}px' ],
		] );

		$this->add_responsive_control( 'comment_content_spacing', [
			'label'      => 'Comment Content Spacing',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 200 ),
			),
			'selectors'  => [ '{{WRAPPER}} .thecomment .comment-content' => 'margin-top:{{SIZE}}px' ],
		] );

		$this->add_responsive_control( 'comment_list_spacingb', [
			'label'      => 'Comments List Spacing Bottom',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 200 ),
			),
			'selectors'  => [ '{{WRAPPER}} .thecomment' => 'padding-bottom:{{SIZE}}px' ],
		] );

		$this->add_responsive_control( 'comment_child_spacing', [
			'label'      => 'Sub Comments Spacing',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 200 ),
			),
			'selectors'  => [ '{{WRAPPER}} .comments .children,{{WRAPPER}} .comments>.comment>.comment,{{WRAPPER}} .comments>.comment>.comment>.comment,{{WRAPPER}} .comments>.comment>.comment>.comment>.comment,{{WRAPPER}} .comments>.comment>.comment>.comment>.comment>.comment' => 'margin:0 0 0 {{SIZE}}px' ],
		] );

		$this->add_responsive_control( 'commentf_heading', [
			'label'     => 'Comment Form',
			'type'      => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_responsive_control( 'commentf_heading_spacing', [
			'label'     => 'Comment Form Heading Title Spacing',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => [ '{{WRAPPER}} #respond h3.comment-reply-title' => 'margin-bottom:{{SIZE}}px' ],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'color_style', [
			'label' => esc_html__( 'Comments List', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'comment_heading_typo',
			'label'    => __( 'Typography for Heading Title', 'soledad' ),
			'selector' => '{{WRAPPER}} .post-box-title',
		) );

		$this->add_control( 'comment_heading_color', [
			'label'     => 'Heading Title Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-box-title' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'comment_heading_lcolor', [
			'label'     => 'Heading Title Lines Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-box-title:before,{{WRAPPER}} .post-box-title:after' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'author_ava_width', [
			'label'     => 'Author Avatar Width',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => [
				'{{WRAPPER}} .thecomment .author-img'   => 'width:{{SIZE}}px',
				'{{WRAPPER}} .thecomment .comment-text' => 'margin-left:calc({{SIZE}}px + 20px)',
			],
		] );

		$this->add_control( 'author_ava_boderradius', [
			'label'     => 'Author Avatar Borders Radius',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => [ '{{WRAPPER}} .thecomment .author-img' => 'border-radius:{{SIZE}}px;overflow:hidden;' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'comment_author_text_typo',
			'label'    => __( 'Typography for Author Name', 'soledad' ),
			'selector' => '{{WRAPPER}} .thecomment .comment-text span.author, {{WRAPPER}} .thecomment .comment-text span.author a',
		) );

		$this->add_control( 'comment_name_color', [
			'label'     => 'Author Name Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .thecomment .comment-text span.author, {{WRAPPER}} .thecomment .comment-text span.author a' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'comment_name_hcolor', [
			'label'     => 'Author Name Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .thecomment .comment-text span.author a:hover' => 'color:{{VALUE}}' ],
		] );



		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'comment_date_typo',
			'label'    => __( 'Typography for Comment Date', 'soledad' ),
			'selector' => '{{WRAPPER}} .thecomment .comment-text span.date',
		) );

		$this->add_control( 'comment_date_color', [
			'label'     => 'Color for Comment Date',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .thecomment .comment-text span.date' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'comment_date_icolor', [
			'label'     => 'Comment Date Icon Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .thecomment .comment-text span.date i' => 'color:{{VALUE}}' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'comment_content_typo',
			'label'    => __( 'Typography for Comment Content', 'soledad' ),
			'selector' => '{{WRAPPER}} .thecomment .comment-content',
		) );

		$this->add_control( 'comment_content_color', [
			'label'     => 'Color for Comment Content',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .thecomment .comment-content' => 'color:{{VALUE}}' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'comment_reply_typo',
			'label'    => __( 'Typography for Reply Button', 'soledad' ),
			'selector' => '{{WRAPPER}} .post-comments span.reply a,{{WRAPPER}} #respond h3 small a',
		) );

		$this->add_control( 'comment_reply_color', [
			'label'     => 'Reply Button Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-comments span.reply a,{{WRAPPER}} #respond h3 small a' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'comment_reply_hcolor', [
			'label'     => 'Reply Button Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-comments span.reply a:hover,{{WRAPPER}} #respond h3 small a:hover' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'comment_item_bcolor', [
			'label'     => 'General Borders Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .comments .comment,{{WRAPPER}} .pc-cscomments.move-top .post-title-box,{{WRAPPER}} #respond h3' => 'border-color:{{VALUE}}' ],
		] );

		$this->add_responsive_control( 'comment_list_spacingt', [
			'label'      => 'Comments List Spacing Top',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 200 ),
			),
			'selectors'  => [
				'{{WRAPPER}} .thecomment'                                    => 'padding-top:{{SIZE}}px',
				'{{WRAPPER}} .comments > .comment:first-child > .thecomment' => 'padding-top:0',
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'comment_form_style', [
			'label' => esc_html__( 'Comment Form', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'comment_form_heading_typo',
			'label'    => __( 'Typography for Heading Title', 'soledad' ),
			'selector' => '{{WRAPPER}} #respond h3.comment-reply-title span',
		) );

		$this->add_control( 'commentf_heading_color', [
			'label'     => 'Heading Title Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} #respond h3.comment-reply-title span' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'commentf_heading_lcolor', [
			'label'     => 'Heading Title Lines Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} #respond h3.comment-reply-title span:before,{{WRAPPER}} #respond h3.comment-reply-title span:after' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'commentf_form_settings', [
			'label' => 'Comment Form',
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'commentf_input_text_typo',
			'label'    => __( 'Typography for Input Text', 'soledad' ),
			'selector' => '{{WRAPPER}} #respond textarea, {{WRAPPER}} #respond input',
		) );

		$this->add_control( 'commentf_input_text_color', [
			'label'     => 'Input Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} #respond textarea, {{WRAPPER}} #respond input' => 'color:{{VALUE}}',
				'{{WRAPPER}} #respond ::placeholder'                        => 'color:{{VALUE}}',
			],
		] );


		$this->add_control( 'commentf_input_border_color', [
			'label'     => 'Input Borders Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} #respond textarea, {{WRAPPER}} #respond input' => 'border-color:{{VALUE}}' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'commentf_btn_text_typo',
			'label'    => __( 'Typography for Submit Text', 'soledad' ),
			'selector' => '{{WRAPPER}} #respond #submit',
		) );

		$this->add_control( 'commentf_submit_bg_color', [
			'label'     => 'Submit Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} #respond #submit' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'commentf_submit_bg_hcolor', [
			'label'     => 'Submit Background Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} #respond #submit:hover' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'commentf_submit_txt_color', [
			'label'     => 'Submit Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} #respond #submit' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'commentf_submit_txt_hcolor', [
			'label'     => 'Submit Text Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} #respond #submit:hover' => 'color:{{VALUE}}' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'      => 'commentf_cookies_notice_typo',
			'label'     => __( 'Typography for Cookie Save Notice', 'soledad' ),
			'selector'  => '{{WRAPPER}} #respond .comment-form-cookies-consent',
			'condition' => [ 'penci_single_hide_save_fields!' => 'yes' ]
		) );

		$this->add_control( 'commentf_cookies_notice_color', [
			'label'     => 'Cookie Save Notice Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} #respond .comment-form-cookies-consent' => 'color:{{VALUE}}' ],
			'condition' => [ 'penci_single_hide_save_fields!' => 'yes' ]
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'      => 'commentf_gdpr_text_typo',
			'label'     => __( 'Typography for GDPR Message', 'soledad' ),
			'selector'  => '{{WRAPPER}} #respond .penci-gdpr-message',
			'condition' => [ 'penci_single_gdpr' => 'yes' ]
		) );

		$this->add_control( 'commentf_gdpr_text_color', [
			'label'     => 'GDPR Message Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} #respond .penci-gdpr-message' => 'color:{{VALUE}}' ],
			'condition' => [ 'penci_single_gdpr' => 'yes' ]
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
		$settings   = $this->get_settings();
		$hide_count = 0;
		if ( $settings['penci_single_comments_remove_name'] ) {
			$hide_count += 1;
		}
		if ( $settings['penci_single_comments_remove_email'] ) {
			$hide_count += 1;
		}
		if ( $settings['penci_single_comments_remove_website'] ) {
			$hide_count += 1;
		}
		$comment_form = '<div id="respond" class="comment-respond">
    <h3 id="reply-title" class="comment-reply-title">
    	<span>Leave a Comment</span>
    	<small><a rel="nofollow" id="cancel-comment-reply-link" href="#respond" style="display:none;">Cancel Reply</a></small>
    </h3>
    <form action="#" method="post" id="commentform" class="comment-form">
        <p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" placeholder="Your Comment" aria-required="true" spellcheck="false"></textarea></p>';

		if ( ! $settings['penci_single_comments_remove_name'] ) {
			$comment_form .= '<p class="comment-form-author"><input id="author" name="author" type="text" value="" placeholder="Name*" size="30" aria-required="true"></p>';
		}

		if ( ! $settings['penci_single_comments_remove_email'] ) {
			$comment_form .= '<p class="comment-form-email"><input id="email" name="email" type="text" value="" placeholder="Email*" size="30" aria-required="true"></p>';
		}

		if ( ! $settings['penci_single_comments_remove_website'] ) {
			$comment_form .= '<p class="comment-form-url"><input id="url" name="url" type="text" value="" placeholder="Website" size="30"></p>';
		}
		if ( ! $settings['penci_single_hide_save_fields'] ) {
			$comment_form .= '<p class="comment-form-cookies-consent">
        	<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes">
        	<span class="comment-form-cookies-text" for="wp-comment-cookies-consent">Save my name, email, and website in this browser for the next time I comment.</span>
        </p>';
		}
		if ( $settings['penci_single_gdpr'] ) {
			$comment_form .= '<div class="penci-gdpr-message">' . $settings['penci_single_gdpr_text'] . '</div>';
		}
		$comment_form .= '<p class="form-submit"><input name="submit" type="submit" id="submit" class="submit" value="Submit"></p>
    </form>
</div>';
		$comment_list = '<div class="post-title-box"><h4 class="post-box-title">3 comments</h4></div><div class="comments">
    <div class="comment byuser comment-author-admin even thread-even depth-1" id="comment-62" itemprop="" itemscope="itemscope" itemtype="https://schema.org/UserComments">
        <meta itemprop="discusses" content="Green Corner in My Home">
        <link itemprop="url" href="#comment-62">
        <div class="thecomment">
            <div class="author-img">
                <img alt="" src="' . PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=100&h=100" width="100" height="100"></div>
            <div class="comment-text">
                <span class="author" itemprop="creator" itemtype="https://schema.org/Person"><span itemprop="name"><a href="http://pencidesign.com/" rel="external nofollow ugc" class="url">Penci</a></span></span>
                <span class="date" datetime="2017-07-19T03:55:55+00:00" title="Wednesday, July 19, 2017, 3:55 am" itemprop="commentTime"><i class="penci-faicon fa fa-clock-o"></i>July 19, 2017 - 3:55 am</span>
                <div class="comment-content" itemprop="commentText">
                    <p>Neque porro quisquam est, qui dolorem
                        ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius
                        modi
                        tempora incidunt ut labore.</p>
                </div>
                <span class="reply">
                    <a rel="nofollow" class="comment-reply-link" href="#?replytocom=62#respond" data-commentid="62" data-postid="243" data-belowelement="comment-62" data-respondelement="respond" data-replyto="Reply to Penci" aria-label="Reply to Penci">Reply</a> </span>
            </div>
        </div>
        <div class="comment byuser comment-author-admin odd alt depth-2" id="comment-63" itemprop="" itemscope="itemscope" itemtype="https://schema.org/UserComments">
            <meta itemprop="discusses" content="Green Corner in My Home">
            <link itemprop="url" href="#comment-63">
            <div class="thecomment">
                <div class="author-img">
                    <img alt="" src="' . PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=100&h=100" width="100" height="100"></div>
                <div class="comment-text">
                    <span class="author" itemprop="creator" itemtype="https://schema.org/Person"><span itemprop="name"><a href="http://pencidesign.com/" rel="external nofollow ugc" class="url">Penci</a></span></span>
                    <span class="date" datetime="2017-07-19T03:56:02+00:00" title="Wednesday, July 19, 2017, 3:56 am" itemprop="commentTime"><i class="penci-faicon fa fa-clock-o"></i>July 19, 2017 - 3:56 am</span>
                    <div class="comment-content" itemprop="commentText">
                        <p>Quis autem vel eum iure
                            reprehenderit
                            qui in ea voluptate velit esse quam nihil.</p>
                    </div>
                    <span class="reply">
                        <a rel="nofollow" class="comment-reply-link" href="#?replytocom=63#respond" data-commentid="63" data-postid="243" data-belowelement="comment-63" data-respondelement="respond" data-replyto="Reply to Penci" aria-label="Reply to Penci">Reply</a> </span>
                </div>
            </div>
        </div><!-- #comment-## -->
    </div><!-- #comment-## -->
    <div class="comment byuser comment-author-admin even thread-odd thread-alt depth-1" id="comment-64" itemprop="" itemscope="itemscope" itemtype="https://schema.org/UserComments">
        <meta itemprop="discusses" content="Green Corner in My Home">
        <link itemprop="url" href="#comment-64">
        <div class="thecomment">
            <div class="author-img">
                <img alt="" src="' . PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=100&h=100" width="100" height="100"></div>
            <div class="comment-text">
                <span class="author" itemprop="creator" itemtype="https://schema.org/Person"><span itemprop="name"><a href="http://pencidesign.com/" rel="external nofollow ugc" class="url">Penci</a></span></span>
                <span class="date" datetime="2017-07-19T03:56:10+00:00" title="Wednesday, July 19, 2017, 3:56 am" itemprop="commentTime"><i class="penci-faicon fa fa-clock-o"></i>July 19, 2017 - 3:56 am</span>
                <div class="comment-content" itemprop="commentText">
                    <p>Et harum quidem rerum facilis est et
                        expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque
                        nihil impedit quo minus id quod maxime placeat facere.</p>
                </div>
                <span class="reply">
                    <a rel="nofollow" class="comment-reply-link" href="#?replytocom=64#respond" data-commentid="64" data-postid="243" data-belowelement="comment-64" data-respondelement="respond" data-replyto="Reply to Penci" aria-label="Reply to Penci">Reply</a> </span>
            </div>
        </div>
    </div><!-- #comment-## -->
</div>';
		$class        = $settings['penci_post_move_comment_box'] ? 'move-top' : 'normal';
		echo '<div class="pc-cscomments ' . $class . '">';
		echo '<div id="comments" class="post-comments penci-comments-hide-' . $hide_count . '">';
		if ( $settings['penci_post_move_comment_box'] ) {
			echo $comment_form . $comment_list;
		} else {
			echo $comment_list . $comment_form;
		}
		echo '</div></div>';
	}

	protected function builder_content() {
		$settings = $this->get_settings();
		$mods     = [
			'penci_single_gdpr_text',
			'penci_single_gdpr',
			'penci_single_hide_save_fields',
			'penci_post_move_comment_box',
			'penci_single_comments_remove_name',
			'penci_single_comments_remove_email',
			'penci_single_comments_remove_website'
		];
		foreach ( $mods as $mod ) {
			$value = $settings[ $mod ];
			add_filter( 'theme_mod_' . $mod, function () use ( $value ) {
				return $value;
			} );
		}
		$class = $settings['penci_post_move_comment_box'] ? 'move-top' : 'normal';
		echo '<div class="pc-cscomments ' . $class . '">';
		comments_template();
		echo '</div>';
	}
}
