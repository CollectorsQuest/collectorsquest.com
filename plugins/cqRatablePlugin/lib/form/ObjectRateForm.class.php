<?php


class ObjectRateForm extends ObjectRateDynamicExtendForm
{
  public function configure()
  {
    $peer = get_class($this->getObject()->getPeer());
    unset($this['id']);
    $this->widgetSchema['rate'] = new cqWidgetFormRateStar(
      array('choices' => $peer::getRateChoices())
    );
    $this->validatorSchema['rate'] = new sfValidatorChoice(
      array('choices' => $peer::getRateChoices(), 'required' => true)
    );

    $this->useFields(array('rate'));

  }
}
