<?php

class CollectibleForSaleForm extends BaseCollectibleForSaleForm
{

  protected function setupConditionField()
  {
    // Get the Collectibles for sale condictions
    $conditions = CollectibleForSalePeer::$conditions;
    $conditions[''] = '';

    $this->setWidget('condition', new sfWidgetFormChoice(array(
        'choices' => $conditions
      ), array(
        'required' => 'required',
    )));
    $this->setValidator('condition', new sfValidatorChoice(
      array('choices' => array_keys($conditions), 'required' => false)
    ));
  }

  public function setupIsReadyField()
  {
    $this->setValidator('is_ready', new sfValidatorBoolean(array('required' => false)));
  }

  public function validateIsReadyField($validator, $values)
  {
    if (!empty($values['is_ready']))
    {
      /** @var $seller Seller */
      $seller = cqContext::getInstance()->getUser()->getSeller();

      $collectible_has_credit = (boolean) PackageTransactionCreditQuery::create()
        ->filterByCollectibleId($this->getObject()->getCollectibleId())
        ->notExpired()
        ->count();
      if ($seller && ($seller->hasPackageCredits() || $collectible_has_credit))
      {
        if ($seller->hasPaypalDetails())
        {
          $values = $this->validatePriceField($validator, $values);
          $values = $this->validateConditionsdsdsField($validator, $values);
        }
      }
      else
      {
        // throw a global error
        $errorSchema = new sfValidatorErrorSchema($validator);
        $errorSchema->addError(new sfValidatorError($validator, 'invalid'));

        throw $errorSchema;
      }
    }

    return $values;
  }

  public function setupPriceField()
  {
    $this->widgetSchema['price'] = new sfWidgetFormInputText(array(), array('required' => 'required'));
    $this->setValidator('price', new sfValidatorString(array('required' => false)));
    $this->setDefault('price', sprintf('%01.2f', $this->getObject()->getPrice()));

    // Get the Collectibles for sale currencies
    $currencies = CollectibleForSalePeer::$currencies;
    $this->setWidget('price_currency', new sfWidgetFormChoice(
      array('choices' => $currencies, 'default' => 'USD')
    ));
    $this->setValidator('price_currency', new sfValidatorChoice(
      array('choices' => array_keys($currencies), 'required' => false)
    ));
  }

  public function updatePriceColumn($v)
  {
    $this->getObject()->setPrice($v);
  }

  public function setupTaxFields()
  {
    $c = new Criteria();
    // Restrict to "United States" only
    $c->add(iceModelGeoCountryPeer::ID, 226);

    $this->widgetSchema['tax_country']= new sfWidgetFormPropelChoice(array(
      'model' => 'iceModelGeoCountry', 'add_empty' => true, 'key_method' => 'getIso3166',
      'criteria' => $c
    ));
    $this->validatorSchema['tax_country'] = new sfValidatorPropelChoice(array(
      'model' => 'iceModelGeoCountry', 'column' => 'iso3166', 'required' => false
    ));

    $this->widgetSchema['tax_state'] = new sfWidgetFormPropelChoice(array('model' => 'iceModelGeoRegion'));
    $this->widgetSchema['tax'] = new sfWidgetFormInputText(array(), array('required' => 'false'));
    $this->validatorSchema['tax'] = new cqValidatorPrice(
      array('required' => false, 'max' => 50), array('max' => 'You cannot set Tax more than 50%',
        'invalid' => 'The tax percentage you have specified is not valid')
    );
    $this->setDefault('tax', sprintf('%01.2f', $this->getObject()->getTaxPercentage()));
  }

  public function updateTaxColumn($v)
  {
    $this->getObject()->setTaxPercentage($v);
  }

  public function validatePriceField($validator, $values)
  {
    if (!empty($values['is_ready']))
    {
      try
      {
        $price_validator = new cqValidatorPrice(array('required' => true, 'min' => 0.01));
        $values['price'] = $price_validator->clean($values['price']);
      }
      catch (sfValidatorError $error)
      {
        // throw an error bound to the price field
        throw new sfValidatorErrorSchema($validator, array('price' => $error));
      }
    }

    if (empty($values['price_currency']))
    {
      $values['price_currency'] = 'USD';
    }

    // price is valid or not required, return the clean values
    return $values;
  }

  public function validateConditionsdsdsField($validator, $values)
  {
    // Get the Collectibles for sale condictions
    $conditions = CollectibleForSalePeer::$conditions;
    $conditions[''] = '';

    try
    {
      $condition_validator = new sfValidatorChoice(
        array('choices' => array_keys($conditions), 'required' => true)
      );
      $values['condition'] = $condition_validator->clean($values['condition']);
    }
    catch (sfValidatorError $error)
    {
      // throw an error bound to the price field
      throw new sfValidatorErrorSchema($validator, array('condition' => $error));
    }

    return $values;
  }
}
