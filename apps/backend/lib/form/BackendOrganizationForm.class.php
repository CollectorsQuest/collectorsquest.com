<?php

/**
 * An edit/create form for the backend Organization module
 */
class BackendOrganizationForm extends OrganizationForm
{
  public function configure()
  {
    parent::configure();

    $this->setupFounderIdField();
    $this->setupUrlField();

    $this->mergePostValidator(new BackendOrganizationValidatorSchema());
  }

  protected function setupFounderIdField()
  {
    $this->widgetSchema['founder_id'] = new BackendWidgetFormModelTypeAhead(array(
        'field' => CollectorPeer::DISPLAY_NAME,
        'submit_on_enter' => false, // enter on typeahead does not submit the form
    ));

    $this->validatorSchema['founder_id'] = new cqValidatorCollectorByName();
  }

  protected function setupUrlField()
  {
    $this->validatorSchema['url']->setOption('trim', true);
  }

  protected function unsetFields()
  {
    parent::unsetFields();

    unset($this['slug']);
    unset($this['updated_at']);
    unset($this['created_at']);
    unset($this['organization_membership_list']);
  }

}
