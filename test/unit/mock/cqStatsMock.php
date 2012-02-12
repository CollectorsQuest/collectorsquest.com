<?php

/**
 * Mock cqStats class, expand as needed
 */
class cqStats
{
  protected static $stats;

  public static function increment($stat, $rate = 1)
  {
    self::$stats[$stat] = isset(self::$stats[$stat])
      ? self::$paths[$path] + $rate
      : $rate;
  }

  public static function hasStat($stat)
  {
    return isset(self::$stats[$stat]);
  }

  public static function getStat($stat)
  {
    return isset(self::$stats[$stat])
      ? self::$stats[$stat]
      : null;
  }

}
