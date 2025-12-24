<?php
$settings      = vc_map_get_attributes( $this->getShortcode(), $atts );
$css_class     = vc_shortcode_custom_css_class( $settings['css'] );
$block_id      = Penci_Vc_Helper::get_unique_id_block( 'pc_single_meta' );
$css_custom    = '';
$hide_readtime = $settings['penci_single_hreadtime'];
$avatar        = $settings['penci_single_author_avatar'];
$avatarw       = isset( $settings['penci_avatar_w'] ) && $settings['penci_avatar_w'] ? $settings['penci_avatar_w'] : 32;
$icon_style    = $settings['meta_icon_style'];
$label_text    = $settings['hide_meta_label'];
global $post;
?>
    <div id="<?php echo $block_id; ?>"
         class="post-box-meta-single style-<?php echo esc_attr( $icon_style . ' ' . $css_class ); ?>">
		<?php if ( ! $settings['penci_single_meta_author'] ) :
			?>
            <span class="author-post byline">
                    <span class="author vcard">
	                    <?php if ( ! $label_text ) {
		                    echo penci_get_setting( 'penci_trans_written_by' );
	                    } ?>
	                    <?php
	                    $author_ids = [];
	                    $author     = get_post_field( 'post_author', get_the_ID() );
	                    if ( $author ) {
		                    $author_ids[] = $author;
	                    }
	                    if ( function_exists( 'coauthors__echo' ) ) {
		                    $author_list = coauthors__echo( 'ID', 'field', array(
			                    'between'     => ',',
			                    'betweenLast' => ',',
			                    'before'      => '',
			                    'after'       => '',
		                    ), null, false );
		                    if ( $author_list ) {
			                    $author_ids = explode( ',', $author_list );
		                    }
	                    }
	                    $total   = count( $author_ids );
	                    $current = 0;
	                    foreach ( $author_ids as $author_id ) {
		                    $current ++;
		                    ?>

                            <a class="author-url url fn n"
                               href="<?php echo get_author_posts_url( $author_id ); ?>">
                                                <?php
                                                if ( ! $avatar ) {
	                                                echo get_avatar( $author_id, $avatarw );
                                                } else {
	                                                echo $current == 2 ? ', ' : ( ( $current == $total ) ? ' & ' : '' );
                                                }
                                                echo get_the_author_meta( 'display_name', $author_id );
                                                ?>
                                            </a>
	                    <?php } ?>
                    </span>
                </span>
		<?php endif; ?>
	    <?php penci_author_update_name(); ?>
		<?php if ( ! $settings['penci_single_meta_date'] ) : ?>
            <span class="pctmp-date-post">
				<?php penci_soledad_time_link( 'single' ); ?></span>
		<?php endif; ?>
		<?php if ( ! $settings['penci_single_meta_comment'] ) :
			?>
            <span class="pctmp-comment-post">
            <?php
            $comment_text  = ! $label_text ? ' ' . penci_get_setting( 'penci_trans_comment' ) : '';
            $comments_text = ! $label_text ? ' ' . penci_get_setting( 'penci_trans_comments' ) : '';
            ?>
            <?php comments_number( '0' . $comments_text, '1' . $comment_text, '%' . $comments_text ); ?></span>
		<?php endif; ?>
		<?php if ( ! $settings['penci_single_show_cview'] ) : ?>
            <span class="pctmp-view-post">
                <i class="penci-post-countview-number"><?php echo penci_get_post_views( get_the_ID() ); ?></i><?php if ( ! $label_text ) {
					echo ' ' . penci_get_setting( 'penci_trans_countviews' );
				} ?></span>
		<?php endif; ?>
		<?php if ( penci_isshow_reading_time( $hide_readtime ) ):
			?>
            <span class="single-readtime">
            <?php penci_reading_time(); ?></span>
		<?php endif; ?>
    </div>
<?php
$css = [
	'meta-color'                       => [
		'{{WRAPPER}} .post-box-meta-single, {{WRAPPER}} .post-box-meta-single span' => 'color:{{VALUE}}'
	],
	'penci_single_meta_gnr_icon_color' => [ '{{WRAPPER}} .pcmt-icon' => 'color:{{VALUE}}' ],
	'penci_single_meta_gnr_bg_color'   => [
		'{{WRAPPER}} .pcmt-icon'                                       => 'background-color:{{VALUE}}',
		'{{WRAPPER}} .post-box-meta-single.style-s3 .pcmt-icon:after'  => 'border-left-color:{{VALUE}} !important',
		'{{WRAPPER}} .post-box-meta-single.style-s4 .pcmt-icon:before' => 'border-left-color:{{VALUE}} !important',

	],
	'meta-link-color'                  => [
		'{{WRAPPER}} .post-box-meta-single a' => 'color:{{VALUE}}'
	],
	'meta-link-hcolor'                 => [
		'{{WRAPPER}} .post-box-meta-single a:hover' => 'color:{{VALUE}}'
	],
	'meta-author-color'                => [ '{{WRAPPER}} .author-post,{{WRAPPER}} .author-post .author' => 'color:{{VALUE}}' ],
	'meta-author-lcolor'               => [ '{{WRAPPER}} .author-post a' => 'color:{{VALUE}}' ],
	'meta-author-hcolor'               => [ '{{WRAPPER}} .author-post a:hover' => 'color:{{VALUE}}' ],
	'meta-pdate-color'                 => [ '{{WRAPPER}} .pctmp-date-post' => 'color:{{VALUE}} !important' ],
	'meta-pcomment-color'              => [ '{{WRAPPER}} .pctmp-comment-post' => 'color:{{VALUE}} !important' ],
	'meta-pview-color'                 => [ '{{WRAPPER}} .pctmp-view-post' => 'color:{{VALUE}} !important' ],
	'meta-preading-color'              => [ '{{WRAPPER}} .single-readtime' => 'color:{{VALUE}} !important' ],
];
penci_wpbakery_el_extract_css( $css, $settings, '#' . $block_id );
