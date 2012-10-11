<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1348503327.
 * Generated on 2012-09-24 12:15:27 by root
 */
class PropelMigration_1349988396
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

        DROP TABLE IF EXISTS `shopping_order_feedback`;
        CREATE TABLE `shopping_order_feedback`
        (
          `id` INTEGER NOT NULL AUTO_INCREMENT,
          `shopping_order_id` INTEGER NOT NULL,
          `collectible_id` INTEGER NOT NULL,
          `seller_id` INTEGER NOT NULL,
          `buyer_id` INTEGER,
          `rating` TINYINT NOT NULL,
          `rating_for` TINYINT NOT NULL,
          `comment` TEXT,
          `is_rated` TINYINT(1) DEFAULT 0,
          `created_at` DATETIME,
          `updated_at` DATETIME,
          PRIMARY KEY (`id`),
          INDEX `shopping_order_feedback_FI_1` (`shopping_order_id`),
          INDEX `shopping_order_feedback_FI_2` (`collectible_id`),
          INDEX `shopping_order_feedback_FI_3` (`seller_id`),
          INDEX `shopping_order_feedback_FI_4` (`buyer_id`),
          CONSTRAINT `shopping_order_feedback_FK_1`
            FOREIGN KEY (`shopping_order_id`)
            REFERENCES `shopping_order` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `shopping_order_feedback_FK_2`
            FOREIGN KEY (`collectible_id`)
            REFERENCES `collectible` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `shopping_order_feedback_FK_3`
            FOREIGN KEY (`seller_id`)
            REFERENCES `collector` (`id`)
            ON DELETE CASCADE,
          CONSTRAINT `shopping_order_feedback_FK_4`
            FOREIGN KEY (`buyer_id`)
            REFERENCES `collector` (`id`)
            ON DELETE SET NULL
        ) ENGINE=InnoDB;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
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
    return array (
      'propel' => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        DROP TABLE IF EXISTS `shopping_order_feedback`;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

}
