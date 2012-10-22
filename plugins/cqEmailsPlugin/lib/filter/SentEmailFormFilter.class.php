<?php

/**
 * SentEmail filter form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
class SentEmailFormFilter extends BaseSentEmailFormFilter
{
  public function configure()
  {
      $this->widgetSchema['sender_email'] = new BackendWidgetFormModelTypeAhead(array(
          'field' => SentEmailPeer::SENDER_EMAIL
      ));
      $this->widgetSchema['receiver_email'] = new BackendWidgetFormModelTypeAhead(array(
          'field' => SentEmailPeer::RECEIVER_EMAIL
      ));
      $this->widgetSchema['subject'] = new BackendWidgetFormModelTypeAhead(array(
          'field' => SentEmailPeer::SUBJECT
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
