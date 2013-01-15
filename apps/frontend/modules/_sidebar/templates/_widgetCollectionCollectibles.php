<?php
/**
 * @var $collectibles Collectible[]
 * @var $pager        cqCollectionCollectiblesPager
 */

$_height = 0;

?>

<div class="carousel-sidebar sidebar-title items<?= $pager->getMaxPerPage() ?>" id="collectionCollectiblesWidget"
     data-page="<?= $pager->getPage() ?>"  data-lastpage="<?= $pager->getLastPage() ?>"
     data-url="<?= url_for('ajax_sidebar', array('section' => 'component', 'page' => 'widgetCollectionCollectibles')); ?>">

  <h3 class="Chivo webfont spacer-bottom-5">Other items in this collection:</h3>
  <div class="thumbnails-inner well">
    <?php if ($pager->haveToPaginate()): ?>
      <a href="javascript:void(0)" title="previous collectible" class="left-arrow">
        <i class="icon-chevron-left white"></i>
      </a>
      <a href="javascript:void(0)" title="next collectible" class="right-arrow">
        <i class="icon-chevron-right white"></i>
      </a>
    <?php endif; ?>
    <div id="carousel" class="thumbnails">
      <?php
        foreach ($pager->getResults() as $c)
        {
          if (isset($collectible) && $c->getId() === $collectible->getId())
          {
            slot('lastItem');
            echo link_to(
              '<span>'.$pager->getNbResults().'</span>'.'Items',
              'collection_by_slug', $pager->getCollection(), array('class' => 'moreItems')
            );
            end_slot();
          }
          else
          {
            echo link_to_collectible($c, 'image', array(
              'link_to' => array('class' => 'thumbnail'),
              'image_tag' => array('width' => 100, 'height' => 100, 'max_width' => 90, 'max_height' => 90)
            ));
          }
        }
      ?>
      <?php echo get_slot('lastItem') ?>
    </div>
  </div>
</div>

<?php
  $_height -= 165;

  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>


<script type="text/javascript">
  $(document).ready(function()
  {
    window.cq.settings = $.extend(true, {}, window.cq.settings, {
      collectionColletiblesWidget: {
        collection_id: '<?= $pager->getCollection()->getId(); ?>',
        collectible_id: '<?= $pager->getCollectibleId() ?>',
        limit: '<?= $pager->getMaxPerPage() ?>'
      }
    });
  });
</script>
