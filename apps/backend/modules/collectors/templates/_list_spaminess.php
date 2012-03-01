<?php
  /**
   * @var $Collector Collector
   */

  $spam_score = (float) $Collector->getSpamScore();

  if ($spam_score == 0)
  {
    echo '<center><span class="label">', $spam_score, '</span></center>';
  }
  else if ($spam_score <= 30)
  {
    echo '<center><span class="label label-success">', $spam_score, '%</span></center>';
  }
  else if ($spam_score <= 60)
  {
    echo '<center><span class="label label-warning">', $spam_score, '%</span></center>';
  }
  else
  {
    echo '<center><span class="label label-important">', $spam_score, '%</span></center>';
  }
?>
