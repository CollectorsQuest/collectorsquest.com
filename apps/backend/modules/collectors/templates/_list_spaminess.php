<?php
  $spam_score = (float) $Collector->getSpamScore();

  if ($spam_score == 0)
  {
    echo '<center><span class="label">', $spam_score, '</span></center>';
  }
  else if ($spam_score <= 30)
  {
    echo '<center><span class="label success">', $spam_score, '%</span></center>';
  }
  else if ($spam_score <= 60)
  {
    echo '<center><span class="label warning">', $spam_score, '%</span></center>';
  }
  else
  {
    echo '<center><span class="label important">', $spam_score, '%</span></center>';
  }
?>
