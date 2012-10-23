<?php

class batchSellersAnonymousTask extends sfBaseTask
{

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
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $routing = sfContext::createInstance($this->configuration)->getRouting();

    $pattern = '/((?:\$|£|€)\s*\d+|(?:shipping|(?<!not\s)for sale|selling))/i';
    $baseUrl = 'http://' . sfConfig::get('app_www_domain');

    $body = "Collectibles:\n";
    $collectibles = CollectibleQuery::create()
      ->filterByUpdatedAt(strtotime('-1 day'), Criteria::GREATER_EQUAL)
      ->useCollectibleForSaleQuery(null, Criteria::LEFT_JOIN)
      ->filterByCollectibleId(null, Criteria::ISNULL)
      ->endUse()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find($connection);

    $this->log(sprintf('Processing %d collectibles', $collectibles->count()));
    /* @var $collectibles Collectible[] */
    foreach ($collectibles as $collectible)
    {
      foreach (array('name', 'description') as $field)
      {
        $string = $collectible->{'get' . ucfirst($field)}();
        if (preg_match($pattern, $string))
        {
          $body .= sprintf("%s\n", $baseUrl . $routing->generate('collectible_by_slug', array('sf_subject'=>$collectible)));
          $suspicious['collectibles'][] = $collectible->getId();
          if ($options['debug'])
          {
            $this->logSection('collectible', sprintf('%d: %s', $collectible->getId(), preg_replace($pattern, "\033[01;31m$1\033[0m", $string)));
          }
        }
      }
    }

    $body .= "\nCollections:\n";
    $collections = CollectionQuery::create()
      ->filterByUpdatedAt(strtotime('-1 day'), Criteria::GREATER_EQUAL)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find($connection);

    $this->log(sprintf('Processing %d collections', $collections->count()));
    /* @var $collections Collection[] */
    foreach ($collections as $collection)
    {
      foreach (array('name', 'description') as $field)
      {
        $string = $collection->{'get' . ucfirst($field)}();
        if (preg_match($pattern, $string))
        {
          $body .= sprintf("%s\n", $baseUrl . $routing->generate('collectible_by_slug', array('sf_subject'=>$collection)));
          $suspicious['collections'][] = $collection->getId();
          if ($options['debug'])
          {
            $this->logSection('collection', sprintf('%d: %s', $collection->getId(), preg_replace($pattern, "\033[01;31m$1\033[0m", $string)));
          }
        }
      }
    }

    $body .= "\nCollectors:\n";
    $collectors = CollectionQuery::create()
      ->filterByUpdatedAt(strtotime('-1 day'), Criteria::GREATER_EQUAL)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->find($connection);

    $this->log(sprintf('Processing %d collectors', $collectors->count()));
    /* @var $collectors Collector[] */
    foreach ($collectors as $collector)
    {
      foreach (array('name', 'description') as $field)
      {
        $string = $collector->{'get' . ucfirst($field)}();
        if (preg_match($pattern, $string))
        {
          $body .= sprintf("%s\n", $baseUrl . $routing->generate('collectible_by_slug', array('sf_subject'=>$collector)));
          $suspicious['collectors'][] = $collector->getId();
          if ($options['debug'])
          {
            $this->logSection('collector', sprintf('%d: %s', $collector->getId(), preg_replace($pattern, "\033[01;31m$1\033[0m", $string)));
          }
        }
      }
    }

    $mailer = $this->getMailer();
    $message = $mailer->compose('no-reply@collectorsquest.com',
      'ysimeonoff@collectorsquest.com', 'Suspicious hidden sellers notification',
      $body);

    try
    {
//      var_dump($body);
      $mailer->send($message);
    } catch (Swift_SwiftException $e)
    {
      //
    }
  }
}
