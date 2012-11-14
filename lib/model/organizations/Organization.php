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
        ->filterByCollectorId($collector)
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
        ->filterByCollectorId($collector)
      ->_endif()
      ->orderById(Criteria::DESC) // alternative to an "order by created at" cond
      ->findOne($con) ?: false;
  }

  /**
   * Gets a collection of Collector objects related by a many-to-many relationship
   * to the current object by way of the organization_membership cross-reference table.
   *
   * If the $criteria is not null, it is used to always fetch the results from the database.
   * Otherwise the results are fetched from the database the first time, then cached.
   * Next time the same method is called without $criteria, the cached collection is returned.
   * If this Organization is new, it will return
   * an empty collection or the current collection; the criteria is ignored on a new object.
   *
   * Will automatically order the collectors by joined at date
   *
   * @param      Criteria $criteria Optional query object to filter the query
   * @param      PropelPDO $con Optional connection object
   *
   * @return     PropelCollection|array Collector[] List of Collector objects
   */
  public function getCollectors($criteria = null, PropelPDO $con = null)
  {
    if(null === $this->collCollectors || null !== $criteria) {
      if ($this->isNew() && null === $this->collCollectors) {
        // return empty collection
        $this->initCollectors();
      } else {
        $collCollectors = CollectorQuery::create(null, $criteria)
          ->filterByOrganization($this)
          ->useOrganizationMembershipQuery()
            ->orderByJoinedAt(Criteria::DESC)
          ->endUse()
          ->find($con);
        if (null !== $criteria) {
          return $collCollectors;
        }
        $this->collCollectors = $collCollectors;
      }
    }
    return $this->collCollectors;
  }

}
