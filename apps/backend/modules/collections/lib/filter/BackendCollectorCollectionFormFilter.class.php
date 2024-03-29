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
    $this->widgetSchema['content_category_id'] = new cqWidgetFormPropelChoiceByNestedSet(array(
      'model' => 'ContentCategory',
      'add_empty' => true, 'chozen' => true
    ));
    $this->validatorSchema['content_category_id'] = new sfValidatorPropelChoice(array(
      'required' => false,
      'model' => 'ContentCategory',
      'column' => 'id',
    ));

    $this->setupIdField();
    $this->setupCreatedAtField();
    $this->setupSecretSale();
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

  public function setupSecretSale()
  {
    $this->widgetSchema['secret_sale'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['secret_sale'] = new sfValidatorBoolean();
  }

  public function addSecretSaleColumnCriteria($criteria, $field, $value = null)
  {
    if ($value)
    {
      // get all the collections
      $collections = CollectorCollectionQuery::create()
        ->isComplete()
        ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
        ->find();

      // and filter them for secret sales
      $secret_seller_ids = array_keys(FindsSecretSale::forCollections($collections));

      // then force the filter to use only them
      $criteria->filterById($secret_seller_ids);
    }
  }

}
