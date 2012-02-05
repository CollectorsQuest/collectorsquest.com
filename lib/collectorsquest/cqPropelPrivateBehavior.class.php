<?php

class cqPropelPrivateBehavior
{
  static protected $isActivated = false;
  static protected $wasActivated = false;

  public function updateCriteria($class, $myCriteria, $con = null)
  {
    $columnName = 'is_public';

    if (self::$isActivated)
    {
      if ($class == 'BaseCollectionPeer')
      {
        $do_join = true;
        $joins = $myCriteria->getJoins();

        if (is_array($joins) && !empty($joins))
        {
          foreach ($joins as $join)
          {
            $left = $join->getLeftColumn();
            $right = $join->getRightColumn();

            if ($left == CollectionPeer::COLLECTOR_ID && $right == CollectorPeer::ID)
            {
              $do_join = false;
            }
          }
        }

        if ($do_join)
        {
          $myCriteria->addJoin(CollectionPeer::COLLECTOR_ID, CollectorPeer::ID, Criteria::LEFT_JOIN);
        }

        $myCriteria->add(CollectorPeer::IS_PUBLIC, true);
        $myCriteria->addOr(CollectorPeer::IS_PUBLIC, null, Criteria::ISNULL);
      }
      else if ($class == 'BaseCollectiblePeer')
      {
        $do_collector_join = $do_collection_join = true;
        $joins = $myCriteria->getJoins();

        if (is_array($joins) && !empty($joins))
        {
          foreach ($joins as $join)
          {
            $left = $join->getLeftColumn();
            $right = $join->getRightColumn();

            if ($left == CollectionPeer::COLLECTOR_ID && $right == CollectorPeer::ID)
            {
              $do_collector_join = false;
            }
            else if ($left == CollectionItemPeer::COLLECTION_ID && $right == CollectionPeer::ID)
            {
              $do_collection_join = false;
            }
          }
        }

        if ($do_collection_join)
        {
          $myCriteria->addJoin(CollectionItemPeer::COLLECTION_ID, CollectionPeer::ID, Criteria::LEFT_JOIN);
        }
        if ($do_collector_join)
        {
          $myCriteria->addJoin(CollectionPeer::COLLECTOR_ID, CollectorPeer::ID, Criteria::LEFT_JOIN);
        }

        $myCriteria->add(CollectionPeer::IS_PUBLIC, true);
        $myCriteria->addOr(CollectionPeer::IS_PUBLIC, null, Criteria::ISNULL);
        $myCriteria->add(CollectorPeer::IS_PUBLIC, true);
        $myCriteria->addOr(CollectorPeer::IS_PUBLIC, null, Criteria::ISNULL);

        return;
      }

      $myCriteria->add(call_user_func(array($class, 'translateFieldName'), $columnName, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME), true);
      $myCriteria->addOr(call_user_func(array($class, 'translateFieldName'), $columnName, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME), null, Criteria::ISNULL);
    }
  }

  static public function restore()
  {
    (self::$wasActivated) ? self::enable() : self::disable();
  }

  static public function enable()
  {
    self::$wasActivated = self::$isActivated;
    self::$isActivated = true;
  }

  static public function disable()
  {
    self::$wasActivated = self::$isActivated;
    self::$isActivated = false;
  }

  static public function isActivated()
  {
    return self::$isActivated;
  }
}
