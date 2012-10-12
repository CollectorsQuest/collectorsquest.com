<?php
require_once dirname(__FILE__) . '/RateTableBehaviorObjectBuilderModifier.php';
require_once dirname(__FILE__) . '/RateTableBehaviorPeerBuilderModifier.php';

class RateTableBehavior extends Behavior
{
  protected $parameters = array(
    'max_rate'        => 5,
    'dimensions'        => null,
    'rate_table'        => null,
    'user_table'        => null,
  );
  protected
  $peerBuilderModifier,
    $dimensions = array(),
    $userTable,
    $rateTable;

  public function getObjectBuilderModifier()
  {
    if (is_null($this->objectBuilderModifier))
    {
      $this->objectBuilderModifier = new RateTableBehaviorObjectBuilderModifier($this);
    }

    return $this->objectBuilderModifier;
  }

  public function getPeerBuilderModifier()
  {
    if (is_null($this->peerBuilderModifier))
    {
      $this->peerBuilderModifier = new RateTableBehaviorPeerBuilderModifier($this);
    }

    return $this->peerBuilderModifier;
  }

}
