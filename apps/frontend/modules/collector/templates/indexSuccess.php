<?php
/**
 * @var $sf_user cqFrontendUser
 * @var $collector Collector
 * @var $profile CollectorProfile
 */
?>

<div class="row-fluid header-bar">
  <div class="span9">
    <h1 class="Chivo webfont" style="margin-left: 145px;">
      <?= $collector->getDisplayName(); ?>
    </h1>
  </div>
  <div class="span3 text-right">
    <?= $sf_user->isOwnerOf($collector) ? link_to('Edit Your Profile →', '@mycq_profile') : '&nbsp;'; ?>
  </div>
</div>

<div id="public-profile-info">
  <div class="row-fluid">
    <div class="span9">
      <div class="row-fluid profile-info">
        <div class="span4 thumbnail" style="margin-top: -55px; background: #fff;">
          <?= image_tag_collector($collector, '235x315', array('max_width' => 138, 'max_height' => 185)) ?>
        </div>
        <div class="span8" style="padding-top: 10px;">
          <?php
            echo sprintf(
              'I am %s <strong>%s</strong> collector',
              in_array(strtolower(substr($profile->getCollectorType(), 0, 1)), array('a', 'e', 'i', 'o')) ? 'an' : 'a',
              $profile->getCollectorType()
            );

            if ($profile->getCountryIso3166())
            {
              echo sprintf(
                ' from %s',
                ($profile->getCountryIso3166() == 'US') ? 'the United States' : $profile->getCountry()
              );
            }
          ?>

          <?php if ($text = $collector->getICollect()): ?>
          <p style="margin-top: 10px;">
            <strong>I collect:</strong>
            <?= $text ?>
          </p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="span3">
      <span class="stat-area spacer-bottom-20" style="padding-bottom: 5px;">
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
        $count = $collector->countCollectiblesInCollections();
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
