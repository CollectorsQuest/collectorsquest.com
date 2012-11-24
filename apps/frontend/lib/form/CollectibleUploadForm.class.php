<?php

class CollectibleUploadForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'collectible_id'  => new sfWidgetFormInputHidden()
    ));

    $this->setValidators(array(
      'collectible_id'  => new sfValidatorInteger(array('required' => false))
    ));

    $this->setupThumbnailField();

    $this->widgetSchema->setNameFormat('collectible_upload[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  protected function setupThumbnailField()
  {
    $this->widgetSchema['thumbnail'] = new sfWidgetFormInputFile(
      array('label' => 'Photo')
    );
    $this->validatorSchema['thumbnail'] = new cqValidatorFile(array(
      'mime_types' => 'cq_supported_multimedia', 'required' => true
    ));

    $this->getWidgetSchema()->setHelp(
      'thumbnail', 'Choose a photo which represents your item.'
    );
  }

}
