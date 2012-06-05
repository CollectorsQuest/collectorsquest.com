<?php

/**
 * legacy actions.
 *
 * @package    CollectorsQuest
 * @subpackage legacy
 * @author     Collectors Quest, Inc.
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class legacyActions extends sfActions
{
 /**
  * Executes help action
  *
  * @param sfWebRequest $request A request object
  *
  * @deprecated
  */
  public function executeHelp(sfWebRequest $request)
  {
    $this->redirect(urldecode($this->generateUrl('blog_page', array('slug' => 'cq-faqs/general-questions'))), 301);
  }
}
