<?php

class VideoCollectionCategory extends BaseVideoCollectionCategory
{
  public function __toString()
  {
    return $this->getName();
  }
}
