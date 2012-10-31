<?php
/* @var $PackageTransaction PackageTransaction */

if ($collector = $PackageTransaction->getCollector())
{
  echo $collector, "<br />\n";
  echo mail_to($collector->getEmail(), $collector->getEmail());
}



