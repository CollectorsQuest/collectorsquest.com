<?php

class cqMemcacheCache extends IceMemcacheCache
{
  /** @var  $memcache  Memcache */

  /**
   * @param  array  $options
   */
  public function __construct($options = array())
  {
    $options = array_merge(
      array(
        'prefix' => 'collectorsquest',
        'host' => 'localhost'
      ),
      $options
    );

    $this->initialize($options);
  }
}
