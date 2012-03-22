<?php
/**
 * File: CollectorCollectionFormFilter.class.php
 *
 * @author zecho
 * @version $Id$
 *
 */

class BackendCollectorCollectionFormFilter extends BaseCollectorCollectionFormFilter
{

  public function configure()
  {
    $this->widgetSchema['collection_category_id'] = new bsWidgetFormInputTypeAhead(array(
      'source'    => $this->getOption('collection_category_id_url', sfContext::getInstance()->getController()->genUrl('collections/collectionCategory')),
    ));
  }
}
