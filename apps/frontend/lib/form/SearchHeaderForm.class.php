<?php

class SearchHeaderForm extends sfForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'q' => new bsWidgetFormInputTypeAhead(array(
        'source' => sfContext::getInstance()->getController()->genUrl(array('sf_route' => 'ajax_typeahead', 'section' => 'search', 'page' => 'header')),
      ))
    ));

    $this->setValidators(array(
      'q' => new sfValidatorString(array('required' => true)),
    ));

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }
}
