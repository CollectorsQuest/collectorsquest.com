<?php

class batchDisqusEmailsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));

    $this->namespace        = 'batch';
    $this->name             = 'disqus-emails';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [batch:disqus-emails|INFO] task connects to gmail to read the messages of robot@collectorsquest.com in the folder
"Discus Comments". Figures out what collection or collectible is the comment for and send a copy to the owner for moderation.

Call it with:

  [php symfony batch:disqus-emails|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // Get the SwiftMailer
    $mailer = $this->getMailer();

    // We need Zend_Mail_Storage_Imap so load Zend Framework autoloader
    cqStatic::loadZendFramework();

    // connecting with Imap
    $mail = new Zend_Mail_Storage_Imap(array('host'     => 'imap.gmail.com',
                                             'ssl'      => 'SSL',
                                             'port'     => 993,
                                             'user'     => 'robot@collectorsquest.com',
                                             'password' => '65A295'));

    $mail->selectFolder('Disqus Comments');

    foreach ($mail as $message)
    {
      // The number of the message in the IMAP mailbox
      $i = $message->key();

      if ($message->hasFlag(Zend_Mail_Storage::FLAG_SEEN))
      {
        continue;
      }

      if ($message->isMultipart())
      {
        foreach (new RecursiveIteratorIterator($message) as $part)
        {
          try
          {
            if (strtok($part->contentType, ';') == 'text/plain')
            {
              $body = trim($part);
              break;
            }
          }
          catch (Zend_Mail_Exception $e) { ; }
        }
      }
      else
      {
        $body = $message->getContent();
      }

      preg_match('/Link to comment: (.*)\s/iu', $body, $m);
      $url = isset($m[1]) ? $m[1] : null;

      if ($url && substr($url, 0, 26) != 'http://www.collectorsquest')
      {
        $url = IceWebBrowser::getHttpHeader($url, 'Location');
      }

      if (IceWebBrowser::isUrl($url))
      {
        $this->log('Found URL: '. $url);

        $collector = null;
        preg_match('/collectorsquest\.(com|dev)\/(.*)\/(\d+)\/([\-\w\.]+)/iu', $url, $m);

        if (isset($m[2]))
        switch($m[2])
        {
          case 'collectible':
          case 'collection/item':
            if ($collectible = CollectiblePeer::retrieveByPK($m[3]))
            {
              $collector = $collectible->getCollector();
            }
            break;
          case 'collection':
            if ($collection = CollectionPeer::retrieveByPK($m[3]))
            {
              $collector = $collection->getCollector();
            }
            break;
          case 'blog':
            $message = $mailer->compose('no-reply@collectorsquest.com', 'bloggers@collectorsquest.com', $message->subject, $body)
                              ->setReplyTo($message->xOriginalSender);
            try {
              // Send the email to the bloggers
              $mailer->send($message);
            }
            catch (SwiftException $e) { ; }
            break;
        }

        if (isset($collector) && $collector instanceof Collector)
        {
          $message = $mailer->compose('no-reply@collectorsquest.com', $collector->getEmail(), $message->subject, $body)
                            ->setReplyTo($message->xOriginalSender);

          try
          {
            // Send the email to the collector
            $mailer->send($message);
          }
          catch (SwiftException $e) { ; }
        }
      }

      $mail->setFlags($i, array(Zend_Mail_Storage::FLAG_SEEN));
      $mail->noop();
    }
  }
}
