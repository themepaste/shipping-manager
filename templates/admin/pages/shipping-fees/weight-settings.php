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

// $weight_ranges = ShippingFeesSettings::WEIGHT_BASED_RANGE_UNIT_RULES;
// error_log( print_r( $weight_ranges, true ));

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
<div id="weight-base-fee-wrapper" style="display:<?php echo ( tps_manager_is_checked( $data[ ShippingFeesSettings::ENABLE_WEIGHT_BASED_FEES ] ) ) ? 'block' : 'none'; ?>">
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
	
<div class="input-wrapper amount" style="display: <?php echo ( tps_manager_is_checked( $data[ ShippingFeesSettings::WEIGHT_BASED_SHIPPING_FEES_TYPE ], true, ShippingFeesSettings::WEIGHT_PER_UNIT ) ) ? 'block' : 'none'; ?>;">
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
	
<div class="input-wrapper range" style="display: <?php echo ( tps_manager_is_checked( $data[ ShippingFeesSettings::WEIGHT_BASED_SHIPPING_FEES_TYPE ], true, ShippingFeesSettings::WEIGHT_RANGE_UNIT ) ) ? 'block' : 'none'; ?>;">
	<label for=""><?php esc_html_e( 'Weight Range Fee', 'tps-manager' ); ?></label>
	<div id="range-rows-wrapper">
		<div class="range-table-header">
			<span>From</span>
			<span>To</span>
			<span>Fee</span>
		</div>
		<?php
		$weight_ranges = array( array( 0, 1, 5), array(1, 2, 10) );

		if ( ! empty( $weight_ranges ) ) :
			foreach ( $weight_ranges as $key => $values ) :
		?>
		<div class="range-row-wrapper">
			<input
				id="<?php echo 'weight-ranges-from-' . esc_attr( $key ); ?>"
				name="<?php echo 'weight-ranges[' . esc_attr( $key ) . '][]' ; ?>"
				value="<?php echo esc_attr( $values[0] ); ?>"
				type="text"
			>
			<input
				id="<?php echo 'weight-ranges-to-' . esc_attr( $key ); ?>"
				name="<?php echo 'weight-ranges[' . esc_attr( $key ) . '][]' ; ?>"
				value="<?php echo esc_attr( $values[1] ); ?>"
				type="text"
			>
			<input
				id="<?php echo 'weight-ranges-fee-' . esc_attr( $key ); ?>"
				name="<?php echo 'weight-ranges[' . esc_attr( $key ) . '][]' ; ?>"
				value="<?php echo esc_attr( $values[2] ); ?>"
				type="text"
			>
			<div class="remove-row-button remove-range"><?php echo __( 'Remove', 'tps-manager' ); ?></div>
		</div>
		<?php
		endforeach;
		endif;
		?>
	</div>
	<div class="add-new-row-button">
		<div class="add-new-button-text" id="add-new-range"><?php echo __( 'Add New', 'tps-manager' ); ?></div>
	</div>
	<div class="help-tip"><?php esc_html_e( 'Fees for unit range weight for product shipping.', 'tps-manager' ); ?></div>
</div>
</div><!-- weight base fee wrapper -->