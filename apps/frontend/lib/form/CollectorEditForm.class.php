<?php

class CollectorEditForm extends CollectorForm
{

  /** @var CollectorProfileForm */
  protected $profile_form;

  public function configure()
  {
    parent::configure();

    $this->setupPasswordFields();
    $this->embedProfileForm();
    $this->setupProfileGenderField();
    $this->setupProfileCollectorType();
    $this->setupProfileWebsite();

    if ($this->getOption('seller_settings_show'))
    {
      $this->setupSellerSettingsFields($this->getOption('seller_settings_required', false));
    }

    $this->widgetSchema->setLabels(array(
      'display_name'              => 'Screen Name',
      'collector_type'            => 'Collector Type',
      'country_iso3166'           => 'Country',
      'about_what_you_collect'    => 'What do you collect?',
      'about_collections'         => 'About My Items',
      'about_purchase_per_year'   => 'How many times a year do you purchase?',
      'about_most_expensive_item' => "The most you've spent on an item?",
      'about_annually_spend'      => 'Annually?',
      'about_interests'           => 'My Interests Are',
      'website'                   => 'Personal Website',
    ));

    $this->setupDisplayNameValidator();

    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  protected function setupPasswordFields()
  {
    $this->widgetSchema['old_password'] = new sfWidgetFormInputPassword(
      array(
        'label' => 'Current Password'
      ),
      array(
        'placeholder' => 'Enter your current CollectorsQuest.com account password'
      ));
    $this->widgetSchema['password'] = new sfWidgetFormInputPassword(array(), array(
      'placeholder' => 'Set new password here',
    ));
    $this->widgetSchema['password_again'] = new sfWidgetFormInputPassword(array(
      'label'       => 'Confirm Password'
    ), array(
      'placeholder' => 'Confirm your new password'
    ));

    $this->validatorSchema['old_password'] = new sfValidatorPass();
    $this->validatorSchema['password'] = new sfValidatorString(
      array(
        'min_length' => 6,
        'max_length' => 50,
        'required'   => false,
      ), array(
      'max_length' => 'The password is too long (%max_length% characters max).',
      'min_length' => 'The password is too short (%min_length% characters min).',
    ));
    $this->validatorSchema['password_again'] = new sfValidatorPass();

    $this->mergePostValidator(new sfValidatorAnd(array(
      new CollectorEditFormPasswordSchemaValidator(null, array(
        'collector' => $this->getObject(),
      )),
      new sfValidatorSchemaCompare(
        'password', sfValidatorSchemaCompare::EQUAL, 'password_again',
        array('throw_global_error' => true),
        array('invalid' => 'The two passwords do not match, please enter them again!')),
    )));
  }

  /**
   * Add the seller settings fields to the form, whose values are kept in
   * ExtraPropertiesBehavior
   *
   * @param boolean $required
   */
  protected function setupSellerSettingsFields($required = false)
  {
    $this->setupSellerSettingsPayPalFields();
    $this->setupSellerSettingsPhoneNumberField(false);

//    $this->widgetSchema['seller_settings_store_description'] = new sfWidgetFormTextarea(array(
//      'label' => 'Store Description',
//    ));
    $this->widgetSchema['seller_settings_return_policy'] = new sfWidgetFormTextarea(array(
      'label' => 'Return Policy',
    ));
//    $this->widgetSchema['seller_settings_payment_accepted'] = new sfWidgetFormTextarea(array(
//      'label' => 'Payment Accepted',
//    ));

    $this->setupSellerSettingsWelcomeField($required);
    $this->setupSellerSettingsShippingField($required);
    $this->setupSellerSettingsRefundsField($required);
    $this->setupSellerSettingsAdditionalPoliciesField($required);

    $this->validatorSchema['seller_settings_paypal_email'] = new sfValidatorEmail(array('required' => $required));
//    $this->validatorSchema['seller_settings_store_description'] = new sfValidatorString(array('required' => $required));
    $this->validatorSchema['seller_settings_return_policy'] = new sfValidatorString(array('required' => false));
//    $this->validatorSchema['seller_settings_payment_accepted'] = new sfValidatorString(array('required' => false));
  }

  protected function embedProfileForm()
  {
    $this->mergeForm($this->getProfileForm());
  }

  protected function setupProfileGenderField()
  {
    $this->widgetSchema['gender'] = new sfWidgetFormSelectRadio(array(
      'choices'   => array(
        'f' => 'Female',
        'm' => 'Male',
        ''  => 'Rather not say'
      ),
      'formatter' => array($this, 'inlineRadioInputFormatter'),
    ));
  }

  protected function setupProfileCollectorType()
  {
    $this->widgetSchema['collector_type'] = new sfWidgetFormSelectRadio(array(
      'choices'   => $this->getProfileForm()->getCollectorTypeChoices(),
      'formatter' => array($this, 'inlineRadioInputFormatter'),
    ));
  }

  protected function setupProfileWebsite()
  {
    if (!$this->getObject()->getIsSeller())
    {
      $this->widgetSchema['website']->setAttributes(array(
        'placeholder' => 'This feature is only available for sellers',
        'disabled'    => 'disabled',
        'class'       => 'disabled',
      ));
    }
  }

  protected function setupDisplayNameValidator()
  {
    // because sfValidatorPropelUnique is a post validator we cannot modify it,
    // so we add a new unique validator to be execute before the other ones
    // and halt if there is error with it, allowing us to set the proper
    // error message

    $this->widgetSchema['display_name']->setAttribute('required', 'required');
    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorPropelUnique(array(
        'model'  => $this->getModelName(),
        'column' => 'display_name',
      ), array(
        'invalid' => 'A Collector with the same ' .
            $this->widgetSchema->getLabel('display_name') .
            ' already exists.',
      )),
      $this->validatorSchema->getPostValidator(),
    ), array('halt_on_error' => true)));
  }

  /**
   * Update the Collector object with the form values
   *
   * @param array $values
   */
  protected function doUpdateObject($values)
  {
    parent::doUpdateObject($values);

    // Also update the collector profile
    $this->getObject()->getProfile()->fromArray($values, BasePeer::TYPE_FIELDNAME);

    // and update the values kept in by ExtraPropertiesBehavior
    if (isset($values['seller_settings_paypal_email']))
    {
      $this->getObject()->setSellerSettingsPaypalEmail(
        $values['seller_settings_paypal_email']
      );
    }
    if (isset($values['seller_settings_paypal_fname']))
    {
      $this->getObject()->setSellerSettingsPaypalFirstName(
        $values['seller_settings_paypal_fname']
      );
    }
    if (isset($values['seller_settings_paypal_lname']))
    {
      $this->getObject()->setSellerSettingsPaypalLastName(
        $values['seller_settings_paypal_lname']
      );
    }
    if (isset($values['seller_settings_phone_code']))
    {
      $this->getObject()->setSellerSettingsPhoneCode(
        $values['seller_settings_phone_code']
      );
    }
    if (isset($values['seller_settings_phone_number']))
    {
      $this->getObject()->setSellerSettingsPhoneNumber(
        $values['seller_settings_phone_number']
      );
    }
    //  if (isset($values['seller_settings_store_description']))
    //  {
    //    $this->getObject()->setSellerSettingsStoreDescription(
    //      $values['seller_settings_store_description']);
    //  }
    if (isset($values['seller_settings_return_policy']))
    {
      $this->getObject()->setSellerSettingsReturnPolicy(
        $values['seller_settings_return_policy']
      );
    }
    //  if (isset($values['seller_settings_payment_accepted']))
    //  {
    //    $this->getObject()->setSellerSettingsPaymentAccepted(
    //      $values['seller_settings_payment_accepted']);
    //  }
    if (isset($values['seller_settings_welcome']))
    {
      $this->getObject()->setSellerSettingsWelcome($values['seller_settings_welcome']);
    }
    if (isset($values['seller_settings_shipping']))
    {
      $this->getObject()->setSellerSettingsShipping($values['seller_settings_shipping']);
    }
    if (isset($values['seller_settings_refunds']))
    {
      $this->getObject()->setSellerSettingsRefunds($values['seller_settings_refunds']);
    }
    if (isset($values['seller_settings_additional_policies']))
    {
      $this->getObject()->setSellerSettingsAdditionalPolicies(
        $values['seller_settings_additional_policies']
      );
    }
  }

  /**
   * Update form defaults from object, adding fields that are kept in ExtraProperties
   * behavior
   */
  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    $this->setDefaults(array_merge($this->defaults, array(
      'seller_settings_paypal_email'          => $this->getObject()->getSellerSettingsPaypalEmail(),
      'seller_settings_paypal_fname'          => $this->getObject()->getSellerSettingsPaypalFirstName(),
      'seller_settings_paypal_lname'          => $this->getObject()->getSellerSettingsPaypalLastName(),
      'seller_settings_phone_code'            => $this->getObject()->getSellerSettingsPhoneCode(),
      'seller_settings_phone_number'          => $this->getObject()->getSellerSettingsPhoneNumber(),
//      'seller_settings_store_description'   => $this->getObject()->getSellerSettingsStoreDescription(),
      'seller_settings_return_policy'         => $this->getObject()->getSellerSettingsReturnPolicy(),
//      'seller_settings_payment_accepted'    => $this->getObject()->getSellerSettingsPaymentAccepted(),
      'seller_settings_welcome'               => $this->getObject()->getSellerSettingsWelcome(),
      'seller_settings_shipping'              => $this->getObject()->getSellerSettingsShipping(),
      'seller_settings_refunds'               => $this->getObject()->getSellerSettingsRefunds(),
      'seller_settings_additional_policies'   => $this->getObject()->getSellerSettingsAdditionalPolicies(),
    )));
  }

  protected function unsetFields()
  {
    unset ($this['username']);
    unset ($this['email']);
    unset ($this['has_completed_registration']);
    unset ($this['user_type']);
    unset ($this['items_allowed']);
    unset ($this['max_collectibles_for_sale']);
    unset ($this['locale']);
    unset ($this['is_spam']);
    unset ($this['is_public']);
    // merged from CollectorProfileEditForm
    unset ($this['about_new_item_every']);

    parent::unsetFields();
  }

  /**
   * @return    CollectorProfileForm
   */
  protected function getProfileForm()
  {
    if (null === $this->profile_form)
    {
      $this->profile_form = new CollectorProfileEditForm(
        $this->getObject()->getProfile());
      $this->profile_form->widgetSchema->setFormFormatterName('Bootstrap');
    }

    return $this->profile_form;
  }

  public function inlineRadioInputFormatter($widget, $inputs)
  {
    $rows = array();
    foreach ($inputs as $input)
    {
      $rows[] = $widget->renderContentTag('label', $input['input'] . strip_tags($input['label']), array('class' => 'radio inline'));
    }

    return !$rows ? '' : $widget->renderContentTag('div', implode($this->getOption('separator'), $rows), array('class' => $this->getOption('class')));
  }

  public function updateAboutMeColumn($value = null)
  {
    $value = strip_tags($value);
    return $value;
  }

  public function updateAboutCollectionsColumn($value = null)
  {
    $value = strip_tags($value);
    return $value;
  }

  public function updateAboutInterestsColumn($value = null)
  {
    $value = strip_tags($value);
    return $value;
  }

  public function setupSellerSettingsPayPalFields()
  {
    $this->widgetSchema['seller_settings_paypal_email'] = new sfWidgetFormInputText(array(
      'label' => 'Email Address',
    ), array(
      'type' => 'email', 'required' => 'required'
    ));

    $this->widgetSchema['seller_settings_paypal_fname'] = new sfWidgetFormInputText(array(
      'label' => 'First Name',
    ), array(
      'required' => 'required',
    ));

    $this->widgetSchema['seller_settings_paypal_lname'] = new sfWidgetFormInputText(array(
      'label' => 'Last Name',
    ), array(
      'required' => 'required',
    ));

    $this->validatorSchema['seller_settings_paypal_email'] = new sfValidatorEmail(
      array('required' => true)
    );

    $this->validatorSchema['seller_settings_paypal_fname'] = new sfValidatorString(
      array('required' => true)
    );

    $this->validatorSchema['seller_settings_paypal_lname'] = new sfValidatorString(
      array('required' => true)
    );

    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'validateSellerSettingsPayPal')))
    );
  }

  public function setupSellerSettingsPhoneNumberField($required = false)
  {
    $this->widgetSchema['seller_settings_phone_number'] = new sfWidgetFormInputText(array(
      'label' => 'Phone Number',
    ));

    $this->validatorSchema['seller_settings_phone_number'] = new sfValidatorRegex(array(
      'pattern' => '/^0(?:0|11)(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
          2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
          4[987654310]|3[9643210]|2[70]|7|1)\d{0,14}$/',
      'required' => $required,
    ));
  }

  public function setupSellerSettingsWelcomeField($required = false)
  {
    $this->widgetSchema['seller_settings_welcome'] = new sfWidgetFormTextarea(array(
      'label' => 'Welcome',
    ));

    $this->validatorSchema['seller_settings_welcome'] = new sfValidatorString(
      array('required' => $required)
    );
  }

  public function setupSellerSettingsShippingField($required = false)
  {
    $this->widgetSchema['seller_settings_shipping'] = new sfWidgetFormTextarea(array(
      'label' => 'Shipping',
    ));

    $this->validatorSchema['seller_settings_shipping'] = new sfValidatorString(
      array('required' => $required)
    );
  }

  public function setupSellerSettingsRefundsField($required = false)
  {
    $this->widgetSchema['seller_settings_refunds'] = new sfWidgetFormTextarea(array(
      'label' => 'Refunds and Exchange',
    ));

    $this->validatorSchema['seller_settings_refunds'] = new sfValidatorString(
      array('required' => $required)
    );
  }

  public function setupSellerSettingsAdditionalPoliciesField($required = false)
  {
    $this->widgetSchema['seller_settings_additional_policies'] = new sfWidgetFormTextarea(array(
      'label' => 'Additional Policies and FAQs',
    ));

    $this->validatorSchema['seller_settings_additional_policies'] = new sfValidatorString(
      array('required' => $required)
    );
  }

  public function validateSellerSettingsPayPal($validator, $values)
  {
    $data = array(
      'GetVerifiedStatusFields' => array(
        'MatchCriteria' => 'NAME',
        'EmailAddress' => $values['seller_settings_paypal_email'],
        'FirstName' => $values['seller_settings_paypal_fname'],
        'LastName' => $values['seller_settings_paypal_lname']
      )
    );

    $AdaptivePayments = cqStatic::getPayPaylAdaptivePaymentsClient();
    $result = $AdaptivePayments->GetVerifiedStatus($data);

    if ($AdaptivePayments->APICallSuccessful($result['Ack']))
    {
      $this->getObject()->setSellerSettingsPaypalAccountStatus('VERIFIED');
      $this->getObject()->setSellerSettingsPaypalAccountId($result['AccountID']);
      $this->getObject()->setSellerSettingsPaypalBusinessName($result['BusinessName']);
    }
    else
    {
      if (isset($result['Errors'][0]))
      {
        throw new sfValidatorError($validator, $result['Errors'][0]['Message']);
      }

      throw new sfValidatorError($validator, 'invalid');
    }

    return $values;
  }
}
