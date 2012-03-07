<?php

/**
 * @method  boolean  isOwnerOf($something)
 */
class cqUser extends cqBaseUser
{

  public function getLogoutUrl($next = null)
  {
    return '@logout?r='. $next;
  }

}
