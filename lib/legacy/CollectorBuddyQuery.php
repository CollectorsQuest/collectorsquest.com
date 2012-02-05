<?php


/**
 * Skeleton subclass for performing query and update operations on the 'collector_buddy' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.legacy
 */
class CollectorBuddyQuery extends BaseCollectorBuddyQuery {

	/**
	 * Returns a new CollectorBuddyQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    CollectorBuddyQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof CollectorBuddyQuery) {
			return $criteria;
		}
		$query = new self('propel', 'CollectorBuddy', $modelAlias);
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

} // CollectorBuddyQuery
