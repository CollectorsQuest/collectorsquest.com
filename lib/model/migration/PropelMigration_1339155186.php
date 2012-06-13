<?php

class PropelMigration_1339155186
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
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
        SET FOREIGN_KEY_CHECKS = 0;

        UPDATE `package`
           SET `package_name` = '$2.50 for one credit',
               `package_description` = '$2.50 for one credit',
               `updated_at` = NOW()
         WHERE `id` = '1';

        UPDATE `package`
           SET `package_name` = '$20 for ten credits',
               `package_description` = '$20 for ten credits',
               `max_items_for_sale` = '10',
               `package_price` = '20',
               `updated_at` = NOW()
         WHERE `id` = '2';

        UPDATE `package`
           SET `package_name` = '$150 for 100 credits',
               `package_description` = '$150 for 100 credits',
               `max_items_for_sale` = '100',
               `package_price` = '150',
               `updated_at` = NOW()
         WHERE `id` = '3';

        UPDATE `package`
           SET `package_name` = '$250 for unlimited credits',
               `package_description` = '$250 for unlimited credits for one year.',
               `updated_at` = NOW()
         WHERE `id` = '6';

        UPDATE `package`
           SET `package_price` = '0',
               `updated_at` = NOW()
         WHERE `id` = '9999';

        -- Delete the extra legacy packages
        DELETE FROM `package` WHERE `id` IN ('4','5');

        INSERT INTO `promotion`
          (`id`, `promotion_code`, `promotion_name`, `promotion_desc`,
           `amount`, `amount_type`, `no_of_time_used`, `expiry_date`,
           `updated_at`, `created_at`)
        VALUES
          (NULL, 'CQ2012-DHX12', 'Free subscription!', 'Beta test program of the 2012 Marketplace',
           '100', 'Percentage', '100', '2012-07-01 00:00:00', NOW(), NOW());

        ALTER TABLE `package`
             CHANGE `max_items_for_sale` `credits` INT(11)  NOT NULL  DEFAULT 0;
        ALTER TABLE `package_transaction`
             CHANGE `max_items_for_sale` `credits` INT(11)  NOT NULL  DEFAULT 0;

        DROP TABLE IF EXISTS `package_transaction_credit`;
        CREATE TABLE `package_transaction_credit`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `package_transaction_id` INTEGER NOT NULL,
          `collector_id` INTEGER,
          `collectible_id` INTEGER,
          `expiry_date` DATETIME,
          `created_at` DATETIME,
          PRIMARY KEY (`id`),
          INDEX `package_transaction_credit_FI_1` (`package_transaction_id`),
          CONSTRAINT `package_transaction_credit_FK_1`
            FOREIGN KEY (`package_transaction_id`)
            REFERENCES `package` (`id`)
            ON DELETE RESTRICT
        ) ENGINE=InnoDB;

        SET FOREIGN_KEY_CHECKS = 1;
      ",
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
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
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `package`;
        CREATE TABLE `package` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `plan_type` enum('Casual','Power') DEFAULT NULL,
          `package_name` varchar(255) NOT NULL,
          `package_description` text NOT NULL,
          `max_items_for_sale` int(11) DEFAULT NULL,
          `package_price` float NOT NULL,
          `updated_at` datetime NOT NULL,
          `created_at` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        INSERT INTO `package`
          (`id`, `plan_type`, `package_name`, `package_description`,
           `max_items_for_sale`, `package_price`, `updated_at`, `created_at`)
        VALUES
          (1,'Casual','1 item only','1 item only',1,2.5,'2011-06-05 19:49:24','2011-06-05 19:49:24'),
          (2,'Casual','Up to 5 items ($2.25 ea)','Up to 5 items ($2.25 ea)',5,11.25,'2011-06-05 19:49:24','2011-06-05 19:49:24'),
          (3,'Casual','Up to 15 items ($2.00 ea)','Up to 15 items ($2.00 ea)',15,30,'2011-06-05 19:49:24','2011-06-05 19:49:24'),
          (4,'Casual','Up to 25 items ($1.65 ea)','Up to 25 items ($1.65 ea)',25,41.5,'2011-06-05 19:49:24','2011-06-05 19:49:24'),
          (5,'Power','Up to 1000 items ($.15 ea)','Up to 1000 items ($.15 ea)',1000,150,'2011-06-05 19:49:24','2011-06-05 19:49:24'),
          (6,'Power','Unlimited','Unlimited items for sale',9999,250,'2011-06-05 19:49:24','2011-06-05 19:49:24'),
          (9999,'Power','Free subscription!','Free as in \"Beer\"!',9999,250,'2011-06-05 19:49:24','2011-06-05 19:49:24');

        DELETE FROM `promotion` WHERE `promotion_code` = 'CQ2012-DHX12';

        ALTER TABLE `package` CHANGE `credits` `max_items_for_sale` INT(11)  NULL  DEFAULT NULL;
        ALTER TABLE `package_transaction` CHANGE `credits` `max_items_for_sale` INT(11)  NULL  DEFAULT NULL;

        DROP TABLE IF EXISTS `package_transaction_credit`;

        SET FOREIGN_KEY_CHECKS = 1;
      ",
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

}
