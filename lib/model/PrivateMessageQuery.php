<?php

require 'lib/model/om/BasePrivateMessageQuery.php';

class PrivateMessageQuery extends BasePrivateMessageQuery
{
  /**
   * Filter the query by a related Collector username
   *
   * @param null $username
   * @param null $comparison
   * @param string $alias
   * @return ModelCriteria
   */
  public function filterByCollectorSenderUsername($username = null, $comparison = null, $alias = 'sender_col')
  {
    return $this->useCollectorRelatedBySenderQuery($alias)
        ->filterByUsername($username, $comparison)
      ->endUse();
  }

  /**
   * Filter the query by a related Collector username
   *
   * @param null $username
   * @param null $comparison
   * @param string $alias
   * @return ModelCriteria
   */
  public function filterByCollectorReceiverUsername($username = null, $comparison = null, $alias = 'receiver_col')
  {
    return $this->useCollectorRelatedByReceiverQuery($alias)
        ->filterByUsername($username, $comparison)
      ->endUse();
  }

  /**
   * Order by Sender Username
   *
   * @param $type string
   * @return PrivateMessageQuery
   */
  public function orderBySenderName($type)
  {
    $this->addAlias('sender_col', CollectorPeer::TABLE_NAME);
    $this->addJoin(
      PrivateMessagePeer::SENDER, CollectorPeer::alias('sender_col', CollectorPeer::ID), Criteria::LEFT_JOIN
    );
    switch ($type) {
      case 'asc':
        $this->addAscendingOrderByColumn(CollectorPeer::alias('sender_col', CollectorPeer::USERNAME));
        break;
      case 'desc':
        $this->addDescendingOrderByColumn(CollectorPeer::alias('sender_col', CollectorPeer::USERNAME));
        break;
    }

    return $this;
  }

  /**
   * Order by Receiver Username
   * @param $type string
   * @return PrivateMessageQuery
   */
  public function orderByReceiverName($type)
  {
    $this->addAlias('receiver_col', CollectorPeer::TABLE_NAME);
    $this->addJoin(PrivateMessagePeer::RECEIVER, CollectorPeer::alias('receiver_col', CollectorPeer::ID), Criteria::LEFT_JOIN);
    switch ($type) {
      case 'asc':
        $this->addAscendingOrderByColumn(CollectorPeer::alias('receiver_col', CollectorPeer::USERNAME));
        break;
      case 'desc':
        $this->addDescendingOrderByColumn(CollectorPeer::alias('receiver_col', CollectorPeer::USERNAME));
        break;
    }
    return $this;
  }
}
