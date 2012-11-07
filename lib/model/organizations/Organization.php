<?php

/**
 * Skeleton subclass for representing a row from the 'organization' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.organizations
 */
class Organization extends BaseOrganization
{

  /**
   * Return the type of the organization for display purposes
   *
   * @return    string
   */
  public function getDisplayType()
  {
    if ($this->getOrganizationType())
    {
      return $this->getOrganizationType()->getName();
    }
    else
    {
      return $this->getTypeOther();
    }
  }

  /**
   * @param     Collector|integer $collector
   * @param     PropelPDO $con
   *
   * @return    boolean
   */
  public function isMember($collector, PropelPDO $con = null)
  {
    return !!OrganizationMembershipQuery::create()
      ->filterByOrganization($this)
      ->_if($collector instanceof Collector)
        ->filterByCollector($collector)
      ->_else()
        ->filterByCollecotrId($collector)
      ->_endif()
      ->count($con);
  }

  /**
   * @param     Collector $collector
   * @param     PropelPDO $con
   *
   * @return    OrganizationMembershipRequest|boolean
   */
  public function isMembershipRequested($collector, PropelPDO $con = null)
  {
    return OrganizationMembershipRequestQuery::create()
      ->filterByOrganization($this)
      ->_if($collector instanceof Collector)
        ->filterByCollectorRelatedByCollectorId($collector)
      ->_else()
        ->filterByCollecotrId($collector)
      ->_endif()
      ->orderById(Criteria::DESC) // alternative to an "order by created at" cond
      ->findOne($con) ?: false;
  }

}
