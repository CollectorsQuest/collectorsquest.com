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
      $('.dropdown-toggle').dropdown();
      $('.fade-white').mosaic();

      $("a.target").bigTarget({
        hoverClass: 'over',
        clickZone: 'div.link'
      });

      COMMON.setupProjectWideHelpers();
    } // init
  }, // common

  /**
   * "general" symfony module
   */
  general: {
    // general/index is the homepage action
    index: function() {
      GENERAL.setupCarousel();
    }
  }, // general

  /**
   * "search" symfony module
   */
  search: {
    index: function() {
      SEARCH.setupMasonry();
    }
  }, // search

  /**
   * "messages" symfony module
   */
  messages: {
    init: function(action) {
      $('.nav-private-messages #' + action).addClass('active');
    },
    inbox: function() {
      $('.private-messages-list-select .dropdown-toggle').dropdown();
      $('.private-messages-list-select .dropdown-menu a').on('click', function(e) {
        var $checkboxes = $('.private-messages-list input[type=checkbox]');

        switch ($(this).data('select')) {
          case 'all':
            $checkboxes.attr('checked', 'checked');
            break;
          case 'none':
            $checkboxes.attr('checked', false);
            break;
          case 'read':
            $checkboxes.attr('checked', false).filter('.read').attr('checked', 'checked');
            break;
          case 'unread':
            $checkboxes.attr('checked', false).filter('.unread').attr('checked', 'checked');
            break;
        }
      });
    },
    show: function() {
      $('#message_body').elastic();
    },
    compose: function() {
      $('#message_body').elastic();
    }
  }, // messages

  /**
   * "mycq" symfony module
   */
  mycq: {
    init: function()
    {
      $(document).controls();

      // If a a.auto-close is contained in the dialogs content,
      // the dialog will automatically redirect to a new page
      $(document).delegate(".modal", "dialog2.content-update", function()
      {
        // got the dialog as this object. Do something with it!
        var e = $(this);
        var autoclose = e.find("a.auto-close");

        if (autoclose.length > 0)
        {
          var href = autoclose.attr('href');
          if (href) {
            window.location.href = href;
          }
        }
      });
    },
    collections: function() {

    }
  } // mycq

}; // APP

var COMMON = window.COMMON = (function(){

  /**
   * Handle footer panes for login modal tabs
   */
  function setupModalLoginRegistrationDialogFooterTabs($holder) {
    $holder.find('ul.nav').on('click', 'a', function() {
      // hide the currently active pane
      $holder.find('.modal-footer .tab-pane.active').removeClass('active');
      // add active class to pane with the same name as the modal body target,
      // only with the ending "pane" replaced by "foter", like:
      // modal-login-username-pane -> modal-login-openid-footer
      $($(this).attr('href').replace(/\bpane$/, 'footer')).addClass('active');

      return true;
    });
  }

  /**
   * We use a <button> element outside the <form> and need to manually trigger
   * the submit event. Additionally, the html5 forms validation api
   * will not function if the submit event is triggered directly on the form,
   * so we append a <input type=submit /> element and trigger "click" on it
   */
  function setupModalLoginRegistrationDialogFormSubmission($holder) {
    $holder.find('.modal-footer button').on('click', function() {
      var $form = $holder.find('.modal-body .active form');

      if (!Modernizr.html5formvalidation) {
        $form.trigger('submit');
        return true;
      }

      if (!$form.find('input[type=submit]').length) {
        $form.append('<input type="submit" class="hidden" />');
      }

      $form.find('input[type=submit]').trigger('click');
      return true;
    });
  }

  // return object literal
  return {
    setupProjectWideHelpers: function() {
      COMMON.setupModalLoginRegistrationDialog();
      COMMON.linkifyTables();
      COMMON.setupComments();
      COMMON.setupFooterLoginOrSignup();
      COMMON.setupScrollToTop();
      COMMON.setupEmailSpellingHelper();
    },
    setupModalLoginRegistrationDialog: function() {
      var $holder = $('#modal-login-holder');

      setupModalLoginRegistrationDialogFooterTabs($holder);
      setupModalLoginRegistrationDialogFormSubmission($holder);

      $('.requires-login').on('click', function(e) {
        var $this = $(this);
        // execute the modal JS if not already executed
        if (undefined === $holder.data('modal')) {
          $holder.modal({
            backdrop: true,
            keyboard: true,
            show: false
          });
        }

        if (!window.cq.authenticated) {
          $holder.modal('show');

          if (undefined !== $this.data('login-title')) {
            $holder.find('#modal-login-username-pane h3').html($this.data('login-title'));
          }

          if (undefined !== $this.data('signup-title')) {
            $holder.find('#modal-sign-up-pane h3').html($this.data('signup-title'));
          }

          e.preventDefault();
          return false;
        }

        return true;
      });
    }, // setupModalLoginRegistrationDialog
    setupComments: function() {
      // setup adding a new comment
      var $form_holder = $('.add-comment');

      $form_holder.on('click', 'textarea, button', function(){
        var $extra_fields = $form_holder.find('.extra-fields.non-optional');

        if (!window.cq.authenticated) {
          var $extra_fields_not_auth = $form_holder.find('.extra-fields.not-authenticated');
          $extra_fields_not_auth.find('input').attr('required', 'required');
          $extra_fields = $extra_fields.add($extra_fields_not_auth);
        }

        $extra_fields.slideDown(200);
        $form_holder.find('button, textarea').addClass('expand');

        // type property cannot be changed, but we want a normal looking button
        // initially that behaves as type=button, so we use 2 elements and switch
        // the type=button for a type=submit one
        $form_holder.find('button.fake').hide();
        $form_holder.find('button.hidden').removeClass('hidden');

        // we want to execute this click handler only once, so we unbind it here
        $form_holder.off('click');

        return true;
      });

      var $load_more = $('#load-more-comments');

      $load_more.on('click', function() {
        $.get($load_more.data('uri'), {
            token: $load_more.data('token'),
            offset: $load_more.data('offset')
          }, function (data) {
            console.log(data);
            $('.user-comments').append(data.html);
            if (!data.has_more) {
              $load_more.parent('.see-more-under-image-set').hide();
              $load_more.off('click');
            } else {
              $load_more.data('offset', $load_more.data('offset') + $load_more.data('offset'));
            }
          },
          'json'
        )
      });
    },
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
    }, // setupFooterLoginOrSignup()

    linkifyTables: function() {
      // make all table rows with class "linkify" clickable links ;)
      var $link_table_rows = $('table').find('tr.linkify');

      $link_table_rows.find('td:not(.dont-linkify)').on('click', function() {
          window.location = $(this).parent().data('url');
      });
    } // linkifyTables

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
          $roundaboutEl.fadeTo(1000, 1);
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
              msgText: (settings.masonry.loading_text) ? settings.masonry.loading_text : 'Loading...',
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
            var $bricks = $(selector).css({opacity: 0});

            // ensure that images load before adding to masonry layout
            $bricks.imagesLoaded(function() {
              // show bricks now that they're ready
              $bricks.animate({opacity: 1});
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
