<?php foreach ($collectors as $collector): ?>
  <div id="sidebar_collector_<?= $collector->getId(); ?>" class="span-6 collector last">
    <div class="stack">
      <?= link_to_collector($collector, 'image', array('width' => 50, 'height' => 50)); ?>
    </div>
    <div class="caption">
      <?php
        echo sprintf(
          '%s by %s',
          link_to_collector($collector, 'text', array('truncate' => 50)),
          link_to_collector($collector, 'text', array('truncate' => 17))
        );
      ?>
    </div>
  </div>
  <br clear="all">
<?php endforeach; ?>
