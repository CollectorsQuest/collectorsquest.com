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
      COMMON.setupEmailSpellingHelper();
    }
  } // common

}; // APP


var COMMON = window.COMMON = (function(){

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

    setupEmailSpellingHelper: function() {
      var suggestion_html =
          '<div class="email-suggestion" style="display: none">' +
             'Did you mean ' +
             '<a class="email">' +
                '<span class="address">asf</span>@<span class="domain">gmail.com</span>' +
             '</a>?' +
           '</div>';

      // check if js/jquery/mailcheck.js is loaded
      if ($().mailcheck) {
        // find input elements of type email
        $('input[type=email]').each(function() {
          var $email_el = $(this),
              $email_el_form = $email_el.parents('form');

          var perform_mailcheck = function($el) {
            $el.mailcheck({
              suggested: function(element, suggestion) {
                // if we have a suggestion by mailcheck, display it
                var $suggestion = $el.siblings('div.email-suggestion');
                if (!$suggestion.length) {
                  $suggestion = $(suggestion_html);
                  $suggestion.insertAfter($el);
                }
                $suggestion.find('.address').html(suggestion.address);
                $suggestion.find('.domain').html(suggestion.domain);
                $suggestion.find('a').data('suggested-address', suggestion.full);
                $el.data('suggestion-shown', true);
                $suggestion.slideDown(200);
              },
              empty: function() {
                // if the user manually fixes the problem, hide the suggestion
                var $suggestion = $el.siblings('div.email-suggestion');
                $el.data('suggestion-shown', false);
                $suggestion.hide();
              }
            });
          };

          // add delegated click event on the suggestion to fill it in the email filed
          $email_el_form.on('click', '.email-suggestion a', function() {
            var $this = $(this);
            $email_el.val($this.data('suggested-address'));
            $this.parent('div').hide();
          })
          // add an on submit hook to require the user to click 2 times on the submit
          // button before submitting with an email that may be wrong
          .on('submit', function(e) {
            perform_mailcheck($email_el);

            if ($email_el.data('suggestion-shown') && !$email_el_form.data('not-first-submit') ){
              $email_el_form.data('not-first-submit', true);
              // possibly add highlight effect?
              //$email_el.siblings('div.email-suggestion').effect("highlight");
              return false;
            }

            return true;
          });

          // setup blur event - check if the email is ok
          $email_el.on('blur', function() {
            perform_mailcheck($email_el);
          });
        });
      }
    },

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
