<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1341508451.
 * Generated on 2012-07-05 13:14:11 by root
 */
class PropelMigration_1341508451
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

	public function postUp($manager)
	{
    $collectibles_for_sale = CollectibleForSaleQuery::create()
      ->find();

    foreach ($collectibles_for_sale as $collectible_for_sale)
    {
      if (( $credit = $collectible_for_sale->getActiveCredit() ))
      {
        $collectible_for_sale->setMarkedForSaleAt(strtotime(
          '-'.PackageTransactionCreditPeer::STANDARD_EXPIRY_TIME,
          $credit->getExpiryDate('U')));
      }
      else
      {
        $collectible_for_sale->setMarkedForSaleAt(
          $collectible_for_sale->getCreatedAt());
      }
    }

    $collectibles_for_sale->save();
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


        ALTER TABLE `collectible_for_sale`
        ADD `marked_for_sale_at` DATETIME AFTER `is_ready`;

        ALTER TABLE `collectible_for_sale_archive`
        ADD `marked_for_sale_at` DATETIME AFTER `is_ready`;


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


        ALTER TABLE `collectible_for_sale`
        DROP `marked_for_sale_at`;

        ALTER TABLE `collectible_for_sale_archive`
        DROP `marked_for_sale_at`;


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