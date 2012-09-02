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
   * @param bool $strict
   * @return Collector
   */
  protected function getCollector($strict = false)
  {
    return $this->getUser()->getCollector($strict);
  }
}
