
if (window._ENV === 'prod')
{
  var _page_load_start = new Date();
  var _server_load_time = 0;
  var _gaq = _gaq || [];

  window.onload = function()
  {
    var _page_load_end = new Date();
    var _page_load_time = _page_load_end.getTime() - _page_load_start.getTime();

    if (_server_load_time > 0)
    {
      _server_load_time = parseInt(_server_load_time / 100) * 100;
      _gaq.push(['_setCustomVar', 1, 'serverLoadTime', _server_load_time, 3]);
    }

    _gaq.push(
      ['_setCustomVar', 2, 'pageLoadTime', parseInt(_page_load_time / 100) * 100, 3],
      ['_setAccount', 'UA-669177-1'],
      ['_trackPageview']
    );
  };

  if (top.location != self.location)
  {
    top.location.replace(self.location.toString());
  }
}

// name should be "Width" or "Height"
function getWindowSize(name)
{
  var docElemProp = window.document.documentElement[ "client" + name ],
    body = window.document.body;

  return window.document.compatMode === "CSS1Compat" && docElemProp ||
    body && body[ "client" + name ] || docElemProp;
}

// Modified script based on this function:
document.cookie = 'resolution=' + Math.max(getWindowSize("Width"), getWindowSize("Height")) + '; path=/';
