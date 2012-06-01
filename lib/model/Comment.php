<?php

require 'lib/model/om/BaseComment.php';

class Comment extends BaseComment
{

  /** @var BaseObject */
  protected $model_object;


  /**
   * @param     PropelPDO $con
   * @return    BaseObject
   */
  public function getModelObject(PropelPDO $con = null)
  {
    if (null === $this->model_object && $this->getModel() && $this->getModelId())
    {
      $this->model_object = CommentPeer::retrieveCommentableObject(
        $this->getModel(),
        $this->getModelId()
      );
    }
    else if ($this->getCollectionId())
    {
      return $this->getCollectionId();
    }
    else if ($this->getCollectibleId())
    {
      return $this->getCollectible($con);
    }

    return $this->model_object;
  }

  /**
   * @param     BaseObject|null $object
   * @return    Comment
   */
  public function setModelObject(BaseObject $object = null)
  {
    if (null === $object)
    {
      $this->model_object = null;

      return $this->setModel(null)->setModelId(null);
    }

    $model_class = get_class($object);
    if (in_array($model_class, array('Collection', 'Collectible')))
    {
      $this->setModelObject(null);
      return call_user_func(array($this, 'set'.$model_class), $object);
    }

    $this->setModel($model_class);
    $this->setModelId($object->getPrimaryKey());
    $this->model_object = $object;

    return $this;
  }

  /**
   * Not to be confused with getModel()
   *
   * Will return the model object's class, even if Collection or Collectible
   *
   * @return    string
   */
  public function getModelObjectClass()
  {
    if ($this->getModel() && $this->getModelId())
    {
      return $this->getModel();
    }
    else if ($this->getCollectionId())
    {
      return 'Collection';
    }
    else if ($this->getCollectibleId())
    {
      return 'Collectible';
    }
  }

  /**
   * Not to be confused with getModelId()
   *
   * Will return the model object's PK even if Collection or Collectible
   *
   * @return    integer
   */
  public function getModelObjectPk()
  {
    if ($this->getModel() && $this->getModelId())
    {
      return $this->getModelId();
    }
    else if ($this->getCollectionId())
    {
      return $this->getCollectionId();
    }
    else if ($this->getCollectibleId())
    {
      return $this->getCollectibleId();
    }
  }

  /**
   * Will return the email address regardless if the comment was made by a collector
   * or an unregistered user
   *
   * @return    string
   */
  public function getAuthorEmail()
  {
    if ($this->getCollectorId())
    {
      return $this->getCollector()->getEmail();
    }
    else
    {
      return parent::getAuthorEmail();
    }
  }

  /**
   * Will return the author name regardless if the comment was made by a collector
   * or an unregistered user
   *
   * @return    string
   */
  public function getAuthorName()
  {
    if ($this->getCollectorId())
    {
      return $this->getCollector()->getDisplayName()
        ?: $this->getCollector()->getUsername();
    }
    else
    {
      return parent::getAuthorName();
    }
  }

}
