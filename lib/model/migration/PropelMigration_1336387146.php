<?php

class PropelMigration_1336387146
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp($manager)
  {

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
      'propel'  => "
        DELETE FROM `collectible_for_sale` WHERE `price` IS NULL;
        UPDATE `collectible_for_sale` SET `quantity` = 0 WHERE `is_sold` = 1;

        ALTER TABLE `collectible_for_sale` MODIFY COLUMN `is_ready` TINYINT(1) DEFAULT '0' AFTER `is_shipping_free`;
        ALTER TABLE `collectible_for_sale` ADD COLUMN `price_amount` INTEGER DEFAULT 0 NOT NULL AFTER `price`;
        ALTER TABLE `collectible_for_sale` ADD COLUMN `price_currency` CHAR(3) DEFAULT 'USD' NOT NULL AFTER `price_amount`;

        UPDATE `collectible_for_sale` SET `price_amount` = `price` * 100;
        ALTER TABLE `collectible_for_sale` DROP `price`;
      ",
      'archive' => "
        ALTER TABLE `collectible_for_sale_archive` DROP `id`;

        DELETE FROM `collectible_for_sale_archive` WHERE `price` IS NULL;
        UPDATE `collectible_for_sale_archive` SET `quantity` = 0 WHERE `is_sold` = 1;

        ALTER TABLE `collectible_for_sale_archive` MODIFY COLUMN `is_ready` TINYINT(1) DEFAULT '0' AFTER `is_shipping_free`;
        ALTER TABLE `collectible_for_sale_archive` ADD COLUMN `price_amount` INTEGER DEFAULT 0 NOT NULL AFTER `price`;
        ALTER TABLE `collectible_for_sale_archive` ADD COLUMN `price_currency` CHAR(3) DEFAULT 'USD' NOT NULL AFTER `price_amount`;

        UPDATE `collectible_for_sale_archive` SET `price_amount` = `price` * 100;
        ALTER TABLE `collectible_for_sale_archive` DROP `price`;
      "
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
      'propel'  => "
        ALTER TABLE `collectible_for_sale` ADD COLUMN `price` FLOAT DEFAULT NULL AFTER `collectible_id`;
        UPDATE `collectible_for_sale` SET  `price` = `price_amount` / 100;

        ALTER TABLE `collectible_for_sale` DROP `price_amount`;
        ALTER TABLE `collectible_for_sale` DROP COLUMN `price_currency`;
      ",
      'archive' => "
        ALTER TABLE `collectible_for_sale_archive` ADD COLUMN `price` FLOAT DEFAULT NULL AFTER `collectible_id`;
        UPDATE `collectible_for_sale_archive` SET  `price` = `price_amount` / 100;

        ALTER TABLE `collectible_for_sale_archive` DROP `price_amount`;
        ALTER TABLE `collectible_for_sale_archive` DROP COLUMN `price_currency`;
      "
    );
  }

}
