<?php

/**
 * Update package_transaction table
 *  - change payment_status to ENUM
 *
 * Generated on 2012-06-21 10:34:51 by root
 */
class PropelMigration_1340289291
{

	public function preUp($manager)
	{
    PackageTransactionQuery::create()
      ->where('PackageTransaction.PaymentStatus = ?',  'pending')
      ->update(array('PaymentStatus' => '0'));

    PackageTransactionQuery::create()
      ->where('PackageTransaction.PaymentStatus = ?',  'paid')
      ->update(array('PaymentStatus' => '1'));

    PackageTransactionQuery::create()
      ->where('PackageTransaction.PaymentStatus = ?',  'canceled')
      ->update(array('PaymentStatus' => '2'));
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
    PackageTransactionQuery::create()
      ->where('PackageTransaction.PaymentStatus = ?',  0)
      ->update(array('PaymentStatus' => 'pending'));

    PackageTransactionQuery::create()
      ->where('PackageTransaction.PaymentStatus = ?',  '1')
      ->update(array('PaymentStatus' => 'paid'));

    PackageTransactionQuery::create()
      ->where('PackageTransaction.PaymentStatus = ?',  '2')
      ->update(array('PaymentStatus' => 'canceled'));
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

ALTER TABLE `package_transaction` CHANGE `payment_status` `payment_status` TINYINT DEFAULT 0;

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

ALTER TABLE `package_transaction` CHANGE `payment_status` `payment_status` VARCHAR(255) DEFAULT \'pending\' NOT NULL;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}