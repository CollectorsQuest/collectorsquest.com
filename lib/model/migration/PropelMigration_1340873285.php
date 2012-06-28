<?php

class PropelMigration_1340873285
{

	public function preUp($manager)
	{
    $q = CollectorQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    $collectors = $q->find();
    foreach ($collectors as $collector)
    {
      /** @var $m iceModelMultimedia */
      $m = iceModelMultimediaPeer::retrieveByModel($collector);

      $collector->setEblobElement('multimedia', $m->toXML(true));
      $collector->save();
    }

    $q = CollectorCollectionQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    $collections = $q->find();
    foreach ($collections as $collection)
    {
      /** @var $m iceModelMultimedia */
      $m = iceModelMultimediaPeer::retrieveByModel($collection);

      $collection->setEblobElement('multimedia', $m->toXML(true));
      $collection->save();
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
    return array(
      'propel'  => '
        SET FOREIGN_KEY_CHECKS = 0;
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
    return array(
      'propel'  => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

}
