<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1328786453.
 * Generated on 2012-02-09 06:20:53 by root
 */
class PropelMigration_1328807739
{

  public function preUp(PropelMigrationManager $manager)
  {
    /* @var $pdo PropelPDO */
    $pdo = $manager->getPdoConnection('propel');

    $fields = array(
      'about.me'              => 'about',
      'about.collections'     => 'collections',
      'about.new_item_every'  => 'new_item_every',
      'about.interests'       => 'interests',
    );

    $sql = 'SELECT * FROM collector_profile';

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
        $propertyStmt->execute(array($collector['collector_id'], $propertyName, $collector[$fieldName]));
      }

      if (!empty($collector['collecting']))
      {
        $propertyStmt = $pdo->prepare($sql);
        $propertyStmt->execute(array($collector['collector_id'], 'about.what_you_collect', $collector['collecting']));
      }

      if (!empty($collector['most_spent']))
      {
        $propertyStmt = $pdo->prepare($sql);
        $propertyStmt->execute(array($collector['collector_id'], 'about.most_spent', $collector['most_spent']));
      }

      if (!empty($collector['anually_spent']))
      {
        $propertyStmt = $pdo->prepare($sql);
        $propertyStmt->execute(array($collector['collector_id'], 'about.annually_spend', $collector['anually_spent']));
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
      'propel'  => '
        ALTER TABLE `collector_profile`
        DROP `about`,
        DROP `collections`,
        DROP `collecting`,
        DROP `most_spent`,
        DROP `anually_spent`,
        DROP `new_item_every`,
        DROP `interests`
        ',

      'archive' => '
        ALTER TABLE `collector_profile_archive`
        DROP `about`,
        DROP `collections`,
        DROP `collecting`,
        DROP `most_spent`,
        DROP `anually_spent`,
        DROP `new_item_every`,
        DROP `interests`
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
  }

}
