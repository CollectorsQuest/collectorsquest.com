<?php

require 'lib/model/om/BaseCollectorQuery.php';

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

  public function filterByCqnextAccessAllowed($value)
  {
    return $this
      ->filterByExtraPropertyWithDefault(
        CollectorPeer::PROPERTY_CQNEXT_ACCESS_ALLOWED,
        $value,
        CollectorPeer::PROPERTY_CQNEXT_ACCESS_ALLOWED_DEFAULT_VALUE
      );
  }

  /**
   * Filter by Newsletter property
   *
   * @param $value integet
   * @return CollectorQuery
   */
  public function filterByNewsletter($value)
  {
    return $this
      ->filterByExtraProperty(
      CollectorPeer::PROPERTY_PREFERENCES_NEWSLETTER,
      $value=='0'?'':$value,
      CollectorPeer::PROPERTY_PREFERENCES_NEWSLETTER_DEFAULT
    );
  }
}
