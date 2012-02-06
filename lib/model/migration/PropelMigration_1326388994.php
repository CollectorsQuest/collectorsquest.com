<?php

class PropelMigration_1326388994
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
    return array('propel' => "
      ALTER TABLE `collector` ADD `locale` VARCHAR(5) NOT NULL DEFAULT 'en_US' AFTER `company`;

      DROP TABLE IF EXISTS `collector_email`;
      CREATE TABLE `collector_email`
      (
        `id` INTEGER NOT NULL AUTO_INCREMENT,
        `collector_id` INTEGER,
        `email` VARCHAR(128),
        `hash` VARCHAR(40) NOT NULL,
        `salt` VARCHAR(32) NOT NULL,
        `is_verified` TINYINT(1) DEFAULT 0,
        `updated_at` DATETIME,
        `created_at` DATETIME,
        PRIMARY KEY (`id`),
        INDEX `collector_email_I_1` (`email`),
        INDEX `collector_email_FI_1` (`collector_id`),
        CONSTRAINT `collector_email_FK_1`
          FOREIGN KEY (`collector_id`)
          REFERENCES `collector` (`id`)
      ) ENGINE=InnoDB;

      DROP TABLE IF EXISTS `spam_control`;

      CREATE TABLE `spam_control`
      (
      	`id` INTEGER NOT NULL AUTO_INCREMENT,
      	`field` ENUM('email','phone','ip','regex','session') DEFAULT 'regex' NOT NULL,
      	`value` VARCHAR(64) NOT NULL,
      	`credentials` SET('read', 'create', 'edit', 'comment') DEFAULT 'read' NOT NULL,
      	`is_banned` BOOL DEFAULT 0 NOT NULL,
      	`is_throttled` BOOL DEFAULT 0 NOT NULL,
      	`created_at` DATETIME,
      	`updated_at` DATETIME,
      	PRIMARY KEY (`id`),
      	UNIQUE INDEX `spam_control_U_1` (`field`, `value`)
      ) ENGINE=InnoDB;
    ");
  }

  /**
   * Get the SQL statements for the Down migration
   *
   * @return array list of the SQL strings to execute for the Down migration
   *               the keys being the datasources
   */
  public function getDownSQL()
  {
    return array('propel' => "
      ALTER TABLE `collector` DROP `locale`;

      DROP TABLE IF EXISTS `collector_email`;
      DROP TABLE IF EXISTS `spam_control`;
    ");
  }
}
