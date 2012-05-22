<?php

require 'lib/model/om/BaseCommentQuery.php';

class CommentQuery extends BaseCommentQuery
{

  /**
   * Filter on an arbitrary propel object.
   * Native code used for Collection/Collectible/Collector
   *
   * @param     BaseObject $object
   * @return    CommentQuery
   */
  public function filterByModelObject(BaseObject $object)
  {
    $model_class = get_class($object);

    if (in_array($model_class, array('Collection', 'Collectible', 'Collector')))
    {
      return call_user_func(array($this, 'filterBy'.$model_class), $object);
    }

    return $this
      ->filterByModel($model)
      ->filterByModelId($object->getPrimaryKey());
  }

}
