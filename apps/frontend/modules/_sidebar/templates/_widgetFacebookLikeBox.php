<?php
/**
 * @var $height stdClass
 */

$_height = 0;
?>

<div id="side_facebook">
  <h4>
    <a href="https://www.facebook.com/pages/Collectors-Quest/119338990397" target="_blank">
      <?= cq_image_tag('icons/facebook.png', array('width'=>'20', 'height'=>'18', 'alt'=>'Facebook', 'class' => 'v-align'));?>
    </a>&nbsp;<?php echo __('Become fan of CollectorsQuest.com');?>
  </h4>
  <fb:like-box href="https://www.facebook.com/pages/Collectors-Quest/119338990397" header="false" width="309" height="340" border_color="#EFF0F2" stream="false" locale="<?php echo $sf_user->getCulture(); ?>"></fb:like-box>
</div>
<div class="clear">&nbsp;</div>

<?php
  $_height -= 19;
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
