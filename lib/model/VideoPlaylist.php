<?php

require 'lib/model/om/BaseVideoPlaylist.php';

class VideoPlaylist extends BaseVideoPlaylist
{
  public function __toString()
  {
    return (string) $this->getPlaylist();
  }
}
