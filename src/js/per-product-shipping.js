( function ( $ ) {
	$( document ).ready( function () {} );
	$( document ).on( 'click', '.add-row-button', function ( e ) {
		e.preventDefault();

		const totalZone = $( '.tsm_table_per_product>table tr' ).length;

		$( '.empty-row' )
			.clone()
			.appendTo( '.tsm_table_per_product>table' )
			.show()
			.removeClass( 'empty-row' )
			.find( 'td input' )
			.prop( 'name', `product_shipping_rates[${ totalZone - 1 }][]` );
	} );
} )( jQuery );
