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

    $criteria = new Criteria();
    $criteria->clearSelectColumns();
    $criteria->addSelectColumn(CollectorPeer::ID);
    $criteria->addSelectColumn(CollectorPeer::USERNAME);
    $criteria->addSelectColumn(CollectorPeer::DISPLAY_NAME);
    $criteria->addSelectColumn(CollectorPeer::EMAIL);
    $criteria->addSelectColumn(CollectorPeer::CREATED_AT);

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

  public function executeAutoLogin(sfWebRequest $request)
  {
    $collector = CollectorQuery::create()->findOneById($request->getParameter('id'));

    if ($collector)
    {
      $hash = $collector->getAutoLoginHash();
      $this->redirect(sfProjectConfiguration::getActive()->generateFrontendUrl('collector_auto_login', array('hash' => $hash)), 301);
    }

    return sfView::ERROR;
  }

}
