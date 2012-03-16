<?php /** @var $collectible Collectible */ ?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view" class="collectible_grid_view">
  <?= link_to_collectible($collectible, 'image', array('width' => 190, 'height' => 150)); ?>
  <div class="cover">
    <p><?= link_to_collectible($collectible, 'text'); ?></p>
  </div>
</div>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(document).ready(function()
{
  $('.collectible_grid_view').ready(function()
  {
    $("a, img", this).attr('title', '').attr('alt', '');
  });

  $('.collectible_grid_view').hover(function()
  {
    $(this).attr('title', '');
    $(".cover", this).stop().animate({top: '85px'}, {queue: false, duration: 160});
  },
  function()
  {
    $(".cover", this).stop().animate({top: '150px'}, {queue: false, duration: 160});
  });
});
</script>
<?php cq_end_javascript_tag(); ?>
