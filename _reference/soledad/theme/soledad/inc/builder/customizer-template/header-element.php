<?php
$section_id = 'penci_header_' . $key;
if ( ! empty( $settings ) ) {
	$section_id = $settings;
}
?>
<div class="header-element <?php echo esc_attr( $key ); ?> open-section" data-element="<?php echo esc_attr( $key ); ?>"
     data-section="<?php echo esc_attr( $section_id ); ?>">
    <span class="header-element-title">
        <?php echo esc_html( $value ); ?>
    </span>
    <span class="header-element-close"></span>
</div>
