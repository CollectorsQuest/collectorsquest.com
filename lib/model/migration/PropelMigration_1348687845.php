<?php

/**
 * Add hidden and spam fields to comment and comment archive
 * Updated at: 2012-09-26 10:31PM
 */
class PropelMigration_1348501687
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
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `comment` ADD `is_hidden` TINYINT(1) NOT NULL AFTER `is_notify`;
ALTER TABLE `comment` ADD `is_spam` TINYINT(1) NOT NULL AFTER `is_hidden`;
ALTER TABLE `comment_archive` ADD `is_hidden` TINYINT(1) NOT NULL AFTER `is_notify`;
ALTER TABLE `comment_archive` ADD `is_spam` TINYINT(1) NOT NULL AFTER `is_hidden`;

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

ALTER TABLE `comment` DROP `is_hidden`;
ALTER TABLE `comment` DROP `is_spam`;
ALTER TABLE `comment_archive` DROP `is_hidden`;
ALTER TABLE `comment_archive` DROP `is_spam`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}