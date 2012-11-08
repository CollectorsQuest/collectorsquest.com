<?php
/*
 * @var $height   stdClass
 * @var $_height  integer
 * @var $title    string
 */
$_height = 0;

cq_sidebar_title('More HISTORY');
$_height -= 63;
?>

<a class="block spacer-bottom" href="http://www.history.com/shows/cajun-pawn-stars"
   title="Cajun Pawn Stars" target="_blank">
  <?php echo cq_image_tag('headlines/cps_more_history_promo.jpg', array('alt'=>'Cajun Pawn Stars'));?>
</a>

<a class="block spacer-bottom" href="http://www.history.com/shows/american-restoration"
   title="American Restoration" target="_blank">
  <?php echo cq_image_tag('headlines/ar_more_history_promo.jpg', array('alt'=>'American Restoration'));?>
</a>

<a class="block spacer-bottom" href="http://www.history.com/shows/counting-cars"
   title="Counting Cars" target="_blank">
  <?php echo cq_image_tag('headlines/counting_cars_300x45.jpg', array('alt'=>'Counting Car'));?>
</a>

<?php
  $_height -= 165;

  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
