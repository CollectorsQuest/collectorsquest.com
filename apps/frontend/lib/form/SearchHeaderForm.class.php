<?php

class SearchHeaderForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'q' => new bsWidgetFormInputTypeAhead(array(
        'source' => sfContext::getInstance()->getController()->genUrl(array('sf_route' => 'ajax_typeahead', 'section' => 'search', 'page' => 'header')),
        'autoselect' => false,
      ))
    ));

    $this->setValidators(array(
      'q' => new sfValidatorString(array('required' => true)),
    ));

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    $this->disableLocalCSRFProtection();
  }

}
