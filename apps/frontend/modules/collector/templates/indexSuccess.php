<?php
/**
 * @var $sf_user cqFrontendUser
 * @var $collector Collector
 * @var $profile CollectorProfile
 */
?>
<div class="row-fluid header-bar">
  <div class="span9">
    <h1 class="Chivo webfont" style="margin-left: 145px;" itemprop="name">
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
        <div class="span4 thumbnail profile-avatar">
          <?php
            echo image_tag_collector($collector, '235x315',
              array('max_width' => 138, 'max_height' => 185, 'itemprop' => 'image')
            )
          ?>
        </div>
        <div class="span8 spacer-inner-top" itemprop="jobTitle">
          <?php
            echo sprintf(
              'I am %s <strong>%s</strong> collector',
              in_array(strtolower(substr($profile->getCollectorType(), 0, 1)), array('a', 'e', 'i', 'o')) ? 'an' : 'a',
              $profile->getCollectorType()
            );

            if ($profile->getCountryIso3166())
            {
              echo sprintf(' from <span itemprop="nationality">%s</span>',
                'US' == $profile->getCountryIso3166()
                  ? 'the United States'
                  : $profile->getCountryName()
              );
            }
          ?>

          <?php if (!empty($i_collect_tags)): ?>
          <p class="spacer-top">
            <strong>I collect:</strong>
            <?php
              $i = 1;
              foreach ($i_collect_tags as $tag)
              {
                echo link_to($tag, '@search?q=' . $tag, array(
                       'title' => sprintf('Search for %s in Collectors Quest website!', $tag)
                     ));
                if ($i < count($i_collect_tags)) echo ', ';
                $i++;
              }
            ?>
          </p>
          <?php endif; ?>
          <br /><?php include_component('collector', 'feedbackRate', array('collector' => $collector)) ?>
        </div>
      </div>
    </div>
    <div class="span3">
      <span class="stat-area spacer-bottom-20 spacer-inner-bottom-5">
      <?php
        echo format_number_choice(
          '[0] No <span>COLLECTIONS</span>|[1] 1 <span>COLLECTION</span>|(1,+Inf] %1% <span>COLLECTIONS</span>',
          array('%1%' => number_format($collectionsCount)), $collectionsCount
        );
      ?>
      </span>
      <span class="stat-area spacer-inner-bottom-5">
      <?php
        echo format_number_choice(
          '[0] No <span>COLLECTIBLES</span>|[1] 1 <span>COLLECTIBLE</span>|(1,+Inf] %1% <span>COLLECTIBLES</span>',
          array('%1%' => number_format($collectiblesCount)), $collectiblesCount
        );
      ?>
      </span>
    </div>
  </div>
</div>

<?php include_component('collector', 'indexCollectiblesForSale', array('collector' => $collector)) ?>

<?php include_component('collector', 'indexCollections', array('collector' => $collector)) ?>
