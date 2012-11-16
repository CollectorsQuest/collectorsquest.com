<?php
require_once dirname(__FILE__) . '/RatingTableBehaviorObjectBuilderModifier.php';
require_once dirname(__FILE__) . '/RatingTableBehaviorPeerBuilderModifier.php';

class RatingTableBehavior extends Behavior
{
  protected $parameters = array(
    'max_rating'        => 5,
    'dimensions'        => null,
    'rating_table'        => null,
    'user_table'        => null,
  );
  protected
  $peerBuilderModifier,
    $dimensions = array(),
    $userTable,
    $ratingTable;

  public function getObjectBuilderModifier()
  {
    if (is_null($this->objectBuilderModifier))
    {
      $this->objectBuilderModifier = new RatingTableBehaviorObjectBuilderModifier($this);
    }

    return $this->objectBuilderModifier;
  }

  public function getPeerBuilderModifier()
  {
    if (is_null($this->peerBuilderModifier))
    {
      $this->peerBuilderModifier = new RatingTableBehaviorPeerBuilderModifier($this);
    }

    return $this->peerBuilderModifier;
  }

}
