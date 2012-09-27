<?php

require 'lib/model/om/BasePrivateMessageQuery.php';

class PrivateMessageQuery extends BasePrivateMessageQuery
{

  /**
   * Order by Sender Username
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
