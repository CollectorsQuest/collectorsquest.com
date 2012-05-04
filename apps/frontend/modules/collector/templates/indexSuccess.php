<?php cq_page_title('Collector Profile Page'); ?>

<div id="public-profile-info">
  <div class="row-fluid">
    <div class="span9">
      <div class="row-fluid profile-info">
        <div class="span4 thumbnail" style="margin-top: -55px; background: white;">
          <img src="http://placehold.it/135x200" alt="">
          <? image_tag_collector($collector, '130x130') ?>
        </div>
        <div class="span8">
          <h1>Robotbacon is a serious collector</h1>
          <p><strong>Collecting:</strong>
            <a href="#">Slide Puzzles</a>,
            <a href="#">Books on American History that center around the American Revolution</a>,
            <a href="#">Stone Eggs</a>,
            <a href="#">1920's and 1930's furniture</a>

            <!--
            Slide Puzzles, Books on American History that center around the American Revolution, Stone Eggs, 1920's and 1930's furniture
            -->

            <!--
            <a href="#" class="tags">Slide Puzzles</a>
            <a href="#" class="tags">Books on American History that center around the American Revolution</a>
            <a href="#" class="tags">Stone Eggs</a>
            <a href="#" class="tags">1920's and 1930's furniture</a>
            -->
          </p>
        </div>
      </div>
    </div>
    <div class="span3">
      <span class="stat-area bottom-margin-double">
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

<?php include_component('collector', 'indexCollectiblesForSale', array('collector' => $collector)) ?>

<?php include_component('collector', 'indexCollections', array('collector' => $collector)) ?>
