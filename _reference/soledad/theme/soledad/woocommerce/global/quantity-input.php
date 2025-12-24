<?php
/**
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 */

defined( 'ABSPATH' ) || exit;

/* translators: %s: Quantity. */
$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'woocommerce' );

// In some cases we wish to display the quantity but not allow for it to be changed.
if ( $max_value && $min_value === $max_value ) {
	$is_readonly = true;
	$input_value = $min_value;
} else {
	$is_readonly = false;
}
?>
    <div class="quantity">
        <input type="button" value="-" class="minus"/>
		<?php
		/**
		 * Hook to output something before the quantity input field.
		 *
		 * @since 7.2.0
		 */
		do_action( 'woocommerce_before_quantity_input_field' );
		?>
        <label class="screen-reader-text"
               for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_attr( $label ); ?></label>
        <input
                type="<?php echo $is_readonly ? 'text' : 'number'; ?>"
			<?php wp_readonly( $is_readonly ); ?>
                id="<?php echo esc_attr( $input_id ); ?>"
                class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
                name="<?php echo esc_attr( $input_name ); ?>"
                value="<?php echo esc_attr( $input_value ); ?>"
                title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ); ?>"
				aria-label="<?php esc_attr_e( 'Product quantity', 'woocommerce' ); ?>"
                size="999999"
                min="<?php echo esc_attr( $min_value ); ?>"
                <?php if ( 0 < $max_value ) : ?>
					max="<?php echo esc_attr( $max_value ); ?>"
				<?php endif; ?>
			<?php if ( ! $is_readonly ): ?>
                step="<?php echo esc_attr( $step ); ?>"
                placeholder="<?php echo esc_attr( $placeholder ); ?>"
                inputmode="<?php echo esc_attr( $inputmode ); ?>"
                autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'on' ); ?>"
			<?php endif; ?>
        />
        <input type="button" value="+" class="plus"/>
		<?php
		/**
		 * Hook to output something after quantity input field
		 *
		 * @since 3.6.0
		 */
		do_action( 'woocommerce_after_quantity_input_field' );
		?>
    </div>
<?php
