<?php
require 'lib/model/om/BaseNewsletterSignupPeer.php';

/**
 * Skeleton subclass for performing query and update operations on the 'newsletter_signup' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class NewsletterSignupPeer extends BaseNewsletterSignupPeer
{
  public static function retrieveByEmail($email)
  {
    return NewsletterSignupQuery::create()->findOneByEmail($email);
  }

}
