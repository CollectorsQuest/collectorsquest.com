<?php

/**
 * CollectibleForSale form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors
 */
class CollectibleForSaleForm extends BaseCollectibleForSaleForm
{

  public function configure()
  {
    $this->setupCollectibleIdField();
  }

  private function setupCollectibleIdField()
  {
    $this->setWidget('collectible_id', new BackendWidgetFormModelTypeAhead(array(
      'field'=>CollectiblePeer::NAME,
    )));

  }
}
