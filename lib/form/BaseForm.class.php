<?php

/**
 * Base project form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony
{

  /**
   * Renders all errors associated with this form.
   *
   * @return string The rendered errors
   */
  public function renderAllErrors()
  {
    $formatter = $this->widgetSchema->getFormFormatter();

    if (!method_exists($formatter, 'formatAllErrorsForRow'))
    {
      throw new Exception('[sfForm] In order to use "renderAllErrors() you must
        select a form formatter that implements "formatAllErrorsForRow", eg the
        Bootstrap formatter');
    }

    return $this->widgetSchema->getFormFormatter()->formatAllErrorsForRow($this->getErrorSchema());
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
