// frontend/application.js

(function (window, document, $, undefined) {
"use strict";

var APP = window.APP = {

  /**
   * Defaults to be used throughout the project
   */
  defaults:   {

    tooltip: {
      position: 'bottom'
    }
  }, // defaults

  /**
   * Common module, executed for every page
   */
  common: {
    init: function() {
      $(".alert").alert();
      $('.dropdown-toggle').dropdown();
      $('.fade-white').mosaic();
      $("[rel=tooltip]").tooltip({
        placement: function(template, el) {
            var $el = $(el),
                settings = $.extend(true, {}, APP.defaults, window.cq.settings);

            if ($el.hasClass('tooltip-position-right')) {
              return 'right';
            } else if ($el.hasClass('tooltip-position-left')) {
              return 'left';
            } else if ($el.hasClass('tooltip-position-top')) {
              return 'top';
            } else if ($el.hasClass('tooltip-position-bottom')) {
              return 'bottom';
            }

            return settings.tooltip.position;
          }
      });

      $("a.target").bigTarget({
        hoverClass: 'over',
        clickZone: '.link'
      });

      window.locale = {
        "fileupload": {
          "errors": {
            "maxFileSize": "File is too big",
            "minFileSize": "File is too small",
            "acceptFileTypes": "Filetype not allowed",
            "maxNumberOfFiles": "Max number of files exceeded",
            "uploadedBytes": "Uploaded bytes exceed file size",
            "emptyResult": "Empty file upload result"
          },
          "error": "Error",
          "start": "Start",
          "cancel": "Cancel",
          "destroy": "Delete"
        }
      };

      $('img.lazy').jail();

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
   * "collection" symfony module
   */
  collection: {
    // collection/collectible action
    collectible: function() {
      // Check if we have collectionColletiblesWidget before depending on it
      if (window.cq.settings.collectionColletiblesWidget)
      {
        $('#collectionCollectiblesWidget').collectionCollectiblesCarousel({
          collection_id: window.cq.settings.collectionColletiblesWidget.collection_id
        });
      }
    }
  },

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
    inbox: function() {
      $('.private-messages-list-select .dropdown-toggle').dropdown();
      $('.private-messages-list-select .dropdown-menu a').on('click', function(e) {
        var $checkboxes = $('.private-messages-list input[type=checkbox]');

        switch ($(this).data('select')) {
          case 'all':
            $checkboxes.attr('checked', 'checked');
            shouldBatchActionsBeEnabled();
            break;
          case 'none':
            $checkboxes.attr('checked', false);
            shouldBatchActionsBeEnabled();
            break;
          case 'read':
            $checkboxes.attr('checked', false).filter('.read').attr('checked', 'checked');
            shouldBatchActionsBeEnabled();
            break;
          case 'unread':
            $checkboxes.attr('checked', false).filter('.unread').attr('checked', 'checked');
            shouldBatchActionsBeEnabled();
            break;
        }
      });
      var $action_buttons = $('.private-messages-list-actions input')
                            .addClass('disabled').attr('disabled', 'disabled');
      var $messages_inbox = $('#private-messages-inbox');

      var shouldBatchActionsBeEnabled = function() {
        if ($messages_inbox.find('input:checked').length) {
          $action_buttons.removeClass('disabled').removeAttr('disabled');
        } else {
          $action_buttons.addClass('disabled').attr('disabled', 'disabled');
        }
      };
      $messages_inbox.on('change', 'input', shouldBatchActionsBeEnabled);
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
      /**
       * @see: https://basecamp.com/1759305/projects/824949-collectorsquest-com/todos/11926034-when-going-from-page
       */
      if (0 === $(window).scrollTop()) {
        // scroll the window only if the user hasn't done it already
        $.scrollTo('#slot1', { offset: -10, duration: 500, easing: 'easeOutExpo' });
      }

      /**
       * When the jquery-controls plugin is added to a website,
       * a.open-dialog links will open a dialog which shows in page or ajax content.
       *
       * @see: https://github.com/Nikku/jquery-bootstrap-scripting/
       */
      $(document).controls();

      // If a a.auto-close is contained in the dialogs content,
      // the dialog will automatically redirect to a new page
      $(document).delegate(".modal", "dialog2.content-update", function()
      {
        var $autoclose = $(this).find("a.auto-close");

        if ($autoclose.length > 0)
        {
          $autoclose.blur();

          var href = $autoclose.attr('href');
          if (href) {
            window.location.href = href;
          }
        }
      });

      $('#main').bind('drop dragover', function (e) {
        e.preventDefault();
      });

      $(document).bind('dragover', function (e)
      {
        var dropZone = $('#dropzone'),
          timeout = window.dropZoneTimeout;
        if (!timeout) {
          dropZone.addClass('in');
        } else {
          clearTimeout(timeout);
        }
        if (e.target === dropZone[0]) {
          dropZone.addClass('hover');
        } else {
          dropZone.removeClass('hover');
        }
        window.dropZoneTimeout = setTimeout(function () {
          window.dropZoneTimeout = null;
          dropZone.removeClass('in hover');
        }, 100);
      });
    },
    collection: function() {
      AVIARY.setup();
    },
    collectible: function() {
      AVIARY.setup();
    },
    profile: function() {
      AVIARY.setup();
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
    var $form = $holder.find('.modal-body .active form');

    $form.on('keypress', 'input', function(event) {
      // if ENTER was pressed
      if (13 === event.which) {
        if (!$form.find('input[type=submit]').length) {
          $form.append('<input type="submit" class="hidden" />');
        }

        $form.find('input[type=submit]').trigger('click');
      }

      return true;
    });

    $holder.find('.modal-footer button').on('click', function() {
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
      COMMON.setupCssHelpers();
      COMMON.setupModalLoginRegistrationDialog();
      COMMON.linkifyTables();
      COMMON.setupComments();
      COMMON.setupFooterLoginOrSignup();
      COMMON.setupScrollToTop();
      COMMON.setupEmailSpellingHelper();
      COMMON.setupLinksModalConfirm();
      COMMON.loginLogoutHelpers();
      if (window.cq.authenticated) {
        COMMON.setupEditable();
      }
    },
    setupCssHelpers: function() {
      // search box extend overline over close-by elements
      $('.sort-search-box').on('focusin focusout', 'input.input-sort-by', function() {
        var $this = $(this);
        $this.siblings('button').toggleClass('blue-outline-t-b-r');
        $this.siblings('.btn-group').find('div').toggleClass('blue-outline-t-b-l');
        $this.siblings('.btn-group').find('a').toggleClass('blue-outline-t-b');
      });
    },
    setupLinksModalConfirm: function() {
      $('a.requires-confirm').on('click', function(e) {
        var $this = $(this);
        e.preventDefault();

        MISC.modalConfirm($this.data('modalTitle'),
          $this.data('modalText'), $this.attr('href'));

        return false;
      });
      $('a.requires-confirm-destructive').on('click', function(e) {
        var $this = $(this);
        e.preventDefault();

        MISC.modalConfirmDestructive($this.data('modalTitle'),
          $this.data('modalText'), $this.attr('href'));

        return false;
      });
    },
    loginLogoutHelpers: function() {
      // set proper logout redirects when included as iframe (only for same domain)
      if (Modernizr.insideiframe && window.parent.location.href) {
        $('a.logout-link').each(function (){
          $(this).attr('href',
            $(this).attr('href') + '?r=' + window.parent.location.href
          );
        });
      }

      // set proper input[name=goto] redirects
      $('input.set-value-to-href').each(function() {
        var $this = $(this);
        if (!$this.val()) {
          if (Modernizr.insideiframe) {
            $(this).val(window.parent.location.href || '');
          } else {
            $(this).val(window.location.href);
          }
        }
      });
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

        // if the modal is not available or we are inside an iframe
        // execute a normal click
        if (!$holder.length || Modernizr.insideiframe) {
          return true;
        }

        if (!window.cq.authenticated) {
          $holder.modal('show');

          if (undefined !== $this.data('loginTitle')) {
            $holder.find('#modal-login-username-pane h3').html($this.data('loginTitle'));
          }

          if (undefined !== $this.data('signupTitle')) {
            $holder.find('#modal-sign-up-pane h3').html($this.data('signupTitle'));
          }

          $holder.find('input:visible').first().focus();
          e.preventDefault();
          return false;
        }

        return true;
      });
    }, // setupModalLoginRegistrationDialog
    setupComments: function() {
      // setup adding a new comment
      var $form_holder = $('.add-comment');

      $form_holder.one('click', 'textarea, button', function(){
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

        return true;
      });

      var $load_more = $('#load-more-comments');

      $load_more.on('click', function() {
        $.get($load_more.data('uri'), {
            token: $load_more.data('token'),
            offset: $load_more.data('offset')
          }, function (data) {
            $('.user-comments').append(data.html);
            if (!data.has_more) {
              $load_more.parent('.see-more-under-image-set').hide();
              $load_more.off('click');
            } else {
              $load_more.data('offset', $load_more.data('offset') + $load_more.data('offset'));
            }
          },
          'json'
        );
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
      if ($.fn.mailcheck) {
        // find input elements of type email
        $('input[type=email]').each(function() {
          var $email_el = $(this),
              $email_el_form = $email_el.parents('form');

          var perform_mailcheck = function($el, $form) {
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
                $suggestion.find('a').data('suggestedAddress', suggestion.full);
                $suggestion.slideDown(200);
                $form.addClass('mailcheck-has-suggestion');
              },
              empty: function() {
                // if the user manually fixes the problem, hide the suggestion
                var $suggestion = $el.siblings('div.email-suggestion');
                $suggestion.hide();
                $form.addClass('mailcheck-has-suggestion');
              }
            });
          };

          // add delegated click event on the suggestion to fill it in the email filed
          $email_el_form.on('click', '.email-suggestion a', function() {
            var $this = $(this);
            $email_el.val($this.data('suggestedAddress'));
            $this.parent('div').hide();
          })
          // add an on submit hook to require the user to click 2 times on the submit
          // button before submitting with an email that may be wrong
          .bindFirst('submit', function() {
            perform_mailcheck($email_el, $email_el_form);

            if ($email_el_form.hasClass('mailcheck-has-suggestion')) {
              if (!$email_el_form.data('mailcheckBlockedFirstSubmit') ) {
                $email_el_form.data('mailcheckBlockedFirstSubmit', true);

                return false;
              } else {
                $email_el_form.data('mailcheckUnblockedSecondSubmit', true);
              }
            }

            return true;
          });

          // setup blur event - check if the email is ok
          $email_el.on('blur', function() {
            perform_mailcheck($email_el, $email_el_form);
          });
        });

        // DUCK-PUNCHING $.fn.showLoading to work with our mailcheck
        if ($.fn.showLoading) {
          (function(){
            var old_showLoading = $.fn.showLoading;
            $.fn.showLoading = function(options) {
              var $form = $(this).find('form.mailcheck-has-suggestion');
              if (!$form.length || $form.data('mailcheckUnblockedSecondSubmit'))
              {
                return old_showLoading.apply(this, arguments);
              }
              else
              {
                return this;
              }
            };
          }());
        }
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
      if (window.cq.cookies && $.cookie(window.cq.cookies.username)) {
        // triger the event to show the login form in the footer
        $('#footer-control-login-button').trigger('click');
        // and set the username to the value of the cookie
        $('#login_username').val($.cookie(window.cq.cookies.username));
      }
    }, // setupFooterLoginOrSignup()

    setupEditable: function ()
    {
      $('.header-bar.editable h1').editable('/ajax/editable',
      {
        indicator: '<img src="/images/loading.gif"/>',
        tooltip: 'Click to edit...',
        cancel: 'Cancel',
        submit: 'Save',
        onedit: function()
        {
          $(this).parent().parent().removeClass('header-bar');
        },
        onreset: function ()
        {
          $(this).parent().parent().parent().addClass('header-bar');
        },
        onsubmit: function ()
        {
          $(this).parent().parent().parent().addClass('header-bar');
        }
      });

      $('.editable_html').editable('/ajax/editable',
      {
        loadurl: '/ajax/editable-load',

        type: 'wysihtml5',
        cancel: 'Cancel',
        submit: 'Save',
        indicator: '<img src="/images/loading.gif"/>',
        tooltip: 'Click to edit...',
        onblur: "ignore",
        width: '100%',
        height: '100px',
        wysihtml5: {
          "font-styles": false,
          "image": false,
          "link": false
        }
      });
    },

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
          settings = $.extend(true, {}, defaults, window.cq.settings);

      $container.imagesLoaded(function() {
        $container.masonry({
          itemSelector : '.brick',
          columnWidth : 196, gutterWidth: 15
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

var AVIARY = window.AVIARY = (function(){

  var aviary_loaded = false;
  var aviary_image_updated = false;
  var aviary_editor;

  // load aviary if not already loaded
  function loadAviary(callback) {
    if (false === aviary_loaded) {
      Modernizr.load({
        load: '//dme0ih8comzn4.cloudfront.net/js/feather.js',
        callback: function() {
          aviary_loaded = true;
          $.isFunction(callback) && callback();
        }
      });
    } else {
      $.isFunction(callback) && callback();
    } // if not aviary_loaded
  } // loadAviary()

  // setup the private aviary_editor variable
  function setupAviary(onLoad) {
    if (undefined === aviary_editor) {
      aviary_editor = new Aviary.Feather($.extend(true, {}, window.cq.settings.aviary, {
        apiVersion: 2,
        tools: 'orientation,crop,text,effects,enhance,brightness,contrast,sharpness,saturation',
        minimumStyling: true,
        onSave: aviaryOnSave,
        onClose: aviaryOnClose,
        onLoad: $.isFunction(onLoad) && onload || window.noop,
        appendTo: ''
      }));
    }

    return aviary_editor;
  }

  // set flag that the image was updated
  function aviaryOnSave(image_id, new_url) {
    aviary_image_updated = true;
  }

  // if the image was updated reload the page, so that a new thumbnail
  // will be created
  function aviaryOnClose()
  {
    if (aviary_image_updated) {
      window.location.reload();
    }
  }

  // return object literal of callable functions
  return {
    setup: function() {
      // async load Aviary and setup the "aviary_editor" variable
      loadAviary(setupAviary);

      $('.multimedia-edit').on('click', function clickclackclock() {
        // make sure we are at the top of the document so the whole editor is visible
        $('html, body').animate({scrollTop:0}, 'medium');

        // if aviary is loaded
        if (undefined !== aviary_editor && AV.feather_loaded) {
          var $this = $(this);
          // launch the image editor
          aviary_editor.launch({
            image: $this.siblings('img')[0],
            postData: $this.data('postData'),
            url: $this.data('originalImageUrl')
            // test image
            // url: 'http://images.aviary.com/imagesv5/feather_default.jpg'
          });
        } else {
          // call this function again until we have the editor available
          setTimeout($.proxy(clickclackclock, this), 100);

          return false;
        }
      });
    } // setup()
  }; // AVIARY public interface object literal

}()); // AVIARY

var MISC = window.MISC = (function(){

  function setupIsDestructiveModal($modal, destructive)
  {
    if (destructive) {
      $modal.find('button.proceed').addClass('btn-danger')
                                   .removeClass('btn-primary');
    } else {
      $modal.find('button.proceed').addClass('btn-primary')
                                   .removeClass('btn-danger');
    }
  }

  function commonModalConfirm(destructive, title, text, target, return_callback) {
    title = title || 'Are you sure?';
    text  = text  || 'Are you sure you wish to proceeed?';
    var $modal = $('#confirmation-modal');
    var callback = $.isFunction(target) && target || function() {
      window.location.href = target;
    };

    if (!$modal.data('modal')) {
      $modal.modal({
        backdrop: true,
        keyboard: true,
        show: false
      });

      $modal.on('click', 'button.cancel', function() {
        $modal.modal('hide');
      });
    }

    function execute(ev) {
      // we need to get the proper context for the callback that is passed to us
      var that = ev && ev.target || this;

      setupIsDestructiveModal($modal, destructive);
      $modal.find('.modal-header h3').html(title);
      $modal.find('.modal-body p').html(text);
      $modal.modal('show');

      $modal.one('click', 'button.proceed', function() {
        $modal.one('hidden', $.proxy(callback, that));
        $modal.modal('hide');

        return true;
      });
    }

    return return_callback && execute || execute();
  }

  return {
    // target should be either a callable or a URL
    // if return callback is truthy the modal display routine will be returned
    modalConfirm: function(title, text, target, return_callback) {
      return commonModalConfirm(false, title, text, target, return_callback);
    },
    modalConfirmDestructive: function(title, text, target, return_callback) {
      return commonModalConfirm(true, title, text, target, return_callback);
    }
  }; // MISC public interface object literal
}()); // MISC


})(this, this.document, jQuery);
