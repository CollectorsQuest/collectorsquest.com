<?php


/**
 * Skeleton subclass for performing query and update operations on the 'wp_posts' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.blog
 */
class wpPostQuery extends BasewpPostQuery {

	/**
	 * Returns a new wpPostQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    wpPostQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof wpPostQuery) {
			return $criteria;
		}
		$query = new self('blog', 'wpPost', $modelAlias);
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}


  public function orderByWpUser($order = Criteria::ASC)
  {
    return $this
      ->usewpUserQuery()
        ->orderByUserNicename($order)
      ->endUse();
  }
} // wpPostQuery
