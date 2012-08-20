<?php
/**
 * @var $title string
 * @var $collectors Collector[]
 */
?>

<?php
  $link = link_to('Browse profiles &raquo;', '@collectors', array('class' => 'text-v-middle link-align'));
  $link = null;

  cq_sidebar_title($title, $link, array('left' => 7, 'right' => 5));
?>

<?php foreach ($collectors as $collector): ?>
<div class="featured-sellers link">
  <div class="spotlight-inner">
    <div class="thumbnails-inner">
      <ul class="thumbnails">
        <?php
          $q = CollectibleForSaleQuery::create()
            ->filterByCollector($collector)
            ->isForSale();

          /** @var $collectibles_for_sale CollectibleForSale[] */
          $collectibles_for_sale = $q->limit(5)->find();

          foreach ($collectibles_for_sale as $collectible_for_sale)
          {
            echo '<li class="span2">';
            echo link_to(
              image_tag_collectible(
                $collectible_for_sale->getCollectible(), '75x75',
                array('max_width' => 54, 'max_height' => 54)
              ),
              url_for_collectible($collectible_for_sale->getCollectible()),
              array('class' => 'thumbnail', 'title' => $collectible_for_sale->getName())
            );
            echo '</li>';
          }
        ?>
      </ul>
    </div><!-- /.thumbnails-inner -->
    <div class="seller">
      <div class="row-fluid">
        <div class="span3">
          <?php
            echo link_to_collector($collector, 'image', array(
                'image_tag' => array('max_width' => '60', 'max_height' => 60)
            ));
          ?>
        </div>
        <div class="span9">
          <?php if ($text = $collector->getProfile()->getProperty('about.what_you_sell')): ?>
          <p><?= link_to_collector($collector, 'text'); ?> sells <?= $text; ?>.</p>
          <?php endif; ?>
          <a href="<?= url_for_collector($collector, true); ?>">
            Visit <?= $collector->getDisplayName() ?>’s page &raquo;
          </a>
        </div>
      </div>
    </div><!-- /.seller -->
  </div><!-- /.spotlight-inner -->
</div><!-- /#spotlight-sidebar -->
<?php endforeach; ?>
