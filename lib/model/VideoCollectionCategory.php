<?php

require 'lib/model/om/BaseVideoCollectionCategory.php';

class VideoCollectionCategory extends BaseVideoCollectionCategory
{
  public function __toString()
  {
    return (string) $this->getCollectionCategory();
  }
}
