<div class="row-fluid header-bar">
  <div class="span9">
    <h1 class="Chivo webfont" style="margin-left: 145px;"><?= $collector->getDisplayName() ?></h1>
  </div>
  <div class="span3 text-right">
    <?= ($sf_user->isOwnerOf($collector)) ? link_to('Edit Your Profile →', '@mycq_profile') : '&nbsp;'; ?>
  </div>
</div>

<div id="public-profile-info">
  <div class="row-fluid">
    <div class="span9">
      <div class="row-fluid profile-info">
        <div class="span4 thumbnail" style="margin-top: -55px; background: #fff;">
          <?= image_tag_collector($collector, '235x315', array('max_width' => 138, 'max_height' => 185)) ?>
        </div>
        <div class="span8">
          <ul style="margin-top: 10px;">
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
          <p><strong>Collecting:</strong>
            <?= $profile->getProperty('about.what_you_collect')?>
          </p>
        </div>
      </div>
    </div>
    <div class="span3">
      <span class="stat-area bottom-margin-double" style="padding-bottom: 5px;">
      <?php
        $count = $collector->countCollections();
        echo format_number_choice(
          '[0] No <span>COLLECTIONS</span>|[1] 1 <span>COLLECTION</span>|(1,+Inf] %1% <span>COLLECTIONS</span>',
          array('%1%' => number_format($count)), $count
        );
      ?>
      </span>
      <span class="stat-area" style="padding-bottom: 5px;">
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
