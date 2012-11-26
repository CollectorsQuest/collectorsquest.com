<?php

/**
 * ShoppingCartCollectible filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Collectors
 */
class ShoppingCartCollectibleFormFilter extends BaseShoppingCartCollectibleFormFilter
{
  public function configure()
  {
    $this->setupCreatedAtField();
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
