<?php

class CollectibleCreateForm extends CollectibleForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'collection_id'  => new sfWidgetFormInputHidden(),
      'name'  => new sfWidgetFormInputText(array(
        'label' => 'Collectible Name',
      ), array(
        'required' => 'required',
        'class' => 'input-xlarge'
      )),
      'tags'  => new sfWidgetFormInputText(array(
        'label' => 'Tags'
      ), array(
        'required' => 'required',
        'class' => 'input-xlarge'
      ))
    ));

    // Setup the Tags field
    $this->setupTagsField();

    $this->setValidators(array(
      'collection_id'    => new sfValidatorPropelChoice(
        array('model' => 'Collection', 'column' => 'id', 'required' => true)
      ),
      'name'  => new sfValidatorString(),
      'tags'  => new sfValidatorCallback(
        array('required' => true, 'callback' => array($this, 'validateTagsField'))
      )
    ));

    $this->widgetSchema->setNameFormat('collectible[%s]');
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
      'name' => 'collectible[tags][]',
      'required' => 'required',
      'class' => 'tag'
    ));

    $this->widgetSchema['tags']->setDefault($tags);
    $this->getWidgetSchema()->setHelp(
      'tags', 'Choose at least three descriptive words for your collectible, separated by commas'
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
