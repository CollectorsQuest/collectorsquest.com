<?php

/**
 * In marketplace, convert all expire dates to `date`
 *
 * Generated on 2013-07-07 12:31:26 by root
 */
class PropelMigration_1373214686
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

            ALTER TABLE `package_transaction` CHANGE `expiry_date` `expiry_date` DATE;

            ALTER TABLE `package_transaction_credit` CHANGE `expiry_date` `expiry_date` DATE;

            ALTER TABLE `promotion` CHANGE `expiry_date` `expiry_date` DATE;

            ALTER TABLE `seller_promotion` CHANGE `expiry_date` `expiry_date` DATE;

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

            ALTER TABLE `package_transaction` CHANGE `expiry_date` `expiry_date` DATETIME;

            ALTER TABLE `package_transaction_credit` CHANGE `expiry_date` `expiry_date` DATETIME;

            ALTER TABLE `promotion` CHANGE `expiry_date` `expiry_date` DATETIME;

            ALTER TABLE `seller_promotion` CHANGE `expiry_date` `expiry_date` DATETIME;

            # This restores the fkey checks, after having unset them earlier
            SET FOREIGN_KEY_CHECKS = 1;
          ',
        );
    }

}