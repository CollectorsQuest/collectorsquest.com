<?php


require 'lib/model/organizations/om/BaseOrganizationTypePeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'organization_type' table.
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.organizations
 */
class OrganizationTypePeer extends BaseOrganizationTypePeer
{
  public static $organization_types = array(
      self::TYPE_COLLECTOR_CLUB => 'Collector Club',
      self::TYPE_ANTIQUE_MALL => 'Antique Mall',
      self::TYPE_FLEA_MARKET => 'Flea Market',
      self::TYPE_TRADE_SHOW => 'Trade Show',
  );

}
