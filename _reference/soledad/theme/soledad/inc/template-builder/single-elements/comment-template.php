<?php
// Get numbers comments
$comment_numbers = get_comments_number();
$hide_count      = 0;
if ( get_theme_mod( 'penci_single_comments_remove_name' ) ) {
	$hide_count += 1;
}
if ( get_theme_mod( 'penci_single_comments_remove_email' ) ) {
	$hide_count += 1;
}
if ( get_theme_mod( 'penci_single_comments_remove_website' ) ) {
	$hide_count += 1;
}
?>
<div class="post-comments<?php if ( $comment_numbers == 0 ): echo ' no-comment-yet'; endif;
echo ' penci-comments-hide-' . $hide_count; ?>" id="comments">
	<?php
	if ( have_comments() ) :
		if ( $GLOBALS['penci_custom_heading'] != 'yes' ) {
			echo '<div class="post-title-box"><h4 class="post-box-title">';
			comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) );
			echo '</h4></div>';
		}

		echo "<div class='comments'>";
		wp_list_comments( array(
			'avatar_size' => 100,
			'max_depth'   => 5,
			'style'       => 'div',
			'callback'    => 'penci_comments_template',
			'type'        => 'all'
		) );
		echo "</div>";

		echo "<div id='comments_pagination'>";
		paginate_comments_links( array( 'prev_text' => '&laquo;', 'next_text' => '&raquo;' ) );
		echo "</div>";

	endif;

	// If comments are closed and there are comments, let's leave a little note.
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
        <p class="no-comments"><?php echo penci_get_setting( 'penci_trans_comments_closed' ); ?></p>
	<?php endif;
	?>
</div> <!-- end comments div -->
