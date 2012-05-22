<?php

require 'lib/model/om/BaseCommentPeer.php';

class CommentPeer extends BaseCommentPeer
{


  /**
   * Retrieve a commentable object
   *
   * @param     string   $object_model
   * @param     integer  $object_id
   *
   * @return    BaseObject
   */
  public static function retrieveCommentableObject($object_model, $object_id)
  {
    try
    {
      $peer = sprintf('%sPeer', $object_model);

      if (!class_exists($peer))
      {
        throw new Exception(sprintf('Unable to load class %s', $peer));
      }

      $object = call_user_func(array($peer, 'retrieveByPk'), $object_id);

      if (is_null($object))
      {
        throw new Exception(sprintf('Unable to retrieve %s with primary key %s', $object_model, $object_id));
      }

      return $object;
    }
    catch (Exception $e)
    {
      return sfContext::getInstance()->getLogger()->log($e->getMessage());
    }
  }

}
