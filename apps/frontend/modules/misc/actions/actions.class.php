<?php

/**
 * misc actions.
 *
 * @package    CollectorsQuest
 * @subpackage misc
 * @author     Collectors Quest, Inc.
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class miscActions extends cqFrontendActions
{

  /**
   * Executes index action
   */
  public function executeIndex()
  {
    $this->redirect('@homepage');
  }

  public function executeGuideToCollectingShortcut()
  {
    $this->redirect('@misc_guide_to_collecting', 301);
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeGuideToCollecting(sfWebRequest $request)
  {
    if ($this->getUser()->isAuthenticated())
    {
      $this->redirect('@misc_guide_download');
    }

    $signupForm = new CollectorGuideSignupForm();
    $loginForm = new CollectorGuideLoginForm();
    $display = 'signup';

    if (sfRequest::POST == $request->getMethod())
    {
      if ($request->getParameter($signupForm->getName()))
      {
        $signupForm->bind($request->getParameter($signupForm->getName()));

        if ($signupForm->isValid())
        {
          $values = $signupForm->getValues();

          // try to guess the collector's country based on IP address
          $values['country_iso3166'] = cqStatic::getGeoIpCountryCode(
            $request->getRemoteAddress(), $check_against_geo_country = true
          );

          // Run the pre create hook
          $this->getUser()->preCreateHook();

          // create the collector
          $collector = CollectorPeer::createFromArray($values);

          $collector_email = CollectorEmailPeer::retrieveByCollectorEmail(
            $collector, $collector->getEmail()
          );

          // Run the post create hook
          $cqEmail = new cqEmail($this->getMailer());
          $cqEmail->send('misc/validate_email_to_download', array(
            'to' => $collector->getEmail(),
            'subject' => 'Quest Your Best: The Essential Guide to Collecting',
            'params' => array(
              'collector' => $collector,
              'collector_email' => $collector_email,
            ),
          ));

          // authenticate the collector and redirect to @misc_guide_download
          $this->getUser()->Authenticate(true, $collector, false);

          // Run the post create hook (not sending the welcome email yet)
          $this->getUser()->postCreateHook($collector, false);

          $this->getUser()->setFlash(
            'success',
            'Thanks for joining the fine folks at Collectors Quest!
             Please explore some of the sections below.'
          );

          $this->redirect('@mycq');
        }
      }
      else if ($request->getParameter($loginForm->getName()))
      {
        $display = 'login';
        $loginForm->bind($request->getParameter($loginForm->getName()));

        if ($loginForm->isValid())
        {
          /* @var $collector Collector */
          $collector = $loginForm->getValue('collector');
          $this->getUser()->Authenticate(true, $collector, $loginForm->getValue('remember'));

          $this->redirect('@misc_guide_download');
        }
      }
    }

    $this->signup_form = $signupForm;
    $this->login_form = $loginForm;
    $request->setParameter('display', $display);

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('misc_guide_to_collecting'));

    return sfView::SUCCESS;
  }

  /**
   * Action GuideDownload
   *
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeGuideDownload(sfWebRequest $request)
  {
    $this->redirectUnless($this->getUser()->isAuthenticated(), '@misc_guide_to_collecting');

    /* @var $collector Collector */
    $collector = $this->getUser()->getCollector();

    $email = CollectorEmailQuery::create()
        ->filterByCollector($collector)
        ->findOneByEmail($collector->getEmail());

    if (!$email || !$email->getIsVerified())
    {
      $form = new CollectorValidateEmailForm($email);

      if (sfRequest::POST == $this->getRequest()->getMethod())
      {
        //Form submited
        $form->bind($this->getRequestParameter($form->getName()));

        if ($form->isValid())
        {
          $cqEmail = new cqEmail($this->getMailer());
          $cqEmail->send('misc/validate_email_to_download', array(
            'to'      => $collector->getEmail(),
            'subject' => 'Quest Your Best: The Essential Guide to Collecting',
            'params'  => array(
              'collector'       => $collector,
              'collector_email' => $email,
            )
          ));

          $this->getUser()->setFlash(
            'success', sprintf('Validation email sent to %s', $email->getEmail())
          );

          $this->redirect('@misc_guide_download');
          return sfView::SUCCESS;
        }
      }

      $this->form = $form;

      return sfView::ALERT;
    }

    $hash = $this->getUser()->getAttribute('hash', false, 'guide');
    $expireAt = $this->getUser()->getAttribute('expire', 0, 'guide');

    if ($this->getRequestParameter('sf_format', false) && $hash && $expireAt >= time())
    {
      $format = $this->getRequestParameter('sf_format');
      $filename = sprintf('Essential Guide to Collecting - CollectorsQuest.%s', $format);
      $mimeType = 'pdf' == $format ? 'application/pdf' : 'application/zip';
      $sourceFile = sfConfig::get('sf_data_dir') . '/download/essential-guide.' . $format;

      header('Expires: 0');
      header('Cache-control: private');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Content-Description: File Transfer');
      header(sprintf('Content-Type: %s, charset=UTF-8; encoding=UTF-8', $mimeType));
      header('Content-disposition: attachment; filename="' . $filename .'"');

      readfile($sourceFile);
      exit(0);
    }

    if (!$hash || $expireAt < time())
    {
      //Generate hash for next 5 minutes
      $hash = ShoppingOrderPeer::getUuidFromId(date('mdHis'));
      $expireAt = strtotime('+5 minute');
      $this->getUser()->setAttribute('hash', $hash, 'guide');
      $this->getUser()->setAttribute('expire', $expireAt, 'guide');
    }

    $this->hash = $hash;

    $this->getResponse()->addOpenGraphMeta('type', 'website');
    $this->getResponse()->addOpenGraphMeta('url', 'http://www.collectorsquest.com/guide');
    $this->getResponse()->addOpenGraphMeta('title',
      'Quest Your Best: The Essential Guide to Collecting | Collectors Quest'
    );
    $this->getResponse()->addOpenGraphMeta('image',
      'http://www.collectorsquest.com/images/frontend/misc/guide-splash-page-img.png'
    );
    $this->getResponse()->addOpenGraphMeta('description',
      "The guide has something for every collector, whether you're just
       beginning to acquire treasures or you're a dedicated hunter looking for the next,
       perfect addition to your display case."
    );
    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('misc_guide_download'));

    $this->redirectIf(
      $request->getRequestFormat() && $request->getRequestFormat() !== 'html',
      '@misc_guide_to_collecting'
    );

    return sfView::SUCCESS;
  }

  /**
   * Action GuideVerifyEmail
   *
   * @return string
   */
  public function executeGuideVerifyEmail()
  {
    /* @var $collectorEmail CollectorEmail */
    $collectorEmail = $this->getRoute()->getObject();
    $this->forward404Unless($collectorEmail instanceof CollectorEmail);

    $collector = $collectorEmail->getCollector();
    $collector->setEmail($collectorEmail->getEmail());
    $collector->save();

    $collectorEmail->setIsVerified(true);
    $collectorEmail->save();

    // Finally, send the welcome email
    $cqEmail = new cqEmail($this->getMailer());
    $cqEmail->send($collector->getUserType() . '/welcome_to_cq', array(
      'to' => $collector->getEmail(),
    ));

    $this->getUser()->Authenticate(true, $collector, false);

    $this->redirect('@misc_guide_download');
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeWordPressFeaturedItems(sfWebRequest $request)
  {
    /** @var $wp_post wpPost */
    $wp_post = $this->getRoute()->getObject();

    $values = $wp_post->getPostMetaValue('_featured_items');

    // do we exclude the sidebar and have full page width image?
    $this->no_sidebar = !empty($values['cq_no_sidebar']) ? $values['cq_no_sidebar'] : false;

    // set template with no sidebar and full page width image
    if ($this->no_sidebar == 'true')
    {
      $this->setTemplate('wordPressFeaturedItemsNoSidebar');
    }

    // what is the layout for the page - grid or pinterest
    $this->cq_layout = !empty($values['cq_layout']) ? $values['cq_layout'] : 'grid';
    if ($this->cq_layout != 'grid' && $this->cq_layout != 'pinterest')
    {
      $this->cq_layout = 'grid';
    }

    $this->wp_post = $wp_post;

    $this->addBreadcrumb($wp_post->getPostTitle(), null);

    $title = $wp_post->getPostMetaValue('_yoast_wpseo_title') ?:
             $wp_post->getPostTitle();
    $this->getResponse()->setTitle($title);
    $this->getResponse()->addOpenGraphMetaFor($wp_post);

    return sfView::SUCCESS;
  }

}
