<?php

class cqTimer extends sfTimer
{
  /**
   * @var cqTimer|null
   */
  private static $_timer = null;

  /**
   * @static
   * @return cqTimer
   */
  public static function getInstance()
  {
    if (self::$_timer === null)
    {
      self::$_timer = new self();
    }

    return self::$_timer;
  }
}
