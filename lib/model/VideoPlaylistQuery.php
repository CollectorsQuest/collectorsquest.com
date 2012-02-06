<?php


/**
 * Skeleton subclass for performing query and update operations on the 'video_playlist' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class VideoPlaylistQuery extends BaseVideoPlaylistQuery {

	/**
	 * Returns a new VideoPlaylistQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    VideoPlaylistQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof VideoPlaylistQuery) {
			return $criteria;
		}
		$query = new self('propel', 'VideoPlaylist', $modelAlias);
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

} // VideoPlaylistQuery
