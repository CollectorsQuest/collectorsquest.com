(function (window, document, $, undefined) {
"use strict";

var APP = window.APP = {

  /**
   * Common module, executed for every page
   */
  common: {
    init: function() {
      $('a[rel="popover"]').popover();
      $('a[rel="clickover"]').clickover();

      $("a[target='_blank']", '#sf_admin_content td.sf_admin_text')
        .attr({title: 'Opens in a new window'})
        .append('&nbsp;<i class="icon-external-link">&nbsp;</i>&nbsp;');

      // Adjust table to fit filters if they exist
      if ($('#sf_admin_bar').size()) {
        var filter_width = $('#sf_admin_bar').width() + 25;

        $('.sf_admin_list').css('padding-right', filter_width);

        // Add filter header
        $('table tbody', '#sf_admin_bar').before("<thead><tr><th colspan='2'>Filters</th></tr></thead>");
      }

      $('li.node').hover(
        function() {
          $('ul', this).css('display', 'block');
          $(this).addClass('nodehover');
        },
        function() {
          $('ul', this).css('display', 'none');
          $(this).removeClass('nodehover');
        }
      );

      $('li.node a[href=#]').click(function() {
        return false;
      });
    } // init
  }, // common

  organization: {
    init: function() {
      var $organization_type = $('#organization_type'),
          $organization_type_other = $('#organization_type_other');

      var disableOneTypeField = function() {
        // initially enable both type fields
        $organization_type.removeAttr('disabled');
        $organization_type_other.removeAttr('disabled');

        // and then disable the empty one
        if ($organization_type.val()) {
          $organization_type_other.attr('disabled', 'disabled');
        } else if ($organization_type_other.val()) {
          $organization_type.attr('disabled', 'disabled');
        }
      };

      // when the value of a type field changes reevaluate which one should be disabled
      $('#organization_type, #organization_type_other').on('change', disableOneTypeField);

      // run the disable type field function at page load
      disableOneTypeField();
    } // edit
  } // organization

} // APP


// // The Gaber-Irish Markup based unobstructive DOM-ready execution
// http://www.viget.com/inspire/extending-paul-irishs-comprehensive-dom-ready-execution/
var CONTROLLER  = window.CONTROLLER = {
  exec: function( controller, action, report_action ) {
    var ns = window.APP,
        action = ( action === undefined ) ? "init" : action
        report_action = ( report_action === undefined) ? '' : report_action;

    if ( controller !== "" && ns[controller] && typeof ns[controller][action] === "function" ) {
      ns[controller][action](report_action);
    }
  },

  init: function() {
    var body = document.body,
        controller = body.getAttribute( "data-controller" ),
        action = body.getAttribute( "data-action" );

    CONTROLLER.exec( "common" );
    CONTROLLER.exec( controller, "init", action);
    CONTROLLER.exec( controller, action );

    CONTROLLER.controller = controller;
    CONTROLLER.action = action;
  }
};

// Init the controller on DOM ready
$( document ).ready( CONTROLLER.init );

// provide a global noop function
window.noop = function() {};



})(this, this.document, jQuery);

