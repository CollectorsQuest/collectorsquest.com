<?php

/**
 * CommentDeleteConfirmationForm does exactly what you'd expect it to do ;
 */
class CommentDeleteConfirmationForm extends BaseForm
{

  public function configure()
  {
    $this->setupConfirmCheckbox();

    $this->widgetSchema->setNameFormat('comment_delete_confirmation[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  protected function setupConfirmCheckbox()
  {
    $this->widgetSchema['_confirm'] = new sfWidgetFormInputCheckbox(array(
        'label' => 'Confirm delete',
      ), array(
        'required' => 'required',
    ));
    $this->validatorSchema['_confirm'] = new sfValidatorBoolean(array(
        'required' => true
      ), array(
        'required' => 'You need to confirm that you want to delete this comment',
    ));
  }

}