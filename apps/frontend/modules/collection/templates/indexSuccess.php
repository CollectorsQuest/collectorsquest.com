<?php
/**
 * @var  $sf_user     cqFrontendUser
 * @var  $display     string
 * @var  $collection  Collection
 * @var  $pager       sfPropelPager
 */
?>

<?php cq_page_title($collection); ?>


<div class="blue-actions-panel spacer-20">
  <div class="row-fluid">
    <div class="pull-left">
      <ul>
        <li>
          By <?= link_to_collector($collection->getCollector(), 'text'); ?>
          </li>
        <li>
          <?php
          echo format_number_choice(
            '[0] no collectibles yet|[1] 1 Collectible|(1,+Inf] %1% Collectibles',
            array('%1%' => number_format($collection->getNumItems())), $collection->getNumItems()
          );
          ?>
        </li>
        <li>
          <?php
          echo format_number_choice(
            '[0] no views yet|[1] 1 View|(1,+Inf] %1% Views',
            array('%1%' => number_format($collection->getNumViews())), $collection->getNumViews()
          );
          ?>
        </li>
      </ul>
    </div>
    <div class="pull-right share">
      <!-- AddThis Button BEGIN -->
      <a class="btn btn-lightblue btn-mini-social addthis_button_email">
        <i class="mail-icon-mini"></i> Email
      </a>
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="40"></a>
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
      <a class="addthis_button_pinterest_pinit" pi:pinit:media="<?= src_tag_collection($collection, 'original'); ?>" pi:pinit:layout="horizontal"></a>
      <!-- AddThis Button END -->
    </div>
  </div>
</div>

<?php if ($pager->getPage() === 1): ?>
<div class="cf" style="margin-top: 20px;">
  <?= cqStatic::linkify($collection->getDescription('html')); ?>
</div>
<?php endif; ?>

<div class="row spacer-top">
  <div id="collectibles" class="row-content">
  <?php
    /** @var $collectible Collectible */
    foreach ($pager->getResults() as $i => $collectible)
    {
      if ($collectible->isForSale())
      {
        // Show the collectible (in grid, list or hybrid view)
        include_partial(
          'marketplace/collectible_for_sale_grid_view_square',
          array('collectible_for_sale' => $collectible->getCollectibleForSale(), 'i' => (int) $i)
        );
      }
      else
      {
        // Show the collectible (in grid, list or hybrid view)
        include_partial(
          'collection/collectible_grid_view_square',
          array('collectible' => $collectible, 'i' => (int) $i)
        );
      }
    }
  ?>
  </div>
</div>

<div class="row-fluid" style="text-align: center;">
<?php
  include_component(
    'global', 'pagination',
    array('pager' => $pager, 'options' => array('id' => 'collectibles-pagination', 'show_all' => true))
  );
?>
</div>

<?php include_partial('comments/comments', array('for_object' => $collection)); ?>

<?php if ($sf_params->get('show') == 'all'): ?>
<script>
$(document).ready(function()
{
  $('#collectibles').infinitescroll(
    {
      navSelector: '#collectibles-pagination',
      nextSelector: '#collectibles-pagination li.next a',
      itemSelector: '#collectibles .span4',
      loading:
      {
        msgText: 'Loading more collectibles...',
        finishedMsg: 'No more pages to load.',
        img: '<?= image_path('frontend/progress.gif'); ?>'
      },
      bufferPx: 150
    },
    function()
    {
      $('.collectible_grid_view').mosaic({
        animation: 'slide'
      });
    });

  // Hide the pagination before infinite scroll does it
  $('#collectibles-pagination').hide();
});
</script>
<?php endif; ?>
