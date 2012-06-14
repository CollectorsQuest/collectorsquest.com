<?php

/**
 * Promotion form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 */
class PromotionForm extends BasePromotionForm
{
  protected $amountTypes = array('Fix'=>'Fix', 'Percentage'=>'Percentage');

  public function __construct($object = null, $options = array(), $CSRFSecret = null)
  {
    parent::__construct($object, $options, $CSRFSecret);

    $this->setDefault('promotion_code', sprintf('CQ%d-%s', date('Y'), ShoppingOrderPeer::getUuidFromId(date('mdHis'))));
  }

  public function configure()
  {
    $this->widgetSchema['expiry_date'] = new sfWidgetFormJQueryDate();
    
    $this->widgetSchema['amount_type'] = new sfWidgetFormChoice(array('choices'=> $this->amountTypes, 'expanded'=>true), array('class'=>'unstyled'));
  }

}
