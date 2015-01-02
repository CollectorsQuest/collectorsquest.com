<?php

class FrontendCommentFormValidatorSchema extends sfValidatorSchema
{
  /** @var cqBaseUser */
  protected $sf_user;

  public function __construct(
    cqBaseUser $sf_user,
    $options = array(),
    $messages = array()
  ) {
    $this->sf_user = $sf_user;

    parent::__construct(null, $options, $messages);
  }

  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addMessage('invalid_token', 'Invalid token');
    $this->addMessage('spam', 'Comment was recognized as spam');
  }

  protected function doClean($values)
  {
    $errorSchema = new sfValidatorErrorSchema($this);

    if (!CommentPeer::retrieveFromCommentableToken($values['token'], $this->sf_user))
    {
      $errorSchema->addError(new sfValidatorError($this, 'invalid_token'), 'token');
      // we throw this error immediately
      throw $errorSchema;
    }

    $is_authenticated = $this->sf_user->isAuthenticated();
    $is_facebook_authenticated = $this->sf_user->isFacebookAuthenticated();

    // Auto set commenter's details if user is authenticated
    if ($is_authenticated)
    {
      $values['collector_id'] = $this->sf_user->getId();
    }
    else if ($is_facebook_authenticated)
    {
      $values['facebook_id']  = $this->sf_user->getFacebookId();
      $values['author_name']  = $this->sf_user->getName();
      $values['author_email'] = $this->sf_user->getEmail();
      $values['author_url']   = $this->sf_user->getWebsiteUrl();
    }

    // check spam for unauthenticated users
    if ( !($is_authenticated || $is_facebook_authenticated) )
    {
      $akismet = cqStatic::getAkismetClient();
      $akismet->setCommentContent($values['body']);
      $akismet->setPermalink($values['referer']);
      $akismet->setUserIP(self::getIp());

      $akismet->setCommentAuthor($values['author_name']);
      $akismet->setCommentAuthorEmail($values['author_email']);
      $akismet->setCommentAuthorURL(isset($values['author_url'])
        ? $values['author_url'] : '');

      try
      {
        if ($akismet->isCommentSpam())
        {
          $errorSchema->addError(new sfValidatorError($this, 'spam'));
        }
      }
      catch (Exception $e)
      {
        // dd($e);
      }
    }

    if ($errorSchema->count())
    {
      throw $errorSchema;
    }

    return $values;
  }

  protected static function getIp()
  {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
      // the client uses a proxy
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      return $_SERVER['REMOTE_ADDR'];
    }
  }
}
