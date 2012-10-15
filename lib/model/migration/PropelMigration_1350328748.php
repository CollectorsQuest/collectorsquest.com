<?php

/**
 * Migration to add the rating fields to the *_archive tables
 */
class PropelMigration_1350328748
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
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'archive' => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `collectible_archive` ADD `average_rating` FLOAT AFTER `score`;
        ALTER TABLE `collectible_archive` ADD `average_content_rating` FLOAT AFTER `average_rating`;
        ALTER TABLE `collectible_archive` ADD `average_images_rating` FLOAT AFTER `average_content_rating`;

        ALTER TABLE `collection_archive` ADD
        (
          `average_rating` FLOAT,
          `average_content_rating` FLOAT,
          `average_images_rating` FLOAT
        );

        ALTER TABLE `collector_archive` ADD (
          `average_rating` FLOAT,
          `average_content_rating` FLOAT,
          `average_images_rating` FLOAT
        );

        ALTER TABLE `collector_collection_archive` ADD
        (
          `average_rating` FLOAT,
          `average_content_rating` FLOAT,
          `average_images_rating` FLOAT
        );

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
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
      'archive' => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `collection_archive` DROP `average_rating`;
        ALTER TABLE `collection_archive` DROP `average_content_rating`;
        ALTER TABLE `collection_archive` DROP `average_images_rating`;

        ALTER TABLE `collector_archive` DROP `average_rating`;
        ALTER TABLE `collector_archive` DROP `average_content_rating`;
        ALTER TABLE `collector_archive` DROP `average_images_rating`;

        ALTER TABLE `collector_collection_archive` DROP `average_rating`;
        ALTER TABLE `collector_collection_archive` DROP `average_content_rating`;
        ALTER TABLE `collector_collection_archive` DROP `average_images_rating`;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
  }

}
