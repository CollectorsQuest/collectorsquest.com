<?php

/**
 * NewsletterSignup form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 */
class NewsletterSignupForm extends BaseNewsletterSignupForm
{
  public function configure()
  {
    $this->getValidatorSchema()->setPostValidator(new sfValidatorPropelUnique(array(
        'field'=>'email',
        'model' => 'NewsletterSignup',
        'column' => 'email'
        ), array(
        'invalid' => 'This email is already signed up'
        )
    ));
  }

}
