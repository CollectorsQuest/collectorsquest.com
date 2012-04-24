<?php

class newsActions extends cqFrontendActions
{
  public function executeIndex()
  {
    $this->redirect('blog/index', 301);
  }
}
