<?php

class SellerPackagesSignupForm extends CollectorSignupStep1Form
{

  public function configure()
  {
    parent::configure();

    // assume that a new user signing up through this form is a collector
    $this->widgetSchema['seller'] = new sfWidgetFormInputHidden();
    $this->setDefault('seller', 1);
  }

}