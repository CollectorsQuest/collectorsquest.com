<?php

class batchAutorespondersMonthTask extends sfBaseTask
{
  /** @var sfApplicationConfiguration */
  protected $configuration;

  protected function configure()
  {
    unset($_SERVER['PATH_TRANSLATED'], $_SERVER['SCRIPT_NAME']);

    $this->namespace  = 'batch';
    $this->name       = 'autoresponders-month';

    $this->addArgument('application', sfCommandArgument::OPTIONAL, 'The application name', 'frontend');
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev');
    $this->addOption('limit', null, sfCommandOption::PARAMETER_REQUIRED, 'Limit of emails to send', 100);
  }

  protected function execute($arguments = array(), $options = array())
  {
    $_SERVER['HTTP_HOST'] = sfConfig::get('app_www_domain');

    cqContext::createInstance($this->configuration);

    // Database initialization
    new sfDatabaseManager($this->configuration);

    // how many letters we send per action
    $limit = $options['limit'] ?: 100;

    // Get the SwiftMailer
    $mailer = $this->getMailer();

    /*
     * We want to send autoresponders to everyone who has not received one yet
     * and has not been seen for more than a month.
     *
     * @var $q CollectorQuery
     */
    $q = CollectorQuery::create()
      ->filterByExtraPropertyWithDefault(CollectorPeer::PROPERTY_AUTORESPONDERS_ONE_MONTH_INACTIVITY, false, false)
      ->filterByLastSeenAt(date('Ymd000000', strtotime('-30 days')), Criteria::GREATER_EQUAL)
      ->_and()
      ->filterByLastSeenAt(date('Ymd000000', strtotime('-31 days')), Criteria::LESS_THAN)
      ->limit($limit);

    /**
     * Get all the collectors
     *
     * @var $collectors Collector[]
     */
    $collectors = $q->find();

    // Send an email to each collector
    foreach ($collectors as $collector)
    {
      if ($collector->getEmail() && $collector->countCollectorCollections() === 0)
      {
        $cqEmail = new cqEmail($mailer);
        $cqEmail->send('Collector/one_month_inactivity_reminder', array(
          'to'     => $collector->getEmail(),
          'params' => array(
            'collector' => $collector
          )
        ));
      }

      $collector->setProperty(CollectorPeer::PROPERTY_AUTORESPONDERS_ONE_MONTH_INACTIVITY, true);
      $collector->save();
    }
  }

}
