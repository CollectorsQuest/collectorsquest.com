<?php

/**
 * Remove unused field is_shipping_free from collectible_for_sale
 */
class PropelMigration_1355255763
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

            ALTER TABLE `collectible_for_sale` DROP `is_shipping_free`;
            ALTER TABLE `collectible_for_sale_archive` DROP `is_shipping_free`;

            # This restores the fkey checks, after having unset them earlier
            SET FOREIGN_KEY_CHECKS = 1;
          ',
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
        return array (
          'propel' => '
            # This is a fix for InnoDB in MySQL >= 4.1.x
            # It "suspends judgement" for fkey relationships until are tables are set.
            SET FOREIGN_KEY_CHECKS = 0;

            ALTER TABLE `collectible_for_sale` ADD `is_shipping_free` TINYINT(1) DEFAULT 0 AFTER `is_price_negotiable`;
            ALTER TABLE `collectible_for_sale_archive` ADD `is_shipping_free` TINYINT(1) DEFAULT 0 AFTER `is_price_negotiable`;

            # This restores the fkey checks, after having unset them earlier
            SET FOREIGN_KEY_CHECKS = 1;
          ',
          'blog' => '
            SET FOREIGN_KEY_CHECKS = 0;
            SET FOREIGN_KEY_CHECKS = 1;
          ',
        );
    }

}