<?php
/**
 * Product Page Shipping Menu Page
 *
 * @since TSM_SINCE
 */
defined( 'ABSPATH' ) || exit;

use \Themepaste\ShippingManager\Constants;
use \Themepaste\ShippingManager\Models\ProductPageShippingSettings;
?>

<form class="tsm-admin-settings-form" method="POST">

	<?php tsm_admin_nonce_field(); ?>
	<div  class="input-wrapper checkbox">
		<label for="<?php echo ProductPageShippingSettings::PRODUCT_PAGE_SHIPPING; ?>"><?php esc_html_e( 'Enable Product Page Shipping', 'shipping-manager' ); ?></label>
		<input
			type="checkbox"
			id="<?php echo ProductPageShippingSettings::PRODUCT_PAGE_SHIPPING; ?>"
			name="<?php echo ProductPageShippingSettings::PRODUCT_PAGE_SHIPPING; ?>"
			value="<?php echo Constants::YES; ?>"
			<?php tsm_is_checked( $data[ ProductPageShippingSettings::PRODUCT_PAGE_SHIPPING ] ); ?>
		>
		<div class="help-tip"><?php esc_html_e('This will enable product page shipping calculator for logged in users.', 'shipping-manager'); ?></div>
	</div>

	<div class="input-wrapper submit">
		<button class="woocommerce-save-button components-button is-primary" value="free-shipping"><?php esc_html_e( 'Save', 'shipping-manager' ); ?></button>
	</div>
</form>
