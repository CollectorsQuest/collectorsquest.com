<?php
/**
 * @var  $sf_user            cqFrontendUser
 * @var  $display            string
 * @var  $collection         Collection
 * @var  $pager              sfPropelPager
 * @var  $collectible_rows   integer
 */
$height_main_div = new stdClass;
$height_main_div->value = 116;
?>

<?php
  $options = array(
    'id' => sprintf('%s_%d_name', get_class($collection), $collection->getId()),
    'class' => isset($editable) && true === $editable ? 'row-fluid header-bar editable' : 'row-fluid header-bar'
  );

  cq_page_title($collection, null, $options);
?>

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
              '[0] no items yet|[1] 1 Item|(1,+Inf] %1% Items',
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
    <div id="social-sharing" class="pull-right share">
      <!-- AddThis Button BEGIN -->
      <a class="btn-lightblue btn-mini-social addthis_button_email">
        <i class="mail-icon-mini"></i> Email
      </a>
      <a class="addthis_button_pinterest_pinit" pi:pinit:media="<?= src_tag_collection($collection, 'original'); ?>" pi:pinit:layout="horizontal"></a>
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="75"></a>
      <!-- AddThis Button END -->
    </div>
  </div>
</div>

<?php if ($pager->getPage() === 1): ?>
<div class="cf spacer-top-20 <?= $editable ? 'editable_html' : '' ?>"
     id="<?= sprintf('%s_%s_description', get_class($collection), $collection->getId()) ?>">
  <?= $description = $collection->getDescription('html'); ?>
  <?php
  /**
   * Calculate height of description
   * We have around 100 symbols in a row
   */
  $description_rows = (integer) (strlen($description) / 100 + 1);

  // Approximately 2 <br> tags account for a new line
  $br_count = (integer) (substr_count($description, '<br') / 2);
  $height_main_div->value += 18 * ($br_count + $description_rows);
  ?>
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
          array('collectible_for_sale' => $collectible->getCollectibleForSale(), 'i' => (integer) $i)
        );
      }
      else
      {
        // Show the collectible (in grid, list or hybrid view)
        include_partial(
          'collection/collectible_grid_view_square',
          array('collectible' => $collectible,'i' => (integer) $i)
        );
      }
    }
  ?>
  </div>
</div>

<?php $height_main_div->value += $collectible_rows * 238; ?>

<div class="row-fluid text-center">
  <?php
  include_component(
    'global', 'pagination',
    array(
      'pager' => $pager,
      'height' => &$height_main_div,
      'options' => array(
        'id' => 'collectibles-pagination',
        'show_all' => true
      )
    )
  );
  ?>
</div>

<?php
  include_component(
    'comments', 'comments', array(
      'for_object' => $collection,
      'height' => &$height_main_div
  ));
?>

<?php $sf_user->setFlash('height_main_div', $height_main_div, false, 'internal'); ?>

<?php if ($sf_params->get('show') == 'all'): ?>
<script>
$(document).ready(function () {
  $('#collectibles').infinitescroll(
  {
    navSelector:'#collectibles-pagination',
    nextSelector:'#collectibles-pagination li.next a',
    itemSelector:'#collectibles .span4',
    loading:{
      msgText:'Loading more collectibles...',
      finishedMsg:'No more pages to load.',
      img:'<?= image_path('frontend/progress.gif'); ?>'
    },
    bufferPx:150
  },
  function () {
    $('.collectible_grid_view').mosaic({
      animation:'slide'
    });
  });

  // Hide the pagination before infinite scroll does it
  $('#collectibles-pagination').hide();
});
</script>
<?php endif; ?>
