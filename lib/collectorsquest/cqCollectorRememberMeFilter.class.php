<?php

/**
 * Processes the "remember me" cookie.
 *
 * This filter should be added to the application filters.yml file **above**
 * the security filter:
 *
 *    remember_me:
 *      class: sfGuardRememberMeFilter
 *
 *    security: ~
 *
 * based on sfGuardPlugin implementation
 */
class cqCollectorRememberMeFilter extends sfFilter
{
  /**
   * @see sfFilter
   */
  public function execute($filterChain)
  {
    $cookieName = sfConfig::get('app_collector_remember_cookie_name', 'cqRemember');

    if ( $this->isFirstCall()
      && !$this->context->getUser()->isAuthenticated()
      && $cookie = $this->context->getRequest()->getCookie($cookieName) )
    {
      $criteria = new Criteria();
      $criteria->add(CollectorRememberKeyPeer::REMEMBER_KEY, $cookie);

      if (( $crk = CollectorRememberKeyPeer::doSelectOne($criteria) ))
      {
        $this->context->getUser()->Authenticate(true, $crk->getCollector());
      }
    }

    $filterChain->execute();
  }

}
