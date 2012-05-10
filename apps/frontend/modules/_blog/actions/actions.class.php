<?php

class _blogActions extends cqFrontendActions
{
  public function executeIndex()
  {
    // We do not want the web debug bar on blog requests
    sfConfig::set('sf_web_debug', false);

    return sfView::SUCCESS;
  }
}
