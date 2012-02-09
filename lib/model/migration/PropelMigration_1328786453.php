<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1328786453.
 * Generated on 2012-02-09 06:20:53 by root
 */
class PropelMigration_1328786453
{

  public function preUp(PropelMigrationManager $manager)
  {
    /* @var $pdo PropelPDO */
    $pdo = $manager->getPdoConnection('propel');

    $fields = array(
      'about.annually_spent'      => 'annually_spend',
      'about.what_you_collect'    => 'what_you_collect',
      'about.what_you_sell'       => 'what_you_sell',
      'about.purchases_per_year'  => 'purchases_per_year',
      'about.most_expensive_item' => 'most_expensive_item',
      'about.company'             => 'company',
    );

    $sql = 'SELECT * FROM collector';

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $index = 0;
    while ($collector = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $pdo->beginTransaction();

      $sql = sprintf('INSERT IGNORE INTO %s
              (%s, %s, %s)
              VALUES
              (?, ?, ?)
              ', CollectorExtraPropertyPeer::TABLE_NAME,
        CollectorExtraPropertyPeer::COLLECTOR_ID, CollectorExtraPropertyPeer::PROPERTY_NAME, CollectorExtraPropertyPeer::PROPERTY_VALUE);

      foreach ($fields as $propertyName=> $fieldName)
      {
        $propertyStmt = $pdo->prepare($sql);
        $propertyStmt->execute(array($collector['id'], $propertyName, $collector[$fieldName]));
      }

      $pdo->commit();
    }
  }

  public function postUp($manager)
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
        ALTER TABLE `collector`
        DROP `what_you_collect`,
        DROP `purchases_per_year`,
        DROP `what_you_sell`,
        DROP `annually_spend`,
        DROP `most_expensive_item`,
        DROP `company`
        ',
      'propel' => '
        ALTER TABLE `collector_archive`
        DROP `what_you_collect`,
        DROP `purchases_per_year`,
        DROP `what_you_sell`,
        DROP `annually_spend`,
        DROP `most_expensive_item`,
        DROP `company`
      ',
    );
  }

  public function preDown($manager)
  {
    return false; //No way back
  }

  public function postDown($manager)
  {
    // add the post-migration code here
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
        ALTER TABLE `collector` ADD
        (
          `what_you_collect` VARCHAR(255),
          `purchases_per_year` INTEGER DEFAULT 0 NOT NULL,
          `what_you_sell` VARCHAR(255),
          `annually_spend` FLOAT,
          `most_expensive_item` FLOAT,
          `company` VARCHAR(255)
        );
        ',
    );
  }

}
