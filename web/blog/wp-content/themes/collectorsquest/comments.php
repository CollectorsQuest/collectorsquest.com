<?php

if (SF_APP === 'frontend')
{
  require_once "comments.new.php";
}
else
{
  require_once "comments.legacy.php";
}
