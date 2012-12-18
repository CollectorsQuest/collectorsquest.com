<?php

/**
 * @method    string              getAttachedObjectClass()
 *
 * @method    boolean             hasAttachedCollection(PropelPDO $con = null, $forceQuery = false)
 * @method    Collection|null     getAttachedCollection(PropelPDO $con = null, $forceQuery = false)
 *
 * @method    boolean             hasAttachedCollectible(PropelPDO $con = null, $forceQuery = false)
 * @method    Collectible|null    getAttachedCollectible(PropelPDO $con = null, $forceQuery = false)
 *
 * @method    boolean             hasAttachedShoppingOrder(PropelPDO $con = null, $forceQuery = false)
 * @method    ShoppingOrder|null  getAttachedShoppingOrder(PropelPDO $con = null, $forceQuery = false)
 *
 */
class PrivateMessage extends BasePrivateMessage
{
  const MAGIC_ATTACHED_METHODS_REGEX = '/(?<modifier>(?:get|has))Attached(?<class>(\w+))/';

  /** @var BaseObject */
  protected $attached_object;

  /**
   * Initialize extra properties so that auto setters/getters are added
   * to the object
   */
  public function initializeProperties()
  {
    $this->registerProperty(PrivateMessagePeer::PROPERTY_ATTACHED_OBJECT_CLASS);
    $this->registerProperty(PrivateMessagePeer::PROPERTY_ATTACHED_OBJECT_PK);
  }

  /**
   * @param     string $v
   * @return    PrivateMessage
   *
   * @throws    RuntimeException
   */
  public function setAttachedObjectClass($v)
  {
    if (!in_array($v, PrivateMessagePeer::$allowedAttachClasses))
    {
      throw new RuntimeException(sprintf(
        'Cannot attach object of type %s to PrivateMessage, allowed classes: %s',
        $v,
        implode(', ', PrivateMessagePeer::$allowedAttachClasses)
      ));
    }

    return parent::setAttachedObjectClass($v);
  }

  /**
   * @param     int|array $v
   * @return    PrivateMessage
   */
  public function setAttachedObjectPK($v)
  {
    if (is_array($v))
    {
      $v = implode('-', $v);
    }

    return parent::setAttachedObjectPK($v);
  }

  /**
   * @return    int|array
   */
  public function getAttachedObjectPK()
  {
    $v = parent::getAttachedObjectPK();
    if (false !== strpos('-', $v))
    {
      $v = explode('-', $v);
    }

    return $v;
  }

  /**
   * Directly set the attached object data
   *
   * @param     string $class
   * @param     int|array $pk
   *
   * @return    PrivateMessage
   */
  public function setAttachedObjectData($class, $pk)
  {
    $this->setAttachedObjectClass($class);
    $this->setAttachedObjectPK($pk);

    return $this;
  }

  /**
   * Set a Propel object as attached to this PrivateMessage
   *
   * @param     BaseObject $obj
   * @return    PrivateMessage
   */
  public function setAttachedObject(BaseObject $obj)
  {
    $this->attached_object = $obj;
    $this->setAttachedObjectData(get_class($obj), $obj->getPrimaryKey());

    return $this;
  }

  /**
   * Get the attached object, if there is one
   *
   * @param     PropelPDO $con
   * @param     boolean $forceQuery
   *
   * @return    BaseObject|null
   */
  public function getAttachedObject(PropelPDO $con = null, $forceQuery = false)
  {
    // if we don't have the object cached, or forceQuery option was set
    // and we have attached object class and pk set
    if (
      (null === $this->attached_object || $forceQuery) &&
      $this->getAttachedObjectClass() && $this->getAttachedObjectPK()
    )
    {
      // check that a Query object exists for the attached class
      // and if not, log error and return null
      $query = sprintf('%sQuery', $this->getAttachedObjectClass());
      if (!class_exists($query))
      {
        cqContext::getInstance()->getLogger()->log(
          sprintf('Unable to load class %s', $query)
        );

        return null;
      }

      // try to get the attached object
      $q = call_user_func(array($query, 'create'));
      $this->attached_object = call_user_func_array(
        array($q, 'findPk'),
        array($this->getAttachedObjectPK(), $con)
      );

      // and if no object was returned, log an error
      if (null === $this->attached_object)
      {
        cqContext::getInstance()->getLogger()->log(sprintf(
          'Unable to retrieve %s with primary key %s',
          $this->getAttachedObjectClass(),
          implode('-', (array) $this->getAttachedObjectPK())
        ));
      }
    }

    // and return the attached object
    return $this->attached_object;
  }

  /**
   * @param     PropelPDO $con
   * @param     boolean $forceQuery
   *
   * @return    boolean
   */
  public function hasAttachedObject(PropelPDO $con = null, $forceQuery = false)
  {
    return !! $this->getAttachedObject($con, $forceQuery);
  }

  /**
   * Handle magic (get/has)Attached(Something) methods
   */
  public function __call($name, $params)
  {
    // if the call method name matches our magic attached methods regex
    // (get/has)Attached(Something)
    if (preg_match(self::MAGIC_ATTACHED_METHODS_REGEX, $name, $matches))
    {
      // and the Something is one of the allowed attach classes
      if (in_array($matches['class'], PrivateMessagePeer::$allowedAttachClasses))
      {
        // and we are performing a getAttachedSomething
        if ('get' == $matches['modifier'])
        {
          // we have to check if we meet the request method class restriction
          if ($matches['class'] == $this->getAttachedObjectClass())
          {
            // and only then try to return the attached object
            return call_user_func_array(array($this, 'getAttachedObject'), $params);
          }
          else
          {
            // otherwize return null (even if we have an attached object)
            return null;
          }
        }

        // if we are performing a hasAttachedSomething
        if ('has' == $matches['modifier'])
        {
          // then we can first do a very quick check of Something
          // and the attached object class
          if ($matches['class'] == $this->getAttachedObjectClass())
          {
            // and execute hasAttachedObject method only when needed
            return call_user_func_array(array($this, 'hasAttachedObject'), $params);
          }
          else
          {
            // or directly return false if they don't match
            return false;
          }
        }
      }
    }

    // otherwize fallback to parent's __call()
    return parent::__call($name, $params);
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

    return $this->setSenderId($v);
  }

  /**
   * @param     int|Collector|email $v
   * @return    PrivateMessage
   */
  public function setReceiver($v)
  {
    if ($v instanceof Collector)
    {
      $v = $v->getId();
    }

    if (is_numeric($v))
    {
      return $this->setReceiverId($v);
    }
    else
    {
      return $this->setReceiverEmail($v);
    }
  }

  /**
   * @param     string $v
   * @return    PrivateMessage
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
