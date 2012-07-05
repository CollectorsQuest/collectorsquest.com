<?php

/**
 * fill in the data for collector I_COLLECT and I_SELL tags
 */
class PropelMigration_1341338916
{

	public function preUp($manager)
	{
    $fd = fopen(sfConfig::get('sf_data_dir'). '/migrations/1341338916_icollect.csv', 'r');
    $extra_icollect_tags = array();

    while ( ($data = fgetcsv($fd)) !== false )
    {
      $extra_icollect_tags[$data[0]] = $data[1];
    }
    fclose($fd);

    $collectors = CollectorQuery::create()
      ->joinWith('Collector.CollectorProfile')
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find();

    echo "\n". 'Processing collectors: ';
    foreach($collectors as $collector)
    {
      if (isset($extra_icollect_tags[$collector->getId()]))
      {
        $collector->addICollectTag($extra_icollect_tags[$collector->getId()]);
      }
      else
      {
        $collector->setICollectTags(
          $collector->getProfile()->getProperty('about.what_you_collect'));
      }

      // Move collector profile about what I sell to Collector tags
      $collector->setISellTags(
        $collector->getProfile()->getProperty('about.what_you_sell'));

      $collector->save();

      echo '.';
    }

    echo " done!\n";
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
      'propel' => '
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
