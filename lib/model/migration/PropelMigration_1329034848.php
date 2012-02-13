<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1328786453.
 * Generated on 2012-02-09 06:20:53 by root
 */
class PropelMigration_1329034848
{

  public function preUp(PropelMigrationManager $manager)
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
      'propel'  => '
        ALTER TABLE `collector_email`
        ADD UNIQUE INDEX `unq_collector_email` (`collector_id`, `email`)
        ',
    );
  }

  public function postUp($manager)
  {
    // add the post-migration code here
  }

  public function preDown($manager)
  {
    return false; //No way back
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
      'propel'  => '
        ALTER TABLE `collector_email`
        DROP UNIQUE INDEX `unq_collector_email`
        ',
    );
  }

  public function postDown($manager)
  {
    // add the post-migration code here
  }

}
