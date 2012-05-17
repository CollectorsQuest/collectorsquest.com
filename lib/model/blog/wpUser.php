<?php

require 'lib/model/blog/om/BasewpUser.php';

class wpUser extends BasewpUser
{
  /**
   * @todo: To implement
   *
   * @param string $type
   * @return array|string
   */
  public function getTags($type = 'string')
  {
    $tags = array();

    return ($type == 'array') ? $tags : implode(', ', $tags);
  }
}
