<?php
/**
 * Products template function
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'penci_elementor_products_tabs_template' ) ) {
	function penci_elementor_products_tabs_template( $settings ) {
		$settings = wp_parse_args( $settings, penci_custom_product_query_default_args() );

		$image_output    = '';
		$wrapper_classes = '';
		$header_classes  = '';
		$title_classes   = '';

		// Header classes.
		$settings['alignment'] = $settings['alignment'] ? $settings['alignment'] : 'center';
		$header_classes        .= ' text-' . $settings['alignment'];

		// Wrapper classes.
		$wrapper_classes .= ' tabs-design-' . $settings['design'];

		// Tabs settings.
		$first_tab_title = '';

		if ( isset( $settings['tabs_items'][0]['title'] ) ) {
			$first_tab_title = $settings['tabs_items'][0]['title'];
		}

		$allow_html_tags = array(
			'br'     => array(),
			'i'      => array(),
			'b'      => array(),
			'u'      => array(),
			'em'     => array(),
			'del'    => array(),
			'a'      => array(
				'href'   => true,
				'class'  => true,
				'target' => true,
				'title'  => true,
				'rel'    => true,
			),
			'strong' => array(),
			'span'   => array(
				'style' => true,
				'class' => true,
			),
		);
		?>
        <div class="woocommerce penci-products-tabs <?php echo esc_attr( $wrapper_classes ); ?>">
            <div class="penci-tabs-header <?php echo esc_attr( $header_classes ); ?>">
				<?php if ( $settings['title'] ) : ?>
                    <h4 class="tabs-name title">
						<?php if ( $image_output ) : ?>
							<?php echo $image_output; // phpcs:ignore ?>
						<?php endif; ?>

                        <span class="tabs-text<?php echo esc_attr( $title_classes ); ?>"
                              data-elementor-setting-key="title">
							<?php echo wp_kses( $settings['title'], $allow_html_tags ); ?>
						</span>
                    </h4>
				<?php endif; ?>

                <div class="tabs-navigation-wrapper">
					<span class="open-title-menu">
						<?php echo wp_kses( $first_tab_title, $allow_html_tags ); ?>
					</span>

                    <ul class="products-tabs-title">
						<?php foreach ( $settings['tabs_items'] as $key => $item ) : ?>
							<?php
							$li_classes        = '';
							$icon_output       = '';
							$item['elementor'] = true;
							$encoded_settings  = wp_json_encode( array_intersect_key( array_merge( $settings, $item ), penci_custom_product_query_default_args() ) );

							if ( 0 === $key ) {
								$li_classes .= ' active-tab-title';
							}

							if ( isset( $item['image']['id'] ) && $item['image']['id'] ) {
								$icon_output = '<span class="img-wrapper">' . penci_get_image_html( $item, 'image' ) . '</span>';
							}

							$unique_title = md5( $item['title'] );
							?>

                            <li data-layout="<?php echo esc_attr( $settings['layout'] ); ?>"
                                data-queryid="<?php echo esc_attr( $unique_title ); ?>"
                                data-atts="<?php echo esc_attr( $encoded_settings ); ?>"
                                class="<?php echo esc_attr( $li_classes ); ?>">
								<?php if ( $icon_output ) : ?>
									<?php echo $icon_output; // phpcs:ignore ?>
								<?php endif; ?>

                                <span class="tab-label">
									<?php echo esc_html( $item['title'] ); ?>
								</span>
                            </li>
						<?php endforeach; ?>
                    </ul>
                </div>
            </div>

			<?php
			if ( isset( $settings['tabs_items'][0] ) ) :
				$settings['class'] = 'active';
				?>
                <div class="penci-tab-content-container">
                    <div class="penci-products-preloader">
                        <span class="penci-loading-icon"><span class="bubble"></span><span class="bubble"></span><span
                                    class="bubble"></span></span>
                    </div>
					<?php penci_elementor_products_template( array_merge( $settings, $settings['tabs_items'][0] ) ); ?>
                </div>
			<?php endif; ?>
        </div>
		<?php
	}
}
