<?php

/**
 * ShippingRateCollectibleFormForEmbedding
 */
class ShippingRateCollectibleFormForEmbedding extends ShippingRateCollectibleForm
{

  public function configure()
  {
    parent::configure();

    $this->widgetSchema['collectible_id'] = new sfWidgetFormInputHidden();

    unset ($this['country_iso3166']);
    unset ($this['calculation_type']);

    $this->setupVisibleFieldsBasedOnCalculationType();

    // we unset the shippingRatePriceRangeValidatorSchema and
    // shippingRateAmountInCentsOrPercentValidatorSchema validators,
    // because they need to be added in a special way by the parent form,
    // in order to work in combination with cqCopyFieldsToEmbeddedFormValidatorSchema
    $this->validatorSchema->setPostValidator(new sfValidatorPass());

    $this->removeRequiredValidators();

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);
  }

  public function getNameForEmbedding()
  {
    return 'shipping_rate_collectible_' . $this->getObject()->getId();
  }

  protected function removeRequiredValidators()
  {
    foreach (array_keys($this->validatorSchema->getFields()) as $validator_name)
    {
      $this->validatorSchema[$validator_name]->setOption('required', false);
    }
  }

  protected function setupVisibleFieldsBasedOnCalculationType()
  {
    switch($this->getCurrentCalculationType()):
      case (ShippingRatePeer::CALCULATION_TYPE_FLAT_RATE):
        $this->useFields(array(
            'amount_in_cents',
            'amount_in_percent',
        ));
        break;

      case (ShippingRatePeer::CALCULATION_TYPE_FREE_SHIPPING):
      case (ShippingRatePeer::CALCULATION_TYPE_LOCAL_PICKUP):
      case (ShippingRatePeer::CALCULATION_TYPE_NO_SHIPPING):
        $this->useFields(array()); // hide all fields
        break;

      case (ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE):
        // show all fields
        break;

      default:
        // show all fields
        break;

    endswitch;
  }

  protected function getCurrentCalculationType()
  {
    return $this->getOption('current_calculation_type',
      $this->getObject()->getCalculationType());
  }

}
