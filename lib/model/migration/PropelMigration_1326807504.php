<?php

class PropelMigration_1326807504
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
        ALTER TABLE `collector` DROP `deleted_at`;
        ALTER TABLE `collection` DROP `deleted_at`;
        ALTER TABLE `collectible` DROP `deleted_at`;
        ALTER TABLE `collectible_for_sale` DROP `deleted_at`;
        ALTER TABLE `collectible_offer` DROP `deleted_at`;
      ",
      'archive' => "
        UPDATE `collector_archive` SET `archived_at` = `deleted_at` WHERE `archived_at` IS NULL;
        ALTER TABLE `collector_archive` DROP `deleted_at`;

        UPDATE `collection_archive` SET `archived_at` = `deleted_at` WHERE `archived_at` IS NULL;
        ALTER TABLE `collection_archive` DROP `deleted_at`;

        UPDATE `collectible_archive` SET `archived_at` = `deleted_at` WHERE `archived_at` IS NULL;
        ALTER TABLE `collectible_archive` DROP `deleted_at`;

        UPDATE `collectible_for_sale_archive` SET `archived_at` = `deleted_at` WHERE `archived_at` IS NULL;
        ALTER TABLE `collectible_for_sale_archive` DROP `deleted_at`;

        UPDATE `collectible_offer_archive` SET `archived_at` = `deleted_at` WHERE `archived_at` IS NULL;
        ALTER TABLE `collectible_offer_archive` DROP `deleted_at`;
      "
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
