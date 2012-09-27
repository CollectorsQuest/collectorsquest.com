<?php

/**
 * CommentReportSpamConfirmationForm does exactly what you'd expect it to do ;
 */
class CommentReportSpamConfirmationForm extends BaseForm
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
        'label' => 'Confirm report as spam',
      ), array(
        'required' => 'required',
    ));
    $this->validatorSchema['_confirm'] = new sfValidatorBoolean(array(
        'required' => true
      ), array(
        'required' => 'You need to confirm that you want to report this comment as spam',
    ));
  }

}