<?php

/**
 * Migration to automatic set rating & machine tags for collectibles from predefined collections
 */
class PropelMigration_1352237841
{

  public function preUp()
  {
    // add the pre-migration code here
  }

  public function postUp()
  {
    $collectorIdentifiers = CollectorIdentifierQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find();

    /* @var $collectorIdentifiers CollectorIdentifier[] */
    foreach ($collectorIdentifiers as $identifier)
    {
      $identifier->setProvider($identifier->getProviderFromIdentifier());
      $identifier->save();
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

        ALTER TABLE collector_identifier
          ADD `provider` VARCHAR(20) AFTER `identifier`;

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

        ALTER TABLE `collector_identifier`
          DROP `provider`;

        SET FOREIGN_KEY_CHECKS = 1;
      ',
      'blog' => '
        SET FOREIGN_KEY_CHECKS = 0;
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }


}
