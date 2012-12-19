<?php

require 'lib/model/om/BaseCollectorIdentifier.php';

class CollectorIdentifier extends BaseCollectorIdentifier
{

  protected $identifiers = array(
    'facebook',
    'google',
    'live',
    'aol',
    'twitter',
    'yahoo',
  );

  public function getProviderFromIdentifier()
  {
    foreach ($this->identifiers as $identifier)
    {
      if (false !== stripos($this->getIdentifier(), $identifier))
      {
        return $identifier;
      }
    }

    return null;
  }

}
