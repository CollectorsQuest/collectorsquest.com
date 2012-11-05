<?php

class cqViewCacheManager extends IceViewCacheManager
{
  /**
   * Computes the cache key based on the passed parameters.
   *
   * @param     array  $parameters  An array of parameters
   * @return    string
   */
  public function computeCacheKey(array $parameters)
  {
    if (isset($parameters['sf_cache_key']))
    {
      return $parameters['sf_cache_key'];
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $this->dispatcher->notify(new sfEvent($this, 'application.log', array('Generate cache key')));
    }

    // We want to optimize the cache key for BaseObjects with primary keys
    foreach ($parameters as $i => $parameter)
    {
      if ($parameter instanceof BaseObject && method_exists($parameter, 'getPrimaryKey'))
      {
        /* @var $parameter BaseObject */
        $parameters[$i] = $parameter->getPrimaryKey();
      }
    }

    // Sort the parameters
    ksort($parameters);

    return md5(serialize($parameters));
  }

  public function generateCacheKey($internalUri, $hostName = '', $vary = '', $contextualPrefix = '')
  {
    $internalUri .= strpos($internalUri, '?') !== false ? '&authenticated=' : '?authenticated=';
    $internalUri .= sfContext::getInstance()->getUser()->isAuthenticated() ? 'yes' : 'no';

    return parent::generateCacheKey($internalUri, $hostName, $vary, $contextualPrefix);
  }
}
