<?php

class CollectionCreateForm extends CollectorCollectionForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'id'  => new sfWidgetFormInputHidden(),
      'name'  => new sfWidgetFormInputText(array(
        'label' => 'Collection Name',
      ), array(
        'required' => 'required',
        'class' => 'input-xlarge',
        'tabindex' => 1

      )),
      'tags'  => new cqWidgetFormInputTags(array(
        'label' => 'Tags',
        'autocompleteURL' => '@ajax_typeahead?section=tags&page=edit',
      ), array(
        'required' => 'required',
        'class' => 'input-xlarge',
        'tabindex' => 2,
      )),
      'content_category_id' => new sfWidgetFormInputHidden(array(
        'label' => 'Category',
      ), array(
        'required' => 'required'
      )),
      'collectible_id'  => new sfWidgetFormInputHidden(),
      'step'       => new sfWidgetFormInputHidden(array('default' => 1)),
    ));

    // Setup the Tags field
    $this->setupTagsField();
    // Setup the Name field
    $this->setupNameField();

    $this->setValidators(array(
      'id'    => new sfValidatorPropelChoice(
        array('model' => 'CollectorCollection', 'column' => 'id', 'required' => false)
      ),
      'name'  => new cqValidatorName(
        array('required' => true),
        array('invalid' => 'Please enter a complete title for your collection.')
      ),
      'tags'  => new cqValidatorTags(),
      'content_category_id' => new sfValidatorPropelChoice(array(
        'required' => true,
        'model' => 'ContentCategory',
        'column' => 'id',
      )),
      'collectible_id'  => new sfValidatorInteger(array('required' => false)),
      'step'  => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('collection[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  protected function setupTagsField()
  {
    // pretty ugly hack, but in this case this is the only way
    // to keep the field's state between requests...
    $tags = $this->getObject()->getTags();

    $this->widgetSchema['tags']->setDefault($tags);
    $this->getWidgetSchema()->setHelp(
      'tags', 'Choose at least three descriptive words
               or phrases, separated by commas.'
    );
  }

  protected function setupNameField()
  {
    $this->getWidgetSchema()->setHelp(
      'name', 'Enter a title to describe your entire collection.'
    );
  }
}
