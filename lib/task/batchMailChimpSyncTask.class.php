<?php

class batchMailChimpSyncTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));

    $this->namespace        = 'batch';
    $this->name             = 'mailchimp-sync';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [batch:mailchimp-sync|INFO] task sync collectors with MailChimp.com

Call it with:

  [php symfony batch:mailchimp-sync|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);

    $mc = cqStatic::getMailChimpClient();

    $collectors = CollectorQuery::create()
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
      ->orderBy('CreatedAt', Criteria::DESC)
      ->find();

    /** @var $collector Collector */
    foreach ($collectors as $collector)
    {
      $avatar = !$collector->getProfile()->getIsImageAuto() && !$collector->hasPhoto() ?
        'Yes' : 'No';

      $fields = array(
        'ID' => $collector->getId(),
        'DNAME' => $collector->getDisplayName(),
        'AVATAR' => $avatar,
        'TYPE' => $collector->getUserType(),
        'NUMCTIONS' => $collector->countCollectorCollections(),
        'NUMCIBLES' => $collector->countCollectionCollectibles(),
        'COMPLETED' => (int) $collector->getProfile()->getProfileCompleted(),
        'VISITED_AT' => $collector->getLastVisitedAt('m/d/Y'),
        'SEEN_AT' => $collector->getLastSeenAt('m/d/Y'),
        'CREATED_AT' => $collector->getCreatedAt('m/d/Y'),
      );

      $mc->listSubscribe(
        '4b51c2b29c', $collector->getEmail(), $fields,
        'html', false, true, true, false
      );

      if ($mc->errorCode) {
        $this->logSection('error', $mc->errorMessage);
      } else {
        $this->logSection('success', 'Syncronized collector '. $collector->getDisplayName());
      }
    }

    return 0;
  }
}
