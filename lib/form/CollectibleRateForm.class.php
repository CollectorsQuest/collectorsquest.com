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

    $this->widgetSchema['rate'] = new sfWidgetFormChoice(
      array('choices' => CollectibleRatePeer::getRateChoices(), 'expanded' => true)
    );
    $this->validatorSchema['rate'] = new sfValidatorChoice(
      array('choices' => CollectibleRatePeer::getRateChoices(), 'required' => true)
    );
    $this->useFields(array('rate'));

  }
}
