<?php

ini_set('memory_limit', '512M');

class PropelMigration_1340873286
{

	public function preUp($manager)
	{
    $q = CollectibleQuery::create()
      ->filterByEblob(null, Criteria::ISNULL)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $collectibles Collectible[] */
    $collectibles = $q->find();
    foreach ($collectibles as $collectible)
    {
      /** @var $m iceModelMultimedia */
      $m = iceModelMultimediaPeer::retrieveByModel($collectible);

      $collectible->setEblobElement('multimedia', $m->toXML(true));
      $collectible->save();

      echo "Processed ". $collectible->getName() ."\n";
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
