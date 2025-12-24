<?php 
$header_data    = $args['header_data'];
$btn_link        = penci_builder_validate_mod( $header_data, 'penci_header_pb_button_mobile_2_link_setting' );
$btn_link_rel    = penci_builder_validate_mod( $header_data, 'penci_header_pb_button_mobile_2_link_rel', 'noreferrer' );
$btn_link_target = penci_builder_validate_mod( $header_data, 'penci_header_pb_button_mobile_2_link_target', '_blank' );
$btn_title       = penci_builder_validate_mod( $header_data, 'penci_header_pb_button_mobile_2_text_setting' );
$btn_style       = penci_builder_validate_mod( $header_data, 'penci_header_pb_button_mobile_2_style', 'style-4' );
$btn_shape       = penci_builder_validate_mod( $header_data, 'penci_header_pb_button_mobile_2_shape', 'retangle' );

$classes   = [];
$classes[] = 'penci-builder penci-builder-button penci-builder-button button-mobile-2';
$classes[] = 'button-define-' . $btn_style;
$classes[] = 'button-shape-' . $btn_shape;
$classes[] = penci_builder_validate_mod( $header_data, 'penci_header_pb_button_mobile_2_txt_css_class', 'default' );
$btn_title = ! empty( $btn_title ) ? $btn_title : 'Button Text';

if ( ($btn_link && $btn_title) || is_customize_preview() ):?>
    <a target="<?php echo esc_attr( $btn_link_target ); ?>" rel="<?php echo esc_attr( $btn_link_rel ); ?>"
       href="<?php echo esc_url( $btn_link ); ?>" class="<?php echo implode( ' ', $classes ); ?> ">
		<?php echo $btn_title; ?>
    </a>
<?php 
endif; ?>
