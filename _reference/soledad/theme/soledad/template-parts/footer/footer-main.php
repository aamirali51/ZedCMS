<?php
$footerreorder       = get_theme_mod( 'penci_footer_order_sections' ) ? get_theme_mod( 'penci_footer_order_sections' ) : 'widgets-instagram-signupform-footersocial';
$social_end_array    = array(
    'widgets-instagram-signupform-footersocial',
    'widgets-signupform-instagram-footersocial',
    'instagram-widgets-signupform-footersocial',
    'instagram-signupform-widgets-footersocial',
    'signupform-widgets-instagram-footersocial',
    'signupform-instagram-widgets-footersocial',
);
$footerreorder_array = explode( '-', $footerreorder );
if ( ! empty( $footerreorder_array ) ) {
    foreach ( $footerreorder_array as $ftsec ) {
        if ( $ftsec == 'widgets' && ! $hide_fwidget ) :
            ?>
            <?php
            if ( ( in_array(
                       $footer_layout,
                       array(
                           'style-2',
                           'style-3',
                           'style-8',
                           'style-9',
                           'style-10',
                       )
                   ) && ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) ) ) || ( in_array(
                                                                                                          $footer_layout,
                                                                                                          array(
                                                                                                              'style-1',
                                                                                                              'style-5',
                                                                                                              'style-6',
                                                                                                              'style-7',
                                                                                                          )
                                                                                                      ) && ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) ) || ( $footer_layout == 'style-4' && ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) ) ) :
                ?>
                <div id="widget-area"
                    <?php
                    if ( get_theme_mod( 'penci_footer_widget_bg_image' ) ) :
                        echo ' class="penci-lazy" data-bgset="' . get_theme_mod( 'penci_footer_widget_bg_image' ) . '"';
                    endif;
                    ?>
                >
                    <div class="container<?php echo esc_attr( $penci_footer_width ? ' container-' . $penci_footer_width : '' ); ?>">
                        <?php
                        if ( in_array(
                            $footer_layout,
                            array(
                                'style-1',
                                'style-5',
                                'style-6',
                                'style-7',
                            )
                        ) ) {
                            ?>
                            <div class="footer-widget-wrapper footer-widget-<?php echo $footer_layout; ?>">
                                <?php dynamic_sidebar( 'footer-1' ); ?>
                            </div>
                            <div class="footer-widget-wrapper footer-widget-<?php echo $footer_layout; ?>">
                                <?php dynamic_sidebar( 'footer-2' ); ?>
                            </div>
                            <div class="footer-widget-wrapper footer-widget-<?php echo $footer_layout; ?> last">
                                <?php dynamic_sidebar( 'footer-3' ); ?>
                            </div>
                            <?php
                        } elseif ( in_array(
                            $footer_layout,
                            array(
                                'style-2',
                                'style-3',
                                'style-8',
                                'style-9',
                                'style-10',
                            )
                        ) ) {
                            ?>
                            <div class="footer-widget-wrapper footer-widget-<?php echo $footer_layout; ?>">
                                <?php dynamic_sidebar( 'footer-1' ); ?>
                            </div>
                            <div class="footer-widget-wrapper footer-widget-<?php echo $footer_layout; ?> last">
                                <?php dynamic_sidebar( 'footer-2' ); ?>
                            </div>
                        <?php } elseif ( $footer_layout == 'style-4' ) { ?>
                            <div class="footer-widget-wrapper footer-widget-<?php echo $footer_layout; ?>">
                                <?php dynamic_sidebar( 'footer-1' ); ?>
                            </div>
                            <div class="footer-widget-wrapper footer-widget-<?php echo $footer_layout; ?>">
                                <?php dynamic_sidebar( 'footer-2' ); ?>
                            </div>
                            <div class="footer-widget-wrapper footer-widget-<?php echo $footer_layout; ?>">
                                <?php dynamic_sidebar( 'footer-3' ); ?>
                            </div>
                            <div class="footer-widget-wrapper footer-widget-<?php echo $footer_layout; ?> last">
                                <?php dynamic_sidebar( 'footer-4' ); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php
        endif;
        if ( $ftsec == 'instagram' && is_active_sidebar( 'footer-instagram' ) ) :
            ?>
            <div class="footer-instagram footer-instagram-html
            <?php
            if ( get_theme_mod( 'penci_footer_insta_title_overlay' ) ) :
                echo ' penci-insta-title-overlay';
            endif;
            ?>
            ">
                <?php dynamic_sidebar( 'footer-instagram' ); ?>
            </div>
        <?php
        endif;
        if ( $ftsec == 'signupform' && is_active_sidebar( 'footer-signup-form' ) ) :
            ?>
            <div class="footer-subscribe">
                <?php dynamic_sidebar( 'footer-signup-form' ); ?>
            </div>
        <?php
        endif;
        if ( $ftsec == 'footersocial' && ! get_theme_mod( 'penci_footer_social' ) && ! in_array( $footerreorder, $social_end_array ) ) :
            ?>
            <div class="penci-footer-social-media penci-footer-social-moved
            <?php
            if ( get_theme_mod( 'penci_footer_social_around' ) ) :
                echo ' footer-social-remove-circle';
            endif;
            if ( get_theme_mod( 'penci_footer_social_drop_line' ) ) :
                echo ' footer-social-drop-line';
            endif;
            if ( get_theme_mod( 'penci_footer_disable_radius_social' ) ) :
                echo ' footer-social-remove-radius';
            endif;
            if ( get_theme_mod( 'penci_footer_social_remove_text' ) ) :
                echo ' footer-social-remove-text';
            endif;
            ?>
            ">
                <div class="container<?php echo esc_attr( $penci_footer_width ? ' container-' . $penci_footer_width : '' ); ?>">
                    <div class="footer-socials-section
                    <?php
                    if ( get_theme_mod( 'penci_footer_brand_social' ) && ! get_theme_mod( 'penci_footer_social_around' ) ) {
                        echo ' penci-social-colored';
                    } elseif ( get_theme_mod( 'penci_footer_brand_social' ) && get_theme_mod( 'penci_footer_social_around' ) ) {
                        echo ' penci-social-textcolored';
                    }
                    ?>
                    ">
                        <ul class="footer-socials">
                            <?php
                            $social_data         = penci_social_media_array();
                            $custom_social_icons = get_option( 'penci_custom_socials', array() );

                            foreach ( $social_data as $name => $sdata ) {
                                if ( $sdata[0] ) {
                                    $icon_html = penci_icon_by_ver( $sdata[1] );
                                    $name      = isset( $custom_social_icons[ $name ]['name'] ) ? $custom_social_icons[ $name ]['name'] : $name;
                                    ?>
                                    <li><a href="<?php echo esc_url( do_shortcode( $sdata[0] ) ); ?>"
                                           aria-label="<?php echo ucwords( $name ); ?>" <?php echo penci_reltag_social_icons(); ?>
                                           target="_blank"><?php echo $icon_html; ?>
                                            <span><?php echo ucwords( $name ); ?></span></a></li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php
        endif;
    }
}
$ftuser_bg = '';
if ( get_theme_mod( 'penci_footer_copyright_bg_image' ) ) :
    $ftuser_bg = ' data-bgset="' . get_theme_mod( 'penci_footer_copyright_bg_image' ) . '"';
endif;
?>
<footer id="footer-section"
        class="penci-footer-social-media
        <?php
        if ( get_theme_mod( 'penci_footer_social_around' ) ) :
            echo ' footer-social-remove-circle';
        endif;
        if ( get_theme_mod( 'penci_footer_social_drop_line' ) ) :
            echo ' footer-social-drop-line';
        endif;
        if ( get_theme_mod( 'penci_footer_disable_radius_social' ) ) :
            echo ' footer-social-remove-radius';
        endif;
        if ( get_theme_mod( 'penci_footer_social_remove_text' ) ) :
            echo ' footer-social-remove-text';
        endif;
        if ( $ftuser_bg ) :
            echo ' penci-lazy';
        endif;
        ?>
        "
    <?php
        echo $ftuser_bg;
    ?>
    <?php
    if ( ! get_theme_mod( 'penci_schema_wpfoot' ) ) :
        ?>
        itemscope itemtype="https://schema.org/WPFooter"<?php endif; ?>>
    <div class="container<?php echo esc_attr( $penci_footer_width ? ' container-' . $penci_footer_width : '' ); ?>">
        <?php if ( ! get_theme_mod( 'penci_footer_social' ) && in_array( $footerreorder, $social_end_array ) ) : ?>
            <div class="footer-socials-section
            <?php
            if ( get_theme_mod( 'penci_footer_brand_social' ) && ! get_theme_mod( 'penci_footer_social_around' ) ) {
                echo ' penci-social-colored';
            } elseif ( get_theme_mod( 'penci_footer_brand_social' ) && get_theme_mod( 'penci_footer_social_around' ) ) {
                echo ' penci-social-textcolored';
            }
            ?>
            ">
                <ul class="footer-socials">
                    <?php
                    $custom_social_icons = get_option( 'penci_custom_socials', array() );
                    $social_data         = penci_social_media_array();
                    foreach ( $social_data as $name => $sdata ) {
                        if ( $sdata[0] ) {
                            $icon_html = penci_icon_by_ver( $sdata[1] );
                            $name      = isset( $custom_social_icons[ $name ]['name'] ) ? $custom_social_icons[ $name ]['name'] : $name;
                            ?>
                            <li><a href="<?php echo esc_url( do_shortcode( $sdata[0] ) ); ?>"
                                   aria-label="<?php echo ucwords( $name ); ?>" <?php echo penci_reltag_social_icons(); ?>
                                   target="_blank"><?php echo $icon_html; ?>
                                    <span><?php echo ucwords( $name ); ?></span></a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if ( get_theme_mod( 'penci_related_post_popup' ) ) : ?>
            <div class="penci-flag-rlt-popup"></div><?php endif; ?>
        <?php if ( ( get_theme_mod( 'penci_footer_logo' ) && ! get_theme_mod( 'penci_hide_footer_logo' ) ) || get_theme_mod( 'penci_footer_copyright' ) || ! get_theme_mod( 'penci_go_to_top' ) || get_theme_mod( 'penci_footer_menu' ) ) : ?>
            <div class="footer-logo-copyright
            <?php
            if ( ! get_theme_mod( 'penci_footer_logo' ) || get_theme_mod( 'penci_hide_footer_logo' ) ) :
                echo ' footer-not-logo';
            endif;
            ?>
            <?php
            if ( get_theme_mod( 'penci_go_to_top' ) ) :
                echo ' footer-not-gotop';
            endif;
            ?>
">
                <?php
                if ( get_theme_mod( 'penci_footer_logo' ) && ! get_theme_mod( 'penci_hide_footer_logo' ) ) :
                    $logo_src = get_theme_mod( 'penci_footer_logo' );
                    $flogow = penci_get_image_data_basedurl( $logo_src, 'w' );
                    $flogoh = penci_get_image_data_basedurl( $logo_src, 'h' );
                    ?>
                    <div id="footer-logo">
                        <a href="<?php echo $footer_logo_url; ?>">
                            <?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
                                <img class="penci-lazy"
                                     src="<?php echo penci_holder_image_base( $flogow, $flogoh ); ?>"
                                     data-src="<?php echo esc_url( get_theme_mod( 'penci_footer_logo' ) ); ?>"
                                     alt="<?php esc_html_e( 'Footer Logo', 'soledad' ); ?>"
                                     width="<?php echo $flogow; ?>" height="<?php echo $flogoh; ?>"/>
                            <?php } else { ?>
                                <img src="<?php echo esc_url( get_theme_mod( 'penci_footer_logo' ) ); ?>"
                                     alt="<?php esc_html_e( 'Footer Logo', 'soledad' ); ?>"
                                     width="<?php echo $flogow; ?>" height="<?php echo $flogoh; ?>"/>
                            <?php } ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ( get_theme_mod( 'penci_footer_menu' ) ) : ?>
                    <div class="footer-menu-wrap" role="navigation"
                        <?php
                        if ( ! get_theme_mod( 'penci_schema_sitenav' ) ) :
                            ?>
                            itemscope
                            itemtype="https://schema.org/SiteNavigationElement"<?php endif; ?>>
                        <?php
                        /**
                         * Display main navigation
                         */
                        wp_nav_menu(
                            array(
                                'container'      => false,
                                'theme_location' => 'footer-menu',
                                'menu_class'     => 'footer-menu',
                            )
                        );
                        ?>
                    </div>
                <?php endif; /* End check if enable footer menu */ ?>

                <?php if ( penci_get_setting( 'penci_footer_copyright' ) ) : ?>
                    <div id="footer-copyright">
                        <p><?php echo penci_get_setting( 'penci_footer_copyright' ); ?></p>
                    </div>
                <?php endif; ?>
                <?php if ( ! get_theme_mod( 'penci_go_to_top' ) ) : ?>
                    <div class="go-to-top-parent"><a aria-label="Go to top" href="#" class="go-to-top"><span><i
                                        class="penciicon-up-chevron"></i> <br><?php echo penci_get_setting( 'penci_trans_back_to_top' ); ?></span></a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</footer>