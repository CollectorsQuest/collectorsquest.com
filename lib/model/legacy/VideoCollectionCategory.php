<?php

require 'lib/model/legacy/om/BaseVideoCollectionCategory.php';

class VideoCollectionCategory extends BaseVideoCollectionCategory
{
  public function __toString()
  {
    return (string) $this->getCollectionCategory();
  }
}
