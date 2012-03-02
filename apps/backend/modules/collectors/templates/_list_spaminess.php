<?php
/**
 * @var $Collector Collector
 */

$spam_score = (float)$Collector->getSpamScore();

switch ($spam_score)
{
  case 0:
    $class = '';
    break;

  case $spam_score <= 30:
    $class = 'label-success';
    break;

  case $spam_score <= 60:
    $class = 'label-warning';
    break;

  case $spam_score > 60:
    $class = 'label-important';
    break;
}

?>
<span class="label <?php echo $class ?>"><?php echo $spam_score; ?>%</span>
