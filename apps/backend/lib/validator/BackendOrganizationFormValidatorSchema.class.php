<?php

/**
 * BackendOrganizationFormValidatorSchema
 */
class BackendOrganizationValidatorSchema extends sfValidatorSchema
{

  /**
   * Remove the fields parameter because it's unused in this context
   */
  public function __construct($options = array(), $messages = array())
  {
    parent::__construct(null, $options, $messages);
  }

  /**
   * Messages:
   *  - type_required
   */
  public function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addMessage(
      'type_required',
      'You must either select one of the pre-defined organization types
       or enter a custom type for this organization.'
    );
    $this->addMessage(
      'type_double',
      'You must enter only one of the two possilbe types
       (either a pre-defined or a custom one) and not both.'
    );
  }

  /**
   * @param     array $values
   * @return    array
   *
   * @throws    sfValidatorErrorSchema
   */
  protected function doClean($values)
  {
    // an error schema object to add errors to
    $local_error_schema = new sfValidatorErrorSchema($this);

    // either a predefined type or a custom type has to be set
    if (!$values['type']  && !$values['type_other'])
    {
      $local_error_schema->addError(new sfValidatorError($this, 'type_required'));
    }

    // either only one of predefined type or a custom type
    if ($values['type']  && $values['type_other'])
    {
      $local_error_schema->addError(new sfValidatorError($this, 'type_double'));
    }

    // throw the error schema if we have added errors to it
    if (count($local_error_schema))
    {
      throw $local_error_schema;
    }

    return $values;
  }

}
