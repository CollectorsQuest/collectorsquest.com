<?php

require 'lib/model/om/BaseCollectorProfilePeer.php';

class CollectorProfilePeer extends BaseCollectorProfilePeer
{
  public static $collector_types = array(
    'casual' => 'Casual',
    'occasional' => 'Occasional',
    'serious' => 'Serious',
    'obsessive' => 'Obsessive',
    'expert' => 'Expert'
  );
}
