<?php

/**
 * Updates for user registration
 */
class PropelMigration_1330861697
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
		return array (
      'propel' => '
        ALTER TABLE `collector` ADD `has_completed_registration` BOOL NOT NULL DEFAULT 0 AFTER `is_public`;

        UPDATE `collector`, `collector_profile`
           SET collector.has_completed_registration = 1
         WHERE collector.id = collector_profile.collector_id
           AND collector_profile.country_iso3166 IS NOT NULL;
      ',
      'archive' => '
        ALTER TABLE `collector_archive` ADD `has_completed_registration` BOOL NOT NULL DEFAULT 0 AFTER `is_public`;

        UPDATE `collector_archive`, `collector_profile_archive`
           SET collector_archive.has_completed_registration = 1
         WHERE collector_archive.id = collector_profile_archive.collector_id
           AND collector_profile_archive.country_iso3166 IS NOT NULL;
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
        ALTER TABLE `collector` DROP `has_completed_registration`;
      ',
      'archive' => '
        ALTER TABLE `collector_archive` DROP `has_completed_registration`;
      ',
    );
	}

}
