<?php

/**
 * Collector form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 * @method     Collector getObject()
 */
class CollectorForm extends BaseCollectorForm
{
  public function configure()
  {
    $this->unsetFields();
  }

  protected function unsetFields()
  {
    unset($this['graph_id']);
    unset($this['cookie_uuid']);
    unset($this['facebook_id']);
    unset($this['slug']);
    unset($this['sha1_password']);
    unset($this['portable_password']);
    unset($this['salt']);
    unset($this['score']);
    unset($this['spam_score']);
    unset($this['session_id']);
    unset($this['last_seen_at']);
    unset($this['eblob']);
    unset($this['created_at']);
    unset($this['updated_at']);

    parent::unsetFields();
  }
}
