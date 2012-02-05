<?php

/**
 * MessageTemplate form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class MessageTemplateForm extends BaseMessageTemplateForm
{
  public function configure()
  {
    $this->widgetSchema['body'] = new sfWidgetFormTextareaTinyMCE(array(
      'width'  => 550,
      'height' => 400,
      'config' => 'theme_advanced_disable: "anchor,image,cleanup,help"',
    ));
  }
}
