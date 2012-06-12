<?php

/**
 * This is for correct IDE code suggests
 *
 * @method cqFrontendUser getUser()
 * @method cqWebRequest getRequest()
 * @method cqWebResponse getResponse()
 */
class cqFrontendComponents extends sfComponents
{
  /**
   * @return Collector
   */
  protected function getCollector()
  {
    return $this->getUser()->getCollector();
  }
}
