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

  public function configure()
  {
    $this->getWidget('expiry_date')->setOption('with_empty', false);
    $this->setupCollectorIdField();
  }

  public function setupCollectorIdField()
  {
    $this->widgetSchema['collector_id'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::DISPLAY_NAME,
    ));

    $this->validatorSchema['collector_id'] = new sfValidatorString(array('required'=> false));
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
}
