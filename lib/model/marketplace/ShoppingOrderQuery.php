<?php

require 'lib/model/marketplace/om/BaseShoppingOrderQuery.php';

class ShoppingOrderQuery extends BaseShoppingOrderQuery
{
  public function search($q)
  {
    return $this;
  }
}
