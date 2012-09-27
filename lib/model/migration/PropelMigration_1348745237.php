<?php

/**
 * Add hidden and spam fields to comment and comment archive
 * Updated at: 2012-09-26 10:31PM
 */
class PropelMigration_1348745237
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
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `content_category` ADD `name_singular` VARCHAR(64) NULL DEFAULT NULL AFTER `name`;

        DROP TABLE IF EXISTS `content_category_extra_property`;
        CREATE TABLE `content_category_extra_property`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `property_name` VARCHAR(255) NOT NULL,
          `property_value` TEXT,
          `content_category_id` INTEGER NOT NULL,
          PRIMARY KEY (`id`),
          INDEX `content_category_extra_property_FI_1` (`content_category_id`),
          CONSTRAINT `content_category_extra_property_FK_1`
            FOREIGN KEY (`content_category_id`)
            REFERENCES `content_category` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB;

        # This restores the fkey checks, after having unset them earlier
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

        ALTER TABLE `content_category` DROP `name_singular`;
        DROP TABLE IF EXISTS `content_category_extra_property`;

        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
  }

}
