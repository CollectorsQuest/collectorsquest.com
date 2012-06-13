<?php

class CollectorSignupStep2Form extends CollectorProfileEditForm
{
  public function configure()
  {
    parent::configure();

    $this->widgetSchema['collector_type']->setOption('expanded', false);

    $this->validatorSchema['about_what_you_collect']->setOption('required', true);
    $this->validatorSchema['about_purchase_per_year']->setOption('required', true);

    $this->widgetSchema->setNameFormat('signup_step2[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  public function getCollectorTypeChoices()
  {
    return array('' => '--- Select Collector Type ---')
           + parent::getCollectorTypeChoices();
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    // ignore the default value of "casual" and let the user select the type
    $this->setDefault('collector_type', null);
  }

  protected function unsetFields()
  {
    parent::unsetFields();

    unset($this['collector_id']);
    unset($this['birthday']);
    unset($this['gender']);
    unset($this['zip_postal']);
    unset($this['country_iso3166']);
    unset($this['website']);
    unset($this['about_me']);
    unset($this['about_collections']);
    unset($this['about_new_item_every']);
    unset($this['about_interests']);
  }

}
