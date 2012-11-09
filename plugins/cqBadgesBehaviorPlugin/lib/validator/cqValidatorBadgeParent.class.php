<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * cqValidatorBadgeParent check parent object when custom tier selected.
 *
 */
class cqValidatorBadgeParent extends sfValidatorSchema
{

  public function __construct($fields = null, $options = array(), $messages = array())
  {
    parent::__construct($fields, $options, $messages);
    $this->setMessage('invalid', 'Parent object is wrong.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    if (null === $values)
    {
      $values = array();
    }

    if (!is_array($values))
    {
      throw new InvalidArgumentException('You must pass an array parameter to the clean() method');
    }

    if ($values['tier'] != BasecqBadgePeer::TIER_CUSTOM)
    {
      $values['parent_model'] = null;
      $values['parent_model_id'] = null;
      return $values;
    }

    $objectValidator = new sfValidatorPropelChoice(
      array('model' => $values['parent_model'], 'column' => 'id', 'required' => true)
    );
    $objectValidator->setMessage('invalid', $this->getMessage('invalid'));
    $objectValidator->doClean($values['parent_model_id']);

    return $values;
  }
}
