<?php

class batchNewsletterConfirmationTask extends sfBaseTask
{
  /** @var sfApplicationConfiguration */
  protected $configuration;

  protected function configure()
  {
    unset($_SERVER['PATH_TRANSLATED'], $_SERVER['SCRIPT_NAME']);

    $this->namespace  = 'batch';
    $this->name       = 'newsletter-confirmation';

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

    // Get the array of Collector ID's we already sent to
    $last_id = (integer) file_get_contents('/www/tmp/newsletter-confirmation.txt');

    /**
     * Get all the collectors
     *
     * @var $collectors Collector[]
     */
    $collectors = CollectorQuery::create()
      ->filterById($last_id, Criteria::GREATER_THAN)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->limit($limit)
      ->orderBy('Id', Criteria::ASC)
      ->find();

    // Send an email to each collector
    foreach ($collectors as $collector)
    {
      if ($collector->getEmail())
      {
        $cqEmail = new cqEmail($mailer);
        $cqEmail->send('Collector/newsletter_subscription_confirmation', array(
          'to' => $collector->getEmail(),
          'params' => array(
            'oCollector' => $collector
          )
        ));
        echo $collector->getEmail() . "\n";
      }

      $last_id = $collector->getId();
    }

    // write the Collector ID's we already sent to
    file_put_contents('/www/tmp/newsletter-confirmation.txt', $last_id);
  }

}
