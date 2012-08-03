<?php

require 'lib/model/om/BaseContentCategoryPeer.php';

class ContentCategoryPeer extends BaseContentCategoryPeer
{

  // Category names that should always got to top among their ancestors
  public static $force_order_to_top = array(
      'Uncategorized',
  );

  // Category names that should always got to bottom among their ancestors
  public static $force_order_to_bottom = array(
      'Other',
  );

}
