<?php
/**
 * @var $collectible Collectible
 * @var $buyer_feedback ShoppingOrderFeedback
 */
if ($buyer_feedback = $collectible->getShoppingOrderFeedbackFromBuyer($sf_user->getCollector()))
{
  ?>
<tr>
  <td>Feedback:</td>
  <td>
  <?php
    echo '<span class="label rate_'. strtolower($buyer_feedback->getRating()) .'">'.
            $buyer_feedback->getRating() .
         '</span> ';
    echo $buyer_feedback->getComment();
  ?>
  </td>
</tr>
<?php
}
elseif ($buyer_feedback = $collectible->getShoppingOrderFeedbackFromBuyer($sf_user->getCollector(), false))
{
 ?>
<tr>
  <td>Feedback:</td>
  <td>
    <?= link_to('Leave Feedback', '@mycq_collectible_feedback_leave?id='. $buyer_feedback->getId()); ?>
  </td>
</tr>
<?php
}

