<?php

/**
 * Setting a "uuid" cookie for identifying the user
 */
class cqUuidFilter extends sfFilter
{

  /**
   * @param  sfFilterChain  $filterChain
   * @return void
   */
  public function execute($filterChain)
  {
    /* @var $request sfWebRequest */
    $request = $this->getContext()->getRequest();

    /* @var $response sfWebResponse */
    $response = $this->getContext()->getResponse();

    if (!$uuid = $request->getCookie('cq_uuid'))
    {
      $uuid = cqStatic::getUniqueId(32);
      $response->setCookie('cq_uuid', $uuid, strtotime('+1 year'), '/', '.'. sfConfig::get('app_domain_name'));
    }

    $filterChain->execute();
  }

}
