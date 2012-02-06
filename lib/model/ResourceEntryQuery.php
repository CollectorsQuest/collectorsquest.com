<?php


/**
 * Skeleton subclass for performing query and update operations on the 'resource_entry' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class ResourceEntryQuery extends BaseResourceEntryQuery {

	/**
	 * Returns a new ResourceEntryQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    ResourceEntryQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof ResourceEntryQuery) {
			return $criteria;
		}
		$query = new self('propel', 'ResourceEntry', $modelAlias);
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

} // ResourceEntryQuery
