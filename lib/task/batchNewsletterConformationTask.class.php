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

    /* @var $q Collector[] */
    $collectors = CollectorQuery::create()
      // @todo change/remove next line
      ->filterByExtraProperty('PROPERTY_PREFERENCES_NEWSLETTER_SENT_CONFORMATION', false)
      // will increase this value for production
      ->limit(2)
      ->find($connection);

    // Get the SwiftMailer
    $mailer = $this->getMailer();

    // send emails to collectors and mark as sent
    foreach ($collectors as $collector)
    {
      /* @var $collector Collector */
      $cqEmail = new cqEmail($mailer);
      $cqEmail->send('Collector/newsletter_subscription_confirmation', array(
        'to' => 'anton@collectorsquest.com'
        //'to' => $collector->getEmail()
      ));

      // @todo mark collector as received email
    }
  }

}
