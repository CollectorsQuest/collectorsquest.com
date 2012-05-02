<?php

class PropelMigration_1335953255
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
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
        ALTER TABLE `multimedia` ADD `slug` VARCHAR(128) NOT NULL AFTER `name`;
        ALTER TABLE `multimedia` ADD `position` SMALLINT UNSIGNED DEFAULT 65535 AFTER `is_primary`;
        ALTER TABLE `multimedia` CHANGE `type` `type` ENUM('image','video','pdf')  NOT NULL  DEFAULT 'image';
      ",
      'archive' => "
        ALTER TABLE `multimedia_archive` ADD `slug` VARCHAR(128) NOT NULL AFTER `name`;
        ALTER TABLE `multimedia_archive` ADD `position` SMALLINT UNSIGNED DEFAULT 65535 AFTER `is_primary`;
        ALTER TABLE `multimedia_archive` CHANGE `type` `type` ENUM('image','video','pdf')  NOT NULL  DEFAULT 'image';
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
        ALTER TABLE `multimedia` DROP `slug`;
        ALTER TABLE `multimedia` DROP `position`;
        ALTER TABLE `multimedia` CHANGE `type` `type` ENUM('image','video')  NOT NULL  DEFAULT 'image';
      ",
      'archive' => "
        ALTER TABLE `multimedia_archive` DROP `slug`;
        ALTER TABLE `multimedia_archive` DROP `position`;
        ALTER TABLE `multimedia_archive` CHANGE `type` `type` ENUM('image','video')  NOT NULL  DEFAULT 'image';
      "
    );
  }

}
