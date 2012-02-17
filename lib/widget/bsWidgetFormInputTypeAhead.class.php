<?php
/**
 * File: bsWidgetFormInputTypeAhead.class.php
 *
 * Widget implementing typeahead control of bootstrap
 *
 * @author zecho
 * @version $Id$
 *
 */

class bsWidgetFormInputTypeAhead extends sfWidgetFormInput
{

  public function __construct($options = array(), $attributes = array())
  {
    $this->addRequiredOption('source');
    $this->addOption('items', 8);
    $this->addOption('matcher');
    $this->addOption('sorter');
    $this->addOption('highlighter');

    parent::__construct($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $attributes['data-provide'] = 'typeahead';

    $typeAheadOptions = array_intersect_key($this->options, array_flip(array('source', 'items', 'matcher', 'sorter', 'highlighter')));

    return parent::render($name, $value, $attributes, $errors)
        . sprintf('<script type="text/javascript">jQuery("#%s").typeahead(%s);</script>', $this->generateId($name), json_encode($typeAheadOptions));
  }

}
