<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1330364834.
 * Generated on 2012-02-27 12:47:14 by root
 */
class PropelMigration_1330364834
{

	public function preUp($manager)
	{
    //
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
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `collector_geocache` DROP `country`;

CREATE INDEX `collector_geocache_FI_2` ON `collector_geocache` (`country_iso3166`);

ALTER TABLE `collector_geocache` ADD CONSTRAINT `collector_geocache_FK_2`
	FOREIGN KEY (`country_iso3166`)
	REFERENCES `ice_geo_country` (`iso3166`);


ALTER TABLE `collector_profile` CHANGE `country_iso3166` `country_iso3166` CHAR(2);

UPDATE collector_profile
SET collector_profile.country_iso3166 = collector_profile.country
WHERE collector_profile.country_iso3166 IS NULL
  AND length(collector_profile.country) = 2;

ALTER TABLE `collector_profile` DROP `country`;

CREATE INDEX `collector_profile_FI_2` ON `collector_profile` (`country_iso3166`);

ALTER TABLE `collector_profile` ADD CONSTRAINT `collector_profile_FK_2`
	FOREIGN KEY (`country_iso3166`)
	REFERENCES `ice_geo_country` (`iso3166`);

SET FOREIGN_KEY_CHECKS = 1;
',


  'archive' => '
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `collector_geocache_archive` DROP `country`;

ALTER TABLE `collector_profile_archive` DROP `country`;

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


ALTER TABLE `collector_geocache` ADD
(
	`country` CHAR(64)
);

ALTER TABLE `collector_profile` CHANGE `country_iso3166` `country_iso3166` VARCHAR(2);

ALTER TABLE `collector_profile` ADD
(
	`country` VARCHAR(64)
);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',


  'archive' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `collector_geocache_archive` ADD
(
	`country` CHAR(64)
);

ALTER TABLE `collector_profile_archive` ADD
(
	`country` VARCHAR(64)
);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}