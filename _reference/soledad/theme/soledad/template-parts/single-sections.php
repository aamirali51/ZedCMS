<?php
$reorder      = get_theme_mod( 'penci_single_ordersec' ) ? get_theme_mod( 'penci_single_ordersec' ) : 'author-postnav-related-comments';
$reorderarray = explode( '-', $reorder );
if ( ! empty( $reorderarray ) ) {
    foreach ( $reorderarray as $sec ) {
        ?>

        <?php if ( $sec == 'author' && ! get_theme_mod( 'penci_post_author' ) ) : ?>
            <?php get_template_part( 'inc/templates/about_author' ); ?>
        <?php endif; ?>

        <?php if ( $sec == 'postnav' && ! get_theme_mod( 'penci_post_nav' ) ) : ?>
            <?php get_template_part( 'inc/templates/post_pagination' ); ?>
        <?php endif; ?>

        <?php if ( $sec == 'related' && ! get_theme_mod( 'penci_post_related' ) ) : ?>
            <?php get_template_part( 'inc/templates/related_posts' ); ?>
        <?php endif; ?>

        <?php if ( $sec == 'comments' && ! get_theme_mod( 'penci_post_hide_comments' ) ) : ?>
            <?php comments_template( '', true ); ?>
        <?php endif; ?>

        <?php
    }
}