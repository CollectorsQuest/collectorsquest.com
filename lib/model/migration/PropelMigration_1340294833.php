<?php

/**
 * Update package_transaction_credit:
 *   -  make package_transaction_id on delete cascade
 *   -  remove collector_id because it is not needed
 *   -  make collectible_id an actual foreign key (on delete setnull)
 *
 * Generated on 2012-06-21 12:07:13 by root
 */
class PropelMigration_1340294833
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

ALTER TABLE `package_transaction_credit` DROP FOREIGN KEY `package_transaction_credit_FK_1`;

ALTER TABLE `package_transaction_credit` DROP `collector_id`;

CREATE INDEX `package_transaction_credit_FI_2` ON `package_transaction_credit` (`collectible_id`);

ALTER TABLE `package_transaction_credit` ADD CONSTRAINT `package_transaction_credit_FK_1`
	FOREIGN KEY (`package_transaction_id`)
	REFERENCES `package_transaction` (`id`)
	ON DELETE CASCADE;

ALTER TABLE `package_transaction_credit` ADD CONSTRAINT `package_transaction_credit_FK_2`
	FOREIGN KEY (`collectible_id`)
	REFERENCES `collectible` (`id`)
	ON DELETE SET NULL;


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


ALTER TABLE `package_transaction_credit` DROP FOREIGN KEY `package_transaction_credit_FK_2`;

ALTER TABLE `package_transaction_credit` DROP FOREIGN KEY `package_transaction_credit_FK_1`;

DROP INDEX `package_transaction_credit_FI_2` ON `package_transaction_credit`;

ALTER TABLE `package_transaction_credit` ADD
(
	`collector_id` INTEGER
);

ALTER TABLE `package_transaction_credit` ADD CONSTRAINT `package_transaction_credit_FK_1`
	FOREIGN KEY (`package_transaction_id`)
	REFERENCES `package` (`id`);


# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}