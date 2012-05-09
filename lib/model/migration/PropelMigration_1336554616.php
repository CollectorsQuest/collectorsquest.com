<?php

class PropelMigration_1336554616
{

  /**
   * @param PropelMigrationManager $manager
   */
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
      'propel'  => "
        SET FOREIGN_KEY_CHECKS = 0;

        -- ---------------------------------------------------------------------
        -- meta_tag
        -- ---------------------------------------------------------------------

        DROP TABLE IF EXISTS `meta_tag`;

        CREATE TABLE `meta_tag`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `url` VARCHAR(255) NOT NULL,
          `parameters` VARCHAR(255),
          `updated_at` DATETIME,
          `created_at` DATETIME,
          PRIMARY KEY (`id`),
          UNIQUE INDEX `meta_tag_U_1` (`url`, `parameters`)
        ) ENGINE=InnoDB;

        -- ---------------------------------------------------------------------
        -- meta_tag_i18n
        -- ---------------------------------------------------------------------

        DROP TABLE IF EXISTS `meta_tag_i18n`;

        CREATE TABLE `meta_tag_i18n`
        (
          `id` INTEGER NOT NULL,
          `title` VARCHAR(255),
          `description` VARCHAR(255) NOT NULL,
          `keywords` VARCHAR(255) NOT NULL,
          `culture` VARCHAR(7) NOT NULL,
          PRIMARY KEY (`id`,`culture`),
          CONSTRAINT `meta_tag_i18n_FK_1`
            FOREIGN KEY (`id`)
            REFERENCES `meta_tag` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
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
    return array(
      'propel'  => "
        DROP TABLE IF EXISTS `meta_tag`;
        DROP TABLE IF EXISTS `meta_tag_i18n`;
      "
    );
  }

}
