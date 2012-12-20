<?php

/**
 * Copy messages directly relayed to email addresses to the users's
 * Private Message *sent* folder
 */
class PropelMigration_1356015277
{

    const SENDER_ID_REGEX = '/collector\/(?<sender_id>(\d+))/';
    const MESSAGE_REGEX = '/<em>(?<message>((?:.|\r|\n)+))<\/em>/u';

    public function preUp($manager)
    {
      $emails_logs = EmailsLogQuery::create()
        ->filterByTextHtml('%Hello from CollectorsQuest.com!%', Criteria::LIKE)
        ->filterByTextHtml('%has sent you a message through CollectorsQuest.com%', Criteria::LIKE)
        ->find();
      $count = $emails_logs->count();

      echo "\n Copying Emails to PMs: \n";

      foreach ($emails_logs as $k => $emails_log)
      {
        /* @var $emails_log EmailsLog */
        $email_html = $emails_log->getTextHtml();

        if (
          preg_match(self::SENDER_ID_REGEX, $email_html, $sender) &&
          preg_match(self::MESSAGE_REGEX, $email_html, $message)
        )
        {
          $pm = new PrivateMessage();
          $pm->setSubject('Message to ' . $emails_log->getReceiverEmail());
          $pm->setReceiverEmail($emails_log->getReceiverEmail());
          $pm->setSenderId($sender[1]);
          $pm->setBody($message[1]);
          $pm->setCreatedAt($emails_log->getCreatedAt());
          $pm->setIsRead(true);
          $pm->save();
        }

        echo sprintf("\r Completed: %.2f%%", round($k/$count, 4) * 100);
      }

      echo "\r Completed: 100.00% \n";
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
            SET FOREIGN_KEY_CHECKS = 0;
            SET FOREIGN_KEY_CHECKS = 1;
          ',
          'blog' => '
            SET FOREIGN_KEY_CHECKS = 0;
            SET FOREIGN_KEY_CHECKS = 1;
          ',
        );
    }

}