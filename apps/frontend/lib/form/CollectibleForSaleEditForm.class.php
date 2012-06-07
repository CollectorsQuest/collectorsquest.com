<?php

class CollectibleForSaleEditForm extends BaseCollectibleForSaleForm
{
  public function configure()
  {
    parent::configure();

    $this->useFields(array(
      'is_ready',
      'price_currency',
      'condition',
      'quantity',
    ));

    // Get the Collectibles for sale condictions
    $conditions = CollectibleForSalePeer::$conditions;
    $conditions[''] = '';

    $this->setWidget('condition', new sfWidgetFormChoice(array('choices' => $conditions)));
    $this->setValidator('condition', new sfValidatorChoice(
        array('choices' => array_keys($conditions), 'required' => false)
    ));

    $this->setWidget('price', new sfWidgetFormInputText(array(), array('required' => 'required')));
    $this->setValidator('price', new sfValidatorString(array('required' => false)));
    $this->setDefault('price', $this->getObject()->getPrice());

    // Get the Collectibles for sale currencies
    $currencies = CollectibleForSalePeer::$currencies;
    $this->setWidget('price_currency', new sfWidgetFormChoice(array('choices' => $currencies)));
    $this->setValidator('price_currency', new sfValidatorChoice(
        array('choices' => array_keys($currencies), 'required' => false)
    ));

    $this->setValidator('is_ready', new sfValidatorBoolean(array('required' => false)));
    $this->setValidator('quantity', new sfValidatorInteger(array('required' => false)));

    // add a post validator
    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'checkPrice')))
    );

    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');
    $this->getWidgetSchema()->setNameFormat('collectible_for_sale[%s]');
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    $this->validatorSchema['price']->setOption('required', !empty($taintedValues['is_ready']));

    parent::bind($taintedValues, $taintedFiles);
  }

  public function updatePriceColumn($v)
  {
    $this->getObject()->setPrice($v);
  }

  public function checkPrice($validator, $values)
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

    // password is correct, return the clean values
    return $values;
  }

}
