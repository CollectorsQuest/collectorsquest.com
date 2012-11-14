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
    $this->setupReferralCodeField();

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

  protected function setupReferralCodeField()
  {
    // because we want to allow null uniques for referral_code, we need to
    // manually make sure the return value for the field is NULL when an empty
    // string is inputted
    $original_validator = $this->validatorSchema['referral_code'];
    $this->validatorSchema['referral_code'] = new sfValidatorCallback(array(
        'callback' => function(sfValidatorCallback $v, $value) use ($original_validator)
        {
          // return an empty string as NULL
          return '' == $original_validator->clean($value)
            ? null
            : $value;
        },
    ));

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
