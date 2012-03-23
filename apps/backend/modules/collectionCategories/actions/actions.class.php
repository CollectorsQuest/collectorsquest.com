<?php

require_once dirname(__FILE__).'/../lib/collectionCategoriesGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/collectionCategoriesGeneratorHelper.class.php';

/**
 * collectionCategories actions.
 *
 * @package    CollectorsQuest
 * @subpackage collectionCategories
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class collectionCategoriesActions extends autoCollectionCategoriesActions
{

  /**
   * Action Parent
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeParent(sfWebRequest $request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $items = CollectionCategoryQuery::create()
        ->filterByName("%$q%")
        ->limit($limit)
        ->find()
        ->toKeyValue('Id', 'Name');

    return $this->renderText(json_encode($items));
  }

}
