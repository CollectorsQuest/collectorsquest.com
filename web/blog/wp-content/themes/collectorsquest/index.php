<?php

if ($_SERVER['HTTP_HOST'] == 'www.collectorsquest.next'||$_SERVER['HTTP_HOST'] == 'www.cqnext.com')
{
  require_once "index.new.php";
}
else
{
  require_once "index.old.php";
}

?>
