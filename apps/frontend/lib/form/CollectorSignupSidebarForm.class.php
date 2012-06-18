<?php

class CollectorSignupSidebarForm extends CollectorSignupStep1Form
{
  public function setup()
  {
    parent::setup();

    unset($this->widgetSchema['display_name']);
  }

  /**
   * Overwrite getCSRFToken so that it will be compatible with CollectorSignupStep1Form
   *
   * @param     string $secret The secret string to use (null to use the current secret)
   * @return    string A token string
   *
   * @see       sfForm::getCSRFToken
   */
  public function getCSRFToken($secret = null)
  {
    if (null === $secret)
    {
      $secret = $this->localCSRFSecret ? $this->localCSRFSecret : self::$CSRFSecret;
    }

    return md5($secret.session_id().'CollectorSignupStep1Form');
  }
}
