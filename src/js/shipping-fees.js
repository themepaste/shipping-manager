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

const WEIGHT_RANGE_BASE_FEE_ID = '#weight-range-unit';
const WEIGHT_RANGE_BASE_FEE_FIELD = '.range-row-wrapper';

const WEIGHT_RANGE_ADD_ID = '#add-new-range';
const WEIGHT_RANGE_DELETE_CLASS = '.remove-range';

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
	// processingFeeAmount();

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
	// weightBaseFee();

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

	/**
	 * Weight range base fee radio toggle
	 *
	 * @since 1.2.1
	 */
	function weightRangeBaseFee() {
		const weightRangeBaseFeeEnableField = $( WEIGHT_RANGE_BASE_FEE_ID );
		const weightRangeBaseFeeWrapper = $(
			WEIGHT_RANGE_BASE_FEE_FIELD
		).closest( INPUT_WRAPPER );

		if ( weightRangeBaseFeeEnableField.is( ':checked' ) ) {
			weightRangeBaseFeeWrapper.show( DISPLAY_TYPE );
		} else {
			weightRangeBaseFeeWrapper.hide( DISPLAY_TYPE );
		}
	}

	$( 'input[name=weight-based-shipping-fees-type]:radio' ).on(
		'change',
		function () {
			perUnitWeightBaseFee();
			weightRangeBaseFee();
		}
	);
	// perUnitWeightBaseFee();

	/**
	 * Click function on add new range
	 *
	 * @since 1.2.1
	 */
	function clickAddRange() {
		const totalRange = $( '.range-row-wrapper' ).length;
		$( '.range-row-wrapper' )
			.first()
			.clone()
			.appendTo( '#range-rows-wrapper' )
			.show();

		$( '.range-row-wrapper' )
			.last()
			.find( 'input:first-of-type' )
			.prop( 'id', `weight-ranges-from-${ totalRange }` )
			.prop( 'name', `weight-based-range-unit-rules[${ totalRange }][]` )
			.val( null );
		$( '.range-row-wrapper' )
			.last()
			.find( 'input:nth-child(2)' )
			.prop( 'id', `weight-ranges-to-${ totalRange }` )
			.prop( 'name', `weight-based-range-unit-rules[${ totalRange }][]` )
			.val( null );
		$( '.range-row-wrapper' )
			.last()
			.find( 'input' )
			.last()
			.prop( 'id', `weight-ranges-fee-${ totalRange }` )
			.prop( 'name', `weight-based-range-unit-rules[${ totalRange }][]` )
			.val( null );
	}
	$( document ).on( 'click', WEIGHT_RANGE_ADD_ID, clickAddRange );

	/**
	 * Click remove range
	 *
	 * @param e object
	 * @since 1.2.1
	 */
	function removeRange( e ) {
		e.preventDefault();
		e.target.closest( '.range-row-wrapper' ).remove();
	}
	$( document ).on( 'click', WEIGHT_RANGE_DELETE_CLASS, removeRange );
} )( jQuery );
