/**
 * Admin settings script for shipping fees page
 *
 * @since 1.1.0
 */
const INPUT_WRAPPER = '.input-wrapper';
const PROCESSING_FEE_ID = '#enable-processing-fees';
const PROCESSING_FEE_AMOUNT_ID = '#processing-fees-amount';
const DISPLAY_TYPE = 'medium';

( function ( $ ) {
	$( document ).ready( function () {} );

	/**
	 * Minimum amount toggle
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
} )( jQuery );
