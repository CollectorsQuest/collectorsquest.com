<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1320912729.
 */
class PropelMigration_1320912729
{
  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp($manager)
  {
    /** @var $collectors Collector[] */
    $collectors = CollectorQuery::create()
                ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
                ->find();

    foreach ($collectors as $collector)
    {
      $name = $collector->getDisplayName();
      $collector->setDisplayName($name . ' ');
      $collector->save();

      $collector->setDisplayName($name);
      $collector->save();
    }

    /** @var $collections Collection[] */
    $collections = CollectionQuery::create()
                 ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
                 ->find();

    foreach ($collections as $collection)
    {
      $name = $collection->getName();
      $collection->setName($name . ' ');
      $collection->save();

      $collection->setName($name);
      $collection->save();
    }

    /** @var $collectibles Collectible[] */
    $collectibles = CollectibleQuery::create()
                  ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
                  ->find();

    foreach ($collectibles as $collectible)
    {
      $name = $collectible->getName();
      $collectible->setName($name . ' ');
      $collectible->save();

      $collectible->setName($name);
      $collectible->save();
    }
  }

  public function preDown($manager)
  {
    // add the pre-migration code here
  }

  public function postDown($manager)
  {
    // add the post-migration code here
  }

  /**
   * Get the SQL statements for the Up migration
   *
   * @return array list of the SQL strings to execute for the Up migration
   *               the keys being the datasources
   */
  public function getUpSQL()
  {
    return array(
      'propel' => "
        ALTER TABLE `collectible` CHANGE `name` `name` VARCHAR(255)  NOT NULL  DEFAULT '';
        ALTER TABLE `collection` CHANGE `name` `name` VARCHAR(255) NULL DEFAULT NULL;
        ALTER TABLE `collection` CHANGE `slug` `slug` VARCHAR(128) NOT NULL DEFAULT '';
        ALTER TABLE `collection_category` ADD `slug` VARCHAR(64) NULL DEFAULT NULL AFTER `name`;
        ALTER TABLE `collection_category` CHANGE `name` `name` VARCHAR(64)  NOT NULL  DEFAULT '';
        ALTER TABLE `collection_category` CHANGE `slug` `slug` VARCHAR(64)  NOT NULL  DEFAULT '';
      ",
    );
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
