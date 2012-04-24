<?php

/**
 * cqNextAccessFilter will auto login any user that has the proper login hash,
 * checked against the allowed time limit since the hash's generation.
 *
 * If the user cannot be logged in, he will be forwarded to the countdown action
 */
class cqNextAccessFilter extends sfFilter
{

  public function execute($filterChain)
  {
    // Do not put restrictions in certain environments
    if (in_array(SF_ENV, array('dev', 'test')))
    {
      $filterChain->execute();

      return;
    }

    /* @var $request sfWebRequest */
    $request = $this->context->getRequest();

    /* @var $sf_user cqBaseUser */
    $sf_user = $this->context->getUser();

    $param_name = $this->getAutoLoginParameterName();

    // try to login the user if auto login parameter present and hash valid
    if ( $request->hasParameter($param_name)
      && ($collector = CollectorPeer::retrieveByHashTimeLimited($request->getParameter($param_name), $this->getAutoLoginTimeLimit()))
      && $collector->getCqnextAccessAllowed() )
    {
      if ($sf_user->Authenticate(true, $collector, $remember_me = true))
      {
        $this->redirectToOriginalRequestUri($request);

        return ;
      }
    }

    // if the current user is not authenticated or is authenticated but does not have cqnext access
    // and the current action is not countdown
    if ( !($sf_user->isAuthenticated() && $sf_user->getCollector()->getCqnextAccessAllowed())
      && !$this->currentActionIsCountdown() && !$this->currentModuleIsSandbox() )
    {
      $this->forwardToCountdownAction();

      return ;
    }

    $filterChain->execute();
  }

  protected function forwardToCountdownAction()
  {
    $this->context->getController()->forward(
      $this->getCountdownModuleName(),
      $this->getCountdownActionName()
    );

    throw new sfStopException();
  }

  protected function redirectToOriginalRequestUri(sfWebRequest $request)
  {
    $parsed_url = parse_url($request->getUri());
    $this->context->getController()->redirect($parsed_url['path']);

    throw new sfStopException();
  }

  protected function currentModuleIsSandbox()
  {
    /* @var $last_action sfActions */
    $last_action = $this->context->getController()->getActionStack()->getLastEntry();

    return $last_action->getModuleName() == 'sandbox';
  }

  protected function currentActionIsCountdown()
  {
    /* @var $last_action sfActions */
    $last_action = $this->context->getController()->getActionStack()->getLastEntry();

    return $last_action->getModuleName() == $this->getCountdownModuleName()
        && $last_action->getActionName() == $this->getCountdownActionName();
  }

  protected function getCountdownModuleName()
  {
    return sfConfig::get('app_countdown_module');
  }

  protected function getCountdownActionName()
  {
    return sfConfig::get('app_countdown_action');
  }

  protected function getAutoLoginParameterName()
  {
    return sfConfig::get('app_cqnext_auto_login_parameter_name');
  }

  protected function getAutoLoginTimeLimit()
  {
    return sfConfig::get('app_cqnext_auto_login_time_limit');
  }

}
