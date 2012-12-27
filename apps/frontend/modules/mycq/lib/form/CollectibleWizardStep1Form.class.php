<?php

class CollectibleWizardStep1Form extends CollectibleForm
{
  public function configure()
  {
    $this->useFields(array('name'));
  }

}
