<?php

class PropelMigration_1338971491
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
    /* @var $collections Collection[] */
    $collections = CollectionQuery::create()
        ->find();

    foreach ($collections as $collection)
    {
      $collection->setDescription(cqMarkdown::doConvert($collection->getDescription()));
    }

    $collections->save();

		return array (
      'propel' => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
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
    /* @var $collections Collection[] */
    $collections = CollectionQuery::create()
        ->find();

    foreach ($collections as $collection)
    {
      $collection->setDescription(cqMarkdownify::doConvert($collection->getDescription()));
    }

    $collections->save();

		return array (
      'propel' => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      ',
    );
	}

}
