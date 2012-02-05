<?php

class VideoPlaylist extends BaseVideoPlaylist
{
  public function __toString()
  {
    return $this->getName();
  }
}
