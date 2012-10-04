<?php

require 'lib/model/om/BaseContentCategoryPeer.php';

class ContentCategoryPeer extends BaseContentCategoryPeer
{

  const PROPERTY_SEO_COLLECTIONS_TITLE_PREFIX = 'SEO_COLLECTIONS_TITLE_PREFIX';
  const PROPERTY_SEO_COLLECTIONS_TITLE_SUFFIX = 'SEO_COLLECTIONS_TITLE_SUFFIX';
  const PROPERTY_SEO_COLLECTIONS_USE_SINGULAR = 'SEO_COLLECTIONS_USE_SINGULAR';

  const PROPERTY_SEO_MARKET_TITLE_PREFIX = 'SEO_MARKET_TITLE_PREFIX';
  const PROPERTY_SEO_MARKET_TITLE_SUFFIX = 'SEO_MARKET_TITLE_SUFFIX';
  const PROPERTY_SEO_MARKET_USE_SINGULAR = 'SEO_MARKET_USE_SINGULAR';

  // Category names that should always got to top among their ancestors
  public static $force_order_to_top = array(
      'Uncategorized',
  );

  // Category names that should always got to bottom among their ancestors
  public static $force_order_to_bottom = array(
      'Other',
  );

}
