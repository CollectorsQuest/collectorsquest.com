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
    $this->setupProgressField();
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

  public function setupProgressField()
  {
    $choices = array(
      '' => '',
      0 => 'Shipping & Handling',
      1 => 'Payment',
      2 => 'Sale'
    );
    $this->widgetSchema['progress'] = new sfWidgetFormSelect(array(
      'choices' => $choices,
    ));
    $this->validatorSchema['progress'] = new sfValidatorChoice(array(
      'choices' => array_keys($choices),
      'required' => false,
    ));
  }

  public function setupPaymentStatusField()
  {
    $choices = array(
      '' => '',
      ShoppingPaymentPeer::STATUS_INITIALIZED => 'Initialized',
      ShoppingPaymentPeer::STATUS_INPROGRESS => 'Inprogress',
      ShoppingPaymentPeer::STATUS_CONFIRMED => 'Confirmed',
      ShoppingPaymentPeer::STATUS_CANCELLED => 'Cancelled',
      ShoppingPaymentPeer::STATUS_FAILED => 'Failed',
      ShoppingPaymentPeer::STATUS_COMPLETED => 'Completed',
    );
    $this->widgetSchema['payment_status'] = new sfWidgetFormSelect(array(
      'choices' => $choices,
    ));
    $this->validatorSchema['payment_status'] = new sfValidatorChoice(array(
      'choices' => array_keys($choices),
      'required' => false,
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
