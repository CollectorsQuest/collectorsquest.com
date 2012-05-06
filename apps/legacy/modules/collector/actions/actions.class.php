<?php

/**
 * collector actions.
 *
 * @package    CollectorsQuest
 * @subpackage collector
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class collectorActions extends cqActions
{

  private $_facebook_fields = array(
    array('name' => 'name'),
    array('name' => 'email'),
    array('name' => 'gender'),
    array('name' => 'birthday'),
    array('name' => 'location'),
    array('name' => 'password'),
    array('name' => 'captcha'),
  );

  /**
   * Executes index action
   *
   * @param  sfWebRequest  $request  A request object
   * @return string
   */
  public function executeIndex(sfWebRequest $request)
  {
    /** @var $collector Collector */
    $collector = $this->getRoute()->getObject();

    /** @var $profile CollectorProfile */
    $profile = $collector->getCollectorProfile();

    /**
     * Increment the number of views
     */
    if (!$this->getCollector()->isOwnerOf($collector))
    {
      $profile->setNumViews($profile->getNumViews() + 1);
      $profile->save();
    }

    /**
     * Special checks for the Collectibles of A&E
     */
    $pawn_stars = sfConfig::get('app_aent_pawn_stars');
    $american_pickers = sfConfig::get('app_aent_american_pickers');

    if (in_array($collector->getId(), array($pawn_stars['collector'], $american_pickers['collector'])))
    {
      $this->plural = true;
    }
    else
    {
      $this->plural = false;
    }

    if ($collector->getId() == $this->getUser()->getCollector()->getId())
    {
      $request->setAttribute('header_icons_active', 'profile');
    }

    $this->addBreadcrumb($this->__('Community'), '@community');
    $this->addBreadcrumb($this->__('Collectors'), '@collectors');
    $this->addBreadcrumb($collector->getDisplayName());

    $this->collector = $collector;
    $this->collector_profile = $profile;

    $this->related_collections = $collector->getRelatedCollections(10, $this->rnd_flag);
    $this->count_collections = $collector->countCollectorCollections();

    // Get the dropbox of the collector
    $dropbox = $collector->getCollectionDropbox();

    if ($dropbox && $dropbox->getCountCollectibles() > 0)
    {
      $this->collections = array_merge(array($dropbox), $collector->getRecentCollections(2));
    }
    else
    {
      $this->collections = $collector->getRecentCollections(3);
    }

    // Building the title
    $this->prependTitle($collector->getDisplayName());

    // Building the meta tags
    $this->getResponse()->addMeta('description', $this->collector->getAboutMe());
    $this->getResponse()->addMeta('keywords', $this->collector->getAboutInterests());

    // Building the geo.* meta tags
    $this->getResponse()->addGeoMeta($collector);

    // Setting the Canonical URL
    $this->loadHelpers(array('cqLinks'));
    $this->getResponse()->setCanonicalUrl(url_for_collector($collector, true));

    return sfView::SUCCESS;
  }

  public function executeDropbox()
  {
    if ($collector = $this->getCollector())
    {
      $id = $collector->getId();
      $slug = $collector->getSlug();

      $this->redirect('@dropbox_by_slug?collector_id=' . $id . '&collector_slug=' . $slug);
    }

    $this->redirect('@login');
  }

  public function executeMe()
  {
    if ($collector = $this->getCollector())
    {
      $id = $collector->getId();
      $slug = $collector->getSlug();

      $this->redirect('@collector_by_slug?id=' . $id . '&slug=' . $slug);
    }

    $this->redirect('@login');
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeSignup(sfWebRequest $request)
  {
    // Redirect to the community if already signed up
    $this->redirectIf($this->getUser()->isAuthenticated()
          && $this->getUser()->getCollector()->getHasCompletedRegistration(),
      '@manage_profile');

    if (!$this->getUser()->isAuthenticated() && !$this->getUser()->getAttribute('signup_type', false, 'registration'))
    {
      $this->redirect('collector/signupChoice');
    }

    $signupType = $this->getUser()->getAttribute('signup_type', null, 'registration');
    $defaultStep = 1;

    if ('seller' == $signupType && !$this->getUser()->hasAttribute('package', 'registration'))
    {
      $defaultStep = 4;
    }

    // Get the current form step, default to 1 if not set
    $this->snStep = $request->getParameter('step', $defaultStep);

    // if the requested step is not 1 and the user is not authenticated
    // redirect the user to step 1
    if ($defaultStep != $this->snStep && !$this->getUser()->isAuthenticated())
    {
      $this->redirect($this->getController()->genUrl(array(
        'sf_route' => 'collector_signup',
        'step'     => $defaultStep
      )));
    }

    if ($this->getUser()->isAuthenticated())
    {
      $completedSteps = $this->getUser()->getCollector()
          ->getSingupNumCompletedSteps();

      // if the requested step is not step 2, but the user has not yet
      // completed step 2 then redirect the user to step 2
      if (2 != $this->snStep && 1 == $completedSteps)
      {
        $this->redirect($this->getController()->genUrl(array(
          'sf_route' => 'collector_signup',
          'step'     => 2
        )));
      }

      if (3 != $this->snStep && 2 == $completedSteps)
      {
        $this->redirect($this->getController()->genUrl(array(
          'sf_route' => 'collector_signup',
          'step'     => 3
        )));
      }
    }

    // create the form object based on the current step
    $formClass = sprintf('CollectorSignupStep%dForm', $this->snStep);

    switch ($this->snStep):
      // for step 1
      case 1:
        // simply create the singup (step 1) form
        $this->form = new $formClass();
        break;
      case 2:
      case 3:
        // the form extends CollectorProfileEditForm,
        // so we add the profile object to it
        $this->form = new $formClass($this->getUser()->getCollector()->getProfile());
        break;
      case 4:
        if ($package = $request->getParameter('package', false))
        {
          $this->getUser()->setAttribute('package', $package, 'registration');
          $this->redirect('@seller_account_information');
        }
        $this->getUser()->setAttribute('package', null, 'registration');
        $this->form = false; //Hack as $this->form is used as required in signupSuccess
        break;
    endswitch;

    // if request is post
    if (sfRequest::POST == $request->getMethod())
    {
      // and the form is valid
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {
        // perform actions for step and valid form
        switch ($this->snStep):

          case 1:
            // create user
            $values = $this->form->getValues();
            $collector = CollectorPeer::createFromArray($values);

            // authenticate the user
            $this->getUser()->Authenticate(true, $collector, false);

            if ('seller' == $this->getUser()->getAttribute('signup_type', null, 'registration'))
            {
              $this->redirect($this->getController()->genUrl(array(
                'sf_route' => 'seller_become',
                'type'     => $this->getUser()->getAttribute('package', null, 'registration'),
              )));
            }
            // redirect to step 2
            $this->redirect($this->getController()->genUrl(array(
              'sf_route' => 'collector_signup',
              'step'     => 2
            )));
            break;

          case 2:
            // update the profile
            $this->form->save();

            // mark step 2 as completed and save
            $this->getUser()->getCollector()->setSingupNumCompletedSteps(2)
                ->save();

            // redirect to step 3
            $this->redirect($this->getController()->genUrl(array(
              'sf_route' => 'collector_signup',
              'step'     => 3
            )));
            break;

          case 3:
            // update the profile
            $this->form->save();

            $collector = $this->getUser()->getCollector();
            // mark step 3 as completed, registration as completed and save
            $collector->setSingupNumCompletedSteps(3)
                ->setHasCompletedRegistration(true)
                ->save();
            $collector->sendToDefensio('UPDATE');

            // redirect to step manage profile
            $this->redirect($this->getController()->genUrl(array(
              'sf_route' => 'manage_profile'
            )));
            break;

        endswitch;
      } // if form is valid
    } // if request is post

    /* * /
   $facebook = $this->getUser()->getFacebook();

   if (($signed_request = $facebook->getSignedRequest()) && !empty($signed_request['oauth_token']))
   {
     // We need to make an extra check for the valid registration fields
     if (json_encode($this->_facebook_fields) === @$signed_request['registration_metadata']['fields'])
     {
       $params = $signed_request['registration'];

       $amStep1Data['username'] = uniqid('fb');
       $amStep1Data['facebook_id'] = $signed_request['user_id'];
       $amStep1Data['password'] = $params['password'];
       $amStep1Data['email'] = $params['email'];
       $amStep1Data['display_name'] = $params['name'];

       $amStep3Data['gender'] = ucfirst($params['gender']);
       $amStep3Data['birthday'] = $params['birthday'];
       $amStep3Data['country'] = $signed_request['user']['country'];
     }
   }
   /* */

    if ('seller' == $signupType)
    {
      $this->buttons = array();

      $this->addBreadcrumb($this->__('Sign Up for a Seller Account'));
      $this->prependTitle($this->__('Sign Up for a Seller Account'));
    }
    else
    {
      $this->buttons = array(
        0 => array(
          'text'  => 'Use Another Web ID',
          'icon'  => 'person',
          'route' => '@collector_signup#openid'
        )
      );

      $this->addBreadcrumb($this->__('Sign Up for a Collector Account'));
      $this->prependTitle($this->__('Sign Up for a Collector Account'));
    }

    return sfView::SUCCESS;
  }

  public function executeSignupFacebook()
  {
    $this->fields = $this->_facebook_fields;

    $this->addBreadcrumb($this->__('Sign Up for a Collector Account'));
    $this->prependTitle($this->__('Sign Up for a Collector Account'));

    return sfView::SUCCESS;
  }

  /**
   * Action VerifyEmail
   */
  public function executeVerifyEmail()
  {
    /* @var $collectorEmail CollectorEmail */
    $collectorEmail = $this->getRoute()->getObject();
    $this->forward404Unless($collectorEmail instanceof CollectorEmail);

    $collector = $collectorEmail->getCollector();
    $collector->setEmail($collectorEmail->getEmail());
    $collector->save();

    $collectorEmail->setIsVerified(true);
    $collectorEmail->save();

    $this->getUser()->Authenticate(true, $collector, true);

    $this->getUser()->setFlash('success', 'Your email has been verified.');
    $this->redirect('@manage_profile');
  }

  /**
   * Action SignupChoice
   *
   * @param sfWebRequest $request
   *
   */
  public function executeSignupChoice(sfWebRequest $request)
  {
    $signupType = $request->getParameter('type');

    if ($this->getUser()->isAuthenticated())
    {
      if ($signupType == 'seller' && !$this->getUser()->isSeller())
      {
        $this->redirect('seller/packages');
      }
      else if ($signupType == 'seller')
      {
        $this->redirect('@manage_collections');
      }

      $this->redirect('@collector_signup');
    }

    if (in_array($signupType, array('collector', 'seller')))
    {
      $this->getUser()->setAttribute('signup_type', $signupType, 'registration');
      $this->getUser()->setAttribute('package', null, 'registration');

      $this->redirect('@seller_choose_package');
    }
  }

}
