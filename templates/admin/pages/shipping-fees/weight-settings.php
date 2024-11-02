<?php

defined( 'ABSPATH' ) || exit;

use \Themepaste\ShippingManager\Constants;
use \Themepaste\ShippingManager\Models\ShippingFeesSettings;

function tps_manager_get_weight_range_id( int $serial, string $name ): string {
	return ShippingFeesSettings::WEIGHT_BASED_RANGE_UNIT_RULES . '_' . $serial . '_' . $name;
}

function tps_manager_get_weight_range_name( int $serial, string $name ): string {
	return ShippingFeesSettings::WEIGHT_BASED_RANGE_UNIT_RULES . '[' . $serial . ']' . '[' . $name . ']';
}

function tps_manager_get_weight_range_value( int $serial, string $name ): string {
	return $data[ ShippingFeesSettings::WEIGHT_BASED_RANGE_UNIT_RULES ][ $serial ][ $name ] ?? '';
}

?>
<div class="input-wrapper checkbox">
	<label for="<?php echo esc_attr( ShippingFeesSettings::ENABLE_WEIGHT_BASED_FEES ); ?>"><?php esc_html_e( 'Weight Based Fee', 'tps-manager' ); ?></label>
	<input
		id="<?php echo esc_attr( ShippingFeesSettings::ENABLE_WEIGHT_BASED_FEES ); ?>"
		name="<?php echo esc_attr( ShippingFeesSettings::ENABLE_WEIGHT_BASED_FEES ); ?>"
		value="<?php echo esc_attr( Constants::YES ); ?>"
		type="checkbox"
		<?php tps_manager_is_checked( $data[ ShippingFeesSettings::ENABLE_WEIGHT_BASED_FEES ] ); ?>
	>
	<div class="help-tip"><?php esc_html_e( 'Adds weight based fee for product.', 'tps-manager' ); ?></div>
</div>
<div id="weight-base-fee-wrapper">
<!-- <div class="input-wrapper radio"> -->
	<div class="single-radio-option">
		<input
			type="radio"
			id="<?php echo esc_attr( ShippingFeesSettings::WEIGHT_PER_UNIT ); ?>"
			value="<?php echo esc_attr( ShippingFeesSettings::WEIGHT_PER_UNIT ); ?>"
			name="<?php echo esc_attr( ShippingFeesSettings::WEIGHT_BASED_SHIPPING_FEES_TYPE ); ?>"
			<?php tps_manager_is_checked( $data[ ShippingFeesSettings::WEIGHT_BASED_SHIPPING_FEES_TYPE ], true, ShippingFeesSettings::WEIGHT_PER_UNIT ); ?>
		>
		<label for="<?php echo esc_attr( ShippingFeesSettings::WEIGHT_PER_UNIT ); ?>"><?php esc_html_e( 'Per Unit', 'tps-manager' ); ?></label>
		<div class="help-tip"><?php esc_html_e( 'Add fees based on per unit', 'tps-manager' ); ?></div>
	</div>
	
<!-- </div> -->
<div class="input-wrapper amount">
	<label for="<?php echo esc_attr( ShippingFeesSettings::WEIGHT_BASED_PER_UNIT_AMOUNT_FEES ); ?>"><?php esc_html_e( 'Weight Flat Fee', 'tps-manager' ); ?></label>
	<input
		id="<?php echo esc_attr( ShippingFeesSettings::WEIGHT_BASED_PER_UNIT_AMOUNT_FEES ); ?>"
		name="<?php echo esc_attr( ShippingFeesSettings::WEIGHT_BASED_PER_UNIT_AMOUNT_FEES ); ?>"
		type="text"
		value="<?php echo esc_attr( $data[ ShippingFeesSettings::WEIGHT_BASED_PER_UNIT_AMOUNT_FEES ] );?>"
	>
	<div class="help-tip"><?php esc_html_e( 'Fees for per unit weight for product shipping.', 'tps-manager' ); ?></div>
</div>

<div class="single-radio-option">
		<input
			type="radio"
			id="<?php echo esc_attr( ShippingFeesSettings::WEIGHT_RANGE_UNIT ); ?>"
			value="<?php echo esc_attr( ShippingFeesSettings::WEIGHT_RANGE_UNIT ); ?>"
			name="<?php echo esc_attr( ShippingFeesSettings::WEIGHT_BASED_SHIPPING_FEES_TYPE ); ?>"
			<?php tps_manager_is_checked( $data[ ShippingFeesSettings::WEIGHT_BASED_SHIPPING_FEES_TYPE ], true, ShippingFeesSettings::WEIGHT_RANGE_UNIT ); ?>
		>
		<label for="<?php echo esc_attr( ShippingFeesSettings::WEIGHT_RANGE_UNIT ); ?>"><?php esc_html_e( 'Unit Range', 'tps-manager' ); ?></label>
		<div class="help-tip"><?php esc_html_e( 'Add fees based on unit range', 'tps-manager' ); ?></div>
	</div>
	
<div class="input-wrapper range">
	<label for=""><?php esc_html_e( 'Weight Range Fee', 'tps-manager' ); ?></label>
	<div class="range-row-wrapper">
		from
		<input
			id="<?php echo esc_attr( tps_manager_get_weight_range_id( 0, ShippingFeesSettings::WEIGHT_FROM ) ); ?>"
			name="<?php echo esc_attr( tps_manager_get_weight_range_name( 0, ShippingFeesSettings::WEIGHT_FROM ) ); ?>"
			value="<?php echo esc_attr( tps_manager_get_weight_range_value( 0, ShippingFeesSettings::WEIGHT_FROM ) ); ?>"
			type="text"
		>
		to
		<input
			id="<?php echo esc_attr( tps_manager_get_weight_range_id( 0, ShippingFeesSettings::WEIGHT_TO ) ); ?>"
			name="<?php echo esc_attr( tps_manager_get_weight_range_name( 0, ShippingFeesSettings::WEIGHT_TO ) ); ?>"
			value="<?php echo esc_attr( tps_manager_get_weight_range_value( 0, ShippingFeesSettings::WEIGHT_TO ) ); ?>"
			type="text"
		>
		fee
		<input
			id="<?php echo esc_attr( tps_manager_get_weight_range_id( 0, ShippingFeesSettings::WEIGHT_RANGE_FEE ) ); ?>"
			name="<?php echo esc_attr( tps_manager_get_weight_range_name( 0, ShippingFeesSettings::WEIGHT_RANGE_FEE ) ); ?>"
			value="<?php echo esc_attr( tps_manager_get_weight_range_value( 0, ShippingFeesSettings::WEIGHT_RANGE_FEE ) ); ?>"
			type="text"
		>
		<div class="remove-row-button">Remove</div>
	</div>
	<div class="add-new-row-button">
		<div class="add-new-button-text">Add New</div>
	</div>
	<div class="help-tip"><?php esc_html_e( 'Fees for unit range weight for product shipping.', 'tps-manager' ); ?></div>
</div>
</div><!-- weight base fee wrapper -->