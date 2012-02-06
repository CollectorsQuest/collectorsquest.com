<?php


/**
 * Skeleton subclass for performing query and update operations on the 'collection_item_offer' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.legacy
 */
class CollectionItemOfferQuery extends BaseCollectionItemOfferQuery {

	/**
	 * Returns a new CollectionItemOfferQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    CollectionItemOfferQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof CollectionItemOfferQuery) {
			return $criteria;
		}
		$query = new self('propel', 'CollectionItemOffer', $modelAlias);
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

} // CollectionItemOfferQuery
