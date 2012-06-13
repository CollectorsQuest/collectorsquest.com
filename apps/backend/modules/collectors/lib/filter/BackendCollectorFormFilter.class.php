<?php

class BackendCollectorFormFilter extends CollectorFormFilter
{

  public function configure()
  {
    parent::configure();

    $this->setupUsernameField();
    $this->setupDisplayNameField();
    $this->setupCollectionIdField();
  }

  public function setupUsernameField()
  {
    $this->widgetSchema['username'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::USERNAME
    ));
  }

  public function setupDisplayNameField()
  {
    $this->widgetSchema['display_name'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::DISPLAY_NAME
    ));
  }

  public function setupCollectionIdField()
  {
    $this->widgetSchema['collection_id'] = new sfWidgetFormInputText();
    $this->validatorSchema['collection_id'] = new cqValidatorNumber(array(
      'required'=> false,
      'multiple'=> true
    ));
  }

  /**
   * @param CollectorQuery $criteria
   * @param string $field
   * @param array|string|null $values
   * @return CollectorQuery
   */
  public function addCollectionIdColumnCriteria($criteria, $field, $values = null)
  {
    if (null === $values)
    {
      return $criteria;
    }

    if (!is_array($values))
    {
      $values = explode(',', $values);
    }

    if (count($values))
    {
      $criteria->joinCollectorCollection();
      $criteria->useCollectorCollectionQuery()
        ->filterById($values, Criteria::IN)
        ->endUse();
    }

    return $criteria;
  }

}
