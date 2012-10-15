<?php

/**
 * Migration to add collectible_rating
 */
class PropelMigration_1349844479
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

        ALTER TABLE `collectible` ADD `average_rating` FLOAT AFTER `score`;
        ALTER TABLE `collectible` ADD `average_content_rating` FLOAT AFTER `average_rating`;
        ALTER TABLE `collectible` ADD `average_images_rating` FLOAT AFTER `average_content_rating`;

        DROP TABLE IF EXISTS `collectible_rating`;
        CREATE TABLE `collectible_rating`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `dimension` VARCHAR(255) NOT NULL,
          `rating` INTEGER NOT NULL,
          `collectible_id` INTEGER NOT NULL,
          `collector_id` INTEGER NOT NULL,
          PRIMARY KEY (`id`),
          INDEX `collectible_rating_FI_1` (`collectible_id`),
          INDEX `collectible_rating_FI_2` (`collector_id`),
          CONSTRAINT `collectible_rating_FK_1`
            FOREIGN KEY (`collectible_id`)
            REFERENCES `collectible` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `collectible_rating_FK_2`
            FOREIGN KEY (`collector_id`)
            REFERENCES `collector` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB;

        # This restores the fkey checks, after having unset them earlier
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
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `collectible_rating`;

        ALTER TABLE `collectible` DROP `average_rating`;
        ALTER TABLE `collectible` DROP `average_content_rating`;
        ALTER TABLE `collectible` DROP `average_images_rating`;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

}
