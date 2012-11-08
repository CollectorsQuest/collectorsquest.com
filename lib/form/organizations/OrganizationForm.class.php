<?php

/**
 * Organization form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors Quest, Inc.
 */
class OrganizationForm extends BaseOrganizationForm
{
  public function configure()
  {
    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorPropelUnique(array(
            'model' => 'Organization',
            'column' => array('referral_code'),
            'allow_null_uniques' => true,
        )),
        new sfValidatorPropelUnique(array(
            'model' => 'Organization',
            'column' => array('slug'),
        )),
      ))
    );
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (!$this->isNew())
    {
      // because the target PK is actually a propel enum, we need to account for
      // propel's altomatic id to name translation here
      $type_value_set = OrganizationTypePeer::getValueSet(OrganizationTypePeer::TYPE);
      $this->setDefault(
        'type',
        isset($type_value_set[$this->getObject()->getType()])
          ? $type_value_set[$this->getObject()->getType()]
          : null
      );
    }
  }

}
