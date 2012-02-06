<?php

/**
 * Subclass for representing a row from the 'collection_item_offer' table.
 *
 * 
 *
 * @package lib.model
 */ 
class CollectionItemOffer extends BaseCollectionItemOffer
{
	/**
	 * Initializes internal state of CollectionItemOffer object.
	 * @see        parent::__construct()
	 */
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}

}
