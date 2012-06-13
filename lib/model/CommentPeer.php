<?php

require 'lib/model/om/BaseCommentPeer.php';

class CommentPeer extends BaseCommentPeer
{

 /**
   * Add a token to available ones in the user session
   * and return generated token
   *
   * @param     BaseObject  $model_object
   * @param     sfUser $sf_user
   *
   * @return    string
   */
  public static function addCommentableTokenToSession(
    BaseObject $model_object,
    sfUser $sf_user
  ) {
    $object_class = get_class($model_object);
    $object_id = $model_object->getPrimaryKey();

    $token = self::generateCommentableToken($object_class, $object_id);
    $tokens = $sf_user->getAttribute('tokens', array(), 'cq/user/comments');
    $tokens = array(
        $token => array($object_class, $object_id),
      ) + $tokens;
    // limit to 10 tokens
    $tokens = array_slice($tokens, 0, 10);
    $sf_user->setAttribute('tokens', $tokens, 'cq/user/comments');

    return $token;
  }

  /**
   * Generates token representing a commentable object from its model and its id
   *
   * @param     string $object_class
   * @param     integer $object_id
   *
   * @return    string
   */
  public static function generateCommentableToken($object_class, $object_id)
  {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
      // the client uses a proxy
      $ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip_adress = $_SERVER['REMOTE_ADDR'];
    }

    return md5(sprintf(
      '%s-%s-%s-%s',
      $ip_adress, $object_class, $object_id, 'c0mm3nt4bl3'
    ));
  }

  /**
   * Retrieve commentable object instance from token
   *
   * @param     string $token
   * @param     sfUser $sf_user
   *
   * @return    BaseObject|null
   */
  public static function retrieveFromCommentableToken($token, sfUser $sf_user)
  {
    $tokens = $sf_user->getAttribute('tokens', array(), 'cq/user/comments');

    if (
      array_key_exists($token, $tokens) &&
      is_array($tokens[$token]) &&
      class_exists($tokens[$token][0])
    ) {
      $object_class = $tokens[$token][0];
      $object_id = $tokens[$token][1];
      $new_token = self::generateCommentableToken($object_class, $object_id);

      // check is token has changed or not (ie., if the user's IP has changed)
      if ($token == $new_token)
      {
        return self::retrieveCommentableObject($object_class, $object_id);
      }
    }

    return null;
  }

  /**
   * Retrieve a commentable object
   *
   * @param     string   $object_class
   * @param     integer  $object_id
   *
   * @return    BaseObject
   */
  public static function retrieveCommentableObject($object_class, $object_id)
  {
    try
    {
      $query = sprintf('%sQuery', $object_class);

      if (!class_exists($query))
      {
        throw new Exception(sprintf('Unable to load class %s', $query));
      }

      $q = call_user_func(array($query, 'create'));
      $object = call_user_func(array($q, 'findOneByPrimaryKey'), $object_id);

      if (null === $object)
      {
        throw new Exception(
          sprintf('Unable to retrieve %s with primary key %s', $object_class, $object_id)
        );
      }

      return $object;
    }
    catch (Exception $e)
    {
      return sfContext::getInstance()->getLogger()->log($e->getMessage());
    }
  }

}
