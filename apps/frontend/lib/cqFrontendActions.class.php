<?php

/**
 * @method  cqFrontendUser        getUser()
 * @method  cqFrontWebController  getController()
 */
abstract class cqFrontendActions extends cqBaseActions
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
