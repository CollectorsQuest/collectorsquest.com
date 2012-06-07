<?php

/**
 * Description of FrontendNewsletterSignupForm
 *
 * Forward facing form that does not expose unnecessary field
 *
 */
class FrontendNewsletterSignupForm extends NewsletterSignupForm
{

  public function configure()
  {
    parent::configure();
    unset ($this['id']);
  }

}
