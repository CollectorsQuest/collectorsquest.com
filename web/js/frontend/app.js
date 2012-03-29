// frontend/app.js

(function (window, document, $, undefined) {
"use strict";

var APP = window.APP = {
  /**
   * Defaults to be used throughout the project
   */
  defaults:   {
    // empty ;)
  }, // defaults


  /**
   * Common module, executed for every page
   */
  common: {
    init: function()
    {
      $(".alert").alert();

      /**
       * "Scroll to Top" link on every long page
       */
      $('#top-link').topLink({
        min: 600,
        fadeSpeed: 500
      }).click(function(e) {
        e.preventDefault();
        $.scrollTo(0,300);
      });

      $.waypoints.settings.scrollThrottle = 50;
      $('#footer').waypoint(function(event, direction)
      {
        $('#top-link').toggleClass('sticky', direction === "up");
      }, {
        offset: '100%'
      });
    }
  } // common

}; // APP


var COMMON = window.COMMON = (function(){
  "use strict";

  function cq_not_implemented_yet()
  {
    console.log('We are inside a private function returned as object literal executed by common controller. wo-hoo!');

    return true;
  }

  // return object literal
  return {
    notImplementedYet: cq_not_implemented_yet
  }
}());

})(this, this.document, jQuery);
