<?php

if (SF_APP === 'frontend')
{
  require_once "index.new.php";
}
else
{
  require_once "index.legacy.php";
}
