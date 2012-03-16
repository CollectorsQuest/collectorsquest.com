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
  common:     {
    init:   function() {
      COMMON.notImplemented();
      // empty :)
    }
  } // common

}; // APP


var COMMON =  window.COMMON = (function(){
  "use strict";

  function cq_not_implemented_yet()
  {
    console.log('We are inside a private function returned as object literal executed by common controller. wo-hoo!');

    return true;
  }

  // return object literal
  return {
    notImplemented: cq_not_implemented_yet
  }
}());



})(this, this.document, jQuery);
