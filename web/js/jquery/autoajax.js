//
//  This is a simple plugin which converts normal links into
// AJAX GET requests
//
// Steve Kemp
// --
// http://www.steve.org.uk/jquery/
//

//
// create closure
//
(function ($) {
  "use strict";

  //
  // plugin definition
  //
  $.fn.autoajax = function (options) {

    // build main options before element iteration
    var opts = $.extend({}, $.fn.autoajax.defaults, options);

    // iterate and add the click handler to each matched element
    return this.each(function ()
    {
      $(this).click(function ()
      {
        if (opts.onstart) {
          opts.onstart();
        }

        var loc = this.href;
        var frag = loc.indexOf('#');
        if (frag > 0) {
          // The URL we're loading
          var url = loc.substring(0, frag);

          // The div we're to load into, prefixed by "#".
          var div = loc.substring(frag);

          // The URL we're requesting.
          opts.url = url;

          //
          // On success we write the data to the div, unless
          // the user over-rode that.
          //
          if (!opts.success) {
            opts.success = function (data) {
              $(div).html(data);
              if (opts.oncomplete) {
                opts.oncomplete();
              }
            };
          }
          $.ajax(opts);
        }

        return false;
      });
    });
  };

  //
  // plugin defaults: None by default.
  //
  $.fn.autoajax.defaults = {};

  //
  // end of closure
  //
})(jQuery);
