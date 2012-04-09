<?php

/**
 * newsletter actions.
 *
 * @package    CollectorsQuest
 * @subpackage newsletter
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 2171 2011-06-17 22:23:46Z yanko $
 */
class newsletterActions extends sfActions
{
  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404();
  }

  public function executeSignup(sfWebRequest $request)
  {
    $newsletterSignup = new NewsletterSignup();

    if ($this->getUser()->isAuthenticated()) {

      if (NewsletterSignupPeer::retrieveByEmail($this->getUser()->getCollector()->getEmail())) {
        $this->getUser()->setFlash('error', 'You have been signed up already.');
        return sfView::ERROR;
      }

      $newsletterSignup->setEmail($this->getUser()->getCollector()->getEmail());
      $newsletterSignup->setName($this->getUser()->getCollector()->getDisplayName());
    }

    $form = new FrontendNewsletterSignupForm($newsletterSignup);

    if ($request->isMethod('post'))
    {
      if ($form->bindAndSave($request->getParameter($form->getName())))
      {
        $this->getUser()->setFlash('success', 'You have successfuly subscribed.');

        if ($this->getUser()->isAuthenticated())
        {
          $this->redirect('@collector_by_slug', $this->getUser()->getCollector());
        }
        $this->redirect('@homepage');
      }
      else
      {
        $this->getUser()->setFlash('error', 'Invalid data');
      }
    }

    $this->form = $form;
  }

}
