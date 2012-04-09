<?php

/**
 * Project form base class.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormBaseTemplate.php 9304 2008-05-27 03:49:32Z dwhittle $
 */
abstract class BaseFormPropel extends sfFormPropel
{
  public function __construct($object = null, $options = array(), $CSRFSecret = null)
  {
    parent::__construct($object, $options, $CSRFSecret);
    $this->unsetFields();
  }

  /**
   * Overload this method and use it to unset widgets/validators like this:
   *
   * unset($this['field_name']);
   */
  protected function unsetFields()
  {
  }
}
