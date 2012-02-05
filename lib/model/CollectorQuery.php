<?php

/**
 * Skeleton subclass for performing query and update operations on the 'collector' table.
 *
 * @package    propel.generator.lib.model
 */
class CollectorQuery extends BaseCollectorQuery
{
  public function filterBySpaminess($level, $criteria = Criteria::EQUAL)
  {
    switch ($level)
    {
      case 'green':
        $this->filterBySpamScore(30, Criteria::LESS_EQUAL);
        break;
      case 'yellow':
        $this->filterBySpamScore(30, Criteria::GREATER_THAN);
        $this->filterBySpamScore(60, Criteria::LESS_EQUAL);
        break;
      case 'red':
        $this->filterBySpamScore(60, Criteria::GREATER_THAN);
        break;
    }

    return $this;
  }
}
