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

    $collector = new Collector();
    $collectors = CollectorQuery::create()
      ->joinWith('Collector.CollectorProfile')
      ->find();

    echo "\n". 'Processing collecotrs: ';
    foreach($collectors as $collector)
    {
      // Move collector profile about what I collect extra property to Collector tags
      $collector->setICollectTags($collector->getProfile()->getAboutWhatYouCollect());
      // samoe for about what I sell
      $collector->setISellTags($collector->getProfile()->getAboutWhatYouSell());

      if (isset($extra_icollect_tags[$collector->getId()]))
      {
        $collector->addICollectTag($extra_icollect_tags[$collector->getId()]);
      }
      echo '.';
    }

    echo "\n".'Saving collectors...';
    $collectors->save();
    echo ' done!';
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