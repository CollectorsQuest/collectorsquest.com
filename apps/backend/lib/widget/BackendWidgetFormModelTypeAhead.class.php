<?php

class BackendWidgetFormModelTypeAhead extends bsWidgetFormInputTypeAhead
{
  public function __construct($options = array(), $attributes = array())
  {
    list ($model, $field) = explode('.', $options['field']);
    unset($options['field']);

    $model = lcfirst(sfInflector::camelize(strtolower($model)));
    $field = lcfirst(sfInflector::camelize(strtolower($field)));

    $options['source'] = cqContext::getInstance()->getController()->genUrl(
      '@ajax_typeahead?section='. $model .'&page='. $field
    );

    parent::__construct($options, $attributes);
  }
}
