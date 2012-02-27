<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1329991662.
 * Generated on 2012-02-23 05:07:42 by root
 */
class PropelMigration_1329991662
{

  protected $collector_type_value_set = array(
      0 => 'casual',
      1 => 'occasional',
      2 => 'serious',
      3 => 'obsessive',
      4 => 'expert',
  );

	public function preUp($manager)
	{
    foreach (array('propel' => 'collector_profile', 'archive' => 'collector_profile_archive') as $connection_name => $table_name)
    {
      foreach ($this->collector_type_value_set as $new_value => $original_value)
      {
        /* @var $pdo PDO */
        $pdo = $manager->getPdoConnection($connection_name);

        $sql = "UPDATE {$table_name}
                SET `collector_type` = '{$new_value}'
                WHERE `collector_type` = '{$original_value}'";
        $pdo->exec($sql);
      }
    }
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
    foreach (array('propel' => 'collector_profile', 'archive' => 'collector_profile_archive') as $connection_name => $table_name)
    {
      /* @var $pdo PDO */
      $pdo = $manager->getPdoConnection($connection_name);

      foreach ($this->collector_type_value_set as $new_value => $original_value)
      {
        $sql = "UPDATE {$table_name}
                SET `collector_type` = '{$original_value}'
                WHERE `collector_type` = '{$new_value}'";
        $pdo->exec($sql);
      }
    }
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

ALTER TABLE `collector_profile` CHANGE `collector_type` `collector_type` TINYINT DEFAULT 0 NOT NULL;

',

  'archive' => '

ALTER TABLE `collector_profile_archive` CHANGE `collector_type` `collector_type` TINYINT DEFAULT 0 NOT NULL;

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

ALTER TABLE `collector_profile` CHANGE `collector_type` `collector_type` VARCHAR(64) DEFAULT \'casual\' NOT NULL;

',

  'archive' => '

ALTER TABLE `collector_profile_archive` CHANGE `collector_type` `collector_type` VARCHAR(64) DEFAULT \'casual\' NOT NULL;

',
);
	}

}