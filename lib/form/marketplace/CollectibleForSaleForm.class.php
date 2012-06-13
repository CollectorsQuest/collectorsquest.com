<?php

class CollectibleForSaleForm extends BaseCollectibleForSaleForm
{
  public function configure()
  {
    $this->setValidator('is_ready', new sfValidatorBoolean(array('required' => false)));
    $this->getValidator('is_ready')->setDefaultMessage(
      'invalid', 'You do not have enough credits to post this Collectible to the marketplace!'
    );
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

  public function updatePriceColumn($v)
  {
    $this->getObject()->setPrice($v);
  }

  public function setupPriceField()
  {
    $this->setWidget('price', new sfWidgetFormInputText(array(), array('required' => 'required')));
    $this->setValidator('price', new sfValidatorString(array('required' => false)));
    $this->setDefault('price', $this->getObject()->getPrice());

    // Get the Collectibles for sale currencies
    $currencies = CollectibleForSalePeer::$currencies;
    $this->setWidget('price_currency', new sfWidgetFormChoice(
      array('choices' => $currencies, 'default' => 'USD')
    ));
    $this->setValidator('price_currency', new sfValidatorChoice(
      array('choices' => array_keys($currencies), 'required' => false)
    ));
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
      }
      else
      {
        // throw an error bound to the price field
        throw new sfValidatorErrorSchema(
          $validator,
          array('is_ready' => new sfValidatorError($validator, 'invalid'))
        );
      }
    }

    return $values;
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

    if (null === $values['price_currency'])
    {
      $values['price_currency'] = 'USD';
    }

    // price is valid or not required, return the clean values
    return $values;
  }
}
