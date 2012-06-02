<?php

/**
 * Filename: cqSecurityFilter.php
 *
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 4/9/12
 * Id: $Id$
 */

class cqSecurityFilter extends sfBasicSecurityFilter
{

  protected function forwardToLoginAction()
  {
    $this->context->getResponse()->setStatusCode(401);

    parent::forwardToLoginAction();
  }

}
