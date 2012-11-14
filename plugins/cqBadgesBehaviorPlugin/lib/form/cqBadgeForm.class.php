<?php

/**
 * cqBadge form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class cqBadgeForm extends BasecqBadgeForm
{
  public function configure()
  {
    $this->useFields(array('name', 'tier', 'parent_model', 'parent_model_id'));

    $this->widgetSchema['parent_model'] =
      new sfWidgetFormChoice(array('choices' => sfConfig::get('app_badges_parent_models', array())));
    $this->validatorSchema['parent_model'] =
      new  sfValidatorChoice(array('choices' => sfConfig::get('app_badges_parent_models', array())));

    $this->mergePostValidator(new cqValidatorBadgeParent());

  }
}
