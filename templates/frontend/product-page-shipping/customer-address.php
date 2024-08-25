<?php
/**
 * Product page shipping single product page shipping calculator customer form
 *
 * @since TSM_SINCE
 */
defined('ABSPATH') || exit;
?>
<div class="shipping-calculator-wrapper">
	<h2><?php esc_html_e( 'Calculate Shipping', 'tps-manager' )?></h2>
	<form class="woocommerce-shipping-calculator" method="post">
		<p class="form-row from-row-wide">
			<label for="calc_shipping_country">
				<?php esc_html_e( 'Country', 'tps-manager' ); ?>
			</label>
			<select name="calc_shipping_country" id="calc_shipping_country">
				<option value=""><?php esc_html_e( 'Select a country ...', 'tps-manager' ); ?></option>
				<?php foreach( WC()->countries->get_shipping_countries() as $key => $value ): ?>
				<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p class="form-row from-row-wide">
			<label><?php esc_html_e( 'Postcode / ZIP', 'tps-manager' );?></label>
			<input type="text" name="calc_shipping_postcode" id="calc_shipping_postcode" />
		</p>
		<button type="submit" name="calc_shipping" value="1" class="button">
			<?php esc_html_e( 'Calculate Shipping', 'tps-manager' ); ?>
		</button>
	</form>
</div>
<div class="tsm-shipping-result"></div>