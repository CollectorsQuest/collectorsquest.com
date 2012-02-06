var player = null;
function playerReady(thePlayer)
{
  player = window.document[thePlayer.id];
  player.addControllerListener('LOAD', 'playlistItemMonitor');
  player.addControllerListener('ITEM', 'playlistItemMonitor');
}

function playItem(i)
{
  player.sendEvent('ITEM', parseInt(i));
}

function playlistItemMonitor(obj)
{
  var playlist = player.getPlaylist();

  document.getElementById("video-title").innerHTML = playlist[obj['index']]['title'];
  document.getElementById("video-description").innerHTML = playlist[obj['index']]['description'];
}

function createPlayer(playlist)
{
  var flashvars =
  {
    playlistfile: playlist,
    plugins: 'viral-2',
    autostart: "true"
  }

  var params =
  {
    allowfullscreen: "true",
    allowscriptaccess: "always"
  }

  var attributes =
  {
    id: "mpl",
    name: "mpl"
  }

  swfobject.embedSWF("/swf/mediaplayer.swf", "mediaspace", "480", "385", "9.0.115", false, flashvars, params, attributes);
}
