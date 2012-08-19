<?php

/**
 * Update Collector and Collectible tables
 */
class PropelMigration_1345389228
{

  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp($manager)
  {
    // add the post-migration code here
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
    return array (
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `collector` DROP `max_collectibles_for_sale`;
        ALTER TABLE `collector` DROP `items_allowed`;
        ALTER TABLE `collectible` ADD `is_public` TINYINT(1) DEFAULT 1 AFTER `is_name_automatic`;

        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'archive' => '
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `collector_archive` DROP `max_collectibles_for_sale`;
        ALTER TABLE `collector_archive` DROP `items_allowed`;
        ALTER TABLE `collectible_archive` ADD `is_public` TINYINT(1) DEFAULT 1 AFTER `is_name_automatic`;

        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
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
    return array (
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }
}
