<?php
/*
 * @var $height   stdClass
 * @var $_height  integer
 * @var $title    string
 */
$_height = 0;

?>
<div class="mobile-optimized-300 center">
  <?php
    cq_sidebar_title('More HISTORY');
    $_height -= 63;
  ?>

  <a class="block spacer-bottom" href="http://www.history.com/shows/cajun-pawn-stars"
     title="Cajun Pawn Stars" target="_blank">
    <?php echo cq_image_tag('headlines/cps_more_history_promo.jpg', array('alt'=>'Cajun Pawn Stars'));?>
  </a>
</div>

<?php
  $_height -= 165;

  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
