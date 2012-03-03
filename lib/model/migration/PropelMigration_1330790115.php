<?php

require_once __DIR__ .'/../../../plugins/iceLibsPlugin/lib/vendor/FileCsv.class.php';

/**
 * Migration for fixing collector_profile_extra_property
 */
class PropelMigration_1330790115
{

	public function preUp(PropelMigrationManager $manager)
	{
    $csv = new FileCsv(sfConfig::get('sf_data_dir').'/migrations/1330790115_profile_ids.csv');
    $rows = $csv->connect();

    /* @var $pdo PropelPDO */
    $pdo = $manager->getPdoConnection('propel');

    $pdo->exec("
      ALTER TABLE `collector_profile_extra_property`
      ADD `is_migrated` BOOL NULL DEFAULT '0' AFTER `property_value`;
    ");

    $sql = 'UPDATE `collector_profile_extra_property`
               SET `collector_profile_collector_id` = ?, `is_migrated` = 1
             WHERE `collector_profile_collector_id` = ? AND `is_migrated` = 0;';
    $stmt = $pdo->prepare($sql);

    foreach ($rows as $row)
    {
      if ($row['profile_id'] === $row['collector_id']) {
        continue;
      }

      try
      {
        $stmt->execute(array($row['collector_id'], $row['profile_id']));
      }
      catch (PDOException $e)
      {
        echo "Collector with ID ", $row['collector_id'], " was probably deleted!\n";
      }
    }
	}

	public function postUp(PropelMigrationManager $manager)
	{
    /* @var $pdo PropelPDO */
    $pdo = $manager->getPdoConnection('propel');
    $pdo->exec("ALTER TABLE `collector_profile_extra_property` DROP `is_migrated`;");
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
		return array();
	}

	/**
	 * Get the SQL statements for the Down migration
	 *
	 * @return array list of the SQL strings to execute for the Down migration
	 *               the keys being the datasources
	 */
	public function getDownSQL()
	{
		return array();
	}

}
