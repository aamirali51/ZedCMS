<div class="penci-post-reactions">
    <h4 class="penci-post-reactions-title"><?php echo penci_get_setting( 'penci_trans_reactions_title' ); ?></h4>
    <p class="penci-post-reactions-desc"><?php echo penci_get_setting( 'penci_trans_reactions_desc' ); ?></p>
	<?php
	$reactions = get_theme_mod( 'penci_post_reactions_items', [ 'like', 'love', 'haha', 'wow', 'sad', 'angry' ] );
	?>
    <ul>
		<?php foreach ( $reactions as $reaction ) : ?>
            <li class="penci-post-reaction-item penci-post-reaction-item-<?php echo esc_attr( $reaction ); ?>">
                <a href="#" class="penci-post-like penci-post-reaction penci-post-reaction-<?php echo esc_attr( $reaction ); ?>"
                   data-reaction="<?php echo esc_attr( $reaction ); ?>" data-post_id="<?php echo esc_attr( get_the_ID() ); ?>">
                    <img src="<?php echo PENCI_SOLEDAD_URL . '/images/emotions/' . $reaction . '.svg'; ?>"
                         alt="<?php echo esc_attr( $reaction ); ?>">
                </a>
                <span class="penci-reaction-count dt-share"
                      data-reaction="<?php echo esc_attr( $reaction ); ?>">
                    <?php echo esc_html( penci_getPostCountLike( get_the_ID(), $reaction ) ); ?>
                </span>
            </li>
		<?php endforeach; ?>
    </ul>
</div>