<?php

require 'lib/model/om/BaseCommentQuery.php';

class CommentQuery extends BaseCommentQuery
{

  /**
   * Filter on an arbitrary propel object.
   * Native code used for Collection/Collectible/Collector
   *
   * @param  BaseObject   $object
   * @param  null|string  $comparison
   *
   * @return CommentQuery
   */
  public function filterByModelObject(BaseObject $object, $comparison = null)
  {
    $special = array(
      'Collection', 'CollectorCollection',
      'Collectible', 'CollectionCollectible'
    );
    $model_class = get_class($object);

    if (in_array($model_class, $special))
    {
      return call_user_func_array(
        array($this, 'filterBy' . $model_class),
        array($object, $comparison)
      );
    }

    return $this
      ->filterByModel($model_class)
      ->filterByModelId($object->getPrimaryKey());
  }

  /**
   * @param  CollectorCollection|null  $v
   * @param  null|string  $comparison
   *
   * @return CommentQuery
   */
  public function filterByCollectorCollection(CollectorCollection $v = null, $comparison = null)
  {
    return $this
      ->filterByCollectionId($v !== null ? $v->getId() : null, $comparison)
      ->filterByCollectibleId(null, Criteria::ISNULL);
  }

  /**
   * @param  CollectionCollectible|null  $v
   * @param  null|string  $comparison
   *
   * @return CommentQuery
   */
  public function filterByCollectionCollectible(CollectionCollectible $v = null, $comparison = null)
  {
    return $this
      ->filterByCollectionId($v !== null ? $v->getCollectionId() : null, $comparison)
      ->filterByCollectibleId($v !== null ? $v->getCollectibleId() : null, $comparison);
  }

}
