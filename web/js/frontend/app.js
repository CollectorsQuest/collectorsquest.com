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
    init: function() {
      $(".alert").alert();
      $('.fade-white').mosaic();

      $("a.target").bigTarget({
        hoverClass: 'over',
        clickZone: 'div.link'
      });

      COMMON.setupScrollToTop();
      COMMON.setupFooterLoginOrSignup();
      COMMON.setupEmailSpellingHelper();
    } // init
  }, // common

  /**
   * "general" symfony module
   */
  general: {
    // general/index is the homepage action
    index: function() {
      GENERAL.setupCarousel();
    } // index
  }, // general

  /**
   * "search" symfony module
   */
  search: {
    index: function() {
      SEARCH.setupMasonry();
    } // index
  } // search

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


var GENERAL = window.GENERAL = (function(){

  // return object literal
  return {
    setupCarousel: function() {
      var $roundaboutEl = $('#sample-roundabout'),
          originalHtml = $roundaboutEl.html(),
          childInFocus = 0,
          switchResolutions = [];

      /**
       * Find the current resolution index, 0 based, from switchResolutions
       */
      var findCurrentResolutionIndex = function() {
        var index = 0,
            currentWidth = window.cq.helpers.getWindowWidth();

        $.each(switchResolutions, function(i, resolution){
          if ( currentWidth > resolution ) {
            index = i+1;
          } else {
            return false; // exit each
          }
        });

        return index;
      };
      var resolutionIndex = findCurrentResolutionIndex();

      var setupRoundabout = function() {
        $roundaboutEl.hide();
        $roundaboutEl.roundabout({
          startingChild: childInFocus,
          shape: 'square',
          minScale: 0.6,
          minOpacity: 1,
          duration: 800,
          easing: 'easeOutQuad',
          triggerFocusEvents: true,
          enableDrag: true,
          responsive: true,
          dropEasing: 'easeOutBounce',
          btnNext: '.button-carousel-next',
          btnPrev: '.button-carousel-previous',
          autoplay: true,
          autoplayDuration: 6000,
          autoplayPauseOnHover: true
        },function(){
          $roundaboutEl.fadeTo(1000, 1)
        });
      }; // setup_roundabout

      /**
       * Callback executed after the window resize operation has finished,
       * and a small timeout has passed (so that it does not fire while the user
       * is still resizing the window
       *
       * If the current resolution has a different index than when the window
       * was loaded then destroy the roundabout and recreate it, so that it will
       * use the new dimensions
       */
      var onResizeComplete = function() {
        if (findCurrentResolutionIndex() !== resolutionIndex) {
          // keep the currenly displayed item
          childInFocus = $roundaboutEl.roundabout('getChildInFocus');
          // fix for multiple intervals left from initial roundabout
          $roundaboutEl.roundabout('stopAutoplay');
          // fade out the current roundabout and regenerate it from the original html
          $roundaboutEl.fadeOut(500, function(){
            $roundaboutEl.html(originalHtml);
            // we need to manually make webfonts visible, because usually
            // the js for them will have executed after we have copied the original
            // html, and the visibility will not be set.
            $roundaboutEl.find('.webfont').css('visibility', 'visible');
            setupRoundabout();
          });

          // set the new resolution index
          resolutionIndex = findCurrentResolutionIndex();
        }
      };

      // bind on window resize, but fire only after 200 ms of no resize happening
      var resizeTimeout = false;
      $(window).on('resize', function() {
        if (false !== resizeTimeout) {
          clearTimeout(resizeTimeout);
        }
        resizeTimeout = setTimeout(onResizeComplete, 200);
      });

      // all is in place, setup the roundabout
      setupRoundabout();

    } // setupCarousel()

  }; // GENERAL object literal
}());

var SEARCH = window.SEARCH = (function(){

  var defaults = {
    masonry: {
      add_infinite_scroll: false
    }
  };

  // return object literal
  return {
    setupMasonry: function() {
      var $container = $('#search-results'),
          settings = $.extend({}, defaults, window.cq.settings);

      $container.imagesLoaded(function() {
        $container.masonry({
          itemSelector : '.brick',
          columnWidth : 196, gutterWidth: 15,
          isAnimated: !Modernizr.csstransitions
        });
      });

      if (settings.masonry.add_infinite_scroll) {
        $container.infinitescroll({
            navSelector: '#search-pagination',
            nextSelector: '#search-pagination li.next a',
            itemSelector: '.brick',
            loading: {
              finishedMsg: 'No more pages to load.',
              img: settings.masonry.loading_image
            },
            bufferPx: 150
          },
          // trigger Masonry as a callback
          function(selector) {
            $('.fade-white').mosaic();
            $('.collectible_grid_view').mosaic({
              animation: 'slide'
            });
            $(".mosaic-overlay a.target").bigTarget({
              hoverClass: 'over',
              clickZone : 'div:eq(1)'
            });

            // hide new bricks while they are loading
            var $bricks = $(selector).css({ opacity: 0 });

            // ensure that images load before adding to masonry layout
            $bricks.imagesLoaded(function() {
              // show bricks now that they're ready
              $bricks.animate({ opacity: 1 });
              $container.masonry('appended', $bricks, true);
            });
          });

        // Hide the pagination before infinite scroll does it
        $('#search-pagination').hide();
      } // if settings masonry add_infinite_scroll
    } // setupMasonry()

  }; // SEARCH object literal
}());



})(this, this.document, jQuery);
