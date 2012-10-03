<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1348862739.
 * Generated on 2012-09-28 15:47:09 by root
 */
class PropelMigration_1348862739
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

  /**
   * add values for field count_collectibles_for_sale in ContentCategory model
   */
	public function postUp($manager)
	{
    $categories = ContentCategoryQuery::create()
      ->find();
    $count = count($categories);

    /** @var $connection PropelPDO */
    $connection = Propel::getConnection();

    foreach ($categories as $k => $category)
    {
      /** @var $q CollectibleForSaleQuery */
      $q = CollectibleForSaleQuery::create()
        ->filterByContentCategoryWithDescendants($category);

      $_num_collectibles_for_sale = $q->count($connection);

      /** @var $category ContentCategory */
      $category->setNumCollectiblesForSale($_num_collectibles_for_sale);
      $category->save();

      echo sprintf("\r Completed: %.2f%%", round($k/$count, 4) * 100);
    }

    echo "\r Completed: 100%  \n";
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

ALTER TABLE `content_category` ADD `num_collectibles_for_sale` INTEGER;

ALTER TABLE `content_category_archive` ADD `num_collectibles_for_sale` INTEGER;

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

ALTER TABLE `content_category` DROP `num_collectibles_for_sale`;

ALTER TABLE `content_category_archive` DROP `num_collectibles_for_sale`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}
