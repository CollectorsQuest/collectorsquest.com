<?php

/**
 * Payment options not directly related to the model class
 */
class wpPostPayment
{

  public static $tiers = array(
      // between 0 and 450 words = 10$
      10 => array('min' => 0),
      // between 450 and inf = 25$
      25 => array('min' => 450),
  );


  /**
   * Get the price of the post based on lenght
   *
   * @param       wpPost $wpPost
   * @return      integer
   */
  public static function getUSDForPost(wpPost $wpPost)
  {
    return self::getUSDForWordCount($wpPost->countPostContentWords());
  }


  /**
   * Get the price for a specific word count
   *
   * @param       integer $count
   * @return      integer
   */
  public static function getUSDForWordCount($count)
  {
    // sort from highest to lowest tier
    arsort(self::$tiers);

    foreach (self::$tiers as $usd => $tier)
    {
      if ($count >= $tier['min']) {
        return $usd;
      }
    }

    return 0;
  }

}