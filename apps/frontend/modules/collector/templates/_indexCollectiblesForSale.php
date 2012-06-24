<?php
/**
 * @var $sf_user cqFrontendUser
 * @var $collector Collector
 * @var $pager sfPropelPager
 */
?>

<?php
  $link = $sf_user->isOwnerOf($collector) ? link_to('Edit Market', '@mycq_marketplace', array('class' => 'text-v-middle link-align')) .'&nbsp; | &nbsp;' : null ;
  $link = $link . link_to('See all &raquo;', '@marketplace', array('class' => 'text-v-middle link-align'));
  $link = null;

  cq_section_title($collector->getDisplayName() ."'s Collectibles for Sale", $link);
?>

<div id="user-collectibles-for-sale">
  <div class="row thumbnails">
    <?php
      foreach ($pager->getResults() as $i => $collectible_for_sale)
      {
        include_partial(
          'marketplace/collectible_for_sale_grid_view_square_small',
          array('collectible_for_sale' => $collectible_for_sale, 'i' => $i)
        );
      }
    ?>
  </div>

  <?php if ($pager->getPage() > 1 && $pager->getPage() < $pager->getLastPage()): ?>
  <div class="well clearfix">
    <i class="icon icon-search"></i>&nbsp;
    <?php
      echo link_to(
        sprintf('Want to see more? Click here for all collectibles for sale from %s!', $collector->getDisplayName()),
        '@collections_by_collector?collector_id='. $collector->getId() .'&collector_slug='. $collector->getSlug()
      );
    ?>
  </div>
  <?php elseif ($pager->getPage() == 1 && $pager->haveToPaginate()): ?>
  <button id="seemore-collectibles-for-sale" class="btn btn-small see-more-full">
    See more
  </button>

  <script>
    $(document).ready(function()
    {
      $('#seemore-collectibles-for-sale').click(function()
      {
        var $url = '<?= url_for('@ajax_collector?section=component&page=indexCollectiblesForSale&id='. $collector->getId(), false); ?>';
        var $button = $(this);

        $button.html('loading...');

        $("<div>").load($url +'&p=2 #user-collectibles-for-sale', function()
        {
          $('#user-collectibles-for-sale').append($(this).find('#user-collectibles-for-sale').html());
          $button.hide();
        });
      });
    });
  </script>
  <?php endif; ?>
</div>

