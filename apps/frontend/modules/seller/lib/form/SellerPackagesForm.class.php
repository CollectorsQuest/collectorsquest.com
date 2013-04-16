<?php

/**
 * Filename: SellerPackagesForm.class.php
 *
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 4/9/12
 * Id: $Id$
 */

class SellerPackagesForm extends BaseForm
{
  const PENDING_TRANSACTION_TIMEOUT = '30 minutes';


  /* @var $promotion Promotion */
  public $promotion = null;

  /* @var $package Package */
  public $package = null;

  public function configure()
  {
    $this->setupPackageIdField();
    $this->setupPromoCodeField();
    $this->setupPaymentTypeField();
    $this->setupCountryField();
    $this->setupCardTypeField();
    $this->setupCardNumberField();
    $this->setupCardExpiryDateField();
    $this->setupCardVerificationNumberField();
    $this->setupCardFirstNameField();
    $this->setupCardLastNameField();
    $this->setupCardStreetField();
    $this->setupCardCityField();
    $this->setupCardStateField();
    $this->setupCardZipField();

    $this->setupTermsField();
    $this->setupPendingTransactionConfirmationField();

    // disable CSRF to allow user signup in the same request
    // this form cannot be used maliciously anyways
    $this->disableCSRFProtection();

    $this->widgetSchema->setFormFormatterName('Bootstrap');
    $this->widgetSchema->setNameFormat('packages[%s]');
  }

  protected function setupPackageIdField()
  {
    $this->setWidget('package_id', new sfWidgetFormSelectRadio(array(
      'choices'    => PackagePeer::getAllPackageLabelsForSelectById(),
      'label'      => 'Package',
      'formatter'  => function($widget, $inputs)
      {
        /* @var $widget sfWidget  */

        $rows = array();
        foreach ($inputs as $input)
        {
          $rows[] = $widget->renderContentTag('label',
              $input['input'] . html_entity_decode($input['label']),
            array('class'=> 'radio')
          );
        }

        return !$rows ? '' : $widget->renderContentTag(
          'div',
          implode($widget->getOption('separator'), $rows),
          array('class' => $widget->getOption('class'))
        );
      }
    ), array(
      'required' => 'required',
    )));

    $this->setValidator('package_id', new sfValidatorChoice(array(
      'choices'=> $this->getPackagesKeys(),
    ), array(
      'required'=> 'No package selected!',
      'invalid' => 'Invalid package'
    )));
  }

  protected function setupCountryField()
  {
    $this->setWidget('country', new cqWidgetFormChoiceGeoIpCountry(array(
      'remote_address' => cqContext::getInstance()->getRequest()->getRemoteAddress(),
      'add_empty'=> true,
    ), array(
      'placeholder' => 'Country',
    )));

    $this->setValidator('country', new sfValidatorI18nChoiceCountry(array(
    )));
  }

  protected function setupPromoCodeField()
  {
    $this->setWidget('promo_code', new sfWidgetFormInputText(array(
      'label'=> 'Promo code',
    ), array(
      'required' => 'required',
    )));
    $this->setValidator('promo_code', new sfValidatorString(
      array('required' => false, 'trim' => true),
      array('required' => 'The promo code is required while we are in private beta!')
    ));
    $this->mergePreValidator(
      new sfValidatorCallback(array('callback'=> array($this, 'applyPromoCode')))
    );
  }

  protected function setupPaymentTypeField()
  {
    $this->setWidget('payment_type', new sfWidgetFormChoice(array(
      'choices'           => $this->getPaymentTypes(),
      'renderer_class'    => 'cqWidgetFormSelectPayment',
      'renderer_options'  => array(
        'class'=> 'packages unstyled',
      ),
    )));

    $this->setValidator(
      'payment_type', new sfValidatorChoice(array('choices'=> array_keys($this->getPaymentTypes())))
    );
  }

  protected function setupCardTypeField()
  {
    $this->setWidget('cc_type', new sfWidgetFormChoice(array(
        'choices'=> array_merge(array('' => ''), $this->getCardTypes()),
        'label' => 'Card Type',
      ), array(
        'placeholder' => 'credit/debit card type',
    )));
    $this->setValidator(
      'cc_type', new sfValidatorChoice(array('choices'=> array_keys($this->getCardTypes()), 'required' => false))
    );
  }

  protected function setupCardNumberField()
  {
    $this->setWidget('cc_number', new cqWidgetFormCreditCard(array(
        'label' => 'Card Numer',
      ), array(
        'placeholder' => 'XXXX-XXXX-XXXX-XXXX',
    )));
    $this->setValidator('cc_number', new cqValidatorCreditCardNumber(
      array(),
      array(
        'required' => 'The card number field is required.',
        'invalid' => 'The card number is invalid.'
      )
    ));
  }

  protected function setupCardExpiryDateField()
  {
    $expDateYears = range(date('Y'), date('Y') + 10);
    $this->setWidget('expiry_date', new sfWidgetFormDate(array(
      'format'=> '%month%<div class="expiration-date-slash">/</div>%year%',
      'years' => array_combine($expDateYears, $expDateYears),
      'label' => 'Expiration Date',
    ), array(
    )));

    $this->setValidator('expiry_date', new cqValidatorExpiryDate(
      array(
        'min'                    => strtotime('previous month'),
        'date_format'            => '~(?P<month>\d{2})/(?P<year>\d{4})~',
        'date_format_range_error'=> 'm/Y'
      ),
      array(
        'required' => 'Both expiration date fields are required.',
        'invalid' => 'The expiration date is invalid.'
      )
    ));
  }

  protected function setupCardVerificationNumberField()
  {
    $this->setWidget('cvv_number', new sfWidgetFormInputText(
      array('label' => 'CVV/CSC Number'),
      array('maxlength' => 4)
    ));

    $this->setValidator('cvv_number', new sfValidatorNumber(
      array(
        'min'      => 1,
        'max'      => 9999,
        'required' => true,
      ),
      array(
        'required' => 'The CVV/CSC number field is required.',
        'invalid' => 'The CVV/CSC number is invalid.'
      )
    ));
  }

  protected function setupCardFirstNameField()
  {
    $this->setWidget('first_name', new sfWidgetFormInputText(array(
        'label' => 'First Name',
      ), array(
        'placeholder' => 'First name',
    )));
    $this->setValidator('first_name', new sfValidatorString(
      array(),
      array(
        'required' => 'The first name field is required.',
        'invalid' => 'The first name is invalid.'
      )
    ));
  }

  protected function setupCardLastNameField()
  {
    $this->setWidget('last_name', new sfWidgetFormInputText(array(), array(
      'placeholder' => 'Last name',
    )));
    $this->setValidator('last_name', new sfValidatorString(
      array(),
      array(
        'required' => 'The last name field is required.',
        'invalid' => 'The last name is invalid.'
      )
    ));
  }

  protected function setupCardStreetField()
  {
    $this->setWidget('street', new sfWidgetFormInputText(
      array(
        'label' => 'Address'
      )
    ));
    $this->setValidator('street', new sfValidatorString(array('required' => true)));
  }

  protected function setupCardCityField()
  {
    $this->setWidget('city', new sfWidgetFormInputText());
    $this->setValidator('city', new sfValidatorString(array('required' => true)));
  }

  protected function setupCardStateField()
  {
    $this->setWidget('state', new sfWidgetFormInputText(array(
      'label' => 'State / Province / Region',
    )));
    $this->setValidator('state', new sfValidatorString(array('required' => true)));
  }

  protected function setupCardZipField()
  {
    $this->setWidget('zip', new sfWidgetFormInputText(array(
      'label' => 'Zip / Postal code',
    )));
    $this->setValidator('zip', new sfValidatorString(array('required' => true)));
  }

  protected function setupTermsField()
  {
    $this->setWidget('terms', new sfWidgetFormInputCheckbox(array(), array(
      'required' => 'required',
    )));
    $this->setValidator('terms', new sfValidatorBoolean(
      array('required' => true),
      array('required' => 'You need to accept the terms and conditions.')
    ));

    $this->setWidget('fyi', new sfWidgetFormInputCheckbox(array(
        'label' => 'Paypal terms',
      ), array(
        'required' => 'required',
    )));
    $this->setValidator('fyi', new sfValidatorBoolean(
      array('required' => true),
      array('required' => 'You need to acknowledge the PayPal<sup>®</sup> account requirement.')
    ));
  }

  protected function getPaymentTypes()
  {
    //TODO: Replace with proper labeling
    return array(
      'paypal' => '/images/frontend/payment/paypal.gif',
      'cc'     => '/images/frontend/payment/cc.gif',
    );
  }

  protected function getCardTypes()
  {
    return array(
      'Visa'              => 'Visa',
      'MasterCard'        => 'MasterCard',
      'Discover'          => 'Discover',
      'Amex'              => 'American Express'
    );
  }

  /**
   * Return the package keys for validation; based on the same function used for
   * displaying the values
   *
   * @return    array
   */
  protected function getPackagesKeys()
  {
    return array_keys(PackagePeer::getAllPackageLabelsForSelectById());
  }

  public function applyPromoCode($validator, $values)
  {
    if (empty($values['promo_code']))
    {
      return $values;
    }

    $promo = PromotionPeer::findByPromotionCode($values['promo_code']);

    /** @var $collector Collector */
    $collector = cqContext::getInstance()->getUser()->getCollector();
    $error = false;

    if (!$promo)
    {
      $error = new sfValidatorError($validator, 'Sorry! That code is invalid.');
    }
    else if (0 == $promo->getNoOfTimeUsed())
    {
      $error = new sfValidatorError($validator, 'Sorry! That code has expired.');
    }
    else if (time() > $promo->getExpiryDate('U'))
    {
      $error = new sfValidatorError($validator, 'Sorry! That code has expired.');
    }
    else if (PromotionTransactionPeer::findOneByCollectorAndCode($this->getOption('collector', $collector), $values['promo_code']))
    {
      $error = new sfValidatorError($validator, 'Sorry! You’ve already used this code!');
    }

    if (!$error)
    {
      $this->promotion = $promo;
      if (isset($values['package_id']))
      {
        $packageId = $this->getValidator('package_id')->clean($values['package_id']);

        if ($promo && $this->getPackage($packageId))
        {
          $this->package->applyPromo($promo);
        }

        if ($this->package->getPackagePrice() <= $this->package->getDiscount())
        {
          $this->validatorSchema['payment_type']->setOption('required', false);
        }
      }

      $this->getWidget('package_id')->setOption(
        'choices', PackagePeer::getAllPackageLabelsForSelectById($promo)
      );
    }
    else
    {
      unset($values['promo_code']);

      throw new sfValidatorErrorSchema($validator, array('promo_code'=> $error));
    }

    return $values;
  }

  public function setPartialRequirements()
  {
    $fields = array(
      'package_id', 'payment_type', 'cc_type', 'cc_number', 'expiry_date', 'cvv_number',
      'first_name', 'last_name', 'street', 'city', 'state', 'zip', 'country', 'terms', 'fyi'
    );
    foreach ($fields as $field)
    {
      $this->getValidator($field)->setOption('required', false);
    }
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    // disable the credit card validators if payment type is not credit card
    // or the promotion makes the selected package free
    $no_payment_or_not_cc = !isset($taintedValues['payment_type']) ||
      'cc' != $taintedValues['payment_type'];
    if ($no_payment_or_not_cc || $this->isNoPaymentInfoRequired($taintedValues))
    {
      $fields = array(
        'cc_type', 'cc_number', 'expiry_date', 'cvv_number', 'first_name',
        'last_name', 'street', 'city', 'state', 'zip', 'country'
      );

      foreach ($fields as $field)
      {
        $this->getValidator($field)->setOption('required', false);
      }
      // specifically, we don't even need the payment_type field in this case
      if ($this->isNoPaymentInfoRequired($taintedValues))
      {
        $this->getValidator('payment_type') ->setOption('required', false);
      }
    }

    parent::bind($taintedValues, $taintedFiles);

    // automatically guess cc_type if payment is cc, but type is not selected
    if (
      $this->isValid() &&
      'cc' == $this->getValue('payment_type') &&
      !$this->getValue('cc_type')
    )
    {
      $this->values['cc_type'] = cqValidatorCreditCardNumber
        ::getCreditCardTypeFromNumber($this->getValue('cc_number'));
    }
  }

  /**
   * Check if payment information is required based on the selected package
   * and promotion; If the promotion covers 100% of the package price, then
   * no payment information is required
   *
   * @param     array $taintedValues
   * @return    boolean
   */
  protected function isNoPaymentInfoRequired($taintedValues)
  {
    // rudamentary promo code validation
    $promo_code = filter_var($taintedValues['promo_code'], FILTER_SANITIZE_STRING);
    $promotion = $this->getPromotion($promo_code);

    if ($promotion && isset($taintedValues['package_id']))
    {
      // rudamentary package id validation
      $package_id = filter_var($taintedValues['package_id'], FILTER_SANITIZE_NUMBER_INT);
      $package_id = in_array($package_id, $this->getPackagesKeys())
        ? $package_id
        : null;

      if (( $package = $this->getPackage($package_id) ))
      {
        if (0 == $package->getPriceWithDiscount($promotion))
        {
          return true;
        }
      }
    }

    return false;
  }

  protected function setupPendingTransactionConfirmationField()
  {
    $has_recent_processing_transactions = !! PackageTransactionQuery::create()
      ->filterByPaymentStatus(PackageTransactionPeer::PAYMENT_STATUS_PROCESSING)
      ->filterByCreatedAt(strtotime('-'.self::PENDING_TRANSACTION_TIMEOUT), Criteria::GREATER_EQUAL)
      ->count();

    if ($has_recent_processing_transactions)
    {
      $this->setWidget('pending_transaction_confirm', new sfWidgetFormInputCheckbox(array(
          'label' => 'I understand. Continue with this transaction',
        ), array(
          'required' => 'required',
      )));
      $this->setValidator('pending_transaction_confirm', new sfValidatorBoolean(
        array('required' => true),
        array('required' => 'You need to acknowledge you may be purchasing the same item a second time.')
      ));
    }
  }

  /**
   * @param     string|null  $promo_code
   * @return    Promotion|null
   */
  public function getPromotion($promo_code = null)
  {
    if (null === $this->promotion)
    {
      $promo_code = is_null($promo_code)
        ? $this->getValue('promo_code')
        : $promo_code;

      if (!$promo_code)
      {
        return null;
      }

      $this->promotion = PromotionQuery::create()
        ->findOneByPromotionCode($promo_code);
    }

    return $this->promotion;
  }

  /**
   * @param     int $package_id
   * @return    null|Package
   */
  public function getPackage($package_id = null)
  {
    if (null === $this->package)
    {
      $package_id = !is_null($package_id)
        ? $package_id
        : $this->getValue('package_id');

      if (!$package_id)
      {
        return null;
      }

      $this->package = PackagePeer::retrieveByPK($package_id);
    }

    return $this->package;
  }

}
