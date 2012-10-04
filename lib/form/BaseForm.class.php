<?php

/**
 * Base project form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 */
class BaseForm extends sfFormSymfony
{

  /** @var   string The field name that will be checked on bind and auto-populated */
  protected $ip_address_field_name = 'ip_address';

  /**
   * @return    string
   */
  public function getIpAddressFieldName()
  {
    return $this->ip_address_field_name;
  }

  /**
   * @param     string $name
   * @return    BaseForm
   */
  protected function setIpAddressFieldName($name)
  {
    $this->ip_address_field_name = $name;

    return $this;
  }

  /**
   * Convenience method for fast IP address setup from any form
   *
   * @param     string $field_name A specific field name, "ip_address" by default
   * @return    void
   */
  protected function setupIpAddressField($field_name = null)
  {
    if (null !== $field_name)
    {
      $this->setIpAddressFieldName($field_name);
    }

    $this->widgetSchema[$this->getIpAddressFieldName()] = new cqWidgetFormNullInput();
    $this->validatorSchema[$this->getIpAddressFieldName()] = new sfValidatorPass();
  }

  /**
   * Auto populate the request's IP address if a field named "ip_address" is present
   *
   * @param     array $taintedValues
   * @param     array $taintedFiles
   */
  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if (isset($this->widgetSchema[$this->getIpAddressFieldName()]))
    {
      /* @var $request cqWebRequest */
      $request = cqContext::getInstance()->getRequest();
      $taintedValues = array_merge((array) $taintedValues, array(
          $this->getIpAddressFieldName() => $request->getRemoteAddress(),
      ));
    }

    parent::bind($taintedValues, $taintedFiles);
  }

  /**
   * Renders all errors associated with this form.
   *
   * @return string The rendered errors
   */
  public function renderAllErrors($errors_header = null)
  {
    $formatter = $this->widgetSchema->getFormFormatter();

    if (!method_exists($formatter, 'formatAllErrorsForRow'))
    {
      throw new Exception('[sfForm] In order to use "renderAllErrors() you must
        select a form formatter that implements "formatAllErrorsForRow", eg the
        Bootstrap formatter');
    }

    return $this->widgetSchema->getFormFormatter()->formatAllErrorsForRow(
      $this->getErrorSchema(),
      $errors_header
   );
  }

  /**
   * @param string $path
   * @return sfFormField
   */
  public function getFormField($path)
  {
    if (false !== $pos = strpos($path, '['))
    {
      $field = $this[substr($path, 0, $pos)];
    }
    else
    {
      return $this[$path];
    }

    if (preg_match_all('/\[(?P<part>[^]]+)\]/', $path, $matches))
    {
      foreach($matches['part'] as $part)
      {
        $field = $field[$part];
      }
    }

    return $field;
  }

  /**
   * Partial implementation mimicking the Form Functional Tester isError() function
   *
   * @param type $field
   * @param type $value
   */
  public function isError($field, $value = true)
  {
    if (null === $field)
    {
      $error = new sfValidatorErrorSchema(new sfValidatorPass(), $this->form->getGlobalErrors());
    }
    else
    {
      $error = $this->getFormField($field)->getError();
    }

    if (false === $value)
    {
      return !$error || 0 == count($error);
    }
    else if (true === $value)
    {
      return $error && count($error) > 0;
    }
    else if (is_int($value))
    {
      return $error && count($error) == $value;
    }
    else
    {
      throw new RuntimeException('Unimplemented error matching pattern "%s" for form %s', $field, $this->widgetSchema->getName());
    }
  }

}
