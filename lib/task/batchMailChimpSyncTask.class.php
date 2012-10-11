<?php

ini_set('memory_limit', '256M');

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
    $batch = array();

    $q = CollectorQuery::create()
      ->filterByCreatedAt(strtotime('yesterday'), Criteria::GREATER_EQUAL)
      ->filterByCreatedAt(strtotime('today'), Criteria::LESS_THAN)
      ->orderBy('CreatedAt', Criteria::DESC)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $collectors Collector[] */
    $collectors = $q->find();

    /* @var $collector Collector */
    foreach ($collectors as $collector)
    {
      // Continue to the next Collector if there is a problem with the Profile
      if (!$profile = $collector->getProfile())
      {
        continue;
      }

      $avatar = !$profile->getIsImageAuto() && !$collector->hasPhoto() ?
        'Yes' : 'No';

      $newsletter = $collector->getPreferencesNewsletter() ?
        'Yes' : 'No';

      $batch[] = array(
        'EMAIL' => $collector->getEmail(),
        'EMAIL_TYPE' => 'html',
        'ID' => $collector->getId(),
        'DNAME' => $collector->getDisplayName(),
        'AVATAR' => $avatar,
        'TYPE' => $collector->getUserType(),
        'NUMCTIONS' => $collector->countCollectorCollections(),
        'NUMCIBLES' => $collector->countCollectionCollectibles(),
        'COMPLETED' => (int) $profile->getProfileCompleted(),
        'PAGEVIEWS' => $collector->getVisitorInfoNumPageViews(),
        'VISITS' => $collector->getVisitorInfoNumVisits(),
        'NEWSLETTER' => $newsletter,
        'VISITED_AT' => $collector->getLastVisitedAt('m/d/Y'),
        'SEEN_AT' => $collector->getLastSeenAt('m/d/Y'),
        'CREATED_AT' => $collector->getCreatedAt('m/d/Y'),
      );
    }

    // Do the API call to MailChimp only if there are new Collectors
    if (!empty($batch))
    {
      $result = $mc->listBatchSubscribe('4b51c2b29c', $batch, false, false, false);

      if ($mc->errorCode)
      {
        $this->logSection('error', 'Batch Subscribe failed!');
        $this->logSection('error', 'Code: '. $mc->errorCode);
        $this->logSection('error', 'Message: '. $mc->errorMessage);
      }
      else
      {
        $this->logSection('success', 'Added: '. $result['add_count']);
        $this->logSection('success', 'Updated: '. $result['update_count']);
        $this->logSection('success', 'Errors: '. $result['error_count']);

        foreach ($result['errors'] as $error)
        {
          $this->logSection('error', $error['email_address']. ' failed');
          $this->logSection('error', 'Code: '. $error['code']);
          $this->logSection('error', 'Message: '. $error['message']);
        }
      }
    }

    $q = CollectorQuery::create()
      ->filterByCreatedAt(strtotime('yesterday'), Criteria::LESS_THAN)
      ->orderBy('CreatedAt', Criteria::DESC)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $collectors Collector[] */
    $collectors = $q->find();

    /* @var $collector Collector */
    foreach ($collectors as $collector)
    {
      // Continue to the next Collector if there is a problem with the Profile
      if (!$profile = $collector->getProfile())
      {
        continue;
      }

      $avatar = !$profile->getIsImageAuto() && !$collector->hasPhoto() ?
        'Yes' : 'No';

      $newsletter = $collector->getPreferencesNewsletter() ?
        'Yes' : 'No';

      $fields = array(
        'ID' => $collector->getId(),
        'DNAME' => $collector->getDisplayName(),
        'AVATAR' => $avatar,
        'TYPE' => $collector->getUserType(),
        'NUMCTIONS' => $collector->countCollectorCollections(),
        'NUMCIBLES' => $collector->countCollectionCollectibles(),
        'COMPLETED' => (int) $profile->getProfileCompleted(),
        'PAGEVIEWS' => $collector->getVisitorInfoNumPageViews(),
        'VISITS' => $collector->getVisitorInfoNumVisits(),
        'NEWSLETTER' => $newsletter,
        'VISITED_AT' => $collector->getLastVisitedAt('m/d/Y'),
        'SEEN_AT' => $collector->getLastSeenAt('m/d/Y'),
        'CREATED_AT' => $collector->getCreatedAt('m/d/Y'),
      );

      $mc->listUpdateMember('4b51c2b29c', $collector->getEmail(), $fields, 'html', false);

      if ($mc->errorCode)
      {
        $this->logSection('error', $mc->errorMessage);
      }
      else
      {
        $this->logSection('success', 'Updated collector '. $collector->getEmail());
      }
    }

    $q = CollectorArchiveQuery::create()
      ->filterByCreatedAt(strtotime('yesterday'), Criteria::GREATER_EQUAL)
      ->filterByCreatedAt(strtotime('today'), Criteria::LESS_THAN)
      ->orderBy('CreatedAt', Criteria::DESC)
      ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);

    /** @var $collectors Collector[] */
    $collectors = $q->find();

    /* @var $collector Collector */
    foreach ($collectors as $collector)
    {
      $mc->listUnsubscribe('4b51c2b29c', $collector->getEmail(), true);

      if ($mc->errorCode)
      {
        $this->logSection('error', $mc->errorMessage);
      }
      else
      {
        $this->logSection('success', 'Unsibscribed collector '. $collector->getEmail());
      }
    }

    return 0;
  }
}
