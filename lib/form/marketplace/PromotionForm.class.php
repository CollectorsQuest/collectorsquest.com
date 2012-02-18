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
  
  public function configure()
  {
    $this->widgetSchema['expiry_date'] = new sfWidgetFormJQueryDate(array(
        'culture' => $this->getOption('culture'),
        'image' =>  '/images/backend/theme/medium/calendar.png',
//        'date_widget' => new sfWidgetFormInput(),
      ));
    
    $this->widgetSchema['amount_type'] = new sfWidgetFormChoice(array('choices'=> $this->amountTypes, 'expanded'=>true));
  }

}
