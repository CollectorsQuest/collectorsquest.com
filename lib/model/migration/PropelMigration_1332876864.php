<?php

class PropelMigration_1332876864
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

CREATE TABLE `shipping_rate_collector`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`collector_id` INTEGER NOT NULL,
	`country_iso3166` CHAR(2) NOT NULL,
	`calculation_type` TINYINT NOT NULL,
	`price_range_min` INTEGER NOT NULL,
	`price_range_max` INTEGER NOT NULL,
	`amount_in_cents` INTEGER NOT NULL,
	`amount_in_percent` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `shipping_rate_collector_FI_1` (`collector_id`),
	INDEX `shipping_rate_collector_I_2` (`country_iso3166`),
	CONSTRAINT `shipping_rate_collector_FK_1`
		FOREIGN KEY (`collector_id`)
		REFERENCES `collector` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `shipping_rate_collector_FK_2`
		FOREIGN KEY (`country_iso3166`)
		REFERENCES `geo_country` (`iso3166`)
) ENGINE=InnoDB;

CREATE TABLE `shipping_rate_collectible`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`collectible_id` INTEGER NOT NULL,
	`country_iso3166` CHAR(2) NOT NULL,
	`calculation_type` TINYINT NOT NULL,
	`price_range_min` INTEGER NOT NULL,
	`price_range_max` INTEGER NOT NULL,
	`amount_in_cents` INTEGER NOT NULL,
	`amount_in_percent` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `shipping_rate_collectible_FI_1` (`collectible_id`),
	INDEX `shipping_rate_collectible_I_2` (`country_iso3166`),
	CONSTRAINT `shipping_rate_collectible_FK_1`
		FOREIGN KEY (`collectible_id`)
		REFERENCES `collectible` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `shipping_rate_collectible_FK_2`
		FOREIGN KEY (`country_iso3166`)
		REFERENCES `geo_country` (`iso3166`)
) ENGINE=InnoDB;

INSERT INTO geo_country
VALUES ("", "Wordwide", "wordwide", "ZZ", "USD", NULL, NULL, NULL);

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

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE `shipping_rate_collector`;
DROP TABLE `shipping_rate_collectible`;

DELETE FROM geo_country WHERE iso3166 = "ZZ";

SET FOREIGN_KEY_CHECKS = 1;

',
);
	}

}
