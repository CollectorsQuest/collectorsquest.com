// frontend/sidebar-height.js

(function (window, document, $, undefined) {
  
  var SIDEBAR_HEIGHT = window.SIDEBAR_HEIGHT = {
    getValue: function(selector) {
      height = $(selector).css('height');
    	
      if (typeof height == 'undefined')
      {
    	  return 0;
      }
      
      height = height.replace('px', '');
      return parseInt(height);
    },
    init: function() {
      $('#collectibles-for-sale').show();
      $('#collections-of-interest').show();
      var h_sidebar = SIDEBAR_HEIGHT.getValue('#sidebar');
      var h_main = SIDEBAR_HEIGHT.getValue('#main');
      var h_collectibles = SIDEBAR_HEIGHT.getValue('#collectibles-for-sale');
      var h_collections = SIDEBAR_HEIGHT.getValue('#collections-of-interest');
      $('#collectibles-for-sale').hide();
      $('#collections-of-interest').hide();
      
      if (h_main >= h_sidebar)
      {
    	$('#collectibles-for-sale').show();
        $('#collections-of-interest').show();
        return false;
      }
      
      if (h_sidebar - h_collectibles - h_collections < h_main)
      {
    	$('#collectibles-for-sale').show();
      }
      if (h_sidebar - h_collections < h_main)
      {
    	$('#collections-of-interest').show();
      }
    }
  };

  // Init the controller on DOM ready
  // $( document ).ready( SIDEBAR_HEIGHT.init );

  // provide a global noop function
  window.noop = function() {};

})(this, this.document, jQuery);