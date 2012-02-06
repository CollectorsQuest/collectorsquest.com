<?php

/**
 * Subclass for representing a row from the 'collection_item_for_sale' table.
 *
 *
 *
 * @package lib.model
 */
class CollectionItemForSale extends BaseCollectionItemForSale
{
  /**
  * Initializes internal state of CollectionItemForSale object.
  * @see        parent::__construct()
  */
  public function __construct()
  {
    // Make sure that parent constructor is always invoked, since that
    // is where any default values for this object are set.
    parent::__construct();
  }

  public function getOffersCount()
  {
    $c = new Criteria();
    $c->add(CollectionItemOfferPeer::STATUS, 'pending');

    return count($this->getCollectionItemOffers());
  }
}
