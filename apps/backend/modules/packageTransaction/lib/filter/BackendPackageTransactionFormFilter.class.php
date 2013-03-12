<?php

/**
 * PackageTransaction filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Kiril Angov
 */
class BackendPackageTransactionFormFilter extends BasePackageTransactionFormFilter
{
  protected static $is_promo_purchase = array(
      0 => 'any',
      1 => 'yes',
      2 => 'no',
  );

  public function configure()
  {
    $this->getWidget('expiry_date')->setOption('with_empty', false);

    $this->setupCollectorIdField();
    $this->setupCreatedAtField();
    $this->setupIsPromoPurchaseField();
  }

  public function setupCollectorIdField()
  {
    $this->widgetSchema['collector_id'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::DISPLAY_NAME,
    ));

    $this->validatorSchema['collector_id'] = new sfValidatorString(array('required'=> false));
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

  protected function setupIsPromoPurchaseField()
  {
    $this->widgetSchema['is_promo_purchase'] = new sfWidgetFormSelect(array(
        'choices' => self::$is_promo_purchase,
    ));
    $this->validatorSchema['is_promo_purchase'] = new sfValidatorChoice(array(
        'choices' => array_keys(self::$is_promo_purchase),
    ));
  }

  /**
   * @param PackageTransactionQuery $criteria
   * @param string $field
   * @param mixed $values
   *
   * @return ModelCriteria
   */
  public function addCollectorIdColumnCriteria($criteria, $field, $values = null)
  {
    if (is_null($values))
    {
      return null;
    }

    if ((int)$values)
    {
      $criteria->filterByCollectorId((int)$values);
    }
    else
    {
      $values = trim($values);
      $criteria->useCollectorQuery()
          ->filterByDisplayName("%$values%")
          ->endUse();
    }

    return $criteria;
  }

  /**
   * @param PackageTransactionQuery $q
   * @param string $field
   * @param boolean $value
   */
  public function addIsPromoPurchaseColumnCriteria($q, $field, $value)
  {
    if (1 == $value)
    {
      $q->filterByPromotionTransactionId(null, Criteria::NOT_EQUAL);
    }

    if (2 == $value)
    {
      $q->filterByPromotionTransactionId(null);
    }
  }
}
