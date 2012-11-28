<?php

class batchSellersAnonymousTask extends sfBaseTask
{
  /* @var sfApplicationConfiguration */
  protected $configuration;

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      new sfCommandOption('debug', null, sfCommandOption::PARAMETER_NONE, 'Debug mode'),
    ));

    $this->namespace = 'batch';
    $this->name = 'sellers-anonymous';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [batch:sellers-anonymous|INFO] task checks for keywords about selling collectibles.
Call it with:

  [php symfony batch:sellers-anonymous|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    /* @var $sf_context cqContext */
    $sf_context = cqContext::createInstance($this->configuration);

    /* @var $routing cqPatternRouting */
    $routing = $sf_context->getRouting();

    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $pattern = '/((?:\$|£|€)\s*\d+|(?:shipping|(?<!not\s)for sale|selling))/i';
    $baseUrl = 'http://' . sfConfig::get('app_www_domain');

    $body = "Collectibles:\n";

    if ($options['debug'])
    {
      $this->logSection('collectibles', 'Processing');
    }

    $collectibles = CollectibleQuery::create()
      ->isComplete()
      ->filterByUpdatedAt(strtotime('-1 day'), Criteria::GREATER_EQUAL)
      ->useCollectibleForSaleQuery(null, Criteria::LEFT_JOIN)
        ->filterByCollectibleId(null, Criteria::ISNULL)
      ->endUse()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find($connection);

    /* @var $collectibles Collectible[] */
    foreach ($collectibles as $collectible)
    {
      foreach (array('name', 'description') as $field)
      {
        $string = $collectible->{'get' . ucfirst($field)}();
        if (preg_match($pattern, $string))
        {
          $body .= sprintf("%s\n", $baseUrl . $routing->generate('collectible_by_slug', array('sf_subject' => $collectible)));
          $suspicious['collectibles'][] = $collectible->getId();
          if ($options['debug'])
          {
            $this->logSection('collectible+', sprintf(
              '%d: %s', $collectible->getId(), preg_replace($pattern, "\033[01;31m$1\033[0m", $string)
            ));
          }
        }
      }
    }

    $body .= "\nCollections:\n";
    if ($options['debug'])
    {
      $this->logSection('collections', 'Processing');
    }
    $collections = CollectionQuery::create()
      ->isComplete()
      ->filterByUpdatedAt(strtotime('-1 day'), Criteria::GREATER_EQUAL)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find($connection);

    /* @var $collections Collection[] */
    foreach ($collections as $collection)
    {
      foreach (array('name', 'description') as $field)
      {
        $string = $collection->{'get' . ucfirst($field)}();
        if (preg_match($pattern, $string))
        {
          $body .= sprintf("%s\n", $baseUrl . $routing->generate('collection_by_slug', array('sf_subject' => $collection)));
          $suspicious['collections'][] = $collection->getId();
          if ($options['debug'])
          {
            $this->logSection('collection+', sprintf(
              '%d: %s', $collection->getId(), preg_replace($pattern, "\033[01;31m$1\033[0m", $string)
            ));
          }
        }
      }
    }

    $body .= "\nCollectors:\n";
    if ($options['debug'])
    {
      $this->logSection('collectors', 'Processing');
    }
    $collectors = CollectorQuery::create()
      ->filterByIsPublic(true)
      ->filterByUpdatedAt(strtotime('-1 day'), Criteria::GREATER_EQUAL)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find($connection);

    /* @var $collectors Collector[] */
    foreach ($collectors as $collector)
    {
      foreach (array('displayName') as $field)
      {
        $string = $collector->{'get' . ucfirst($field)}();
        if (preg_match($pattern, $string))
        {
          $body .= sprintf("%s\n", $baseUrl . $routing->generate('collector_by_slug', array('sf_subject' => $collector)));
          $suspicious['collectors'][] = $collector->getId();
          if ($options['debug'])
          {
            $this->logSection('collector+', sprintf(
              '%d: %s', $collector->getId(), preg_replace($pattern, "\033[01;31m$1\033[0m", $string)
            ));
          }
        }
      }
    }

    try
    {
      $mailer = $this->getMailer();
      $mailer->composeAndSend(
        'no-reply@collectorsquest.com', 'info@collectorsquest.com', '[AUTOMATED] Sellers Anonymous', $body
      );
    }
    catch (Swift_SwiftException $e)
    {
      die('There was an error sending the email to info@collectorsquest.com');
    }
  }
}
