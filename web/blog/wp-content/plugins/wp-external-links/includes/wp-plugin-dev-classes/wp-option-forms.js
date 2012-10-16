/* WP Option Form */
jQuery( document ).ready( function( $ ){

	// save function
	function saveAjaxForm( target ) {
		var $this = $( target ),
			$form = $this.parents( 'form' ),
			vals = $form.serializeArray();

		// disable button
		$this.attr( 'disabled', true );

		// show ajax loader
		$form.find( '.ajax-feedback' ).css( 'visibility', 'visible' );

		// save option values
		$.post( ajaxurl, vals, function ( result ) {
			var $msg = $( '<strong>' ).insertBefore( $this );

			if ( result == '1' ) {
				$msg.html( 'Saved' );
			} else {
				$msg.html( 'Error: could NOT save.' )
					.css({ color: '#f00' });
			}

			$msg.css({ margin: '0 5px' })
				.delay( 1000 )
				.fadeOut(function(){
					$( this ).remove();
				});

			// enable button
			$this.attr( 'disabled', false );

			// hide ajax loader
			$form.find( '.ajax-feedback' ).css( 'visibility', 'hidden' );
		});
	};

	// add ajax post
	$( 'form.ajax-form input[type="submit"]' ).click(function( e ){
		saveAjaxForm( this );
		e.preventDefault();
	});

});
