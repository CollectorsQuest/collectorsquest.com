<?php

/**
 * sfWidgetFormSchemaFormatterBootstrap basic twitter bootstrap formatter
 */
class sfWidgetFormSchemaFormatterBootstrap extends sfWidgetFormSchemaFormatter
{
  protected $rowFormat = '
    <div class="control-group %error_class%">
      %label%
      <div class="controls">
        %field%
        %help%
        %errors%
        %hidden_fields%
      </div>
    </div>
';

  protected $helpFormat                  = '<p class="help-block">%help%</p>';
  protected $errorRowFormatForField      = '<li class="help-block">%error%</li>';
  protected $namedErrorRowFormatForField = '<li class="help-block">%name%: %error%</li>';

  protected $errorListFormatInARow    = '<ul class="unstyled alert alert-error">%errors%  </ul>';
  protected $errorListFormatForField  = '<ul class="unstyled">%errors%  </ul>';


  public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null)
  {
    return strtr($this->getRowFormat(), array(
        '%label%'         => $label,
        '%field%'         => $field,
                             // show help only when there are no errors
        '%help%'          => 0 == count($errors) ? $this->formatHelp($help) : '',
        '%errors%'        => $this->formatErrorsForField($errors),
        '%error_class%'   => count($errors) ? 'error' : '',
        '%hidden_fields%' => null === $hiddenFields ? '%hidden_fields%' : $hiddenFields,
    ));
  }

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


  public function generateLabel($name, $attributes = array())
  {
    $attributes['class'] = (isset($attributes['class']) ? $attributes['class'] : '') . ' control-label';

    return parent::generateLabel($name, $attributes);
  }

}
