<?php

/**
 * cqEmailsPlugin configuration.
 * 
 * @package     cqEmailsPlugin
 * @subpackage  config
 * @author      Ivan Plamenov Tanev aka Crafty_Shadow @ WEBWORLD.BG <vankata.t@gmail.com>
 * @version     SVN: $Id: PluginConfiguration.class.php 17207 2009-04-10 15:36:26Z Kris.Wallsmith $
 */
class cqEmailsPluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '1.0.0-DEV';

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
      // Connect listeners
      $this->configuration->getEventDispatcher()
          ->connect('mailer.configure', array($this, 'listenToMailConfigureEvent'));
  }

  public function listenToMailConfigureEvent(sfEvent $event)
  {
        $mailer = $event->getSubject();
        $mailer->registerPlugin(new cqEmailLog());
  }
}
