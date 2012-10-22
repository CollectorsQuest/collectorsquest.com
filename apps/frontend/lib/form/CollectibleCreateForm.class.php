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
      'content_category_id' => new sfWidgetFormInputHidden(array(
        'label' => 'Category',
      ), array(
        'required' => 'required'
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
      ))
    ));

    $this->setValidators(array(
      'collection_id'  => new sfValidatorPropelChoice(array(
        'model' => 'CollectorCollection', 'column' => 'id', 'required' => true
      )),
      'name'  => new cqValidatorName(
        array('required' => true),
        array('invalid' => 'You need to use more descriptive name for your item
                            (is it the camera auto generated name?)')),
      'content_category_id' => new sfValidatorPropelChoice(array(
        'required' => true,
        'model' => 'ContentCategory',
        'column' => 'id',
      )),
      'description'  => new sfValidatorString(array('required' => true))
    ));

    // Setup the Tags field
    $this->setupTagsField();
    // Setup the Name field
    $this->setupNameField();
    // unset thumbnail field
    $this->unsetThumbnailField();

    $this->widgetSchema->setNameFormat('collectible[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  public function unsetCollectionIdField()
  {
    unset ($this['collection_id']);
  }

  public function unsetThumbnailField()
  {
    unset ($this['thumbnail']);
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
               or phrases'
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

  protected function setupNameField()
  {
    $this->getWidgetSchema()->setHelp(
      'name', 'Enter a name for this item.'
    );
  }
}
