<?php

/**
 * A simple check task for collector emails
 */
class badNamesListTask extends sfBaseTask
{
  /* @var sfApplicationConfiguration */
  protected $configuration;

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->namespace = 'debug';
    $this->name      = 'badnameslist';
  }

  protected function execute($arguments = array(), $options = array())
  {
    cqContext::createInstance($this->configuration);

    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);

    /* @var $propel PropelPDO */
    $propel = $databaseManager->getDatabase('propel')->getConnection();

    $stream = fopen(sfConfig::get('sf_cache_dir').'/bad_names.csv', 'w+');
    $delimeter = ',';
    $enclosure = '"';

    $this->configuration->loadHelpers(array('cqLinks'));

    $collections = CollectionQuery::create()
      ->useCollectorCollectionQuery()
        ->orderByCollectorId(Criteria::ASC)
      ->endUse()
      ->find($propel);

    $v = new cqValidatorName();

    $countCollection = 0;
    $countCollectibles = 0;
    $totalCountCollection = 0;
    $totalCountCollectibles = 0;
    $count = 0;

    $collectorsIds = array();
    fputcsv($stream, array('Id','Username','Display Name','Email',
      'Collection ID','Collection Name','Collection Url',
      'Collectible ID','Collectible Name','Collectible Url'
    ), $delimeter, $enclosure);
    foreach ($collections as $collection)
    {
      $totalCountCollection++;
      /** @var  $collection Collection */

      try
      {
        $v->clean($collection->getName());
      }
      catch (sfValidatorError $e)
      {
        $this->log(sprintf('Collection ID: %d  Name: %s', $collection->getId(), $collection->getName()));
        $count++;
        $countCollection++;
        if (!in_array($collection->getCollectorId(), $collectorsIds))
        {
          $this->addCollectorRow($stream, $collection->getCollector(), $delimeter, $enclosure);
          $collectorsIds[] = $collection->getCollectorId();
        }
        fputcsv($stream, array('', '', '', '',
          $collection->getId(), $collection->getName(),
          'http://www.collectorsquest.com' . url_for_collection($collection)), $delimeter, $enclosure);
      }

      $q = CollectibleQuery::create();
      $q->filterByCollectionId($collection->getId());
      $q->setFormatter(ModelCriteria::FORMAT_ARRAY);
      $collectiblesArray= $q->find($propel);
      foreach ($collectiblesArray as $collectibleArray)
      {
        $totalCountCollectibles++;
        /** @var  $collectible Collectible */
        try
        {
          $v->clean($collectibleArray['Name']);
        }
        catch (sfValidatorError $e)
        {
          $this->log(sprintf('Collectible ID: %d  Name: %s',
            $collectibleArray['Id'], $collectibleArray['Name']));
          $count++;
          $countCollectibles++;
          $collectible = new Collectible();
          $collectible->fromArray($collectibleArray);

          if (!in_array($collectible->getCollectorId(), $collectorsIds))
          {
            $this->addCollectorRow($stream, $collectible->getCollector(), $delimeter, $enclosure);
            $collectorsIds[] = $collectible->getCollectorId();
          }
          fputcsv($stream, array('', '', '', '', '', '', '', $collectible->getId(),
            $collectible->getName(), 'http://www.collectorsquest.com' . url_for_collectible($collectible, false)),
            $delimeter, $enclosure);
        }
      }
    }

    fclose($stream);

    $this->log(sprintf('Checked Collections %d  collectibles: %s ',
      $totalCountCollection, $totalCountCollectibles));

    $this->log(sprintf('Founded Collectors %s Collections %d  collectibles: %s ',
      count($collectorsIds), $countCollection, $countCollectibles));

    $this->log('Total Bad Records: '.$count);
    $this->log('You can find report here '.sfConfig::get('sf_cache_dir').'/bad_names.csv');
  }

  private function addCollectorRow($stream, Collector $collector,$delimeter, $enclosure)
  {
    fputcsv($stream, array($collector->getId(), $collector->getUsername(),
        $collector->getDisplayName(),$collector->getEmail()), $delimeter, $enclosure);
  }

}
