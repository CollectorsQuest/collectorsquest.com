<?php

class BackendCollectibleFormFilter extends CollectibleFormFilter
{
  public function configure()
  {
    parent::configure();

    $this->setupSecretSale();
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
      // get all the collectibles
      $collectibles = CollectibleQuery::create()
        ->isComplete()
        ->useCollectibleForSaleQuery(null, Criteria::LEFT_JOIN)
          ->filterByCollectibleId(null, Criteria::ISNULL)
        ->endUse()
        ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
        ->find();

      // and filter them for secret sales
      $secret_seller_ids = array_keys(FindsSecretSale::forCollectibles($collectibles));

      // then force the filter to use only them
      $criteria->filterById($secret_seller_ids);
    }
  }
}
