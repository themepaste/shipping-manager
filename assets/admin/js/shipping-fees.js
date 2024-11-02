( () => {
	const e = '#enable-processing-fees',
		n = 'medium';
	! ( function ( c ) {
		function o() {
			const o = c( e ),
				s = c( '#processing-fees-amount' ).closest( '.input-wrapper' );
			o.is( ':checked' ) ? s.show( n ) : s.hide( n );
		}
		c( document ).ready( function () {} ), c( e ).on( 'change', o ), o();
	} )( jQuery );
} )();
