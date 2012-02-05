<?php

class PropelMigration_1328285996
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
        ALTER TABLE `collectible` ADD `batch_hash` VARCHAR(32)  NULL  DEFAULT NULL  AFTER `num_comments`;
        ALTER TABLE `collectible` ADD INDEX `collectible_I_1` (`batch_hash`);
      ",
      'archive' => "
        ALTER TABLE `collectible_archive` ADD `batch_hash` VARCHAR(32)  NULL  DEFAULT NULL  AFTER `num_comments`;
        ALTER TABLE `collectible_archive` ADD INDEX `collectible_I_1` (`batch_hash`);
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
      'propel' => "
        ALTER TABLE `collectible` DROP `batch_hash`;
        ALTER TABLE `collectible` DROP INDEX `collectible_I_1`;
      ",
      'archive' => "
        ALTER TABLE `collectible_archive` DROP `batch_hash`;
        ALTER TABLE `collectible_archive` DROP INDEX `collectible_I_1`;
      "
    );
  }
}
