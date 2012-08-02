<?php

/**
 * @method  cqFrontendUser getUser()
 */
abstract class cqFrontendAction extends cqBaseAction
{
  /**
   * @param  boolean $strict
   *
   * @return Collector
   */
  public function getCollector($strict = false)
  {
    return $this->getUser()->getCollector($strict);
  }

  /**
   * @param  boolean $strict
   *
   * @return Seller
   */
  public function getSeller($strict = false)
  {
    return $this->getUser()->getSeller($strict);
  }
}
