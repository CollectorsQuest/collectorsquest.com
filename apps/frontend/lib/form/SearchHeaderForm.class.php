<?php

class SearchHeaderForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'q' => new bsWidgetFormInputTypeAhead(array(
        'source' => cqContext::getInstance()->getController()->genUrl(
          array('sf_route' => 'ajax_search', 'section' => 'header', 'page' => 'typeahead')
        ),
        'autoselect' => false,
      )),
      'show' => new sfWidgetFormInputHidden(array(), array('value' => 'all'))
    ));

    $this->setValidators(array(
      'q' => new sfValidatorString(array('required' => true)),
      'show' => new sfValidatorPass()
    ));

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    $this->disableLocalCSRFProtection();
  }

}
