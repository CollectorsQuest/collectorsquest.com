<?php /** @var $collector Collector */ ?>

<div id="collector_<?= $collector->getId(); ?>_grid_view"
     data-id="<?= $collector->getId(); ?>" class="collector_grid_view_compact">

  <div class="row-fluid link">
    <div class="span3">
      <?php
      echo link_to_collector(
        $collector, 'image', array(),
        array(
          'max_width'  => 64,
          'max_height' => 64
        )
      );
      ?>
    </div>
    <div class="span9">
      <h2 style="margin-bottom: 5px;">
        <?= link_to_collector($collector, 'text', array('class' => 'target')); ?>
      </h2>
      <ul style="list-style: none; margin-left: 0;">
        <?php if ($collectionsCount = $collector->countCollections() and $collectiblesCount = $collector->countCollectibles()): ?>
        <li>
          <?= format_number_choice(
          '[1] has <b>1</b> collection|(1,+Inf] has <b>%1%</b> collections',
          array('%1%' => number_format($collectionsCount)),
          $collectionsCount
        );
          ?>
        </li>
        <li>
          <?= format_number_choice(
          '[1] with <b>1</b> collectible|(1,+Inf] with <b>%1%</b> collectible',
          array('%1%' => number_format($collectiblesCount)),
          $collectiblesCount
        );
          ?>
        </li>
        <?php else: ?>
        <?php if (time() > strtotime('+1 year', $collector->getCreatedAt('U'))): ?>
          <li><?= sprintf('member since %s', $collector->getCreatedAt('Y'))?></li>
          <?php elseif ((time() - $collector->getCreatedAt('U')) < 86400): ?>
          <li>joined <b>today</b></li>
          <?php elseif ((time() - $collector->getCreatedAt('U')) < 172800): ?>
            <li>joined <b>yesterday</b></li>
          <?php else: ?>
          <li><?= sprintf('joined <b>%s</b> ago', time_ago_in_words($collector->getCreatedAt('U'))); ?></li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>
