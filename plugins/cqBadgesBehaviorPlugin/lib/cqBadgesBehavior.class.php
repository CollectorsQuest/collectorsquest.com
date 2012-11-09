<?php

class cqBadgesBehavior
{
  /**
   * initialize Badges collection
   */
  private function initBadges(BaseObject $object, $overrideExisting = true)
  {
    if (isset($object->collBadges) && null !== $object->collBadges && !$overrideExisting)
    {
      return;
    }
    $object->collBadges = new PropelObjectCollection();
    $object->collBadges->setModel('cqBadge');
  }

  /**
   * Add Badge
   *
   * @param BaseObject $object
   * @param cqBadge $badge
   * @return BaseObject
   */
  public function addBadge(BaseObject $object, cqBadge $badge)
  {
    if (!isset($object->collBadges) || $object->collBadges === null)
    {
      $this->initBadges($object);
    }
    if (!$object->collBadges->contains($badge))
    {
      // only add it if the **same** object is not already associated
      $object->collBadges[] = $badge;
      $object->badgesToAdd[] = $badge;
    }
    if (isset($object->badgesForDeletion) && count($object->badgesForDeletion))
    {
      // Remove added item from ToDeletion array
      foreach ($object->badgesForDeletion as $key => $badgeToDeletion)
      {
        if ($badge->equals($badgeToDeletion))
        {
          unset($object->badgesForDeletion[$key]);
        }
      }
    }

    return $object;
  }

  /**
   * Gets an array of Badges objects which references to $object.
   *
   * If the $criteria is not null, it is used to always fetch the results from the database.
   * Otherwise the results are fetched from the database the first time, then cached.
   * Next time the same method is called without $criteria, the cached collection is returned.
   * If this $object is new, it will return
   * an empty collection or the current collection; the criteria is ignored on a new object.
   *
   *
   * @param BaseObject $object
   * @param null $criteria
   * @param PropelPDO $con
   * @return array|mixed|PropelObjectCollection
   */
  public function getBadges(BaseObject $object, $criteria = null, PropelPDO $con = null)
  {
    if (isset($object->collBadges) && $object->collBadges !== null && null === $criteria)
    {
      return $object->collBadges;
    }
    else
    {
      if ($object->isNew())
      {
        $this->initBadges($object);
      }
      $q = cqBadgeQuery::create(null, $criteria);
      $q->usecqBadgeReferenceQuery()
        ->filterByModelId($object->getPrimaryKey())
        ->filterByModel(get_class($object))
        ->endUse();
      if ($criteria === null)
      {
        return $q->find($con);
      }
      $object->collBadges = $q->find($con);

    }
    return $object->collBadges;
  }

  /**
   * Sets a collection of Badge
   *
   * @param BaseObject $object
   * @param PropelCollection $badges
   * @param PropelPDO $con
   */
  public function setBadges(BaseObject $object, PropelCollection $badges, PropelPDO $con = null)
  {
    $object->badgesForDeletion = $object->getBadges(new Criteria(), $con)->diff($badges, false);

    foreach ($badges as $badge)
    {
      $object->addBadge($badge);
    }

    $this->collBadges = $badges;
  }

  /**
   * Remove All Badges
   *
   * @param BaseObject $object
   */
  public function removeAllBadges(BaseObject $object)
  {
    $object->badgesForDeletion = $object->getBadges();
    unset($object->badgesToAdd);
    $this->initBadges($object, true);
  }

  /**
   * Badges saving logic, runned after the object himself has been saved
   *
   * @param  BaseObject  $object
   */
  public function postSave(BaseObject $object)
  {
    if (isset($object->badgesForDeletion) && count($object->badgesForDeletion))
    {
      foreach ($object->badgesForDeletion as $toDeletion)
      {
        /* @var $toDeletion cqBadge*/
        cqBadgeReferenceQuery::create()
          ->filterByModelId($object->getPrimaryKey())
          ->filterByModel(get_class($object))
          ->filterByBadgeId($toDeletion->getId())
          ->delete();
      }
    }
    unset($object->badgesForDeletion);

    if (isset($object->badgesToAdd) && count($object->badgesToAdd))
    {
      foreach ($object->badgesToAdd as $toAdd)
      {
        /* @var $toAdd cqBadge*/
        if ($toAdd->isNew())
        {
          $toAdd->save();
        }
        /* @var $r cqBadge*/
        $r = cqBadgeReferenceQuery::create()
          ->filterByModelId($object->getPrimaryKey())
          ->filterByModel(get_class($object))
          ->filterByBadgeId($toAdd->getId())
          ->findOneOrCreate();
        if ($r->isNew())
        {
          $r->save();
        }
      }
    }

    unset($object->badgesToAdd);

  }

  /**
   * Badges removing logic, runned before the object himself has been deleted
   *
   * @param  BaseObject  $object
   */
  public function preDelete(BaseObject $object)
  {
    $object->removeAllBadges();
    $object->save();
  }
}
