<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1348861629.
 * Generated on 2012-10-03 23:45:09 by root
 */
class PropelMigration_1348861630
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

  /**
   * Copy over the collection category to collectibles which belong to one
   */
	public function postUp($manager)
	{
    echo "Updating Collectibles with ContentCategoryId from their Collections: \n";
    /* @var $collection Collection */
    $collections = CollectionPeer::doSelect(new Criteria());
    $count = count($collections);

    foreach ($collections as $k => $collection)
    {
      CollectibleQuery::create()
        ->filterById(
          // we cannot directly filter by category because mysql does not support
          // using UPDATE SET in combination with JOIN. That's why we do things
          // the roundabout way
          CollectionCollectibleQuery::create()
            ->filterByCollectionId($collection->getId())
            ->select('CollectibleId')
            ->find()->getArrayCopy(),
          Criteria::IN
        )
        ->update(array(
            'ContentCategoryId' => $collection->getContentCategoryId()
        ));

      echo sprintf("\r Completed: %.2f%%", round($k/$count, 4) * 100);
    }

    echo "\r Completed: 100%  \n";


    echo "Updating NumCollectiblesForSale on ContentCategory records: \n";
    /* @var $category Category */
    $categories = ContentCategoryQuery::create()
      ->descendantsOfRoot()
      ->find();
    $count = count($categories);
    /* @var $con PropelPDO */
    $con = Propel::getConnection();

    foreach ($categories as $k => $category)
    {
      $category->updateNumCollectiblesForSale($con);

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

ALTER TABLE `collectible` ADD `content_category_id` INTEGER AFTER `collector_id`;
CREATE INDEX `collectible_FI_2` ON `collectible` (`content_category_id`);
ALTER TABLE `collectible` ADD CONSTRAINT `collectible_FK_2`
	FOREIGN KEY (`content_category_id`)
	REFERENCES `content_category` (`id`)
	ON DELETE SET NULL;


ALTER TABLE `collectible_archive` ADD `content_category_id` INTEGER AFTER `collector_id`;
CREATE INDEX `collectible_archive_I_4` ON `collectible_archive` (`content_category_id`);


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

ALTER TABLE `collectible` DROP `content_category_id`;
ALTER TABLE `collectible_archive` DROP `content_category_id`;


ALTER TABLE `content_category` DROP `num_collectibles_for_sale`;
ALTER TABLE `content_category_archive` DROP `num_collectibles_for_sale`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}