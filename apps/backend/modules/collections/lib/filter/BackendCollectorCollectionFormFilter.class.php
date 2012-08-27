<?php
/**
 * File: CollectorCollectionFormFilter.class.php
 *
 * @author zecho
 * @version $Id$
 *
 */

class BackendCollectorCollectionFormFilter extends BaseCollectorCollectionFormFilter
{

  public function configure()
  {
    $this->widgetSchema['collection_category_id'] = new bsWidgetFormInputTypeAhead(array(
      'source' => $this->getOption(
        'collection_category_id_url',
        cqContext::getInstance()->getController()->genUrl('collections/collectionCategory')
      ),
    ));

    $this->setupIdField();
    $this->setupCreatedAtField();
  }

  public function setupIdField()
  {
    $this->widgetSchema['id']    = new sfWidgetFormInputText();
    $this->validatorSchema['id'] = new cqValidatorNumber(array(
      'required'=> false,
      'multiple'=> true
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

  /**
   * @param CollectorCollectionQuery $criteria
   * @param string $field
   * @param mixed $values
   * @return mixed
   */
  public function addCollectionCategoryIdColumnCriteria($criteria, $field, $values = null)
  {
    if (is_null($values))
    {
      return null;
    }

    if ((int)$values)
    {
      $criteria->filterByCollectionCategoryId((int)$values);
    }
    else
    {
      $criteria->useCollectionCategoryQuery()
          ->filterByName("%$values%")
          ->endUse();
    }

    return $criteria;
  }

  /**
   * @param CollectorCollectionQuery $criteria
   * @param string $field
   * @param array|int|null $values
   * @return CollectorCollectionQuery
   */
  public function addIdColumnCriteria($criteria, $field, $values = null)
  {
    if (null === $values)
    {
      return null;
    }

    $criteria->filterById(explode(',', $values), Criteria::IN);

    return $criteria;
  }

}
