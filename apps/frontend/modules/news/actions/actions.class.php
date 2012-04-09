<?php

class newsActions extends cqFrontendActions
{
  public function executeIndex()
  {
    $q = wpPostQuery::create()
       ->orderByPostDate(Criteria::DESC)
       ->limit(7);

    $this->posts = $q->find();

    return sfView::SUCCESS;
  }
}
