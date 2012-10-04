<?php

/**
 * Move collector profile preferences column to Collector::ExtraPropertiesBehavior
 *
 * Generated on 2012-08-25 13:59:14 by me :)
 */
class PropelMigration_1345917554
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
    /* @var $con PropelPDO */
    $con = $manager->getPdoConnection('propel');

    $sql = 'SELECT collector_id, preferences, notifications FROM collector_profile';
    $rows = $con->query($sql);
    $rows_count = $rows->rowCount();
    foreach ($rows as $k => $row)
    {
      $preferences = unserialize($row['preferences']);
      $notifications = unserialize($row['notifications']);

      $collector = CollectorPeer::retrieveByPK($row['collector_id']);

      $collector->setPreferencesShowAge(!!@$preferences['show_age']);
      $collector->setPreferencesMsgOn(!!@$preferences['msg_on']);
      $collector->setPreferencesInviteOnly(!!@$preferences['invite_only']);
      $collector->setPreferencesNewsletter(!!@$preferences['newsletter']);

      $collector->setNotificationsComment(!!@$notifications['comment']);
      $collector->setNotificationsBuddy(!!@$notifications['buddy']);
      $collector->setNotificationsMessage(!!@$notifications['message']);

      $collector->save();

      echo sprintf("\r Completed: %.2f%%", round($k/$rows_count, 4) * 100);
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

        ALTER TABLE `collector_profile` DROP `preferences`;
        ALTER TABLE `collector_profile` DROP `notifications`;

        ALTER TABLE `collector_profile_archive` DROP `preferences`;
        ALTER TABLE `collector_profile_archive` DROP `notifications`;

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
