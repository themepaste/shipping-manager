( () => {
	const e = '#enable-processing-fees',
		n = 'medium',
		o = '#enable-weight-based-fees';
	! ( function ( s ) {
		function c() {
			const o = s( e ),
				c = s( '#processing-fees-amount' ).closest( '.input-wrapper' );
			o.is( ':checked' ) ? c.show( n ) : c.hide( n );
		}
		function i() {
			console.log( 'Hello' );
			const e = s( o ),
				c = s( '#weight-base-fee-wrapper' );
			e.is( ':checked' ) ? c.show( n ) : c.hide( n );
		}
		s( document ).ready( function () {} ),
			s( e ).on( 'change', c ),
			c(),
			s( o ).on( 'change', i ),
			i();
	} )( jQuery );
} )();
