<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(document).ready(function()
{
  $("#header-actions a").bigTarget({
    hoverClass: 'active',
    clickZone : 'div:eq(0)'
  });

  $('.editable').editable('<?= url_for('@ajax_editable'); ?>',
  {
    cancel: '<?= __('Cancel'); ?>',
    submit: '<?= __('Save'); ?>',
    indicator: '<img src="/images/loading.gif"/>',
    tooltip: '<?= __('Click to edit...'); ?>'
  });
  $('.editable_h1').editable('<?= url_for('@ajax_editable'); ?>',
  {
    indicator: '<img src="/images/loading.gif"/>',
    tooltip: '<?= __('Click to edit...'); ?>'
  });
  $('.editable_html').editable('<?= url_for('@ajax_editable'); ?>',
  {
    loadurl: '<?= url_for('@ajax_editable_load'); ?>',

    type: 'autogrow',
    cancel: '<?= __('Cancel'); ?>',
    submit: '<?= __('Save'); ?>',
    indicator: '<img src="/images/loading.gif"/>',
    tooltip: '<?= __('Click to edit...'); ?>',
    onblur: "ignore",
    autogrow: {
      lineHeight: 16,
      minHeight: 50
    }
  });
  $('.editable_textarea').editable('<?= url_for('@ajax_editable'); ?>',
  {
    type: 'autogrow',
    cancel: '<?= __('Cancel'); ?>',
    submit: '<?= __('Save'); ?>',
    indicator: '<img src="/images/loading.gif"/>',
    tooltip: '<?= __('Click to edit...'); ?>',
    onblur: "ignore",
    autogrow: {
      lineHeight: 16,
      minHeight: 32
    }
  });
});

function fancybox_collection_add_collectibles(collection_id)
{
  $.fancybox(
  {
    href: '<?php echo url_for('@ajax_collection?section=partial&page=collectibles_upload'); ?>?id=' + collection_id,
    type: 'ajax',
    'hideOnContentClick': false,
    'hideOnOverlayClick': false,
    overlayOpacity: 0.5,
    autoDimensions: false,
    width: 410, height: 215, padding: 20,
    enableEscapeButton: false,
    centerOnScroll: true,
    titlePosition: 'inside',
    titleFormat: function () { return '<?= __('Please click on the BROWSE button and select collectible images!'); ?>'; }
  });
}

function fancybox_collection_edit(collection_id)
{
  return cq_not_implemented_yet();

  $.fancybox(
  {
    href: '<?php echo url_for('@homepage'); ?>',
    type: 'ajax',
    'hideOnContentClick': false,
    overlayOpacity: 0.2,
    autoDimensions: true,
    padding: 20,
    enableEscapeButton: true,
    centerOnScroll: true
  });
}

function fancybox_collection_choose_category()
{
  return cq_not_implemented_yet();
}

function ajax_collectible_delete(id)
{
  $.ajax(
  {
    url: "<?php echo url_for('@ajax_collection?section=collectible&page=delete') ?>?collectible_id=" + id,
    success: function()
    {
      $('#grid_view_collectible_' + id).fadeOut();
    }
  });

  return false;
}

function ajax_collectible_rotate(id)
{
  $.ajax(
  {
    url: "<?php echo url_for('@ajax_collection?section=collectible&page=rotate') ?>?collectible_id=" + id,
    success: function()
    {
      $('#grid_view_collectible_' + id +' img.thumbnail').rotateRight();
    }
  });

  return false;
}

</script>
<?php cq_end_javascript_tag(); ?>
