<?php

/**
 * PrivateMessage form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PrivateMessageForm extends BasePrivateMessageForm
{
  public function configure()
  {
    $this->widgetSchema['id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['sender'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['receiver'] = new sfWidgetFormPropelSelectMany(array('model' => 'Collector'));
    $this->widgetSchema->setNameFormat('message[%s]');

    $this->validatorSchema['receiver'] = new sfValidatorPropelChoiceMany(array('model' => 'Collector', 'required' => true));
    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);
  }
}
