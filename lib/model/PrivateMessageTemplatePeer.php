<?php

require 'lib/model/om/BasePrivateMessageTemplatePeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'private_message_template' table.
 *
 * @package    propel.generator.lib.model
 */
class PrivateMessageTemplatePeer extends BasePrivateMessageTemplatePeer
{
  const COLLECTOR_SIGNUP_WELCOME = 1;
  const SELLER_SIGNUP_WELCOME = 2;

  public static function retrieveByConst($const)
  {
    return self::retrieveByPk(constant('self::'. $const));
  }
}
