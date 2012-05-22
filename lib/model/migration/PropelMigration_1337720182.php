<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1337720182.
 * Generated on 2012-05-22 16:56:22 by root
 */
class PropelMigration_1337720182
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


ALTER TABLE `comment` DROP FOREIGN KEY `comment_FK_2`;
ALTER TABLE `comment` ADD CONSTRAINT `comment_FK_2`
	FOREIGN KEY (`collectible_id`)
	REFERENCES `collectible` (`id`)
	ON DELETE CASCADE;

ALTER TABLE `comment` CHANGE `collection_id` `collection_id` INTEGER;
ALTER TABLE `comment` ADD `model` CHAR(64) NOT NULL AFTER `collector_id`;
ALTER TABLE `comment` ADD `model_id` INTEGER NOT NULL AFTER `model`;
ALTER TABLE `comment` ADD `is_notify` TINYINT(1) DEFAULT 0 NOT NULL AFTER `author_url`;

CREATE INDEX `comment_model_object` ON `comment` (`model`,`model_id`);


ALTER TABLE `comment_archive` ADD `model` CHAR(64) NOT NULL AFTER `collector_id`;
ALTER TABLE `comment_archive` ADD `model_id` INTEGER NOT NULL AFTER `model`;


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


ALTER TABLE `comment` DROP FOREIGN KEY `comment_FK_2`;
ALTER TABLE `comment` ADD CONSTRAINT `comment_FK_2`
	FOREIGN KEY (`collectible_id`)
	REFERENCES `collectible` (`id`)
	ON DELETE SET NULL;

DROP INDEX `comment_model_object` ON `comment`;
ALTER TABLE `comment` CHANGE `collection_id` `collection_id` INTEGER NOT NULL;
ALTER TABLE `comment` DROP `model`;
ALTER TABLE `comment` DROP `model_id`;
ALTER TABLE `comment` DROP `is_notify`;


ALTER TABLE `comment_archive` DROP `model`;
ALTER TABLE `comment_archive` DROP `model_id`;


# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}