<?php

/**
 * Description of CollectorAddCreditsForm
 */
class CollectorAddCreditsForm extends BaseForm
{
  public function configure()
  {
    $this->setupNumCreditsField();
    $this->setupConfirmationField();
    $this->getWidgetSchema()
      ->setNameFormat('collector_add_credits[%s]')
      ->setFormFormatterName('Bootstrap');
  }

  protected function setupNumCreditsField()
  {
    $this->widgetSchema['num_credits'] = new sfWidgetFormInputText(array(
        'label' => 'Credits to add',
    ));
    $this->validatorSchema['num_credits'] = new sfValidatorNumber(array(
        'min' => 1,
    ));
  }

  protected function setupConfirmationField()
  {
    $this->widgetSchema['confirm'] = new sfWidgetFormInputCheckbox(array(
        'label' => 'Confirm',
    ));
    $this->validatorSchema['confirm'] = new sfValidatorBoolean(
      array('required' => true),
      array('required' => 'You must confirm that you want to add credits to this user')
    );
  }

}
