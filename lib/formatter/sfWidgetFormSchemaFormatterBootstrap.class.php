<?php

/**
 * sfWidgetFormSchemaFormatterBootstrap basic twitter bootstrap formatter
 */
class sfWidgetFormSchemaFormatterBootstrap extends sfWidgetFormSchemaFormatter
{
  protected $rowFormat                   = '<div class="control-group %error_class%">
                                              %label%
                                              <div class="controls">
                                                %field%
                                                %help%
                                                %errors%
                                                %hidden_fields%
                                              </div>
                                            </div>';

  protected $helpFormat                  = '<p class="help-block">%help%</p>';
  protected $errorRowFormatForField      = '<li>%error%</li>';
  protected $namedErrorRowFormatForField = '<li>%name%: %error%</li>';
  protected $errorRowFormatForRowAll      = '<li class="">%error%</li>';
  protected $namedErrorRowFormatForRowAll = '<li class="">%name%: %error%</li>';

  protected $errorListFormatInARow       = '<ul class="unstyled alert alert-error">%errors%  </ul>';
  protected $errorListFormatForField     = '<ul class="unstyled alert alert-error">%errors%  </ul>';
  protected $errorListFormatForRowAll    = '<div class="alert alert-error all-errors">
                                              <a class="close" data-dismiss="alert">×</a>
                                              <h4 class="alert-heading">%errors_header%</h4>
                                              <ul class="unstyled">
                                              %errors%
                                              </ul>
                                            </div>';

  protected $fieldRequiredFormat         = '<div class="with-required-token">
                                              <span class="required-token">
                                                %required_token%
                                              </span>
                                              %field%
                                            </div>';


  public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null)
  {
    // we want to hide null fields completely, so we skip all renrering, including
    // labels (but we still need to output hidden fields)
    if (cqWidgetFormNullInput::NULL_RENDER === $field)
    {
      return "\n" . cqWidgetFormNullInput::NULL_RENDER . "\n"
        . (null === $hiddenFields ? '%hidden_fields%' : $hiddenFields);
    }

    return strtr($this->getRowFormat(), array(
        '%label%'         => $label,
        '%field%'         => $this->formatRequiredField($field),
                             // show help only when there are no errors
        '%help%'          => 0 == count($errors) ? $this->formatHelp($help) : '',
        '%errors%'        => $this->formatErrorsForField($errors, preg_replace('/:$/', '', strip_tags($label))),
        '%error_class%'   => count($errors) ? 'error' : '',
        '%hidden_fields%' => null === $hiddenFields ? '%hidden_fields%' : $hiddenFields,
    ));
  }

  /**
   * Displays all errors for a given form, both field and global
   *
   * @param     sfValidatorErrorSchema|null $errors
   * @param     string $errors_header The header message for the error block
   */
  public function formatAllErrorsForRow($errors, $errors_header = null)
  {
    if (null === $errors || !$errors || !count($errors))
    {
      return '';
    }

    if (!is_array($errors))
    {
      $errors = array($errors);
    }

    return strtr($this->errorListFormatForRowAll, array(
        '%errors%' => implode('', $this->unnestErrorsForRowAll($errors)),
        '%errors_header%' => null !== $errors_header
          ? $errors_header
          : 'There were some problems with your data:',
    ));
  }

  /**
   * used by formatAllErrorsForRow()
   *
   * Will try to use field labels for error names if possible
   *
   * @param     sfValidatorErrorSchema $errors
   * @param     string $prefix
   *
   * @return    array of strings
   */
  protected function unnestErrorsForRowAll($errors, $prefix = '')
  {
    $newErrors = array();

    foreach ($errors as $name => $error)
    {
      if ($error instanceof ArrayAccess || is_array($error))
      {
        $name = trim($this->generateLabelName($name), '?:');
        $newErrors = array_merge($newErrors, $this->unnestErrorsForRowAll($error, ($prefix ? $prefix.' > ' : '').$name));
      }
      else
      {
        if ($error instanceof sfValidatorError)
        {
          $err = $this->translate($error->getMessageFormat(), $error->getArguments());
        }
        else
        {
          $err = $this->translate($error);
        }

        if (!is_integer($name))
        {
          $name = trim($this->generateLabelName($name), '?:');

          $newErrors[] = $this->formatNamedErrorRowForRowAll($err, $prefix, $name);
        }
        else
        {
          $newErrors[] = strtr($this->errorRowFormatForRowAll, array('%error%' => $err));
        }
      }
    }

    return $newErrors;
  }

  /**
   * Special formatting for all-errors blog fields with a single error, that is either
   * "required" or "invalid"
   *
   * @param     string $error
   * @param     string $prefix
   * @param     string $name
   *
   * @return    string
   */
  protected function formatNamedErrorRowForRowAll($error, $prefix, $name)
  {
    $error = strtr($this->namedErrorRowFormatForRowAll, array('%error%' => $error, '%name%' => ($prefix ? $prefix.' > ' : '').$name));

    // see sfValidatorBase::$globalDefaultMessages
    // In case we have one of the two default error messages, we write out
    // the error in the format "The FIELD_NAME field is ERROR_MESSAGE."
    $error = preg_replace(
      '/([\w\s]+): ((?:Invalid.|Required.))/ue',
      '"$1: The " . strtolower("$1") . " field is " . strtolower("$2")',
      $error
    );

    return $error;
  }

  /**
   * Generates the label name for the given field name.
   *
   * It seems that at some point sfWidgetFormSchema's interface changed, and
   * exception started being thrown. The original sfWidgetFormSchemaFormatter
   * does not account for that, so we do it manually here
   *
   * @param  string $name  The field name
   *
   * @return string The label name
   */
  public function generateLabelName($name)
  {
    // handle change in getLabel() intrface:
    // exception being thrown for non-existent labels
    try {
      $label = $this->widgetSchema->getLabel($name);
    } catch (InvalidArgumentException $e) {
      $label = '';
    }

    if (!$label && false !== $label)
    {
      $label = str_replace('_', ' ', ucfirst('_id' == substr($name, -3) ? substr($name, 0, -3) : $name));
    }

    return $this->translate($label);
  }

  /**
   * If the field is required, reformat it with a required token,
   * a * in front
   *
   * @param     string $field
   * @return    string
   */
  public function formatRequiredField($field)
  {
    if (false !== strpos($field, 'required='))
    {
      return strtr($this->fieldRequiredFormat, array(
          '%required_token%' => '*',
          '%field%' => $field,
      ));
    }

    return $field;
  }

  /**
   * Format erros to be displayed alongside the field
   *
   * @param     sfValidatorErrorSchema $errors
   * @param     string $label
   *
   * @return    string
   */
  public function formatErrorsForField($errors, $label = '')
  {
    if (null === $errors || !$errors)
    {
      return '';
    }

    if (!is_array($errors))
    {
      $errors = array($errors);
    }

    return strtr($this->errorListFormatForField, array('%errors%' => implode('', $this->unnestErrorsForField($errors, '', $label))));
  }

  /**
   * used by formatErrorsForField()
   *
   * @param     sfValidatorErrorSchema $errors
   * @param     string $prefix
   * @param     string $label
   *
   * @return    array of strings
   */
  protected function unnestErrorsForField($errors, $prefix = '', $label = '')
  {
    $newErrors = array();

    foreach ($errors as $name => $error)
    {
      if ($error instanceof ArrayAccess || is_array($error))
      {
        $newErrors = array_merge($newErrors, $this->unnestErrorsForField($error, ($prefix ? $prefix.' > ' : '').$name, $label));
      }
      else
      {
        if ($error instanceof sfValidatorError)
        {
          $err = $this->translate($error->getMessageFormat(), $error->getArguments());
        }
        else
        {
          $err = $this->translate($error);
        }

        if (!is_integer($name))
        {
          $newErrors[] = strtr($this->namedErrorRowFormatForField, array('%error%' => $err, '%name%' => ($prefix ? $prefix.' > ' : '').$name));
        }
        else
        {
          $newErrors[] = $this->formatErrorRowFormatForField($err, $label);
        }
      }
    }

    return $newErrors;
  }

  /**
   * Special formatting for non-named field errors
   *
   * @param     string $error
   * @param     string $label
   * @return    string
   */
  protected function formatErrorRowFormatForField($error, $label = '')
  {
    if ('' != trim($label))
    {
      // see sfValidatorBase::$globalDefaultMessages
      // In case we have one of the two default error messages, we write out
      // the error in the format "The FIELD_NAME field is ERROR_MESSAGE."
      $error = preg_replace(
        '/(?:Invalid.|Required.)/ue',
        '"The " . strtolower($label) . " field is " . strtolower("$0")',
        $error
      );
    }

    return strtr($this->errorRowFormatForField, array('%error%' => $error));
  }


  /**
   * @param     string $name
   * @param     array $attributes
   *
   * @return    string
   */
  public function generateLabel($name, $attributes = array())
  {
    $attributes['class'] = (isset($attributes['class']) ? $attributes['class'] : '') . ' control-label';

    return parent::generateLabel($name, $attributes);
  }

}
