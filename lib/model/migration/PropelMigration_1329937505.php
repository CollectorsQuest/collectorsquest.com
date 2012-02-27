<?php

/**
 * Migration for proper 1:1 relation between collector and collector_profile
 *
 * Generated on 2012-02-22 17:05:05 by root
 */
class PropelMigration_1329937505
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


ALTER TABLE `collector_profile_extra_property` DROP FOREIGN KEY `collector_profile_extra_property_FK_1`;

DROP INDEX `unq_collector_profile_property` ON `collector_profile_extra_property`;

DROP INDEX `collector_profile_extra_property_FI_1` ON `collector_profile_extra_property`;

ALTER TABLE `collector_profile_extra_property` CHANGE `collector_profile_id` `collector_profile_collector_id` INTEGER NOT NULL;

CREATE INDEX `collector_profile_extra_property_FI_1` ON `collector_profile_extra_property` (`collector_profile_collector_id`);


# We need to remove AUTO_INCREMENT property from ID before dropping the PK
ALTER TABLE `collector_profile` CHANGE `id` `id` INTEGER NOT NULL;

ALTER TABLE `collector_profile` DROP PRIMARY KEY;

ALTER TABLE `collector_profile` DROP FOREIGN KEY `fk_collector`;

DROP INDEX `collector_profile_FI_1` ON `collector_profile`;

ALTER TABLE `collector_profile` DROP `id`;

ALTER TABLE `collector_profile` ADD PRIMARY KEY (`collector_id`);

ALTER TABLE `collector_profile` ADD CONSTRAINT `collector_profile_FK_1`
	FOREIGN KEY (`collector_id`)
	REFERENCES `collector` (`id`)
	ON DELETE CASCADE;


# This constraint must be added after collector_id has been set as primary key
ALTER TABLE `collector_profile_extra_property` ADD CONSTRAINT `collector_profile_extra_property_FK_1`
	FOREIGN KEY (`collector_profile_collector_id`)
	REFERENCES `collector_profile` (`collector_id`)
	ON DELETE CASCADE;


# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',


  'archive' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `collector_profile_archive` DROP PRIMARY KEY;

DROP INDEX `collector_profile_archive_I_1` ON `collector_profile_archive`;

DROP INDEX `collector_profile_archive_I_2` ON `collector_profile_archive`;

ALTER TABLE `collector_profile_archive` CHANGE `collector_id` `collector_id` INTEGER NOT NULL;

ALTER TABLE `collector_profile_archive` DROP `id`;

ALTER TABLE `collector_profile_archive` ADD PRIMARY KEY (`collector_id`);

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

# These indexes must be dropped before we start altering the foreign table
ALTER TABLE `collector_profile_extra_property` DROP FOREIGN KEY `collector_profile_extra_property_FK_1`;

DROP INDEX `collector_profile_extra_property_FI_1` ON `collector_profile_extra_property`;


# We need to remove AUTO_INCREMENT property from COLLECTOR_ID before dropping the PK
ALTER TABLE `collector_profile` CHANGE `collector_id` `collector_id` INTEGER NOT NULL;

ALTER TABLE `collector_profile` DROP FOREIGN KEY `collector_profile_FK_1`;

ALTER TABLE `collector_profile` DROP PRIMARY KEY;

ALTER TABLE `collector_profile` ADD `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

CREATE UNIQUE INDEX `collector_profile_FI_1` ON `collector_profile` (`collector_id`);

ALTER TABLE `collector_profile` ADD CONSTRAINT `fk_collector`
	FOREIGN KEY (`collector_id`)
	REFERENCES `collector` (`id`)
	ON UPDATE CASCADE
	ON DELETE CASCADE;



ALTER TABLE `collector_profile_extra_property` CHANGE `collector_profile_collector_id` `collector_profile_id` INTEGER NOT NULL;

CREATE INDEX `collector_profile_extra_property_FI_1` ON `collector_profile_extra_property` (`collector_profile_id`);

CREATE UNIQUE INDEX `unq_collector_profile_property` ON `collector_profile_extra_property` (`collector_profile_id`,`property_name`);

ALTER TABLE `collector_profile_extra_property` ADD CONSTRAINT `collector_profile_extra_property_FK_1`
	FOREIGN KEY (`collector_profile_id`)
	REFERENCES `collector_profile` (`id`)
	ON DELETE CASCADE;


# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',


  'archive' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `collector_profile_archive` DROP PRIMARY KEY;

ALTER TABLE `collector_profile_archive` CHANGE `collector_id` `collector_id` INTEGER;

ALTER TABLE `collector_profile_archive` ADD
(
	`id` INTEGER NOT NULL
);

# The IDs do not really matter, but they should be set to /something/
# Archivable behavior does not use them to populate a restored object
UPDATE `collector_profile_archive` SET `id` = `collector_id`;

ALTER TABLE `collector_profile_archive` ADD PRIMARY KEY (`id`);

CREATE INDEX `collector_profile_archive_I_1` ON `collector_profile_archive` (`id`);

CREATE INDEX `collector_profile_archive_I_2` ON `collector_profile_archive` (`collector_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}