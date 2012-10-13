<?php

/**
 * Backend PrivateMessage filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Pavel Goncharov
 */
class BackendPrivateMessageFormFilter extends PrivateMessageFormFilter
{
  public function configure()
  {
    parent::configure();
    $this->setupCollectorSender();
    $this->setupCollectorReceiver();
  }

  public function setupCollectorSender()
  {
    $this->widgetSchema['collector_sender_username'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::USERNAME
    ));
    $this->validatorSchema['collector_sender_username'] = new  sfValidatorPass(array('required' => false));
  }

  public function setupCollectorReceiver()
  {
    $this->widgetSchema['collector_receiver_username'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::USERNAME
    ));
    $this->validatorSchema['collector_receiver_username'] = new  sfValidatorPass(array('required' => false));
  }

}
