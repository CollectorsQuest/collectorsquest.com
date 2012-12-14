<?php

/**
 * Remove errorneously created empty collectible_for_sale records
 */
class PropelMigration_1354899068
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

            DELETE FROM collectible_for_sale
            WHERE price_amount = 0
            AND NOT EXISTS (
              SELECT 1 from package_transaction_credit
              WHERE collectible_id = collectible_for_sale.collectible_id
            );

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