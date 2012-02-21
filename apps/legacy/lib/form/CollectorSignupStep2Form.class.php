<?php

class CollectorSignupStep2Form extends CollectorProfileEditForm
{

  public function configure()
  {
    parent::configure();

    $this->widgetSchema['collector_type']->setOption('expanded', false);

    $this->validatorSchema['about_what_you_collect']->setOption('required', true);
    $this->validatorSchema['about_purchase_per_year']->setOption('required', true);

    $this->widgetSchema->setNameFormat('collectorstep2[%s]');
  }

  public function getCollectorTypeChoices()
  {
    return array('' => '--- Select Collector Type ---')
           + parent::getCollectorTypeChoices();
  }

  protected function unsetFields()
  {
    unset($this['id']);
    unset($this['birthday']);
    unset($this['gender']);
    unset($this['zip_postal']);
    unset($this['country']);
    unset($this['website']);
    unset($this['about_me']);
    unset($this['about_collections']);
    unset($this['about_new_item_every']);
    unset($this['about_interests']);
  }

}
