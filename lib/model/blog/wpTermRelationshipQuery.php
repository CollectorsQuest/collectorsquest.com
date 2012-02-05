<?php


/**
 * Skeleton subclass for performing query and update operations on the 'wp_term_relationships' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.blog
 */
class wpTermRelationshipQuery extends BasewpTermRelationshipQuery {

	/**
	 * Returns a new wpTermRelationshipQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    wpTermRelationshipQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof wpTermRelationshipQuery) {
			return $criteria;
		}
		$query = new self('blog', 'wpTermRelationship', $modelAlias);
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

} // wpTermRelationshipQuery
