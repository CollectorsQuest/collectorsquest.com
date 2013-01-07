<?php

class batchAutorespondersWeekTask extends sfBaseTask
{
  /** @var sfApplicationConfiguration */
  protected $configuration;

  protected function configure()
  {
    unset($_SERVER['PATH_TRANSLATED'], $_SERVER['SCRIPT_NAME']);

    $this->namespace  = 'batch';
    $this->name       = 'autoresponders-week';

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

    /** @var cqApplicationConfiguration $configuration */
    $configuration = sfProjectConfiguration::getActive();
    $configuration->loadHelpers(array('cqImages'));

    /*
     * We want to send autoresponders to everyone who has not received one yet and has not been
     * seen for more than a week
     *
     * @var $q CollectorQuery
     */
    $q = CollectorQuery::create()
      ->filterByExtraPropertyWithDefault(CollectorPeer::PROPERTY_AUTORESPONDERS_ONE_WEEK_INACTIVITY, false, false)
      ->filterByLastSeenAt(date(strtotime( '-1 week' )), Criteria::LESS_EQUAL)
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
      $email = $collector->getEmail();
      if ($email)
      {
        $cqEmail = new cqEmail($mailer);
        $cqEmail->send('Collector/one_week_inactivity_reminder', array(
          'to'     => $email,
          'params' => array(
            'collector'       => $collector,
            'collector_image' => image_tag_collector(
              $collector, $which = '235x315', $options =
                array('size' => '75x100', 'style' => 'float: left; margin-right: 8px;', 'absolute' => true)
            )
          )
        ));

        echo $email . "\n";

        $collector->setProperty(CollectorPeer::PROPERTY_AUTORESPONDERS_ONE_WEEK_INACTIVITY, true);
        $collector->save();
      }
    }
  }

}
