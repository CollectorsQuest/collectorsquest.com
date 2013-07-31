<?php

class ReordersWpPostMetaItems
{

  const FEATURED_ITEMS_META_KEY = '_featured_items';

  const FEATURED_ITEMS_CQ_COLLECTIBLE_IDS = 'cq_collectible_ids';
  const FEATURED_ITEMS_CQ_HOMEPAGE_COLLECTIBLE_IDS = 'cq_homepage_collectible_ids';

  public static $collectibles_array_keys = array(
      self::FEATURED_ITEMS_CQ_COLLECTIBLE_IDS,
      self::FEATURED_ITEMS_CQ_HOMEPAGE_COLLECTIBLE_IDS
  );

  /**
   * Reorders the '_featured_items' post meta
   *
   * @param     wpPost $post
   * @param     string|array $order
   * @return    integer Was the post meta updated
   */
  public static function reorderFeaturedItems(wpPost $post, $order)
  {
    $featured_items = $post->getPostMetaValue(self::FEATURED_ITEMS_META_KEY);

    foreach (static::$collectibles_array_keys as $collectibles_array_key)
    {
      if (isset($featured_items[$collectibles_array_key]))
      {
        $featured_items[$collectibles_array_key] = static::getReorderedFeaturedItems(
          $featured_items[$collectibles_array_key],
          $order
        );
      }
    }

    return $post->setPostMetaValue(
      self::FEATURED_ITEMS_META_KEY,
      $featured_items
    );
  }

  /**
   * Reorders WP featured items meta value. Handles items in the reorder array not
   * being present in the input and the ID:SIZE notation used for masonry
   *
   * @param     string|array $input
   * @param     string|array $order
   * @return    string
   */
  public static function getReorderedFeaturedItems($input, $order)
  {
    if (!is_array($input))
    {
      $input = cqFunctions::explode(',', $input);
    }

    if (!is_array($order))
    {
      $order = cqFunctions::explode(',', $order);
    }

    $normalized_input = static::normalizeFeaturedItemsArray($input);
    $output = array();
    foreach ($order as $result_key => $input_key)
    {
      $output[$result_key] = $normalized_input[$input_key];
      unset($normalized_input[$input_key]);
    }

    // handle items not present in the order input
    $output = array_merge($output, $normalized_input);

    return implode(', ', $output);
  }


  /**
   * Gets an array of featured item ids and converts it to the following type:
   *
   * from:
   *   ['1:1x2', '2:1x2', '3']
   * to
   *  [
   *     1 => '1:1x2',
   *     2 => '2:1x2',
   *     3 => '3'
   *  ]
   *
   * @param     array $input
   * @return    array
   */
  public static function normalizeFeaturedItemsArray(array $input)
  {
    $output = array();
    foreach($input as $input_val)
    {
      if (false !== strpos($input_val, ':'))
      {
        $exploded = explode(':', $input_val);
        $key = $exploded[0];
      }
      else
      {
        $key = $input_val;
      }

      $output[$key] = $input_val;
    }

    return $output;
  }
}
