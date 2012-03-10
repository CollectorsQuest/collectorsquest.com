
  <script type="text/javascript">
    var page_load_start = new Date();
    var server_load_time = 0;
    var _gaq = _gaq || [];

    window.onload = function()
    {
      var page_load_end = new Date();
      var page_load_time = page_load_end.getTime() - page_load_start.getTime();

      if (server_load_time > 0)
      {
        server_load_time = parseInt(server_load_time / 100) * 100;
        _gaq.push(['_setCustomVar', 1, 'serverLoadTime', server_load_time, 3]);
      }

      _gaq.push(
        ['_setCustomVar', 2, 'pageLoadTime', parseInt(page_load_time / 100) * 100, 3],
        ['_setAccount', 'UA-669177-1'],
        ['_trackPageview']
      );
    };
  </script>
