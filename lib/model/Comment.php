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
    else if ($this->getCollectorId())
    {
      return $this->getCollector();
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
    if (in_array($model_class, array('Collection', 'Collectible', 'Collector')))
    {
      $this->setModelObject(null);
      return call_user_func(array($this, 'set'.$model_class), $object);
    }

    $this->setModel($model_class);
    $this->setModelId($object->getPrimaryKey());
    $this->model_object = $object;

    return $this;
  }

}
