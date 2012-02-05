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
    $this->widgetSchema['spaminess'] = new sfWidgetFormChoice(array(
      'choices' => array('' => '', 'green' => 'Green', 'yellow' => 'Yellow', 'red' => 'Red')
    ));
    $this->validatorSchema['spaminess'] = new sfValidatorChoice(array(
      'required' => false,
      'choices' => array('', 'green', 'yellow', 'red')
    ));
  }

  public function getCollectorTypes()
  {
    return array(
      '' => '',
      'Collector' => 'Collector',
      'Seller' => 'Seller',
    );
  }
}
