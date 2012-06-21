<?php

require_once dirname(__FILE__) . '/../lib/collectibleForSaleGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/collectibleForSaleGeneratorHelper.class.php';

/**
 * collectibleForSale actions.
 *
 * @package    CollectorsQuest
 * @subpackage collectibleForSale
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 2211 2011-06-30 20:36:50Z yanko $
 */
class collectibleForSaleActions extends autoCollectibleForSaleActions
{

  public function buildQuery()
  {
    /* @var $query CollectibleForSaleQuery */
    $query = parent::buildQuery();

    $query->innerJoinCollectible();

    return $query;
  }

  public function executeExport1(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
    $filename = sprintf('marketplace_export_%s.csv', date('Y_m_d_(hi)'));

    header("Expires: 0");
    header("Cache-control: private");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Description: File Transfer");
    header('Content-Type: text/csv, charset=UTF-8; encoding=UTF-8');
    header("Content-disposition: attachment; filename=". $filename);

    $out = fopen('php://output', 'w');

    $criteria = new Criteria();
    $criteria->clearSelectColumns();
    $criteria->addSelectColumn(CollectibleForSalePeer::COLLECTIBLE_ID);
    $criteria->addJoin(CollectibleForSalePeer::COLLECTIBLE_ID, CollectiblePeer::ID);
    $criteria->addJoin(CollectiblePeer::COLLECTOR_ID, CollectorPeer::ID);
    $criteria->addSelectColumn(CollectiblePeer::NAME);
    $criteria->addSelectColumn(CollectibleForSalePeer::CONDITION);
    $criteria->addSelectColumn(CollectibleForSalePeer::PRICE);
    $criteria->addSelectColumn(CollectorPeer::DISPLAY_NAME);
    $criteria->addSelectColumn(CollectibleForSalePeer::IS_PRICE_NEGOTIABLE);
    $criteria->addSelectColumn(CollectibleForSalePeer::IS_SHIPPING_FREE);
    $criteria->addSelectColumn(CollectibleForSalePeer::IS_SOLD);
    $criteria->addSelectColumn(CollectibleForSalePeer::CREATED_AT);

//    CollectibleForSalePeer::addSelectColumns($criteria);

    $stmt = CollectibleForSalePeer::doSelectStmt($criteria);

    if ($stmt->rowCount())
    {
      /* @var $collectibleForSale CollectibleForSale */
      while ($collectibleForSale = $stmt->fetch(PDO::FETCH_NUM))
      {
        fputcsv($out, $collectibleForSale);
      }
    }

    $stmt->closeCursor();

    fclose($out);
    return sfView::NONE;
  }

}
