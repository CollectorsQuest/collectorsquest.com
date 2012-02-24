<?php

class CollectibleForSaleEditForm extends BaseCollectibleForSaleForm
{
  public function configure()
  {
    parent::configure();

    $this->useFields(array(
      'price',
      'condition',
      'is_shipping_free',
      'is_ready',
      'quantity',
    ));

    // Get the Collectibles for sale condictions
    $conditions = CollectibleForSalePeer::$conditions;
    $conditions[''] = '';

    $this->setWidget('condition', new sfWidgetFormChoice(array('choices' => $conditions)));
    $this->setValidator('condition', new sfValidatorChoice(array('choices' => array_keys($conditions), 'required' => false)));

    $this->setValidator('price', new sfValidatorString(array('required' => false)));
    $this->setValidator('quantity', new sfValidatorInteger(array('required' => false)));
    $this->setValidator('is_ready', new sfValidatorBoolean(array('required' => false)));

    // add a post validator
    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'checkPrice')))
    );

    $this->widgetSchema->setNameFormat('collectible_for_sale[%s]');
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    $this->validatorSchema['price']->setOption('required', !empty($taintedValues['is_ready']));

    parent::bind($taintedValues, $taintedFiles);
  }

  public function checkPrice($validator, $values)
  {
    if (!empty($values['is_ready']))
    {
      try
      {
        $price_validator = new cqValidatorPrice(array('required' => true));
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
