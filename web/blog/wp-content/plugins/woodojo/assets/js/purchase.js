/**
 * WooDojo Purchase JavaScript
 *
 * All JavaScript logic for the WooDojo purchase process.
 * @since 1.0.0
 *
 * - popup()
 */

(function ($) {

  WooDojoPurchase = {
  	
  	/**
	 * init_popup()
	 *
	 * @since 1.0.0
	 */
	
	init_popup: function ( e ) {
		var paymentUrl = $( 'input[name="payment_url"]' ).val();

		if ( paymentUrl != '' && ( paymentUrl != null ) ) {
			WooDojoPurchase.popup( paymentUrl, 'woodojo-popup', 690, 360, e );
		}

		return false;
	}, // End init_popup()

  	/**
	 * popup()
	 *
	 * @since 1.0.0
	 */
 	popup: function ( url, name, width, height, e ) {
 		var top = window.screenY + ($(window).height() / 2) - (height / 2),
            left = window.screenX + ($(window).width() / 2) - (width / 2);

        var p = window.open( url, name, 'width=' + width + ',height=' + height + ',location=1,scrollbars=1,top=' + top + ',left=' + left );
        p.focus();
 	} // End popup()
  
  }; // End WooDojoPurchase Object // Don't remove this, or the sky will fall on your head.

/**
 * Execute the above methods in the WooDojoPurchase object.
 *
 * @since 1.0.0
 */
	$(document).ready(function ( e ) {

		WooDojoPurchase.init_popup( e );
	
	});
  
})(jQuery);