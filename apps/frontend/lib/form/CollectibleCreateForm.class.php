<?php

class CollectibleCreateForm extends CollectibleForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'collection_id'  => new sfWidgetFormInputHidden(),
      'name'  => new sfWidgetFormInputText(array(
        'label' => 'Name',
      ), array(
        'required' => 'required',
        'class' => 'input-xlarge'
      )),
      'description' => new sfWidgetFormTextarea(
        array(),
        array(
          'required' => 'required',
          'class' => 'input-xlarge js-invisible'
        )
      ),
      'tags'  => new cqWidgetFormInputTags(array(
        'label' => 'Tags',
        'autocompleteURL' => '@ajax_typeahead?section=tags&page=edit',
      ), array(
        'required' => 'required',
        'class' => 'input-xlarge'
      )),
      'thumbnail'  => new sfWidgetFormInputHidden()
    ));

    $this->setValidators(array(
      'collection_id'  => new sfValidatorPropelChoice(array(
        'model' => 'CollectorCollection', 'column' => 'id', 'required' => true
      )),
      'name'  => new cqValidatorName(
        array('required' => true),
        array('invalid' => 'You need to use more descriptive name for your item
                            (is it the camera auto generated name?)')),
      'description'  => new sfValidatorString(array('required' => true)),
      'thumbnail'  => new sfValidatorInteger(array('required' => false))
    ));

    // Setup the Tags field
    $this->setupTagsField();

    $this->widgetSchema->setNameFormat('collectible[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  protected function setupTagsField()
  {
    // pretty ugly hack, but in this case this is the only way
    // to keep the field's state between requests...
    $tags = $this->getObject()->getTags();

    $this->widgetSchema['tags'] = new cqWidgetFormInputTags(array(
      'label' => 'Tags',
      'autocompleteURL' => '@ajax_typeahead?section=tags&page=edit',
    ), array(
      'required' => 'required',
    ));

    $this->widgetSchema['tags']->setDefault($tags);
    $this->getWidgetSchema()->setHelp(
      'tags', 'Choose at least three descriptive words
               or phrases, separated by commas'
    );

    $this->validatorSchema['tags'] = new cqValidatorTags();
  }

  public function validateTagsField($validator, $values)
  {
    $values = (array) $values;

    if (empty($values)) {
      throw new sfValidatorError($validator, 'required');
    }
    else {
      return $values;
    }
  }
}
