<?php

/**
 * Collector filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormFilterTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class CollectorFormFilter extends BaseCollectorFormFilter
{
  public function configure()
  {
    $this->setupUserTypeField();
    $this->setupSpaminessField();
  }

  public function getCollectorTypes()
  {
    return array(
      '' => '',
      'collector' => 'Collector',
      'seller' => 'Seller',
    );
  }

  protected function setupUserTypeField()
  {
    $this->widgetSchema['user_type'] = new sfWidgetFormChoice(array(
      'choices' => $this->getCollectorTypes(),
    ));
  }

  protected function setupSpaminessField()
  {
    $this->widgetSchema['spaminess'] = new sfWidgetFormChoice(array(
      'choices' => array('' => '', 'green' => 'Green', 'yellow' => 'Yellow', 'red' => 'Red')
    ));
    $this->validatorSchema['spaminess'] = new sfValidatorChoice(array(
      'required' => false,
      'choices' => array('', 'green', 'yellow', 'red')
    ));
  }

}
