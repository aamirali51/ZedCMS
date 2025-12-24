<div class="penci-clearfix penci-biggrid-wrapper penci-grid-col-1 penci-grid-mcol-1 penci-bgrid-based-post penci-bgrid-style-1 pcbg-ficonpo-top-right pcbg-reiconpo-top-left penci-bgrid-content-below pencibg-imageh-none pencibg-texth-none pencibg-textani-movetop textop pc-flexmnld">
    <div class="penci-clearfix penci-biggrid penci-bgstyle-1 penci-bgel">
        <div class="penci-biggrid-inner default">
            <div class="penci-clearfix penci-biggrid-data penci-dflex">
                <div class="penci-bgitem">
                    <div class="penci-bgitin">
                        <div class="penci-bgmain">
                            <?php if ( $show_image ) : ?>
                            <div class="pcbg-thumb">
                            <?php
                            do_action( 'penci_bookmark_post', get_the_ID() );
                            ?>
                                <div class="pcbg-thumbin"><a class="pcbg-bgoverlay"
                                                             href="<?php the_permalink(); ?>"
                                                             title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>
                                    <div <?php echo penci_layout_bg(penci_image_srcset( get_the_ID(), $thumbnail, $mthumb_size ),$disable_lazy);?> class="<?php echo penci_layout_bg_class($disable_lazy);?> penci-image-holder">
                                        <?php echo penci_layout_img(penci_image_srcset( get_the_ID(), $thumbnail, $mthumb_size ),get_the_title(),$disable_lazy);?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="pcbg-content">
                                <div class="pcbg-content-flex"><a class="pcbg-bgoverlay"
                                                                  href="<?php the_permalink(); ?>"
                                                                  title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>
                                    <div class="pcbg-content-inner bgcontent-block"><a
                                                href="<?php the_permalink(); ?>"
                                                title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                                                class="pcbg-bgoverlaytext item-hover"></a>

                                        <?php if ( $showcat ) : ?>
                                            <div class="pcbg-above item-hover">
                                                <span class="cat pcbg-sub-title">
                                                    <?php penci_category( '', $primary_cat ); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <div class="pcbg-heading item-hover">
                                            <?php if ( $show_title ) : ?>
                                            <h3 class="pcbg-title" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
                                                <a href="<?php the_permalink(); ?>">
													<?php the_title(); ?>
                                                </a>
                                            </h3>
                                            <?php endif; ?>
                                        </div>
                                        <div class="grid-post-box-meta pcbg-meta item-hover">
                                            <?php if ( $show_meta ) : ?>
                                            <div class="pcbg-meta-desc">
                                                <span class="bg-date-author author-italic author vcard">
                                                    <?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
                                                        penci_coauthors_posts_links();
                                                    else: ?>
                                                        <a class="author-url url fn n"
                                                        href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="bg-date"><?php penci_soledad_time_link(); ?></span>
                                                <?php do_action( 'penci_extra_meta' ); ?>
                                            </div>
                                            <?php endif; ?>
                                            <?php if ( $excerpt ) : ?>
                                            <div class="pcbg-pexcerpt">
                                                <?php penci_the_excerpt( $excerpt_length ); ?>
                                            </div>
                                        <?php endif; ?>
                                        </div>
                                        <?php if ( $readmore ) : ?>
                                        <div class="pcbg-readmore-sec item-hover">
                                            <a href="<?php the_permalink(); ?>"
                                               class="pcbg-readmorebtn <?php echo 'pcreadmore-icon-' . $readmore_icon_pos; ?>">
                                                <span class="pcrm-text"><?php echo penci_get_setting( 'penci_trans_read_more' ); ?></span>
												<?php if ( $readmore_icon ): ?>
													<?php \Elementor\Icons_Manager::render_icon( $readmore_icon ); ?>
												<?php endif; ?>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>