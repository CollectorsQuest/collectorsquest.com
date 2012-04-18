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
  <div class="well" style="background-color: #e6f2f9; padding: 15px 10px 12px 15px;">
    By <?= link_to_collector($collection->getCollector()); ?> &nbsp;|&nbsp;
    <?= $collection->getNumItems(); ?> Collectibles &nbsp;|&nbsp;
    <?= $collection->getNumViews(); ?> Views

    <div class="pull-right">
      <span class='st_email'></span>
      <span class='st_facebook'></span>
      <span class='st_twitter'></span>
      <span class='st_pinterest'></span>
      <span class='st_plusone'></span>
    </div>
  </div>
</div>

<blockquote>
  <?= cqStatic::linkify($collection->getDescription('html')); ?>
  <a href="#">Read the whole text</a>
</blockquote>

<div class="row">
  <div id="collectibles" class="row-content">
  <?php
    /** @var $collectible Collectible */
    foreach ($pager->getResults() as $i => $collectible)
    {
      echo '<div class="', ('list' == $display ? 'span6' : 'span4'), '">';
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/collectible_'. $display .'_view',
        array(
          'collectible' => $collectible,
          'culture' => (string) $sf_user->getCulture(),
          'i' => (int) $i
        )
      );
      echo '</div>';
    }
  ?>
  </div>
</div>

<div class="row-fluid" style="text-align: center;">
  <?php
  include_component(
    'global', 'pagination',
    array('pager' => $pager, 'options' => array('id' => 'collectibles-pagination'))
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
