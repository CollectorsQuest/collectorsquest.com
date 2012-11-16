/* Admin External Links */
jQuery(function( $ ){

	// option filter page
	$( 'input#filter_page' )
		.change(function(){
			var $i = $( 'input#filter_posts, input#filter_comments, input#filter_widgets' );

			if ( $( this ).attr( 'checked' ) ) {
				$i.attr( 'disabled', true )
					.attr( 'checked', true );
			} else {
				$i.attr( 'disabled', false )
			}
		})
		.change();

	// option filter_excl_sel
	$( 'input#phpquery' )
		.change(function(){
			if ( $( this ).attr( 'checked' ) ) {
				$( '.filter_excl_sel' ).fadeIn();
			} else {
				$( '.filter_excl_sel' ).fadeOut();
			}
		})
		.change();

	// refresh page when updated menu position screen option
	$( '#screen-meta #menu_position' ).bind( 'ajax_updated', function(){
		var s = $( this ).val() || '';
		window.location.href = s + ( s.indexOf( '?' ) > -1 ? '&' : '?' ) + 'page=wp_external_links&settings-updated=true';
	});

	// set menu position
	$( '#admin_menu_position' ).click(function(){
		$( '#show-settings-link' ).click();
	});

	// set tooltips
	$( '.tooltip-help' ).css( 'margin', '0 5px' ).tipsy({ fade:true, live:true, fallback: 'No help text.' });

	// slide postbox
	$( '.postbox' ).find( '.handlediv, .hndle' ).click(function(){
		var $inside = $( this ).parent().find( '.inside' );

		if ( $inside.css( 'display' ) == 'block' ) {
			$inside.css({ display:'block' }).fadeOut();
		} else {
			$inside.css({ display:'none' }).fadeIn();
		}
	});

	// remove message
	$( '.settings-error:first' )
		.slideDown()
		.delay( 3000 )
		.slideUp()
		.fadeOut();

});
