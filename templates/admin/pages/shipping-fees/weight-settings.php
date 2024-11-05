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

$weight_unit = get_option('woocommerce_weight_unit');
$currency_symbol = get_woocommerce_currency_symbol();
$weight_ranges = get_option('tps_manager_tps_manager_shipping_fees')['weight-based-range-unit-rules'];
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
			<span><?php echo __( 'From', 'tps-manager' ); ?></span>
			<span><?php echo __( 'To', 'tps-manager' ); ?></span>
			<span><?php echo __( 'Fee', 'tps-manager' ); ?></span>
		</div>
		<div class="range-row-wrapper" style="display: none;">
			<div class="range-from">
				<input
					id=""
					name=""
					value=""
					type="text"
				>
				<span><?php echo $weight_unit; ?></span>
			</div>
			<div class="range-to">
				<input
					id=""
					name=""
					value=""
					type="text"
				>
				<span><?php echo $weight_unit; ?></span>
			</div>
			<div class="range-fee">
				<input
					id=""
					name=""
					value=""
					type="text"
				>
				<span><?php echo $currency_symbol; ?></span>
			</div>
			<span class="dashicons dashicons-remove remove-range"></span>
		</div>
		<?php
		if ( ! empty( $weight_ranges ) ) :
			foreach ( $weight_ranges as $key => $values ) :
		?>
		<div class="range-row-wrapper">
			<div class="range-from">
				<input
					id="<?php echo 'weight-ranges-from-' . esc_attr( $key ); ?>"
					name="<?php echo 'weight-based-range-unit-rules[' . esc_attr( $key ) . '][]' ; ?>"
					value="<?php echo esc_attr( $values[0] ); ?>"
					type="text"
				>
				<span><?php echo $weight_unit; ?></span>
			</div>
			<div class="range-to">
				<input
					id="<?php echo 'weight-ranges-to-' . esc_attr( $key ); ?>"
					name="<?php echo 'weight-based-range-unit-rules[' . esc_attr( $key ) . '][]' ; ?>"
					value="<?php echo esc_attr( $values[1] ); ?>"
					type="text"
				>
				<span><?php echo $weight_unit; ?></span>
			</div>
			<div class="range-fee">
				<input
					id="<?php echo 'weight-ranges-fee-' . esc_attr( $key ); ?>"
					name="<?php echo 'weight-based-range-unit-rules[' . esc_attr( $key ) . '][]' ; ?>"
					value="<?php echo esc_attr( $values[2] ); ?>"
					type="text"
				>
				<span><?php echo $currency_symbol; ?></span>
			</div>
			<span class="dashicons dashicons-remove remove-range"></span>
		</div>
		<?php
			endforeach;
		endif;
		?>
	</div>
	<div class="add-new-row-button">
		<div class="button button-primary" id="add-new-range"><?php echo __( 'Add New Weight Range', 'tps-manager' ); ?></div>
	</div>
	<div class="help-tip"><?php esc_html_e( 'Fees for unit range weight for product shipping.', 'tps-manager' ); ?></div>
</div>
</div><!-- weight base fee wrapper -->