<?php

require 'lib/model/legacy/om/BaseVideoPlaylist.php';

class VideoPlaylist extends BaseVideoPlaylist
{
  public function __toString()
  {
    return (string) $this->getPlaylist();
  }
}
