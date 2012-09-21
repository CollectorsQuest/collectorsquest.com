/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 *
 * Requires: 1.2.2+
 */
(function($){

  var mwheelI = {
      pos: [-260, -260]
    },
    minDif 	= 3,
    doc 	= document,
    root 	= doc.documentElement,
    body 	= doc.body,
    longDelay, shortDelay
    ;

  function unsetPos(){
    if(this === mwheelI.elem){
      mwheelI.pos = [-260, -260];
      mwheelI.elem = false;
      minDif = 3;
    }
  }

  $.event.special.mwheelIntent = {
    setup: function(){
      var jElm = $(this).bind('mousewheel', $.event.special.mwheelIntent.handler);
      if( this !== doc && this !== root && this !== body ){
        jElm.bind('mouseleave', unsetPos);
      }
      jElm = null;
      return true;
    },
    teardown: function(){
      $(this)
        .unbind('mousewheel', $.event.special.mwheelIntent.handler)
        .unbind('mouseleave', unsetPos)
      ;
      return true;
    },
    handler: function(e, d){
      var pos = [e.clientX, e.clientY];
      if( this === mwheelI.elem || Math.abs(mwheelI.pos[0] - pos[0]) > minDif || Math.abs(mwheelI.pos[1] - pos[1]) > minDif ){
        mwheelI.elem = this;
        mwheelI.pos = pos;
        minDif = 250;

        clearTimeout(shortDelay);
        shortDelay = setTimeout(function(){
          minDif = 10;
        }, 200);
        clearTimeout(longDelay);
        longDelay = setTimeout(function(){
          minDif = 3;
        }, 1500);
        e = $.extend({}, e, {type: 'mwheelIntent'});
        return $.event.handle.apply(this, arguments);
      }
    }
  };
  $.fn.extend({
    mwheelIntent: function(fn) {
      return fn ? this.bind("mwheelIntent", fn) : this.trigger("mwheelIntent");
    },

    unmwheelIntent: function(fn) {
      return this.unbind("mwheelIntent", fn);
    }
  });

  $(function(){
    body = doc.body;
    //assume that document is always scrollable, doesn't hurt if not
    $(doc).bind('mwheelIntent.mwheelIntentDefault', $.noop);
  });
})(jQuery);


(function($) {

  var types = ['DOMMouseScroll', 'mousewheel'];

  if ($.event.fixHooks) {
    for ( var i=types.length; i; ) {
      $.event.fixHooks[ types[--i] ] = $.event.mouseHooks;
    }
  }

  $.event.special.mousewheel = {
    setup: function() {
      if ( this.addEventListener ) {
        for ( var i=types.length; i; ) {
          this.addEventListener( types[--i], handler, false );
        }
      } else {
        this.onmousewheel = handler;
      }
    },

    teardown: function() {
      if ( this.removeEventListener ) {
        for ( var i=types.length; i; ) {
          this.removeEventListener( types[--i], handler, false );
        }
      } else {
        this.onmousewheel = null;
      }
    }
  };

  $.fn.extend({
    mousewheel: function(fn) {
      return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
    },

    unmousewheel: function(fn) {
      return this.unbind("mousewheel", fn);
    }
  });


  function handler(event) {
    var orgEvent = event || window.event, args = [].slice.call( arguments, 1 ), delta = 0, returnValue = true, deltaX = 0, deltaY = 0;
    event = $.event.fix(orgEvent);
    event.type = "mousewheel";

    // Old school scrollwheel delta
    if ( orgEvent.wheelDelta ) { delta = orgEvent.wheelDelta/120; }
    if ( orgEvent.detail     ) { delta = -orgEvent.detail/3; }

    // New school multidimensional scroll (touchpads) deltas
    deltaY = delta;

    // Gecko
    if ( orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS ) {
      deltaY = 0;
      deltaX = -1*delta;
    }

    // Webkit
    if ( orgEvent.wheelDeltaY !== undefined ) { deltaY = orgEvent.wheelDeltaY/120; }
    if ( orgEvent.wheelDeltaX !== undefined ) { deltaX = -1*orgEvent.wheelDeltaX/120; }

    // Add event and delta to the front of the arguments
    args.unshift(event, delta, deltaX, deltaY);

    return ($.event.dispatch || $.event.handle).apply(this, args);
  }

})(jQuery);
