<?php

class BackendCollectorFormFilter extends CollectorFormFilter
{

  public function configure()
  {
    parent::configure();

    $this->setupUsernameField();
    $this->setupDisplayNameField();
    $this->setupCollectionIdField();
    $this->setupNewsletterField();
    $this->setupCreatedAtField();
    $this->setupSecretSale();
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

  public function setupNewsletterField()
  {
    $this->widgetSchema['newsletter'] = new sfWidgetFormChoice(array(
      'choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no')
    ));
    $this->validatorSchema['newsletter'] = new sfValidatorChoice(array(
      'required' => false,
      'choices' => array('', 1, 0)
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

  public function setupSecretSale()
  {
    $this->widgetSchema['secret_sale'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['secret_sale'] = new sfValidatorBoolean();
  }

  protected $secret_sellers_criteria_executing = false;
  /**
   * @param CollectorQuery $criteria
   * @param string $field
   * @param boolean $value
   */
  public function addSecretSaleColumnCriteria($criteria, $field, $value = null)
  {
    // we will invoke build criteria again inside this function,
    // and we have to make sure to prevent infinite recursion
    if (!$this->secret_sellers_criteria_executing && $value)
    {
      // build a temporary criteria, without this field
      $this->secret_sellers_criteria_executing = true;

      $temp_criteria = $this->buildCriteria($this->getValues() ?: $this->processedValues);
      $this->secret_sellers_criteria_executing = false;

      // get all the collectors for the selected filters
      $collectors = $temp_criteria->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)->find();
      // and filter them for secret sellers
      $secret_seller_ids = array_keys(FindsSecretSale::forCollectors($collectors));

      // then force only them as the result
      $criteria->filterById($secret_seller_ids);
    }
  }

  // fiter values are kept in the session; We need to capture them before
  // addSecretSaleColumnCriteria is called, in order to do the magics
  protected $processedValues = array();
  public function processValues($values)
  {
    $values = parent::processValues($values);

    $this->processedValues = $values;

    return $values;
  }
}
