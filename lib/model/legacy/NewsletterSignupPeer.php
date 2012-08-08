<?php

require 'lib/model/legacy/om/BaseNewsletterSignupPeer.php';

class NewsletterSignupPeer extends BaseNewsletterSignupPeer
{
  public static function retrieveByEmail($email)
  {
    return NewsletterSignupQuery::create()->findOneByEmail($email);
  }
}
