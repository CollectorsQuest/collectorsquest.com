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
    $collectors = CollectorPeer::retrieveForSelect($request->getParameter('q'), $request->getParameter('limit'));

    return $this->renderText(json_encode($collectors));
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeExport(sfWebRequest $request)
  {
    $filename = sprintf('collectors_export_%s.csv', date('Y_m_d_(hi)'));

    header("Expires: 0");
    header("Cache-control: private");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Description: File Transfer");
    header('Content-Type: text/csv, charset=UTF-8; encoding=UTF-8');
    header("Content-disposition: attachment; filename=" . $filename);

    $out = fopen('php://output', 'w');

    /* @var $criteria Criteria */
    $criteria = $this->buildQuery();
    $criteria->clearSelectColumns();
    $criteria->addSelectColumn(CollectorPeer::ID);
    $criteria->addSelectColumn(CollectorPeer::USERNAME);
    $criteria->addSelectColumn(CollectorPeer::DISPLAY_NAME);
    $criteria->addSelectColumn(CollectorPeer::EMAIL);
    $criteria->addAsColumn('collections', sprintf('COUNT(%s)', CollectorCollectionPeer::ID));
    $criteria->addSelectColumn(CollectorPeer::CREATED_AT);
    $criteria->addJoin(CollectorPeer::ID, CollectorCollectionPeer::COLLECTOR_ID, Criteria::LEFT_JOIN);
    $criteria->addGroupByColumn(CollectorCollectionPeer::COLLECTOR_ID);
//    $criteria->addHaving('collections', 0, Criteria::GREATER_THAN);

    $stmt = CollectorPeer::doSelectStmt($criteria);

    if ($stmt->rowCount())
    {
      /* @var $collectibleForSale CollectibleForSale */
      while ($collector = $stmt->fetch(PDO::FETCH_NUM))
      {
        fputcsv($out, $collector);
      }
    }

    $stmt->closeCursor();

    fclose($out);
    return sfView::NONE;
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
      $url = sfProjectConfiguration::getActive()->generateFrontendUrl('collector_auto_login', array('hash' => $hash));

      $this->redirect($url, 301);
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

  public function executeChangeCqnextAccessAllowed()
  {
    /* @var $collector Collector */
    $collector = $this->getRoute()->getObject();
    $collector->setCqnextAccessAllowed(
      !$collector->getCqnextAccessAllowed()
    );
    $collector->save();

    $this->redirect('collector');
  }

}
