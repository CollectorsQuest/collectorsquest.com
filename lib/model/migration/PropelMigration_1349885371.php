<?php

/**
 * Migration to add collector_rating, collector_collection_rating
 */
class PropelMigration_1349885371
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

        ALTER TABLE `collectible_rating` DROP FOREIGN KEY `collectible_rating_FK_2`;
        DROP INDEX `collectible_rating_FI_2` ON `collectible_rating`;
        ALTER TABLE `collectible_rating` CHANGE `collector_id` `sf_guard_user_id` INTEGER NOT NULL;
        CREATE INDEX `collectible_rating_FI_2` ON `collectible_rating` (`sf_guard_user_id`);

        ALTER TABLE `collectible_rating` ADD CONSTRAINT `collectible_rating_FK_2`
          FOREIGN KEY (`sf_guard_user_id`)
          REFERENCES `sf_guard_user` (`id`)
          ON DELETE CASCADE;

        ALTER TABLE `collection` ADD
        (
          `average_rating` FLOAT,
          `average_content_rating` FLOAT,
          `average_images_rating` FLOAT
        );

        ALTER TABLE `collector` ADD (
          `average_rating` FLOAT,
          `average_content_rating` FLOAT,
          `average_images_rating` FLOAT
        );

        ALTER TABLE `collector_collection` ADD
        (
          `average_rating` FLOAT,
          `average_content_rating` FLOAT,
          `average_images_rating` FLOAT
        );

        CREATE TABLE `collector_rating`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `dimension` VARCHAR(255) NOT NULL,
          `rating` INTEGER NOT NULL,
          `collector_id` INTEGER NOT NULL,
          `sf_guard_user_id` INTEGER NOT NULL,
          PRIMARY KEY (`id`),
          INDEX `collector_rating_FI_1` (`collector_id`),
          INDEX `collector_rating_FI_2` (`sf_guard_user_id`),
          CONSTRAINT `collector_rating_FK_1`
            FOREIGN KEY (`collector_id`)
            REFERENCES `collector` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `collector_rating_FK_2`
            FOREIGN KEY (`sf_guard_user_id`)
            REFERENCES `sf_guard_user` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB;

        CREATE TABLE `collection_rating`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `dimension` VARCHAR(255) NOT NULL,
          `rating` INTEGER NOT NULL,
          `collection_id` INTEGER NOT NULL,
          `sf_guard_user_id` INTEGER NOT NULL,
          PRIMARY KEY (`id`),
          INDEX `collection_rating_FI_1` (`collection_id`),
          INDEX `collection_rating_FI_2` (`sf_guard_user_id`),
          CONSTRAINT `collection_rating_FK_1`
            FOREIGN KEY (`collection_id`)
            REFERENCES `collection` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `collection_rating_FK_2`
            FOREIGN KEY (`sf_guard_user_id`)
            REFERENCES `sf_guard_user` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB;

        CREATE TABLE `collector_collection_rating`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `dimension` VARCHAR(255) NOT NULL,
          `rating` INTEGER NOT NULL,
          `collector_collection_id` INTEGER NOT NULL,
          `sf_guard_user_id` INTEGER NOT NULL,
          PRIMARY KEY (`id`),
          INDEX `collector_collection_rating_FI_1` (`collector_collection_id`),
          INDEX `collector_collection_rating_FI_2` (`sf_guard_user_id`),
          CONSTRAINT `collector_collection_rating_FK_1`
            FOREIGN KEY (`collector_collection_id`)
            REFERENCES `collector_collection` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `collector_collection_rating_FK_2`
            FOREIGN KEY (`sf_guard_user_id`)
            REFERENCES `sf_guard_user` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB;

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
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `collector_rating`;
        DROP TABLE IF EXISTS `collection_rating`;
        DROP TABLE IF EXISTS `collector_collection_rating`;

        ALTER TABLE `collectible_rating` DROP FOREIGN KEY `collectible_rating_FK_2`;
        DROP INDEX `collectible_rating_FI_2` ON `collectible_rating`;
        ALTER TABLE `collectible_rating` CHANGE `sf_guard_user_id` `collector_id` INTEGER NOT NULL;
        CREATE INDEX `collectible_rating_FI_2` ON `collectible_rating` (`collector_id`);

        ALTER TABLE `collectible_rating` ADD CONSTRAINT `collectible_rating_FK_2`
          FOREIGN KEY (`collector_id`)
          REFERENCES `collector` (`id`)
          ON DELETE CASCADE;

        ALTER TABLE `collection` DROP `average_rating`;
        ALTER TABLE `collection` DROP `average_content_rating`;
        ALTER TABLE `collection` DROP `average_images_rating`;
        ALTER TABLE `collector` DROP `average_rating`;
        ALTER TABLE `collector` DROP `average_content_rating`;
        ALTER TABLE `collector` DROP `average_images_rating`;

        ALTER TABLE `collector_collection` DROP `average_rating`;
        ALTER TABLE `collector_collection` DROP `average_content_rating`;
        ALTER TABLE `collector_collection` DROP `average_images_rating`;

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
