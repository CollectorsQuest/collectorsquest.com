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
    /** @var $sf_user cqBaseUser */
    $sf_user = $this->context->getUser();

    if ( $sf_user->isAuthenticated()
      && $this->currentActionIsSecure()
      && !$sf_user->getCollector()->getHasCompletedRegistration() )
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $this->context->getEventDispatcher()->notify(new sfEvent($this, 'application.log', array(
          sprintf('Collector "%s" has not completed his/her registration, forwarding to "%s/%s"',
            $sf_user->getCollector()->getDisplayName(),
            sfConfig::get('app_signup_module'), sfConfig::get('app_signup_action')
        ))));
      }

      if (1 < $tries = $sf_user->getAttribute('not_completed_registration_tries', 0))
      {
        $message = trim('
          We are sorry but you need to finish your Collector Profile
          before you can use any of the personalized sections of collectorsquest.com.
          It *only* takes a minute!
        ');
        $sf_user->setFlash('error', $message, true);
      }

      $sf_user->setAttribute('not_completed_registration_tries', ++$tries);

      $this->forwardToSignupAction();

      return ;
    }
    else if ($sf_user->getCollector()->getHasCompletedRegistration())
    {
      // Unset the session variable if set before
      $sf_user->setAttribute('not_completed_registration_tries', null);
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
