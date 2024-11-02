/**
 * Admin settings script for shipping fees page
 *
 * @since 1.1.0
 */
const INPUT_WRAPPER = '.input-wrapper';
const PROCESSING_FEE_ID = '#enable-processing-fees';
const PROCESSING_FEE_AMOUNT_ID = '#processing-fees-amount';
const DISPLAY_TYPE = 'medium';

const WEIGHT_BASE_FEE_CHECKBOX_ID = '#enable-weight-based-fees';
const WEIGHT_BASE_FEE_WRAPPER = '#weight-base-fee-wrapper';

const PER_UNIT_WEIGHT_BASE_FEE_ID = '#weight-per-unit';
const PER_UNIT_WEIGHT_BASE_FEE_FIELD = '#weight-based-per-unit-amount-fees';

( function ( $ ) {
	$( document ).ready( function () {} );

	/**
	 * Processing fee checkbox toggle
	 *
	 * @since 1.2.1
	 */
	function processingFeeAmount() {
		const processingFeeEnableField = $( PROCESSING_FEE_ID );
		const processingFeeAmountWrapper = $(
			PROCESSING_FEE_AMOUNT_ID
		).closest( INPUT_WRAPPER );

		if ( processingFeeEnableField.is( ':checked' ) ) {
			processingFeeAmountWrapper.show( DISPLAY_TYPE );
		} else {
			processingFeeAmountWrapper.hide( DISPLAY_TYPE );
		}
	}

	$( PROCESSING_FEE_ID ).on( 'change', processingFeeAmount );
	processingFeeAmount();

	/**
	 * Weight Base fee checkbox toggle
	 *
	 * @since 1.2.1
	 */
	function weightBaseFee() {
		const weightBaseFeeEnableField = $( WEIGHT_BASE_FEE_CHECKBOX_ID );
		const weightBaseFeeWrapper = $( WEIGHT_BASE_FEE_WRAPPER );

		if ( weightBaseFeeEnableField.is( ':checked' ) ) {
			weightBaseFeeWrapper.show( DISPLAY_TYPE );
		} else {
			weightBaseFeeWrapper.hide( DISPLAY_TYPE );
		}
	}

	$( WEIGHT_BASE_FEE_CHECKBOX_ID ).on( 'change', weightBaseFee );
	weightBaseFee();

	/**
	 * Per unit weight base fee radio toggle
	 *
	 * @since 1.2.1
	 */
	function perUnitWeightBaseFee() {
		const perUnitBaseWeightBaseFeeEnableField = $(
			PER_UNIT_WEIGHT_BASE_FEE_ID
		);
		const perUnitBaseWeightBaseFeeWrapper = $(
			PER_UNIT_WEIGHT_BASE_FEE_FIELD
		).closest( INPUT_WRAPPER );

		if ( perUnitBaseWeightBaseFeeEnableField.is( ':checked' ) ) {
			perUnitBaseWeightBaseFeeWrapper.show( DISPLAY_TYPE );
		} else {
			perUnitBaseWeightBaseFeeWrapper.hide( DISPLAY_TYPE );
		}
	}

	$( PER_UNIT_WEIGHT_BASE_FEE_ID ).on( 'click', perUnitWeightBaseFee );
	perUnitWeightBaseFee();
} )( jQuery );
