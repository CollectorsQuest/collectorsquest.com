<?php

class cqConditionalCacheFilter extends sfFilter
{
  public function execute($filterChain)
  {
    /* @var $context cqContext */
    $context = $this->getContext();

    /**
     * We want to turn off cache
     */
    if ($context->getUser()->isAdmin())
    {
      sfConfig::set('sf_cache', false);
      $context->set('viewCacheManager', null);
    }

    // Execute next filter
    $filterChain->execute();
  }
}
