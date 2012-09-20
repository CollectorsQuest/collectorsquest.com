<?php

/**
 * Make every collector who signed up BEFORE 08/01/2012 (1st of August) as newsletter = false
 *
 * https://basecamp.com/1759305/projects/19290-collectorsquest-com/todos/15713021-we-need-to-have-a
 */
class PropelMigration_1347984240
{

  public function preUp()
  {
    /** @var $q CollectorQuery */
    $q = CollectorQuery::create();

    /** @var $collectors PropelObjectCollection|Collector[] */
    $collectors = $q->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)->find();

    /** @var $collector_count integer */
    $count = count($collectors);

    foreach ($collectors as $k => $collector)
    {
      $collector->setProperty(CollectorPeer::PROPERTY_PREFERENCES_NEWSLETTER_OPT_OUT, false);
      $collector->save();

      echo sprintf("\r Completed: %.2f%%", round($k/$count, 4) * 100);
    }

    echo "\r Completed: 100%  \n";
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
    return array (
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

}
