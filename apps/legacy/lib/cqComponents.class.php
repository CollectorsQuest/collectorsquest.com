<?php

/**
 * This is for correct IDE code suggests
 *
 * @method cqUser getUser()
 * @method cqWebRequest getRequest()
 * @method cqWebResponse getResponse()
 */
class cqComponents extends sfComponents
{
  /**
   * @return Collector
   */
  protected function getCollector()
  {
    return $this->getUser()->getCollector();
  }
}
