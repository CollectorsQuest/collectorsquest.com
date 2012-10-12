<?php


class ObjectRatingForm extends ObjectRatingDynamicExtendForm
{
  public function configure()
  {
    $peer = get_class($this->getObject()->getPeer());
    unset($this['id']);
    $this->widgetSchema['rating'] = new cqWidgetFormRatingStar(
      array('choices' => $peer::getRatingChoices())
    );
    $this->validatorSchema['rating'] = new sfValidatorChoice(
      array('choices' => $peer::getRatingChoices(), 'required' => true)
    );

    $this->useFields(array('rating'));

  }
}
