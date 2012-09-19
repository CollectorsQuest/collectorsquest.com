<?php

class batchNewsletterConformationTask extends sfBaseTask
{
  /** @var sfApplicationConfiguration */
  protected $configuration;

  protected function configure()
  {
    unset($_SERVER['PATH_TRANSLATED'], $_SERVER['SCRIPT_NAME']);

    $this->namespace  = 'batch';
    $this->name       = 'newsletter-conformation';

    $this->addArgument('application', sfCommandArgument::OPTIONAL, 'The application name', 'frontend');
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev');
  }

  protected function execute($arguments = array(), $options = array())
  {
    $_SERVER['HTTP_HOST'] = sfConfig::get('app_www_domain');

    sfContext::createInstance($this->configuration);

    // Database initialization
    new sfDatabaseManager($this->configuration);

    // not sure if we need this
    Propel::setForceMasterConnection(false);

    /**
     * Get a connection for read and write connection
     *
     * @var $connection PropelPDO
     */
    $connection = Propel::getConnection('propel', Propel::CONNECTION_WRITE);

    /**
     * get all the collectors
     * @var $q Collector[]
     */
    $collectors = CollectorQuery::create()
      ->find($connection);

    // Get the SwiftMailer
    $mailer = $this->getMailer();

    // get the array of Collector ID's we already sent to
    $file_with_results = file_get_contents('/www/tmp/newsletter-conformation.txt');
    $already_sent = explode(',', trim($file_with_results));

    // how many letters we send per action
    $limit = 100;

    // counter for number of emails sent by the task
    $i = 0;

    // send emails to collectors and add ID's to array
    foreach ($collectors as $collector)
    {
      /* @var $collector Collector */
      $id = $collector->getId();
      if (!in_array($id, $already_sent))
      {
        $cqEmail = new cqEmail($mailer);
        $cqEmail->send('Collector/newsletter_subscription_confirmation', array(
          'to' => $collector->getEmail()
        ));

        $already_sent[] = $id;

        $i++;
      }

      if ($i == $limit)
      {
        break;
      }
    }

    // write the Collector ID's we already sent to
    file_put_contents('/www/tmp/newsletter-conformation.txt', implode(',', $already_sent));
  }

}
