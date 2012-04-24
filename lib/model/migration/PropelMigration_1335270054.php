<?php

class PropelMigration_1335270054
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
      'propel'  => '
        ALTER TABLE `collector` ADD `max_collectibles_for_sale` INTEGER NOT NULL DEFAULT 0;
        ALTER TABLE package_transaction
        ADD promotion_transaction_id INTEGER,
        ADD discount float unsigned,
        ADD INDEX `package_transaction_FI_3` (`promotion_transaction_id`),
        ADD CONSTRAINT `package_transaction_FK_3`
					FOREIGN KEY (`promotion_transaction_id`)
					REFERENCES `promotion_transaction` (`id`)
					ON DELETE RESTRICT;
      ',
      'archive' => '
        ALTER TABLE `collector_archive` ADD `max_collectibles_for_sale` INTEGER NOT NULL DEFAULT 0
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
      'propel'  => '
        ALTER TABLE `collector` DROP `max_collectibles_for_sale`;
        ALTER TABLE `package_transaction`
          DROP FOREIGN KEY `package_transaction_FK_3`,
          DROP INDEX `package_transaction_FI_3`;
        ALTER TABLE `package_transaction`
        DROP `promotion_transaction_id`,
        DROP `discount`;
      ',
      'archive' => '
        ALTER TABLE `collector_archive` DROP `max_collectibles_for_sale`;
      ',
    );
  }

}
