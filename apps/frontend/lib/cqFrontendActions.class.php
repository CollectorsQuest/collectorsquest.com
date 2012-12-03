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

  /**
   * Increments a counter column using SimpleCalculationsBehavior and iceLibsPlugin
   *
   * The column is incremented only if the currently logged in collector is not the
   * owner of the object, or the user is not logged in.
   *
   * The increment is done in a delayed function and in bulk (by default, every 5
   * increments are written out to the DB)
   *
   * @param     BaseObject $obj
   * @param     string $counter_column
   * @param     string $by
   *
   * @return    void
   */
  public function incrementCounter(BaseObject $obj, $counter_column, $by = '+1')
  {
    if (!$this->getCollector()->isOwnerOf($obj))
    {
      $this->getResponse()->addDelayedFunction(array($obj, 'updateColumn'), array($counter_column, $by));
    }
  }

}
