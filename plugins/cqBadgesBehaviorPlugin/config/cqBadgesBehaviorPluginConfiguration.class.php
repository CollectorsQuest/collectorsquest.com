<?php

/**
 * cqBadgesBehaviorPlugin configuration.
 *
 * @package     cqBadgesBehaviorPlugin
 * @subpackage  config
 */
class cqBadgesBehaviorPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    sfPropelBehavior::registerHooks('cqBadgesBehavior', array (
      ':save:post' => array ('cqBadgesBehavior', 'postSave'),
      ':delete:pre' => array ('cqBadgesBehavior', 'preDelete'),
    ));

    sfPropelBehavior::registerMethods('cqBadgesBehavior', array (
      array('cqBadgesBehavior', 'addBadge'),
      array('cqBadgesBehavior', 'getBadges'),
      array('cqBadgesBehavior', 'removeAllBadges'),
      array('cqBadgesBehavior', 'setBadges'),
    ));

    return parent::initialize();
  }
}