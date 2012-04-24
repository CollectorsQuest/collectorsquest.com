<?php
/**
 * Copyright 2012 Collectors' Quest, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

/**
 * Filename: SellerPackagesForm.class.php
 *
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 4/9/12
 * Id: $Id$
 */

class SellerPackagesForm extends sfForm
{

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

    $this->widgetSchema->setNameFormat('packages[%s]');
  }

  private function setupPackageIdField()
  {
    $this->setWidget('package_id', new sfWidgetFormChoice(array(
      'choices'          => PackagePeer::getAllPackagesForSelectGroupedByPlanType(),
      'expanded'         => true,
      'label'            => false,
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
    $this->setWidget('country', new sfWidgetFormI18nChoiceCountry(array(
      'choices'  => $this->getCountries(),
      'add_empty'=> true
    )));

    $this->setValidator('country', new sfValidatorI18nChoiceCountry(array(
      'choices'=> array_keys($this->getCountries()),
    )));
  }

  private function setupPromoCodeField()
  {
    $this->setWidget('promo_code', new sfWidgetFormInputText());
    $this->setValidator('promo_code', new sfValidatorCallback(array(
      'required'=> false,
      'callback'=> array($this, 'applyPromoCode')
    )));
  }

  private function setupPaymentTypeField()
  {
    $this->setWidget('payment_type', new sfWidgetFormChoice(array(
      'choices'           => $this->getPaymentTypes(),
      'renderer_class'    => 'cqWidgetFormSelectPayment',
      'renderer_options'  => array(
        'class'=> 'packages',
      ),
    )));

    $this->setValidator('payment_type', new sfValidatorChoice(array('choices'=> array_keys($this->getPaymentTypes()))));
  }

  private function setupCardTypeField()
  {
    $this->setWidget('cc_type', new sfWidgetFormChoice(array('choices'=> $this->getCardTypes())));
    $this->setValidator('cc_type', new sfValidatorChoice(array('choices'=> array_keys($this->getCardTypes()))));
  }

  private function setupCardNumberField()
  {
    $this->setWidget('cc_number', new cqWidgetFormCreditCard());
    $this->setValidator('cc_number', new sfValidatorString());
  }

  private function setupCardExpiryDateField()
  {
    $expDateYears = range(date('Y'), date('Y') + 10);
    $this->setWidget('expiry_date', new sfWidgetFormDate(array(
      'format'=> '%month% %year%',
      'years' => array_combine($expDateYears, $expDateYears),
      'label' => 'Expiration date',
    )));

    $this->setValidator('expiry_date', new cqValidatorExpiryDate(array(
      'min'                    => strtotime('previous month'),
      'date_format'            => '~(?P<month>\d{2})/(?P<year>\d{4})~',
      'date_format_range_error'=> 'm/Y'
    )));
  }

  private function setupCardVerificationNumberField()
  {
    $this->setWidget('cvv_number', new sfWidgetFormInputText(array(), array('maxlength'=> 3)));

    $this->setValidator('cvv_number', new sfValidatorNumber(array(
      'max'     => 999,
      'required'=> true
    )));
  }

  private function setupCardFirstNameField()
  {
    $this->setWidget('first_name', new sfWidgetFormInputText());
    $this->setValidator('first_name', new sfValidatorString());
  }

  private function setupCardLastNameField()
  {
    $this->setWidget('last_name', new sfWidgetFormInputText());
    $this->setValidator('last_name', new sfValidatorString());
  }

  private function setupCardStreetField()
  {
    $this->setWidget('street', new sfWidgetFormInputText());
    $this->setValidator('street', new sfValidatorString());
  }

  private function setupCardCityField()
  {
    $this->setWidget('city', new sfWidgetFormInputText());
    $this->setValidator('city', new sfValidatorString());
  }

  private function setupCardStateField()
  {
    $this->setWidget('state', new sfWidgetFormInputText());
    $this->setValidator('state', new sfValidatorString());
  }

  private function setupCardZipField()
  {
    $this->setWidget('zip', new sfWidgetFormInputText());
    $this->setValidator('zip', new sfValidatorString());
  }

  private function setupTermsField()
  {
    $this->setWidget('terms', new sfWidgetFormInputCheckbox());
    $this->setValidator('terms', new sfValidatorBoolean(array('required'=> true)));
  }

  private function getCountries()
  {
    $countries = sfCultureInfo::getInstance('en')->getCountries();

    // Dirty hack to display given countries at the top but only solution currently
    $top = array(
      ''   => '',
      'US' => $countries['US'],
      'GB' => $countries['GB'],
      'AU' => $countries['AU'],
    );

    foreach ($top as $key => $value)
    {
      unset($countries[$key]);
    }

    $countries = array_merge($top, $countries);

    return $countries;
  }

  protected function getPaymentTypes()
  {
    //TODO: Replace with proper labeling
    return array(
      'paypal'=> '/images/legacy/payment/paypal.gif',
      'cc'    => '/images/legacy/payment/cc.gif',
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

  public function applyPromoCode($validator, $value)
  {
    $promo = PromotionPeer::findByPromotionCode($value);

    if (!$promo)
    {
      throw new sfValidatorError($validator, 'Invalid promotion code!');
    }

    if (0 == $promo->getNoOfTimeUsed())
    {
      throw new sfValidatorError($validator, 'No of time Used of this promo code is over!');
    }

    if (time() > $promo->getExpiryDate('U'))
    {
      throw new sfValidatorError($validator, 'This Promotion code has been expired!');
    }

    $this->promotion = $promo;

    return $value;
  }

  public function setPartialRequirements()
  {
    $fields = array('payment_type', 'cc_type', 'cc_number', 'expiry_date', 'cvv_number', 'first_name', 'last_name', 'street', 'city', 'state', 'zip', 'country', 'terms');
    foreach ($fields as $field)
    {
      $this->getValidator($field)->setOption('required', false);
    }
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if (!empty($taintedValues['promo_code']))
    {
      $packageId = $this->getValidator('package_id')->clean($taintedValues['package_id']);
      $this->getValidator('promo_code')->clean($taintedValues['promo_code']);
      if ($this->promotion && $this->getPackage($packageId))
      {
        $this->package->applyPromo($this->promotion);
        if ($this->package->getPackagePrice() <= $this->package->getDiscount())
        {
          $this->getValidator('payment_type')->setOption('required', false);
        }
      }
    }
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
