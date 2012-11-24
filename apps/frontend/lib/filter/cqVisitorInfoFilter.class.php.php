<?php

/**
 * If the current user is a logged in collector set LastVisitedAt and save
 */
class cqVisitorInfoFilter extends sfFilter
{

  /** @var array A list of update callbacks for properties */
  protected static $handlers_for_properties = array(
    CollectorPeer::PROPERTY_VISITOR_INFO_FIRST_VISIT_AT => 'updatePropFirstVisitAt',
    CollectorPeer::PROPERTY_VISITOR_INFO_LAST_VISIT_AT => 'updatePropLastVisitAt',
    CollectorPeer::PROPERTY_VISITOR_INFO_NUM_VISITS => 'updatePropNumVisits',
    CollectorPeer::PROPERTY_VISITOR_INFO_NUM_PAGE_VIEWS => 'updatePropNumPageViews',
  );

  /**
   * Main filter chain execution
   */
  public function execute($filterChain)
  {
    /* @var $request cqWebRequest */
    $request = $this->context->getRequest();

    if (!$request->isXmlHttpRequest())
    {
      /* @var $sf_user cqFrontendUser */
      $sf_user = $this->context->getUser();

      $this->updateLastVisitedAtForAuthenticated($sf_user);
      $this->updateVisitorInfo($sf_user);

      /**
       * Send information about the Collector to NewRelic
       */
      if (extension_loaded('newrelic') && ($collector = $sf_user->getCollector(true)))
      {
        newrelic_set_user_attributes($collector->getUsername(), $collector->getId(), SF_APP);
      }
    }

    $filterChain->execute();
  }

  /**
   * Iterate over self::$handlers_for_properties and call the update functions
   * for those properties. Afterwards persist the new data through cqFrontendUser
   *
   * @param     cqFrontendUser $sf_user
   */
  protected function updateVisitorInfo(cqFrontendUser $sf_user)
  {
    $visitor_info = (array) $sf_user->getVisitorInfoArray();

    foreach (self::$handlers_for_properties as $prop_name => $update_method)
    {
      $visitor_info[$prop_name] = call_user_func(
        array($this, $update_method),
        $sf_user,
        isset($visitor_info[$prop_name])
          ? $visitor_info[$prop_name]
          : null
      );
    }

    $sf_user->setVisitorInfoArray($visitor_info);
  }

  /**
   * Update PROPERTY_VISITOR_INFO_FIRST_VISIT_AT
   *
   * @param     cqFrontendUser $sf_user
   * @param     string $current_val
   *
   * @return    string
   */
  protected function updatePropFirstVisitAt(cqFrontendUser $sf_user, $current_val)
  {
    return $current_val ?: date('Y-m-d H:i:s');
  }

  /**
   * Update PROPERTY_VISITOR_INFO_LAST_VISIT_AT
   *
   * @param     cqFrontendUser $sf_user
   * @param     string $current_val
   *
   * @return    string
   */
  protected function updatePropLastVisitAt(cqFrontendUser $sf_user, $current_val)
  {
    return date('Y-m-d H:i:s');
  }

  /**
   * Update PROPERTY_VISITOR_INFO_NUM_VISITS
   *
   * @param     cqFrontendUser $sf_user
   * @param     integer $current_val
   *
   * @return    integer
   */
  protected function updatePropNumVisits(cqFrontendUser $sf_user, $current_val)
  {
    $last_visit_at = $sf_user->getVisitorInfo(
      CollectorPeer::PROPERTY_VISITOR_INFO_LAST_VISIT_AT);

    $then = new DateTime($last_visit_at);
    $interval = $then->diff(new DateTime());

    // if more than 12 hours or first visit
    if ($interval->days || $interval->h > 12 || null === $last_visit_at)
    {
      return $current_val+1;
    }
    else
    {
      return $current_val;
    }
  }

  /**
   * Update PROPERTY_VISITOR_INFO_NUM_PAGE_VIEWS
   *
   * @param     cqFrontendUser $sf_user
   * @param     integer $current_val
   *
   * @return    integer
   */
  protected function updatePropNumPageViews(cqFrontendUser $sf_user, $current_val)
  {
    return $current_val+1;
  }

  /**
   * Update the actual collector.last_visited_at DB field, when the current user
   * is logged in
   *
   * @param cqFrontendUser $sf_user
   */
  protected function updateLastVisitedAtForAuthenticated(cqFrontendUser $sf_user)
  {
    if (( $collector = $sf_user->getCollector($strict = true) ))
    {
      $collector->setLastVisitedAt(time());

      // prevent updated at from being set to the current time;
      // if the column has been manually modified TimestampableBehavior
      // won't auto-set it to the current time
      $updated_at = $collector->getUpdatedAt(null);
      $collector->setUpdatedAt(false);
      $collector->setUpdatedAt($updated_at);

      /* @var $response IceWebResponse */
      $response = $this->context->getResponse();

      $response->addDelayedFunction(function()
      {
        if ($collector = cqContext::getInstance()->getUser()->getCollector($strict = true))
        {
          $collector->save();
        }
      });
    }
  }

}
