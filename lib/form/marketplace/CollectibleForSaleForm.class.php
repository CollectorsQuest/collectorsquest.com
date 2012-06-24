<?php

class CollectibleForSaleForm extends BaseCollectibleForSaleForm
{
  public function configure()
  {

  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    $this->validatorSchema['price']->setOption('required', !empty($taintedValues['is_ready']));

    parent::bind($taintedValues, $taintedFiles);
  }

  protected function setupConditionField()
  {
    // Get the Collectibles for sale condictions
    $conditions = CollectibleForSalePeer::$conditions;
    $conditions[''] = '';

    $this->setWidget('condition', new sfWidgetFormChoice(array('choices' => $conditions)));
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
      $seller = sfContext::getInstance()->getUser()->getSeller();

      if ($seller && $seller->hasPackageCredits())
      {
        $values = $this->validatePriceField($validator, $values);
        $values = $this->validateConditionsdsdsField($validator, $values);
      }
      else
      {
        // throw an error bound to the price field
        $errorSchema = new sfValidatorErrorSchema($validator);
        $errorSchema->addError(new sfValidatorError($validator, 'invalid'), 'is_ready');

        throw $errorSchema;
      }
    }

    return $values;
  }

  public function setupPriceField()
  {
    $this->setWidget('price', new sfWidgetFormInputText(array(), array('required' => 'required')));
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

  public function validatePriceField($validator, $values)
  {
    if (!empty($values['is_ready']))
    {
      try
      {
        $price_validator = new cqValidatorPrice(array('required' => true, 'min' => 1));
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
