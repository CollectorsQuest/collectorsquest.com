<div id="grid_view_collectible_<?php echo  $collectible->getId(); ?>" class="span-4 grid_view_collectible">
  <?php
    if (isset($editable) && $editable == true)
    {
      echo image_tag_collectible($collectible, '150x150');
    }
    else
    {
      echo link_to_collectible($collectible, 'image');
    }
  ?>

  <?php if (isset($editable) && $editable == true): ?>
  <div class="cover" style="padding-top: 15px;">
    <?php
      echo link_to_function(
        image_tag('icons/black/delete.png', array('style' => 'margin-right: 5px;')),
        sprintf('ajax_collectible_delete(%d)', $collectible->getId()),
        array('confirm'=>'Are you sure to delete this item?')
      );
    ?>
    <?php
      echo link_to(
        image_tag('icons/black/manage.png', array('style' => 'margin-right: 5px;')),
        sprintf('@manage_collectible_by_slug?id=%d&slug=%s', $collectible->getId(), $collectible->getSlug())
      );
    ?>
    <?php
      echo link_to_function(
        image_tag('icons/black/rotate.png'),
        sprintf('ajax_collectible_rotate(%d)', $collectible->getId())
      );
    ?>
  </div>
  <?php elseif (!$collectible->getIsNameAutomatic()): ?>
  <div class="cover">
    <p><?php echo  link_to_collectible($collectible, 'text'); ?></p>
  </div>
  <?php endif; ?>
</div>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(document).ready(function()
{
  <?php if (!isset($editable) || $editable != true): ?>
  $(".grid_view_collectible a").bigTarget(
  {
    hoverClass: 'pointer',
    clickZone : 'div:eq(0)'
  });
  <?php endif; ?>

  $('.grid_view_collectible').ready(function()
  {
    $("a, img", this).attr('title', '').attr('alt', '');
  });

  $('.grid_view_collectible').hover(function()
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
