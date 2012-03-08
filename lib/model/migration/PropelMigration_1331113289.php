<?php

class PropelMigration_1331113289
{
	public function preUp($manager)
	{

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
      'propel' => "
        SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE `collectible_offer` DROP INDEX `collectible_offer_FI_2`;
        ALTER TABLE `collectible_offer` DROP FOREIGN KEY `collectible_offer_FK_2`;
        ALTER TABLE `collectible_offer` DROP `collectible_for_sale_id`;

        # We need to remove AUTO_INCREMENT property from ID before dropping the PK
        ALTER TABLE `collectible_for_sale` CHANGE `id` `id` INTEGER NOT NULL;
        ALTER TABLE `collectible_for_sale` DROP PRIMARY KEY;

        ALTER TABLE `collectible_for_sale` DROP INDEX `collectible_for_sale_item`;
        ALTER TABLE `collectible_for_sale` DROP `id`;

        ALTER TABLE `collectible_for_sale` ADD PRIMARY KEY (`collectible_id`);

        ALTER TABLE `collectible_for_sale` ADD CONSTRAINT `collectible_for_sale_FK_1`
          FOREIGN KEY (`collectible_id`)
          REFERENCES `collectible` (`id`)
          ON DELETE CASCADE;

        SET FOREIGN_KEY_CHECKS = 1;
      ",
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
      'propel' => "
        SET FOREIGN_KEY_CHECKS = 0;

        SET FOREIGN_KEY_CHECKS = 1;
      ",
    );
	}

}
