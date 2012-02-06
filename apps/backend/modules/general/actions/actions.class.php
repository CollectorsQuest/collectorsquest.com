<?php

/**
 * general actions.
 *
 * @package    CollectorsQuest
 * @subpackage collectors
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class generalActions extends cqBackendActions
{
  /**
   * @return string
   */
  public function executeIndex()
  {
    return sfView::NONE;
  }

}
