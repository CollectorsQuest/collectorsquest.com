<?php

require 'lib/model/om/BasePrivateMessage.php';

class PrivateMessage extends BasePrivateMessage
{
  /**
   * @param  Collector $v
   * @return void
   */
  public function setSender($v)
  {
    if ($v instanceof Collector)
    {
      $id = $v->getId();
    }
    else
    {
      $id = (int) $v;
    }

    parent::setSender($id);
  }

  /**
   * @param  Collector $v
   * @return void
   */
  public function setReceiver($v)
  {
    if ($v instanceof Collector)
    {
      $id = $v->getId();
    }
    else
    {
      $id = (int) $v;
    }

    parent::setReceiver($id);
  }

  /**
   * @param  string $v
   * @return void
   */
  public function setSubject($v)
  {
    parent::setSubject(strip_tags($v));
  }

  public function setBody($v, $clean = false)
  {
    $v = trim($v);
    $v = (!$this->getIsRich()) ? nl2br($v) : $v;
    $v = (true === $clean) ? IceStatic::cleanText($v, false, 'b, u, i, strong, br', $this->getIsRich() ? -1 : 0) : $v;

    parent::setBody($v);
  }

  public function getCollectorRelatedBySender()
  {
    $c = new Criteria();
    $c->add(CollectorPeer::ID, $this->getSender());

    return CollectorPeer::doSelectOne($c);
  }

  public function getCollectorRelatedByReceiver()
  {
    $c = new Criteria();
    $c->add(CollectorPeer::ID, $this->getReceiver());

    return CollectorPeer::doSelectOne($c);
  }

  public function getReplySubject()
  {
    $subject = "RE: ". $this->getSubject();

    do {
      $subject = str_ireplace("RE: RE: ", "RE: ", $subject, $count);
    }
    while ($count > 0);

    return $subject;
  }

  public function getThreadCount()
  {
    $c = new Criteria();
    $c->add(PrivateMessagePeer::THREAD, $this->getThread());
    $c->add(PrivateMessagePeer::IS_DELETED, false);

    return PrivateMessagePeer::doCount($c);
  }
}
