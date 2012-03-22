<?php

/**
 * Collector filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormFilterTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BackendCollectorFormFilter extends BaseCollectorFormFilter
{

  public function configure()
  {
    $this->setupUserTypeField();
    $this->setupSpaminessField();
    $this->setupCqnextAccessAllowedField();

    $this->widgetSchema['username'] = new bsWidgetFormInputTypeAhead(array(
      'source' => $this->getOption('url_username', sfContext::getInstance()->getController()->genUrl('collectors/username'))
    ));

    $this->widgetSchema['display_name'] = new bsWidgetFormInputTypeAhead(array(
      'source' => $this->getOption('url_display_name', sfContext::getInstance()->getController()->genUrl('collectors/displayName')),
    ));
  }

  public function getCollectorTypes()
  {
    return array(
      ''          => '',
      'collector' => 'Collector',
      'seller'    => 'Seller',
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
      'choices' => array(
        ''       => '',
        'green'  => 'Green',
        'yellow' => 'Yellow',
        'red'    => 'Red'
      )
    ));
    $this->validatorSchema['spaminess'] = new sfValidatorChoice(array(
      'required' => false,
      'choices'  => array('', 'green', 'yellow', 'red')
    ));
  }

  protected function setupCqnextAccessAllowedField()
  {
    $this->widgetSchema['cqnext_access_allowed'] = new sfWidgetFormChoice(array(
      'choices' => array(
        '' => 'yes or no',
        1  => 'yes',
        0  => 'no'
      )
    ));
    $this->validatorSchema['cqnext_access_allowed'] = new sfValidatorChoice(array(
      'required' => false,
      'choices'  => array('', 1, 0)
    ));
  }

}
