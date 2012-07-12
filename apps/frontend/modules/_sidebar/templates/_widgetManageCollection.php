<?php
/**
 * @var $collection CollectorCollection
 */
?>

<div class="well">
  <div class="row-fluid">
    <div class="span8">
      <ul class="nav nav-list spacer-inner-left-reset">
        <li class="nav-header">Owner Options:</li>
        <li>
          <a rel="nofollow" href="<?= url_for('mycq_collection_by_slug', $collection); ?>">
            <i class="icon-edit"></i>
            Edit Collection
          </a>
        </li>
        <li>
          <a href="<?=url_for('@ajax_collection?section=component&page=collectiblesReorder') . '?id=' . $collection->getId()?>" onclick="ajax_load('#main', this.href); return false;">
            <i class="icon-move"></i>
            Reorder Collectibles
          </a>
        </li>
      </ul>
    </div>
    <div class="span4">
      <i class="wrench-well-icon pull-right spacer-top"></i>
    </div>
  </div>
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
