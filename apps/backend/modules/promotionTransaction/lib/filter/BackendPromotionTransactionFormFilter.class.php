<?php

/**
 * PromotionTransaction Backend filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 */
class BackendPromotionTransactionFormFilter extends PromotionTransactionFormFilter
{
  public function configure()
  {
    $this->setupCollectorName();
    $this->setupCollectorEmail();
  }

  public function setupCollectorName()
  {
    $this->widgetSchema['collector_username'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::USERNAME
    ));
    $this->validatorSchema['collector_username'] = new  sfValidatorPass(array('required' => false));
  }

  public function setupCollectorEmail()
  {
    $this->widgetSchema['collector_email'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::EMAIL
    ));
    $this->validatorSchema['collector_email'] = new  sfValidatorPass(array('required' => false));
  }
}
