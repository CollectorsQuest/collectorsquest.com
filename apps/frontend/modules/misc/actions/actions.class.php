<?php

/**
 * misc actions.
 *
 * @package    CollectorsQuest
 * @subpackage misc
 * @author     Collectors Quest, Inc.
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class miscActions extends sfActions
{

  /**
   * Executes index action
   */
  public function executeIndex()
  {
    $this->redirect('@homepage');
  }

  /**
   * @return string
   */
  public function executeGuideToCollecting()
  {
    if ($this->getUser()->isAuthenticated())
    {
      $this->redirect('@misc_guide_download');
    }

    $signupForm = new CollectorGuideSignupForm();
    $loginForm = new CollectorGuideLoginForm();
    $display = 'signup';

    $request = $this->getRequest();
//    dd($request->getParameterHolder()->getAll(), $request->getMethod());
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

          // Run the post create hook
          $this->getUser()->postCreateHook($collector);

          // authenticate the collector and redirect to @mycq_profile
          $this->getUser()->Authenticate(true, $collector, false);

          $this->redirect('@misc_guide_download');
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

    return sfView::SUCCESS;
  }

  /**
   * Action GuideDownload
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeGuideDownload(sfWebRequest $request)
  {
    $this->redirectUnless($this->getUser()->isAuthenticated(), '@misc_guide_to_collecting');

    /* @var $collector Collector */
    $collector = $this->getUser()->getCollector();

    /* @var $profile CollectorProfile */
    $profile = $collector->getProfile();

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
            'subject' => 'Download essential guide',
            'params'  => array(
              'collector'       => $collector,
              'collector_email' => $email,
            )
          ));

          $this->getUser()->setFlash('success', sprintf('Validation email sent to %s', $email->getEmail()));

          $this->redirect('@misc_guide_download');
          return sfView::SUCCESS;
        }
      }

      $this->form = $form;

      return sfView::ALERT;
    }

//    $this->redirectUnless($email && $email->getIsVerified(), '@misc_guide_to_collecting');

    $hash = $this->getUser()->getAttribute('hash', false, 'guide');
    $expireAt = $this->getUser()->getAttribute('expire', 0, 'guide');

    if ($this->getRequestParameter('sf_format', false) && $hash && $expireAt >= time())
    {
      //Download
      dd($hash, $expireAt, $this->getRequestParameter('sf_format'), $expireAt - time());

      $format = $this->getRequestParameter('sf_format');
      $filename = sprintf('essential-guide.%s', $format);
      $mimeType = 'pdf' == $format ? 'application/pdf' : 'application/zip';
      $sourceFile = sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'guide.' . $format;

      header("Expires: 0");
      header("Cache-control: private");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-Description: File Transfer");
      header(sprintf('Content-Type: %s, charset=UTF-8; encoding=UTF-8', $mimeType));
      header("Content-disposition: attachment; filename=" . $filename);

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

//      dd($this->getUser()->getAttribute('hash', null, 'guide'), $this->getUser()->getAttribute('expire', null, 'guide'), time(), $expireAt-time());
//    dd($email);

    $this->hash = $hash;

    return sfView::SUCCESS;
  }

  /**
   * Action ValidationEmailSent
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeValidationEmailSent(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /**
   * Action GuideVerifyEmail
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeGuideVerifyEmail(sfWebRequest $request)
  {
    /* @var $collectorEmail CollectorEmail */
    $collectorEmail = $this->getRoute()->getObject();
    $this->forward404Unless($collectorEmail instanceof CollectorEmail);

    $collector = $collectorEmail->getCollector();
    $collector->setEmail($collectorEmail->getEmail());
    $collector->save();

    $collectorEmail->setIsVerified(true);
    $collectorEmail->save();

    $this->getUser()->Authenticate(true, $collector, false);

    $this->redirect('@misc_guide_download');
  }

}
