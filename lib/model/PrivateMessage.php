<?php

require 'lib/model/om/BasePrivateMessage.php';

class PrivateMessage extends BasePrivateMessage
{

  /**
   * @param     Collector $v
   * @return    PrivateMessage
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

    return parent::setSender($id);
  }

  /**
   * @param     Collector $v
   * @return    PrivateMessage
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

    return parent::setReceiver($id);
  }

  /**
   * @param     string $v
   * @return    PrivateMesage
   */
  public function setSubject($v)
  {
    return parent::setSubject(strip_tags($v));
  }

  /**
   * @param     string $v
   * @param     boolean $clean
   * @return    PrivateMessage
   */
  public function setBody($v, $clean = false)
  {
    $v = trim($v);
    $v = (!$this->getIsRich()) ? nl2br($v) : $v;
    $v = (true === $clean) ? IceStatic::cleanText($v, false, 'b, u, i, strong, br', $this->getIsRich() ? -1 : 0) : $v;

    return parent::setBody($v);
  }

  /**
   * @return    string
   */
  public function getReplySubject()
  {
    $subject = "RE: ". $this->getSubject();

    do {
      $subject = str_ireplace("RE: RE: ", "RE: ", $subject, $count);
    }
    while ($count > 0);

    return $subject;
  }

  /**
   * @return    integer
   */
  public function getThreadCount()
  {
    $c = new Criteria();
    $c->add(PrivateMessagePeer::THREAD, $this->getThread());
    $c->add(PrivateMessagePeer::IS_DELETED, false);

    return PrivateMessagePeer::doCount($c);
  }

  /**
   * Perform pre-save operations
   *
   * @param     PropelPDO $con
   * @return    boolean
   */
  public function preSave(PropelPDO $con = null)
  {
    if (null === $this->getThread())
    {
      $this->setThread(PrivateMessagePeer::generateThread());
    }

    return parent::preSave($con);
  }

}
