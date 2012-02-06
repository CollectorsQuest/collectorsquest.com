<?php


/**
 * Skeleton subclass for performing query and update operations on the 'collection_item_for_sale' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.legacy
 */
class CollectionItemForSaleQuery extends BaseCollectionItemForSaleQuery {

	/**
	 * Returns a new CollectionItemForSaleQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    CollectionItemForSaleQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof CollectionItemForSaleQuery) {
			return $criteria;
		}
		$query = new self('propel', 'CollectionItemForSale', $modelAlias);
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

} // CollectionItemForSaleQuery
