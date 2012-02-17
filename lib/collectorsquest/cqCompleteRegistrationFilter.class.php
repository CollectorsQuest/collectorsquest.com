<?php

/**
 * Check if the user has the right to accesss secure actions
 *
 * Because user registration is done in 3 parts, a user should be allowed to access
 * all non-secure pages, but be prompted to complete his/her registration when
 * trying to access a secure one
 */
class cqCompleteRegistrationFilter extends sfFilter
{
  public function execute($filterChain)
  {
    if ( $this->context->getUser()->isAuthenticated()
      && $this->currentActionIsSecure()
      && !$this->context->getUser()->getCollector()->getHasCompletedRegistration() )
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $this->context->getEventDispatcher()->notify(new sfEvent($this, 'application.log', array(
          sprintf('User "%s" has not completed his/her registration,forwarding to "%s/%s"',
            $this->context->getUser()->getCollector()->getDisplayName(),
            sfConfig::get('app_signup_module'), sfConfig::get('app_signup_action')
        ))));
      }

      $this->forwardToSignupAction();

      return ;
    }

    $filterChain->execute();
  }

  protected function currentActionIsSecure()
  {
    return $this->context->getController()->getActionStack()->getLastEntry()->getActionInstance()->isSecure();
  }

  protected function forwardToSignupAction()
  {
    $this->context->getController()->forward(sfConfig::get('app_signup_module'), sfConfig::get('app_signup_action'));

    throw new sfStopException();
  }

}