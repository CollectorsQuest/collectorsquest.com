<?php

/**
 * Fix missing slugs for Collector records
 */
class PropelMigration_1342027526
{

  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp($manager)
  {
    /** @var $q CollectorQuery */
    $q = CollectorQuery::create()
      ->filterBySlug(null, Criteria::ISNULL)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $collectors Collector[] */
    $collectors = $q->find();
    foreach ($collectors as $collector)
    {
      $display_name = $collector->getDisplayName();
      $collector->setDisplayName(null);
      $collector->setDisplayName($display_name);
      $collector->save();
    }
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
