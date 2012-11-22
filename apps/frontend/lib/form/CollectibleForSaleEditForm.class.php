<?php

class CollectibleForSaleEditForm extends CollectibleForSaleForm
{
  public function configure()
  {
    parent::configure();

    $this->setupPriceField();
    $this->setupTaxFields();
    $this->setupConditionField();

    // add a post validator
    $this->mergePostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'validateIsReadyField')
      ), array(
        'invalid' => "Please purchase a seller package if you'd like to sell this item.",
    )));

    // validate the collector has setup his paypal info
    // before allowing him/her to add a collectible for sale
    $this->mergePostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'validatePaypalDetailsSet'),
      ), array(
        'invalid'  => sprintf(
          'You must <a href="%s">setup your store settings</a>
           before you can sell in the Market',
          cqContext::getInstance()->getController()->genUrl('@mycq_marketplace_settings')),
      )
    ));

    $this->useFields(array(
      'is_ready',
      'price',
      'condition',
      'tax_country',
      'tax_state',
      'tax'
    ));

    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');
    $this->getWidgetSchema()->setNameFormat('collectible_for_sale[%s]');
  }

  /**
   * If we are trying to add the collectible for sale (is_ready) check
   * first if the collector has set his paypal details before proceeding
   */
  public function validatePaypalDetailsSet($validator, $values)
  {
    if (isset($values['is_ready']) && $values['is_ready'])
    {
      $collector = cqContext::getInstance()->getUser()->getCollector();

      if (!$collector->hasPaypalDetails())
      {
        $errorSchema = new sfValidatorErrorSchema($validator);
        $errorSchema->addError(new sfValidatorError($validator, 'invalid'));

        throw $errorSchema;
      }
    }

    return $values;
  }

}
