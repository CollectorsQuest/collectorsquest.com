<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1333387771.
 * Generated on 2012-04-02 13:29:31 by root
 */
class PropelMigration_1333387771
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

	public function postUp($manager)
	{
    $content_category_root = new ContentCategory();
    $content_category_root->makeRoot();
    $content_category_root->setName('Root');
    $content_category_root->save();
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

DROP TABLE IF EXISTS `content_category`;
CREATE TABLE `content_category`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`collection_category_id` SMALLINT,
	`name` VARCHAR(64) NOT NULL,
	`slug` VARCHAR(64) NOT NULL,
	`description` TEXT,
	`tree_left` INTEGER,
	`tree_right` INTEGER,
	`tree_level` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `content_category_FI_1` (`collection_category_id`),
	CONSTRAINT `content_category_FK_1`
		FOREIGN KEY (`collection_category_id`)
		REFERENCES `collection_category` (`id`)
		ON DELETE SET NULL
) ENGINE=InnoDB;

ALTER TABLE `collection` ADD `content_category_id` INTEGER AFTER `collection_category_id`;
CREATE INDEX `collection_FI_2` ON `collection` (`content_category_id`);
ALTER TABLE `collection` ADD CONSTRAINT `collection_FK_2`
	FOREIGN KEY (`content_category_id`)
	REFERENCES `content_category` (`id`)
	ON DELETE SET NULL;

ALTER TABLE `collector_collection` ADD `content_category_id` INTEGER AFTER `collection_category_id`;
CREATE INDEX `collector_collection_FI_4` ON `collector_collection` (`content_category_id`);
ALTER TABLE `collector_collection` ADD CONSTRAINT `collector_collection_FK_4`
	FOREIGN KEY (`content_category_id`)
	REFERENCES `content_category` (`id`)
	ON DELETE SET NULL;

ALTER TABLE `collector_interview` ADD `content_category_id` INTEGER AFTER `collection_category_id`;
CREATE INDEX `collection_FI_5` ON `collector_interview` (`content_category_id`);
ALTER TABLE `collector_interview` ADD CONSTRAINT `collection_FK_5`
	FOREIGN KEY (`content_category_id`)
	REFERENCES `content_category` (`id`)
	ON DELETE SET NULL;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',


  'archive' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `collection_archive` ADD `content_category_id` INTEGER AFTER `collection_category_id`;
CREATE INDEX `collection_archive_I_4` ON `collection_archive` (`content_category_id`);

ALTER TABLE `collector_collection_archive` ADD `content_category_id` INTEGER AFTER `collection_category_id` ;
CREATE INDEX `collector_collection_archive_I_5` ON `collector_collection_archive` (`content_category_id`);

DROP TABLE IF EXISTS `content_category_archive`;
CREATE TABLE `content_category_archive`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`collection_category_id` SMALLINT,
	`name` VARCHAR(64) NOT NULL,
	`slug` VARCHAR(64) NOT NULL,
	`description` TEXT,
	`tree_left` INTEGER,
	`tree_right` INTEGER,
	`tree_level` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `content_category_I_1` (`collection_category_id`)
) ENGINE=InnoDB;

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

ALTER TABLE `collection` DROP INDEX `collection_FI_2`;
ALTER TABLE `collection` DROP FOREIGN KEY `collection_FK_2`;
ALTER TABLE `collection` DROP `content_category_id`;

ALTER TABLE `collector_collection` DROP INDEX `collector_collection_FI_4`;
ALTER TABLE `collector_collection` DROP FOREIGN KEY `collector_collection_FK_4`;
ALTER TABLE `collector_collection` DROP `content_category_id`;

ALTER TABLE `collector_interview` DROP INDEX `collection_FI_5`;
ALTER TABLE `collector_interview` DROP FOREIGN KEY `collection_FK_5`;
ALTER TABLE `collector_interview` DROP `content_category_id`;

DROP TABLE IF EXISTS `content_category`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',


  'archive' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `collection_archive` DROP INDEX `collection_archive_I_4`;
ALTER TABLE `collection_archive` DROP `content_category_id`;

ALTER TABLE `collector_collection_archive` DROP INDEX `collector_collection_archive_I_5`;
ALTER TABLE `collector_collection_archive` DROP `content_category_id`;

DROP TABLE IF EXISTS `content_category_archive`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}