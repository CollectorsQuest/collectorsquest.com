<?php /** @var $collector Collector */ ?>

<div id="collector_<?= $collector->getId(); ?>_grid_view"
     data-id="<?= $collector->getId(); ?>" class="collector_grid_view">

  <div class="row-fluid link">
    <div class="span9">
      <div class="row-fluid profile-info">
        <div class="span3">
          <?= link_to_collector($collector, 'image', array('width' => 64, 'height' => '64')); ?>
        </div>
        <div class="span9">
          <h2 style="margin-bottom: 5px;">
            <?= link_to_collector($collector, 'text', array('class' => 'target')); ?>
          </h2>
          <ul>
            <li>
            <?php
              echo sprintf(
                '%s %s collector',
                in_array(strtolower(substr($collector->getCollectorType(), 0, 1)), array('a', 'e', 'i', 'o')) ? 'An' : 'A',
                '<strong>'. $collector->getCollectorType() .'</strong>'
              );
            ?>
            </li>
            <li>
              From <?= $collector->getProfile()->getCountry(); ?>
            </li>
          </ul>
        </div>
      </div>
      <div class="span12 about">
        <?php
          echo !empty($excerpt) ?
            $excerpt :
            cqStatic::truncateText($collector->getProfile()->getAboutMe(), 140, '...', true);
        ?>
      </div>
    </div>
    <div class="span3">
      <span class="stat-area spacer-bottom">
        <?php
          $count = $collector->countCollections();
          echo format_number_choice(
            '[0] No <span>COLLECTIONS</span>|[1] 1 <span>COLLECTION</span>|(1,+Inf] %1% <span>COLLECTIONS</span>',
            array('%1%' => number_format($count)), $count
          );
        ?>
      </span>
      <span class="stat-area">
        <?php
          $count = $collector->countCollectibles();
          echo format_number_choice(
            '[0] No <span>COLLECTIBLES</span>|[1] 1 <span>COLLECTIBLE</span>|(1,+Inf] %1% <span>COLLECTIBLES</span>',
            array('%1%' => number_format($count)), $count
          );
        ?>
      </span>
    </div>
  </div>
</div>
