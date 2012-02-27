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

    while ($profile = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $pdo->beginTransaction();

      $sql = sprintf(
        'REPLACE INTO %s (%s, %s, %s) VALUES (?, ?, ?)',
        CollectorProfileExtraPropertyPeer::TABLE_NAME,
        'collector_profile_extra_property.collector_profile_id',
        CollectorProfileExtraPropertyPeer::PROPERTY_NAME, CollectorProfileExtraPropertyPeer::PROPERTY_VALUE
      );

      foreach ($fields as $propertyName => $fieldName)
      {
        $propertyStmt = $pdo->prepare($sql);
        $propertyStmt->execute(array($profile['id'], strtoupper($propertyName), $profile[$fieldName]));
      }

      if (!empty($profile['collecting']))
      {
        $propertyStmt = $pdo->prepare($sql);
        $propertyStmt->execute(array($profile['id'], strtoupper('about.what_you_collect'), $profile['collecting']));
      }

      if (!empty($profile['most_spent']))
      {
        $propertyStmt = $pdo->prepare($sql);
        $propertyStmt->execute(array($profile['id'], strtoupper('about.most_expensive_item'), $profile['most_spent']));
      }

      if (!empty($profile['anually_spent']))
      {
        $propertyStmt = $pdo->prepare($sql);
        $propertyStmt->execute(array($profile['id'], strtoupper('about.annually_spend'), $profile['anually_spent']));
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
