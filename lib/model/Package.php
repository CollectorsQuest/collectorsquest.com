<?php


/**
 * Skeleton subclass for representing a row from the 'package' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class Package extends BasePackage 
{
	// Added By Prakash Panchal. 18-Apr-2011
	/**
	* Initializes internal state of CollectionItemForSale object.
	* @see        parent::__construct()
	*/
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}	
	/**
	* function setTableDefinition() to set default value 0 to column id_collection
	*/	
	public function __toString()
	{  
		return $this->getPackageName();
	}
	public static function packageName($snId)
	{
		$oCriteria =new Criteria();
		$oCriteria->add(PackagePeer::ID,$snId);
		$omCollector = PackagePeer::doSelectOne($oCriteria);
		
		return $omCollector->getPackageName();
	}


} // Package
