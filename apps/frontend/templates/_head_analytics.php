
  <script type="text/javascript">
    //<![CDATA[
    var page_load_start = new Date();
    var _gaq = _gaq || [];

    window.onload = function()
    {
      var page_load_end = new Date();
      var page_load_time = page_load_end.getTime() - page_load_start.getTime();

      if (typeof(server_load_time) !== 'undefined')
      {
        _gaq.push(['_setCustomVar', 1, 'serverLoadTime', server_load_time, 3]);
      }

      _gaq.push(
        ['_setCustomVar', 2, 'pageLoadTime', parseInt(page_load_time / 100) * 100, 3],
        ['_setAccount', 'UA-669177-1'],
        ['_trackPageview']
      );
    };
    //]]>
  </script>
