<?php
/* @var $pager sfPropelPager */
/* @var $sf_user cqUser */
/* @var $sf_request sfWebRequest */
$offset = 0;
$collections = $pager->getResults();
?>
<br clear="all" /><br />
<div id="collections">
  <?php foreach ($collections as $i => $collection): ?>
  <?php
  // Show the collection (in grid, list or hybrid view)
  include_partial(
    'collections/' . $display . '_view_collection',
    array(
      'collection' => $collection,
      'culture'    => $sf_user->getCulture(),
      'i'          => $i
    )
  ); ?>

  <?php if (0 == ($i + $offset + 1) % 3): ?>
    <br clear="all" />
    <?php endif; ?>
  <?php endforeach; ?>

  <br clear="all" />

</div>
<div id="collections-pager" class="span-19 last" style="margin-bottom: 25px">
  <?php include_partial('global/pager', array(
  'pager'  => $pager,
  'options'=> array('url'   => '@collections_by_filter?filter=' . $filter)
)); ?>
</div>

<?php if (!$sf_user->isAuthenticated()): ?>
<div class="span-19 append-bottom last">
  <?php cq_ad_slot('collectorsquest_com_-_After_Listing_728x90', '728', '90'); ?>
</div>
<?php endif; ?>
<?php if ('all' == $sf_request->getParameter('show')): ?>
<script type="text/javascript">
  $(function () {
    var opts =
    {
      navSelector:"div#collections-pager div.pagination",
      nextSelector:"div#collections-pager div.pagination:last span.next:last a",
      itemSelector:"div.<?php echo $display ?>_view_collection",
      contentSelector:'div#collections',
      loading:{
        img:'/images/loading.gif',
        msgText:'<?php echo __('Loading the next page...'); ?>',
        finishedMsg:'<?php echo __('No more pages to load'); ?>'
      },
      loadingMsgRevealSpeed:0,
      bufferPx:80,
      extraScrollPx:0,
      debug:false,
      animate:false
    };

    $('#collections').infinitescroll(opts);
    $('#collections-pager div.pagination').hide();
  });
</script>
<?php endif; ?>
