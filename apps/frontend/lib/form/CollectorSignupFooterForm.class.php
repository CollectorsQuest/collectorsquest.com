<?php

class CollectorSignupFooterForm extends CollectorSignupStep1Form
{
  public function setup()
  {
    parent::setup();

    unset($this->widgetSchema['display_name']);
  }
}
