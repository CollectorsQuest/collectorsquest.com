<?php
/**
 * @var $collection CollectorCollection
 * @var $height stdClass
 */

$_height = 0;
?>

<div class="well">
  <div class="row-fluid">
    <div class="span8">
      <ul class="nav nav-list spacer-inner-left-reset">
        <li class="nav-header">Owner Options:</li>
        <li>
          <a rel="nofollow" href="<?= url_for('mycq_collection_by_section', array('id' => $collection->getId(), 'section' => 'details')); ?>">
            <i class="icon-edit"></i>
            Edit Collection
          </a>
        </li>
        <li>
          <a href="<?=url_for('@ajax_collection?section=component&page=collectiblesReorder') . '?id=' . $collection->getId()?>" onclick="ajax_load('#main', this.href); return false;">
            <i class="icon-move"></i>
            Reorder Items
          </a>
        </li>
      </ul>
    </div>
    <div class="span4">
      <i class="wrench-well-icon pull-right spacer-top"></i>
    </div>
  </div>
</div>

<?php $_height -= 94; ?>

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>

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
