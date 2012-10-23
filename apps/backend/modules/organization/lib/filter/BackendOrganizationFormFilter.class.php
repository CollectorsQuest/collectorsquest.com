<?php

/**
 * A form filter for the backend Organization module
 */
class BackendOrganizationFormFilter extends OrganizationFormFilter
{

  public function configure()
  {
    parent::configure();

    $this->widgetSchema['phone']->setOption('with_empty', false);
    $this->widgetSchema['url']->setOption('with_empty', false);
    $this->widgetSchema['referral_code']->setOption('with_empty', false);

    $this->setupFounderIdField();
  }

  protected function setupFounderIdField()
  {
    $this->widgetSchema['founder_id'] = new BackendWidgetFormModelTypeAhead(array(
        'field' => CollectorPeer::DISPLAY_NAME,
        'submit_on_enter' => false,
    ));

    $this->validatorSchema['founder_id'] = new sfValidatorString(array(
        'required' => false,
    ));
  }


}

