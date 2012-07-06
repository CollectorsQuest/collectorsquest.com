<?php

class CollectorLoginFooterForm extends CollectorLoginForm
{

  public function configure()
  {
    parent::configure();

    $this->widgetSchema->setFormFormatterName('BootstrapWithRowFluid');
  }

}
