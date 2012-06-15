<?php
/**
 * @var $collection CollectorCollection
 */
?>

<div class="well" style="padding: 8px 0;">
  <ul class="nav nav-list">
    <li class="nav-header">Owner Options:</li>
    <li>
      <a rel="nofollow" href="<?= url_for('mycq_collection_by_slug', $collection); ?>">
        <i class="icon-edit"></i>
        Edit Collection
      </a>
    </li>
    <li>
      <a href="<?=url_for('@ajax_collection?section=component&page=collectiblesReorder') . '?id=' . $collection->getId()?>" onclick="ajax_load('#main', this.href); return false;">
        <i class="icon-refresh"></i>
        Re-Order Collectibles
      </a>
    </li>
  </ul>
</div>
<script type="text/javascript">
  var ajax_load = function(target, url) {
    var $target = $(target);

    if ($target && url)
    {
      var pos = $target.offset();

//      $('#main').css(
//      {
//        "left": pos.left +"px",
//        "top":  pos.top  +"px"
//      });

      $('#main').showLoading();
//      $('#loading').height($target.height() + 10);
//      $('#loading').width($target.width());
//      $('#loading').show();

      $target.load(url, function()
      {
        $('#main').hideLoading();
      });

      return true;
    }

    return false;
  }
</script>
