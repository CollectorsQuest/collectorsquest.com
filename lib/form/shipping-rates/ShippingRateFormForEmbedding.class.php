<?php

class ShippingRateFormForEmbeddig extends ShippingRateForm
{

  public function configure()
  {
    parent::configure();

    if ($this->getOption('allow_empty'))
    {
      $this->validatorSchema['shipping_carrier_service_id']
        ->setOption('required', false);
    }

    if (!$this->getOption('is_new'))
    {
      $this->setupDeleteField();
    }

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);
  }

  protected function setupDeleteField()
  {
    $this->widgetSchema['_delete'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['_delete'] = new sfValidatorPass();

    $this->setDeleteField('_delete');
  }

  protected function unsetFields()
  {
    parent::unsetFields();

    unset ($this['id']);
    unset ($this['shipping_reference_id']);
  }

}