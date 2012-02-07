<?php

class PropelMigration_1328632411
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
        DROP TABLE IF EXISTS `collector_extra_property`;

        CREATE TABLE `collector_extra_property`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `collector_id` INTEGER NOT NULL,
          `property_name` VARCHAR(255) NOT NULL,
          `property_value` TEXT,
          PRIMARY KEY (`id`),
          INDEX `collector_extra_property_FI_1` (`collector_id`),
          CONSTRAINT `collector_extra_property_FK_1`
            FOREIGN KEY (`collector_id`)
            REFERENCES `collector` (`id`)
            ON DELETE CASCADE
        ) ENGINE=InnoDB;
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
        DROP TABLE IF EXISTS `collector_extra_property`;
      "
    );
  }
}
