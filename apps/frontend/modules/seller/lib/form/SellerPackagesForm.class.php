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

    $this->widgetSchema->setFormFormatterName('Bootstrap');
    $this->widgetSchema->setNameFormat('packages[%s]');
  }

  private function setupPackageIdField()
  {
    $this->setWidget('package_id', new sfWidgetFormSelectRadio(array(
      'choices'    => PackagePeer::getAllPackagesForSelectGroupedByPlanType(),
      'label'      => 'Package',
      'formatter'  => function($widget, $inputs)
      {
        $rows = array();
        foreach ($inputs as $input)
        {
          $rows[] = $widget->renderContentTag('label',
              $input['input'] . html_entity_decode($input['label']),
            array('class'=> 'radio')
          );
        }

        return !$rows ? '' : $widget->renderContentTag('div', implode($widget->getOption('separator'), $rows), array('class' => $widget->getOption('class')));
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

  private function setupCountryField()
  {
    $this->setWidget('country', new cqWidgetFormChoiceGeoIpCountry(array(
      'remote_address' => sfContext::getInstance()->getRequest()->getRemoteAddress(),
      'add_empty'=> true,
    ), array(
      'placeholder' => 'Country',
    )));

    $this->setValidator('country', new sfValidatorI18nChoiceCountry(array(
    )));
  }

  private function setupPromoCodeField()
  {
    $this->setWidget('promo_code', new sfWidgetFormInputText(array(
      'label'=> 'Promo code',
    ), array(
      'required' => 'required',
      'placeholder' => 'Enter Your Promo Code',
    )));
    $this->setValidator('promo_code', new sfValidatorString(
      array('required'=> false),
      array('required' => 'The promo code is required while we are in private beta!')
    ));
    $this->mergePreValidator(new sfValidatorCallback(array('callback'=> array($this, 'applyPromoCode'))));
  }

  private function setupPaymentTypeField()
  {
    $this->setWidget('payment_type', new sfWidgetFormChoice(array(
      'choices'           => $this->getPaymentTypes(),
      'renderer_class'    => 'cqWidgetFormSelectPayment',
      'renderer_options'  => array(
        'class'=> 'packages unstyled',
      ),
    )));

    $this->setValidator('payment_type', new sfValidatorChoice(array('choices'=> array_keys($this->getPaymentTypes()))));
  }

  private function setupCardTypeField()
  {
    $this->setWidget('cc_type', new sfWidgetFormChoice(array(
        'choices'=> array_merge(array('' => ''), $this->getCardTypes()),
        'label' => 'Card Type',
      ), array(
        'placeholder' => 'credit/debit card type',
    )));
    $this->setValidator('cc_type', new sfValidatorChoice(array('choices'=> array_keys($this->getCardTypes()))));
  }

  private function setupCardNumberField()
  {
    $this->setWidget('cc_number', new cqWidgetFormCreditCard(array(
        'label' => 'Card Numer',
      ), array(
        'placeholder' => 'Credit card number',
    )));
    $this->setValidator('cc_number', new cqValidatorCreditCardNumber());
  }

  private function setupCardExpiryDateField()
  {
    $expDateYears = range(date('Y'), date('Y') + 10);
    $this->setWidget('expiry_date', new sfWidgetFormDate(array(
      'format'=> '%month%<div class="expiration-date-slash">/</div>%year%',
      'years' => array_combine($expDateYears, $expDateYears),
      'label' => 'Expiration Date',
    ), array(
    )));

    $this->setValidator('expiry_date', new cqValidatorExpiryDate(array(
      'min'                    => strtotime('previous month'),
      'date_format'            => '~(?P<month>\d{2})/(?P<year>\d{4})~',
      'date_format_range_error'=> 'm/Y'
    )));
  }

  private function setupCardVerificationNumberField()
  {
    $this->setWidget('cvv_number', new sfWidgetFormInputText(array(
        'label' => 'CVV Number',
      ), array(
        'maxlength'   => 3,
        'placeholder' => 'CVV number',
    )));

    $this->setValidator('cvv_number', new sfValidatorNumber(array(
      'min'     => 100,
      'max'     => 999,
      'required'=> true,
    )));
  }

  private function setupCardFirstNameField()
  {
    $this->setWidget('first_name', new sfWidgetFormInputText(array(
        'label' => 'First Name',
      ), array(
        'placeholder' => 'First name',
    )));
    $this->setValidator('first_name', new sfValidatorString());
  }

  private function setupCardLastNameField()
  {
    $this->setWidget('last_name', new sfWidgetFormInputText(array(), array(
      'placeholder' => 'Last name',
    )));
    $this->setValidator('last_name', new sfValidatorString());
  }

  private function setupCardStreetField()
  {
    $this->setWidget('street', new sfWidgetFormInputText(array(), array(
      'placeholder' => 'Street',
    )));
    $this->setValidator('street', new sfValidatorString());
  }

  private function setupCardCityField()
  {
    $this->setWidget('city', new sfWidgetFormInputText(array(), array(
      'placeholder' => 'City',
    )));
    $this->setValidator('city', new sfValidatorString());
  }

  private function setupCardStateField()
  {
    $this->setWidget('state', new sfWidgetFormInputText(array(), array(
      'placeholder' => 'State',
    )));
    $this->setValidator('state', new sfValidatorString());
  }

  private function setupCardZipField()
  {
    $this->setWidget('zip', new sfWidgetFormInputText(array(), array(
      'placeholder' => 'Zip',
    )));
    $this->setValidator('zip', new sfValidatorString());
  }

  private function setupTermsField()
  {
    $this->setWidget('terms', new sfWidgetFormInputCheckbox(array(), array(
      'required' => 'required',
    )));
    $this->setValidator('terms', new sfValidatorBoolean(
      array('required' => true),
      array('required' => 'You need to accept the terms and conditions')
    ));

    $this->setWidget('fyi', new sfWidgetFormInputCheckbox(array(
        'label' => 'Paypal terms',
      ), array(
        'required' => 'required',
    )));
    $this->setValidator('fyi', new sfValidatorBoolean(
      array('required' => true),
      array('required' => 'You need to acknowledge the PayPal<sup>®</sup> account requirement')
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

  private function getPackagesKeys()
  {
    return PackageQuery::create()
        ->filterById(9999, Criteria::LESS_THAN)
        ->find()
        ->getPrimaryKeys(false);
  }

  public function applyPromoCode($validator, $values, $arguments)
  {
    if (IceGateKeeper::locked('mycq_seller_pay') && empty($values['promo_code']))
    {
      throw new sfValidatorErrorSchema($validator, array(
        'promo_code'=> new sfValidatorError($validator,
          'A promo code is required while we are in private beta!<br/>
           Please email info@collectorsquest.com for more information.'
        ))
      );
    }

    if (empty($values['promo_code']))
    {
      return $values;
    }

    $promo = PromotionPeer::findByPromotionCode($values['promo_code']);
    $collector = sfContext::getInstance()->getUser()->getCollector();
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
    else if ($used = PromotionTransactionPeer::findOneByCollectorAndCode($this->getOption('collector', $collector), $values['promo_code']))
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
        else if (IceGateKeeper::locked('mycq_seller_pay'))
        {
          throw new sfValidatorErrorSchema($validator, array('promo_code' => new sfValidatorError($validator, 'This promo code cannot be used in beta testing mode!')));
        }
      }

      $this->getWidget('package_id')->setOption('choices', PackagePeer::getAllPackagesForSelectGroupedByPlanType($promo));
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
    $fields = array('package_id', 'payment_type', 'cc_type', 'cc_number', 'expiry_date', 'cvv_number', 'first_name', 'last_name', 'street', 'city', 'state', 'zip', 'country', 'terms');
    foreach ($fields as $field)
    {
      $this->getValidator($field)->setOption('required', false);
    }
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if (!isset($taintedValues['payment_type']) || 'cc' != $taintedValues['payment_type'])
    {
      $fields = array('cc_type', 'cc_number', 'expiry_date', 'cvv_number', 'first_name', 'last_name', 'street', 'city', 'state', 'zip', 'country');
      foreach ($fields as $field)
      {
        $this->getValidator($field)->setOption('required', false);
      }
    }

    return parent::bind($taintedValues, $taintedFiles);
  }

  protected function setupPendingTransactionConfirmationField()
  {
    $has_recent_pending_transactions = !! PackageTransactionQuery::create()
      ->filterByPaymentStatus(PackageTransactionPeer::PAYMENT_STATUS_PENDING)
      ->filterByCreatedAt(strtotime('-'.self::PENDING_TRANSACTION_TIMEOUT), Criteria::GREATER_EQUAL)
      ->count();

    if ($has_recent_pending_transactions)
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
   * @return Promotion|null
   */
  public function getPromotion()
  {
    if (null !== $this->promotion)
    {
      return $this->promotion;
    }

    if (!$this->getValue('promo_code'))
    {
      return null;
    }

    $promo = PromotionQuery::create()->findOneByPromotionCode($this->getValue('promo_code'));

    if ($promo)
    {
      $this->promotion = $promo;
    }

    return $this->promotion;
  }

  public function getPackage($packageId = null)
  {
    if (null !== $this->package)
    {
      return $this->package;
    }

    $packageId = !is_null($packageId) ? $packageId : $this->getValue('package_id');

    if (!$packageId)
    {
      return null;
    }

    if ($package = PackagePeer::retrieveByPK($packageId))
    {
      $this->package = $package;
    }

    return $package;
  }

}
