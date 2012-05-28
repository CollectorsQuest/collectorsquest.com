<?php
/**
 * @var  $collector  Collector
 * @var  $profile    CollectorProfile
 * @var  $sf_user    cqFrontendUser
 */
?>

<div class="blue-actions-panel spacer-bottom-20">
  <div class="row-fluid">
    <div class="pull-left">
      <ul>
        <li>
          <?php
          echo format_number_choice(
            '[0] no views yet|[1] 1 View|(1,+Inf] %1% Views',
            array('%1%' => number_format($profile->getNumViews())), $profile->getNumViews()
          );
          ?>
        </li>
      </ul>
    </div>
    <div class="pull-right share">
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="40"></a>
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
    </div>
  </div>
</div>

<?php if ($about_me || $about_collections || $about_interests): ?>

  <?php cq_section_title('More About '. $collector->getDisplayName()); ?>
  <div class="personal-info-sidebar">
    <?php if ($about_me): ?>
      <p><strong>About me:</strong> <?= $about_me; ?></p>
    <?php endif; ?>
    <?php if ($about_collections): ?>
      <p><strong>My collections:</strong> <?= $about_collections; ?></p>
    <?php endif; ?>
    <?php if ($about_interests): ?>
      <p><strong>My interests:</strong> <?= $about_interests; ?></p>
    <?php endif; ?>
  </div>

<?php else: ?>

<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array('collector' => $collector)
  );
?>

<?php endif; ?>

<?php
//  include_component(
//    '_sidebar', 'widgetCollectorMostWanted',
//    array('collector' => $collector)
//  );
?>

