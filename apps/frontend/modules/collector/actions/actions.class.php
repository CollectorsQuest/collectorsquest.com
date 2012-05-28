<?php

class collectorActions extends cqFrontendActions
{

  public function executeIndex(sfWebRequest $request)
  {
    /** @var $collector Collector */
    $collector = $this->getRoute()->getObject();

    /** @var $profile CollectorProfile */
    $profile = $collector->getProfile();

    /**
     * Increment the number of views
     */
    if (!$this->getCollector()->isOwnerOf($collector))
    {
      $profile->setNumViews($profile->getNumViews() + 1);
      $profile->save();
    }

    $this->collector = $collector;
    $this->profile   = $profile;

    return sfView::SUCCESS;
  }

  public function executeDropbox()
  {
    if ($this->getUser()->isAuthenticated() && ($collector = $this->getCollector()))
    {
      $id   = $collector->getId();
      $slug = $collector->getSlug();

      $this->redirect('@dropbox_by_slug?collector_id=' . $id . '&collector_slug=' . $slug);
    }

    $this->redirect('@login');
  }

  public function executeMe()
  {
    if ($this->getUser()->isAuthenticated() && ($collector = $this->getCollector()))
    {
      $id   = $collector->getId();
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
      '@mycq');

    $this->form = new CollectorSignupStep1Form();

    if (sfRequest::POST == $request->getMethod())
    {
      $this->form->bind($request->getParameter($this->form->getName()));

      if ($this->form->isValid())
      {
        $values = $this->form->getValues();
        // try to guess the collector's country based on IP address
        $values['country_iso3166'] = cqStatic::getGeoIpCountryCode(
          $request->getRemoteAddress(), $check_against_geo_country = true);

        // create the collector
        $collector = CollectorPeer::createFromArray($values);

        // authenticate the collector and redirect to @mycq_profile
        $this->getUser()->Authenticate(true, $collector, false);
        $this->redirect('@mycq_profile');
      }
    }

    // Everything below this comment is the old implementation.
    // When it has been confirmed that it will not be needed, it should be deleted

    /* * /
   if (!$this->getUser()->isAuthenticated() && !$this->getUser()->getAttribute('signup_type', false, 'registration'))
   {
     $this->redirect('collector/signupChoice');
   }
   $signupType = $this->getUser()->getAttribute('signup_type', null, 'registration');
   /* */

    $defaultStep = 1;

    /* * /
    if ('seller' == $signupType && !$this->getUser()->hasAttribute('package', 'registration'))
    {
      $defaultStep = 4;
    }
    /* * /

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
      /* * /
      case 4:
        if ($package = $request->getParameter('package', false))
        {
          $this->getUser()->setAttribute('package', $package, 'registration');
          $this->redirect('@seller_account_information');
        }
        $this->getUser()->setAttribute('package', null, 'registration');
        $this->form = false; //Hack as $this->form is used as required in signupSuccess
        break;
      /* * /
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

            /* * /
            if ('seller' == $this->getUser()->getAttribute('signup_type', null, 'registration'))
            {
              $this->redirect($this->getController()->genUrl(array(
                'sf_route' => 'seller_become',
                'type'     => $this->getUser()->getAttribute('package', null, 'registration'),
              )));
            }
            /* * /

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
              'sf_route' => 'mycq'
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

    /* * /
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
    /* */

    return sfView::SUCCESS;
  }

  /**
   * Action Avatar
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeAvatar(sfWebRequest $request)
  {
    $collector = $this->getRoute()->getObject();

    $this->loadHelpers('cqImages');
    $this->redirect(src_tag_collector($collector, '100x100', true, array('absolute'=> true)), 301);
  }

}
