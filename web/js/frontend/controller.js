// frontend/controller.js

(function (window, document, $, undefined) {
  "use strict";

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
  // We init it manually from Modernizr.load (yepnope)
  // $( document ).ready( CONTROLLER.init );

  // provide a global noop function
  window.noop = function() {};

})(this, this.document, jQuery);