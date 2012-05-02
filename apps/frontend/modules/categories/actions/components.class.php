<?php

class categoriesComponents extends cqFrontendComponents
{

  public function executeSidebar()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebarCategory()
  {
    if (!$this->category = CollectionCategoryPeer::retrieveByPk($this->getRequestParameter('id')))
    {
      return sfView::NONE;
    }

    return sfView::SUCCESS;
  }

}
