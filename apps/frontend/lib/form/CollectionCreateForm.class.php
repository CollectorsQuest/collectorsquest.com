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
      'content_category_id' => new cqWidgetFormPropelChoiceByNestedSet(array(
        'label' => 'Category',
        'model' => 'ContentCategory',
        'add_empty' => true,
      )),
      'step'  => new sfWidgetFormInputHidden(array('default' => 1)),
    ));

    $this->setValidators(array(
      'id'    => new sfValidatorPropelChoice(
        array('model' => 'Collection', 'column' => 'id', 'required' => false)
      ),
      'name'  => new sfValidatorString(),
      'tags'  => new sfValidatorString(),
      'content_category_id' => new sfValidatorPropelChoice(array(
        'required' => true,
        'model' => 'ContentCategory',
        'column' => 'id',
      )),
      'step'  => new sfValidatorInteger(),
    ));

    $this->getWidgetSchema()->setHelp(
      'tags', 'Choose at least three descriptive words for your collection, separated by commas'
    );

    $this->widgetSchema->setNameFormat('collection[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }
}
