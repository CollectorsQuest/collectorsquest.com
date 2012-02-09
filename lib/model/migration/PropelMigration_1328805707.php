<?php

class PropelMigration_1328805707
{
  /**
   * @param  PropelMigrationManager  $manager
   */
  public function preUp(PropelMigrationManager $manager)
  {
    /** @var $pdo PDO */
    $pdo = $manager->getPdoConnection('propel');

    $sql = "INSERT INTO `collector_collection` SELECT * FROM `collection`;";
    $pdo->prepare($sql)->execute();

    $sql = "INSERT IGNORE INTO `collection_collectible`
            SELECT `collection_id`, `id`, `score`, `position`, `updated_at`, `created_at` FROM `collectible`;";
    $pdo->prepare($sql)->execute();
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
        ALTER TABLE `collection` DROP FOREIGN KEY `collection_FK_2`;
        ALTER TABLE `collection` DROP INDEX `collection_FI_2`;
        ALTER TABLE `collection` DROP INDEX `collection_FI_1`;
        ALTER TABLE `collection` DROP `collector_id`;

        ALTER TABLE `collectible` DROP FOREIGN KEY `collectible_FK_2`;
        ALTER TABLE `collectible` DROP INDEX `collectible_FI_2`;
        ALTER TABLE `collectible` DROP `collection_id`;
        ALTER TABLE `collectible` DROP `position`;
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
    return array();
  }
}
