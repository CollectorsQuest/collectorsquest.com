<?php

class MachineTagForm extends MachineTagDynamicExtendForm
{
  public function configure()
  {
    $this->setupTagsField();
    $this->useFields(array('tags'));
    unset($this['id']);
  }

  protected function setupTagsField()
  {
    $context = sfContext::getInstance();
    $context->getConfiguration()->loadHelpers(array('iceBackend'));

    // pretty ugly hack, but in this case this is the only way
    // to keep the field's state between requests...
    $tags = $this->getObject()->getTags(array('is_triple' => true, 'return' => 'tag'));

    $this->widgetSchema['tags'] = new cqWidgetFormInputTags(array(
      'label' => 'Machine Tags',
      'autocompleteURL' => url_to_frontend('ajax_typeahead', array('section' => 'tags', 'page' => 'edit')),
    ));
    $this->widgetSchema['tags']->setDefault($tags);
    $this->getWidgetSchema()->setHelp(
      'tags', 'Please add tags in format "namespace:key=value" like "matching:market=batman" and press "Save"'
    );
    $this->validatorSchema['tags'] = new sfValidatorPass(array('required' => false));

  }

  public function save($con = null)
  {
    /** @var $object Collectible */
    $object = parent::save($con);

    /** @var $values array */
    $values = $this->getValues();
    $object->setTags($values['tags'], true);

    $object->save();

    $tags = $this->getObject()->getTags(array('is_triple' => true, 'return' => 'tag'));
    $this->bind(array('tags' => $tags));

    return $object;
  }

}
