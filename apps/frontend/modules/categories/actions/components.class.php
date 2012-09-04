<?php

class categoriesComponents extends cqFrontendComponents
{

  public function executeSidebar()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebarCategory()
  {
    return $this->getVar('category') ? sfView::SUCCESS : sfView::NONE;
  }

}
