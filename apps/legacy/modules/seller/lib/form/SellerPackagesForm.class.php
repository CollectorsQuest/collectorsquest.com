<?php
/**
 * File: SellerPackagesForm.class.php
 *
 * @author zecho
 * @version $Id$
 *
 */

class SellerPackagesForm extends sfForm
{

  public function configure()
  {
    $expDateYears = range(date('Y'), date('Y') + 10);

    $this->setWidgets(array(

      'package_id'    => new sfWidgetFormChoice(array(
        'choices'          => PackagePeer::getAllPackagesForSelectGroupedByPlanType(),
        'expanded'         => true,
        'label'            => false,
        'renderer_options' => array(
          'class'   => 'packages',
          'template'=> '<h5>%group%</h5>%options%',
        ),
      )),
      'country'       => new sfWidgetFormI18nChoiceCountry(array(
        'choices'  => $this->getCountries(),
        'add_empty'=> true
      )),
      'promo_code'    => new sfWidgetFormInputText(),
      'payment_type'  => new sfWidgetFormChoice(array(
        'choices'           => $this->getPaymentTypes(),
        'renderer_class'    => 'cqWidgetFormSelectPayment',
        'renderer_options'  => array(
          'class'=> 'packages',
        ),
      )),
      'card_type'     => new sfWidgetFormChoice(array('choices'=> $this->getCardTypes())),
      'cc_number'     => new sfWidgetFormInputText(),
      'expiry_date'   => new sfWidgetFormDate(array(
        'format'=> '%month% %year%',
        'years' => array_combine($expDateYears, $expDateYears),
        'label' => 'Expiration date',
      )),
      'cvv_number'    => new sfWidgetFormInputText(array(), array('maxlength'=> 3)),
      'first_name'    => new sfWidgetFormInputText(),
      'last_name'     => new sfWidgetFormInputText(),
      'street'        => new sfWidgetFormInputText(),
      'city'          => new sfWidgetFormInputText(),
      'state'         => new sfWidgetFormInputText(),
      'zip'           => new sfWidgetFormInputText(),
      'term_condition'=> new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'package_id'    => new sfValidatorChoice(array(
        'choices'=> $this->getPackagesKeys(),
      ), array(
        'required'=> 'No package selected!',
        'invalid' => 'Invalid package'
      )),
      'country'       => new sfValidatorI18nChoiceCountry(array(
        'choices'=> array_keys($this->getCountries()),
      )),
      'promo_code'    => new sfValidatorCallback(array('callback'=> array($this, 'applyPromoCode'))),
      'payment_type'  => new sfValidatorChoice(array('choices'=> array_keys($this->getPaymentTypes()))),
      'card_type'     => new sfValidatorChoice(array('choices'=> array_keys($this->getCardTypes()))),
      'cc_number'     => new sfValidatorString(),
      'expiry_date'   => new cqValidatorExpiryDate(array(
        'min'                    => strtotime('previous month'),
        'date_format'            => '~(?P<month>\d{2})/(?P<year>\d{4})~',
        'date_format_range_error'=> 'm/Y'
      )),
      'cvv_number'    => new sfValidatorNumber(array(
        'max'     => 999,
        'required'=> true
      )),
      'first_name'    => new sfValidatorString(),
      'last_name'     => new sfValidatorString(),
      'street'        => new sfValidatorString(),
      'city'          => new sfValidatorString(),
      'state'         => new sfValidatorString(),
      'zip'           => new sfValidatorString(),
      'term_condition'=> new sfValidatorBoolean(array('required'=> true)),
    ));

    $this->widgetSchema->setNameFormat('packages[%s]');
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

  private function getPackagesKeys()
  {
    return PackageQuery::create()
        ->filterById(9999, Criteria::LESS_THAN)
        ->find()
        ->getPrimaryKeys(false);
  }

  public function getPaymentTypes()
  {
    //TODO: Replace with proper labeling
    return array(
      'paypal'=> '/images/legacy/payment/paypal.gif',
      'cc'    => '/images/legacy/payment/cc.gif',
    );
  }

  public function getCardTypes()
  {
    return array(
      'visa'              => 'Visa',
      'master_card'       => 'MasterCard',
      'discover'          => 'Discover',
      'american_express'  => 'American Express'
    );
  }
}
