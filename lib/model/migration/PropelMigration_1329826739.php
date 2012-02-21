<?php

/**
 * Updates for user registration
 * Generated on 2012-02-21 07:18:59 by root
 */
class PropelMigration_1329826739
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
UPDATE `collector` SET `has_completed_registration` = 1;

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
);
	}

}