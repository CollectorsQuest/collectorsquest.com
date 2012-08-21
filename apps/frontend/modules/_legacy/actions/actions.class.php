<?php

/**
 * legacy actions.
 *
 * @package    CollectorsQuest
 * @subpackage legacy
 * @author     Collectors Quest, Inc.
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class _legacyActions extends sfActions
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

  /**
   * Action Signup
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeSignup(sfWebRequest $request)
  {
    $this->redirect('collector_signup', 301);
  }

  /**
   * Action ComingSoon
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeComingSoon(sfWebRequest $request)
  {
    $this->redirect('@homepage', 301);
  }

  /**
   * Action RedirectToCollections
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeRedirectToCollections(sfWebRequest $request)
  {
     $this->redirect('@collections', 301);
  }

  /**
   * Action RedirectToContentCategories
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeRedirectToContentCategories(sfWebRequest $request)
  {
     $this->redirect('@content_categories', 301);
  }
}
