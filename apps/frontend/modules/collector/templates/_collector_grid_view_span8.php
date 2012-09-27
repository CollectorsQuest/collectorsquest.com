<?php /** @var $collector Collector */ ?>

<div id="collector_<?= $collector->getId(); ?>_grid_view"
     data-id="<?= $collector->getId(); ?>" class="collector_grid_view">

  <div class="row-fluid link">
    <div class="span9">
      <div class="row-fluid profile-info">
        <div class="span3">
          <?php
            echo link_to_collector($collector, 'image', array(
              'image_tag' => array('max_width' => 64, 'max_height' => '64')
            ));
          ?>
        </div>
        <div class="span9">
          <h2 class="spacer-bottom-5">
            <?php
              echo link_to_collector($collector, 'text', array(
                'link_to' => array('class' => 'target')
              ));
            ?>
          </h2>
          <ul style="list-style: none; margin-left: 0;">
            <li>
            <?php
              echo sprintf(
                'Is %s %s collector',
                in_array(strtolower(substr($collector->getCollectorType(), 0, 1)), array('a', 'e', 'i', 'o')) ? 'an' : 'a',
                '<strong>'. $collector->getCollectorType() .'</strong>'
              );
            ?>
            </li>
            <?php if ($country_iso3166 = $collector->getProfile()->getCountryIso3166()): ?>
            <li>
              Is from <?= ($country_iso3166 == 'US') ? 'the United States' : $collector->getProfile()->getCountryName(); ?>
            </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
      <div class="span12 about">
        <?php
          if (!empty($excerpt) && trim($excerpt) !== '.')
          {
            echo $excerpt;
          }
          else if ($collector->getProfile()->getAboutMe())
          {
            echo cqStatic::truncateText($collector->getProfile()->getAboutMe(), 140, '...', true);
          }
        ?>
      </div>
    </div>
    <div class="span3">
      <span class="stat-area spacer-bottom">
        <?php
          $q = FrontendCollectorCollectionQuery::create()
            ->filterByCollector($collector)
            ->hasCollectibles();
          $count = $q->count();

          echo format_number_choice(
            '[0] No <span>COLLECTIONS</span>|[1] 1 <span>COLLECTION</span>|(1,+Inf] %1% <span>COLLECTIONS</span>',
            array('%1%' => number_format($count)), $count
          );
        ?>
      </span>
      <span class="stat-area">
        <?php
          $q = FrontendCollectibleQuery::create()
            ->filterByCollector($collector)
            ->isPartOfCollection();
          $count = $q->count();

          echo format_number_choice(
            '[0] No <span>COLLECTIBLES</span>|[1] 1 <span>COLLECTIBLE</span>|(1,+Inf] %1% <span>COLLECTIBLES</span>',
            array('%1%' => number_format($count)), $count
          );
        ?>
      </span>
    </div>
  </div>
</div>
