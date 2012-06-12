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
    else if ($this->getCollectibleId())
    {
      return $this->getCollectible($con);
    }
    else if ($this->getCollectionId())
    {
      return $this->getCollection($con);
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

      return $this
        ->setModel(null)
        ->setModelId(null);
    }

    // These classes are not saved as model and model_id
    $special = array(
      'Collection', 'CollectorCollection',
      'Collectible', 'CollectionCollectible'
    );

    $model_class = get_class($object);
    if (in_array($model_class, $special))
    {
      $this->setModelObject(null);
      return call_user_func(array($this, 'set' . $model_class), $object);
    }

    // @todo: We need to make sure multiple primary keys are supported
    $primary_key = implode('-', (array) $object->getPrimaryKey());

    $this->setModel($model_class);
    $this->setModelId($primary_key);
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

  /**
   * Check if the comment has passed its cutoff date (default 6 months)
   *
   * Used to determine if the comment's creation time will be displayed
   *
   * @param     mixed $cutoff_date strtotime/DateTime compatible format
   * @return    boolean
   */
  public function isPastCutoffDate($cutoff_date = '-6 months')
  {
    $cutoff_date = new DateTime($cutoff_date);

    return $cutoff_date > $this->getCreatedAt(null);
  }

  public function setCollectorCollection($v)
  {
    return $this->setCollection($v);
  }

  public function setCollectionCollectible(CollectionCollectible $v = null)
  {
    $this->setCollection($v !== null ? $v->getCollection() : null);
    $this->setCollectible($v !== null ? $v->getCollectible() : null);

    return $this;
  }
}
