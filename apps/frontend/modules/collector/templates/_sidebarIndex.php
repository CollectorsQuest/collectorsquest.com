<?php
/**
 * @var  $collector  Collector
 * @var  $profile    CollectorProfile
 */
?>

<div class="statistics-share-panel bottom-margin-double">
  <div class="row-fluid">
    <div class="span5" style="padding-top: 3px;">
      <?php
        echo format_number_choice(
          '[0] no views yet|[1] 1 View|(1,+Inf] %1% Views',
          array('%1%' => number_format($profile->getNumViews())), $profile->getNumViews()
        );
      ?>
    </div>
    <div class="span7 addthis_toolbox addthis_default_style pull-right" style="text-align: right;">
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="40"></a>
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
    </div>
  </div>
</div>

<?php cq_section_title('More About '. $collector->getDisplayName()); ?>
<div class="personal-info-sidebar">
  <?php if ($text = $profile->getProperty('about.me')): ?>
    <p><strong>About me:</strong> <?= $text; ?></p>
  <?php endif; ?>
  <?php if ($text = $profile->getProperty('about.collections')): ?>
    <p><strong>My collections:</strong> <?= $text; ?></p>
  <?php endif; ?>
  <?php if ($text = $profile->getProperty('about.interests')): ?>
    <p><strong>My interests:</strong> <?= $text; ?></p>
  <?php endif; ?>
</div>

<?php
//  include_component(
//    '_sidebar', 'widgetCollectorMostWanted',
//    array('collector' => $collector)
//  );
?>
