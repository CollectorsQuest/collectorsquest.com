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
        'class' => 'input-xlarge'
      )),
      'tags'  => new sfWidgetFormInputText(array(
        'label' => 'Tags'
      ), array(
        'required' => 'required',
        'class' => 'input-xlarge'
      )),
      'content_category_id' => new sfWidgetFormInputHidden(array(
        'label' => 'Category',
      ), array(
        'required' => 'required'
      )),
      'thumbnail'  => new sfWidgetFormInputHidden(),
      'step'       => new sfWidgetFormInputHidden(array('default' => 1)),
    ));

    // Setup the Tags field
    $this->setupTagsField();

    $this->setValidators(array(
      'id'    => new sfValidatorPropelChoice(
        array('model' => 'CollectorCollection', 'column' => 'id', 'required' => false)
      ),
      'name'  => new sfValidatorString(),
      'tags'  => new sfValidatorCallback(
        array('required' => true, 'callback' => array($this, 'validateTagsField'))
      ),
      'content_category_id' => new sfValidatorPropelChoice(array(
        'required' => true,
        'model' => 'ContentCategory',
        'column' => 'id',
      )),
      'thumbnail'  => new sfValidatorInteger(array('required' => false)),
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

    $this->widgetSchema['tags'] = new cqWidgetFormMultipleInputText(array(
      'label' => 'Tags'
    ), array(
      'required' => 'required',
      'class' => 'tag'
    ));

    $this->widgetSchema['tags']->setDefault($tags);
    $this->getWidgetSchema()->setHelp(
      'tags', 'Choose at least three descriptive words
               or phrases, separated by commas'
    );
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
