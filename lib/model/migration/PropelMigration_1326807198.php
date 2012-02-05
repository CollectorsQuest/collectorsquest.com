<?php

class PropelMigration_1326807198
{
  /**
   * @param  PropelMigrationManager  $manager
   */
  public function preUp(PropelMigrationManager $manager)
  {

  }

  /**
   * @param  PropelMigrationManager  $manager
   */
  public function postUp(PropelMigrationManager $manager)
  {
    $q = CollectorQuery::create()
       ->filterByDeletedAt(null, Criteria::ISNOTNULL)
       ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $collectors Collector[] */
    $collectors = $q->find();
    foreach ($collectors as $collector)
    {
      $collector->delete();
    }

    $q = CollectionQuery::create()
       ->filterByDeletedAt(null, Criteria::ISNOTNULL)
       ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $collections Collection[] */
    $collections = $q->find();
    foreach ($collections as $collection)
    {
      $collection->delete();
    }

    $q = CollectibleQuery::create()
       ->filterByDeletedAt(null, Criteria::ISNOTNULL)
       ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $collectibles Collectible[] */
    $collectibles = $q->find();
    foreach ($collectibles as $collectible)
    {
      $collectible->delete();
    }
  }

  /**
   * Get the SQL statements for the Up migration
   *
   * @return array list of the SQL strings to execute for the Up migration
   *               the keys being the datasources
   */
  public function getUpSQL()
  {
    return array();
  }

  /**
   * Get the SQL statements for the Down migration
   *
   * @return array list of the SQL strings to execute for the Down migration
   *               the keys being the datasources
   */
  public function getDownSQL()
  {
    return array();
  }
}
