<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1338479109.
 * Generated on 2012-05-30 15:02:37 by root
 */
class PropelMigration_1338479109
{

	public function preUp($manager)
	{
    /* @var $collectors Collector[] */
    $collectors = CollectorQuery::create()->find();

    foreach ($collectors as $collector)
    {
      $collector = CollectorQuery::create()->findOneById(1505);
      $collector->setAboutMe(strip_tags($collector->getAboutMe()));
      $collector->setAboutCollections(strip_tags($collector->getAboutCollections()));
      $collector->setAboutInterests(strip_tags($collector->getAboutInterests()));
    }

    $collectors->save();
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

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}
