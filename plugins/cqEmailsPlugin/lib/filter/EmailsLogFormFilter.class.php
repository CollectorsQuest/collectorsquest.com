<?php

/**
 * EmailsLog filter form.
 *
 * @package    cqEmailsPlugin
 * @subpackage filter
 * @author     Pavel Goncharov
 */
class EmailsLogFormFilter extends BaseEmailsLogFormFilter
{
  public function configure()
  {
    $this->widgetSchema['sender_email'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => EmailsLogPeer::SENDER_EMAIL
    ));
    $this->widgetSchema['receiver_email'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => EmailsLogPeer::RECEIVER_EMAIL
    ));
    $this->widgetSchema['subject'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => EmailsLogPeer::SUBJECT
    ));

    $this->widgetSchema['result'] = new sfWidgetFormChoice(array(
      'choices' => array('' => '', 'success' => 'success', 'failed' => 'failed',
        'pending' => 'pending', 'tentative' => 'tentative')
    ));
    $this->validatorSchema['result'] = new sfValidatorChoice(array(
      'required' => false,
      'choices' => array('', 'success', 'failed', 'pending', 'tentative')
    ));

    $this->widgetSchema['created_at'] = new sfWidgetFormJQueryDateRange(array(
      'config' => '{}',
    ));
    $this->validatorSchema['created_at'] = new IceValidatorDateRange(array(
      'required' => false, 'from_date' => 'from', 'to_date' => 'to'
    ));
  }
}
