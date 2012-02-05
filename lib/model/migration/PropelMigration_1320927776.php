<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1320927776.
 */
class PropelMigration_1320927776
{
  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp($manager)
  {
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
