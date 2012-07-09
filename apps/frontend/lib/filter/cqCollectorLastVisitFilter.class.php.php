<?php

/**
 * If the current user is a logged in collector set LastVisitedAt and save
 */
class cqCollectorLastVisitFilter extends sfFilter
{

  public function execute($filterChain)
  {
    /* @var $sf_user cqFrontendUser */
    $sf_user = $this->context->getUser();

    if (( $collector = $sf_user->getCollector($strict = true) ))
    {
      $collector->setLastVisitedAt(time());

      // prevent updated at from being set to the current time;
      // if the column has been manually modified TimestampableBehavior
      // won't auto-set it to the current time
      $updated_at = $collector->getUpdatedAt(null);
      $collector->setUpdatedAt(false);
      $collector->setUpdatedAt($updated_at);

      /* @var $response iceWebResponse */
      $response = $this->context->getResponse();

      $response->addDelayedFunction(array($collector, 'save'));
      //$collector->save();
    }

    $filterChain->execute();
  }

}
