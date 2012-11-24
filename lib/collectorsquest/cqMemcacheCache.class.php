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
        'host' => 'cache.e0sqlk.0001.use1.cache.amazonaws.com'
      ),
      $options
    );

    $this->initialize($options);
  }
}
