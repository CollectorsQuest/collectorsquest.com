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

      COMMON.setupScrollToTop();
      COMMON.setupFooterLoginOrSignup();
    }
  } // common

}; // APP


var COMMON = window.COMMON = (function(){
  "use strict";

  // return object literal
  return {
    setupScrollToTop: function() {
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
      $('#footer').waypoint(function(event, direction) {
          $('#top-link').toggleClass('sticky', direction === "up");
        }, {
          offset: '100%'
      });
    }, // setupScrollToTop

    setupFooterLoginOrSignup: function() {
      // add events to switch between login and signup form in the footer
      $('#footer-control-login-button, #footer-control-signup-button').on('click', function(e) {
        $('#footer-control-login').toggle();
        $('#footer-control-signup').toggle();
        $('#footer-form-signup').toggle();
        $('#footer-form-login').toggle();
        e.preventDefault();

        return false;
      });

      // if the username cookie is set and has a value that is truthy
      if ($.cookie(window.cq.username_cookie)) {
        // triger the event to show the login form in the footer
        $('#footer-control-login-button').trigger('click');
        // and set the username to the value of the cookie
        $('#login_username').val($.cookie(window.cq.username_cookie));
      }
    } // setupFooterLoginOrSignup()

  }; // COMMON object literal
}());


})(this, this.document, jQuery);
