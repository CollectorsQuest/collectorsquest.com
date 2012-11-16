<?php
require_once dirname(__FILE__) . '/../lib/collectorsGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/collectorsGeneratorHelper.class.php';

/**
 * collectors actions.
 *
 * @package    CollectorsQuest
 * @subpackage collectors
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class collectorsActions extends autoCollectorsActions
{

  /**
   * @param  sfWebRequest  $request
   * @return sfView
   */
  public function executeList(sfWebRequest $request)
  {
    $collectors = CollectorPeer::retrieveForSelect(
      $request->getParameter('q'), $request->getParameter('limit')
    );

    return $this->renderText(json_encode($collectors));
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeAutoLogin(sfWebRequest $request)
  {
    /* @var $collector Collector */
    $collector = $this->getRoute()->getObject();

    if ($collector)
    {
      $hash = $collector->getAutoLoginHash();
      $url = sfProjectConfiguration::getActive()->generateFrontendUrl('auto_login', array('hash' => $hash));

      $this->redirect(rtrim($url, '/'), 301);
    }

    return sfView::ERROR;
  }

  /**
   * Action MarkAsSpam
   */
  public function executeMarkAsSpam()
  {
    try
    {
      /* @var $collector Collector */
      $collector = $this->getRoute()->getObject();
      $collector->markAsSpam();

      $this->getUser()->setFlash('notice', sprintf('Collector "%s" marked as spam', $collector->getUsername()));
    }
    catch (Exception $e)
    {
      $this->getUser()->setFlash('error', 'There was an error and the operation did not succeed!');
    }

    $this->redirect('collector');
  }

  /**
   * Action MarkAsHam
   */
  public function executeMarkAsHam()
  {
    try
    {
      /* @var $collector Collector */
      $collector = $this->getRoute()->getObject();
      $collector->markAsHam();

      $this->getUser()->setFlash('notice', sprintf('Collector "%s" marked as ham', $collector->getUsername()));
    }
    catch (Exception $e)
    {
      $this->getUser()->setFlash('error', 'There was an error and the operation did not succeed!');
    }

    $this->redirect('collector');
  }

  public function executeSyncWithMailChimp()
  {
    $mc = cqStatic::getMailChimpClient();

    $collectors = CollectorQuery::create()
     ->filterByCreatedAt(strtotime('today'), Criteria::GREATER_EQUAL)
     ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
     ->orderBy('CreatedAt', Criteria::DESC)
     ->find();

    $i = 0;

    /* @var $collector Collector */
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
        'PAGEVIEWS' => $collector->getVisitorInfoNumPageViews(),
        'VISITS' => $collector->getVisitorInfoNumVisits(),
        'NEWSLETTER' => $collector->getPreferencesNewsletter(),
        'VISITED_AT' => $collector->getLastVisitedAt('m/d/Y'),
        'SEEN_AT' => $collector->getLastSeenAt('m/d/Y'),
        'CREATED_AT' => $collector->getCreatedAt('m/d/Y'),
      );

      $mc->listSubscribe(
        '4b51c2b29c', $collector->getEmail(), $fields,
        'html', false, true, true, false
      );

      $i++;
    }

    $this->getUser()->setFlash(
      'success', 'Synced <strong>'. $i .'</strong> collectors with the MailChimp subscribers list'
    );

    $this->redirect('@collector');
  }

  /**
   * List action for toggling whether posting timeouts for PMs and Comments apply
   * to a specific user
   */
  public function executeListTogglePostingTimeouts(sfWebRequest $request)
  {
    /* @var $collector Collector */
    $collector = $this->getRoute()->getObject();

    $collector->setTimeoutIgnoreForUser(!$collector->getTimeoutIgnoreForUser());
    $collector->save();

    if ($collector->getTimeoutIgnoreForUser())
    {
      $this->getUser()->setFlash('notice', sprintf(
        'Timeout restrictions were removed for user %s.',
        $collector->getDisplayName()
      ));
    }
    else
    {
      $this->getUser()->setFlash('notice', sprintf(
        'Timeout restrictions were re-added for user %s.',
        $collector->getDisplayName()
      ));
    }

    return $this->redirect('@collector');
  }

  /**
   * Mark as spam and delete a collector
   */
  public function executeListSpamAndDestroy()
  {
    /* @var $collector Collector */
    $collector = $this->getRoute()->getObject();

    $this->getUser()->setFlash('notice', sprintf(
      'The collector %s was marked as spam and removed from the CollectorsQuest.',
      $collector->getDisplayName()
    ));

    $collector->markAsSpam();
    $collector->delete();

    return $this->redirect('@collector');
  }

}
