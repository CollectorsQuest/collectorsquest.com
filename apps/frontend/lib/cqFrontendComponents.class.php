<?php

/**
 * This is for correct IDE code suggests
 *
 * @method cqFrontendUser getUser()
 */
class cqFrontendComponents extends cqBaseComponents
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
