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

<?php
  $name = $sf_user->isOwnerOf($collector) ? 'You' : $collector->getDisplayName();
  cq_section_title('More About '. $name);
?>
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
