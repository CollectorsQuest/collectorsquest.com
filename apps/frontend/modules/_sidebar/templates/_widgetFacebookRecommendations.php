<?php
/**
 * @var $height stdClass
 */

$_height = 0;
?>

<div id="side_facebook">
  <h4><a href="http://www.facebook.com/Autohop" target="_blank"><?php echo cq_image_tag('icons/facebook.png', array('width'=>'20', 'height'=>'18', 'alt'=>'Facebook', 'class' => 'v-align transparent'));?></a>&nbsp;<?php echo __('Shared in Facebook');?></h4>
  <fb:recommendations site="autohop.bg" header="false" width="309" height="370" border_color="#EFF0F2" locale="<?php echo $sf_user->getCulture(); ?>" ref="video"></fb:recommendations>
</div>
<div class="clear">&nbsp;</div>

<?php
  $_height -= 19;
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
