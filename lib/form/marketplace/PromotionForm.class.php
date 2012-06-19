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

    if ($this->getObject()->isNew())
    {
      $this->setDefault('promotion_code', sprintf('CQ%d-%s', date('Y'), ShoppingOrderPeer::getUuidFromId(date('mdHis'))));
    }
  }

  public function configure()
  {
    $this->widgetSchema['expiry_date'] = new sfWidgetFormJQueryDate();

    $this->widgetSchema['amount_type'] = new sfWidgetFormSelectRadio(array(
      'choices'=> $this->amountTypes,
      'formatter'        => function($widget, $inputs)
      {
        $rows = array();
        foreach ($inputs as $input)
        {
          $rows[] = $widget->renderContentTag('label',
              $input['input'] . html_entity_decode($input['label']),
            array('class'=> 'radio')
          );
        }

        return !$rows ? '' : $widget->renderContentTag('div', implode($widget->getOption('separator'), $rows), array('class' => $widget->getOption('class')));
      }
    ), array(
      'class'=>'inline'
    ));
  }

}
