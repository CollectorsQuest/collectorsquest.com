<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1320669958.
 * Generated on 2011-11-07 07:45:58 by root
 */
class PropelMigration_1320669958
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
      'propel' => '
      # This is a fix for InnoDB in MySQL >= 4.1.x
      # It "suspends judgement" for fkey relationships until are tables are set.
      SET FOREIGN_KEY_CHECKS = 0;

      DROP TABLE IF EXISTS `schema_info`;
      DROP TABLE IF EXISTS `message`;
      DROP TABLE IF EXISTS `session_storage`;

      ALTER TABLE `collectible` CHANGE `name` `name` VARCHAR(128) NOT NULL;
      ALTER TABLE `collectible` CHANGE `description` `description` TEXT NOT NULL;
      ALTER TABLE `collectible` CHANGE `num_comments` `num_comments` INTEGER DEFAULT 0 NOT NULL;
      ALTER TABLE `collectible` CHANGE `score` `score` INTEGER DEFAULT 0 NOT NULL;
      ALTER TABLE `collectible` CHANGE `position` `position` INTEGER DEFAULT 0 NOT NULL;
      ALTER TABLE `collectible` CHANGE `is_name_automatic` `is_name_automatic` TINYINT(1) DEFAULT 0;
      ALTER TABLE `collectible` DROP `est_value`;
      ALTER TABLE `collectible` DROP `purchased_price`;
      ALTER TABLE `collectible` DROP `currency`;

      ALTER TABLE `collectible_for_sale` DROP FOREIGN KEY `collectible_for_sale_FK_1`;
      ALTER TABLE `collectible_for_sale` CHANGE `is_price_negotiable` `is_price_negotiable` TINYINT(1) DEFAULT 0;
      ALTER TABLE `collectible_for_sale` CHANGE `is_shipping_free` `is_shipping_free` TINYINT(1) DEFAULT 0;
      ALTER TABLE `collectible_for_sale` CHANGE `is_sold` `is_sold` TINYINT(1) DEFAULT 0;
      ALTER TABLE `collectible_for_sale` CHANGE `is_ready` `is_ready` TINYINT(1) DEFAULT 0;
      ALTER TABLE `collectible_for_sale` ADD CONSTRAINT `collectible_for_sale_FK_1`
        FOREIGN KEY (`collectible_id`)
        REFERENCES `collectible` (`id`)
        ON DELETE CASCADE;

      ALTER TABLE `collectible_offer` DROP FOREIGN KEY `collectible_offer_FK_1`;
      ALTER TABLE `collectible_offer` DROP FOREIGN KEY `collectible_offer_FK_2`;
      ALTER TABLE `collectible_offer` ADD CONSTRAINT `collectible_offer_FK_1`
        FOREIGN KEY (`collectible_id`)
        REFERENCES `collectible` (`id`)
        ON DELETE CASCADE;
      ALTER TABLE `collectible_offer` ADD CONSTRAINT `collectible_offer_FK_2`
        FOREIGN KEY (`collectible_for_sale_id`)
        REFERENCES `collectible_for_sale` (`id`)
        ON DELETE CASCADE;

      ALTER TABLE `collection_category` CHANGE `parent_id` `parent_id` INTEGER DEFAULT 0;
      ALTER TABLE `collection_category` CHANGE `name` `name` VARCHAR(64) NOT NULL;
      ALTER TABLE `collection_category` CHANGE `score` `score` INTEGER DEFAULT 0;

      ALTER TABLE `collection` CHANGE `name` `name` VARCHAR(128) NOT NULL;
      ALTER TABLE `collection` CHANGE `description` `description` TEXT NOT NULL;
      ALTER TABLE `collection` CHANGE `num_items` `num_items` INTEGER DEFAULT 0 NOT NULL;
      ALTER TABLE `collection` CHANGE `num_views` `num_views` INTEGER DEFAULT 0 NOT NULL;
      ALTER TABLE `collection` CHANGE `num_comments` `num_comments` INTEGER DEFAULT 0 NOT NULL;
      ALTER TABLE `collection` CHANGE `num_ratings` `num_ratings` INTEGER DEFAULT 0 NOT NULL;
      ALTER TABLE `collection` CHANGE `score` `score` INTEGER DEFAULT 0;
      CREATE INDEX `collection_FI_2` ON `collection` (`collector_id`);

      # This restores the fkey checks, after having unset them earlier
      SET FOREIGN_KEY_CHECKS = 1;',
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
    return array();
  }
}
