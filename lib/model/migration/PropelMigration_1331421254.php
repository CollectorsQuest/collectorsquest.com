<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1331421254.
 * Generated on 2012-03-10 18:14:14 by vagrant
 */
class PropelMigration_1331421254
{

  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp($manager)
  {
    $q = CollectorEmailQuery::create()->filterByHash('', Criteria::EQUAL);

    /** @var $collector_emails CollectorEmail[] */
    $collector_emails = $q->find();

    foreach ($collector_emails as $collector_email)
    {
      /** @var $collector Collector */
      $collector = $collector_email->getCollector();
      $salt = $collector->generateSalt();

      $collector_email->setSalt($salt);
      $collector_email->setHash($collector->getAutoLoginHash(null, null, $salt));
      $collector_email->save();
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
        INSERT INTO `collector_email`
        (`collector_id`, `email`, `is_verified`, `created_at`, `updated_at`)
        SELECT id, email, 1, NOW(), NOW()
        FROM `collector`
        WHERE email IS NOT NULL
        ON DUPLICATE KEY UPDATE is_verified = 1, updated_at = NOW()
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
    return array();
  }

}
