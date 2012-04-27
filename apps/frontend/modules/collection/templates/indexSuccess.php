<?php
/**
 * @var  $sf_user     cqFrontendUser
 * @var  $display     string
 * @var  $collection  Collection
 * @var  $pager       sfPropelPager
 */
?>

<?php cq_page_title($collection); ?>

<div class="row-fluid" style="margin-top: 10px;">
  <div style="background-color: #e6f2f9; padding: 10px 0 10px 15px; font-size: 85%;">
    By <?= link_to_collector($collection->getCollector()); ?> &nbsp;|&nbsp;
    <?= $collection->getNumItems(); ?> Collectibles &nbsp;|&nbsp;
    <?= number_format($collection->getNumViews()); ?> Views

    <div class="pull-right" style="margin-top: -2px; margin-right: 5px;">
      <span class='st_email_button' displayText="Mail"></span>
      <span class='st_facebook_button'></span>
      <span class='st_twitter_button'></span>
      <span class='st_pinterest_button'></span>
    </div>
  </div>
</div>

<?php if ($pager->getPage() === 1): ?>
<div class="cf" style="margin-top: 20px;">
  <?= cqStatic::linkify($collection->getDescription('html')); ?>
</div>
<?php endif; ?>

<br/>
<div class="row">
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
