<?php

class cqViewCacheManager extends IceViewCacheManager
{
  public function generateCacheKey($internalUri, $hostName = '', $vary = '', $contextualPrefix = '')
  {
    $internalUri .= strpos($internalUri, '?') !== false ? '&authenticated=' : '?authenticated=';
    $internalUri .= sfContext::getInstance()->getUser()->isAuthenticated() ? 'yes' : 'no';

    return parent::generateCacheKey($internalUri, $hostName, $vary, $contextualPrefix);
  }
}
