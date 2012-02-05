<?php

require_once dirname(__FILE__).'/../lib/featuredWeeksGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/featuredWeeksGeneratorHelper.class.php';

class featuredWeeksActions extends autoFeaturedWeeksActions
{
  protected function buildQuery()
  {
    /** @var $query FeaturedQuery */
    $query = parent::buildQuery();
    $query->filterByFeaturedModel('FeaturedWeek');

    return $query;
  }

  public function executeEdit(sfWebRequest $request)
  {
    parent::executeEdit($request);

    $this->form->setDefault('title', $this->FeaturedWeek->title);
    $this->form->setDefault('homepage_text', $this->FeaturedWeek->homepage_text);

    return sfView::SUCCESS;
  }
}
