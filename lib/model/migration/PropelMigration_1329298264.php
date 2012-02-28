<?php

class PropelMigration_1329298264
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
    /** @var $q CollectionQuery */
    $q = CollectionQuery::create()
       ->orderBy('Id')
       ->setFormatter(ModelCriteria::FORMAT_ARRAY)
       ->select(array('Id'));

    $collection_ids = $q->find();

    foreach ($collection_ids as $collection_id)
    {
      $q = CollectionCollectibleQuery::create()
         ->filterByCollectionId($collection_id)
         ->orderByPosition(Criteria::ASC)
         ->orderByCreatedAt(Criteria::ASC);

      /** @var $collectibles CollectionCollectible[] */
      $collectibles = $q->find();

      foreach ($collectibles as $i => $collectible)
      {
        $collectible->setPosition($i+1);
      }
      $collectibles->save();
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
    return array(
      'propel' => "
        ALTER TABLE `collection_collectible` ADD `is_primary` BOOL NOT NULL DEFAULT 0 AFTER `position`;
        UPDATE `collection_collectible` SET `is_primary` = 1;
      "
    );
  }

  /**
   * @param  PropelMigrationManager  $manager
   */
  public function preDown(PropelMigrationManager $manager)
  {

  }

  /**
   * @param  PropelMigrationManager  $manager
   */
  public function postDown(PropelMigrationManager $manager)
  {

  }

  /**
   * Get the SQL statements for the Down migration
   *
   * @return array list of the SQL strings to execute for the Down migration
   *               the keys being the datasources
   */
  public function getDownSQL()
  {
    return array(
      'propel' => "ALTER TABLE `collection_collectible` DROP `is_primary`;"
    );
  }
}
