<?php
/**
 * @var  $sf_user     cqFrontendUser
 * @var  $display     string
 * @var  $collection  Collection
 * @var  $pager       sfPropelPager
 */
?>

<div class="row-fluid">
  <div class="span8">
    <?= cq_page_title($collection, '<br/>by '. link_to_collector($collection->getCollector(), 'text')); ?>
  </div>
  <div class="span4" style="margin-top: 20px; text-align: center;">
    <br/><strong>Sharing widget goes here</strong>
  </div>
</div>
<div class="row-fluid">
  <div class="well">
    <?= cqStatic::linkify($collection->getDescription('html')); ?>
    <a href="#">Read the whole text</a>
  </div>
</div>

<div id="collectibles" class="row">
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
