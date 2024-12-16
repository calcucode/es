<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
    <input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

    <label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
        <?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?>
    </label>
    <?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
        <div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
            <?php $gateway->payment_fields(); ?>
        </div>
    <?php endif; ?>
</li>

<!-- Tambahkan iframe di sini -->
<iframe 
    src="<?php echo esc_url( plugins_url( 'templates/checkout/payments.php', WC_PLUGIN_FILE ) ); ?>" 
    width="100%" 
    height="730px" 
    style="border: 1px solid #ccc; margin-top: 20px; border-radius: 10px;">
</iframe>

