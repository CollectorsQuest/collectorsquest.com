<?php

/**
 * @method    PrivateMessage setAttachedCollectibleId(int $v)
 * @method    int            getAttachedCollectibleId()
 *
 * @method    PrivateMessage setAttachedCollectionId(int $v)
 * @method    int            getAttachedCollectionId()
 */
class PrivateMessage extends BasePrivateMessage
{

  /** @var Collectible */
  protected $attached_collectible;

  /** @var Collection */
  protected $attached_collection;

  /**
   * Initialize extra properties so that auto setters/getters are added
   * to the object
   */
  public function initializeProperties()
  {
    $this->registerProperty(PrivateMessagePeer::PROPERTY_ATTACHED_COLLECTION_ID);
    $this->registerProperty(PrivateMessagePeer::PROPERTY_ATTACHED_COLLECTIBLE_ID);
  }

  /**
   * Set the attached collectible for this message
   *
   * @param     Collectible $collectible
   * @return    PrivateMessage
   */
  public function setAttachedCollectible(Collectible $collectilbe)
  {
    $this->setAttachedCollectibleId($collectible->getId());
    $this->attached_collectible = $collectible;

    return $this;
  }

  /**
   * Set the attached colletion for this message
   *
   * @param     Collection $collection
   * @return    PrivateMessage
   */
  public function setAttachedCollection(Collection $collection)
  {
    $this->setAttachedCollectionId($collection->getId());
    $this->attached_collection = $collection;

    return $this;
  }

  /**
   * Get the attached collectible for this message (if present)
   *
   * @return    Collectible|null
   */
  public function getAttachedCollectible()
  {
    if (null === $this->attached_collectible)
    {
      $pk = $this->getAttachedCollectibleId();

      $this->attached_collectible = $pk
        ? call_user_func(array('CollectiblePeer', 'retrieveByPK'), $pk)
        : null;
    }

    return $this->attached_collectible;
  }

  /**
   * Get the attached collection for this message (if present)
   *
   * @return    Collection|null
   */
  public function getAttachedCollection()
  {
    if (null === $this->attached_collection)
    {
      $pk = $this->getAttachedCollectionId();

      $this->attached_collection = $pk
        ? call_user_func(array('CollectionPeer', 'retrieveByPK'), $pk)
        : null;
    }

    return $this->attached_collection;
  }

  /**
   * @param     int|Collector $v
   * @return    PrivateMessage
   */
  public function setSender($v)
  {
    if ($v instanceof Collector)
    {
      $v = $v->getId();
    }

    return parent::setSender($v);
  }

  /**
   * @param     int|Collector $v
   * @return    PrivateMessage
   */
  public function setReceiver($v)
  {
    if ($v instanceof Collector)
    {
      $v = $v->getId();
    }

    return parent::setReceiver($v);
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
    $v = (true === $clean) ?
      IceStatic::cleanText($v, false, 'b, u, i, strong, br', $this->getIsRich() ? -1 : 0) :
      $v;

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
