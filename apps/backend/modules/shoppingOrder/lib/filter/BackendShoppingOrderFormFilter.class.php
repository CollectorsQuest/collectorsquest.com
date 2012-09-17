<?php

/**
 * ShoppingOrder filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Collectors
 */
class BackendShoppingOrderFormFilter extends ShoppingOrderFormFilter
{
  public function configure()
  {
    parent::configure();

    $this->setupCollectorSellerField();
    $this->setupPaymentStatusField();
    $this->setupCreatedAtField();

    $this->widgetSchema['buyer_email'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => ShoppingOrderPeer::BUYER_EMAIL
    ));
    $this->widgetSchema['shipping_full_name'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => ShoppingOrderPeer::SHIPPING_FULL_NAME
    ));
  }

  public function setupCollectorSellerField()
  {
    $this->widgetSchema['collector_seller'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::DISPLAY_NAME
    ));
    $this->validatorSchema['collector_seller'] = new sfValidatorPass(array('required' => false));
  }

  public function setupPaymentStatusField()
  {
    $this->widgetSchema['payment_status'] = new sfWidgetFormChoice(array(
      'choices' =>
        array(
          '' => 'All', 0 => 'Initialized', 1 => 'Inprogress',
          2 => 'Confirmed', 3=>'Cancelled', 4=>'Failed', 5=>'Completed',
        )

    ));
    $this->validatorSchema['payment_status'] = new sfValidatorChoice(array(
      'required' => false, 'choices' => array(0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5,)
    ));
  }

  protected function setupCreatedAtField()
  {
    $this->widgetSchema['created_at'] = new sfWidgetFormJQueryDateRange(array(
      'config' => '{}',
    ));
    $this->validatorSchema['created_at'] = new IceValidatorDateRange(array(
      'required' => false, 'from_date' => 'from', 'to_date' => 'to'
    ));
  }
}
