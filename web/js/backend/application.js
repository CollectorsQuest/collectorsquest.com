(function($) {
  "use strict";

  $(document).ready(function()
  {
    $('a[rel="popover"]').popover();
    $('a[rel="clickover"]').clickover();

    $("a[target='_blank']", '#sf_admin_content td.sf_admin_text')
      .attr({title: 'Opens in a new window'})
      .append('&nbsp;<i class="icon-external-link">&nbsp;</i>&nbsp;');
  });

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

})(window.jQuery);
