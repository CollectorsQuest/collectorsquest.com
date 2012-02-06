<?php


/**
 * Skeleton subclass for performing query and update operations on the 'collector_friend' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class CollectorFriendQuery extends BaseCollectorFriendQuery {

	/**
	 * Returns a new CollectorFriendQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    CollectorFriendQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof CollectorFriendQuery) {
			return $criteria;
		}
		$query = new self('propel', 'CollectorFriend', $modelAlias);
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

} // CollectorFriendQuery
