<?php

/**
 */
class PropelMigration_1354137771
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
    return array(
      'propel' => "
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `collectible_for_sale` ADD `tax_country` CHAR(2) AFTER `price_currency`;
        ALTER TABLE `collectible_for_sale` ADD `tax_state` VARCHAR(100) AFTER `tax_country`;
        ALTER TABLE `collectible_for_sale` ADD `tax_percentage` INTEGER AFTER `tax_state`;

        CREATE INDEX `collectible_for_sale_FI_2` ON `collectible_for_sale` (`tax_country`);

        ALTER TABLE `collectible_for_sale` ADD CONSTRAINT `collectible_for_sale_FK_2`
          FOREIGN KEY (`tax_country`)
          REFERENCES `geo_country` (`iso3166`);

        CREATE TABLE IF NOT EXISTS `geo_region`
          (
            `id` INTEGER NOT NULL AUTO_INCREMENT,
            `geo_country_id` INTEGER NOT NULL,
            `name_cyrillic` VARCHAR(64) NOT NULL,
            `name_latin` VARCHAR(64) NOT NULL,
            `slug_cyrillic` VARCHAR(64) NOT NULL,
            `slug_latin` VARCHAR(64) NOT NULL,
            `coords` TEXT NOT NULL,
            `latitude` FLOAT,
            `longitude` FLOAT,
            `zoom` SMALLINT,
            PRIMARY KEY (`id`),
            UNIQUE INDEX `geo_region_U_1` (`slug_cyrillic`),
            UNIQUE INDEX `geo_region_U_2` (`slug_latin`),
            INDEX `geo_region_FI_1` (`geo_country_id`),
            CONSTRAINT `geo_region_FK_1`
              FOREIGN KEY (`geo_country_id`)
              REFERENCES `geo_country` (`id`)
              ON DELETE CASCADE
          ) ENGINE=InnoDB;

        INSERT INTO `geo_region` (`id`, `geo_country_id`, `name_cyrillic`, `name_latin`, `slug_cyrillic`, `slug_latin`, `coords`, `latitude`, `longitude`, `zoom`) VALUES
          (1, 226, 'Alabama', 'Alabama', 'Alabama', 'Alabama', '', NULL, NULL, NULL),
          (2, 226, 'Alaska', 'Alaska', 'Alaska', 'Alaska', '', NULL, NULL, NULL),
          (3, 226, 'Arizona', 'Arizona', 'Arizona', 'Arizona', '', NULL, NULL, NULL),
          (4, 226, 'Arkansas', 'Arkansas', 'Arkansas', 'Arkansas', '', NULL, NULL, NULL),
          (5, 226, 'California', 'California', 'California', 'California', '', NULL, NULL, NULL),
          (6, 226, 'Colorado', 'Colorado', 'Colorado', 'Colorado', '', NULL, NULL, NULL),
          (7, 226, 'Connecticut', 'Connecticut', 'Connecticut', 'Connecticut', '', NULL, NULL, NULL),
          (8, 226, 'Delaware', 'Delaware', 'Delaware', 'Delaware', '', NULL, NULL, NULL),
          (9, 226, 'District of Columbia', 'District of Columbia', 'District-of-Columbia', 'District-of-Columbia', '', NULL, NULL, NULL),
          (10, 226, 'Florida', 'Florida', 'Florida', 'Florida', '', NULL, NULL, NULL),
          (11, 226, 'Georgia', 'Georgia', 'Georgia', 'Georgia', '', NULL, NULL, NULL),
          (12, 226, 'Hawaii', 'Hawaii', 'Hawaii', 'Hawaii', '', NULL, NULL, NULL),
          (13, 226, 'Idaho', 'Idaho', 'Idaho', 'Idaho', '', NULL, NULL, NULL),
          (14, 226, 'Illinois', 'Illinois', 'Illinois', 'Illinois', '', NULL, NULL, NULL),
          (15, 226, 'Indiana', 'Indiana', 'Indiana', 'Indiana', '', NULL, NULL, NULL),
          (16, 226, 'Iowa', 'Iowa', 'Iowa', 'Iowa', '', NULL, NULL, NULL),
          (17, 226, 'Kansas', 'Kansas', 'Kansas', 'Kansas', '', NULL, NULL, NULL),
          (18, 226, 'Kentucky', 'Kentucky', 'Kentucky', 'Kentucky', '', NULL, NULL, NULL),
          (19, 226, 'Louisiana', 'Louisiana', 'Louisiana', 'Louisiana', '', NULL, NULL, NULL),
          (20, 226, 'Maine', 'Maine', 'Maine', 'Maine', '', NULL, NULL, NULL),
          (21, 226, 'Maryland', 'Maryland', 'Maryland', 'Maryland', '', NULL, NULL, NULL),
          (22, 226, 'Massachusetts', 'Massachusetts', 'Massachusetts', 'Massachusetts', '', NULL, NULL, NULL),
          (23, 226, 'Michigan', 'Michigan', 'Michigan', 'Michigan', '', NULL, NULL, NULL),
          (24, 226, 'Minnesota', 'Minnesota', 'Minnesota', 'Minnesota', '', NULL, NULL, NULL),
          (25, 226, 'Mississippi', 'Mississippi', 'Mississippi', 'Mississippi', '', NULL, NULL, NULL),
          (26, 226, 'Missouri', 'Missouri', 'Missouri', 'Missouri', '', NULL, NULL, NULL),
          (27, 226, 'Montana', 'Montana', 'Montana', 'Montana', '', NULL, NULL, NULL),
          (28, 226, 'Nebraska', 'Nebraska', 'Nebraska', 'Nebraska', '', NULL, NULL, NULL),
          (29, 226, 'Nevada', 'Nevada', 'Nevada', 'Nevada', '', NULL, NULL, NULL),
          (30, 226, 'New Hampshire', 'New Hampshire', 'New-Hampshire', 'New-Hampshire', '', NULL, NULL, NULL),
          (31, 226, 'New Jersey', 'New Jersey', 'New-Jersey', 'New-Jersey', '', NULL, NULL, NULL),
          (32, 226, 'New Mexico', 'New Mexico', 'New-Mexico', 'New-Mexico', '', NULL, NULL, NULL),
          (33, 226, 'New York', 'New York', 'New-York', 'New-York', '', NULL, NULL, NULL),
          (34, 226, 'North Carolina', 'North Carolina', 'North-Carolina', 'North-Carolina', '', NULL, NULL, NULL),
          (35, 226, 'North Dakota', 'North Dakota', 'North-Dakota', 'North-Dakota', '', NULL, NULL, NULL),
          (36, 226, 'Ohio', 'Ohio', 'Ohio', 'Ohio', '', NULL, NULL, NULL),
          (37, 226, 'Oklahoma', 'Oklahoma', 'Oklahoma', 'Oklahoma', '', NULL, NULL, NULL),
          (38, 226, 'Oregon', 'Oregon', 'Oregon', 'Oregon', '', NULL, NULL, NULL),
          (39, 226, 'Pennsylvania', 'Pennsylvania', 'Pennsylvania', 'Pennsylvania', '', NULL, NULL, NULL),
          (40, 226, 'Rhode Island', 'Rhode Island', 'Rhode-Island', 'Rhode-Island', '', NULL, NULL, NULL),
          (41, 226, 'South Carolina', 'South Carolina', 'South-Carolina', 'South-Carolina', '', NULL, NULL, NULL),
          (42, 226, 'South Dakota', 'South Dakota', 'South-Dakota', 'South-Dakota', '', NULL, NULL, NULL),
          (43, 226, 'Tennessee', 'Tennessee', 'Tennessee', 'Tennessee', '', NULL, NULL, NULL),
          (44, 226, 'Texas', 'Texas', 'Texas', 'Texas', '', NULL, NULL, NULL),
          (45, 226, 'Utah', 'Utah', 'Utah', 'Utah', '', NULL, NULL, NULL),
          (46, 226, 'Vermont', 'Vermont', 'Vermont', 'Vermont', '', NULL, NULL, NULL),
          (47, 226, 'Virginia', 'Virginia', 'Virginia', 'Virginia', '', NULL, NULL, NULL),
          (48, 226, 'Washington', 'Washington', 'Washington', 'Washington', '', NULL, NULL, NULL),
          (49, 226, 'West Virginia', 'West Virginia', 'West-Virginia', 'West-Virginia', '', NULL, NULL, NULL),
          (50, 226, 'Wisconsin', 'Wisconsin', 'Wisconsin', 'Wisconsin', '', NULL, NULL, NULL),
          (51, 226, 'Wyoming', 'Wyoming', 'Wyoming', 'Wyoming', '', NULL, NULL, NULL);

        ALTER TABLE `shopping_cart_collectible`
          ADD `shipping_state_region` VARCHAR(100) AFTER `shipping_country_iso3166`;

        ALTER TABLE `collectible_for_sale_archive` ADD `tax_country` CHAR(2) AFTER `price_currency`;
        ALTER TABLE `collectible_for_sale_archive` ADD `tax_state` VARCHAR(100) AFTER `tax_country`;
        ALTER TABLE `collectible_for_sale_archive` ADD `tax_percentage` INTEGER AFTER `tax_state`;

        SET FOREIGN_KEY_CHECKS = 1;
",
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
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
    return array(
      'propel' => '
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `collectible_for_sale` DROP FOREIGN KEY `collectible_for_sale_FK_2`;

        DROP INDEX `collectible_for_sale_FI_2` ON `collectible_for_sale`;

        ALTER TABLE `collectible_for_sale` DROP `tax_country`;

        ALTER TABLE `collectible_for_sale` DROP `tax_state`;

        ALTER TABLE `collectible_for_sale` DROP `tax_percentage`;

        DROP TABLE IF EXISTS `geo_region`;

        ALTER TABLE `shopping_cart_collectible` DROP `shipping_state_region`;

        ALTER TABLE `collectible_for_sale_archive` DROP `tax_country`;

        ALTER TABLE `collectible_for_sale_archive` DROP `tax_state`;

        ALTER TABLE `collectible_for_sale_archive` DROP `tax_percentage`;

        SET FOREIGN_KEY_CHECKS = 1;
',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
',
    );
  }

}
