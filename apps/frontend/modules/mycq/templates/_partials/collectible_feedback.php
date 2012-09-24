<?php
/**
 * @var $collectible Collectible
 * @var $collector_rating CollectorRating
 */
if ($collector_rating = $collectible->getRateFromCollector($sf_user->getCollector()))
{
  ?>
<tr>
  <td>Feedback:</td>
  <td>
  <?php
    echo '<span class="label rate_'.strtolower($collector_rating->getRate()).'">'
            .$collector_rating->getRate().'</span> ';
    echo $collector_rating->getComment();
  ?>
  </td>
</tr>
<?php
}
elseif ($collector_rating = $collectible->getRateFromCollector($sf_user->getCollector(), false))
{
 ?>
<tr>
  <td>Feedback:</td>
  <td>
    <?= link_to('Leave Feedback', '@mycq_collectible_feedback_leave?id='.$collector_rating->getId()); ?>
  </td>
</tr>
<?php
}







