<?php

/**
 * PrivateMessage filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormFilterTemplate.php 11675 2008-09-19 15:21:38Z fabien $
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
    $this->widgetSchema['collector_sender'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::USERNAME
    ));
    $this->validatorSchema['collector_sender'] = new  sfValidatorPass(array('required' => false));
  }

  public function setupCollectorReceiver()
  {
    $this->widgetSchema['collector_receiver'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::USERNAME
    ));
    $this->validatorSchema['collector_receiver'] = new  sfValidatorPass(array('required' => false));
  }

}
