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

    $this->widgetSchema['collector_id'] = new bsWidgetFormInputTypeAhead(array(
      'source' => $this->getOption('url_collector_id', sfContext::getInstance()->getController()->genUrl('packageTransaction/collector')),
    ));

    $this->validatorSchema['collector_id'] = new sfValidatorString(array('required'=>false));
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
