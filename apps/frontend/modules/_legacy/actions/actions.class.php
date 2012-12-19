<?php

/**
 * Legacy actions.
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
  * @deprecated
  */
  public function executeHelp()
  {
    $this->redirect($this->generateUrl(
      'blog_page', array('slug' => 'cq-faqs/general-questions', '_decode' => 1)
    ), 301);
  }

  /**
   * Action ComingSoon
   */
  public function executeComingSoon()
  {
    $this->redirect('@homepage', 301);
  }

  /**
   * Action RedirectToMarketplaceCategory
   *
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeRedirectToMarketplaceCategory(sfWebRequest $request)
  {
    $content_category = ContentCategoryQuery::create()
      ->findOneByCollectionCategoryId($request->getParameter('id', 0));

    if ($content_category)
    {
      $this->redirect(
        $this->getController()->genUrl(
          array(
            'sf_route'  => 'marketplace_category_by_slug',
            'sf_subject'=> $content_category
          )
        ),
        301
      );
    }

    $this->redirect('@marketplace', 301);
  }

}
