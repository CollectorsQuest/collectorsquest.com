<style type="text/css">
.ui-state-active, .ui-widget-content .ui-state-active {
  background: #fff;
}
.ui-tabs .ui-tabs-panel {
  padding: 30px 0 0 0;
}
</style>

<div id="search-tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all" style="margin-top: -5px; margin-right: -5px; background: none; border: none;">
  <ul class="ui-tabs ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
    <?php if (!empty($collectibles) && is_array($collectibles)): ?>
      <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#search-tab-collectibles"><?= __('Collectibles'); ?> <small>(<?= $totals['collectibles']; ?>)</small></a></li>
    <?php endif; ?>
    <?php if (!empty($collections) && is_array($collections)): ?>
      <li class="ui-state-default ui-corner-top ui-state-active"><a href="#search-tab-collections"><?= __('Collections'); ?> <small>(<?= $totals['collections']; ?>)</small></a></li>
    <?php endif; ?>
    <?php if (!empty($collectors) && is_array($collectors)): ?>
      <li class="ui-state-default ui-corner-top ui-state-active"><a href="#search-tab-collectors"><?= __('Collectors'); ?> <small>(<?= $totals['collectors']; ?>)</small></a></li>
    <?php endif; ?>
    <?php if (!empty($blog) && is_array($blog)): ?>
      <li class="ui-state-default ui-corner-top ui-state-active"><a href="#search-tab-blog"><?= __('Blog Articles'); ?> <small>(<?= $totals['blog']; ?>)</small></a></li>
    <?php endif; ?>
    <!--
      <li><a href="#search-tab-events"><?= __('Events'); ?> <small>(15)</small></a></li>
    //-->
	 </ul>
  <?php if (!empty($collectibles) && is_array($collectibles)): ?>
	<div id="search-tab-collectibles">
    <div id="collectibles">
      <?php
        foreach ($collectibles as $i => $collectible)
        {
          // Show the collectible (in grid, list or hybrid view)
          include_partial(
            'collection/grid_view_collectible',
            array(
              'collectible' => $collectible,
              'culture' => $sf_user->getCulture(), 'i' => $i
            )
          );
        }
      ?>
    </div>

    <br clear="all"/>
    <?php
      include_partial(
        'global/pager',
        array('pager' => $pagers['collectibles'], 'options' => array('url' => '@search_collectibles?q='. $q))
      );
    ?>
	</div>
  <?php endif; ?>
  <?php if (!empty($collections) && is_array($collections)): ?>
  <div id="search-tab-collections">
    <div id="collections">
      <?php
        foreach ($collections as $i => $collection)
        {
          // Show the collection (in grid, list or hybrid view)
          include_partial(
            'collections/grid_view_collection',
            array(
              'collection' => $collection,
              'culture' => $sf_user->getCulture(),
              'i' => $i
            )
          );

          echo (($i + 1) % 3 == 0) ? '<br clear="all">' : null;
        }
      ?>
    </div>

    <br clear="all"/>
    <?php
      include_partial(
        'global/pager',
        array('pager' => $pagers['collections'], 'options' => array('url' => '@search_collections?q='. $q))
      );
    ?>
  </div>
  <?php endif; ?>
  <?php if (!empty($collectors) && is_array($collectors)): ?>
	<div id="search-tab-collectors">
    <div id="collectors">
      <?php
        foreach ($collectors as $i => $collector)
        {
          // Show the collection (in grid, list or hybrid view)
          include_partial(
            'collectors/grid_view_collector',
            array(
              'collector' => $collector,
              'culture' => $sf_user->getCulture(),
              'i' => $i
            )
          );

          echo (($i + 1) % 2 == 0) ? '<br clear="all">' : null;
        }
      ?>
    </div>

    <br clear="all"/>
    <?php
      include_partial(
        'global/pager',
        array('pager' => $pagers['collectors'], 'options' => array('url' => '@search_collectors?q='. $q))
      );
    ?>
  </div>
  <?php endif; ?>
  <?php if (!empty($blog) && is_array($blog)): ?>
	<div id="search-tab-blog">
    <div id="blog">
      <?php
        foreach ($blog as $i => $post)
        {
          // Show the collection (in grid, list or hybrid view)
          include_partial(
            '_blog/list_view_post',
            array('post' => $post, 'culture' => $sf_user->getCulture(), 'i' => $i)
          );
        }
      ?>
    </div>

    <br clear="all"/>
    <?php
      include_partial(
        'global/pager',
        array('pager' => $pagers['blog'], 'options' => array('url' => '@search_blog?q='. $q))
      );
    ?>
  </div>

  <?php endif; ?>
  <!--
    <div id="search-tab-events">&nbsp;</div>
  //-->
</div>

<script type="text/javascript">
$(function()
{
  var current_tab = null;
  var opts = new Array(4);

  opts[0] = {
    navSelector: "#search-tab-collectibles div.pagination",
    nextSelector: "#search-tab-collectibles div.pagination span.next a",
    itemSelector: "div#search-collectibles",
    loading: {
      img: '/images/loading.gif',
      msgText: '<?= __('Loading the next page...'); ?>',
      finishedMsg: '<?= __('No more pages to load'); ?>'
    },
    loadingMsgRevealSpeed: 0,
    bufferPx: 80,
    extraScrollPx: 0,
    debug: false,
    animate: false
  };

  opts[1] = {
    navSelector: "#search-tab-collections div.pagination",
    nextSelector: "#search-tab-collections div.pagination span.next a",
    itemSelector: "div#search-collections",
    loading: {
      img: '/images/loading.gif',
      msgText: '<?= __('Loading the next page...'); ?>',
      finishedMsg: '<?= __('No more pages to load'); ?>'
    },
    loadingMsgRevealSpeed: 0,
    bufferPx: 80,
    extraScrollPx: 0,
    debug: false,
    animate: false
  };

  opts[2] = {
    navSelector: "#search-tab-collectors div.pagination",
    nextSelector: "#search-tab-collectors div.pagination span.next a",
    itemSelector: "div#search-collectors",
    loading: {
      img: '/images/loading.gif',
      msgText: '<?= __('Loading the next page...'); ?>',
      finishedMsg: '<?= __('No more pages to load'); ?>'
    },
    loadingMsgRevealSpeed: 0,
    bufferPx: 80,
    extraScrollPx: 0,
    debug: false,
    animate: false
  };

  opts[3] = {
    navSelector: "#search-tab-blog div.pagination",
    nextSelector: "#search-tab-blog div.pagination span.next a",
    itemSelector: "div#search-blog",
    loading: {
      img: '/images/loading.gif',
      msgText: '<?= __('Loading the next page...'); ?>',
      finishedMsg: '<?= __('No more pages to load'); ?>'
    },
    loadingMsgRevealSpeed: 0,
    bufferPx: 80,
    extraScrollPx: 0,
    debug: false,
    animate: false
  };

  var $tabs = <?= json_encode($tabs); ?>;
  $("#search-tabs").tabs(
  {
    show: function(event, ui)
    {
      if (current_tab != null)
      {
        opts[current_tab] = $.infinitescroll.opts;
      }

      switch ($tabs[ui.index])
      {
        <?php if ($pagers['collections']->haveToPaginate()): ?>
        case 'collectibles':
          $('#collectibles').infinitescroll(opts[0]);
        break;
        <?php endif; ?>
        <?php if ($pagers['collections']->haveToPaginate()): ?>
        case 'collections':
          $('#collections').infinitescroll(opts[1]);
        break;
        <?php endif; ?>
        <?php if ($pagers['collectors']->haveToPaginate()): ?>
        case 'collectors':
          $('#collectors').infinitescroll(opts[2]);
        break;
        <?php endif; ?>
        <?php if ($pagers['blog']->haveToPaginate()): ?>
        case 'blog':
          $('#blog').infinitescroll(opts[3]);
        break;
        <?php endif; ?>
      }

      current_tab = ui.index;
    }
  });

  $('div.pagination').hide();
});
</script>
