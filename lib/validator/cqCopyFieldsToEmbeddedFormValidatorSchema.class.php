<?php

/**
 * Description of cqCopyFieldsToEmbeddedFormValidatorSchema
 */
class cqCopyFieldsToEmbeddedFormValidatorSchema extends sfValidatorSchema
{

  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('fields_to_copy');
    $this->addRequiredOption('embedded_form_names');
    $this->addOption('remove_on_copy', false);
  }

  protected function doClean($values)
  {
    // foreach embedded form
    foreach ((array) $this->getOption('embedded_form_names') as $embedded_form)
    {
      // if the embedded form actually has values
      if (is_array($values[$embedded_form]))
      {
        // copy the fields to the embedded form's values
        foreach ((array) $this->getOption('fields_to_copy') as $field_to_copy)
        {
          $values[$embedded_form][$field_to_copy] = $values[$field_to_copy];
        }
      }
    }

    // if the remove on copy option is set
    if ($this->getOption('remove_on_copy'))
    {
      // remove each field for copying from the main form's values
      // note that they are already in the embedded form's values
      foreach ((array) $this->getOption('fields_to_copy') as $field_to_copy)
      {
        unset($values[$field_to_copy]);
      }
    }

    return $values;
  }

}
