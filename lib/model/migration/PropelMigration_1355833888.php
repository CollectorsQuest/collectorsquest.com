<?php

/**
 * Migrate PrivateMessage to better field names and new extra property names
 */
class PropelMigration_1355833888
{

    public function preUp($manager)
    {
      // add the pre-migration code here
    }

    public function postUp($manager)
    {
      // Move ExtraProperties CollectibleID and CollectionID to the general one
      $private_messages = PrivateMessageQuery::create()->find();
      $count = $private_messages->count();

      echo "\n Migrating PrivateMessageExtraProperty records: \n";

      foreach ($private_messages as $k => $message)
      {
        // get the original private message attached ids
        $collection_id = $message->getProperty(
          PrivateMessagePeer::DEPRECIATED_PROPERTY_ATTACHED_COLLECTION_ID
        );
        $collectible_id = $message->getProperty(
          PrivateMessagePeer::DEPRECIATED_PROPERTY_ATTACHED_COLLECTIBLE_ID
        );

        // delete old properties
        $message->deletePropertiesByName(
          PrivateMessagePeer::DEPRECIATED_PROPERTY_ATTACHED_COLLECTION_ID
        );
        $message->deletePropertiesByName(
          PrivateMessagePeer::DEPRECIATED_PROPERTY_ATTACHED_COLLECTIBLE_ID
        );

        // if we got an actual value on one of the original ids
        // add it through the new interface
        if ($collection_id)
        {
          $message->setAttachedObjectData('Collection', $collection_id);
          $message->save();
        }

        if ($collectible_id)
        {
          $message->setAttachedObjectData('Collectible', $collectible_id);
          $message->save();
        }

        echo sprintf("\r Completed: %.2f%%", round($k/$count, 4) * 100);
      }

      echo "\r Completed: 100.00% \n";
    }

    public function preDown($manager)
    {
      // Move ExtraProperties CollectibleID and CollectionID to the general one
      $private_messages = PrivateMessageQuery::create()->find();
      $count = $private_messages->count();

      echo "\n Migrating PrivateMessageExtraProperty records: \n";

      foreach ($private_messages as $k => $message)
      {
        $attached_object_class = $message->getProperty(
          PrivateMessagePeer::PROPERTY_ATTACHED_OBJECT_CLASS
        );

        if ('Collection' ==  $attached_object_class)
        {
          $message->setProperty(
            PrivateMessagePeer::DEPRECIATED_PROPERTY_ATTACHED_COLLECTION_ID,
            $message->getProperty(PrivateMessagePeer::PROPERTY_ATTACHED_OBJECT_PK)
          );

          $message->save();
        }

        if ('Collectible' ==  $attached_object_class)
        {
          $message->setProperty(
            PrivateMessagePeer::DEPRECIATED_PROPERTY_ATTACHED_COLLECTIBLE_ID,
            $message->getProperty(PrivateMessagePeer::PROPERTY_ATTACHED_OBJECT_PK)
          );

          $message->save();
        }

        // delete old properties
        $message->deletePropertiesByName(
          PrivateMessagePeer::PROPERTY_ATTACHED_OBJECT_CLASS
        );
        $message->deletePropertiesByName(
          PrivateMessagePeer::PROPERTY_ATTACHED_OBJECT_PK
        );

        echo sprintf("\r Completed: %.2f%%", round($k/$count, 4) * 100);
      }

      echo "\r Completed: 100.00% \n";
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

            ALTER TABLE `private_message` CHANGE `sender` `sender_id` INTEGER NOT NULL;
            ALTER TABLE `private_message` CHANGE `receiver` `receiver_id` INTEGER;
            ALTER TABLE `private_message` ADD `receiver_email` VARCHAR(255) AFTER `receiver_id`;

            # This restores the fkey checks, after having unset them earlier
            SET FOREIGN_KEY_CHECKS = 1;
          ',
          'blog' => '
            SET FOREIGN_KEY_CHECKS = 0;
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

            ALTER TABLE `private_message` CHANGE `sender_id` `sender` INTEGER NOT NULL;
            ALTER TABLE `private_message` CHANGE `receiver_id` `receiver` INTEGER NOT NULL;
            ALTER TABLE `private_message` DROP `receiver_email`;

            # This restores the fkey checks, after having unset them earlier
            SET FOREIGN_KEY_CHECKS = 1;
          ',
          'blog' => '
            SET FOREIGN_KEY_CHECKS = 0;
            SET FOREIGN_KEY_CHECKS = 1;
          ',
        );
    }

}