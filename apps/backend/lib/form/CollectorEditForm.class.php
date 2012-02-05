<?php

/**
 * Collector form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
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
      'photo'         => new sfValidatorFile(array('mime_types' => 'web_images', 'required' => false)),
      'is_public'     => new sfValidatorBoolean(array('required' => false))
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'Collector', 'column' => array('email')))
    );

    $this->widgetSchema->setNameFormat('collector[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Collector';
  }
}
