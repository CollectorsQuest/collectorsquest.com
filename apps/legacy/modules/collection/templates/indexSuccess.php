<?php
/* @var $sf_user cqUser */
/* @var $collection Collection */
/* @var $pager sfPropelPager */
/* @var $display string */
/* @var $editable boolean */
/* @var $sf_request sfWebRequest */

use_javascript('jquery/carousel.js');
use_stylesheet('legacy/scrollable.css');
?>

<?php if ($sf_user->isOwnerOf($collection)): ?>
<span style="margin: 0 0 0 20px; color: #6699CC; font-weight: bold; font-size: 15px;">
    Click on an image below to edit the collectible information <?php echo $sf_user->hasCredential('seller') ? ' and add pricing.' : ''; ?>
  </span>
<?php endif; ?>
<div class="clear" style="height: 20px;">&nbsp;</div>
<div id="update_view_collectible">
  <?php
  foreach ($pager->getResults() as $i => $collectible)
  {
    // Show the collectible (in grid, list or hybrid view)
    include_partial(
      'collection/' . $display . '_view_collectible',
      array(
        'collection'  => $collection,
        'collectible' => $collectible,
        'editable'    => $editable,
        'culture'     => $sf_user->getCulture(),
        'i'           => $i
      )
    );
  }
  ?>
</div>

<br class="clear"><br>
<div id="collection-pager" class="span-19 last" style="margin-bottom: 25px">
  <?php
  include_partial(
    'global/pager',
    array(
      'pager'   => $pager,
      'options' => array(
        'url'    => url_for('collection_by_slug', $collection),
        'update' => 'update_view_collectible'
      )
    )
  );
  ?>
</div>

<?php if (!$sf_user->isAuthenticated()): ?>
<!--
<div class="span-19 append-bottom last">
  <?php cq_ad_slot('collectorsquest_com_-_After_Listing_728x90', '728', '90'); ?>
</div>
//-->
<?php endif; ?>

<?php
include_component('comments', 'commentForm', array('object' => $collection));
?>
<div id="wrapper-comments">
  <?php
  $commentsOptions = array('object'=> $collection);
  if ('all' == $sf_request->getParameter('show'))
  {
    $commentsOptions['order'] = 'desc';
    $commentsOptions['limit'] = 10;
  }
  include_component('comments', 'commentList', $commentsOptions);
  ?>
</div>

<?php if ('all' == $sf_request->getParameter('show') && $pager->haveToPaginate()): ?>
<script type="text/javascript">
  $(function () {
    var opts =
    {
      navSelector: "div#collection-pager div.pagination",
      nextSelector: "div#collection-pager div.pagination span.next a",
      itemSelector: "div.<?php echo $display ?>_view_collectible",
      loading: {
        img: '/images/loading.gif',
        msgText: '<?php echo __('Loading the next page...'); ?>',
        finishedMsg: '<?php echo __('No more pages to load'); ?>'
      },
      loadingMsgRevealSpeed: 0,
      bufferPx: 80,
      extraScrollPx: 0,
      debug: false,
      animate: false
    };

    $('#update_view_collectible').infinitescroll(opts);
    $('#collection-pager div.pagination').hide();
  });
</script>
<?php endif; ?>
