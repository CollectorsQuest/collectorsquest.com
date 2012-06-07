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
  protected $errorRowFormatForField      = '<li class="help-block">%error%</li>';
  protected $namedErrorRowFormatForField = '<li class="help-block">%name%: %error%</li>';
  protected $errorRowFormatForRowAll      = '<li class="">%error%</li>';
  protected $namedErrorRowFormatForRowAll = '<li class="">%name%: %error%</li>';

  protected $errorListFormatInARow       = '<ul class="unstyled alert alert-error">%errors%  </ul>';
  protected $errorListFormatForField     = '<ul class="unstyled">%errors%  </ul>';
  protected $errorListFormatForRowAll    = '<div class="alert alert-error all-errors">
                                              <a class="close" data-dismiss="alert">×</a>
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
    return strtr($this->getRowFormat(), array(
        '%label%'         => $label,
        '%field%'         => $this->formatRequiredField($field),
                             // show help only when there are no errors
        '%help%'          => 0 == count($errors) ? $this->formatHelp($help) : '',
        '%errors%'        => $this->formatErrorsForField($errors),
        '%error_class%'   => count($errors) ? 'error' : '',
        '%hidden_fields%' => null === $hiddenFields ? '%hidden_fields%' : $hiddenFields,
    ));
  }

  /**
   * Displays all errors for a given form, both field and global
   *
   * @param type $errors
   */
  public function formatAllErrorsForRow($errors)
  {
    if (null === $errors || !$errors || !count($errors))
    {
      return '';
    }

    if (!is_array($errors))
    {
      $errors = array($errors);
    }

    return strtr($this->errorListFormatForRowAll, array('%errors%' => implode('', $this->unnestErrorsForRowAll($errors))));
    return '<div class="alert alert-error">'
           . strtr($this->errorListFormatInARow, array('%errors%' => implode('', $this->unnestErrorsForRowAll($errors))))
           . '</div>';
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
          $name = $this->generateLabelName($name);
          $newErrors[] = strtr($this->namedErrorRowFormatForRowAll, array('%error%' => $err, '%name%' => ($prefix ? $prefix.' > ' : '').$name));
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
   * @return    type
   */
  public function formatErrorsForField($errors)
  {
    if (null === $errors || !$errors)
    {
      return '';
    }

    if (!is_array($errors))
    {
      $errors = array($errors);
    }

    return strtr($this->errorListFormatForField, array('%errors%' => implode('', $this->unnestErrorsForField($errors))));
  }

  /**
   * used by formatErrorsForField()
   *
   * @param     sfValidatorErrorSchema $errors
   * @param     string $prefix
   *
   * @return    array of strings
   */
  protected function unnestErrorsForField($errors, $prefix = '')
  {
    $newErrors = array();

    foreach ($errors as $name => $error)
    {
      if ($error instanceof ArrayAccess || is_array($error))
      {
        $newErrors = array_merge($newErrors, $this->unnestErrorsForField($error, ($prefix ? $prefix.' > ' : '').$name));
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
          $newErrors[] = strtr($this->errorRowFormatForField, array('%error%' => $err));
        }
      }
    }

    return $newErrors;
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
