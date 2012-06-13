<?php

class CollectorSignupStep3Form extends CollectorProfileEditForm
{
  public function configure()
  {
    parent::configure();

    $this->validatorSchema['country_iso3166']->setOption('required', true);

    $this->widgetSchema->setNameFormat('signup_step3[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  protected function unsetFields()
  {
    parent::unsetFields();

    unset($this['collector_id']);
    unset($this['collector_type']);
    unset($this['about_what_you_collect']);
    unset($this['about_purchase_per_year']);
    unset($this['about_most_expensive_item']);
    unset($this['about_annually_spend']);
    unset($this['about_new_item_every']);
    unset($this['about_me']);
    unset($this['about_collections']);
    unset($this['about_new_item_every']);
    unset($this['about_interests']);
  }

}