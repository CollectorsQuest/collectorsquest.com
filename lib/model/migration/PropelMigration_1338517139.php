<?php

class PropelMigration_1338517139
{

	public function preUp($manager)
	{
    /** @var $collections CollectorCollection[] */
    $collections = CollectorCollectionQuery::create()->find();

    foreach ($collections as $collection)
    {
      /** @var $thumbnail iceModelMultimedia */
      $thumbnail = $collection->getThumbnail();

      if (!$thumbnail || !$thumbnail->fileExists('original') || !$thumbnail->fileExists('190x190'))
      {
        if ($thumbnail && $thumbnail->fileExists('original'))
        {
          $collection->createMultimediaThumbs($thumbnail);

          echo "Fixed ". $collection->getName() ."\n";
        }
        else if ($collection->getNumItems() > 0)
        {
          $c = new Criteria();
          $c->addAscendingOrderByColumn('RAND()');

          if ($collectibles = $collection->getCollectionCollectibles($c))
          {
            $collectible = $collectibles[0];

            /** @var $m iceModelMultimedia */
            $m = $collectible->getPrimaryImage();
            $collection->setThumbnail($m->getAbsolutePath('original'));
            $collection->save();

            echo "Fixed ". $collection->getName() ."\n";
          }
        }
      }
    }
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
