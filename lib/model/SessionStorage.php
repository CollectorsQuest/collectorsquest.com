<?php

require 'lib/model/om/BaseSessionStorage.php';

class SessionStorage extends BaseSessionStorage
{
  public function __toString()
  {
    return (string) $this->getId();
  }
}
