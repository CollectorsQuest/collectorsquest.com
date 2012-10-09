<?php

/**
 * CollectibleRate form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors Quest, Inc.
 */
class CollectibleRateForm extends BaseCollectibleRateForm
{
  public function configure()
  {
    // TO DO All this code should be at rate behavior
    unset($this['id']);
    $this->widgetSchema['rate'] = new cqWidgetFormRateStar(
      array('choices' => CollectibleRatePeer::getRateChoices())
    );
    $this->validatorSchema['rate'] = new sfValidatorChoice(
      array('choices' => CollectibleRatePeer::getRateChoices(), 'required' => true)
    );
    $this->useFields(array('rate'));

  }
}
