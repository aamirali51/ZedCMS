<?php
$move_title_bellow    = false;
$avatar               = $args['penci_single_author_avatar'];
$avatarw              = isset( $args['penci_avatar_w'] ) && $args['penci_avatar_w'] ? $args['penci_avatar_w'] : 32;
$ava_icon_enable      = $args['penci_single_meta_ava_icon_check'];
$ava_icon             = $args['penci_single_meta_ava_icon'];
$date_icon_enable     = $args['penci_single_meta_date_icon_check'];
$date_icon            = $args['penci_single_meta_date_icon'];
$commment_icon_enable = $args['penci_single_meta_comment_icon_check'];
$commment_icon        = $args['penci_single_meta_comment_icon'];
$view_icon_enable     = $args['penci_single_meta_view_icon_check'];
$view_icon            = $args['penci_single_meta_view_icon'];
$reading_icon_enable  = $args['penci_single_meta_reading_icon_check'];
$reading_icon         = $args['penci_single_meta_reading_icon'];
$icon_style           = $args['meta_icon_style'];
$label_text           = $args['hide_meta_label'];
$thumb_alt            = $thumb_title_html = '';
global $post;
?>
<div class="header-standard header-classic single-header">
	<?php if ( ! $args['penci_post_cat'] ) : ?>
        <div class="penci-standard-cat penci-single-cat <?php echo $args['cat_pre_style']; ?>"><span
                    class="cat"><?php penci_category( '' ); ?></span></div>
	<?php endif; ?>

	<?php if ( ! $args['hide_title'] ): ?>
        <h1 class="post-title single-post-title entry-title"><span><?php the_title(); ?></span></h1>
	<?php endif; ?>
	<?php
	if ( ! $args['hide_subtitle'] ) {
		penci_display_post_subtitle();
	}
	?>
	<?php penci_soledad_meta_schema(); ?>
	<?php $hide_readtime = $args['penci_single_hreadtime']; ?>
	<?php if ( ! $args['penci_single_meta_author'] || ! $args['penci_single_meta_date'] || ! $args['penci_single_meta_comment'] || $args['penci_single_show_cview'] || penci_isshow_reading_time( $hide_readtime ) ) : ?>
        <div class="post-box-meta-single style-<?php echo esc_attr( $icon_style ); ?>">
			<?php if ( ! $args['penci_single_meta_author'] ) :
				?>
                <span class="author-post byline">
                                        <span class="author vcard">
                                            <?php
                                            if ( ! $ava_icon_enable && $ava_icon ) {
	                                            echo '<span class="pcmt-icon ava-icon">';
	                                            \Elementor\Icons_Manager::render_icon( $ava_icon );
	                                            echo '</span>';
                                            }
                                            ?>
	                                        <?php if ( ! $label_text ) {
		                                        echo penci_get_setting( 'penci_trans_written_by' );
	                                        } ?>
	                                        <?php
	                                        $author_ids = [];
	                                        $post_author_id = get_post_field( 'post_author', get_the_ID() );
	                                        if ( $post_author_id ) {
		                                        $author_ids[] = $post_author_id;
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
                                                echo get_the_author_meta( 'display_name', $author_id ); ?>
                                            </a>
	                                        <?php } ?>
                                        </span>
                                    </span>
			<?php endif; ?>
			<?php if ( ! $args['penci_single_meta_date'] ) : ?>
                <span class="pctmp-date-post">
                                <?php
                                if ( ! $date_icon_enable && $date_icon ) {
	                                echo '<span class="pcmt-icon date-icon">';
	                                \Elementor\Icons_Manager::render_icon( $date_icon );
	                                echo '</span>';
                                }
                                ?>
                                <?php penci_soledad_time_link( 'single' ); ?></span>
			<?php endif; ?>
			<?php if ( ! $args['penci_single_meta_comment'] ) :
				?>
                <span class="pctmp-comment-post">
                                    <?php
                                    if ( ! $commment_icon_enable && $commment_icon ) {
	                                    echo '<span class="pcmt-icon comment-icon">';
	                                    \Elementor\Icons_Manager::render_icon( $commment_icon );
	                                    echo '</span>';
                                    }
                                    $comment_text  = ! $label_text ? ' ' . penci_get_setting( 'penci_trans_comment' ) : '';
                                    $comments_text = ! $label_text ? ' ' . penci_get_setting( 'penci_trans_comments' ) : '';
                                    ?>
                                    <?php comments_number( '0' . $comments_text, '1' . $comment_text, '%' . $comments_text ); ?></span>
			<?php endif; ?>
			<?php if ( ! $args['penci_single_show_cview'] ) : ?>
                <span class="pctmp-view-post">
                                    <?php
                                    if ( ! $view_icon_enable && $view_icon ) {
	                                    echo '<span class="pcmt-icon view-icon">';
	                                    \Elementor\Icons_Manager::render_icon( $view_icon );
	                                    echo '</span>';
                                    }
                                    ?>
                                        <i class="penci-post-countview-number"><?php echo penci_get_post_views( get_the_ID() ); ?></i><?php if ( ! $label_text ) {
						echo ' ' . penci_get_setting( 'penci_trans_countviews' );
					} ?></span>
			<?php endif; ?>
			<?php if ( penci_isshow_reading_time( $hide_readtime ) ):
				?>
                <span class="single-readtime">
                                    <?php
                                    if ( ! $reading_icon_enable && $reading_icon ) {
	                                    echo '<span class="pcmt-icon reading-icon">';
	                                    \Elementor\Icons_Manager::render_icon( $reading_icon );
	                                    echo '</span>';
                                    }
                                    ?>
                                    <?php penci_reading_time(); ?></span>
			<?php endif; ?>
			<?php
			if ( get_the_post_thumbnail_caption() && get_theme_mod( 'penci_post_thumb_caption' ) && ! $move_title_bellow ) {
				echo '<span class="penci-featured-caption penci-fixed-caption penci-caption-relative">' . get_the_post_thumbnail_caption() . '</span>';
			}
			?>
        </div>
	<?php endif; ?>
	<?php
	$recipe_title = get_post_meta( get_the_ID(), 'penci_recipe_title', true );
	if ( has_shortcode( get_the_content(), 'penci_recipe' ) || $recipe_title ) {
		do_action( 'penci_recipes_action_hook' );
	} ?>
</div>
