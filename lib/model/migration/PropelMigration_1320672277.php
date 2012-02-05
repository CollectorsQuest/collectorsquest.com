<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1320672277.
 * Generated on 2011-11-07 08:24:37 by root
 */
class PropelMigration_1320672277
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
      'propel' => "ALTER TABLE `tag` ADD `slug` VARCHAR(255) NOT NULL DEFAULT '' AFTER `name`;",
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
      'propel' => "ALTER TABLE `tag` DROP IF EXISTS `slug`;",
    );
  }
}
