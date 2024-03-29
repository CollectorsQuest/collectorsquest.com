<?php

class CollectorAvatarForm extends BaseFormPropel
{
  public function configure()
  {
    parent::configure();

    $this->widgetSchema['filename']    = new sfWidgetFormInputFile();
    $this->validatorSchema['filename'] = new cqValidatorFile(array(
      'mime_types' => 'cq_supported_images'
    ));

    $this->widgetSchema->setNameFormat('avatar[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  public function getModelName()
  {
    return 'Collector';
  }

  protected function doUpdateObject($values)
  {
    if ($values['filename'] instanceof sfValidatedFile)
    {
      $this->getObject()->setPhoto($values['filename']);
    }
  }
}
