<?php

class CollectorSignupStep3Form extends CollectorProfileEditForm
{
  public function configure()
  {
    parent::configure();

    $this->validatorSchema['country']->setOption('required', true);

    $this->widgetSchema->setNameFormat('collectorstep3[%s]');
  }

  protected function unsetFields()
  {
    unset($this['id']);
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