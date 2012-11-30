<?php

class CollectorEditForm extends CollectorForm
{

  /** @var CollectorProfileForm */
  protected $profile_form;

  public function configure()
  {
    parent::configure();

    $this->embedProfileForm();
    $this->setupPasswordFields();
    $this->setupProfileGenderField();
    $this->setupProfileCollectorType();

    $this->widgetSchema->setHelp(
      'birthday',
      '<strong>Note:</strong> This information will remain private!'
    );

    if ($this->getOption('seller_settings_show'))
    {
      $this->setupSellerSettingsFields($this->getOption('seller_settings_required', false));
    }

    $this->widgetSchema->setLabels(array(
      'display_name'              => 'Screen Name',
      'collector_type'            => 'Collector Type',
      'country_iso3166'           => 'Country',
      'about_what_you_collect'    => 'What do you collect?',
      'about_what_you_sell'       => 'What do you sell?',
      'about_me'                  => 'About me',
      'about_collections'         => 'About my items',
      'about_interests'           => 'About my interests',
      'about_purchase_per_year'   => 'How many times a year do you purchase?',
      'about_most_expensive_item' => "The most you've spent on an item?",
      'about_annually_spend'      => 'Annually?',
      'website'                   => 'Website',
    ));

    $this->setupDisplayNameValidator();

    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  protected function setupPasswordFields()
  {
    $this->widgetSchema['old_password'] = new sfWidgetFormInputPassword(array(
        'label'       => 'Current Password',
      ), array(
        'placeholder' => 'Enter your current CollectorsQuest.com account password',
        'required'    => 'required',
    ));
    $this->widgetSchema['password'] = new sfWidgetFormInputPassword(array(
        'label'       => 'New Password',
      ), array(
        'placeholder' => 'Enter your new password',
        'required'    => 'required',
    ));
    $this->widgetSchema['password_again'] = new sfWidgetFormInputPassword(array(
        'label'       => 'Confirm New Password',
      ), array(
        'placeholder' => 'Confirm your new password',
        'required'    => 'required',
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

    $this->setupSellerSettingsStoreNameField();
    $this->setupSellerSettingsStoreTitleField();
    $this->setupSellerSettingsRefundsField();
    $this->setupSellerSettingsReturnPolicyField();
    $this->setupSellerSettingsWelcomeField($required);
    $this->setupSellerSettingsShippingField($required);
    $this->setupSellerSettingStoreHeaderImageField();
    $this->setupSellerSettingsAdditionalPoliciesField($required);
    $this->setupSellerSettingsTaxFields($required);

    $this->validatorSchema['seller_settings_paypal_email'] = new sfValidatorEmail(
      array('required' => $required)
    );
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
        'invalid' => 'A Collector with the same screen name "' .
            $this->widgetSchema->getLabel('display_name') .
            '" already exists.',
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
    if (isset($values['seller_settings_store_name']))
    {
      $this->getObject()->setSellerSettingsStoreName(
        strip_tags($values['seller_settings_store_name'])
      );
    }
    if (isset($values['seller_settings_store_title']))
    {
      $this->getObject()->setSellerSettingsStoreTitle(
        strip_tags($values['seller_settings_store_title'])
      );
    }
    if (isset($values['seller_settings_return_policy']))
    {
      $this->getObject()->setSellerSettingsReturnPolicy(
        strip_tags($values['seller_settings_return_policy'])
      );
    }
    if (isset($values['seller_settings_return_policy']))
    {
      $this->getObject()->setSellerSettingsReturnPolicy(
        strip_tags($values['seller_settings_return_policy'])
      );
    }
    if (isset($values['seller_settings_welcome']))
    {
      $this->getObject()->setSellerSettingsWelcome(
        strip_tags($values['seller_settings_welcome'])
      );
    }
    if (isset($values['seller_settings_shipping']))
    {
      $this->getObject()->setSellerSettingsShipping(
        strip_tags($values['seller_settings_shipping'])
      );
    }
    if (isset($values['seller_settings_refunds']))
    {
      $this->getObject()->setSellerSettingsRefunds(
        strip_tags($values['seller_settings_refunds'])
      );
    }
    if (isset($values['seller_settings_additional_policies']))
    {
      $this->getObject()->setSellerSettingsAdditionalPolicies(
        strip_tags($values['seller_settings_additional_policies'])
      );
    }


    if (isset($values['seller_settings_tax_country']))
    {
      $this->getObject()->setSellerSettingsTaxCountry(
        strip_tags($values['seller_settings_tax_country'])
      );
    }
    if (isset($values['seller_settings_tax_state']))
    {
      $this->getObject()->setSellerSettingsTaxState(
        strip_tags($values['seller_settings_tax_state'])
      );
    }
    if (isset($values['seller_settings_tax_percentage']))
    {
      $this->getObject()->setSellerSettingsTaxPercentage(
        strip_tags($values['seller_settings_tax_percentage'])
      );
    }

    // if user selected the delete widget remove the old header image
    if (isset($values['seller_settings_store_header_image_delete']) &&
        $values['seller_settings_store_header_image_delete'])
    {
      $old_header_image = $this->getObject()->getMultimediaByRole(
        CollectorPeer::MULTIMEDIA_ROLE_STOREFRONT_HEADER_IMAGE
      );
      if ($old_header_image)
      {
        $old_header_image->delete();
      }
    }

    // handle store header image persistance
    if (isset($values['seller_settings_store_header_image']) &&
        $values['seller_settings_store_header_image'])
    {
      // if we have a previous image set, remove it.
      $old_header_image = $this->getObject()->getMultimediaByRole(
        CollectorPeer::MULTIMEDIA_ROLE_STOREFRONT_HEADER_IMAGE
      );
      if ($old_header_image)
      {
        $old_header_image->delete();
      }

      // add the new store header image
      $multimedia = $this->getObject()->addMultimedia(
        $values['seller_settings_store_header_image'],
        array('role' => CollectorPeer::MULTIMEDIA_ROLE_STOREFRONT_HEADER_IMAGE)
      );

      if ($multimedia)
      {
        // and resize it to the expected dimensions
        $storefront_settings = sfConfig::get('app_marketplace_storefront_settings');

        $multimedia->makeCustomThumb(
          $storefront_settings['header_image_size'][0],
          $storefront_settings['header_image_size'][1],
          $storefront_settings['header_image_name'],
          $method = 'center'
        );
      }
    }
  }

  /**
   * Update form defaults from object, adding fields that are kept in ExtraProperties
   * behavior
   */
  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    $this->setDefaults(array_merge($this->defaults, array(
      'seller_settings_paypal_email'          => $this->getObject()->getSellerSettingsPaypalEmail(),
      'seller_settings_paypal_fname'          => $this->getObject()->getSellerSettingsPaypalFirstName(),
      'seller_settings_paypal_lname'          => $this->getObject()->getSellerSettingsPaypalLastName(),
      'seller_settings_phone_code'            => $this->getObject()->getSellerSettingsPhoneCode(),
      'seller_settings_phone_number'          => $this->getObject()->getSellerSettingsPhoneNumber(),
      'seller_settings_store_name'            => $this->getObject()->getSellerSettingsStoreName(),
      'seller_settings_store_title'           => $this->getObject()->getSellerSettingsStoreTitle(),
      'seller_settings_return_policy'         => $this->getObject()->getSellerSettingsReturnPolicy(),
      'seller_settings_welcome'               => $this->getObject()->getSellerSettingsWelcome(),
      'seller_settings_shipping'              => $this->getObject()->getSellerSettingsShipping(),
      'seller_settings_refunds'               => $this->getObject()->getSellerSettingsRefunds(),
      'seller_settings_additional_policies'   => $this->getObject()->getSellerSettingsAdditionalPolicies(),
      'seller_settings_tax_country'           => $this->getObject()->getSellerSettingsTaxCountry(),
      'seller_settings_tax_state'             => $this->getObject()->getSellerSettingsTaxState(),
      'seller_settings_tax_percentage'        => sprintf('%01.2f', $this->getObject()->getSellerSettingsTaxPercentage()),
    )));
  }

  protected function unsetFields()
  {
    unset ($this['username']);
    unset ($this['email']);
    unset ($this['has_completed_registration']);
    unset ($this['user_type']);
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

  public function updateDisplayNameColumn($value = null)
  {
    return trim(strip_tags($value));
  }

  public function updateAboutMeColumn($value = null)
  {
    return trim(strip_tags($value));
  }

  public function updateAboutCollectionsColumn($value = null)
  {
    return trim(strip_tags($value));
  }

  public function updateAboutInterestsColumn($value = null)
  {
    return trim(strip_tags($value));
  }

  public function setupSellerSettingsPayPalFields()
  {
    $this->widgetSchema['seller_settings_paypal_email'] = new sfWidgetFormInputText(array(
      'label' => 'Email Address',
    ), array(
      'type'     => 'email',
      'required' => 'required'
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

/*    @todo remove comment out
    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(
        array('callback' => array($this, 'validateSellerSettingsPayPal')),
        array('invalid' => 'We cannot verify the status of your PayPal account.<br/>
                            Please check the information you\'ve entered and make
                            sure that it matches your PayPal account.')
      )
    );*/
  }

  public function setupSellerSettingsPhoneNumberField($required = false)
  {
    $this->widgetSchema['seller_settings_phone_number'] = new sfWidgetFormInputText(array(
      'label' => 'Phone Number',
    ));

    $this->widgetSchema->setHelp('seller_settings_phone_number', 'Use format: +1-123-456-7890, 00123-456-789-0123, 00123/123/456 7890');

    $this->validatorSchema['seller_settings_phone_number'] = new sfValidatorRegex(array(
      'pattern' => '/^(\+|00)?\s*\d{1,3}\s*[-\/\(]?\d{1,3}[-\)\/\s]*[-\d\s]{5,10}$/',
      'required' => $required,
    ));
  }

  public function setupSellerSettingsStoreNameField()
  {
    $this->widgetSchema['seller_settings_store_name'] = new sfWidgetFormInputText(
      array('label' => 'Store Name'),
      array('required' => 'required')
    );

    $this->validatorSchema['seller_settings_store_name'] = new sfValidatorString(
      array('required' => true)
    );
  }

  public function setupSellerSettingsStoreTitleField()
  {
    $this->widgetSchema['seller_settings_store_title'] = new sfWidgetFormInputText(
      array('label' => 'Store Slogan'),
      array('required' => 'required')
    );

    $this->validatorSchema['seller_settings_store_title'] = new sfValidatorString(
      array('max_length' => 55, 'required' => true)
    );
  }

  public function setupSellerSettingsWelcomeField($required = false)
  {
    $this->widgetSchema['seller_settings_welcome'] = new sfWidgetFormTextarea(array(
      'label' => 'About Your Store',
    ));

    $this->validatorSchema['seller_settings_welcome'] = new sfValidatorString(
      array('required' => $required)
    );
  }

  public function setupSellerSettingsShippingField($required = false)
  {
    $this->widgetSchema['seller_settings_shipping'] = new sfWidgetFormTextarea(array(
      'label' => 'Shipping Policy',
    ));

    $this->validatorSchema['seller_settings_shipping'] = new sfValidatorString(
      array('required' => $required)
    );
  }

  public function setupSellerSettingsRefundsField()
  {
    $this->widgetSchema['seller_settings_refunds'] = new sfWidgetFormTextarea(
      array('label' => 'Refunds Policy'),
      array('required' => 'required')
    );

    $this->validatorSchema['seller_settings_refunds'] = new sfValidatorString(
      array('required' => true)
    );
  }

  public function setupSellerSettingsReturnPolicyField()
  {
    $this->widgetSchema['seller_settings_return_policy'] = new sfWidgetFormTextarea(
      array('label' => 'Return Policy'),
      array('required' => 'required')
    );

    $this->validatorSchema['seller_settings_return_policy'] = new sfValidatorString(
      array('required' => true)
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

  public function setupSellerSettingsTaxFields($required = false)
  {
    $c = new Criteria();
    // Restrict to "United States" only
    $c->add(iceModelGeoCountryPeer::ID, 226);

    $this->widgetSchema['seller_settings_tax_country'] = new sfWidgetFormPropelChoice(array(
        'label' => 'Country', 'model' => 'iceModelGeoCountry', 'add_empty' => true,
        'key_method' => 'getIso3166', 'criteria' => $c
    ));
    $this->validatorSchema['seller_settings_tax_country'] =  new sfValidatorPropelChoice(array(
        'model' => 'iceModelGeoCountry', 'column' => 'iso3166',
        'criteria' => $c, 'required' => $required
    ));

    $this->widgetSchema['seller_settings_tax_state'] = new sfWidgetFormInputText(
      array('label' => 'State / Province')
    );
    $this->validatorSchema['seller_settings_tax_state'] = new sfValidatorString(
      array('max_length' => 100, 'required' => $required)
    );

    $this->widgetSchema['seller_settings_tax_percentage'] = new sfWidgetFormInputText(
      array('label' => 'Percentage'), array('required' => $required)
    );
    $this->validatorSchema['seller_settings_tax_percentage'] = new cqValidatorPrice(
      array('required' => false, 'max' => 50), array('max' => 'You cannot set Tax more than 50%',
        'invalid' => 'The tax percentage you have specified is not valid')
    );

    $this->mergePostValidator(new cqValidatorCountryRegions(array(
      'country_field' => 'seller_settings_tax_country',
      'region_field' => 'seller_settings_tax_state',
    ), array(
      'invalid' => 'Sorry this State / Province is wrong',
    )));
  }

  public function validateSellerSettingsPayPal($validator, $values)
  {
    $data = array(
      'GetVerifiedStatusFields' => array(
        'MatchCriteria' => 'NAME',
        'EmailAddress'  => $values['seller_settings_paypal_email'],
        'FirstName'     => $values['seller_settings_paypal_fname'],
        'LastName'      => $values['seller_settings_paypal_lname']
      )
    );

    $AdaptivePayments = cqStatic::getPayPalAdaptivePaymentsClient();
    $result = $AdaptivePayments->GetVerifiedStatus($data);

    if ($AdaptivePayments->APICallSuccessful($result['Ack']))
    {
      $this->getObject()->setSellerSettingsPaypalAccountStatus($result['AccountStatus']);
      $this->getObject()->setSellerSettingsPaypalAccountId($result['AccountID']);
      $this->getObject()->setSellerSettingsPaypalBusinessName($result['BusinessName']);
    }
    else
    {
      if (
        isset($result['Errors'][0]) &&
        $result['Errors'][0]['Message'] != 'Cannot determine PayPal Account status'
      ) {
        throw new sfValidatorError($validator, $result['Errors'][0]['Message']);
      }

      throw new sfValidatorError($validator, 'invalid');
    }

    return $values;
  }

  public function setupSellerSettingStoreHeaderImageField()
  {
    $storefront_settings = sfConfig::get('app_marketplace_storefront_settings');
    $header_image = $this->getObject()->getMultimediaByRole(CollectorPeer::MULTIMEDIA_ROLE_STOREFRONT_HEADER_IMAGE);

    $this->widgetSchema['seller_settings_store_header_image'] = new sfWidgetFormInputFileEditable(array(
        'label' => 'Store Header Image',
        'is_image' => true,
        'file_src' => $header_image
            ? $header_image->getRelativePath($storefront_settings['header_image_name'])
            : false,
        'with_delete' => $header_image ? true : false,
        'delete_label' => 'Remove the current header image',
    ));

    $this->validatorSchema['seller_settings_store_header_image'] = new cqValidatorFile(array(
      'mime_types' => 'cq_supported_images',
      'required' => false,
      'max_size' => 10485760, // 10mb
    ));
    $this->validatorSchema['seller_settings_store_header_image_delete'] = new sfValidatorBoolean(array(
        'required' => false,
    ));
  }

}
