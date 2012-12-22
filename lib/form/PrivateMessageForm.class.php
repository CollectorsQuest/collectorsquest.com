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
    $this->widgetSchema['sender_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['receiver_id'] = new sfWidgetFormPropelSelectMany(array(
        'model' => 'Collector',
    ));

    $this->validatorSchema['receiver_id'] = new sfValidatorPropelChoice(array(
        'model' => 'Collector',
        'multiple' => true,
        'required' => true
    ));

    $this->validatorSchema['subject']->setOption('trim', true);
    $this->validatorSchema['body']->setOption('trim', true);

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);

    $this->widgetSchema->setNameFormat('message[%s]');
  }

}
