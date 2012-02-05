<?php


/**
 * Skeleton subclass for performing query and update operations on the 'wp_users' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.blog
 */
class wpUserQuery extends BasewpUserQuery {

	/**
	 * Returns a new wpUserQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    wpUserQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof wpUserQuery) {
			return $criteria;
		}
		$query = new self('blog', 'wpUser', $modelAlias);
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

} // wpUserQuery
