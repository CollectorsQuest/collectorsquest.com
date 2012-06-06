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

}
