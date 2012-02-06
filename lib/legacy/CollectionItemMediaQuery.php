<?php


/**
 * Skeleton subclass for performing query and update operations on the 'collection_item_media' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.legacy
 */
class CollectionItemMediaQuery extends BaseCollectionItemMediaQuery {

	/**
	 * Returns a new CollectionItemMediaQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    CollectionItemMediaQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof CollectionItemMediaQuery) {
			return $criteria;
		}
		$query = new self('propel', 'CollectionItemMedia', $modelAlias);
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

} // CollectionItemMediaQuery
