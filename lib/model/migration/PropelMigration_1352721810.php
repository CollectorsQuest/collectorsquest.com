<?php

/**
 * Add Badge and BadgeReference tables
 */
class PropelMigration_1352721810
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
    return array(
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;

        CREATE TABLE `badge`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(255) NOT NULL,
          `slug` VARCHAR(255) NOT NULL,
          `tier` TINYINT,
          `parent_model` VARCHAR(50),
          `parent_model_id` INTEGER,
          PRIMARY KEY (`id`),
          INDEX `badge_parent` (`parent_model`, `parent_model_id`)
        ) ENGINE=InnoDB;

        CREATE TABLE `badge_reference`
        (
          `model` VARCHAR(50) NOT NULL,
          `model_id` INTEGER NOT NULL,
          `badge_id` INTEGER NOT NULL,
          PRIMARY KEY (`model`,`model_id`,`badge_id`),
          INDEX `object_reference` (`model`, `model_id`),
          INDEX `badge_reference_FI_1` (`badge_id`),
          CONSTRAINT `badge_reference_FK_1`
            FOREIGN KEY (`badge_id`)
            REFERENCES `badge` (`id`)
        ) ENGINE=InnoDB;

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
    return array(
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `badge`;
        DROP TABLE IF EXISTS `badge_reference`;

        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
  }

}