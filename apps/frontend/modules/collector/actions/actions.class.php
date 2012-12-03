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

    /** @var $q FrontendCollectorCollectionQuery */
    $q = CollectorCollectionQuery::create()
      ->filterByCollector($collector)
      ->hasPublicCollectibles();
    $this->collectionsCount = $q->count();

    $q = FrontendCollectionCollectibleQuery::create()
      ->filterByCollector($collector);
    $this->collectiblesCount = $q->count();

    $this->i_collect_tags = $collector->getICollectTags();
    $this->i_sell_tags = $collector->getISellTags();

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collector);

    /*
     * Set meta description
     *
     * @see https://basecamp.com/1759305/projects/288122-collectorsquest-com/todos/11749079-collector-page
     */

    $about_me = $profile->getAboutMe();
    $about_collections = $profile->getAboutCollections();
    $new_description = $about_me . ' | ' . $about_collections;

    if (empty($about_me))
    {
      // if user has a few collectibles we don't want to display them
      $collectibles = $this->collectiblesCount;
      if ($collectibles < 25)
      {
        $meta_description = sprintf(
          'Collectors Quest member %s is sharing their %s collection on Collectors Quest.
           Upload your own collectibles and show off today!',
          $collector->getDisplayName(), $profile->getAboutWhatYouCollect()
        );
      }
      else
      {
        $meta_description = sprintf(
          '%s shares their %s items of their %s collection on Collectors Quest.
           Upload your own items and show off today!',
          $collector->getDisplayName(), $collectibles, $profile->getAboutWhatYouCollect()
        );
      }
    }

    // Display only About information
    else if (strlen($about_me) > 156)
    {
      // Remove HTML tags and cut
      $meta_description = $about_me;
    }

    // About information too short, adding about collections info
    else if (strlen($new_description) > 156)
    {
      $meta_description = $new_description;
    }

    // About information plus about collections information too short, adding about me info
    else
    {
      $meta_description = $about_me . ' | ' . $about_collections . ' | ' . $profile->getAboutWhatYouCollect();
    }

    // Finally add the meta description to the response
    $this->getResponse()->addMeta(
      'description', cqStatic::truncateText(strip_tags($meta_description), 156, '...', true)
    );

    $this->dispatcher->notify(
      new sfEvent($this, 'application.show_object', array('object' => $this->collector))
    );

    return sfView::SUCCESS;
  }

  public function executeDropbox()
  {
    if ($this->getUser()->isAuthenticated() && ($collector = $this->getCollector()))
    {
      $id   = $collector->getId();
      $slug = $collector->getSlug();

      return $this->redirect('@dropbox_by_slug?collector_id=' . $id . '&collector_slug=' . $slug);
    }

    return $this->redirect('@login');
  }

  public function executeMe()
  {
    if ($this->getUser()->isAuthenticated() && ($collector = $this->getCollector()))
    {
      $id   = $collector->getId();
      $slug = $collector->getSlug();

      return $this->redirect('@collector_by_slug?id=' . $id . '&slug=' . $slug);
    }

    return $this->redirect('@login');
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

    // if we are comming from seller signup page, and the user
    // has selected package to pay for after sign up we need to save it here
    if ($request->hasParameter('preselect_package'))
    {
      $this->getUser()->setAttribute(
        'preselected_seller_package',
        $request->getParameter('preselect_package')
      );
    }

    $this->form = new CollectorSignupStep1Form();

    if (sfRequest::POST == $request->getMethod())
    {
      $this->form->bind($request->getParameter($this->form->getName()));

      if ($this->form->isValid())
      {
        $values = $this->form->getValues();
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

        if ($this->getUser()->hasAttribute('preselected_seller_package'))
        {
          $package = $this->getUser()->getAttribute('preselected_seller_package');
          $this->getUser()->getAttributeHolder()->remove('preselected_seller_package');

          return $this->redirect(array(
              'sf_route' =>'seller_packages',
              'package' => $package
          ));
        }

        return $this->redirect('@mycq_profile');
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
          ->getSignupNumCompletedSteps();

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
            $this->getUser()->getCollector()->setSignupNumCompletedSteps(2)
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
            $collector->setSignupNumCompletedSteps(3)
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

    $this->rpxnow = sfConfig::get('app_credentials_rpxnow');

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
    $this->loadHelpers(array('cqImages'));

    return $this->redirect(src_tag_collector($collector, '100x100', true, array('absolute'=> true)), 301);
  }

  /**
   * Verify new user emails
   */
  public function executeVerifyEmail(sfWebRequest $request)
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

    if ($request->getParameter('welcome') == '1')
    {
      $cqEmail = new cqEmail($this->getMailer());
      $cqEmail->send($collector->getUserType() . '/welcome_to_cq', array(
        'to' => $collector->getEmail(),
      ));
    }

    $this->getUser()->setFlash('success', sprintf(
      'Your email, %s, has been verified', $collector->getEmail()
    ));

    return $this->redirect('@mycq_profile');
  }

}
