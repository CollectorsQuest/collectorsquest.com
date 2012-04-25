<?php

/**
 * PrivateMessage form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 *
 * @method     PrivateMessage getObject() Get the model object for the form
 */
class PrivateMessageForm extends BasePrivateMessageForm
{

  public function configure()
  {
    $this->widgetSchema['sender'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['receiver'] = new sfWidgetFormPropelSelectMany(array(
        'model' => 'Collector',
    ));

    $this->validatorSchema['receiver'] = new sfValidatorPropelChoice(array(
        'model' => 'Collector',
        'multiple' => true,
        'required' => true
    ));

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);

    $this->widgetSchema->setNameFormat('message[%s]');
  }

}
