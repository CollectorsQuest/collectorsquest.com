<?php

/**
 * Comment form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 */
class CommentForm extends BaseCommentForm
{
  public function setup()
  {
    parent::setup();

    /** @var $sf_user cqUser */
    $sf_user = sfContext::getInstance()->getUser();

    $is_authenticated = $sf_user->isAuthenticated();
    $is_facebook_authenticated = $sf_user->isFacebookAuthenticated();

    $widgets = array(
      'body' => new sfWidgetFormTextarea(array(), array('rows' => 5, 'cols' => 40)),
      'referer'     => new sfWidgetFormInputHidden(),
      'token'       => new sfWidgetFormInputHidden()
    );

    $validators = array(
      'body' => new sfValidatorString(array('required' => true), array('required' => 'The comment is required.')),
      'referer'     => new sfValidatorString(array('required' => false)),
      'token'       => new sfValidatorString(array('required' => true)),
    );

    if (!$is_facebook_authenticated)
    {
      $widgets['name'] = new sfWidgetFormInput();
      $validators['name'] = new sfValidatorString(
        array('max_length' => 64, 'required' => false),
        array('max_length' => 'The name is too long. It must be of %max_length% characters maximum.')
      );

      $widgets['email'] = new sfWidgetFormInput();
      $validators['email'] = new sfValidatorEmail(
        array('max_length' => 64, 'required' => false),
        array('max_length' => 'The email is too long. It must be of %max_length% characters maximum.')
      );

      $widgets['website'] = new sfWidgetFormInput();
      $validators['website'] = new sfValidatorAnd(
        array(
          new sfValidatorString(
            array('max_length' => 255),
            array('max_length' => 'The website address is too long. It must be of %max_length% characters maximum.')
          ),
          new sfValidatorUrl(array(), array('invalid' => 'This url is invalid.'))
        ),
        array('required' => false)
      );
    }

    $this->setWidgets($widgets);
    $this->setValidators($validators);

    $this->widgetSchema->setNameFormat('comment[%s]');
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

  public function doSave($con = null)
  {
    $is_authenticated = sfContext::getInstance()->getUser()->isAuthenticated();
    $is_facebook_authenticated = sfContext::getInstance()->getUser()->isFacebookAuthenticated();
    $user = sfContext::getInstance()->getUser();

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
      // the client uses a proxy
      $ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip_adress = $_SERVER['REMOTE_ADDR'];
    }

    $token = $this->getValue('token');
    $object = self::retrieveFromToken($token);

    $array = array('body' => IceStatic::cleanText($this->getValue('body')), 'ip_address' => $ip_adress);

    if ($object instanceof Collectible)
    {
      $array['collection_id'] = $object->getCollectionId();
      $array['collectible_id'] = $object->getId();
    }
    else if ($object instanceof Collection)
    {
      $array['collection_id'] = $object->getId();
      $array['collectible_id'] = null;
    }

    if ($is_authenticated)
    {
      $array['collector_id'] = $user->getId();
    }
    else if ($is_facebook_authenticated)
    {
      $array['facebook_id']  = $user->getFacebookId();
      $array['author_name']  = $user->getName();
      $array['author_email'] = $user->getEmail();
      $array['author_url']   = $user->getWebsiteUrl();
    }
    else
    {
      $array['author_name']  = strip_tags($this->getValue('name'));
      $array['author_email'] = strip_tags($this->getValue('email'));
      $array['author_url']   = strip_tags($this->getValue('website'));
    }

    // Spam checking only for guest (anonymous) commenters
    if (!$is_authenticated && !$is_facebook_authenticated)
    {
      $akismet = cqStatic::getAkismetClient();
      $akismet->setCommentContent($array['body']);
      $akismet->setPermalink($this->getValue('referer'));
      $akismet->setUserIP($ip_adress);

      $akismet->setCommentAuthor($array['author_name']);
      $akismet->setCommentAuthorEmail($array['author_email']);
      $akismet->setCommentAuthorURL($array['author_url']);

      // By default the comment is not treated as spam until proven the opposite
      $is_spam = false;
      try
      {
        $is_spam = $akismet->isCommentSpam();
      }
      catch (Exception $e)
      {
        $is_spam = true;
      }

      if ($is_spam)
      {
        return false;
      }
    }

    $comment = new Comment();
    $comment->fromArray($array, BasePeer::TYPE_FIELDNAME);
    $object->addComment($comment);
    $object->save($con);

    $session = sfContext::getInstance()->getUser();
    $session->setAttribute($token, array(), 'cq/user/comments');

    return $comment;
  }

  public function getModelName()
  {
    return 'Comment';
  }

  /**
   * Add a token to available ones in the user session
   * and return generated token
   *
   * @author Nicolas Perriault
   * @param  string  $object_model
   * @param  int     $object_id
   * @return string
   */
  public static function addTokenToSession($object_model, $object_id)
  {
    $session = sfContext::getInstance()->getUser();

    $token = self::generateToken($object_model, $object_id);
    $tokens = $session->getAttribute('tokens', array(), 'cq/user/comments');
    $tokens = array($token => array($object_model, $object_id)) + $tokens;
    $tokens = array_slice($tokens, 0, 10);

    $session->setAttribute('tokens', $tokens, 'cq/user/comments');

    return $token;
  }

  /**
   * Generates token representing a commentable object from its model and its id
   *
   * @param  string  $object_model
   * @param  int     $object_id
   * @return string
   */
  private static function generateToken($object_model, $object_id)
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
      $ip_adress, $object_model, $object_id, 'c0mm3nt4bl3'
    ));
  }

  /**
   * Retrieve commentable object instance from token
   *
   * @author Nicolas Perriault
   * @param  string  $token
   * @return BaseObject
   */
  private static function retrieveFromToken($token)
  {
    $session = sfContext::getInstance()->getUser();
    $tokens = $session->getAttribute('tokens', array(), 'cq/user/comments');

    if (array_key_exists($token, $tokens) && is_array($tokens[$token]) && class_exists($tokens[$token][0]))
    {
      $object_model = $tokens[$token][0];
      $object_id = $tokens[$token][1];
      $new_token = self::generateToken($object_model, $object_id);

      // check is token has changed or not (ie., if the user's IP has changed)
      if ($token == $new_token)
      {
        return self::retrieveCommentableObject($object_model, $object_id);
      }
    }

    return null;
  }

  /**
   * Retrieve a commentable object
   *
   * @param  string  $object_model
   * @param  int     $object_id
   */
  private static function retrieveCommentableObject($object_model, $object_id)
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

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    $token = $taintedValues['token'];
    $object = self::retrieveFromToken($token);

    $session = sfContext::getInstance()->getUser();
    $session->setAttribute($token, $taintedValues, 'cq/user/comments');

    parent::bind($taintedValues, $taintedFiles);
  }

  public function setDefaults($defaults)
  {
    if (!empty($defaults['token']))
    {
      $session = sfContext::getInstance()->getUser();
      $comment = $session->getAttribute($defaults['token'], array(), 'cq/user/comments');

      $defaults = array_merge($defaults, $comment);
    }

    parent::setDefaults($defaults);
  }
}
