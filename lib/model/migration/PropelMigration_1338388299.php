<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1338388299.
 * Generated on 2012-05-30 17:31:39 by root
 */
class PropelMigration_1338388299
{

	public function preUp($manager)
	{
		// remove a duplicate FB id user
    CollectorQuery::create()
      ->filterByUsername('fb555733876')
      ->delete();

    $duplicate_display_names = CollectorQuery::create()
      ->groupBy('DisplayName')
      ->withColumn('COUNT(Collector.Id)', 'count')
      ->having('count > 1')
      ->select(array('Id', 'DisplayName'))
      ->find()->toKeyValue('Id', 'DisplayName');

    foreach ($duplicate_display_names as $display_name)
    {
      $collectors = CollectorQuery::create()
        ->filterByDisplayName($display_name)
        ->find();

      while ($collector = $collectors->getNext())
      {
        $collector->setDisplayName($collector->getDisplayName().$collector->getId());
      }
      $collectors->save();
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

DROP INDEX `facebook_id` ON `collector`;
DROP INDEX `collector_FI_1` ON `collector`;
DROP INDEX `collector_U_1` ON `collector`;
DROP INDEX `collector_U_2` ON `collector`;

CREATE UNIQUE INDEX `collector_U_1` ON `collector` (`graph_id`);
CREATE UNIQUE INDEX `collector_U_2` ON `collector` (`facebook_id`);
CREATE UNIQUE INDEX `collector_U_3` ON `collector` (`username`);
CREATE UNIQUE INDEX `collector_U_4` ON `collector` (`display_name`);
CREATE UNIQUE INDEX `collector_U_5` ON `collector` (`slug`);
CREATE UNIQUE INDEX `collector_U_6` ON `collector` (`email`);

# This restores the fkey checks, after having unset them earlier
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

DROP INDEX `collector_U_1` ON `collector`;
DROP INDEX `collector_U_2` ON `collector`;
DROP INDEX `collector_U_3` ON `collector`;
DROP INDEX `collector_U_4` ON `collector`;
DROP INDEX `collector_U_5` ON `collector`;
DROP INDEX `collector_U_6` ON `collector`;

CREATE INDEX `facebook_id` ON `collector` (`facebook_id`);
CREATE INDEX `collector_FI_1` ON `collector` (`session_id`);
CREATE UNIQUE INDEX `collector_U_1` ON `collector` (`email`);
CREATE UNIQUE INDEX `collector_U_2` ON `collector` (`graph_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}
