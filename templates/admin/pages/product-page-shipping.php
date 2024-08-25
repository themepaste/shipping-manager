<?php
/**
 * Product Page Shipping Menu Page
 *
 * @since 1.1.0
 */

defined( 'ABSPATH' ) || exit;

use \Themepaste\ShippingManager\Constants;
use \Themepaste\ShippingManager\Models\ProductPageShippingSettings;

?>

<form class="tsm-admin-settings-form" method="POST">

	<?php tps_manager_admin_nonce_field(); ?>
	<div  class="input-wrapper checkbox">
		<label for="<?php echo esc_attr( ProductPageShippingSettings::PRODUCT_PAGE_SHIPPING ); ?>"><?php esc_html_e( 'Enable Product Page Shipping', 'tps-manager' ); ?></label>
		<input
			type="checkbox"
			id="<?php echo esc_attr( ProductPageShippingSettings::PRODUCT_PAGE_SHIPPING ); ?>"
			name="<?php echo esc_attr( ProductPageShippingSettings::PRODUCT_PAGE_SHIPPING ); ?>"
			value="<?php echo esc_attr( Constants::YES ); ?>"
			<?php tps_manager_is_checked( $data[ ProductPageShippingSettings::PRODUCT_PAGE_SHIPPING ] ); ?>
		>
		<div class="help-tip"><?php esc_html_e('This will enable product page shipping calculator for logged in users.', 'shipping-manager'); ?></div>
	</div>

	<div class="input-wrapper submit">
		<button class="woocommerce-save-button components-button is-primary" value="free-shipping"><?php esc_html_e( 'Save', 'tps-manager' ); ?></button>
	</div>
</form>
