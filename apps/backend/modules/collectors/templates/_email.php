<?php

/* @var $Collector Collector */
echo $Collector->getEmail();

if ($Collector->getPreferencesNewsletter())
{
  echo '<i class="icon-envelope pull-right" title="Subscribed to newsletter"></i>';
}