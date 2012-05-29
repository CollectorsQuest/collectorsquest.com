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
      'source'    => $this->getOption('collection_category_id_url', sfContext::getInstance()->getController()->genUrl('collections/collectionCategory')),
    ));
    $this->setupIdField();
  }

  public function setupIdField()
  {
    $this->widgetSchema['id']    = new sfWidgetFormInputText();
    $this->validatorSchema['id'] = new cqValidatorNumber(array(
      'required'=> false,
      'multiple'=> true
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
