<?php

/**
 * Collector form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 *
 * @method     Collector getObject()
 */
class CollectorEditForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'display_name'  => new sfWidgetFormInputText(),
      'password'      => new sfWidgetFormInputText(),
      'email'         => new sfWidgetFormInputText(),
      'photo'         => new sfWidgetFormInputFile(),
      'is_public'     => new sfWidgetFormInputCheckbox()
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorPropelChoice(array('model' => 'Collector', 'column' => 'id', 'required' => false)),
      'display_name'  => new sfValidatorString(array('max_length' => 64)),
      'password'      => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'email'         => new sfValidatorEmail(array('max_length' => 128, 'required' => true)),
      'photo'         => new cqValidatorFile(array('mime_types' => 'cq_supported_images', 'required' => false)),
      'is_public'     => new sfValidatorBoolean(array('required' => false))
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'Collector', 'column' => array('email')))
    );

    $this->setupInternalTagsField();

    $this->widgetSchema->setNameFormat('collector[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  protected function setupInternalTagsField()
  {
    // pretty ugly hack, but in this case this is the only way
    // to keep the field's state between requests...
    $tags = $this->getObject()->getInternalTags();

    $this->widgetSchema['internal_tags'] = new cqWidgetFormInputTags(array(
      'label' => 'Internal Tags',
      'autocompleteURL' => '@ajax_typeahead?section=tags&page=edit',
    ), array(
      'class' => 'tag'
    ));

    $this->widgetSchema['internal_tags']->setDefault($tags);
    $this->validatorSchema['internal_tags'] = new cqValidatorTags(array(
      'required' => false,
    ));
  }

  public function getModelName()
  {
    return 'Collector';
  }

  public function save($con = null)
  {
    /* @var $object Collector */
    $object = parent::save($con);

    /** @var $values array */
    $values = $this->getValues();

    if (isset($values['internal_tags']))
    {
      $object->setInternalTags($values['internal_tags']);
    }
    $object->save();

    return $object;
  }
}
