<?php

class ConfirmDestructiveActionForm extends BaseForm
{
  public function configure()
  {
    $this->widgetSchema['input'] = new sfWidgetFormInputText(
      array('label' => 'TYPE "CONFIRM" TO ACKNOWLEDGE THIS ACTION'),
      array('style' => 'width: 97%;')
    );

    $this->validatorSchema['input'] = new sfValidatorChoice(array(
      'required' => true,
      'choices' => array('DELETE', 'CONFIRM', 'delete', 'confirm')
    ));

    $this->getWidgetSchema()->setNameFormat('confirm[%s]');
    $this->getWidgetSchema()->setFormFormatterName('BootstrapStacked');
  }
}
